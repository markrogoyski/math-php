<?php
namespace MathPHP\Number;

use MathPHP\Exception;
use MathPHP\Functions\Bitwise;
use MathPHP\Functions\Special;

/**
 * Big Integer
 *
 * The BigInt object is an array of 2 ints. Concatenating the two ints
 * provides the binary version of the integer in 2s compliment form.
 */
class BigInt implements ObjectArithmetic
{
    // How many ints are in the $value array.
    const WORDS = 2;
    
    // How large is each word. Sould be 32 or 64.
    protected $word_size;
    
    /**
     * Array of ints
     * @var array
     */
    protected $value;
    
    /**
     * Constructor
     *
     * Create a BigInt
     *
     * Providing an int will produce a BigInt with the same value.
     *
     * Providing a string that begins with "0b" will produce a BigInt with
     * the specified bit sequence.
     *
     * Providing an array will create a BigInt with the bit squence of
     * decbin($array[1]) . decbin($array[0])
     *
     * @param mixed $v
     */
    public function __construct($v)
    {
        $this->value = array_fill(0, self::WORDS, 0);
        // Determine if this is 32 or 64 bit OS
        $this->word_size = \PHP_INT_SIZE * 8;
        $word_size = $this->word_size;
        // Should we specify total bits and calculate $this->words = self::BITS / $this->word_size,
        // or specify self::WORDS and calculate bits = self::WORDS * $this->word_size?
        $type = gettype($v);
        if ($type == 'string') {
            $negate = false;
            if (substr($v, 0, 1) == '-') {
                $negate = true;
                $v = substr($v, 1);
            } elseif (substr($v, 0, 1) == '+') {
                $v = substr($v, 1);
            }
            $newint = new BigInt(0);
            if (preg_match('/^0[0-7]*$/', $v)) {
                // Octal or Zero
                // Remove the leading 0
                $v = substr($v, 1);
                // Octal is such a pain, we will convert to binary.
                $v = '0b' . base_convert($v, 8, 2);
            }
            if (preg_match('/^0b[01]+$/', $v)) {
                // Binary
                // Remove the leading 0b
                $v = substr($v, 2);
                $value[0] = self::signedBindec($v);
                $value[1] = 0;
                if (strlen($v) > $word_size) {
                    if (strlen($v) > $word_size * self::WORDS) {
                        throw new Exception\BadParameterException("String has too many bits. Max allowed = " . ($word_size * self::WORDS) . '. Given ' . strlen($v));
                    } else {
                        // Assign remaining bits to $value[1]
                        $v = substr($v, 0, -64);
                        $value[1] = self::signedBindec($v);
                    }
                }
                $newint = new BigInt($value);
            } elseif (preg_match('/^0[xX][0-9a-fA-F]+$/', $v)) {
                // Hex
                // Remove the leading 0x
                $v = substr($v, 2);
                $value[0] = self::signedHexdec($v);
                $value[1] = 0;
                if (strlen($v) > $word_size / 4) {
                    if (strlen($v) > $word_size * self::WORDS / 4) {
                        throw new Exception\BadParameterException("String has too many bytes. Max allowed = " . ($word_size * self::WORDS / 4) . '. Given ' . strlen($v));
                    } else {
                        // Assign remaining bits to $value[1]
                        $v = substr($v, 0, -16);
                        $value[1] = self::signedHexdec($v);
                    }
                }
                $newint = new BigInt($value);
            } elseif (preg_match('/^[1-9][0-9]*$/', $v)) {
                // Decimal
                $power = strlen($v) - 1;
                $tens = new BigInt(10);
                $newint = new BigInt(0);
                $tens = $tens->pow($power);
                for ($i = 0; $i <= $power; $i++) {
                    $multiplicand = (int)substr($v, $i, 1);
                    $newint = $newint->add($tens->multiply($multiplicand));
                    $tens = $tens->intdiv(10);
                }
            } else {
                throw new Exception\BadParameterException("String must be a valid binary, octal, decimal, or hexadecimal value");
            }
            if ($negate) {
                    $newint = $newint->negate();
            }
            $this->value = $newint->value;
        } elseif ($type == 'integer') {
            $this->value[0] = $v;
            $this->value[1] = $v >= 0 ? 0 : -1;
        } elseif ($type == 'array') {
            if (count($v) == 2 && is_int($v[0]) && is_int($v[1])) {
                $this->value[0] = $v[0];
                $this->value[1] = $v[1];
            } else {
                throw new Exception\BadParameterException('Array must contain only two ints');
            }
        } else {
            throw new Exception\IncorrectTypeException('Constructor can only accept array, integer, or string values. Given type ' . $type);
        }
    }

