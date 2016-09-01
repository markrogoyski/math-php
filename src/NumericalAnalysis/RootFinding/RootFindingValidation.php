<?php
namespace Math\NumericalAnalysis\RootFinding;

/**
 * Common validation methods for root finding techniques
 */
trait RootFindingValidation
{
    /**
     * Throw an exception if the tolerance is negative.
     *
     * @param number $tol Tolerance; How close to the actual solution we would like.
     *
     * @throws Exception if $tol (the tolerance) is negative
     */
    public static function tolerance($tol)
    {
        if ($tol < 0) {
            throw new \Exception('Tolerance must be greater than zero.');
        }
    }

    /**
     * Verify that the start and end of of an interval are distinct numbers.
     *
     * @param number $a The start of the interval
     * @param number $b The end of the interval
     *
     * @throws Exception if $a = $b
     */
    public static function interval($a, $b)
    {
        if ($a === $b) {
            throw new \Exception('Start point and end point of interval cannot be the same.');
        }
    }
}
