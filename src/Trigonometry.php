<?php
namespace MathPHP;

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
     * @param int $numpoints
     *
     * @return array
     */
    public static function unitCircle(int $numpoints = 11): array
    {
        $n           = $numpoints - 1;
        $unit_circle = [];

        for ($i = 0; $i <= $n; $i++) {
            $x = cos(2 * pi() * $i / ($n));
            $y = sin(2 * pi() * $i / ($n));
            $unit_circle[] = [$x, $y];
        }

        return $unit_circle;
    }
}
