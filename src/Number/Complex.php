<?php

namespace MathPHP\Number;

use MathPHP\Exception;
use MathPHP\Functions\Special;

/**
 * Complex Numbers
 *
 * https://en.wikipedia.org/wiki/Complex_number
 */
class Complex
{

    protected $r;
    protected $i;

    public function __construct($r, $i)
    {
        $this->r = $r;
        $this->i = $i;
    }
    
    public function __toString()
    {
        $imag_string = $this->i . "i";
        if ($this->i >= 0) {
            $imag_string = "+" . $imag_string;
        }
        return '(' . $this->r . $imag_string . ')';
    }

    /*
     * Setters and Getters
     */
    public function __get($key)
    {
        if ($key != 'r' && $key != 'i') {
            throw new Exception\BadParameterException('The \'' . $key . '\' property does not exist in this class');
        }
        return $this->$key;
    }
    
    public function getR()
    {
        return $this->r;
    }
    
    public function getI()
    {
        return $this->i;
    }

    /*
     * Unary Functions
     */

    /*
     * The conjugate of a complex number
     *
     * https://en.wikipedia.org/wiki/Complex_number#Conjugate
     */
    public function complexConjugate(): Complex
    {
        return new Complex($this->r, -1 * $this->i);
    }

    /*
     * The absolute value (magnitude) of a complex number
     *
     * https://en.wikipedia.org/wiki/Complex_number#Absolute_value_and_argument
     */
    public function abs()
    {
        return sqrt($this->r ** 2 + $this->i ** 2);
    }
    
    /*
     * The argument (phase) of a complex number
     *
     * https://en.wikipedia.org/wiki/Complex_number#Absolute_value_and_argument
     */
    public function arg()
    {
        return atan2($this->i, $this->r);
    }
    
    /*
     * The square root of a complex number
     *
     * https://en.wikipedia.org/wiki/Complex_number#Square_root
     */
    public function sqrt(): Complex
    {
        $r = sqrt(($this->r + $this->abs()) / 2);
        $i = Special::sgn($this->i) * sqrt(($this->abs() - $this->r) / 2);
        return new Complex($r, $i);
    }

    /*
     * The inverse of a complex number
     *
     * https://en.wikipedia.org/wiki/Complex_number#Reciprocal
     */
    public function inv(): Complex
    {
        return $this->complexConjugate()->divide($this->abs() ** 2);
    }

    /*
     * Binary Functions
     */

    /*
     * Complex addition
     *
     * https://en.wikipedia.org/wiki/Complex_number#Addition_and_subtraction
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

    /*
     * Complex subtraction
     *
     * https://en.wikipedia.org/wiki/Complex_number#Addition_and_subtraction
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

    /*
     * Complex multiplication
     *
     * https://en.wikipedia.org/wiki/Complex_number#Multiplication_and_division
     */
    public function multiply($c): Complex
    {
        if (is_numeric($c)) {
            $r = $c * $this->r;
            $i = $c * $this->i;
        } elseif ($c instanceof Complex) {
            $r = $this->r * $c->r - $this->i * $c->i;
            $i = $this->i * $c->r + $c->i * $this->r;
        } else {
            throw new Exception\IncorrectTypeException('Argument must be real or complex number');
        }
        return new Complex($r, $i);
    }

    /*
     * Complex division
     *
     * Dividing two complex numbers is accomplished by multiplying the first by the inverse of the second
     *
     * https://en.wikipedia.org/wiki/Complex_number#Multiplication_and_division
     */
    public function divide($c): Complex
    {
        if (is_numeric($c)) {
            $r = $this->r / $c;
            $i = $this->i / $c;
            return new Complex($r, $i);
        } elseif ($c instanceof Complex) {
            return $this->multiply($c->inv());
        } else {
            throw new Exception\IncorrectTypeException('Argument must be real or complex number');
        }
    }
    
    /*
     * Comparison Operators
     */

    /*
     * Test for equality
     *
     * https://en.wikipedia.org/wiki/Complex_number#Equality
     */
    public function equals($c): bool
    {
        return $this->r == $c->r && $this->i == $c->i;
    }
}
