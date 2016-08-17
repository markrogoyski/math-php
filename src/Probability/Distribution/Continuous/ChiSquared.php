<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Special;
use Math\Functions\Support;

/**
 * χ²-distribution (Chi-squared)
 * https://en.wikipedia.org/wiki/Chi-squared_distribution
 */
class ChiSquared extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x ∈ [0,∞)
     * k ∈ [1,∞)
     * @var array
     */
    const LIMITS = [
        'x' => '[0,∞)',
        'k' => '[1,∞)',
    ];

    /**
     * Probability density function
     *
     *                 1
     *           -------------- x⁽ᵏ/²⁾⁻¹ ℯ⁻⁽ˣ/²⁾
     *  χ²(k) =          / k \
     *           2ᵏ/² Γ |  -  |
     *                   \ 2 /
     *
     * @param number $x point at which to evaluate > 0
     * @param int    $k degrees of freedom > 0
     *
     * @return number probability
     */
    public static function PDF($x, int $k)
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'k' => $k]);

        // Numerator
        $x⁽ᵏ／²⁾⁻¹ = $x**(($k / 2) - 1);
        $ℯ⁻⁽ˣ／²⁾  = exp(-($x / 2));

        // Denominator
        $２ᵏ／²  = 2**($k / 2);
        $Γ⟮k／2⟯ = Special::Γ($k / 2);

        return ($x⁽ᵏ／²⁾⁻¹ * $ℯ⁻⁽ˣ／²⁾) / ($２ᵏ／² * $Γ⟮k／2⟯);
    }

    /**
     * Cumulative distribution function
     *
     * Cumulative t value up to a point, left tail.
     *
     *          / k   x  \
     *       γ |  - , -  |
     *          \ 2   2 /
     * CDF = -------------
     *            / k \
     *         Γ |  -  |
     *            \ 2 /
     *
     * @param number $x Chi-square critical value (CV) > 0
     * @param int    $k degrees of freedom > 0
     *
     * @return number cumulative probability
     */
    public static function CDF($x, int $k)
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'k' => $k]);

        // Numerator
        $γ⟮k／2、x／2⟯ = Special::γ($k / 2, $x / 2);

        // Demoninator
        $Γ⟮k／2⟯ = Special::Γ($k / 2);

        return $γ⟮k／2、x／2⟯ / $Γ⟮k／2⟯;
    }
    
    /**
     * Mean of the distribution
     *
     * μ = k
     *
     * @param int $k degrees of freedom > 0
     *
     * @return int k
     */
    public static function mean(int $k)
    {
        Support::checkLimits(self::LIMITS, ['k' => $k]);

        return $k;
    }
}
