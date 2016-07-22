<?php
namespace Math\NumericalAnalysis;
class NewtonsMethod{
  /**
   * Use Newton's Method to find the x which produces $target=$function(x) value
   * $args is an array of parameters to pass to $function, but having the string
   * 'x' in the position of interest.
   */
  public static function solve($function, $args, $target, $guess, $tol){
    $dif = $tol + 1;  //initialize
    $position = array_search('x', $args); //find the position of 'x' in the arguments
    $args1 = $args;
    
    while ($dif > $tol){
      $args1[$position] = $guess + $tol; //load the initial guess into the arguments
      $args[$position] = $guess; //load the initial guess into the arguments
      $y = call_user_func_array($function, $args);
      $y_at_xplusdelx = call_user_func_array($function, $args1);
      $slope = ($y_at_xplusdelx - $y)/ $tol;
      $del_y = $target - $y;
      $guess = $del_y/$slope + $guess;
      $dif = abs($del_y);
    }
    return $guess;
  } 
}
