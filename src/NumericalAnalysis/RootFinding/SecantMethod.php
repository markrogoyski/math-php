<?php
namespace Math\NumericalAnalysis\RootFinding;

/**
 * Secant Method (also known as the Newton–Raphson method)
 *
 * In numerical analysis, the Secant Method is a method for finding successively
 * better approximations to the roots (or zeroes) of a real-valued function. It is
 * a variation of Newton's Method that we can utilize when the derivative of our
 * function f'(x) is not explicity given or cannot be calculated.
 *
 * https://en.wikipedia.org/wiki/Secant_method
 */
class SecantMethod
{
    /**
     * Use the Secant Method to find the x which produces $f(x) = 0 by calculating
     * the average change between our initial approximations and moving our
     * approximations closer to the root.
     *
     * @param Callable $function f(x) callback function
     * @param number   $p₀       First initial approximation
     * @param number   $p₁       Second initial approximation
     * @param number   $tol      Tolerance; How close to the actual solution we would like.
     * @return number
     */
    public static function solve(callable $function, $p₀, $p₁, $tol)
    {
        // Validate input arguments
        self::validate($function, $p₀, $p₁, $tol);

        do {
            $q₀    = $function($p₀);
            $q₁    = $function($p₁);
            $slope = ($q₁ - $q₀)/($p₁ - $p₀);
            $p     = $p₁ - ($q₁/$slope);
            $dif   = abs($p - $p₁);
            $p₀    = $p₁;
            $p₁    = $p;
        } while ($dif > $tol);

        return $p;
    }

    /**
     * Verify the input arguments are valid for correct use of the secant method
     * If the tolerance is less than zero, an Exception will be thrown. If
     * $p₀ = $p₁, then clearly we cannot run our loop the slope with be
     * undefined, so we throw an Exception. If $a > $b, we simply reverse them
     * as if the user input $b = $a and $a = $b so the new $a < $b.
     *
     * @param Callable $function callback function f(x)
     * @param number   $p₀       First initial approximation
     * @param number   $p₁       Second initial approximation
     * @param number   $tol      Tolerance; How close to the actual solution we would like.
     *
     * @return bool
     * @throws Exception if $tol (the tolerance) is negative
     * @throws Exception if $a = $b
     */
    private static function validate(callable $function, $p₀, $p₁, $tol)
    {
        if ($tol < 0) {
            throw new \Exception('Tolerance must be greater than zero.');
        }

        if ($p₀ === $p₁) {
            throw new \Exception('Start point and end point of interval cannot be the same.');
        }

        if (($p₀ > $p₁)) {
            list($p₀, $p₁) = [$p₁, $p₀];
        }

        return true;
    }
}
