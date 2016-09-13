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
