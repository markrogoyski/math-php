<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Special;
use Math\Functions\Support;

/**
 * Student's t-distribution
 * https://en.wikipedia.org/wiki/Student%27s_t-distribution
 */
class StudentT extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x ∈ (-∞,∞)
     * ν ∈ (0,∞)
     * t ∈ (-∞,∞)
     * @var array
     */
    const LIMITS = [
        'x' => '(-∞,∞)',
        'ν' => '(0,∞)',
        't' => '(-∞,∞)',
    ];

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
     * @param number $x percentile
     * @param int    $ν degrees of freedom > 0
     */
    public static function PDF($x, int $ν)
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'ν' => $ν]);

        $π = \M_PI;

        // Numerator
        $Γ⟮⟮ν＋1⟯∕2⟯ = Special::gamma(($ν + 1) / 2);
        $⟮1＋x²∕ν⟯ = 1 + ($x**2 / $ν);
        $−⟮ν＋1⟯∕2 = -($ν + 1) / 2;

        // Denominator
        $√⟮νπ⟯  = sqrt($ν * $π);
        $Γ⟮ν∕2⟯ = Special::gamma($ν / 2);
        
        return ($Γ⟮⟮ν＋1⟯∕2⟯ * $⟮1＋x²∕ν⟯**$−⟮ν＋1⟯∕2) / ($√⟮νπ⟯ * $Γ⟮ν∕2⟯);
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
     * @param number $t t score
     * @param int    $ν degrees of freedom > 0
     */
    public static function CDF($t, int $ν)
    {
        Support::checkLimits(self::LIMITS, ['t' => $t, 'ν' => $ν]);

        if ($t == 0) {
            return .5;
        }

        $x⟮t⟯  = $ν / ($t**2 + $ν);
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
     * @param number $p Proportion of area
     * @param number $ν Degrees of freedom
     *
     * @return number t-score
     */
    public static function inverse2Tails($p, $ν)
    {
        Support::checkLimits(self::LIMITS, ['ν' => $ν]);
        return self::inverse(1 - $p / 2, $ν);
    }
    
    /**
     * Mean of the distribution
     *
     * μ = 0 if ν > 1
     * otherwise undefined
     *
     * @param number $ν Degrees of freedom
     *
     * @return number
     */
    public static function mean($ν)
    {
        Support::checkLimits(self::LIMITS, ['ν' => $ν]);
        if ($ν > 1) {
            return 0;
        }

        return \NAN;
    }
    
    /**
     * Median of the distribution
     *
     * μ = 0
     *
     * @param number $ν Degrees of freedom
     *
     * @return number
     */
    public static function median($ν)
    {
        Support::checkLimits(self::LIMITS, ['ν' => $ν]);
        return 0;
    }
}