    /**************************************************************************
     * GETTERS AND SETTERS
     **************************************************************************/

    /**
     * Get the bit at a specific positikn
     * zero indexed
     */
    public function getBit(int $n): int
    {
        $word_size = $this->word_size;
        if ($n > $word_size * self::WORDS) {
            throw new Exception\OutOfBoundsException('Trying to access bit ' . $n . ' when only ' . ($word_size * self::WORDS) . ' exist');
        }
        $mask = $n == $word_size - 1 ? \PHP_INT_MIN : 2 ** ($n % $word_size);
        return ($this->value[intdiv($n, $word_size)] & $mask) === 0 ? 0 : 1;
    }

    /**************************************************************************
     * PHP MAGIC METHODS
     *  - __toString
     **************************************************************************/

    public function __toString()
    {
        $msb = $this->MSB();
        
        // Given the most significant bit, this number is the smallest
        // exponent of 10 that will be larger than $this.
        $n = max(ceil(log10(2) * ($msb + 1)), 0);
        $divisor = new BigInt(10);
        $divisor = $divisor->pow($n);
        $string = '';
        $negative = false;
        $temp = $this;
        // If $this is self::minValue() we cannot negate it below. Instead we will perform the
        // first division and negate the results. It seems like a bad hack and there might be
        // a better way to do it.
        $min_value = 1;
        if ($this->isNegative() && !$this->equals(self::minValue())) {
            $temp = $this->negate();
            $negative = true;
        } elseif ($this->equals(self::minValue())) {
            $min_value = -1;
            $negative = true;
        }
        for ($i = 0; $i <= $n; $i++) {
            $results = $temp->euclideanDivision($divisor);
            $divisor = $divisor->intdiv(10);
            $string .= $results['quotient']->toInt() * $min_value;
            $temp = $results['remainder']->multiply($min_value);
            $min_value = 1;
        }
        $string = ltrim($string, '0');
        if ($negative) {
            $string = '-' . $string;
        }
        return $string == '' ? '0' : $string;
    }

    /**************************************************************************
     * CASTING AND OUTPUT
     *  - toInt
     *  - decbin
     *  - dechex
     *  - decoct (TODO)
     **************************************************************************/
    
    /*
     * Cast the BigInt to an int if possible
     */
    public function toInt(): int
    {
        // If the sign of the two values are the same, and the MSbyte is all 0 or all 1
        if ($this->value[1] >= 0 === $this->value[0] >= 0 && ($this->value[1] == -1 || $this->value[1] == 0)) {
            return $this->value[0];
        } else {
            throw new Exception\OutOfBoundsException();
        }
    }

    /**
     * Decimal to Binary
     *
     * return the binary representation of the number without leading zeroes
     * @return string
     */
    public function decbin(): string
    {
        $string = "";
        $bits = strlen(decbin(-1));
        foreach ($this->value as $int) {
            $string = str_pad(decbin($int), $bits, '0', STR_PAD_LEFT) . $string;
        }
        $string = ltrim($string, '0');
        return $string == '' ? '0' : $string;
    }
 
    /**
     * Decimal to Hexadecimal
     *
     * return the hexadecimal representation of the number without leading zeroes.
     * @return string
     */
    public function dechex(): string
    {
        $string = "";
        $bits = strlen(dechex(-1));
        foreach ($this->value as $int) {
            $string = str_pad(dechex($int), $bits, '0', STR_PAD_LEFT) . $string;
        }
        $string = ltrim($string, '0');
        return $string == '' ? '0' : $string;
    }

