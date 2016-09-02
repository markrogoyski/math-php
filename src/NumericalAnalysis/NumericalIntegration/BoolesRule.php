<?php

namespace Math\NumericalAnalysis\NumericalIntegration;

/**
 * Boole's Rule
 *
 * In numerical analysis, Boole's rule is a technique for approximating
 * the definite integral of a function.
 *
 * Boole's rule belongs to the closed Newton-Cotes formulas, a group of methods
 * for numerical integration which approximate the integral of a function. We
 * can either directly supply a set of inputs and their corresponding outputs for
 * said function, or if we explicitly know the function, we can define it as a
 * callback function and then generate a set of points by evaluating that function
 * at n points between a start and end point. We then use these values to
 * interpolate a Lagrange polynomial. Finally, we integrate the Lagrange
 * polynomial to approximate the integral of our original function.
 *
 * Boole's rule is produced by integrating the fourth Lagrange polynomial.
 *
 * https://en.wikipedia.org/wiki/Boole%27s_rule
 * http://mathworld.wolfram.com/BoolesRule.html
 * http://www.efunda.com/math/num_integration/num_int_newton.cfm
 */
class BoolesRule extends NumericalIntegration
{
    /**
     * Use Boole's Rule to aproximate the definite integral of a
     * function f(x). Our input can support either a set of arrays, or a callback
     * function with arguments (to produce a set of arrays). Each array in our
     * input contains two numbers which correspond to coordinates (x, y) or
     * equivalently, (x, f(x)), of the function f(x) whose definite integral we
     * are approximating.
     *
     * Note: Boole's rule requires that our number of subintervals is a factor
     * of four (we must supply an n points such that n-1 is a multiple of four)
     * and also that the size of each subinterval is equal (spacing between each
     * point is equal).
     *
     * The bounds of the definite integral to which we are approximating is
     * determined by the our inputs.
     *
     * Example: approximate([0, 10], [2, 5], [4, 7], [6,3]) will approximate the
     * definite integral of the function that produces these coordinates with a
     * lower bound of 0, and an upper bound of 6.
     *
     * Example: approximate(function($x) {return $x**2;}, [0, 3 ,4]) will produce
     * a set of arrays by evaluating the callback at 4 evenly spaced points
     * between 0 and 3. Then, this array will be used in our approximation.
     *
     * Boole's Rule:
     *
     * xn        ⁿ⁻¹ xᵢ₊₁
     * ∫ f(x)dx = ∑   ∫ f(x)dx
     * x₁        ⁱ⁼¹  xᵢ
     *
     *         ⁽ⁿ⁻¹⁾/⁴ 2h
     *          = ∑    -- [7f⟮x₄ᵢ₋₃⟯ + 32f⟮x₄ᵢ₋₂⟯ + 12f⟮x₄ᵢ₋₁⟯ + 32f⟮x₄ᵢ⟯ + 7f⟮x₄ᵢ₊₁⟯] + O(h⁷f⁽⁶⁾(x))
     *           ⁱ⁼¹   45
     * where h = (xn - x₁) / (n - 1)
     *
     * @param          $source   The source of our approximation. Should be either
     *                           a callback function or a set of arrays. Each array
     *                           (point) contains precisely two numbers, an x and y.
     *                           Example array: [[1,2], [2,3], [3,4], [4,5], [5,6]].
     *                           Example callback: function($x) {return $x**2;}
     * @param numbers  ... $args The arguments of our callback function: start,
     *                           end, and n. Example: approximate($source, 0, 8, 4).
     *                           If $source is a set of points, do not input any
     *                           $args. Example: approximate($source).
     *
     * @return number            The approximation to the integral of f(x)
     */
    public static function approximate($source, ... $args)
    {
        // get an array of points from our $source argument
        $points = self::getPoints($source, $args);

        // Validate input and sort points
        self::validate($points, $degree = 5);
        self::isSubintervalsFactorFour($points);
        $sorted = self::sort($points);
        self::isSpacingConstant($sorted);

        // Descriptive constants
        $x = self::X;
        $y = self::Y;

        // Initialize
        $n             = count($sorted);
        $subintervals  = $n - 1;
        $a             = $sorted[0][$x];
        $b             = $sorted[$n-1][$x];
        $h             = ($b - $a)/$subintervals;
        $approximation = 0;

        /*
        * ⁽ⁿ⁻¹⁾/⁴ 2h
        *  = ∑    -- [7f⟮x₄ᵢ₋₃⟯ + 32f⟮x₄ᵢ₋₂⟯ + 12f⟮x₄ᵢ₋₁⟯ + 32f⟮x₄ᵢ⟯ + 7f⟮x₄ᵢ₊₁⟯] + O(h⁷f⁽⁶⁾(x))
        *   ⁱ⁼¹   45
         */
        for ($i = 1; $i < ($subintervals/4) + 1; $i++) {
            $f⟮x₄ᵢ₋₃⟯        = $sorted[(4*$i)-4][$y]; // y₄ᵢ₋₃
            $f⟮x₄ᵢ₋₂⟯        = $sorted[(4*$i)-3][$y]; // y₄ᵢ₋₂
            $f⟮x₄ᵢ₋₁⟯        = $sorted[(4*$i)-2][$y]; // y₄ᵢ₋₁
            $f⟮x₄ᵢ⟯          = $sorted[(4*$i)-1][$y]; // y₄ᵢ
            $f⟮x₄ᵢ₊₁⟯        = $sorted[(4*$i)][$y];   // y₄ᵢ₊₁
            $approximation += (2*$h/45) * (7*$f⟮x₄ᵢ₋₃⟯ + 32*$f⟮x₄ᵢ₋₂⟯ + 12*$f⟮x₄ᵢ₋₁⟯ + 32*$f⟮x₄ᵢ⟯ + 7*$f⟮x₄ᵢ₊₁⟯);
        }

        return $approximation;
    }

    /**
     * Ensures that the number of subintervals is a factor of four, or
     * equivalently, if there are n points, that n-1 is a factor of 4
     *
     * @param  array $points
     *
     * @return bool
     *
     * @throws Exception if the number of points minus 1 is not a factor of 4
     */
    private static function isSubintervalsFactorFour(array $points): bool
    {
        if ((count($points)-1) % 4 !== 0) {
            throw new \Exception("The number subintervals must be a factor of
                                  four. Your input must either be a set of n
                                  points, where n-1 is a factor of four, or a
                                  callback function evaluated at an n points,
                                  where n-1 is a factor of four");
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
    private static function isSpacingConstant(array $sorted): bool
    {
        $x       = self::X;
        $length  = count($sorted);
        $spacing = ($sorted[$length-1][$x]-$sorted[0][$x])/($length-1);

        for ($i = 1; $i < $length - 1; $i++) {
            if ($sorted[$i+1][$x] - $sorted[$i][$x] !== $spacing) {
                throw new \Exception("The size of each subinterval must be the
                                      same. Provide points with constant
                                      spacing.");
            }
        }

        return true;
    }
}
