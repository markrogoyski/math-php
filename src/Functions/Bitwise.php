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
            $sum = $a + $b;
            $overflow = (($a < 0 || $b < 0) && $sum >= 0) || ($a < 0 && $b < 0);
            return ['overflow'=> $overflow, 'value' => $sum];
        } elseif ($a > 0 && $b > 0) {
            $c = $a - \PHP_INT_MAX + $b - 1 + \PHP_INT_MIN;
            return ['overflow'=> false, 'value' => $c];
        } else {
            $a = $a + \PHP_INT_MAX + 1;
            $b = $b + \PHP_INT_MAX + 1;
            $c = $a + $b;
            return ['overflow'=> true, 'value' => $c];
        }
    }
}
