<?php
namespace Math\Probability\Distribution\Discrete;

use Math\Functions\Support;

class Geometric extends Discrete
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
     * Geometric distribution - probability mass function
     *
     * The probability distribution of the number Y = X − 1 of failures
     * before the first success, supported on the set { 0, 1, 2, 3, ... }
     * https://en.wikipedia.org/wiki/Geometric_distribution
     *
     * k failures where k ∈ {0, 1, 2, 3, ...}
     *
     * pmf = (1 - p)ᵏp
     *
     * @param  int   $k number of trials     k ≥ 1
     * @param  float $p success probability  0 < p ≤ 1
     *
     * @return float
     */
    public static function PMF(int $k, float $p): float
    {
        Support::checkLimits(self::LIMITS, ['k' => $k, 'p' => $p]);

        $⟮1 − p⟯ᵏ = pow(1 - $p, $k);
        return $⟮1 − p⟯ᵏ * $p;
    }

    /**
     * Geometric distribution - cumulative distribution function (lower cumulative)
     *
     * The probability distribution of the number Y = X − 1 of failures
     * before the first success, supported on the set { 0, 1, 2, 3, ... }
     * https://en.wikipedia.org/wiki/Geometric_distribution
     *
     * k failures where k ∈ {0, 1, 2, 3, ...}
     *
     * pmf = 1 - (1 - p)ᵏ⁺¹
     *
     * @param  int   $k number of trials     k ≥ 0
     * @param  float $p success probability  0 < p ≤ 1
     *
     * @return float
     */
    public static function CDF(int $k, float $p): float
    {
        Support::checkLimits(self::LIMITS, ['k' => $k, 'p' => $p]);

        $⟮1 − p⟯ᵏ⁺¹ = pow(1 - $p, $k + 1);
        return 1 - $⟮1 − p⟯ᵏ⁺¹;
    }
}
