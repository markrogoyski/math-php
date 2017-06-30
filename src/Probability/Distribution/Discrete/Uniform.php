<?php
namespace MathPHP\Probability\Distribution\Discrete;

use MathPHP\Exception;

/**
 * Discrete uniform distribution
 * https://en.wikipedia.org/wiki/Discrete_uniform_distribution
 */
class Uniform extends Discrete
{
    /**
     * Distribution parameter bounds limits
     * k ∈ (-∞,∞)
     * a ∈ (-∞,∞)
     * b ∈ (-∞,∞)  b > a
     * @var array
     */
    const LIMITS = [
        'k' => '(-∞,∞)',
        'a' => '(-∞,∞)',
        'b' => '(-∞,∞)',
    ];

    /**
     * Probability mass function
     *
     *       1
     * pmf = -
     *       n
     *
     * Percentile n = b - a + 1
     *
     * @param number $a lower boundary of the distribution
     * @param number $b upper boundary of the distribution
     *
     * @return number
     * @throws BadDataException if b is ≤ a
     */
    public static function pmf(int $a, int $b)
    {
        if ($b <= $a) {
            throw new Exception\BadDataException("b must be > a (b:$b a:$a)");
        }

        $n = $b - $a + 1;

        return 1 / $n;
    }

    /**
     * Cumulative distribution function
     *
     *       k - a + 1
     * pmf = ---------
     *           n
     *
     * Percentile n = b - a + 1
     *
     * @param number $a lower boundary of the distribution
     * @param number $b upper boundary of the distribution
     * @param number $k percentile
     *
     * @return number
     * @throws BadDataException if b is ≤ a
     */
    public static function cdf(int $a, int $b, int $k)
    {
        if ($b <= $a) {
            throw new Exception\BadDataException("b must be > a (b:$b a:$a)");
        }
        if ($k < $a) {
            return 0;
        }
        if ($k > $b) {
            return 1;
        }

        $n = $b - $a + 1;

        return ($k - $a + 1) / $n;
    }

    /**
     * Mean of the distribution
     *
     *     a + b
     * μ = -----
     *       2
     *
     * @param number $a lower boundary of the distribution
     * @param number $b upper boundary of the distribution
     *
     * @return number
     */
    public static function mean($a, $b)
    {
        if ($b <= $a) {
            throw new Exception\BadDataException("b must be > a (b:$b a:$a)");
        }
        return ($a + $b) / 2;
    }

    /**
     * Median of the distribution
     *
     *     a + b
     * μ = -----
     *       2
     *
     * @param number $a lower boundary of the distribution
     * @param number $b upper boundary of the distribution
     *
     * @return number
     */
    public static function median($a, $b)
    {
        if ($b <= $a) {
            throw new Exception\BadDataException("b must be > a (b:$b a:$a)");
        }
        return ($a + $b) / 2;
    }
}
