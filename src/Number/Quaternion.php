<?php

namespace MathPHP\Number;

use MathPHP\Exception;
use MathPHP\Functions\Special;

/**
 * Quaternionic Numbers
 *
 * A quaternion is a number that can be expressed in the form a + bi + cj + dk,
 * where a, b, c, andd are real numbers and i, j, and k are the basic quaternions, satisfying the
 * equation i² = j² = k² = ijk = −1.
 * https://en.wikipedia.org/wiki/Quaternion
 */
class Quaternion implements ObjectArithmetic
{
    /**
     * Real part of the quaternionic number
     * @var number
     */
    protected $r;

    /**
     * First Imaginary part of the quaternionic number
     * @var number
     */
    protected $i;

    /**
     * Second Imaginary part of the quaternionic number
     * @var number
     */
    protected $j;

    /**
     * Third Imaginary part of the quaternionic number
     * @var number
     */
    protected $k;

    /**
     * Floating-point range near zero to consider insignificant.
     */
    const EPSILON = 1e-6;

    /**
     * Constructor
     *
     * @param number $r Real part
     * @param number $i Imaginary part
     * @param number $j Imaginary part
     * @param number $k Imaginary part
     */
    public function __construct($r, $i, $j, $k)
    {
        if (!\is_numeric($r) || !\is_numeric($i) || !\is_numeric($j) || !\is_numeric($k)) {
            throw new Exception\BadDataException('Values must be real numbers.');
        }
        $this->r = $r;
        $this->i = $i;
        $this->j = $j;
        $this->k = $k;
    }

    /**
     * Creates 0 + 0i
     *
     * @return Complex
     */
    public static function createZeroValue(): ObjectArithmetic
    {
        return new Quaternion(0, 0, 0, 0);
    }

    /**
     * String representation of a complex number
     * a + bi + cj + dk, a - bi - cj - dk, etc.
     *
     * @return string
     */
    public function __toString(): string
    {
        if ($this->r == 0 & $this->i == 0 & $this->j == 0 & $this->k == 0) {
            return '0';
        }
        $string = self::stringifyNumberPart($this->r);
        $string = self::stringifyNumberPart($this->i, 'i', $string);
        $string = self::stringifyNumberPart($this->j, 'j', $string);
        return self::stringifyNumberPart($this->k, 'k', $string);
    }

    /**
     * Get r or i or j or k
     *
     * @param string $part
     *
     * @return number
     *
     * @throws Exception\BadParameterException if something other than r or i is attempted
     */
    public function __get(string $part)
    {
        switch ($part) {
            case 'r':
            case 'i':
            case 'j':
            case 'k':
                return $this->$part;

            default:
                throw new Exception\BadParameterException("The $part property does not exist in Quaternion");
        }
    }

    /**************************************************************************
     * UNARY FUNCTIONS
     **************************************************************************/

    /**
     * The conjugate of a quaternion
     *
     * https://en.wikipedia.org/wiki/Quaternion#Conjugation.2C_the_norm.2C_and_reciprocal
     *
     * @return Quaternion
     */
    public function complexConjugate(): Quaternion
    {
        return new Quaternion($this->r, -1 * $this->i, -1 * $this->j, -1 * $this->k);
    }

    /**
     * The absolute value (magnitude) of a quaternion or norm
     * https://en.wikipedia.org/wiki/Quaternion#Conjugation.2C_the_norm.2C_and_reciprocal
     *
     * If z = a + bi + cj + dk
     *        _________________
     * |z| = √a² + b² + c² + d²
     *
     * @return number
     */
    public function abs()
    {
        return sqrt($this->r ** 2 + $this->i ** 2 + $this->j ** 2 + $this->k ** 2);
    }

    /**
     * The inverse of a quaternion (reciprocal)
     *
     * https://en.wikipedia.org/wiki/Quaternion#Conjugation.2C_the_norm.2C_and_reciprocal
     *
     * @return Quaternion
     *
     * @throws Exception\BadDataException if = to 0 + 0i
     */
    public function inverse(): Quaternion
    {
        if ($this->r == 0 && $this->i == 0 && $this->j == 0 && $this->k == 0) {
            throw new Exception\BadDataException('Cannot take inverse of 0 + 0i');
        }

        return $this->complexConjugate()->divide($this->abs() ** 2);
    }

    /**
     * Negate the quaternion
     * Switches the signs of both the real and imaginary parts.
     *
     * @return Quaternion
     */
    public function negate(): Quaternion
    {
        return new Quaternion(-$this->r, -$this->i, -$this->j, -$this->k);
    }

    /**************************************************************************
     * BINARY FUNCTIONS
     **************************************************************************/

