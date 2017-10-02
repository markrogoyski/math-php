<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Support;
use MathPHP\Exception;

abstract class Continuous extends \MathPHP\Probability\Distribution\Distribution
{
    const LIMITS = [];

    protected $params;
    
    public function __construct(...$params)
    {
        $new_params = static::PARAMETER_LIMITS;
        $i = 0;
        foreach ($new_params as $key => $value) {
            $this->$key = $params[$i];
            $new_params[$key] = $params[$i];
            $i++;
        }
        Support::checkLimits(static::PARAMETER_LIMITS, $new_params);
    }
    
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

        $tolerance = .0000000001;
        $dif       = $tolerance + 1;
        $guess     = $params[0];

        while ($dif > $tolerance) {
            // load the guess into the arguments
            $params[0] = $guess;
            $y         = static::cdf(...$params);
            
            // Since the CDF is the integral of the PDF, the PDF is the derivative of the CDF
            $slope = static::pdf(...$params);
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
        $upper_area = static::cdf($x₂, ...$params);
        $lower_area = static::cdf($x₁, ...$params);
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
        return 1 - static::cdf($x, ...$params);
    }
    
    /**
     * Produce a random number with a particular distribution
     */
    public static function rand(...$params)
    {
        return static::inverse(random_int(0, \PHP_INT_MAX) / \PHP_INT_MAX, ...$params);
    }
}
