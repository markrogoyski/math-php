<?php

namespace MathPHP\Sequence;

use MathPHP\Exception;
use MathPHP\NumberTheory\Integer;

/**
 * Non-integer sequences
 *  - Harmonic
 *
 * All sequences return an array of numbers in the sequence.
 * The array index starting point depends on the sequence type.
 */
class NonInteger
{

    /**
     * Harmonic Numbers
     *
     *      n
     * Hᵢ = ∑(1/i)
     *      i=1
     *
     * https://en.wikipedia.org/wiki/Harmonic_number
     *
     * @param int $n the length of the sequence to calculate
     *
     * @return array
     */
    public static function Harmonic(int $n)
    {
        return self::Hyperharmonic($n, 1);
    }

    /**
     * Hyperharmonic Numbers
     *
     *      n
     * Hᵢₛ = ∑(1/iˢ)
     *      i=1
     *
     * https://en.wikipedia.org/wiki/Harmonic_series_(mathematics)#p-series
     *
     * @param int $n the length of the sequence to calculate
     * @param number $s the exponent
     *
     * @return array
     */
    public static function Hyperharmonic(int $n, $s)
    {
        if ($n < 1) {
            throw new Exception\BadParameterException('n must be 1 or greater');
        }
        $result = [];
        $sum = 0;
        for ($i = 1; $i <= $n; $i++) {
            $sum += 1 / $i ** $s;
            $result[$i] = $sum;
        }
        return $result;
    }
}
