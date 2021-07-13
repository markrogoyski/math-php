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
        return self::generalizedHarmonic($n, 1);
    }

    /**
     * Generalized Harmonic Numbers
     *
     *       ₙ  1
     * Hₙₘ = ∑  --
     *      ⁱ⁼¹ iᵐ
     *
     * https://en.wikipedia.org/wiki/Harmonic_number#Generalized_harmonic_numbers
     *
     * @param int   $n the length of the sequence to calculate
     * @param float $m the exponent
     *
     * @return array
     */
    public static function generalizedHarmonic(int $n, float $m): array
    {
        if ($n <= 0) {
            return [];
        }

        $sequence = [];
        $∑        = 0;

        for ($i = 1; $i <= $n; $i++) {
            $∑ += 1 / $i ** $m;
            $sequence[$i] = $∑;
        }

        return $sequence;
    }
}
