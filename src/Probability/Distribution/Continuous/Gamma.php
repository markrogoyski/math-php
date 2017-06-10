<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Special;
use MathPHP\Functions\Support;

/**
 * Gamma distribution
 * https://en.wikipedia.org/wiki/Gamma_distribution
 */
class Gamma extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x ∈ (0,∞)
     * k ∈ (0,∞)
     * θ ∈ (0,∞)
     * @var array
     */
    const LIMITS = [
        'x' => '(0,∞)',
        'k' => '(0,∞)',
        'θ' => '(0,∞)',
    ];

    /**
     * Probability density function
     *
     *                     ₓ
     *          1         ⁻-
     * pdf = ------ xᵏ⁻¹ e θ
     *       Γ(k)θᵏ
     *
     * @param number $x percentile      x > 0
     * @param number $k shape parameter k > 0
     * @param number $θ scale parameter θ > 0
     *
     * @return float
     */
    public static function pdf($x, $k, $θ): float
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'k' => $k, 'θ' => $θ]);

        $Γ⟮k⟯   = Special::Γ($k);
        $θᵏ    = $θ**$k;
        $Γ⟮k⟯θᵏ = $Γ⟮k⟯ * $θᵏ;

        $xᵏ⁻¹ = $x**($k - 1);
        $e    = \M_E**(-$x / $θ);

        return ($xᵏ⁻¹ * $e) / $Γ⟮k⟯θᵏ;
    }

    /**
     * Cumulative distribution function
     *
     *         1      /   x \
     * cdf = ----- γ | k, -  |
     *       Γ(k)     \   θ /
     *
     * @param number $x percentile      x > 0
     * @param number $k shape parameter k > 0
     * @param number $θ scale parameter θ > 0
     *
     * @return float
     */
    public static function cdf($x, $k, $θ): float
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'k' => $k, 'θ' => $θ]);

        $Γ⟮k⟯ = Special::Γ($k);
        $γ   = Special::γ($k, $x / $θ);

        return $γ / $Γ⟮k⟯;
    }
}
