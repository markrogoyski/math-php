<?php
namespace Math\NumericalAnalysis\RootFinding;

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
     * $args is an array of parameters to pass to $function, but having the element that
     * will be changed and serve as the initial guess in position $position.
     *
     * @param Callable $function f(x) callback function
     * @param array    $args     Parameters to pass to callback function. The initial value for the
     *                               parameter of interest must be in this array.
     * @param number   $target   Value of f(x) we a trying to solve for
     * @param number   $tol      Tolerance; How close to the actual solution we would like.
     * @param number   $position Which element in the $args array will be changed; also serves as initial guess

     * @return number
     */
    public static function solve(callable $function, array $args, $target, $tol, $position = 0)
    {
        Validation::tolerance($tol);

        // Initialize
        $args1 = $args;
        $guess = $args[$position];

        do {
            $args1[$position] = $guess + $tol; // load the initial guess into the arguments
            $args[$position]  = $guess;        // load the initial guess into the arguments
            $y                = call_user_func_array($function, $args);
            $y_at_xplusdelx   = call_user_func_array($function, $args1);
            $slope            = ($y_at_xplusdelx - $y)/ $tol;
            $del_y            = $target - $y;
            $guess            = $del_y / $slope + $guess;
            $dif              = abs($del_y);
        } while ($dif > $tol);

        return $guess;
    }
}
