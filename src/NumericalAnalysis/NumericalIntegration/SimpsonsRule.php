<?php

namespace Math\NumericalAnalysis\NumericalIntegration;

/**
 * Simpsons Rule
 *
 * In numerical analysis, Simpson's rule is a technique for approximating
 * the definite integral of a function.
 *
 * Simpson's rule belongs to the Newton-Cotes formulas, a group of methods
 * for numerical integration which approximate the integral of a function when
 * the antiderivative of that function is not known or is not explicity given.
 * Instead, we use the Newton-Cotes formulas when we are given a set of inputs
 * and their corresponding outputs for our function. We then use these values to
 * interpolate a Lagrange polynomial. Finally, we integrate the Lagrange
 * polynomial to approximate the integral of our original function.
 *
 * Simpson's rule is produced by integrating the second Lagrange polynomial.
 *
 * https://en.wikipedia.org/wiki/Simpson%27s_rule
 * http://mathworld.wolfram.com/SimpsonsRule.html
 * http://www.efunda.com/math/num_integration/num_int_newton.cfm
 */
class SimpsonsRule extends NumericalIntegration
{
    /**
     * Use Simpson's Rule to aproximate the definite integral of a
     * function f(x). Each array in our input contains two numbers which
     * correspond to coordinates (x, y) or equivalently, (x, f(x)), of the
     * function f(x) whose definite integral we are approximating.
     *
     * Note: Simpson's method requires that we have an even number of
     * subintervals (we must supply an odd number of points) and also that the
     * size of each subinterval is equal (spacing between each point is equal).
     *
     * The bounds of the definite integral to which we are approximating is
     * determined by the minimum and maximum values of our x-components in our
     * input coordinates (arrays).
     *
     * Example: approximate([9, 10], [6, 5], [3, 7]) will approximate the
     * definite integral of the function that produces these coordinates with a
     * lower bound of 3, and an upper bound of 9.
     *
     * Simpson's Rule: // UPDATE FORMULA
     *
     * xn        ⁿ⁻¹ xᵢ₊₁
     * ∫ f(x)dx = ∑   ∫ f(x)dx
     * x₁        ⁱ⁼¹  xᵢ
     *
     *           ⁿ/²  h
     *          = ∑   - [$f⟮x₂ᵢ⟯ + 4$f⟮x₂ᵢ₊₁⟯ + $f⟮x₂ᵢ₊₂⟯] + O(h^5f″″(x))
     *           ⁱ⁼¹  3
     *
     *  where h = xᵢ₊₁ - xᵢ
     *
     * @param  array $points Array of arrays (array of points).
     *                       Each array (point) contains precisely two numbers,
     *                       an x and y value.
     *                       Our input must contain an odd number of arrays.
     *
     *                       Example: [[1,2], [2,3], [3,4]]
     *
     * @return number        The approximation to the integral of f(x)
     */
    public static function approximate(array $points)
    {
        // Descriptive constants
        $x = self::X;
        $y = self::Y;

        // Validate input and sort points
        self::validate($points, $min = 3);
        self::isSubintervalsEven($points);
        $sorted = self::sort($points);
        self::isSpacingConstant($sorted);

        // Initialize
        $n             = count($sorted);
        $subintervals  = $n-1;
        $a             = $sorted[0][$x];
        $b             = $sorted[$n-1][$x];
        $h             = ($b - $a)/$subintervals;
        $approximation = 0;

        /*
         * Summation
         * ⁿ/²  h
         *  ∑   - [$f⟮x₂ᵢ⟯ + 4$f⟮x₂ᵢ₊₁⟯ + $f⟮x₂ᵢ₊₂⟯] + O(h^5f″″(x))
         * ⁱ⁼¹  3
         *  where h = xᵢ₊₁ - xᵢ
         */

        for ($i = 0; $i < ($subintervals/2); $i++) {
            $f⟮x₂ᵢ⟯          = $sorted[(2*$i)][$y];   // y₂ᵢ
            $f⟮x₂ᵢ₊₁⟯        = $sorted[(2*$i)+1][$y]; // y₂ᵢ₊₁
            $f⟮x₂ᵢ₊₂⟯        = $sorted[(2*$i)+2][$y]; // y₂ᵢ₊₂
            $approximation += ($h * ($f⟮x₂ᵢ⟯ + 4*$f⟮x₂ᵢ₊₁⟯ + $f⟮x₂ᵢ₊₂⟯)) / 3;
        }

        return $approximation;
    }

    /**
     * Ensures that there are an even number of subintervals, or equivalently,
     * an odd number of points
     *
     * @param  array $points
     *
     * @return bool
     *
     * @throws Exception if there is not an odd number of points in our array
     */
    private static function isSubintervalsEven(array $points)
    {
        if (count($sorted) % 2 !== 1) {
            throw new \Exception("There must be an even number of subintervals.
                                  Provide an input with an odd number of points");
        }

        return true;
    }

    /**
     * Ensures that the length of each subinterval is equal, or equivalently,
     * that the spacing between each point is equal
     *
     * @param  array $sorted
     *
     * @return bool
     *
     * @throws Exception if the spacing between any two points is not equal
     *         to the average spacing between every point
     */
    private static function isSpacingConstant(array $sorted)
    {
        $x = self::X;
        $length = count($sorted);
        $spacing = ($sorted[$length-1][$x]-$sorted[0][$x])/($length-1);
        for ($i = 1; $i < $length-1; $i++) {
            if ($sorted[$i+1][$x]-$sorted[$i][$x] !== $spacing) {
                throw new \Exception("The size of each subinterval must be the
                                      same. Provide points with constant
                                      spacing.");
            }
        }

        return true;
    }
}
