<?php
namespace Math\Probability\Distribution\Continuous;

class LogLogistic extends Continuous
{
    /**
     * Log-logistic distribution - probability density function
     * Also known as the Fisk distribution.
     * https://en.wikipedia.org/wiki/Log-logistic_distribution
     *
     *              (β/α)(x/α)ᵝ⁻¹
     * f(x; α, β) = -------------
     *              (1 + (x/α)ᵝ)²
     *
     * @param number $α scale parameter (α > 0)
     * @param number $β shape parameter (β > 0)
     * @param number $x (x > 0)
     */
    public static function PDF($α, $β, $x)
    {
        if ($α <= 0 || $β <= 0 || $x <= 0) {
            throw new \Exception('All parameters must be > 0');
        }
        $⟮β／α⟯⟮x／α⟯ᵝ⁻¹  = ($β / $α) * pow($x / $α, $β - 1);
        $⟮1 ＋ ⟮x／α⟯ᵝ⟯² = pow(1 + ($x / $α)**$β, 2);
        return $⟮β／α⟯⟮x／α⟯ᵝ⁻¹ / $⟮1 ＋ ⟮x／α⟯ᵝ⟯²;
    }
    /**
     * Log-logistic distribution - cumulative distribution function
     * Also known as the Fisk distribution.
     * https://en.wikipedia.org/wiki/Log-logistic_distribution
     *
     *                   1
     * F(x; α, β) = -----------
     *              1 + (x/α)⁻ᵝ
     *
     * @param number $α scale parameter (α > 0)
     * @param number $β shape parameter (β > 0)
     * @param number $x (x > 0)
     */
    public static function CDF($α, $β, $x)
    {
        if ($α <= 0 || $β <= 0 || $x <= 0) {
            throw new \Exception('All parameters must be > 0');
        }
        $⟮x／α⟯⁻ᵝ = pow($x / $α, -$β);
        return 1 / (1 + $⟮x／α⟯⁻ᵝ);
    }
    
    /**
     * Returns the mean of the distribution
     */
    public static function getMean($α, $β)
    {
        $π = \M_PI;
        return $α * $π / $β / sin ($π / $β);
    }
}
