<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Special;
use Math\Functions\Support;

/**
 * Normal distribution
 * https://en.wikipedia.org/wiki/Normal_distribution
 */
class Normal extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x ∈ (-∞,∞)
     * μ ∈ (-∞,∞)
     * σ ∈ (0,∞)
     * @var array
     */
    const LIMITS = [
        'x' => '(-∞,∞)',
        'μ' => '(-∞,∞)',
        'σ' => '(0,∞)',
    ];

    /**
     * Probability density function
     *
     *              1
     * f(x|μ,σ) = ----- ℯ^−⟮x − μ⟯²∕2σ²
     *            σ√⟮2π⟯
     *
     * @param number $x random variable
     * @param number $μ mean
     * @param number $σ standard deviation
     *
     * @return float f(x|μ,σ)
     */
    public static function PDF($x, $μ, $σ): float
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'μ' => $μ, 'σ' => $σ]);

        $π     = \M_PI;
        $σ√⟮2π⟯ = $σ * sqrt(2 * $π);

        $⟮x − μ⟯²∕2σ² = pow(($x - $μ), 2) / (2 * $σ**2);

        $ℯ＾−⟮x − μ⟯²∕2σ² = exp(-$⟮x − μ⟯²∕2σ²);

        return ( 1 / $σ√⟮2π⟯ ) * $ℯ＾−⟮x − μ⟯²∕2σ²;
    }
  
    /**
     * Cumulative distribution function
     * Probability of being below X.
     * Area under the normal distribution from -∞ to X.
     *             _                  _
     *          1 |         / x - μ \  |
     * cdf(x) = - | 1 + erf|  ----- |  |
     *          2 |_        \  σ√2  / _|
     *
     * @param number $x upper bound
     * @param number $μ mean
     * @param number $σ standard deviation
     *
     * @return float cdf(x) below
     */
    public static function CDF($x, $μ, $σ): float
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'μ' => $μ, 'σ' => $σ]);

        return 1/2 * ( 1 + Special::erf(($x - $μ) / ($σ * sqrt(2))) );
    }
    
    /**
     * Mean of the distribution
     *
     * μ = μ
     *
     * @param number $μ mean
     * @param number $σ standard deviation
     *
     * @return number
     */
    public static function mean($μ, $σ)
    {
        Support::checkLimits(self::LIMITS, ['μ' => $μ, 'σ' => $σ]);

        return $μ;
    }
}
