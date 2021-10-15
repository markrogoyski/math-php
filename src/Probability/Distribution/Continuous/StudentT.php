<?php

namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Special;
use MathPHP\Functions\Support;

/**
 * Student's t-distribution
 * https://en.wikipedia.org/wiki/Student%27s_t-distribution
 */
class StudentT extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * ν ∈ (0,∞)
     * @var array
     */
    public const PARAMETER_LIMITS = [
        'ν' => '(0,∞)',
    ];

    /**
     * Distribution support bounds limits
     * t ∈ (-∞,∞)
     * @var array
     */
    public const SUPPORT_LIMITS = [
        't' => '(-∞,∞)',
    ];

    /** @var float Degrees of Freedom Parameter */
    protected $ν;

    /**
     * Constructor
     *
     * @param float $ν degrees of freedom ν > 0
     */
    public function __construct(float $ν)
    {
        parent::__construct($ν);
    }

    /**
     * Probability density function
     *
     *     / ν + 1 \
     *  Γ |  -----  |
     *     \   2   /    /    x² \ ⁻⁽ᵛ⁺¹⁾/²
     *  -------------  | 1 + --  |
     *   __    / ν \    \    ν  /
     *  √νπ Γ |  -  |
     *         \ 2 /
     *
     *
     * @param float $t t score
     *
     * @return float
     */
    public function pdf(float $t): float
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['t' => $t]);

        $ν = $this->ν;
        $π = \M_PI;

        // New Code From R
        $DBL_EPSILON = 2.220446049250313e-16;  // Need to verify
        $tnew = -1 * self::npD0($ν / 2, ($ν + 1) / 2) + Special::stirlingError(($ν + 1) / 2) - Special::stirlingError($ν / 2);
        $x2n = $t**2 / $ν; // in  [0, Inf]
        $ax = 0;
        $lrg_x2n = $x2n > (1 / $DBL_EPSILON);
        if ($lrg_x2n) {
            // large x**2/n :
            $ax = \abs($t);
            $l_x2n = \log($ax) - \log($ν) / 2;
            $u = $ν * $l_x2n;
        } elseif ($x2n > 0.2) {
            $l_x2n = \log(1 + $x2n) / 2;
            $u = $ν * $l_x2n;
        } else {
            $l_x2n = \log1p($x2n) / 2;
            $u = -1* self::npD0($ν / 2, ($ν + $t**2) / 2) + $t**2 / 2;
        }

        $I_sqrt = $lrg_x2n ? \sqrt($ν) / $ax : \exp(-$l_x2n);
        return \exp($tnew - $u) * 1 / \sqrt(2 * $π) * $I_sqrt;
    }

    /**
     * Cumulative distribution function
     * Calculate the cumulative t value up to a point, left tail.
     *
     * cdf = 1 - ½Iₓ₍t₎(ν/2, ½)
     *
     *                 ν
     *  where x(t) = ------
     *               t² + ν
     *
     *        Iₓ₍t₎(ν/2, ½) is the regularized incomplete beta function
     *
     * @param float $t t score
     *
     * @return float
     */
    public function cdf(float $t): float
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['t' => $t]);
        $ν = $this->ν;
        if (\is_infinite($t)) {
            return ($t < 0) ? 0 : 1;
        }
        if (is_infinite($ν)) {
            $norm = new StandardNormal();
            return $norm->cdf($t);
        }
        if ($ν > 4e5) { /*-- Fixme(?): test should depend on `n' AND `x' ! */
            /* Approx. from Abramowitz & Stegun 26.7.8 (p.949) */
            $val = 1 / 4 / $ν;
            $norm = new StandardNormal();
            return $norm->cdf($t*(1 - $val)/sqrt(1 + $t*$t*2*$val));
        }
        $nx = 1 + ($t / $ν) * $t;
        if ($nx > 1e100) { /* <==>  x*x > 1e100 * n  */
            $lval = -0.5 * $ν *(2* \log(\abs($t)) - \log($ν)) - Special::logBeta(0.5 * $ν, 0.5) - \log(0.5 * $ν);
            $val = \exp($lval);
        } else {
            $beta1 = new Beta(.5, $ν / 2);
            $beta2 = new Beta($ν / 2, 0.5);
            $val = ($ν > $t * $t) ? .5 - $beta1->cdf($t * $t / ($ν + $t * $t)) + .5 : $beta2->cdf(1 / $nx);
        }
        $lower_tail = true;
        if ($t <= 0) {
            $lower_tail = false;
        }
        $val /= 2;
        return  $lower_tail ? (0.5 - ($val) + 0.5) : ($val); /* 1 - p */
    }

    /**
     * Inverse 2 tails
     * Find t such that the area greater than t and the area beneath -t is p.
     *
     * @param float $p Proportion of area
     *
     * @return float t-score
     */
    public function inverse2Tails(float $p): float
    {
        Support::checkLimits(['p'  => '[0,1]'], ['p' => $p]);

        return $this->inverse(1 - $p / 2);
    }

    /**
     * Mean of the distribution
     *
     * μ = 0 if ν > 1
     * otherwise undefined
     *
     * @return float
     */
    public function mean(): float
    {
        if ($this->ν > 1) {
            return 0;
        }

        return \NAN;
    }

    /**
     * Median of the distribution
     *
     * μ = 0
     *
     * @return float
     */
    public function median(): float
    {
        return 0;
    }


    /**
     * Mode of the distribution
     *
     * μ = 0
     *
     * @return float
     */
    public function mode(): float
    {
        return 0;
    }

    /**
     * Variance of the distribution
     *
     *        ν
     * σ² = -----    ν > 2
     *      ν - 2
     *
     * σ² = ∞        1 < ν ≤ 2
     *
     * σ² is undefined otherwise
     *
     * @return float
     */
    public function variance(): float
    {
        $ν = $this->ν;

        if ($ν > 2) {
            return $ν / ($ν - 2);
        }

        if ($ν > 1) {
            return \INF;
        }

        return \NAN;
    }

    /**
     * Saddle-point Expansion Deviance
     *
     * Calculate the quantity
     *                                 ∞
     *                                ____
     *                 (x-np)²        \    v²ʲ⁺¹
     * np * D₀(x/np) = ------  + 2*x * >  -------
     *                 (x+np)         /    2*j+1
     * where:                         ____
     *                                j=1
     * D₀(ε) = ε * log(ε) + 1 - ε
     *
     * and:    (x-np)
     *     v = ------
     *         (x+np)
     *
     * Source: https://www.r-project.org/doc/reports/CLoader-dbinom-2002.pdf
     */
    private static function npD0(float $x, float $np)
    {
        $DBL_MIN = 2.23e-308; // Check This
        if (abs($x - $np) < 0.1 * ($x + $np)) {
            $v = ($x - $np) / ($x + $np);
            $s = ($x - $np) * $v;
            if (abs($s) < $DBL_MIN) {
                return $s;
            }
            $Σj = 2 * $x * $v;
            $v² = $v * $v;
            for ($j = 1; $j < 1000; $j++) {
                $Σj *= $v²;
                $stemp = $s;
                $s += $Σj / (($j * 2) + 1);
                if ($s == $stemp) {
                    return $s;
                }
            }
            //MATHLIB_WARNING4("bd0(%g, %g): T.series failed to converge in 1000 it.; s=%g, ej/(2j+1)=%g\n", x, np, s, ej/((1000<<1)+1));
        }
        return ($x * log($x / $np) + $np - $x);
    }
}
