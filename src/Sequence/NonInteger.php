<?php

namespace MathPHP\Sequence;

use MathPHP\Exception;
use MathPHP\Number\Rational;

/**
 * Non-integer sequences
 *  - Harmonic
 *  - Generalized Harmonic
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
    public static function harmonic(int $n): array
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

    /**
     * Hyperharmonic Numbers
     *
     *         ₙ
     * Hₙ⁽ʳ⁾ = ∑  Hₖ⁽ʳ⁻¹⁾
     *        ᵏ⁼¹
     *
     * https://en.wikipedia.org/wiki/Hyperharmonic_number
     *
     * @param int $n the length of the sequence to calculate
     * @param int $r the depth of recursion
     * @param int $rational return results as a Rational object
     *
     * @return array
     */
    public static function hyperharmonic(int $n, int $r, $rational = false): array
    {
        if ($r < 0) {
            throw new Exception\OutOfBoundsException('Recursion depth must be greater than 0');
        }
        if ($n <= 0) {
            return [];
        }
        $sequence = [];

        if ($r == 0) {
            for ($k = 1; $k <= $n; $k++) {
                $sequence[$k] = new Rational(0, 1, $k);
            }
        } else {
            $array = self::hyperharmonic($n, $r - 1, true);
            $∑     = Rational::createZeroValue();
            for ($k = 1; $k <= $n; $k++) {
                $∑ = $∑->add($array[$k]);
                $sequence[$k] = $∑;
            }
        }
        if (!$rational) {
            for ($k = 1; $k <= $n; $k++) {
                $sequence[$k] = $sequence[$k]->toFloat();
            }
        }
        return $sequence;
    }
}