    /**
     * Quaternion addition
     *
     *
     * (a + bi + cj + dk) - (e + fi + gj + hk) = (a + e) + (b + f)i + (c + g)j + (d + h)k
     *
     * @param mixed $q
     *
     * @return Quaternion
     *
     * @throws Exception\IncorrectTypeException if the argument is not numeric or Complex.
     */
    public function add($q): Quaternion
    {
        if (is_numeric($q)) {
            $r = $this->r + $q;
            $i = $this->i;
            $j = $this->j;
            $k = $this->k;
        } elseif ($q instanceof Quaternion) {
            $r = $this->r + $q->r;
            $i = $this->i + $q->i;
            $j = $this->j + $q->j;
            $k = $this->k + $q->k;
        } else {
            throw new Exception\IncorrectTypeException('Argument must be real or quaternion');
        }
        return new Quaternion($r, $i, $j, $k);
    }

    /**
     * Quaternion subtraction
     *
     *
     * (a + bi + cj + dk) - (e + fi + gj + hk) = (a - e) + (b - f)i + (c - g)j + (d - h)k
     *
     * @param mixed $q
     *
     * @return Quaternion
     *
     * @throws Exception\IncorrectTypeException if the argument is not numeric or Complex.
     */
    public function subtract($q): Quaternion
    {
        if (is_numeric($q)) {
            $r = $this->r - $q;
            $i = $this->i;
            $j = $this->j;
            $k = $this->k;
        } elseif ($q instanceof Quaternion) {
            $r = $this->r - $q->r;
            $i = $this->i - $q->i;
            $j = $this->j - $q->j;
            $k = $this->k - $q->k;
        } else {
            throw new Exception\IncorrectTypeException('Argument must be real or quaternion');
        }

        return new Quaternion($r, $i, $j, $k);
    }

    /**
     * Quaternion multiplication
     *
     * Quaternion multiplication is not commutative.
     *
     * @param mixed $q
     *
     * @return Quaternion
     *
     * @throws Exception\IncorrectTypeException if the argument is not numeric or Complex.
     */
    public function multiply($q): Quaternion
    {
        $a = $this->r;
        $b = $this->i;
        $c = $this->j;
        $d = $this->k;
        if (is_numeric($q)) {
            $e = $q;
            $f = 0;
            $g = 0;
            $h = 0;
        } elseif ($c instanceof Quaternion) {
            $e = $q->r;
            $f = $q->i;
            $g = $q->j;
            $h = $q->k;
        } else {
            throw new Exception\IncorrectTypeException('Argument must be real or quaternion');
        }
        return new Quaternion(
            $a * $e - $b * $f - $c * $g - $d * $h,
            $b * $e + $a * $f + $c * $h - $d * $g,
            $a * $g - $b * $h + $c * $e + $d * $f,
            $a * $h + $b * $g - $c * $f + $d * $e
        );
    }

    /**
     * Quaternion division
     * Dividing two quaternions is accomplished by multiplying the first by the inverse of the second
     * This is not commutative!
     *
     * @param mixed $q
     *
     * @return Quaternion
     *
     * @throws Exception\IncorrectTypeException if the argument is not numeric or Complex.
     */
    public function divide($q): Quaternion
    {
        if (is_numeric($q)) {
            $r = $this->r / $q;
            $i = $this->i / $q;
            $j = $this->j / $q;
            $k = $this->k / $q;
            return new Quaternion($r, $i, $j, $k);
        } elseif ($q instanceof Quaternion) {
            return $this->multiply($q->inverse());
        } else {
            throw new Exception\IncorrectTypeException('Argument must be real or quaternion');
        }
    }

    /**************************************************************************
     * COMPARISON FUNCTIONS
     **************************************************************************/

    /**
     * Test for equality
     * Two quaternions are equal if and only if both their real and imaginary parts are equal.
     *
     *
     * @param Quaternion $q
     *
     * @return bool
     */
    public function equals(Quaternion $q): bool
    {
        return \abs($this->r - $q->r) < self::EPSILON && \abs($this->i - $q->i) < self::EPSILON
            && \abs($this->j - $q->j) < self::EPSILON && \abs($this->k - $q->k) < self::EPSILON;
    }

    /**************************************************************************
     * PRIVATE FUNCTIONS
     **************************************************************************/

    /**
     * Stringify an additional part of the quaternion
     */
    private static function stringifyNumberPart($q, string $unit = '', string $string = '')
    {
        if ($q == 0) {
            return $string;
        }
        if ($q > 0) {
            $plus = $string == '' ? '' : ' + ';
            return $string . $plus . "$q" . $unit;
        }
        return $string . ' - ' . (string) \abs($q) . $unit;
    }
}
