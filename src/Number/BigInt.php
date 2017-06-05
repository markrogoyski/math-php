<?php
namespace MathPHP\Number;

use MathPHP\Exception;
use MathPHP\Functions\Special;

/**
 * Big Integer
 *
 * The BigInt obkect is an array of 2 ints. This allows us to represent
 * numbers up to 64 bits.
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
     * @param mixed $v int 
     */
    public function __construct(int $r)
    {
        $this->value[0] = $v;
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
    
    /**************************************************************************
     * UNARY FUNCTIONS
     **************************************************************************/

    /**
     * The absolute value (magnitude) of a complex number (modulus)
     * https://en.wikipedia.org/wiki/Complex_number#Absolute_value_and_argument
     *
     * If z = a + bi
     *        _______
     * |z| = √a² + b²
     *
     * @return number
     */
    public function abs()
    {
        return sqrt($this->r**2 + $this->i**2);
    }
    
    

    /**
     * The inverse of a complex number (reciprocal)
     *
     * https://en.wikipedia.org/wiki/Complex_number#Reciprocal
     *
     * @return Complex
     *
     * @throws Exception\BadDataException if = to 0 + 0i
     */
    public function inverse(): Complex
    {
        if ($this->r == 0 && $this->i == 0) {
            throw new Exception\BadDataException('Cannot take inverse of 0 + 0i');
        }

        return $this->complexConjugate()->divide($this->abs() ** 2);
    }

    /**
     * Negate the complex number
     * Switches the signs of both the real and imaginary parts.
     *
     * @return Complex
     */
    public function negate(): Complex
    {
        return new Complex(-$this->r, -$this->i);
    }

    /**************************************************************************
     * BINARY FUNCTIONS
     **************************************************************************/

    /**
     * Addition
     * https://en.wikipedia.org/wiki/Complex_number#Addition_and_subtraction
     *
     * (a + bi) + (c + di) = (a + c) + (b + d)i
     *
     * @param mixed $c
     *
     * @return Complex
     *
     * @throws Exception\IncorrectTypeException if the argument is not numeric or Complex.
     */
    public function add($c): Complex
    {
        if (is_numeric($c)) {
            $r = $this->r + $c;
            $i = $this->i;
        } elseif ($c instanceof Complex) {
            $r = $this->r + $c->r;
            $i = $this->i + $c->i;
        } else {
            throw new Exception\IncorrectTypeException('Argument must be real or complex number');
        }

        return new Complex($r, $i);
    }

    /**
     * Complex subtraction
     * https://en.wikipedia.org/wiki/Complex_number#Addition_and_subtraction
     *
     * (a + bi) - (c + di) = (a - c) + (b - d)i
     *
     * @param mixed $c
     *
     * @return Complex
     *
     * @throws Exception\IncorrectTypeException if the argument is not numeric or Complex.
     */
    public function subtract($c): Complex
    {
        if (is_numeric($c)) {
            $r = $this->r - $c;
            $i = $this->i;
        } elseif ($c instanceof Complex) {
            $r = $this->r - $c->r;
            $i = $this->i - $c->i;
        } else {
            throw new Exception\IncorrectTypeException('Argument must be real or complex number');
        }

        return new Complex($r, $i);
    }

    /**
     * Complex multiplication
     * https://en.wikipedia.org/wiki/Complex_number#Multiplication_and_division
     *
     * (a + bi)(c + di) = (ac - bd) + (bc + ad)i
     *
     * @param mixed $c
     *
     * @return Complex
     *
     * @throws Exception\IncorrectTypeException if the argument is not numeric or Complex.
     */
    public function multiply($c): Complex
    {
        if (is_numeric($c)) {
            $r = $c * $this->r;
            $i = $c * $this->i;
        } elseif ($c instanceof Complex) {
            $r = $this->r * $c->r - $this->i * $c->i;
            $i = $this->i * $c->r + $this->r * $c->i;
        } else {
            throw new Exception\IncorrectTypeException('Argument must be real or complex number');
        }

        return new Complex($r, $i);
    }

    /**
     * Complex division
     * Dividing two complex numbers is accomplished by multiplying the first by the inverse of the second
     * https://en.wikipedia.org/wiki/Complex_number#Multiplication_and_division
     *
     * @param mixed $c
     *
     * @return Complex
     *
     * @throws Exception\IncorrectTypeException if the argument is not numeric or Complex.
     */
    public function divide($c): Complex
    {
        if (is_numeric($c)) {
            $r = $this->r / $c;
            $i = $this->i / $c;
            return new Complex($r, $i);
        } elseif ($c instanceof Complex) {
            return $this->multiply($c->inverse());
        } else {
            throw new Exception\IncorrectTypeException('Argument must be real or complex number');
        }
    }
    
    /**************************************************************************
     * COMPARISON FUNCTIONS
     **************************************************************************/

    /**
     * Test for equality
     * Two complex numbers are equal if and only if both their real and imaginary parts are equal.
     *
     * https://en.wikipedia.org/wiki/Complex_number#Equality
     *
     * @param Complex $c
     *
     * @return bool
     */
    public function equals(Complex $c): bool
    {
        return abs($this->r - $c->r) < self::EPSILON && abs($this->i - $c->i) < self::EPSILON;
    }
}