    /**************************************************************************
     * NUMBER PROPERTIES
     *  - isNegative
     *  - isPositive
     **************************************************************************/

    public function isNegative(): bool
    {
        return $this->value[1] < 0;
    }

    public function isPositive(): bool
    {
        return $this->value[1] >= 0 && $this->value[0] !== 0;
    }

    /**************************************************************************
     * STATIC CONSTANTS
     *  - maxValue
     *  - minValue
     **************************************************************************/
    
    /*
     * The largest value this object can represent
     */
    public static function maxValue(): BigInt
    {
        return new BigInt([-1, \PHP_INT_MAX]);
    }

    /*
     * The smallest value this object can represent
     */
    public static function minValue(): BigInt
    {
        return new BigInt([0, \PHP_INT_MIN]);
    }

    /**************************************************************************
     * BITWISE FUNCTIONS
     *  - leftShift
     *  - rightShift
     **************************************************************************/

    public function leftShift($n = 1)
    {
        $sign_bit = $this->isNegative() ? 1 : 0;
        $first = $this->value[0];
        $second = $this->value[1];
        for ($i=0; $i<$n; $i++) {
            $first = $first << 1;
            $second = $second << 1;
            if ($this->getBit(63 - $i) == 1) {
                $second = Bitwise::add($second, 1);
            }
            if ($this->getBit(127 - $i) != $sign_bit) {
                // I don't know if this should throw and exceptionof not
                throw new Exception\OutOfBoundsException('Bitshift out of bounds');
            }
        }
        return new BigInt($first, $second);
    }
    
    public function rightShift($n = 1)
    {
        $sign_bit = $this->isNegative() ? 1 : 0;
        $first = $this->value[0];
        $second = $this->value[1];
        for ($i=0; $i<$n; $i++) {
            $first = $first >> 1;
            $second = $second >> 1;
            if ($this->getBit(64 + $i) == 1) {
                $first = $first | \PHP_INT_MIN;
            } else {
                $first = $first & \PHP_INT_MAX;
            }
        }
        return new BigInt($first, $second);
    }

    /**************************************************************************
     * UNARY FUNCTIONS
     *  - MSB
     *  - negate
     **************************************************************************/

    /**
     * Most Significant Bit
     *
     * If the sign bit is a one, find the first zero.
     * If the sign bit is a zero, find the first one.
     * zero indexed
     */
    public function MSB()
    {
        
        $decbin = $this->decbin();
        $length = strlen($decbin);
        $zero = new BigInt(0);
        if ($this->equals($zero)) {
            $length -= 1;
        }
        $msb = $length - 1;
        if ($this->isNegative()) {
            $pos = strpos($decbin, "0");
            $pos = $pos === false ? $length : $pos;
            $msb -= $pos;
        }
        return $msb;
    }

    /**
     * Multiply the BigInt by -1
     *
     * @return BigInt
     */
    public function negate(): BigInt
    {
        if ($this->value[0] === 0 && $this->value[1] === \PHP_INT_MIN) {
            throw new Exception\OutOfBoundsException('The minimum value cannot be negated');
        }
        if ($this->value[0] === 0 && $this->value[1] === 0) {
            // The negative of zero is zero.
            return $this;
        }
        // Combine the ones compliment of $value[1] with the twos compliment of $value[0]
        return new BigInt([-1 * $this->value[0], -1 * $this->value[1] - 1]);
    }

    
    /**************************************************************************
     * HELPER FUNCTIONS
     *  - signedBindec
     *  - signedHexDec
     **************************************************************************/

    /**
     * The native bindec function treats all bits as value bits.
     * This version works on a 64 bit block and treats the leftmost bit
     * as a signing bit.
     */
    private static function signedBindec(string $s): int
    {
        if (strlen($s) < 64) {
            return bindec($s);
        }
        $signbit = strlen($s) > 63 ? substr($s, -64, 1) : 0;
        $valuebits = substr($s, -63);
        $value = bindec($valuebits);
        return $signbit === '0' ? $value : $value - \PHP_INT_MAX - 1;
    }

