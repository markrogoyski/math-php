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
    const BYTES = 2;
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
        $type = gettype($v);
        if ($type == 'string') {
            // Check that $v starts with '0b' and only contains ones and zeroes
            $prefix = substr($v, 0, 2);
            $value = substr($v, 2);
            if ($prefix == '0b' && !preg_match('/[^0-1]/', $value)) {
                // Determine if this is 32 or 64 bit OS
                $bits = strlen(decbin(-1));
                if (strlen($value) > $bits) {
                    if (strlen($value) > $bits * self::BYTES) {
                        throw new Exception\BadParameterException("String has too many bits. Max allowed = " . ($bits * self::BYTES) . '. Given " . strlen($value));
                    } else {
                        // extract the last $bits bits from $value and assign to $value[0]
                        $this->value[0] = bindec(substr($value, -1 * $bits));
                    
                        // Assign remaining bits to $value[1]
                        $this->value[1] = bindec(substr($value, 0, -1 * $bits));
                    }
                } else {
                    $this->value[1] = 0;
                    $this->value[0] = bindec($value);
                }
            } else {
                throw new Exception\BadParameterException("String must start with '0b' and then contain only ones and zeroes");
            }
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
    
    /**
     * String representation of a BigInt
     *
     * @return string
     */
    public function __toString(): string
    {
        $temp = $this;
        $ten_power = new BigInt(1);
        // Find largest n such that 10ⁿ is less than $this
        $n = 0;
        while ($this->greaterThan($ten_power)) {
            $n++;
            $ten_power = $ten_power->multiply(10);
        }
        $n--;
        $ten_power = $ten_power->divide(10);
        $string = '';
        while ($temp->greaterThan(0)) {
            $intdiv = $temp->intdiv($ten_power);
            $string .= $intdiv;
            $temp = $temp->subtract($ten_power->multiply($intdiv));
            $ten_power = $ten_power->divide(10);
        }
        return $string;
    }

    /*
     * Cast the BigInt to an int if possible
     */
    public function toInt(): int
    {
    }

    /*
     * Cast the BigInt to a float
     */
    public function toFloat(): float
    {
    }

    public function get(int $n): int
    {
        return $this->value[$n];
    }

    public function isNegative(): bool
    {
        return $this->value[1] < 0;
    }

    public function isPositive(): bool
    {
        return $this->value[1] > 0;
    }
    /**************************************************************************
     * UNARY FUNCTIONS
     **************************************************************************/

    /**
     * The absolute value of a BigInt
     */
    public function abs(): BigInt
    {
        return $this->isNegative() ? $this->negate() : $this;
    }

    /*
     * Multiply the BigInt by -1
     *
     * @return BigInt
     */
    public function negate(): BigInt
    {
        // Combine the ones compliment of $value[1] with the twos compliment of $value[0]
        return new BigInt([-1 * $this->value[0], -1 * $this->value[1] - 1]);
    }
    
    /*
     * Decimal to Binary
     *
     * return the binary representation of the number without leading zeroes
     * @return string
     */
    public function decbin(): string
    {
        if ($this->value[1] == 0) {
            return decbin($this->value[0]);
        } else {
            $first_part = decbin($this->value[1]);
            $second_part = str_pad(decbin($this->value[0]), strlen(decbin(-1)), '0', STR_PAD_LEFT);
            return $first_part . $second_part;
        }
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
        if ($this->mod($c)->equals(0)) {
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
        $c = 0;
        $temp = $this;
        while ($temp->subtract($c)->greaterThan(0)) {
            $c++;
        }
        return $c;
    }

    /**
     * Mod
     * The remainder of integer division
     */
    public function mod(int $c): BigInt
    {
        return $this->subtract($this->intdiv($c)->multiply($c));
    }
    
    /**************************************************************************
     * COMPARISON FUNCTIONS
     **************************************************************************/

    /**
     * Test for equality
     *
     * @return bool
     */
    public function equals($c): bool
    {
        return $this->value[0] == $c->get(0) && $this->value[1] == $c->get(1);
    }

    /**
     * Test if $c is greater than $this
     *
     * @return bool
     */
    public function greaterThan(BigInt $c): bool
    {
        if (is_int($c)) {
            if ($this->value[1] === 0 && $this->value[0] >= 0 || $this->value[1] === -1 && $this->value[0] < 0) {
                //Big can can be cast to an int
                return $this->toInt() > $c;
            } else {
                // abs($this) is greater than all ints, so will be greater if $value[1] is positive.
                return $this->value[1] > 0;
            }
        } else {
            // If one is positive and one negative
            if ($this->isNegative() !== $c->isNegative()) {
                return $this->isPositive();
            } elseif ($this->value[1] > $c->get(1)) {
                return true;
            }
        }
    }
}
