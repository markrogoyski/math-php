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
 */
class TrapezoidalRule
{
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
     * @param  array ... $arrays Two or more arrays, each containing precisely
     *                           two numbers
     *
     * @return number            The approximation to the integral of f(x)
     */
    public static function solve(array ... $arrays)
    {
        // Validate and sort arrays
        self::validate($arrays);
        $sorted = self::sort($arrays);

        // Initialize
        $number_of_arrays = (count($sorted));
        $steps            = $number_of_arrays - 1;
        $approximation    = 0;

        // Summation
        for ($i = 0; $i < $steps; $i++) {
            $x0             = $sorted[$i][0];
            $x1             = $sorted[$i+1][0];
            $y0             = $sorted[$i][1];
            $y1             = $sorted[$i+1][1];
            $average_y      = ($y1 + $y0)/2;
            $delta_x        = $x1 - $x0;
            $approximation += $delta_x*$average_y;
        }

        return $approximation;
    }

    /**
     * Validate that there are two or more arrays, that each array has precisely
     * two numbers, and that no two arrays share the same first number
     * (x-component)
     *
     * @param  array $arrays
     *
     * @return bool
     * @throws Exception if there are less than two arrays
     * @throws Exception if any array does not contain two numbers
     * @throws Exception if two arrays share the same first number (x-component)
     */
    public static function validate(array $arrays)
    {
        if (count($arrays) < 2) {
            throw new \Exception('You need to have at least two sets of
                                  coordinates (arrays)');
        }

        $x_coordinates = [];
        foreach ($arrays as $array) {
            if (count($array) !== 2) {
                throw new \Exception('Each array needs to have have precisely
                                      two numbers, an x- and y-component');
            }

            $x_component = $array[0];
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
     * @param  array $arrays
     *
     * @return array
     */
    public static function sort(array $arrays)
    {
        $pairs = [];
        foreach ($arrays as $array) {
            $pairs[$array[0]] = [$array[0], $array[1]];
        }
        ksort($pairs);

        $sorted = [];
        while ($pair = current($pairs)) {
            array_push($sorted, $pair);
            next($pairs);
        }

        return $sorted;
    }
}
