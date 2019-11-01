<?php

namespace MathPHP\Number;

use MathPHP\Exception;
use MathPHP\Functions\BaseEncoderDecoder;

/**
 * Arbitrary Length Integer
 *
 * An object to manipulate integers of arbitrary size.
 * The object should be able to store values from 0 up to (256 ** PHP_MAX_INT) - 1
 *
 * http://www.faqs.org/rfcs/rfc3548.html
 */
class ArbitraryInteger implements ObjectArithmetic
{
    /** @var string number in binary format */
    protected $base256;

    /** @var bool is the number positive or negative */
    protected $positive;

    /**
     * Constructor
     *
     * If the constructor is given an integer, this value is used for the value of the object.
     * If a string is given, it must be in one of the standard PHP integer base formats.
     *
     * @param string|integer $number
     */
    public function __construct($number)
    {
        $this->positive = true;
        
        if (is_int($number)) {
            if ($number < 0) {
                // Since abs(PHP_INT_MIN) is PHP_INT_MAX + 1, we cannot just change the sign.
                // This is more universal then making a single edge case for PHP_INT_MIN
                $positive = new ArbitraryInteger(-1 * ($number + 1));
                $this->base256 = $positive->add(1)->toBinary();
                $this->positive = false;
            } else {
                $int_part = intdiv($number, 256);
                $string = chr($number % 256);
                while ($int_part > 0) {
                    $string = chr($int_part % 256) . $string;
                    $int_part = intdiv($int_part, 256);
                }
                $this->base256 = $string;
            }
        } elseif (is_string($number)) {
            if ($number == '') {
                throw new Exception\BadParameterException("String cannot be empty.");
            }
            if ($number[0] == '-') {
                $this->positive = false;
                $number = substr($number, 1);
            }
            $number = strtolower($number);
            $base = 10;
            if ($number[0] == '0') {
                if ($number == '0') {
                    $base = 10;
                } elseif ($number[1] == 'x') {
                    $base = 16;
                    $number = substr($number, 2);
                } elseif ($number[1] == 'b') {
                    $base = 2;
                    $number = substr($number, 2);
                } else {
                    $base = 8;
                    $number = substr($number, 1);
                }
            }
            $base256 = BaseEncoderDecoder::createArbitraryInteger($number, $base);
            $this->base256 = $base256->toBinary();
        } else {
            // Not an int, and not a string
            throw new Exception\IncorrectTypeException("Number can only be an int or a string: type '" . gettype($number) . "' provided");
        }
    }

    /**
     * Construct from binary
     *
     * @param  string           $value
     * @param  bool             $positive
     * @return ArbitraryInteger
     */
    public static function fromBinary(string $value, bool $positive): ArbitraryInteger
    {
        $result = new ArbitraryInteger(0);
        $result->setVariables($value, $positive);
        return $result;
    }

    /**
     * Directly set the class properties
     *
     * @param string $value
     * @param bool $positive
     */
    protected function setVariables(string $value, bool $positive)
    {
        $value = ltrim($value, chr(0));
        if ($value == "") {
            $value = chr(0);
        }
        $this->base256 = $value;
        $this->positive = $positive;
    }

    /**
     * Convert ArbitraryInteger to an int
     *
     * @return int
     */
    public function toInt(): int
    {
        $number = str_split(strrev($this->base256));
        $place_value = 1;
        $int = ord($number[0]);
        unset($number[0]);
        foreach ($number as $digit) {
            $place_value *= 256;
            $int += ord($digit) * $place_value;
        }
        return $int * ($this->positive ? 1 : -1);
    }

    /**
     * Convert ArbitraryInteger to a float
     *
     * @return float
     */
    public function toFloat(): float
    {
        $number = str_split(strrev($this->base256));
        $place_value = 1;
        $float = ord($number[0]);
        unset($number[0]);
        foreach ($number as $digit) {
            $place_value *= 256;
            $float += ord($digit) * $place_value;
        }
        return floatval($float) * ($this->positive ? 1 : -1);
    }

    /**
     * Prepare input value for construction
     *
     * @param  int|string|ArbitraryInteger $number
     * @return ArbitraryInteger
     */
    private static function prepareParameter($number): ArbitraryInteger
    {
        if (!is_object($number)) {
            return new ArbitraryInteger($number);
        }
        $class = get_class($number);
        if ($class == self::class) {
            return $number;
        }
        throw new Exception\IncorrectTypeException("Class of type $class is not supported.");
    }
    
