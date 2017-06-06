<?php

namespace MathPHP\Functions;

class Boolean
{

    /**
     * Bitwise add two ints and return the result and if it overflows.
     */
    public static function bitwiseAdd(int $a, int $b): array
    {
        if (is_int($a + $b)) {
            $sum = $a + $b;
            return ['overflow'=> !($a > 0 && $b > 0), 'value' => $sum];
        } elseif ($a > 0 && $b > 0) {
            $c = (\PHP_INT_MAX - $a - $b) * -1 + \PHP_INT_MIN;
            return ['overflow'=> false, 'value' => $c];
        } else {
            $a = $a + \PHP_INT_MAX + 1;
            $b = $b + \PHP_INT_MAX + 1;
            $c = $a + $b;
            return ['overflow'=> true, 'value' => $c];
        }
    }
}
