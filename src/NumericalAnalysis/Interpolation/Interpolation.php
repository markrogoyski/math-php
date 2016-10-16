<?php
namespace MathPHP\NumericalAnalysis\Interpolation;

use MathPHP\Exception;

/**
 * Base class for interpolation techniques.
 */
abstract class Interpolation
{
    /**
     * @var int Index of x
     */
    const X = 0;

    /**
     * @var int Index of y
     */
    const Y = 1;

    /**
     * Determine where the input $source argument is a callback function, a set
     * of arrays, or neither. If $source is a callback function, run it through
     * the functionToPoints() method with the input $args, and set $points to
     * output array. If $source is a set of arrays, simply set $points to
     * $source. If $source is neither, throw an Exception.
     *
     * @todo  Add method to verify function is continuous on our interval
     * @todo  Add method to verify input arguments are valid.
     *        Verify $start and $end are numbers, $end > $start, and $points is an integer > 1
     *
     * @param          $source The source of our approximation. Should be either
     *                         a callback function or a set of arrays.
     * @param  array   $args   The arguments of our callback function: start,
     *                         end, and n. Example: [0, 8, 5]. If $source is a
     *                         set of arrays, $args will default to [].
     *
     * @return array
     * @throws Exception if $source is not callable or a set of arrays
     */
    public static function getPoints($source, array $args = []): array
    {
        if (is_callable($source)) {
            $function = $source;
            $start    = $args[0];
            $end      = $args[1];
            $n        = $args[2];
            $points   = self::functionToPoints($function, $start, $end, $n);
        } elseif (is_array($source)) {
            $points   = $source;
        } else {
            throw new Exception\BadDataException('Input source is incorrect. You need to input either a callback function or a set of arrays');
        }

        return $points;
    }

    /**
     * Evaluate our callback function at n evenly spaced points on the interval
     * between start and end
     *
     * @param  callable $function f(x) callback function
     * @param  number   $start    the start of the interval
     * @param  number   $end      the end of the interval
     * @param  number   $n        the number of function evaluations
     *
     * @return array
     */
    protected static function functionToPoints(callable $function, $start, $end, $n): array
    {
        $points = [];
        $h      = ($end-$start)/($n-1);

        for ($i = 0; $i < $n; $i++) {
            $xᵢ         = $start + $i*$h;
            $f⟮xᵢ⟯       = $function($xᵢ);
            $points[$i] = [$xᵢ, $f⟮xᵢ⟯];
        }
        return $points;
    }

    /**
     * Validate that there are enough input arrays (points), that each point array
     * has precisely two numbers, and that no two points share the same first number
     * (x-component)
     *
     * @param  array  $points Array of arrays (points)
     * @param  number $degree The miminum number of input arrays
     *
     * @return bool
     * @throws Exception if there are less than two points
     * @throws Exception if any point does not contain two numbers
     * @throws Exception if two points share the same first number (x-component)
     */
    public static function validate(array $points, $degree = 2): bool
    {
        if (count($points) < $degree) {
            throw new Exception\BadDataException('You need to have at least $degree sets of coordinates (arrays) for this technique');
        }

        $x_coordinates = [];
        foreach ($points as $point) {
            if (count($point) !== 2) {
                throw new Exception\BadDataException('Each array needs to have have precisely two numbers, an x- and y-component');
            }

            $x_component = $point[self::X];
            if (in_array($x_component, $x_coordinates)) {
                throw new Exception\BadDataException('Not a function. Your input array contains more than one coordinate with the same x-component.');
            }
            array_push($x_coordinates, $x_component);
        }

        return true;
    }

    /**
     * Sorts our coordinates (arrays) by their x-component (first number) such
     * that consecutive coordinates have an increasing x-component.
     *
     * @param  array $points
     *
     * @return array
     */
    protected static function sort(array $points): array
    {
        $x = self::X;
        usort($points, function ($a, $b) use ($x) {
            return $a[$x] <=> $b[$x];
        });

        return $points;
    }
}
