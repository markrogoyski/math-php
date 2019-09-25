<?php

namespace MathPHP\Number;

use MathPHP\Exception;

/**
 * Arbitrary Length Integer
 *
 * An object to manipulate positive integers of arbitrary size.
 * The object should be able to store values from 0 up to 256 ^ (PHP_MAX_INT + 1) - 1
 *
 * http://www.faqs.org/rfcs/rfc3548.html
 */
class ArbitraryInteger implements ObjectArithmetic
{
    /** @var string number in binary format */
    protected $base256;

    /** string alphabet of base 16 numbers */
    const RFC3548_BASE16 = '0123456789ABCDEF';

    /** string alphabet of base 64 numbers */
    const RFC3548_BASE64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';

    /** string alphabet of file safe base 64 numbers */
    const RFC3548_BASE64_FILE_SAFE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';

    /** string alphabet of base 32 numbers */
    const RFC3548_BASE32 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * Constructor
     *
     * @param mixed $number
     *   A string or integer
     * @param int $base
     *   The number base. If null, the default PHP integer literal rules are used to determine the base.
     * @param mixed $offset
     *   The alphabet or offset of a string number.
     *   $offset can either be a single character or a string.
     *   If it is a string and more than one character, the number of characters must equal the base
     */
    public function __construct($number, $base = null, $offset = null)
    {
        if (is_int($number)) {
            // Should we check that $base is 10 or null and $offset makes sense?
            $int_part = intdiv($number, 256);
            $string = chr($number % 256);

            while ($int_part > 0) {
                $string = chr($int_part % 256) . $string;
                $int_part = intdiv($int_part, 256);
            }
            $this->base256 = $string;
        } elseif (is_string($number)) {
            if ($number == '') {
                throw new Exception\BadParameterException("String cannot be empty.");
            }
            if ($base === null && $offset !== null && strlen($offset) > 1) {
                $base = strlen($offset);
            }
            if ($base === null) {
                if ($number[0] == '0') {
                    if ($number[1] == 'x') {
                        $base = 16;
                        $number = substr($number, 2);
                    } elseif ($number[1] == 'b') {
                        $base = 2;
                        $number = substr($number, 2);
                    } else {
                        $base = 8;
                        $number = substr($number, 1);
                    }
                } else {
                    $base = 10;
                }
            }
            // Can we avoid measuring the length?
            // This would allow very-very long numbers, with more than MaxInt number of chars.
            $length = strlen($number);
            
            // Set to default offset and ascii alphabet
            if ($offset === null) {
                $offset = self::getDefaultAlphabet($base);
            }
            // Check that all elements are greater than the offset, and are members of the alphabet.
            // Remove the offset.
            if ($offset !== chr(0)) {
                // I'm duplicating the for loop instead of placing the if within the for
                // to prevent calling the if/else on every pass.
                if (strlen($offset) ==  1) {
                    // Subtract a constant offset from each character.
                    $offset_num = ord($offset);
                    for ($i = 0; $i < $length; $i++) {
                        $chr = $number[$i];
                        $number[$i] = chr(ord($chr) - $offset_num);
                    }
                } else {
                    // Lookup the offset from the string position
                    for ($i = 0; $i < $length; $i++) {
                        $chr = $number[$i];
                        $number[$i] = chr(strpos($offset, $chr));
                    }
                }
            }

            // Convert to base 256
            $base256 = new ArbitraryInteger(0);
            if ($base < 256) {
                $base_obj = new ArbitraryInteger($base);
                $place_value = new ArbitraryInteger(1);
                $length = strlen($number);
                for ($i = 0; $i < $length; $i++) {
                    $chr = ord($number[$i]);
                    $base256 = $base256->multiply($base)->add($chr);
                }
                $this->base256 = $base256->getBinary();
            } elseif ($base > 256) {
                throw new Exception\BadParameterException("Number base cannot be greater than 256.");
            } else {
                $this->base256 = $number;
                // need to drop any leading zeroes.
            }
        } else {
            // Not an int, and not a string
            throw new Exception\IncorrectTypeException("Number can only be an int or a string: type '" . gettype($number) . "' provided");
        }
    }

