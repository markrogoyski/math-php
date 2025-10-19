<?php

namespace MathPHP;

use MathPHP\Exception\OutOfBoundsException;

/**
 * Trigonometric Functions
 */
class Trigonometry
{
    /**
     * Produce a given number of points on a unit circle
     *
     * The first point is repeated at the end as well to provide overlap.
     * For example: unitCircle(5) would return the array:
     * [[1, 0], [0, 1], [-1, 0], [0, -1], [1, 0]]
     *
     * @param int $points number of points (must be >= 0)
     *
     * @return array<array{float, float}>
     *
     * @throws OutOfBoundsException if points < 0
     */
    public static function unitCircle(int $points = 11): array
    {
        if ($points < 0) {
            throw new OutOfBoundsException('Number of points must be non-negative - given:' . $points);
        }

        if ($points === 0) {
            return [];
        }

        if ($points === 1) {
            return [[1, 0]];
        }

        $n           = $points - 1;
        $unit_circle = [];

        for ($i = 0; $i <= $n; $i++) {
            $x = \cos(2 * \pi() * $i / ($n));
            $y = \sin(2 * \pi() * $i / ($n));
            $unit_circle[] = [$x, $y];
        }

        return $unit_circle;
    }
}
