<?php
namespace Math\Sequence;

/**
 * Advanced integer sequences
 *  - Fibonacci
 *  - Lucas numbers
 *  - Pell numbers
 *  - Triangular numbers
 *  - Pentagonal numbers
 *  - Hexagonal numbers
 *  - Heptagonal numbers
 *
 * All sequences return an array of numbers in the sequence.
 * The array index starting point depends on the sequence type.
 */
class Advanced
{
    /**
     * Fibonacci numbers
     * Every number is the sum of the two preceding ones.
     * https://en.wikipedia.org/wiki/Fibonacci_number
     *
     * F₀ = 0
     * F₁ = 1
     * Fᵢ = Fᵢ₋₁ + Fᵢ₋₂
     *
     * Example:
     *  n = 6
     *  Sequence:    0, 1, 1, 2, 3, 5
     *  Array index: 0, 1, 2, 3, 4, 5
     *
     * @param  int $n How many numbers in the sequence
     *
     * @return array Indexed from 0
     */
    public static function fibonacci(int $n): array
    {
        $fibonacci = [];

        // Bad input; return empty list
        if ($n <= 0) {
            return $fibonacci;
        }

        // Base case (n = 1): F₀ = 0
        $fibonacci[] = 0;
        if ($n === 1) {
            return $fibonacci;
        }

        // Base case (n = 2): F₀ = 0, F₁ = 1
        $fibonacci[] = 1;
        if ($n === 2) {
            return $fibonacci;
        }

        // Standard iterative case (n > 1): Fᵢ = Fᵢ₋₁ + Fᵢ₋₂
        for ($i = 2; $i < $n; $i++) {
            $fibonacci[] = $fibonacci[$i - 1] + $fibonacci[$i - 2];
        }

        return $fibonacci;
    }

    /**
     * Lucas numbers
     * Every number is the sum of its two immediate previous terms.
     * Similar to Fibonacci numbers except the base cases differ.
     * https://en.wikipedia.org/wiki/Lucas_number
     *
     * L₀ = 2
     * L₁ = 1
     * Lᵢ = Lᵢ₋₁ + Lᵢ₋₂
     *
     * Example:
     *  n = 6
     *  Sequence:    2, 1, 3, 4, 7, 11
     *  Array index: 0, 1, 2, 3, 4, 5
     *
     * @param  int $n How many numbers in the sequence
     *
     * @return array Indexed from 0
     */
    public static function lucasNumber(int $n): array
    {
        $lucas = [];

        // Bad input; return empty list
        if ($n <= 0) {
            return $lucas;
        }

        // Base case (n = 1): L₀ = 2
        $lucas[] = 2;
        if ($n === 1) {
            return $lucas;
        }

        // Base case (n = 2): , L₀ = 2L₁ = 1
        $lucas[] = 1;
        if ($n === 2) {
            return $lucas;
        }

        // Standard iterative case: Lᵢ = Lᵢ₋₁ + Lᵢ₋₂
        for ($i = 2; $i < $n; $i++) {
            $lucas[$i] = $lucas[$i - 1] + $lucas[$i - 2];
        }

        return $lucas;
    }

    /**
     * Pell numbers
     * The denominators of the closest rational approximations to the square root of 2.
     * https://en.wikipedia.org/wiki/Pell_number
     *
     * P₀ = 0
     * P₁ = 1
     * Pᵢ = 2Pᵢ₋₁ + Pᵢ₋₂
     *
     * Example:
     *  n = 6
     *  Sequence:    0, 1, 2, 5, 12, 29
     *  Array index: 0, 1, 2, 3, 4,  5
     *
     * @param  int $n How many numbers in the sequence
     *
     * @return array Indexed from 0
     */
    public static function pellNumber(int $n): array
    {
        $pell = [];

        // Bad input; return empty list
        if ($n <= 0) {
            return $pell;
        }

        // Base case (n = 1): P₀ = 0
        $pell[] = 0;
        if ($n === 1) {
            return $pell;
        }

        // Base case (n = 2): P₀ = 0, P₁ = 1
        $pell[] = 1;
        if ($n === 2) {
            return $pell;
        }

        // Standard iterative case: Pᵢ = 2Pᵢ₋₁ + Pᵢ₋₂
        for ($i = 2; $i < $n; $i++) {
            $pell[$i] = 2 * $pell[$i - 1] + $pell[$i - 2];
        }

        return $pell;
    }

