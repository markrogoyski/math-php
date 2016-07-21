<?php
namespace Math\Probability\Distribution;
//use Math\Solver\Newton;
abstract class ContinuousNew extends Distribution {
  /**
   * The Probability Density Function
   *
   * https://en.wikipedia.org/wiki/Probability_density_function
   */
  static function PDF(){}
  /**
   * The Cumulative Distribution Function
   *
   * All children should define their own CDF, ensuring that the $x parameter
   * is first in the parmeter list. This is neeed for the default inverse
   * function to correctly work.
   *
   * https://en.wikipedia.org/wiki/Cumulative_distribution_function
   */
  static function CDF(){}
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
  static function inverse($target, ...$params){
    //$params = array_merge (['x'], $params);
    //$classname = get_called_class();
    //$callback = [$classname, 'CDF'];
    //return Newton::solve($callback, $params, $target, .5, .00000000000001);
  }
  
  /**
   * The area under a continuous distribution, that lies between two specified points
   */
  static function between($upper, $lower, ...$params){
    $function = [get_called_class(), 'CDF'];
    $upper_area = call_user_func_array($function, array_merge([$upper], $params));
    $lower_area = call_user_func_array($function, array_merge([$lower], $params));
    return $upper_area - $lower_area;
  }
  
  /**
   * The area under a continuous distribution, that lies above a specified point
   *  returns 1-CDF(x)
   */
  static function above($x, ...$params){
    $function = [get_called_class(), 'CDF'];
    $area = call_user_func_array($function, array_merge([$x], $params));
    return 1 - $area;
  }
}
