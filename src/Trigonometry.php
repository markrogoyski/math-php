<?php
namespace MathPHP;

/**
 * Trigonometric Functions
 *
 */
class Trigonometry
{

    /*
     * Produce a given number of points on a unit circle
     *
     * The first point is repeated at the end as well to provide overlap.
     * For example: unitCircle(5) would return the array:
     * [[1, 0], [0, 1], [-1, 0], [0, -1], [1, 0]] 
     */
    public static function unitCircle($numpoints = 11): array
    {
        $unit_circle = [];
        for ($i = 0; $i <= $numpoints - 1; $i++) {
            $x = cos(2 * pi() * $i / ($numpoints - 1));
            $y = sin(2 * pi() * $i / ($numpoints - 1));
            $unit_circle[] = [$x, $y];
        }
        return $unit_circle;
    }
}
