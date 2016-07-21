<?php
namespace Math\Probability\Distribution

class ExponentialDistribution extends ContinuousNew {
    /**
     * Exponential distribution - probability density function
     * https://en.wikipedia.org/wiki/Exponential_distribution
     *
     * f(x;λ) = λℯ^⁻λx  x ≥ 0
     *        = 0       x < 0
     *
     * @param float $λ often called the rate parameter
     * @param float $x the random variable
     *
     * @return float
     */
    public static function PDF(float $λ, float $x): float
    {
        if ($x < 0) {
            return 0;
        }
        return $λ * exp(-$λ * $x);
    }
    /**
     * Cumulative exponential distribution - cumulative distribution function
     * https://en.wikipedia.org/wiki/Exponential_distribution
     *
     * f(x;λ) = 1 − ℯ^⁻λx  x ≥ 0
     *        = 0          x < 0
     *
     * @param float $λ often called the rate parameter
     * @param float $x the random variable
     *
     * @return float
     */
    public static function CDF(float $λ, float $x): float
    {
        if ($x < 0) {
            return 0;
        }
        return 1 - exp(-$λ * $x);
    }
}
