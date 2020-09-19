<?php

namespace MathPHP\Util;

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
}