    /**
     * Convert ArbitraryInteger to an int
     *
     * @return int
     */
    public function toInteger(): int
    {
        $number = str_split(strrev($this->base256));
        $place_value = 1;
        $int = ord($number[0]);
        unset($number[0]);
        foreach ($number as $digit) {
            $place_value *= 256;
            $int += ord($digit) * $place_value;
        }
        return $int;
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
        return floatval($float);
    }

    private static function prepareParameter($number): ArbitraryInteger
    {
        if (!is_object($number)) {
            return new ArbitraryInteger($number);
        }
        // else make sure it’s a ArbitraryInteger
        return $number;
    }
    
    /**
     * To String
     *
     * Display the number in base 10
     */
    public function __toString(): string
    {
        return $this->toBase(10);
    }

    /**
     * Get the default alphabet for a given number base
     *
     * @param int $base
     * @return string
     */
    protected static function getDefaultAlphabet(int $base): string
    {
        switch ($base) {
            case 2:
            case 8:
            case 10:
                $offset = '0';
                break;
            case 16:
                $offset = '0123456789abcdef';
                break;
            default:
                $offset = chr(0);
                break;
        }
        return $offset;
    }

    /**
     * Return the number as a binary string
     * @return string
     */
    public function getBinary(): string
    {
        return $this->base256;
    }

    /**
     * Convert to an arbitrary base and alphabet
     *
     * @param int $base
     * @param string $alphabet
     *
     * @return string
     */
    public function toBase(int $base, $alphabet = null): string
    {
        if ($base > 256) {
            throw new Exception\BadParameterException("Number base cannot be greater than 256.");
        }
        if ($alphabet === null) {
            $alphabet = self::getDefaultAlphabet($base);
        }
        $base_256 = $this->base256;
        $result = '';
        while ($base_256 !== '') {
            $carry = 0;
            $next_int = $base_256;
            $len = strlen($base_256);
            $base_256 = '';
            for ($i = 0; $i < $len; $i++) {
                $chr = ord($next_int[$i]);
                $int = intdiv($chr + 256 * $carry, $base);
                $carry = ($chr + 256 * $carry) % $base;
                // or just trim off all leading chr(0)s
                if ($base_256 !== '' || $int > 0) {
                    $base_256 .= chr($int);
                }
            }
            if (strlen($alphabet) == 1) {
                $result = chr(ord($alphabet) + $carry) . $result;
            } else {
                $result = $alphabet[$carry] . $result;
            }
        }
        return $result;
    }

    /**************************************************************************
     * BINARY FUNCTIONS
     **************************************************************************/

    /**
     * Addition
     *
     * @param ArbitraryInteger|int $number
     *
     * @return ArbitraryInteger
     *
     */
    public function add($number): ArbitraryInteger
    {
        // check if string, object, or int
        // throw exception if appropriate
        if (!is_object($number)) {
            $number = new ArbitraryInteger($number);
        }
        $number = $number->getBinary();
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
        return new ArbitraryInteger($result, 256);
    }

    /**
     * Subtraction
     *
     * Calculate the difference between two numbers
     *
     * @param ArbitraryInteger|int $number
     * @return ArbitraryInteger
     */
    public function subtract($number): ArbitraryInteger
    {
        $number = self::prepareParameter($number);
        if ($this->lessThan($number)) {
            throw new Exception\BadParameterException('Negative numbers are not supported.');
        }
        $number = $number->getBinary();
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
        return new ArbitraryInteger($result, 256);
    }

