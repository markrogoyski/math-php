<?php

namespace Math\NumericalAnalysis\NumericalIntegration;

/**
 * Trapezoidal Rule
 *
 * In numerical analysis, the trapezoidal rule is a technique for approximating
 * the definite integral of a function.
 *
 * The trapezoidal rule belongs to the Newton-Cotes formulas, a group of methods
 * for numerical integration which approximate the integral of a function.
 * Instead, we use the Newton-Cotes formulas when we are given a set of inputs
 * and their corresponding outputs for our function. We then use these values to
 * interpolate a Lagrange polynomial. Finally, we integrate the Lagrange
 * polynomial to approximate the integral of our original function.
 *
 * The trapezoidal rule is produced by integrating the first Lagrange polynomial.
 *
 * https://en.wikipedia.org/wiki/Trapezoidal_rule
 * http://mathworld.wolfram.com/TrapezoidalRule.html
 * http://www.efunda.com/math/num_integration/num_int_newton.cfm
 */
class TrapezoidalRule extends NumericalIntegration
{
    /**
     * Use the Trapezoidal Rule to aproximate the definite integral of a
     * function f(x). Our input can support either a set of arrays, or a callback
     * function with arguments (to produce a set of arrays). Each array in our
     * input contains two numbers which correspond to coordinates (x, y) or
     * equivalently, (x, f(x)), of the function f(x) whose definite integral we
     * are approximating.
     *
     * The bounds of the definite integral to which we are approximating is
     * determined by the our inputs.
     *
     * Example: approximate([0, 10], [3, 5], [10, 7]) will approximate the definite
     * integral of the function that produces these coordinates with a lower
     * bound of 0, and an upper bound of 10.
     *
     * Example: approximate(function($x) {return $x**2;}, [0, 4 ,5]) will produce
     * a set of arrays by evaluating the callback at 5 evenly spaced points
     * between 0 and 4. Then, this array will be used in our approximation.
     *
     * Trapezoidal Rule:
     *
     * xn        ⁿ⁻¹ xᵢ₊₁
     * ∫ f(x)dx = ∑   ∫ f(x)dx
     * x₁        ⁱ⁼¹  xᵢ
     *
     *           ⁿ⁻¹  h
     *          = ∑   - [f(xᵢ₊₁) + f(xᵢ)] + O(h³f″(x))
     *           ⁱ⁼¹  2
     *
     *  where h = xᵢ₊₁ - xᵢ
     *  note: this implementation does not compute the error term.
     * @param          $source The source of our approximation. Should be either
     *                         a callback function or a set of arrays. Each array
     *                         (point) contains precisely two numbers, an x and y.
     *                         Example array: [[1,2], [2,3], [3,4]].
     *                         Example callback: function($x) {return $x**2;}
     * @param  array   $args   The arguments of our callback function: start,
     *                         end, and n. Example: [0, 8, 5]. If $source is a
     *                         set of arrays, $args will default to [].
     *
     * @return number          The approximation to the integral of f(x)
     */
    public static function approximate($source, array $args = [])
    {
        // get an array of points from our $source argument
        $points = self::getPoints($source, $args);

        // Validate input and sort points
        self::validate($points, $degree = 2);
        $sorted = self::sort($points);

        // Descriptive constants
        $x = self::X;
        $y = self::Y;

        // Initialize
        $n             = count($sorted);
        $steps         = $n - 1;
        $approximation = 0;

        /*
         * Summation
         * ⁿ⁻¹  h
         *  ∑   - [f(xᵢ₊₁) + f(xᵢ)]
         * ⁱ⁼¹  2
         *  where h = xᵢ₊₁ - xᵢ
         */
        for ($i = 0; $i < $steps; $i++) {
            $xᵢ             = $sorted[$i][$x];
            $xᵢ₊₁           = $sorted[$i+1][$x];
            $f⟮xᵢ⟯           = $sorted[$i][$y];    // yᵢ
            $f⟮xᵢ₊₁⟯         = $sorted[$i+1][$y];  // yᵢ₊₁
            $h              = $xᵢ₊₁ - $xᵢ;
            $approximation += ($h * ($f⟮xᵢ₊₁⟯ + $f⟮xᵢ⟯)) / 2;
        }

        return $approximation;
    }
}
