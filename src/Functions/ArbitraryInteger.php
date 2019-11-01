<?php

namespace MathPHP\Functions;

use MathPHP\Exception;
use MathPHP\Number\ArbitraryInteger as Integer;

/**
 * Functions that operate with ArbitraryInteger objects
 */
class ArbitraryInteger
{
    
    /**
     * Prepare input value for construction
     *
     * @param  int|string|ArbitraryInteger $number
     * @return ArbitraryInteger
     */
    private static function prepareParameter($number): Integer
    {
        if (!is_object($number)) {
            return new Integer($number);
        }
        $class = get_class($number);
        if ($class == Integer::class) {
            return $number;
        }
        throw new Exception\IncorrectTypeException("Class of type $class is not supported.");
    }

    /**
     * Ackermann Function
     * A well known highly recursive function which produces very large numbers
     *
     * https://en.wikipedia.org/wiki/Ackermann_function
     *
     * @param $m
     * @param $n
     * @return ArbitraryInteger
     */
    public static function ackermann($m, $n): Integer
    {
        $m = self::prepareParameter($m);
        $n = self::prepareParameter($n);
        if ($m->equals(0)) {
            return $n->add(1);
        } elseif ($m->equals(1)) {
            return $n->add(2);
        } elseif ($m->equals(2)) {
            return $n->leftShift(1)->add(3);
        } elseif ($m->equals(3)) {
            $one = new Integer(1);
            // 2^(n+3) - 3
            return $one->leftShift($n->add(3))->subtract(3);
        } elseif ($n->equals(0)) {
            return self::ackermann($m->subtract(1), 1);
        } else {
            return self::ackermann($m->subtract(1), self::ackermann($m, $n->subtract(1)));
        }
    }

    /**
     * Create a random ArbitraryInteger
     *
     * @param int $bytes
     * @return ArbitraryInteger
     */
    public static function rand(int $bytes): Integer
    {
        if ($bytes <= 0) {
            throw new Exception\BadParameterException('Cannot produce a random number with zero or negative bytes.');
        }
        return Integer::fromBinary(random_bytes($bytes), mt_rand(0, 1) === 0);
    }
}
