<?php
namespace MathPHP\Number;

use MathPHP\Exception;
use MathPHP\Functions\Special;

/**
 * Big Integer
 *
 * The BigInt object is an array of 2 ints.
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
     * Should the constructor use an array so that $this->value can be extended?
     * @param int $v
     * @param int $w
     */
    public function __construct(int $v, int $w = 0)
    {
        $this->value[0] = $v;
        $this->value[1] = $w;
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
        if (is_int($a + $b)) {
            return ['overflow'=> false, 'value' => $a + $b];
        } else {
            $c = $a - (\PHP_INT_MAX - (\PHP_INT_MAX >> 1)) + $b;
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
