<?php
namespace Math\NumericalAnalysis\Interpolation;

use Math\Functions\Polynomial;

/**
 * Cubic Spline Interpolating Polyonomial
 *
 * In numerical analysis, cubic splines are used for polynomial
 * interpolation.
 */
class CubicSpline extends Interpolation
{
    /**
     * Interpolate
     *
     * @param          $source   The source of our approximation. Should be either
     *                           a callback function or a set of arrays. Each array
     *                           (point) contains precisely two numbers, an x and y.
     *                           Example array: [[1,2], [2,3], [3,4]].
     *                           Example callback: function($x) {return $x**2;}
     * @param numbers  ... $args The arguments of our callback function: start,
     *                           end, and n. Example: approximate($source, 0, 8, 5).
     *                           If $source is a set of points, do not input any
     *                           $args. Example: approximate($source).
     *
     * @return callable          The lagrange polynomial p(x)
     */
    public static function interpolate($source, ... $args)
    {
        // get an array of points from our $source argument
        $points = self::getPoints($source, $args);

        // Validate input and sort points
        self::validate($points, $degree = 1);
        $sorted = self::sort($points);

        // Descriptive constants
        $x = self::X;
        $y = self::Y;

        // Initialize
        $n   = count($sorted);
        $p⟮t⟯ = new Polynomial([0]);
    }
}
