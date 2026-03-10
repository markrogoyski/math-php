<?php

namespace MathPHP\Polynomials;

use Generator;
use InvalidArgumentException;
use MathPHP\Exception;
use MathPHP\Probability\Combinatorics;

final class MonomialExponentGenerator
{
    /**
     * @param int $dimension
     * @param int $degree
     * @return int
     * @throws Exception\OutOfBoundsException
     */
    public static function getNumberOfTerms(int $dimension, int $degree): int
    {
        return (int)(Combinatorics::factorial($dimension + $degree) /
            (Combinatorics::factorial($degree) * Combinatorics::factorial($dimension)));
    }

    /**
     * Returns all exponent tuples with total degree <= $degree.
     *
     * @param int $dimension d >= 1
     * @param int $degree p >= 0
     * @param bool $reverse
     * @return list<list<int>>
     */
    public static function all(int $dimension, int $degree, bool $reverse): array
    {
        $gen = self::iterate($dimension, $degree, $reverse);
        return \iterator_to_array($gen, false);
    }

    /**
     * Generator over all exponent tuples with total degree <= $degree.
     * Uses generators to keep memory usage low for large d/p.
     *
     * @param int $dimension
     * @param int $degree
     * @param bool $reverse
     * @return Generator<int, list<int>> yields int[] (length = $dimension)
     */
    public static function iterate(int $dimension, int $degree, bool $reverse): Generator
    {
        if ($dimension < 1) {
            throw new InvalidArgumentException("dimension must be >= 1.");
        }
        if ($degree < 0) {
            throw new InvalidArgumentException("degree must be >= 0.");
        }

        $current = \array_fill(0, $dimension, 0);

        if ($reverse) {
            // Degrees 0..p; within each degree use lexicographic order
            for ($g = 0; $g <= $degree; $g++) {
                yield from self::recursiveDistributeRevLex($dimension, $g, 0, $current);
            }
        } else {
            // Degrees 0..p; within each degree use lexicographic order
            for ($g = 0; $g <= $degree; $g++) {
                yield from self::recursiveDistributeLex($dimension, $g, 0, $current);
            }
        }
    }

    /**
     * Recursive helper: distributes `remaining` units across positions in lexicographic order.
     *
     * @param int $dimension
     * @param int $remaining
     * @param int $pos
     * @param list<int> $current Variable reference for performance.
     * @return Generator<int, list<int>>
     */
    private static function recursiveDistributeLex(int $dimension, int $remaining, int $pos, array &$current): Generator
    {
        if ($pos === $dimension - 1) {
            $current[$pos] = $remaining;
            yield $current;
            return;
        }
        for ($e = 0; $e <= $remaining; $e++) {
            $current[$pos] = $e;
            yield from self::recursiveDistributeLex($dimension, $remaining - $e, $pos + 1, $current);
        }
    }

    /**
     * Recursive helper: distributes `remaining` units across positions in reverse-lex order.
     *
     * @param int $dimension
     * @param int $remaining
     * @param int $pos
     * @param list<int> $current Variable reference for performance.
     * @return Generator<int, list<int>>
     */
    private static function recursiveDistributeRevLex(int $dimension, int $remaining, int $pos, array &$current): Generator
    {
        if ($pos === $dimension - 1) {
            $current[$pos] = $remaining;
            yield $current;
            return;
        }
        // reverse-lex: prioritize larger exponents at earlier positions
        for ($e = $remaining; $e >= 0; $e--) {
            $current[$pos] = $e;
            yield from self::recursiveDistributeRevLex($dimension, $remaining - $e, $pos + 1, $current);
        }
    }
}