    /**
     * To String
     *
     * Display the number in base 10
     * @return string
     */
    public function __toString(): string
    {
        $sign = $this->positive ? '' : '-';
        return $sign . BaseEncoderDecoder::toBase($this, 10);
    }

    /**
     * Return the number as a binary string
     * @return string
     */
    public function toBinary(): string
    {
        return $this->base256;
    }

    /**
     * is the number positive?
     * @return bool
     */
    public function isPositive(): bool
    {
        return $this->positive;
    }

    /**************************************************************************
     * UNARY FUNCTIONS
     **************************************************************************/

    /**
     * Multiply by -1
     *
     * If $this is zero, then do nothing
     */
    public function negate(): ArbitraryInteger
    {
        return self::fromBinary($this->base256, $this->base256 == chr(0) ? true : !$this->positive);
    }

    /**
     * Integer Square Root
     *
     * Calculate the largest integer less than the square root of an integer.
     * https://en.wikipedia.org/wiki/Integer_square_root
     */
    public function isqrt(): ArbitraryInteger
    {
        $length = strlen($this->base256);
        // Start close to the value, at a number around half the digits.
        $X = self::fromBinary(substr($this->base256, 0, intdiv($length, 2) + 1), true);
        $lastX = $X;
        $converge = false;
        while (!$converge) {
            $NX = $this->intdiv($X);
            $X = $X->add($NX)->intdiv(2);
            if ($X->equals($lastX) || $X->equals($lastX->add(1))) {
                $converge = true;
            } else {
                $lastX = $X;
            }
        }
        return $lastX;
    }

    /**
     * Absolute Value
     *
     * @return ArbitraryInteger
     */
    public function abs(): ArbitraryInteger
    {
        return self::fromBinary($this->base256, true);
    }

    /**************************************************************************
     * BINARY FUNCTIONS
     **************************************************************************/

    /**
     * Addition
     *
     * @param int|string|ArbitraryInteger $number
     *
     * @return ArbitraryInteger
     *
     */
    public function add($number): ArbitraryInteger
    {
        $number = self::prepareParameter($number);
        if (!$number->isPositive()) {
            return $this->subtract($number->negate());
        }
        if (!$this->positive) {
            return $number->subtract($this->negate());
        }
        $number = $number->toBinary();
        $carry = 0;
        $len = strlen($this->base256);
        $num_len = strlen($number);
        $max_len = max($len, $num_len);
        $base_256 = str_pad($this->base256, $max_len, chr(0), STR_PAD_LEFT);
        $number = str_pad($number, $max_len, chr(0), STR_PAD_LEFT);
        $result = '';
        for ($i = 0; $i < $max_len; $i++) {
            $base_chr = ord($base_256[$max_len - $i - 1]);
            $num_chr = ord($number[$max_len - $i - 1]);
            $sum = $base_chr + $num_chr + $carry;
            $carry = intdiv($sum, 256);
            
            $result = chr($sum % 256) . $result;
        }
        if ($carry > 0) {
            $result = chr($carry) . $result;
        }
        return self::fromBinary($result, true);
    }

    /**
     * Subtraction
     *
     * Calculate the difference between two numbers
     *
     * @param int|string|ArbitraryInteger $number
     * @return ArbitraryInteger
     */
    public function subtract($number): ArbitraryInteger
    {
        $number = self::prepareParameter($number);
        
        if (!$number->isPositive()) {
            return $this->add($number->negate());
        }
        if (!$this->positive) {
            return $this->negate()->add($number)->negate();
        }
        if ($this->lessThan($number)) {
            return $number->subtract($this)->negate();
        }
        $number = $number->toBinary();
        $carry = 0;
        $len = strlen($this->base256);
        $num_len = strlen($number);
        $max_len = max($len, $num_len);
        $base_256 = str_pad($this->base256, $max_len, chr(0), STR_PAD_LEFT);
        $number = str_pad($number, $max_len, chr(0), STR_PAD_LEFT);
        $result = '';
        for ($i = 0; $i < $max_len; $i++) {
            $base_chr = ord($base_256[$max_len - $i - 1]) - $carry;
            $carry = 0;
            $num_chr = ord($number[$max_len - $i - 1]);
            if ($num_chr > $base_chr) {
                $base_chr += 256;
                $carry = 1;
            }
            $difference = $base_chr - $num_chr;
            
            $result = chr($difference) . $result;
        }
        return self::fromBinary($result, true);
    }

