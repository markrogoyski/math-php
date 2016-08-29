<?php

namespace Math\NumericalAnalysis;

/**
 * Trapezoidal Rule
 *
 * In numerical analysis, the trapezoidal rule is a technique for approximating
 * the definite integral of a function.
 *
 * The trapezoidal rule belongs to the Newton-Cotes formulas, a group of methods
 * for numerical integration which approximate the integral of a function when
 * the antiderivative of that function is not known or is not explicity given.
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
class TrapezoidalRule
{
    /**
     * @var int Index of x
     */
    const X = 0;

    /**
     * @var int Index of y
     */
    const Y = 1;

    /**
     * Use the Trapezoidal Rule to aproximate the definite integral of a
     * function f(x). Each array in our input contains two numbers which
     * correspond to coordinates (x, y) or equivalently, (x, f(x)), of the
     * function f(x) whose definite integral we are approximating.
     *
     * The bounds of the definite integral to which we are approximating is
     * determined by the minimum and maximum values of our x-components in our
     * input coordinates (arrays).
     *
     * Example: solve([0, 10], [3, 5], [10, 7]) will approximate the definite
     * integral of the function that produces these coordinates with a lower
     * bound of 0, and an upper bound of 10.
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
     *
     * @param  array $points Array of arrays (array of points).
     *                       Each array (point) contains precisely two numbers,
     *                       an x and y value.
     *                       Example: [[1,2], [2,3], [3,4], [4,5]]
     *
     * @return number        The approximation to the integral of f(x)
     */
    public static function solve(array $points)
    {
        // Validate and sort points
        self::validate($points);
        $sorted = self::sort($points);

        // Descriptive constants
        $x = self::X;
        $y = self::Y;

        // Initialize
        $n             = (count($sorted));
        $steps         = $n - 1;
        $approximation = 0;

        /*
         * Summation
         * ⁿ⁻¹  h
         *  ∑   - [f(xᵢ₊₁) + f(xᵢ)] + O(h³f″(x))
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

    /**
     * Validate that there are two or more arrays (points), that each point array
     * has precisely two numbers, and that no two points share the same first number
     * (x-component)
     *
     * @param  array $points Array of arrays (points)
     *
     * @return bool
     * @throws Exception if there are less than two points
     * @throws Exception if any point does not contain two numbers
     * @throws Exception if two points share the same first number (x-component)
     */
    private static function validate(array $points)
    {
        if (count($points) < 2) {
            throw new \Exception('You need to have at least two sets of
                                  coordinates (arrays)');
        }

        $x_coordinates = [];
        foreach ($points as $point) {
            if (count($point) !== 2) {
                throw new \Exception('Each array needs to have have precisely
                                      two numbers, an x- and y-component');
            }

            $x_component = $point[0];
            if (in_array($x_component, $x_coordinates)) {
                throw new \Exception('Not a function. Your input array contains
                                      more than one coordinate with the same
                                      x-component.');
            }
            array_push($x_coordinates, $x_component);
        }
    }

    /**
     * Sorts our coordinates (arrays) by their x-component (first number) such
     * that consecutive coordinates have an increasing x-component.
     *
     * @param  array $points
     *
     * @return array
     */
    private static function sort(array $points)
    {
        $x = self::X;
        usort($points, function($a, $b) use ($x) {
            return $a[$x] <=> $b[$x];
        });

        return $points;
    }
}
