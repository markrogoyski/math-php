<?php

namespace Math\NumericalAnalysis\NumericalIntegration;

/**
 * Base class for numerical integration techniques.
 *
 * Numerical integration techniques are used to approximate the value of
 * an indefinite intergal.
 *
 * This calss gives each technique a set of common tools, and requires each
 * technique to define an approximate() method to approximate an indefinite
 * integral.
 */
abstract class NumericalIntegration
{
    /**
     * @var int Index of x
     */
    const X = 0;

    /**
     * @var int Index of y
     */
    const Y = 1;

    abstract public static function approximate(array $points);

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
    protected static function validate(array $points, $degree)
    {
        if (count($points) < $degree) {
            throw new \Exception("You need to have at least $degree sets of
                                  coordinates (arrays) for this technique");
        }

        $x_coordinates = [];
        foreach ($points as $point) {
            if (count($point) !== 2) {
                throw new \Exception("Each array needs to have have precisely
                                      two numbers, an x- and y-component");
            }

            $x_component = $point[self::X];
            if (in_array($x_component, $x_coordinates)) {
                throw new \Exception("Not a function. Your input array contains
                                      more than one coordinate with the same
                                      x-component.");
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
    protected static function sort(array $points)
    {
        $x = self::X;
        usort($points, function ($a, $b) use ($x) {
            return $a[$x] <=> $b[$x];
        });

        return $points;
    }

    /**
     * Evaluate our input function at n evenly spaced points between
     * start and end
     *
     * @param  callable $function
     * @param  number   $start
     * @param  number   $end
     * @param  number   $evalutations
     *
     * @return array
     */
    protected static function toCoordinates(callable $function, $start, $end,
                                            $evalutions)
    {
        // Still in development
    }
}