    /**
     * Multiply
     * Return the result of multiplying two ArbitraryIntegers, or an ArbitraryInteger and an integer.
     *
     * @todo use Karatsuba algorithm
     * @param int|string|ArbitraryInteger $number
     *
     * @return ArbitraryInteger
     *
     */
    public function multiply($number): ArbitraryInteger
    {
        $number = self::prepareParameter($number);
        $number = $number->toBinary();
        $length = strlen($number);
        $product = new ArbitraryInteger(0);
        for ($i = 1; $i <= $length; $i++) {
            $this_len = strlen($this->base256);
            $base_digit = ord(substr($number, -1 * $i, 1));
            $carry = 0;
            $inner_product = '';
            for ($j = 1; $j <= $this_len; $j++) {
                $digit = ord(substr($this->base256, -1 * $j, 1));
                $step_product = $digit * $base_digit + $carry;
                $mod = $step_product % 256;
                $carry = intdiv($step_product, 256);
                $inner_product = chr($mod) . $inner_product;
            }
            if ($carry > 0) {
                $inner_product = chr($carry) . $inner_product;
            }
            $inner_product = $inner_product . str_repeat(chr(0), $i - 1);
            $inner_obj = self::fromBinary($inner_product, $this->positive);
            $product = $product->add($inner_obj);
        }
        return $product;
    }

    /**
     * Raise an ArbitraryInteger to a power
     *
     * https://en.wikipedia.org/wiki/Exponentiation_by_squaring
     * @param int|string|ArbitraryInteger $number
     *
     * @return ArbitraryInteger
     */
    public function pow($exp): ArbitraryInteger
    {
        $exp = self::prepareParameter($exp);
        if ($exp->equals(0)) {
            return new ArbitraryInteger(1);
        }
        if ($exp->equals(1)) {
            return $this;
        }
        list($int, $mod) = $exp->fullIntdiv(2);
        $square = $this->multiply($this)->pow($int);
        if ($mod->equals(1)) {
            return $square->multiply($this);
        }
        return $square;
    }

    /**
     * Full Integer Division
     * returns both the integer result and remainer
     *
     * @param int|string|ArbitraryInteger $number
     *
     * @return array
     */
    public function fullIntdiv($divisor): array
    {
        if ($this->lessThan($divisor)) {
            return [new ArbitraryInteger(0), $this];
        }
        $two_fifty_six = new ArbitraryInteger(256);

        // If the divisor is less than Int_max / 256 then
        // the native php intdiv and mod functions can be used.
        $safe_bytes = new ArbitraryInteger(intdiv(PHP_INT_MAX, 256));
        $divisor = self::prepareParameter($divisor);
        if ($divisor->lessThan($safe_bytes)) {
            $divisor = $divisor->toInt();
            $base_256 = $this->base256;
            $len = strlen($base_256);
            
            $carry = 0;
            $int = '';
            for ($i = 0; $i < $len; $i++) {
                // Grab same number of chars from $this
                $chr_obj = self::fromBinary(substr($base_256, $i, 1), $this->positive);
                // Convert chr into int
                $chr = $chr_obj->toInt();
                // Calculate $int and $mod
                $int_chr = intdiv($chr + $carry * 256, $divisor);
                $carry = ($chr + $carry * 256) % $divisor;
                if ($int !== '' || $int_chr !== 0) {
                    $int .= chr($int_chr);
                }
            }
            $int = self::fromBinary((string) $int, $this->positive);
            $mod = new ArbitraryInteger($carry);
        } else {
            $int = new ArbitraryInteger(0);
            $divisor256 = $divisor->toBinary();
            $base256 = $this->base256;
            $length = strlen($base256);
            $mod = new ArbitraryInteger(0);
            // Pop a char of the left of $base256 onto the right of $mod
            for ($i = 0; $i < $length; $i++) {
                $new_char = self::fromBinary($base256[$i], true);
                // $mod .= $new_char
                $mod = $mod->leftShift(8)->add($new_char);
                $new_int = new ArbitraryInteger(0);
                if ($mod->greaterThan($divisor)) {
                    while (!$mod->lessThan($divisor)) {
                        $new_int = $new_int->add(1);
                        $mod = $mod->subtract($divisor);
                    }
                }
                $int = $int->leftShift(8)->add($new_int);
            }
        }
        return [$int, $mod];
    }

