<?php
namespace MathPHP\Number;

use MathPHP\Exception;
use MathPHP\Functions\Special;

/**
 * Big Integer
 *
 * The BigInt object is an array of 2 ints. Concatenating the two ints
 * provides the binary version of the integer in 2s compliment form. 
 */
class BigInt implements ObjectArithmetic
{
    /**
     * Real part of the complex number
     * @var array
     */
    protected $value;
    
    /**
     * Constructor
     *
     * convert an int to a BigInt
     * @param int $v
     */
    public function __construct(int $v)
    {
        $this->value[0] = $v;
        $this->value[1] = $v > 0 ? 0: -1;
    }
    
    /**
     * String representation of a BigInt
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->value[0];
    }

    public function get(int $n): int
    {
        return $this->value[$n];
    }

    /**************************************************************************
     * UNARY FUNCTIONS
     **************************************************************************/

    /**
     * The absolute value of a BigInt
     */
    public function abs(): BigInt
    {
        return \NAN;
    }

    public function decbin(): string
    {
        return $this->fullDecBin($this->value[1]) . $this->fullDecBin($this->value[0]);
    }

    // Binary representation of a number with leading zeroes.
    private function fullDecBin($v): string
    {
        $bits = strlen(decbin(-1));
        return str_pad(decbin($v), $bits, '0', STR_PAD_LEFT);
    }

    /**************************************************************************
     * BINARY FUNCTIONS
     **************************************************************************/

    /**
     * Addition
     *
     */
    public function add($c): BigInt
    {
        $first = self::bitwiseAdd($this->value[0], $c->get(0));
        $second = self::bitwiseAdd($this->value[1], $c->get(1));
        if ($second['overflow']) {
            return \NAN;
        }
        if ($first['overflow']) {
            $third = self::bitwiseAdd($second['value'], 1);
        } else {
            $third = $second;
        }
        if ($third['overflow']) {
            return \NAN;
        }
        return new BigInt($first['value'], $third['value']);
    }

    /**
     * Subtraction
     */
    public function subtract($c): BigInt
    {
        return \NAN;
    }

    /**
     * Multiplication
     */
    public function multiply($c): BigInt
    {
        return \NAN;
    }

    /**
     * Division
     *
     * Returns the division of two BigInt, iff they are evenly divisible.
     */
    public function divide($c): BigInt
    {
        if (self::mod($c) === 0) {
            return $this->intdiv($c);
        } else {
            return \NAN;
        }
    }

    /**
     * Integer Division
     *
     * Calculate the integer prtion of a division operation
     */
    public function intdiv($c): BigInt
    {
        return \NAN;
    }

    /**
     * Mod
     * The remainder of integer division
     */
    public function mod(int $c): BigInt
    {
        return \NAN;
    }

    /**
     * Bitwise add two ints and return the result and if it overflows.
     */
    private function bitwiseAdd(int $a, int $b): array
    {
        if (is_int($a + $b) && $a >= 0 || $b >= 0) {
            $sum = $a + $b;
            return ['overflow'=> $a < 0 || $b < 0 && $sum >= 0, 'value' => $sum];
        } else {
            $c = (\PHP_INT_MAX - $a - $b) * -1 + \PHP_INT_MIN;
            return ['overflow'=> true, 'value' => $c];
        }
    }
    
    /**************************************************************************
     * COMPARISON FUNCTIONS
     **************************************************************************/

    /**
     * Test for equality
     *
     * @return bool
     */
    public function equals(BigInt $c): bool
    {
        return $this->value[0] == $c->get(0) && $this->value[1] == $c->get(1);
    }
}
