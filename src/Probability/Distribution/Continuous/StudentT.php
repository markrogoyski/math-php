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
     * Rearranging the equation above and using Stirling approximation
     * along with the saddlepoint expansion gives the following form:
     *
     * T = eᵗ⁻ᵘ * 1/√𝜏 * 1/√(1+x²/ν)
     * Where t = npD₀(-ν/2, (ν+1)/2) + δ((ν+1)/2) - δ(ν/2)
     * and u = ν/2 * log(1+x2⁄ν) = -npD₀(ν/2, (ν+x²)/2) + x²/2
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
        $tnew = -1 * self::npD0($ν / 2, ($ν + 1) / 2) + self::stirlerr(($ν + 1) / 2) - self::stirlerr($ν / 2);
        $x2n = $t**2 / $ν; // in  [0, Inf]
        $ax = 0;
        $lrg_x2n = $x2n > (1 / $DBL_EPSILON);
        if ($lrg_x2n) {
            // large x**2/n :
            $ax = abs($t);
            $l_x2n = log($ax) - log($ν) / 2;
            $u = $ν * $l_x2n;
        } elseif ($x2n > 0.2) {
            $l_x2n = log(1 + $x2n) / 2;
            $u = $ν * $l_x2n;
        } else {
            $l_x2n = log1p($x2n) / 2;
            $u = -1* self::npD0($ν / 2, ($ν + $t**2) / 2) + $t**2 / 2;
        }

        $I_sqrt = $lrg_x2n ? sqrt($ν) / $ax : exp(-$l_x2n);
        return exp($tnew - $u) * 1 / sqrt(2 * $π) * $I_sqrt;
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
        if ($t == 0) {
            return .5;
        }

        $x⟮t⟯  = $ν / ($t ** 2 + $ν);
        $ν／2 = $ν / 2;
        $½    = .5;
        $Iₓ   = Special::regularizedIncompleteBeta($x⟮t⟯, $ν／2, $½);

        if ($t < 0) {
            return $½ * $Iₓ;
        }

        // $t ≥ 0
        return 1 - $½ * $Iₓ;
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

    /**
     * The log of the error term in the Stirling-De Moivre factorial series
     *
     * log(n!) = .5*log(2πn) + n*log(n) - n + δ(n)
     * Where δ(n) is the log of the error.
     *
     * For n <=15, integers or half-integers, uses stored values.
     *
     * @param float $n
     *
     * @return float log of the error
     */
    private static function stirlerr(float $n)
    {
        $S0 = 0.083333333333333333333;        // 1/12
        $S1 = 0.00277777777777777777778;      // 1/360
        $S2 = 0.00079365079365079365079365;   // 1/1260
        $S3 = 0.000595238095238095238095238;  // 1/1680
        $S4 = 0.0008417508417508417508417508; // 1/1188

        $sferr_halves = [
            0.0, /* n=0 - wrong, place holder only */
            0.1534264097200273452913848,  /* 0.5 */
            0.0810614667953272582196702,  /* 1.0 */
            0.0548141210519176538961390,  /* 1.5 */
            0.0413406959554092940938221,  /* 2.0 */
            0.03316287351993628748511048, /* 2.5 */
            0.02767792568499833914878929, /* 3.0 */
            0.02374616365629749597132920, /* 3.5 */
            0.02079067210376509311152277, /* 4.0 */
            0.01848845053267318523077934, /* 4.5 */
            0.01664469118982119216319487, /* 5.0 */
            0.01513497322191737887351255, /* 5.5 */
            0.01387612882307074799874573, /* 6.0 */
            0.01281046524292022692424986, /* 6.5 */
            0.01189670994589177009505572, /* 7.0 */
            0.01110455975820691732662991, /* 7.5 */
            0.010411265261972096497478567, /* 8.0 */
            0.009799416126158803298389475, /* 8.5 */
            0.009255462182712732917728637, /* 9.0 */
            0.008768700134139385462952823, /* 9.5 */
            0.008330563433362871256469318, /* 10.0 */
            0.007934114564314020547248100, /* 10.5 */
            0.007573675487951840794972024, /* 11.0 */
            0.007244554301320383179543912, /* 11.5 */
            0.006942840107209529865664152, /* 12.0 */
            0.006665247032707682442354394, /* 12.5 */
            0.006408994188004207068439631, /* 13.0 */
            0.006171712263039457647532867, /* 13.5 */
            0.005951370112758847735624416, /* 14.0 */
            0.005746216513010115682023589, /* 14.5 */
            0.005554733551962801371038690, /* 15.0 */
        ];

        if ($n <= 15.0) {
            $nn = $n + $n;
            //if ($nn == (int)$nn) {
            return $sferr_halves[$nn];
            //}
            //$M_LN_SQRT_2PI = log(sqrt(2 * \pi()));
            //return self::lgammafn($n + 1) - ($n + 0.5) * log($n) + $n - $M_LN_SQRT_2PI;
        }

        $nn = $n * $n;
        if ($n > 500) {
            return ($S0 - $S1 / $nn) / $n;
        }
        if ($n > 80) {
            return ($S0 - ($S1 - $S2 / $nn) / $nn) / $n;
        }
        if ($n > 35) {
            return ($S0 - ($S1 - ($S2 - $S3/$nn)/$nn)/$nn)/$n;
        }
        /* 15 < n <= 35 : */
        return ($S0-($S1-($S2-($S3-$S4/$nn)/$nn)/$nn)/$nn)/$n;
    }
}
