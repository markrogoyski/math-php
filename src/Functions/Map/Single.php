<?php
namespace MathPHP\Functions\Map;

/**
 * Map functions against a single array
 */
class Single
{
    /**
     * Map addition
     *
     * @param  array $xs
     * @param  number $k Number to add to each element
     *
     * @return array
     */
    public static function add(array $xs, $k): array
    {
        return array_map(
            function ($x) use ($k) {
                return $x + $k;
            },
            $xs
        );
    }

    /**
     * Map subtract
     *
     * @param  array $xs
     * @param  number $k Number to subtract from each element
     *
     * @return array
     */
    public static function subtract(array $xs, $k): array
    {
        return array_map(
            function ($x) use ($k) {
                return $x - $k;
            },
            $xs
        );
    }

    /**
     * Map multiply
     *
     * @param  array $xs
     * @param  number $k Number to multiply to each element
     *
     * @return array
     */
    public static function multiply(array $xs, $k): array
    {
        return array_map(
            function ($x) use ($k) {
                return $x * $k;
            },
            $xs
        );
    }

    /**
     * Map Divide
     *
     * @param  array $xs
     * @param  number $k Number to divide each element by
     *
     * @return array
     */
    public static function divide(array $xs, $k): array
    {
        return array_map(
            function ($x) use ($k) {
                return $x / $k;
            },
            $xs
        );
    }

    /**
     * Map square
     *
     * @param  array  $xs
     *
     * @return array
     */
    public static function square(array $xs): array
    {
        return array_map(
            function ($x) {
                return $x**2;
            },
            $xs
        );
    }

    /**
     * Map cube
     *
     * @param  array  $xs
     *
     * @return array
     */
    public static function cube(array $xs): array
    {
        return array_map(
            function ($x) {
                return $x**3;
            },
            $xs
        );
    }

    /**
     * Map raise to a power
     *
     * @param  array  $xs
     * @param  number $n
     *
     * @return array
     */
    public static function pow(array $xs, $n): array
    {
        return array_map(
            function ($x) use ($n) {
                return $x**$n;
            },
            $xs
        );
    }

    /**
     * Map square root
     *
     * @param  array  $xs
     *
     * @return array
     */
    public static function sqrt(array $xs): array
    {
        return array_map(
            function ($x) {
                return sqrt($x);
            },
            $xs
        );
    }

    /**
     * Map absolute value
     *
     * @param  array $xs
     *
     * @return array
     */
    public static function abs(array $xs): array
    {
        return array_map(
            function ($x) {
                return abs($x);
            },
            $xs
        );
    }
    
    /**
     * Map min value
     * Each element in array is compared against the value,
     * and the min of each is returned.
     *
     * @param  array  $xs
     * @param  number $value
     *
     * @return array
     */
    public static function min(array $xs, $value): array
    {
        return array_map(
            function ($x) use ($value) {
                return min($x, $value);
            },
            $xs
        );
    }
    
    /**
     * Map max value
     * Each element in the array is compared against the value,
     * and the max of each is returned.
     *
     * @param  array  $xs
     * @param  number $value
     *
     * @return array
     */
    public static function max(array $xs, $value): array
    {
        return array_map(
            function ($x) use ($value) {
                return max($x, $value);
            },
            $xs
        );
    }
}
