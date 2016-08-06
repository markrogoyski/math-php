<?php
namespace Math\Probability\Distribution\Continuous;

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
     * Probability density function
     *
     * @param number $x random variable
     *
     * @return float f(x|μ,σ)
     */
    public static function PDF($z)
    {
        return Normal::PDF($z, self::μ, self::σ);
    }

    /**
     * Cumulative distribution function
     * P value for a z score.
     *
     * @param number $x random variable
     *
     * @return float f(x|μ,σ)
     */
    public static function CDF($z)
    {
        return Normal::CDF($z, self::μ, self::σ);
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
