<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Support;

/**
 * Standard normal distribution
 * The simplest case of a normal distribution.
 * This is a special case when μ = 0 and σ = 1,
 */
class StandardNormal extends Continuous
{
    /**
     * Mean is always 0
     * @var int
     */
    const μ = 0;

    /**
     * Standard deviation is always 1
     * @var int
     */
    const σ = 1;

    /**
     * Distribution parameter bounds limits
     * z ∈ (-∞,∞)
     * @var array
     */
    const LIMITS = [
        'z' => '(-∞,∞)',
    ];

    /**
     * Probability density function
     *
     * @param number $z random variable
     *
     * @return float f(z|μ,σ)
     */
    public static function pdf($z)
    {
        Support::checkLimits(self::LIMITS, ['z' => $z]);

        return Normal::pdf($z, self::μ, self::σ);
    }

    /**
     * Cumulative distribution function
     * P value for a z score.
     *
     * @param number $z random variable
     *
     * @return float f(z|μ,σ)
     */
    public static function cdf($z)
    {
        Support::checkLimits(self::LIMITS, ['z' => $z]);

        return Normal::cdf($z, self::μ, self::σ);
    }
    
    /**
     * Mean of the distribution
     *
     * μ = 0
     *
     * @return int 0
     */
    public static function mean()
    {
        return 0;
    }
}
