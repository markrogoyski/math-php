<?php

namespace MathPHP\Util;

use MathPHP\Exception;

/**
 * Search
 * Various functions to find specific indices in an array.
 */
class Search
{
    /**
     * Search Sorted
     * Find the array indices where items should be inserted to maintain sorted order.
     * Similar to Python NumPy's searchsorted
     *
     * @param float[]|int[] $haystack Sorted array with standard increasing numerical array keys
     * @param float         $needle   Item wanting to insert
     *
     * @return int Index of where you would insert the needle and maintain sorted order
     */
    public static function sorted(array $haystack, float $needle): int
    {
        if (empty($haystack)) {
            return 0;
        }

        $index = 0;
        foreach ($haystack as $i => $val) {
            if ($needle > $val) {
                $index++;
            } else {
                return $index;
            }
        }

        return $index;
    }

    /**
     * ArgMax
     * Find the array index of the maximum value.
     *
     * In case of the maximum value appearing multiple times, the index of the first occurrence is returned.
     * In the case NAN is present, the index of the first NAN is returned.
     *
     * @param array $values
     *
     * @return int Index of the first occurrence of the maximum value
     *
     * @throws Exception\BadDataException if the array of values is empty
     */
    public static function argMax(array $values): int
    {
        if (empty($values)) {
            throw new Exception\BadDataException('Cannot find the argMax of an empty array');
        }

        // Special case: NAN wins if present
        $nanPresent = array_filter(
            $values,
            function ($value) {
                return is_float($value) && is_nan($value);
            }
        );
        if (count($nanPresent) > 0) {
            foreach ($values as $i => $v) {
                if (is_nan($v)) {
                    return $i;
                }
            }
        }

        // Standard case: Find max and return index
        $max = max($values);
        foreach ($values as $i => $v) {
            if ($v === $max) {
                return $i;
            }
        }
    }
}
