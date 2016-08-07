<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Special;

/**
 * Cauchy distribution
 * https://en.wikipedia.org/wiki/Cauchy_distribution
 */
class Cauchy extends Continuous
{
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
        if ($γ <= 0) {
            throw new \Exception('Scale must be > 0');
        }
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
        if ($γ <= 0) {
            throw new \Exception('Scale must be > 0');
        }
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
        return NULL;
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
}
