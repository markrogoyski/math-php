<?php

namespace MathPHP\Number;

use MathPHP\Algebra;
use MathPHP\Exception;
use MathPHP\Functions\Special;

/**
 * Rational Numbers
 *
 * https://en.wikipedia.org/wiki/Rational_number
 * A rational number can be expressed as a fraction. Using the rational number object allows a user to
 * express non-integer values with exact precision, and perform arithmetic without floating point
 * errors.
 */
class Rational implements ObjectArithmetic
{
    /** @var int Whole part of the number */
    protected $whole;
    
    /** @var int Numerator part of the fractional part */
    protected $numerator;
    
    /** @var int Denominator part of the fractional part */
    protected $denominator;
    
    /**
     * Constructor
     *
     * to do: How to handle negative numbers in various positions.
     * @param int $w whole part
     * @param int $n numerator part
     * @param int $d
     */
    public function __construct(int $w, int $n, int $d)
    {
        list($w, $n, $d) = self::normalize($w, $n, $d);
        $this->whole = $w;
        $this->numerator = $n;
        $this->denominator = $d;
    }
    
    /**
     * String representation of a rational number
     * 5 6/7, 456079/13745859, etc.
     *
     * @return string
     */
    public function __toString(): string
    {
        $sign = "";
        $whole = "";
        $fraction = "";
        if (Special::sgn($this->whole) == -1 || Special::sgn($this->numerator) == -1) {
            $sign = "-";
        }
        if ($this->whole !== 0) {
            $whole = abs($this->whole);
        }
        if ($this->numerator !== 0) {
            if ($this->whole !== 0) {
                $whole .= " ";
            }
            $fraction = $this->NumeratorToSuperscript() . "/" . $this->DenominatorToSubscript();
        }
        $string = $sign . $whole . $fraction;
        if ($string == "") {
            $string = "0";
        }
        return $string;
    }
    
    private function NumeratorToSuperscript()
    {
        return $this->toSuperOrSubscript(abs($this->numerator), "superscript");
    }
    
    private function DenominatorToSubscript()
    {
        return $this->toSuperOrSubscript($this->denominator, "subscript");
    }
    
    private function toSuperOrSubscript(int $i, string $super_or_sub): string
    {
        $return_string = "";
        $chars = ['⁰', '¹', '²', '³', '⁴', '⁵', '⁶', '⁷', '⁸', '⁹'];
        if ($super_or_sub == "subscript") {
            $chars = ['₀', '₁', '₂', '₃', '₄', '₅', '₆', '₇', '₈', '₉'];
        }
        $number_of_chars = floor(log10($i) + 1);
        $working_value = $i;
        for ($j = $number_of_chars - 1; $j >= 0; $j--) {
            $int = intdiv($working_value, 10 ** $j);
            $return_string .= $chars[$int];
            $working_value -= $int * 10 ** $j;
        }
        return $return_string;
    }
    
    public function toFloat()
    {
        $frac = $this->numerator / $this->denominator;
        $sum = $this->whole + $frac;
        return $sum;
    }
    
    /**************************************************************************
     * UNARY FUNCTIONS
     **************************************************************************/
 
    /**
     * The absolute value of a rational number
     * @return RationalNumber
     */
    public function abs()
    {
        return new Rational(abs($this->whole), abs($this->numerator), abs($this->denominator));
    }

    /**************************************************************************
     * BINARY FUNCTIONS
     **************************************************************************/
 
    /**
     * Addition
     *
     * @param mixed $c
     *
     * @return RationalNumber
     *
     * @throws Exception\IncorrectTypeException if the argument is not numeric or Rational.
     */
    public function add($r): Rational
    {
        $w = $this->whole;
        $n = $this->numerator;
        $d = $this->denominator;
        if (is_int($r)) {
            $w += $r;
        } elseif ($r instanceof Rational) {
            $rn = $r->numerator;
            $rd = $r->denominator;
            $rw = $r->whole;
            
            $w += $rw;
            
            $lcm = Algebra::lcm($d, $rd);
            $n = $n * intdiv($lcm, $d) + $rn * intdiv($lcm, $rd);
            $d = $lcm;
        } else {
            throw new Exception\IncorrectTypeException('Argument must be an integer or RationalNumber');
        }
        return new Rational($w, $n, $d);
    }

