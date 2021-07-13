<?php

namespace MathPHP\Sequence;

/**
 * Non-integer sequences
 *  - Harmonic
 *  - Hyperharmonic
 *
 * All sequences return an array of numbers in the sequence.
 * The array index starting point depends on the sequence type.
 */
class NonInteger
{
    /**
     * Harmonic Numbers
     *
     *      n  1
     * Hᵢ = ∑  -
     *     ⁱ⁼¹ i
     *
     * https://en.wikipedia.org/wiki/Harmonic_number
     *
     * @param int $n the length of the sequence to calculate
     *
     * @return array
     */
    public static function Harmonic(int $n): array
    {
        return self::Hyperharmonic($n, 1);
    }

    /**
     * Hyperharmonic Numbers (p-series)
     *
     *       n  1
     * Hᵢp = ∑  --
     *      ⁱ⁼¹ iᵖ
     *
     * https://en.wikipedia.org/wiki/Harmonic_series_(mathematics)#p-series
     * https://en.wikipedia.org/wiki/Hyperharmonic_number
     *
     * @param int   $n the length of the sequence to calculate
     * @param float $p the exponent
     *
     * @return array
     */
    public static function Hyperharmonic(int $n, float $p): array
    {
        if ($n <= 0) {
            return [];
        }

        $sequence = [];
        $∑        = 0;

        for ($i = 1; $i <= $n; $i++) {
            $∑ += 1 / $i ** $p;
            $sequence[$i] = $∑;
        }

        return $sequence;
    }
}