    /**
     * Mod
     * Returns the remainer from integer division
     *
     * @param int|string|ArbitraryInteger $number
     *
     * @return ArbitraryInteger
     */
    public function mod($divisor): ArbitraryInteger
    {
        list($int, $mod) = $this->fullIntdiv($divisor);
        return $mod;
    }

    /**
     * intdiv
     * Returns the integer quotient from integer division
     *
     * @param int|string|ArbitraryInteger $number
     *
     * @return ArbitraryInteger
     */
    public function intdiv($divisor): ArbitraryInteger
    {
        list($int, $mod) = $this->fullIntdiv($divisor);
        return $int;
    }

    /**
     * Factorial
     *
     * Calculate the factorial of an ArbitraryInteger
     *
     * @return ArbitraryInteger
     */
    public function fact(): ArbitraryInteger
    {
        $result = new ArbitraryInteger(1);
        $i_obj = new ArbitraryInteger(0);
        for ($i = 1; !$this->lessThan($i); $i++) {
            $i_obj = $i_obj->add(1);
            $result = $result->multiply($i_obj);
        }
        return $result;
    }

    /**************************************************************************
     * BITWISE OPERATIONS
     **************************************************************************/

    /**
     * Left Shift
     *
     * Shift the bits of $this $bits steps to the left
     * @param int|string|ArbitraryInteger $number
     *
     * @return ArbitraryInteger
     */
    public function leftShift($bits)
    {
        $bits = self::prepareParameter($bits);
        $shifted_string = "";
        $length = strlen($this->base256);
        list($bytes, $bits) = $bits->fullIntdiv(8);
        $bits = $bits->toInt();
        $carry = 0;
        for ($i = 0; $i < $length; $i++) {
            $chr = ord($this->base256[$i]);
            // If $shifted string is empty, don’t add 0x00.
            $new_value = chr($carry + intdiv($chr << $bits, 256));
            if ($shifted_string !== "" || $new_value !== chr(0)) {
                $shifted_string .= $new_value;
            }
            $carry = ($chr << $bits) % 256;
        }
        $shifted_string .= chr($carry);

        // Pad $bytes of 0x00 on the right.
        $shifted_string = $shifted_string . str_repeat(chr(0), $bytes->toInt());

        return self::fromBinary($shifted_string, true);
    }

    /**************************************************************************
     * COMPARISON FUNCTIONS
     **************************************************************************/

    /**
     * Test for equality
     *
     * Two ArbitraryIntegers are equal IFF their $base256 strings
     * are identical and their signs are identical.
     *
     * @param int|string|ArbitraryInteger $int
     *
     * @return bool
     */
    public function equals($int): bool
    {
        $int = self::prepareParameter($int);
        return $this->base256 == $int->toBinary() && $this->positive == $int->isPositive();
    }

    /**
     * Greater Than
     *
     * Test if one ArbitraryInteger is greater than another
     *
     * @param int|string|ArbitraryInteger $int
     *
     * @return bool
     */
    public function greaterThan($int): bool
    {
        $int = self::prepareParameter($int);
        return $int->lessThan($this);
    }

    /**
     * Less Than
     *
     * Test if one ArbitraryInteger is less than another
     *
     * @param int|string|ArbitraryInteger $int
     *
     * @return bool
     */
    public function lessThan($int): bool
    {
        $int = self::prepareParameter($int);
        $base_256 = $this->base256;
        $int_256 = $int->toBinary();
        $my_len = strlen($base_256);
        $int_len = strlen($int_256);
        $my_positive = $this->positive;
        $int_positive = $int->isPositive();
        
        // Check if signs differ
        if ($my_positive && !$int_positive) {
            return false;
        }
        if ($int_positive && !$my_positive) {
            return true;
        }
        
        // If one number has more digits, its absolute value is larger.
        if ($my_len > $int_len) {
            return !$my_positive;
        } elseif ($int_len > $my_len) {
            return $my_positive;
        } else {
            // Test each digit from most significant to least.
            for ($i = 0; $i < $my_len; $i++) {
                if ($base_256[$i] !== $int_256[$i]) {
                    $test = ord($base_256[$i]) < ord($int_256[$i]);
                    return $my_positive ? $test : !$test;
                }
            }
            // Must be equal
            return false;
        }
    }
}
