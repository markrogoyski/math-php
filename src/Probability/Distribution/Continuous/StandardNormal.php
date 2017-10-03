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
     * Distribution support bounds limits
     * z ∈ (-∞,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'z' => '(-∞,∞)',
    ];

    /**
     * Distribution parameter bounds limits
     * @var array
     */
    const PARAMETER_LIMITS = [];

    /**
     * Probability density function
     *
     * @param number $z random variable
     *
     * @return float f(z|μ,σ)
     */
    public function pdf($z)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['z' => $z]);

        $normal = new Normal(self::μ, self::σ);
        return $normal->pdf($z);
    }

    /**
     * Cumulative distribution function
     * P value for a z score.
     *
     * @param number $z random variable
     *
     * @return float f(z|μ,σ)
     */
    public function cdf($z)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['z' => $z]);

        $normal = new Normal(self::μ, self::σ);
        return $normal->cdf($z);
    }
    
    /**
     * Mean of the distribution
     *
     * μ = 0
     *
     * @return int 0
     */
    public function mean()
    {
        return self::μ;
    }
}
