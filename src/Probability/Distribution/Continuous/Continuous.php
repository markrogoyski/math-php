<?php
namespace Math\Probability\Distribution\Continuous;

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
     * @todo check the parameter ranges.
     * @return $number
     */
    public static function inverse($target, ...$params)
    {
        $initial = static::mean(...$params);
        if (is_nan($initial)) {
            $initial = static::median(...$params);
        }
        array_unshift($params, $initial);

        $classname     = get_called_class();
        $CDF_callback  = [$classname, 'CDF'];
        $PDF_callback  = [$classname, 'PDF'];

        $tolerance = .0000000001;
        $dif       = $tolerance + 1;
        $guess     = $params[0];

        while ($dif > $tolerance) {
            // load the guess into the arguments
            $params[0] = $guess;
            $y         = call_user_func_array($CDF_callback, $params);
            
            // Since the CDF is the integral of the PDF, the PDF is the derivative of the CDF
            $slope = call_user_func_array($PDF_callback, $params);
            $del_y = $target - $y;
            $guess = $del_y / $slope + $guess;
            $dif   = abs($del_y);
        }
        return $guess;
    }
  
    /**
     * CDF between - probability of being between two points, x₁ and x₂
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
     * CDF outside - Probability of being below x₁ and above x₂.
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
    
    /**
     * Produce a random number with a particular distribution
     */
    public static function rand(...$params)
    {
        return static::inverse(mt_rand() / mt_getrandmax(), ...$params);
    }
}
