<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Special;
use Math\Functions\Support;

/**
 * Cauchy distribution
 * https://en.wikipedia.org/wiki/Cauchy_distribution
 */
class Cauchy extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * p  ∈ [0,1]
     * x  ∈ (-∞,∞)
     * x₀ ∈ (-∞,∞)
     * γ  ∈ (0,∞)
     * @var array
     */
    const LIMITS = [
        'x'  => '(-∞,∞)',
        'x₀' => '(-∞,∞)',
        'γ'  => '(0,∞)',
        'p'  => '[0,1]',
    ];

    /**
     * Probability density function
     *
     *                1
     *    --------------------------
     *       ┌        / x - x₀ \ ² ┐
     *    πγ | 1  +  | ---------|  |
     *       └        \    γ   /   ┘
     *
     * @param number $x
     * @param number $x₀ location
     * @param int    $γ  scale
     *
     * @return number
     */
    public static function PDF($x, $x₀, $γ)
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'x₀' => $x₀, 'γ' => $γ]);

        $π = \M_PI;
        return 1 / ($π * $γ * (1 + (($x - $x₀) / $γ) ** 2));
    }
    
    /**
     * Cumulative distribution function
     * Calculate the cumulative value value up to a point, left tail.
     *
     * @param number $x
     * @param number $x₀ location
     * @param int    $γ  scale
     *
     * @return number
     */
    public static function CDF($x, $x₀, $γ)
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'x₀' => $x₀, 'γ' => $γ]);

        $π = \M_PI;
        return 1 / $π * atan(($x - $x₀) / $γ) + .5;
    }
    
    /**
     * Mean of the distribution (undefined)
     *
     * μ is undefined
     *
     * @param number $x₀ location
     * @param int    $γ  scale
     *
     * @return null
     */
    public static function mean($x₀, $γ)
    {
        return \NAN;
    }
        
    /**
     * Meadian of the distribution
     *
     * @param number $x₀ location
     * @param int    $γ  scale
     *
     * @return x₀
     */
    public static function median($x₀, $γ)
    {
        return $x₀;
    }
    
    /**
     * Mode of the distribution
     *
     * @param number $x₀ location
     * @param int    $γ  scale
     *
     * @return x₀
     */
    public static function mode($x₀, $γ)
    {
        return $x₀;
    }
    
    /**
     * Inverse CDF (Quantile function)
     *
     * Q(p;x₀,γ) = x₀ + γ tan[π(p - ½)]
     *
     * @param number $p
     * @param number $x₀
     * @param number $γ
     *
     * @return number
     */
    public static function inverse($p, ...$params)
    {
        $x₀ = $params[0];
        $γ  = $params[1];
        Support::checkLimits(self::LIMITS, ['x₀' => $x₀, 'γ' => $γ, 'p' => $p]);

        $π = \M_PI;

        return $x₀ + $γ * tan($π * ($p - .5));
    }
}
