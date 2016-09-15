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

        // Sort intervals such that start of intervals is increasing
        usort($intervals, function ($a, $b) {
            return $a[0] <=> $b[0];
        });

        foreach ($intervals as $interval) {
            $lastA = $a ?? -INF;
            $lastB = $b ?? -INF;
            $lastBOpen = $bOpen ?? false;

            if (count(array_filter($interval, "is_numeric")) !== 2) {
                throw new \Exception("Each interval must contain two numbers.");
            }

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
        }

        $this->intervals = $intervals;
        $this->functions = $functions;
    }

    public function __toString()
    {

    }

    public function __invoke($x₀)
    {
        $range = self::inPiece($x₀, $this->intervals);
        $function = $this->functions[$range];

        return $function($x₀);
    }

    public static function inPiece ($x, $intervals)
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
    }
}
