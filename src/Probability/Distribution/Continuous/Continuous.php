<?php
namespace Math\Probability\Distribution\Continuous;

use Math\NumericalAnalysis\NewtonsMethod;

abstract class Continuous
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
    
    /**
     * Checks that the values of the distribution parameters fall within the defined bounds.
     * The parameter limits are defined using ISO 31-11 notation.
     * https://en.wikipedia.org/wiki/ISO_31-11
     *
     *  (a,b) = a <  x <  b
     *  [a,b) = a <= x <  b
     *  (a,b] = a <  x <= b
     *  [a,b] = a <= x <= b
     *
     * @param array $limits Boundary limit definitions for each parameter
     *                      ['var1' => limit, 'var2' => limit, ...]
     * @param array $params Parameters and their value to check against the defined limits
     *                      ['var1' => value, 'var2' => value, ...]
     *
     * @return bool True if all parameters are within defined limits
     *
     * @throws \Exception if any parameter is outside the defined limits
     */
    public static function checkLimits(array $limits, array $params)
    {
        foreach ($params as $variable => $value)
        {
            // Remove the first character: ( or [
            $lower_endpoint = substr($limits[$variable], 0, 1);
            
            // Remove the last character: ) or ]
            $upper_endpoint = substr($limits[$variable], -1, 1);
            
            // Set the lower and upper limits: #,#
            list($lower_limit, $upper_limit) = explode(',', substr($limits[$variable], 1, -1));
            
            // If the lower limit is -∞, we are always in bounds.
            if ($lower_limit != "-∞") {
                switch ($lower_endpoint) {
                    case '(':
                        if ($value <= $lower_limit) {
                            throw new \Exception("{$variable} must be > {$lower_limit}");
                        }
                        break;
                    case '[':
                        if ($value < $lower_limit) {
                            throw new \Exception("{$variable} must be >= {$lower_limit}");
                        }
                        break;
                    default:
                        throw new \Exception("Unknown lower endpoint character: {$lower_limit}");
                }
            }
            
            // If the upper limit is ∞, we are always in bounds.
            if ($upper_limit != "∞") {           
                switch ($upper_endpoint) {
                    case ')':
                        if ($value >= $upper_limit) {
                            throw new \Exception("{$variable} must be < {$upper_limit}");
                        }
                        break;
                    case ']':
                        if ($value > $upper_limit) {
                            throw new \Exception("{$variable} must be <= {$upper_limit}");
                        }
                        break;
                    default:
                        throw new \Exception("Unknown upper endpoint character: {$upper_endpoint}");
                }
            }
        }

        return true;
    }
}
