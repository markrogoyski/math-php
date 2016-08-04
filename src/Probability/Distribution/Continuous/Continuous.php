<?php
namespace Math\Probability\Distribution\Continuous;

use Math\NumericalAnalysis\NewtonsMethod;

abstract class Continuous
{
    /**
     * The Inverse CDF of the distribution
     *
     * $target - The area for which we are trying to find the $x
     * $params - a list of all the parameters that are needed for the CDF of the
     *   calling class. This list must be absent the $x parameter.
     *
     * For example, if the calling class CDF definition is CDF($x, $d1, $d2)
     * than the inverse is called as inverse($target, $d1, $d2)
     *
     */
    public static function inverse($target, ...$params)
    {
        $params    = array_merge([.5], $params);
        $classname = get_called_class();
        $callback  = [$classname, 'CDF'];
        return NewtonsMethod::solve($callback, $params, $target, 0, .00000000000001);
    }
  
    /**
     * The area under a continuous distribution, that lies between two specified points
     */
    public static function between($upper, $lower, ...$params)
    {
        $function   = [get_called_class(), 'CDF'];
        $upper_area = call_user_func_array($function, array_merge([$upper], $params));
        $lower_area = call_user_func_array($function, array_merge([$lower], $params));
        return $upper_area - $lower_area;
    }
  
    /**
     * The area under a continuous distribution, that lies above and below two points
     */
    public static function outside($upper, $lower, ...$params)
    {
        return 1 - self::between($upper, $lower, ...$params);
    }

    /**
     * The area under a continuous distribution, that lies above a specified point
     *  returns 1-CDF(x)
     */
    public static function above($x, ...$params)
    {
        $function = [get_called_class(), 'CDF'];
        $area     = call_user_func_array($function, array_merge([$x], $params));
        return 1 - $area;
    }
}