    /**
     * Subtraction
     *
     */
    public function subtract($r): Rational
    {
        if (is_int($r)) {
            return $this->add(-1 * $r);
        } elseif ($r instanceof Rational) {
            return $this->add($r->multiply(-1));
        } else {
            throw new Exception\IncorrectTypeException('Argument must be an integer or RationalNumber');
        }
    }

    /**
     * Multiply
     *
     * Return the result of multiplying two rational numbers, or a rational number and an integer.
     *
     * @param mixed $r
     * $return RationalNumber
     */
    public function multiply($r): Rational
    {
        $w = $this->whole;
        $n = $this->numerator;
        $d = $this->denominator;

        if (is_int($r)) {
            $w *= $r;
            $n *= $r;
            return new Rational($w, $n, $d);
        } elseif ($r instanceof Rational) {
            $w2 = $r->whole;
            $n2 = $r->numerator;
            $d2 = $r->denominator;
            
            $new_w = $w * $w2;
            $new_n = $w * $n2 * $d + $w2 * $n * $d2 + $n2 * $n;
            $new_d = $d * $d2;
            return new Rational($new_w, $new_n, $new_d);
        } else {
            throw new Exception\IncorrectTypeException('Argument must be an integer or RationalNumber');
        }
    }
    
    /**
     * Divide
     *
     * Return the result of dividing two rational numbers, or a rational number by an integer.
     *
     * @param mixed $r
     * $return RationalNumber
     */
    public function divide($r): Rational
    {
        $w = $this->whole;
        $n = $this->numerator;
        $d = $this->denominator;

        if (is_int($r)) {
            return new Rational(0, $w * $d + $n, $r * $d);
        } elseif ($r instanceof Rational) {
            $w2 = $r->whole;
            $n2 = $r->numerator;
            $d2 = $r->denominator;
            
            $new_w = 0;
            $new_n = $d2 * ($w * $d + $n);
            $new_d = $d * ($w2 * $d2 + $n2);
            return new Rational($new_w, $new_n, $new_d);
        } else {
            throw new Exception\IncorrectTypeException('Argument must be an integer or RationalNumber');
        }
    }

    /**************************************************************************
     * COMPARISON FUNCTIONS
     **************************************************************************/

    /**
     * Test for equality
     *
     * Two normalized RationalNumbers are equal IFF all three parts are equal.
     */
    public function equals(Rational $rn): bool
    {
        return $this->whole == $rn->whole && $this->numerator == $rn->numerator && $this->denominator == $rn->denominator;
    }

    /**
     * Normalize the input
     *
     * We want to ensure that the format of the data in the object is correct.
     * We will ensure that the numerator is smaller than the denominator, the sign
     * of the denominator is always positive, and the signs of the numerator and
     * whole number match.
     */
    private function normalize(int $w, int $n, int $d): array
    {
        if ($d == 0) {
            throw new Exception\BadDataException('Denominator cannot be zero');
        }
        // Make sure $d is positive
        if ($d < 0) {
            $n *= -1;
            $d *= -1;
        }

        // Reduce the fraction
        if (abs($n) >= $d) {
            $w += intdiv($n, $d);
            $n = $n % $d;
        }
        $gcd = 0;
        while ($gcd != 1 && $n !== 0) {
            $gcd = abs(Algebra::gcd($n, $d));
            $n /= $gcd;
            $d /= $gcd;
        }

        // Make the signs of $n and $w match
        if (Special::sgn($w) !== Special::sgn($n) && $w !== 0 && $n !== 0) {
            $w = $w - Special::sgn($w);
            $n = ($d - abs($n)) * Special::sgn($w);
        }

        if ($n == 0) {
            $d = 1;
        }
        return [$w, $n, $d];
    }
}
