<?php
namespace MathPHP\NumericalAnalysis\NumericalIntegration;

use MathPHP\Exception;

/**
 * Common validation methods for numerical integration techniques
 */
trait Validation
{
    /**
     * Ensures that the length of each subinterval is equal, or equivalently,
     * that the spacing between each point is equal
     *
     * @param  array $sorted Points sorted by (increasing) x-component
     *
     * @throws Exception if the spacing between any two points is not equal
     *         to the average spacing between every point
     */
    public static function isSpacingConstant(array $sorted)
    {
        $x       = 0;
        $length  = count($sorted);
        $spacing = ($sorted[$length-1][$x]-$sorted[0][$x])/($length-1);

        for ($i = 1; $i < $length - 1; $i++) {
            if ($sorted[$i+1][$x] - $sorted[$i][$x] !== $spacing) {
                throw new Exception\BadDataException('The size of each subinterval must be the same. Provide points with constant spacing.');
            }
        }
    }

    /**
     * Ensures that the number of subintervals is a multiple of m, or
     * equivalently, if there are n points, that n-1 is a multiple of m
     *
     * @param  array  $points
     * @param  number $m      The number that n-1 should be a multiple of
     *
     * @throws Exception if the number of points minus 1 is not a multiple of m
     */
    public static function isSubintervalsMultiple($points, $m)
    {
        if ((count($points)-1) % $m !== 0) {
            throw new Exception\BadDataException('The number of subintervals must be a multiple of m. Your input must either be a set of n points, where n-1 is a multiple of m, or a callback function evaluated at an n points, where n-1 is a multiple of m');
        }
    }
}
