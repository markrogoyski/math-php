<?php
namespace Math\Functions\Map;

/**
 * Map functions against a single array
 */
class Single
{
    /**
     * Map square
     *
     * @param  array  $xs
     * @return array
     */
    public static function square(array $xs): array
    {
        return array_map(
            function($x) {
                return $x**2; 
            }, $xs
        );
    }

    /**
     * Map cube
     *
     * @param  array  $xs
     * @return array
     */
    public static function cube(array $xs): array
    {
        return array_map(
            function($x) {
                return $x**3; 
            }, $xs
        );
    }

    /**
     * Map raise to a power
     *
     * @param  array  $xs
     * @return array
     */
    public static function pow(array $xs, $n): array
    {
        return array_map(
            function($x) use ($n) {
                return $x**$n; 
            }, $xs
        );
    }

    /**
     * Map square root
     *
     * @param  array  $xs
     * @return array
     */
    public static function sqrt(array $xs): array
    {
        return array_map(
            function($x) {
                return sqrt($x); 
            }, $xs
        );
    }
}