    /**
     * The native hexdec function treats all bits as value bits.
     * This version works on a 64 bit block and treats the leftmost bit
     * as a signing bit.
     */
    private static function signedHexdec(string $s): int
    {
        if (strlen($s) < 16) {
            return hexdec($s);
        }
        // If the 16th character > 7, there is a 1 in the leftmost position.
        $negative = strlen($s) > 15 ? hexdec(substr($s, -16, 1)) >= 8 : false;
        $valuebits = substr($s, -15);
        $value = bindec($valuebits);
        return !$negative ? $value : $value - \PHP_INT_MAX - 1;
    }

    /**************************************************************************
     * BINARY FUNCTIONS
     *  - add
     *  - subtract
     *  - multiply
     *  - euclideanDivision
     *  - intdiv
     *  - mod
     *  - pow
     *  - fact (TODO)
     **************************************************************************/

    /**
     * Addition
     *
     */
    public function add($c): BigInt
    {
        $type = gettype($c);
        if ($type == 'integer') {
            $c = new BigInt($c);
        }
        if ($c instanceof BigInt) {
            $first = Bitwise::Add($this->value[0], $c->value[0]);
            $second = Bitwise::Add($this->value[1], $c->value[1]);
            if ($first['overflow']) {
                $second = Bitwise::Add($second['value'], 1);
            }
            $both_positive_but_got_negative_result = $this->isPositive() && $c->isPositive() && $second['value'] < 0;
            $both_negative_but_got_positive_result = $this->isNegative() && $c->isNegative() && $second['value'] >= 0;
            $overflow = $both_positive_but_got_negative_result || $both_negative_but_got_positive_result;
            if ($overflow) {
                throw new Exception\OutOfBoundsException();
                // Return a BigFloat instead?
            }
            return new BigInt([$first['value'], $second['value']]);
        } else {
            throw new Exception\IncorrectTypeException('Constructor can only accept integer or BigInt types. Given type ' . $type);
        }
    }

    /**
     * Subtraction
     */
    public function subtract($c): BigInt
    {
        $type = gettype($c);
        if ($type == 'integer') {
            return $this->add(-1 * $c);
        } elseif ($c instanceof BigInt) {
            return $this->add($c->negate());
        }
    }

    /**
     * Multiplication
     */
    public function multiply($c): BigInt
    {
        $type = gettype($c);
        if ($type == 'integer') {
            $c = new BigInt($c);
        }
        if ($c->equals(self::minValue()) || $this->equals(self::minValue())) {
            // return 1, -1 or exception
        }
        $change_sign_on_result = false;
        $temp = $this;
        if ($c->isNegative()) {
            $change_sign_on_result = !$change_sign_on_result;
            $c = $c->negate();
        }
        if ($temp->isNegative()) {
            $change_sign_on_result = !$change_sign_on_result;
            $temp = $temp->negate();
        }
        $product = new BigInt(0);
        $n = 0;
        $msb = $c->MSB();
        while ($n <= $msb) {
            $bit = $c->getBit($n);
            if ($n > 0) {
                $temp = $temp->leftShift();
            }
            if ($bit == 1) {
                $product = $product->add($temp);
            }
            $n++;
        }
        return $product;
    }

