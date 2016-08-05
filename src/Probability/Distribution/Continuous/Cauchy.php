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
     *       ┌        / x - x0 \ ² ┐
     *    πγ | 1  +  | ---------|  |
     *       └        \    γ   /   ┘
     *
     * @param number $x
     * @param number $x0 location
     * @param int    $γ  scale
     *
     * @return number
     */
    public static function PDF($x, $x0, $γ)
    {
        if ($ν <= 0) {
            throw new \Exception('Scale must be > 0');
        }
        $π = \M_PI;
        return 1 / ($π * $γ * (1 + (($x - $x0) / $γ) ** 2));
    }
    
    /**
     * Cumulative distribution function
     * Calculate the cumulative value value up to a point, left tail.
     *
     * @param number $x  
     * @param number $x0 location
     * @param int    $γ  scale
     *
     * @return number
     */
    public static function CDF($x, $x0, $γ)
    {
        if ($γ <= 0) {
            throw new \Exception('Scale must be > 0');
        }
        $π = \M_PI;
        return 1 / $π * atan(($x - $x0) / $γ) + .5;
    }
    
    /**
     * Returns the mean of the distribution
     */
    public static function getMean($x0, $γ)
    {
        return NULL;
    }
        
    /**
     * Returns the meadian of the distribution
     */
    public static function getMedian($x0, $γ)
    {
        return $x0;
    }
    
    /**
     * Returns the mode of the distribution
     */
    public static function getMode($x0, $γ)
    {
        return $x0;
    }    
    
}
