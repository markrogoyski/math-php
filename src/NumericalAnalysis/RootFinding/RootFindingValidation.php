<?php
namespace Math\NumericalAnalysis\RootFinding;

/**
 * Common validation methods for root finding techniques
 */
class RootFindingValidation
{
    /**
     *
     */
    public static function tolerance($tol) {
        if ($tol < 0) {
            throw new \Exception('Tolerance must be greater than zero.');
        }
    }

    /**
     *
     */
    public static function interval($a, $b)
    {
        if ($a === $b) {
            throw new \Exception('Start point and end point of interval
                                    cannot be the same.');
        }
    }
}
