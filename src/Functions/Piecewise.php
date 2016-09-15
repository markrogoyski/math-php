<?php

namespace Math\Functions;

/**
 * A convenience class for piecewise functions.
 *
 * https://en.wikipedia.org/wiki/Piecewise
 */
class Piecewise
{
    private $intervals;
    private $functions;

    /**
     * Validate that our inputs satisfy the conditions of a piecewise function,
     * and then assign our inputs as the object parameters.
     *
     * In our $intervals array, each interval should contain two numbers [a, b].
     * We can optionally add two booleans to an interval, signifying the openness
     * of a and b, respectfully (true means open, false means closed). If boolean
     * arguments are not supplied, the interval will be assumed to be closed.
     *
     * Examples:
     *     1. [0, 2, true, false] means an interval from 0 to 2, where 0 is open
     *        (exclusive) and 2 is closed (inclusive).
     *     2. [-10, 10] means an closed (inclusive) interval from -10 to 10
     *
     * A number of conditions need to be met for a piecewise function:
     *     o We must provide the same number of intervals as callback functions
     *     o Each function in our $functions array needs to be callable
     *     o Each interval must contain precisely 2 numbers, optionally 2
     *     o An interval defined as a point (e.g. [2, 2]) must be closed at both ends
     *     o The numbers in an interval must be increasing. Given [a, b] then b >= a.
     *     o Two intervals cannot overlap. This means that if two intervals share
     *       a start and end-point, the point must be closed on both sides. Also,
     *       we cannot start or end an interval in the middle of another interval.
     *
     * @param  array $intervals Array of intervals
     *                          Example: [[-10, 0, false, true], [0, 2], [3, 10]]
     * @param  array $functions Array of callback functions
     *
     * @throws Exception if the number of intervals and functions are not the same
     * @throws Exception if any function in $functions is not callable
     * @throws Exception if any interval in $intervals does not contain 2 numbers
     * @throws Exception if any interval [a, b] is decreasing, or b < a
     * @throws Exception if an interval is a point that is not closed
     * @throws Exception if two intervals share a point that is closed at both ends
     * @throws Exception if one interval starts or ends inside another interval
     */
    public function __construct(array $intervals, array $functions)
    {
        if (count($intervals) !== count($functions)) {
            throw new \Exception("For a piecewise function you must provide the
                                  same number of intervals as functions.");
        }

        if (count(array_filter($functions, "is_callable")) !== count($intervals)) {
            throw new \Exception("Not every function provided is valid. Ensure
                                  that each function is callable.");
        }

        $unsortedIntervals = $intervals;

        // Sort intervals such that start of intervals is increasing
        usort($intervals, function ($a, $b) {
            return $a[0] <=> $b[0];
        });

        foreach ($intervals as $interval) {
            // Store values from previous interval
            $lastA = $a ?? -INF;
            $lastB = $b ?? -INF;
            $lastBOpen = $bOpen ?? false;

            if (count(array_filter($interval, "is_numeric")) !== 2) {
                throw new \Exception("Each interval must contain two numbers.");
            }

            // Fetch values from current interval
            $a = $interval[0];
            $b = $interval[1];
            $aOpen = $interval[2] ?? false;
            $bOpen = $interval[3] ?? false;

            if ($a === $b and ($aOpen or $bOpen)) {
                throw new \Exception("Your interval [{$a}, {$b}] is a point and
                                      thus needs to be closed at both ends");
            }

            if ($a > $b) {
                throw new \Exception("Interval must be increasing. Try again
                                      using [{$b}, {$a}] instead of [{$a}, {$b}]");
            }

            if ($a === $lastB and !$aOpen and !$lastBOpen) {
                throw new \Exception("The intervals [{$lastA}, {$lastB}] and [{$a}, {$b}]
                                      share a point, but both intervals are also closed
                                      at that point. For intervals to share a point, one
                                      or both sides of that point must be open.");
            }

            if ($a < $lastB) {
                throw new \Exception("The intervals [{$lastA}, {$lastB}] and [{$a}, {$b}]
                                      overlap. The subintervals of a piecewise functions
                                      cannot overlap.");
            }
        }

        $this->intervals = $unsortedIntervals;
        $this->functions = $functions;
    }

    public function __toString()
    {

    }

    public function __invoke($x₀)
    {
        $index = self::callbackIndex($x₀, $this->intervals);
        if ($index === false) {
            throw new \Exception("The input {$x₀} is not in the domain of this
                                  piecewise function, thus it is undefined at
                                  that point.");
        }
        $function = $this->functions[$index];

        return $function($x₀);
    }

    public static function callbackIndex($x, $intervals)
    {
        foreach ($intervals as $i => $interval) {
            $a = $interval[0];
            $b = $interval[1];
            $aOpen = $interval[2] ?? false;
            $bOpen = $interval[3] ?? false;
            if ($aOpen and $bOpen) {
                if ($x > $a and $x < $b) {
                    return $i;
                }
            } elseif ($aOpen and !$bOpen) {
                if ($x > $a and $x <= $b) {
                    return $i;
                }
            } elseif (!$aOpen and $bOpen) {
                if ($x >= $a and $x < $b) {
                    return $i;
                }
            } elseif (!$aOpen and !$bOpen) {
                if ($x >= $a and $x <= $b) {
                    return $i;
                }
            }
        }

        return false;
    }
}