    /**
     * Triangular numbers
     * Figurate numbers that represent equilateral triangles.
     * https://en.wikipedia.org/wiki/Triangular_number
     *
     *      n(n + 1)
     * Tn = --------
     *         2
     *
     * Example:
     *  n = 6
     *  Sequence:    1, 3, 6, 10, 15, 21
     *  Array index: 1, 2, 3,  4,  5,  6
     *
     * @param  int $n How many numbers in the sequence
     *
     * @return array Indexed from 1
     */
    public static function triangularNumber(int $n): array
    {
        $triangular = [];
        // Bad input; return empty list
        if ($n <= 0) {
            return $triangular;
        }

        // Standard case for pn: n(n + 1) / 2
        for ($i = 1; $i <= $n; $i++) {
            $triangular[$i] = ($i * ($i + 1)) / 2;
        }

        return $triangular;
    }

    /**
     * Pentagonal numbers
     * Figurate numbers that represent pentagons.
     * https://en.wikipedia.org/wiki/Pentagonal_number
     *
     *      3n² - n
     * pn = -------
     *         2
     *
     * Example:
     *  n = 6
     *  Sequence:    1, 5, 12, 22, 35, 51
     *  Array index: 1, 2, 3,  4,  5,  6
     *
     * @param  int $n How many numbers in the sequence
     *
     * @return array Indexed from 1
     */
    public static function pentagonalNumber(int $n): array
    {
        $pentagonal = [];
        // Bad input; return empty list
        if ($n <= 0) {
            return $pentagonal;
        }

        // Standard case for pn: (3n² - n) / 2
        for ($i = 1; $i <= $n; $i++) {
            $pentagonal[$i] = (3*($i**2) - $i) / 2;
        }

        return $pentagonal;
    }

    /**
     * Hexagonal numbers
     * Figurate numbers that represent hexagons.
     * https://en.wikipedia.org/wiki/Hexagonal_number
     *
     *      2n × (2n - 1)
     * hn = -------------
     *           2
     *
     * Example:
     *  n = 6
     *  Sequence:    1, 6, 15, 28, 45, 66
     *  Array index: 1, 2, 3,  4,  5,  6
     *
     * @param  int $n How many numbers in the sequence
     *
     * @return array Indexed from 1
     */
    public static function hexagonalNumber(int $n): array
    {
        $hexagonal = [];

        // Bad input; return empty list
        if ($n <= 0) {
            return $hexagonal;
        }

        // Standard case for hn: (2n × (2n - 1)) / 2
        for ($i = 1; $i <= $n; $i++) {
            $hexagonal[$i] = ((2 * $i) * (2 * $i - 1)) / 2;
        }

        return $hexagonal;
    }

    /**
     * Heptagonal numbers
     * Figurate numbers that represent heptagons.
     * https://en.wikipedia.org/wiki/Heptagonal_number
     *
     *      5n² - 3n
     * Hn = --------
     *         2
     *
     * Example:
     *  n = 6
     *  Sequence:    1, 4, 7, 13, 18, 27
     *  Array index: 1, 2, 3, 4,  5,  6
     *
     * @param  int $n How many numbers in the sequence
     *
     * @return array Indexed from 1
     */
    public static function heptagonalNumber(int $n): array
    {
        $heptagonal = [];

        // Bad input; return empty list
        if ($n <= 0) {
            return $heptagonal;
        }

        // Standard case for Hn: (5n² - 3n) / 2
        for ($i = 1; $i <= $n; $i++) {
            $heptagonal[$i] = ((5 * $i**2) - (3 * $i)) / 2;
        }

        return $heptagonal;
    }
}