    /**
     * Multiply
     * Return the result of multiplying two ArbitraryIntegers, or an ArbitraryInteger and an integer.
     *
     * @param ArbitraryInteger|int $number
     *
     * @return ArbitraryInteger
     *
     */
    public function multiply($number): ArbitraryInteger
    {
        // check if string, object, or int
        // throw exception if appropriate
        if (!is_object($number)) {
            $number = new ArbitraryInteger($number);
        }
        $number = $number->getBinary();
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
            $inner_obj = new ArbitraryInteger($inner_product, 256);
            $product = $product->add($inner_obj);
        }
        return $product;
    }

    public function pow($exp): ArbitraryInteger
    {
        if (!is_object($exp)) {
            $exp = new ArbitraryInteger($exp);
        }
        $result = new ArbitraryInteger(1);
        $i = new ArbitraryInteger(0);
        while ($i->lessThan($exp)) {
            $result = $result->multiply($this);
            $i = $i->add(1);
        }
        return $result;
    }

    /**
     * For divisors less than 256
     */
    public function intdiv($divisor): array
    {
        if ($this->lessThan($divisor)) {
            return [new ArbitraryInteger(0), $this];
        }
        $two_fifty_six = new ArbitraryInteger(256);

        // A 64 bit system can safely use 7 bytes
        $three_bytes = new ArbitraryInteger(33554431);

        $divisor = self::prepareParameter($divisor);
        if ($divisor->lessThan($three_bytes)) {
            // Find number of chars in $divisor
            $nibble_len = strlen($divisor->getBinary());
            $divisor = $divisor->toInteger();
            $base_256 = $this->base256;
            $len = strlen($base_256);
            
            $carry = 0;
            $int = '';
            for ($i = 0; $i < $len; $i += $nibble_len) {
                // Grab same number of chars from $this
                $chr_obj = new ArbitraryInteger(substr($base_256, $i, $nibble_len), 256);

                // Convert chr into int
                $chr = $chr_obj->toInteger();
            
                // Calculate $int and $mod
                $int_chr = intdiv($chr + $carry * 256 ** $nibble_len, $divisor);
                $carry = ($chr + $carry * 256) % $divisor;
                if ($int !== '' || $int_chr !== 0) {
                    $int .= chr($int_chr);
                }
            }
            $int = new ArbitraryInteger($int, 256);
            $mod = new ArbitraryInteger($carry);
        } else {
            // User repeated subtraction to find solutions.
        }
        return [$int, $mod];
    }

    /**
     * Factorial
     *
     * Calculate the factorial of an ArbitraryInteger
     *
     * @param int $int
     *
     * @return ArbitraryInteger
     * @todo Operate on ArbitraryIntegers and not static
     */
    public static function fact(int $int): ArbitraryInteger
    {
        $result = new ArbitraryInteger(1);
        $i_obj = new ArbitraryInteger(0);
        for ($i = 1; $i <= $int; $i++) {
            $i_obj = $i_obj->add(1);
            $result = $result->multiply($i_obj);
        }
        return $result;
    }

    /**
     * currently limited to shifting MaxInt*8 bits
     */
    public function leftShift($bits)
    {
        if (!is_object($bits)) {
            $bits = new ArbitraryInteger($bits);
        }
        $shifted_string = "";
        $length = strlen($this->base256);
        list($bytes, $bits) = $bits->intdiv(8);
        $bits = $bits->toInteger();
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
        $shifted_string = $shifted_string . str_repeat(chr(0), $bytes->toInteger());

        return new ArbitraryInteger($shifted_string, 256);
    }

    /**************************************************************************
     * COMPARISON FUNCTIONS
     **************************************************************************/

    /**
     * Test for equality
     *
     * Two ArbitraryIntegers are equal IFF their $base256 strings are identical.
     *
     * @param ArbitraryInteger $int
     *
     * @return bool
     */
    public function equals($int): bool
    {
        if (is_int($int)) {
            $int = new ArbitraryInteger($int);
        }
        return $this->base256 == $int->getBinary();
    }

    /**
     * Less Than
     *
     * Test if one ArbitraryInteger is less than another
     *
     * @param $int
     *
     * @return bool
     */
    public function lessThan($int): bool
    {
        $int = self::prepareParameter($int);
        $base_256 = $this->base256;
        $int_256 = $int->getBinary();
        $my_len = strlen($base_256);
        $int_len = strlen($int_256);

        // If one number has more digits, it is larger
        if ($my_len > $int_len) {
            return false;
        } elseif ($int_len > $my_len) {
            return true;
        } else {
            // Test each digit from most significant to least.
            for ($i = 0; $i < $my_len; $i++) {
                if ($base_256[$i] !== $int_256[$i]) {
                    return ord($base_256[$i]) < ord($int_256[$i]);
                }
            }
            // Must be equal
            return false;
        }
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
    public static function ackermann($m, $n): ArbitraryInteger
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
            $one = new ArbitraryInteger(1);
            // 2^(n+3) - 3
            return $one->leftShift($n->add(3))->subtract(3);
        } elseif ($n->equals(0)) {
            return ArbitraryInteger::ackermann($m->subtract(1), 1);
        } else {
            return ArbitraryInteger::ackermann($m->subtract(1), ArbitraryInteger::ackermann($m, $n->subtract(1)));
        }
    }
}
