<?php
namespace Math\NumericalAnalysis\NumericalDifferentiation;

/**
 * Second Derivative Midpoint Formula
 *
 * In numerical analysis, the Second Derivative Midpoint formula is used for approximating
 * the second derivative of a function at a point in its domain.
 *
 * We can either directly supply a set of inputs and their corresponding outputs
 * for said function, or if we explicitly know the function, we can define it as a
 * callback function and then generate a set of points by evaluating that function
 * at 3 points between a start and end point.
 */
class SecondDerivativeMidpointFormula extends NumericalDifferentiation
{
    /**
     * Use the Second Derivative Midpoint Formula to aproximate the second derivative of a
     * function at our $target. Our input can support either a set of arrays, or a callback
     * function with arguments (to produce a set of arrays). Each array in our
     * input contains two numbers which correspond to coordinates (x, y) or
     * equivalently, (x, f(x)), of the function f(x) whose derivative we are
     * approximating.
     *
     * The Second Derivative Midpoint Formula requires we supply 3 points that are evenly
     * spaced apart, and that our target equals the x-components of one of our 3 points.
     *
     * Example: differentiate(2, function($x) {return $x**2;}, 0, 4 ,3) will produce
     * a set of arrays by evaluating the callback at 3 evenly spaced points
     * between 0 and 4. Then, this array will be used in our approximation.
     *
     * Second Derivative Midpoint Formula:
     *
     *           1                                h²
     * f''(x₀) = - [f(x₀-h) - 2f(x₀) + f(x₀+h)] - - f⁽⁴⁾(ζ)
     *           h²                               12
     *
     *     where ζ lies between x₀ - h and x₀ + h
     *
     * @param numbers  $target   The value at which we are approximating the derivative
     * @param          $source   The source of our approximation. Should be either
     *                           a callback function or a set of arrays. Each array
     *                           (point) contains precisely two numbers, an x and y.
     *                           Example array: [[1,2], [2,3], [3,4]].
     *                           Example callback: function($x) {return $x**2;}
     * @param numbers  ... $args The arguments of our callback function: start,
     *                           end, and n. Example: differentiate($target, $source, 0, 8, 3).
     *                           If $source is a set of points, do not input any
     *                           $args. Example: approximate($source).
     *
     * @return number            The approximation of f'($target), i.e. the derivative
     *                           of our input at our target point
     */
    public static function differentiate($target, $source, ... $args)
    {
        // Get an array of points from our $source argument
        $points = self::getPoints($source, $args);

        // Validate input, sort points, make sure spacing is constant, and make
        // sure our target is contained in an interval supplied by our $source
        self::validate($points, $degree = 3);
        $sorted = self::sort($points);
        self::isSpacingConstant($sorted);
        self::isTargetInPoints($target, $sorted);

        // Descriptive constants
        $x = self::X;
        $y = self::Y;

        // Initialize
        $n = count($sorted);
        $h = ($sorted[2][$x] - $sorted[0][$x]) / 2;

        /*
        *           1                                h²
        * f''(x₀) = - [f(x₀-h) - 2f(x₀) + f(x₀+h)] - - f⁽⁴⁾(ζ)
        *           h²                               12
        *
        *     where ζ lies between x₀ - h and x₀ + h
         */

        // If the 2nd point is our $target, use the Midpoint Formula
        if ($sorted[1][$x] == $target) {
            $f⟮x₀⧿h⟯     = $sorted[0][$y];
            $f⟮x₀⧾h⟯     = $sorted[2][$y];
            $derivative = ($f⟮x₀⧾h⟯ - $f⟮x₀⧿h⟯) / (2*$h);

        // If the 1st or 3rd point is our $target, use the Endpoint Formula
        } else {
            // The 1st point is our $target
            if ($sorted[0][$x] == $target) {
                $f⟮x₀⟯    = $sorted[0][$y];
                $f⟮x₀⧾h⟯  = $sorted[1][$y];
                $f⟮x₀⧾2h⟯ = $sorted[2][$y];

            // If the 3rd point is our $target, use negative h
            } else {
                $h       = -$h;
                $f⟮x₀⟯    = $sorted[2][$y];
                $f⟮x₀⧾h⟯  = $sorted[1][$y];
                $f⟮x₀⧾2h⟯ = $sorted[0][$y];
            }
            $derivative = (-3*$f⟮x₀⟯ + 4*$f⟮x₀⧾h⟯ - $f⟮x₀⧾2h⟯) / (2*$h);
        }

        return $derivative;
    }
}
