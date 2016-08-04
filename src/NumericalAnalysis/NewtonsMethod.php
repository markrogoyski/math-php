<?php
namespace Math\NumericalAnalysis;

/**
 * Newton's Method (also known as the Newton–Raphson method)
 *
 * In numerical analysis, Newton's method is a method for finding successively better
 * approximations to the roots (or zeroes) of a real-valued function.
 */
class NewtonsMethod
{
    /**
     * Use Newton's Method to find the x which produces $target = $function(x) value
     * $args is an array of parameters to pass to $function, but having the string
     * 'x' in the position of interest.
     *
     * @param Callable $function f(x) callback function
     * @param array    $args     Parameters to pass to callback function. The initial value for the
     *                               parameter of interest must be in this array.
     * @param number   $target   Value of f(x) we a trying to solve for
     * @param number   $position Which element in the $args array will be changed
     * @param number   $tol      Tolerance; How close to the actual solution we would like.
     * 
     * @return number
     */
    public static function solve(callable $function, array $args, $target, $position, $tol)
    {
        if ($tol < 0){
            throw new Exception('Tolerance must be greater than zero.');
        }
        $dif      = $tol + 1;  // initialize
        $args1    = $args;
        $guess    = $args[$position];

        while ($dif > $tol) {
            $args1[$position] = $guess + $tol; // load the initial guess into the arguments
            $args[$position]  = $guess;        // load the initial guess into the arguments
            $y                = call_user_func_array($function, $args);
            $y_at_xplusdelx   = call_user_func_array($function, $args1);
            $slope            = ($y_at_xplusdelx - $y)/ $tol;
            $del_y            = $target - $y;
            $guess            = $del_y / $slope + $guess;
            $dif              = abs($del_y);
        }

        return $guess;
    }
}
