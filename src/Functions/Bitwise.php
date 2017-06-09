<?php

namespace MathPHP\Functions;

class Bitwise
{

    /**
     * Add two ints ignoring the signing bit.
     *
     * 8 bit examples:
     * 0d15 + 0d1 = 0d16
     * 0b00001111 + 0b00000001 = 0b00010000
     *
     * 0d127 + 0d1 = 0d-128
     * 0b01111111 + 0b00000001 = 0b10000000
     *
     * 0d-1 + 0d1 = 0d0
     * 0b11111111 + 0b00000001 = 0b00000000 :overflow = true
     *
     * 0d-1 + 0d-1 = 0d-2
     * 0b11111111 + 0b11111111 = 0b11111110: overflow = true
     *
     * @param int $a
     * @param int $b
     *
     * @return array
     *    'overflow' is true if the result is larger than the bits in an int
     *    'value' is the result of the addition ignoring any overflow.
     */
    public static function bitwiseAdd(int $a, int $b): array
    {
        if (is_int($a + $b)) {
            // $a and $b are negative, the most significant bit will overflow.
            // If only one is negative, the most significant bit will overflow if the sum
            // is positive.
            $sum = $a + $b;
            $overflow = (($a < 0 || $b < 0) && $sum >= 0) || ($a < 0 && $b < 0);
            return ['overflow'=> $overflow, 'value' => $sum];
        } elseif ($a > 0 && $b > 0) {
            // If $a + $b overflows as a signed int, it is now a negative int, but the most significant
            // bit will not overflow.
            $c = $a - \PHP_INT_MAX + $b - 1 + \PHP_INT_MIN;
            return ['overflow'=> false, 'value' => $c];
        } else {
            // The sum of two "large" negative numbers will both overflow the most significant bit
            // and the signed int.
            // The values of $a and $b have to be shifted towards zero to prevent the
            // signed int from overflowing. We are removing the most significant
            // bit from the ints by subtractingPHP_INT_MIN to prevent overflow.
            // $a = 1001, $b = 1010, return [true, '0011] because \PHP_INT_MIN = 1000,
            // Giving $a - 1000 = 0001, $b - 1000 = 0010.
            $a -= \PHP_INT_MIN;
            $b -= \PHP_INT_MIN;
            $c = $a + $b;
            return ['overflow'=> true, 'value' => $c];
        }
    }
}
