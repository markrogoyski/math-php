<?php
namespace MathPHP\Number;

use MathPHP\Exception;
use MathPHP\Functions\Boolean;
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

    /*
     * Decimal to Binary
     *
     * return the binary representation of the number without leading zeroes
     * @return string
     */
    public function decbin(): string
    {
        if ($this->value[1] === 0) {
            return decbin($this->value[0]);
        } else {
            return decbin($this->value[1]) . this->fullDecBin($this->value[0])
    }

    /*
     * Decimal to Binary
     *
     * return the binary representation of a number with leading zeroes
     * @return string
     */
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
        $first = Boolean::bitwiseAdd($this->value[0], $c->get(0));
        $second = Boolean::bitwiseAdd($this->value[1], $c->get(1));
        if ($second['overflow']) {
            return \NAN;
        }
        if ($first['overflow']) {
            $third = Boolean::bitwiseAdd($second['value'], 1);
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
     * Calculate the integer portion of a division operation
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
