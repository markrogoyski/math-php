<?php
namespace Math\Probability\Distribution\Continuous;

class Uniform extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x ∈ (-∞,∞)
     * a ∈ (-∞,∞)
     * b ∈ (-∞,∞)
     * @var array
     */
    const LIMITS = [
        'x' => '(-∞,∞)',
        'a' => '(-∞,∞)',
        'b' => '(-∞,∞)',
    ];

    /**
     * Continuous uniform distribution - probability desnsity function
     * https://en.wikipedia.org/wiki/Uniform_distribution_(continuous)
     *
     *         1
     * pdf = -----  for a ≤ x ≤ b
     *       b - a
     *
     * pdf = 0      for x < a, x > b
     *
     * @param number $a lower boundary of the distribution
     * @param number $b upper boundary of the distribution
     * @param number $x percentile
     */
    public static function PDF($a, $b, $x)
    {
        if ($x < $a || $x > $b) {
            return 0;
        }
        return 1 / ($b - $a);
    }
    
    /**
     * Continuous uniform distribution - cumulative distribution function
     * https://en.wikipedia.org/wiki/Uniform_distribution_(continuous)
     *
     * cdf = 0      for x < a
     *
     *       x - a
     * cdf = -----  for a ≤ x < b
     *       b - a
     *
     * cdf = 1      x ≥ b
     *
     * @param number $a lower boundary of the distribution
     * @param number $b upper boundary of the distribution
     * @param number $x percentile
     */
    public static function CDF($a, $b, $x)
    {
        if ($x < $a) {
            return 0;
        }
        if ($x >= $b) {
            return 1;
        }
        return ($x - $a) / ($b - $a);
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
        return ($a + $b) / 2;
    }
}
