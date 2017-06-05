<?php

namespace MathPHP\Functions;

class Boolean
{

    /**
     * Bitwise add two ints and return the result and if it overflows.
     */
    public static function bitwiseAdd(int $a, int $b): array
    {
        if (is_int($a + $b) && ($a >= 0 || $b >= 0)) {
            $sum = $a + $b;
            return ['overflow'=> ($a < 0 || $b < 0) && $sum >= 0, 'value' => $sum];
        } else {
            $c = (\PHP_INT_MAX - $a - $b) * -1 + \PHP_INT_MIN;
            return ['overflow'=> true, 'value' => $c];
        }
    }
}
