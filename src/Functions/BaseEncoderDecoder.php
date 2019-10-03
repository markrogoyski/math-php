<?php

namespace MathPHP\Functions;

use MathPHP\Exception;
use MathPHP\Number\ArbitraryInteger;

/**
 * Utility functions to manipulate numerical strings with non-standard bases and alphabets
 */
class BaseEncoderDecoder
{
    /** string alphabet of base 64 numbers */
    const RFC3548_BASE64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';

    /** string alphabet of file safe base 64 numbers */
    const RFC3548_BASE64_FILE_SAFE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';

    /** string alphabet of base 32 numbers */
    const RFC3548_BASE32 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

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
     * Convert to an arbitrary base and alphabet
     *
     * @param ArbitraryInteger $number
     * @param int $base
     * @param string $alphabet
     *
     * @return string
     */
    public static function toBase(ArbitraryInteger $number, int $base, $alphabet = null): string
    {
        if ($base > 256) {
            throw new Exception\BadParameterException("Number base cannot be greater than 256.");
        }
        if ($alphabet === null) {
            $alphabet = self::getDefaultAlphabet($base);
        }
        $base_256 = $number->getBinary();
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

    public static function createArbitraryInteger(string $number, int $base, string $offset = null): ArbitraryInteger
    {
        if ($number == '') {
            throw new Exception\BadParameterException("String cannot be empty.");
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
        if ($base <= 256) {
            $base_obj = new ArbitraryInteger($base);
            $place_value = new ArbitraryInteger(1);
            $length = strlen($number);
            for ($i = 0; $i < $length; $i++) {
                $chr = ord($number[$i]);
                $base256 = $base256->multiply($base)->add($chr);
            }
            return $base256;
        } else {
            throw new Exception\BadParameterException("Number base cannot be greater than 256");
        }
    }
}
