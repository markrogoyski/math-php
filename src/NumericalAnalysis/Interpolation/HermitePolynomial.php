<?php

namespace Math\NumericalAnalysis\Interpolation;

use Math\Functions\Arithmetic;

/**
 */
class HermitePolynomial extends Interpolation
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
     * @return number            The interpolated value at our target
     */
    public static function interpolate($source, ... $args)
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
        $n   = count($sorted);
        $Q   = [];

        // Build our 0th-degree Lagrange polynomials: Q₍ᵢ₎₍₀₎ = yᵢ for all i < n
        for ($i = 0; $i < $n; $i++) {
            $Q[$i][0] = function ($x) use ($sorted, $i, $y) {
                return $sorted[$i][$y]; // yᵢ
            };
        }

        // Recursively generate our (n-1)th-degree Lagrange polynomial at $target
        for ($i = 1; $i < $n; $i++) {
            for ($j = 1; $j <= $i; $j++) {
                $xᵢ₋ⱼ        = $sorted[$i - $j][$x];
                $xᵢ          = $sorted[$i][$x];
                $Q₍ᵢ₎₍ⱼ₋₁₎   = $Q[$i][$j-1]($target);
                $Q₍ᵢ₋₁₎₍ⱼ₋₁₎ = $Q[$i-1][$j-1]($target);
                $Q[$i][$j]   = LagrangePolynomial::interpolate([[$xᵢ₋ⱼ,$Q₍ᵢ₋₁₎₍ⱼ₋₁₎],[$xᵢ,$Q₍ᵢ₎₍ⱼ₋₁₎]]);
            }
        }

        // Return our (n-1)th-degree Lagrange polynomial evaluated at $target
        return $Q[$n-1][$n-1]($target);
    }
}
