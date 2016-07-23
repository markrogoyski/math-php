<?php
namespace Math\Probability\Distribution\Discrete;

class ShiftedGeometric extends Discrete
{
    /**
     * Shifted geometric distribution - probability mass function
     * 
     * The probability distribution of the number X of Bernoulli trials needed
     * to get one success, supported on the set { 1, 2, 3, ...}
     * https://en.wikipedia.org/wiki/Geometric_distribution
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
    public static function PMF(int $k, float $p): float
    {
        if ($k < 1) {
            throw new \Exception('k must be an int ≥ 1');
        }
        if ($p <= 0 || $p > 1) {
            throw new \Exception('p must be 0 < p ≤ 1');
        }

        $⟮1 − p⟯ᵏ⁻¹ = pow(1 - $p, $k - 1);
        return $⟮1 − p⟯ᵏ⁻¹ * $p;
    }

    /**
     * Shifted geometric distribution - cumulative distribution function
     *
     * The probability distribution of the number X of Bernoulli trials needed
     * to get one success, supported on the set { 1, 2, 3, ...}
     * https://en.wikipedia.org/wiki/Geometric_distribution
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
    public static function CDF(int $k, float $p): float
    {
        if ($k < 0) {
            throw new \Exception('k must be an int ≥ 0');
        }
        if ($p <= 0 || $p > 1) {
            throw new \Exception('p must be 0 < p ≤ 1');
        }

        $⟮1 − p⟯ᵏ = pow(1 - $p, $k);
        return 1 - $⟮1 − p⟯ᵏ;
    }
}