    /**
     * Euclidean Division
     *
     * Perform integer division and return the quotiant and remainder
     *
     * Must figure out a way to perform division on negative numbers without
     * negating the arguments. I think you just divide like normal, but
     * backfill the left bits with ones.
     *
     * @param $c The divisor - an int or BigInt
     * @return array ['quotient', 'remainder']
     */
    public function euclideanDivision($c): array
    {
        // If we have negative numbers, we will change their signs.
        // If there is only one, the final result will be converted back to negative.
        $change_sign_on_result = false;

        // If $this is self::minValue() we cannot make it positive. Since minValue=-1*(maxValue+1),
        // and minValue is 2s compliment, the bit sequence of maxValue+1 is the same as minValue. We
        // will convert $this to maxValue and add the offset after the first operation.
        $min_value_offset = false;

        $temp = $this;
        $type = gettype($c);
        if ($type == 'integer') {
            $c = new BigInt($c);
        }
        if ($c->equals(self::minValue())) {
            return $temp->greaterThan(self::minValue()) ? ['quotient' => new BigInt(0), 'remainder' => $temp]: ['quotient' => new BigInt(1), 'remainder' => new BigInt(0)];
        }
        if ($c->isNegative()) {
            $change_sign_on_result = !$change_sign_on_result;
            $c = $c->negate();
        }

        if ($temp->isNegative()) {
            $change_sign_on_result = !$change_sign_on_result;
            if (!$temp->equals(self::minValue())) {
                $temp = $temp->negate();
            } else {
                $temp = self::maxValue();
                $min_value_offset = true;
            }
        }
        if ($c->greaterThan($temp)) {
            return ['quotient' => new BigInt(0), 'remainder' => $temp];
        }
        $quotient = new BigInt(0);
        $temp_msb = $temp->MSB();
        $c_msb = $c->MSB();
        $shifted_c = $c->leftShift($temp_msb - $c_msb);
        while (!$shifted_c->lessThan($c)) {
            if (!$shifted_c->greaterThan($temp)) {
                $temp = $temp->subtract($shifted_c);
                if ($min_value_offset) {
                    $temp = $temp->add(1);
                    $min_value_offset = false;
                }
                $quotient = $quotient->leftShift()->add(1);
            } else {
                $quotient = $quotient->leftShift();
            }
            $shifted_c = $shifted_c->rightShift();
        }
        return ['quotient' => $change_sign_on_result ? $quotient->negate() : $quotient, 'remainder' => $temp];
    }

    public function intdiv($c): BigInt
    {
        $euclidean = $this->euclideanDivision($c);
        return $euclidean['quotient'];
    }

    public function mod($c): BigInt
    {
        $euclidean = $this->euclideanDivision($c);
        return $euclidean['remainder'];
    }

    public function pow($c): BigInt
    {
        $temp = new BigInt(1);
        for ($i = 0; $i < $c; $i++) {
            $temp = $temp->multiply($this);
        }
        return $temp;
    }

    /**************************************************************************
     * COMPARISON FUNCTIONS
     *  - equals
     *  - greaterThan
     *  - lessThan
     **************************************************************************/

    /**
     * Test for equality
     *
     * @return bool
     */
    public function equals($c): bool
    {
        return $this->value[0] == $c->value[0] && $this->value[1] == $c->value[1];
    }

    /**
     * Test if $c is greater than $this
     *
     * @return bool
     */
    public function greaterThan($c): bool
    {
        if (is_int($c)) {
            if ($this->value[1] === 0 && $this->value[0] >= 0 || $this->value[1] === -1 && $this->value[0] < 0) {
                //$this can can be cast to an int
                return $this->toInt() > $c;
            } else {
                // abs($this) is greater than all ints, so will be greater if $value[1] is positive.
                return $this->value[1] > 0;
            }
        } else {
            if ($this->equals($c)) {
                return false;
            }
            // If one is positive and one negative
            if ($this->isNegative() !== $c->isNegative()) {
                return $this->isPositive();
            } elseif ($this->value[1] > $c->value[1]) {
                return true;
            } elseif ($this->value[1] < $c->value[1]) {
                return false;
            } else {
                // The largest word in each are equal.
                if ($this->value[0] < 0 !== $c->value[0] < 0) {
                    return $c < 0;
                } else {
                    return $this->value[0] > $c->value[0];
                }
            }
        }
    }
    public function lessThan($c): bool
    {
        if (is_int($c)) {
            $c = new BigInt($c);
        }
        if ($this->equals($c)) {
            return false;
        }
        return !$this->greaterThan($c);
    }
}
