<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Support;

/**
 * Exponental distribution
 * https://en.wikipedia.org/wiki/Exponential_distribution
 */
class Exponential extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x ∈ [0,∞)
     * λ ∈ (0,∞)
     * @var array
     */
    const LIMITS = [
        'x' => '[0,∞)',
        'λ' => '(0,∞)',
    ];

    /**
     * Probability density function
     *
     * f(x;λ) = λℯ^⁻λx  x ≥ 0
     *        = 0       x < 0
     *
     * @param float $x the random variable
     * @param float $λ often called the rate parameter
     *
     * @return float
     */
    public static function pdf(float $x, float $λ): float
    {
        if ($x < 0) {
            return 0;
        }
        Support::checkLimits(self::LIMITS, ['x' => $x, 'λ' => $λ]);

        return $λ * exp(-$λ * $x);
    }
    /**
     * Cumulative distribution function
     *
     * f(x;λ) = 1 − ℯ^⁻λx  x ≥ 0
     *        = 0          x < 0
     *
     * @param float $λ often called the rate parameter
     * @param float $x the random variable
     *
     * @return float
     */
    public static function cdf(float $x, float $λ): float
    {
        if ($x < 0) {
            return 0;
        }
        Support::checkLimits(self::LIMITS, ['x' => $x, 'λ' => $λ]);

        return 1 - exp(-$λ * $x);
    }
    
    /**
     * Mean of the distribution
     *
     * μ = λ⁻¹
     *
     * @param float $λ often called the rate parameter
     *
     * @return number
     */
    public static function mean(float $λ)
    {
        Support::checkLimits(self::LIMITS, ['λ' => $λ]);

        return 1 / $λ;
    }
}
