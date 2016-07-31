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
     * @param array    $args     Parameters to pass to callback function
     * @param number   $target   Value of f(x) we a trying to solve for
     * @param number   $guess    Starting point
     *                           For a polynomial this is very important because there may be many solutions.
     *                           The starting point will determine which solution we will receive.
     * @param number   $tol      Tolerance; How close to the actual solution we would like
     */
    public static function solve(callable $function, array $args, $target, $guess, $tol)
    {
        $dif      = $tol + 1;  // initialize
        $position = array_search('x', $args); // find the position of 'x' in the arguments
        $args1    = $args;

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
