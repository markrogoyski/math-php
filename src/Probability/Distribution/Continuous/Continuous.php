<?php
namespace Math\Probability\Distribution\Continuous;

use Math\NumericalAnalysis\NewtonsMethod;

abstract class Continuous extends \Math\Probability\Distribution\Distribution
{
    /**
     * The Inverse CDF of the distribution
     *
     * For example, if the calling class CDF definition is CDF($x, $d1, $d2)
     * than the inverse is called as inverse($target, $d1, $d2)
     *
     * @param number $target   The area for which we are trying to find the $x
     * @param array ...$params List of all the parameters that are needed for the CDF of the
     *   calling class. This list must be absent the $x parameter.
     *
     * @return $number
     */
    public static function inverse($target, ...$params)
    {
        array_unshift($params, 0.5);
        $classname = get_called_class();
        $callback  = [$classname, 'CDF'];
        $tolerance = .00000000000001;
        $position  = 0;
        return NewtonsMethod::solve($callback, $params, $target, $tolerance, $position);
    }
  
    /**
     * CDF between - probability of being bewteen two points, x₁ and x₂
     * The area under a continuous distribution, that lies between two specified points.
     *
     * P(between) = CDF($x₂) - CDF($x₁)
     *
     * @param number $x₁ Lower bound
     * @param number $x₂ Upper bound
     * @param array  ...$params Remaining parameters for each distribution
     *
     * @return number
     */
    public static function between($x₁, $x₂, ...$params)
    {
        $upper_area = static::CDF($x₂, ...$params);
        $lower_area = static::CDF($x₁, ...$params);
        return $upper_area - $lower_area;
    }
  
    /**
     * CDF outside - Probability of being bewteen below x₁ and above x₂.
     * The area under a continuous distribution, that lies above and below two points.
     *
     * P(outside) = 1 - P(between) = 1 - (CDF($x₂) - CDF($x₁))
     *
     * @param number $x₁ Lower bound
     * @param number $x₂ Upper bound
     * @param array  ...$params Remaining parameters for each distribution
     *
     * @return number
     */
    public static function outside($x₁, $x₂, ...$params)
    {
        return 1 - self::between($x₁, $x₂, ...$params);
    }

    /**
     * CDF above - Probability of being above x to ∞
     * Area under a continuous distribution, that lies above a specified point.
     *
     * P(above) = 1 - CDF(x)
     *
     * @param number $x
     * @param array  ...$params Remaining parameters for each distribution
     *
     * @return number
     */
    public static function above($x, ...$params)
    {
        return 1 - static::CDF($x, ...$params);
    }
}
