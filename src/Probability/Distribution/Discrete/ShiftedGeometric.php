<?php
namespace MathPHP\Probability\Distribution\Discrete;

use MathPHP\Functions\Support;

/**
 * Shifted geometric distribution
 *
 * The probability distribution of the number X of Bernoulli trials needed
 * to get one success, supported on the set { 1, 2, 3, ...}
 *
 * https://en.wikipedia.org/wiki/Geometric_distribution
 */
class ShiftedGeometric extends Discrete
{
    /**
     * Distribution parameter bounds limits
     * k ∈ [1,∞)
     * p ∈ (0,1]
     * @var array
     */
    const LIMITS = [
        'k' => '[1,∞)',
        'p' => '(0,1]',
    ];

    /**
     * Probability mass function
     *
     * k trials where k ∈ {1, 2, 3, ...}
     *
     * pmf = (1 - p)ᵏ⁻¹p
     *
     * @param  int   $k number of trials     k ≥ 1
     * @param  float $p success probability  0 < p ≤ 1
     *
     * @return float
     */
    public static function pmf(int $k, float $p): float
    {
        Support::checkLimits(self::LIMITS, ['k' => $k, 'p' => $p]);

        $⟮1 − p⟯ᵏ⁻¹ = pow(1 - $p, $k - 1);
        return $⟮1 − p⟯ᵏ⁻¹ * $p;
    }

    /**
     * Cumulative distribution function
     *
     * k trials where k ∈ {0, 1, 2, 3, ...}
     *
     * pmf = 1 - (1 - p)ᵏ
     *
     * @param  int   $k number of trials     k ≥ 0
     * @param  float $p success probability  0 < p ≤ 1
     *
     * @return float
     */
    public static function cdf(int $k, float $p): float
    {
        Support::checkLimits(self::LIMITS, ['k' => $k, 'p' => $p]);

        $⟮1 − p⟯ᵏ = pow(1 - $p, $k);
        return 1 - $⟮1 − p⟯ᵏ;
    }
}
