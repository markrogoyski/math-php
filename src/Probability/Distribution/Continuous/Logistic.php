<?php
namespace Math\Probability\Distribution\Continuous;

class Logistic extends Continuous
{
    /**
     * Logistic distribution - probability density function
     *
     * https://en.wikipedia.org/wiki/Logistic_distribution
     *
     *                     /  x - μ \
     *                 exp| - -----  |
     *                     \    s   /
     * f(x; μ, s) = -----------------------
     *                /        /  x - μ \ \ ²
     *              s| 1 + exp| - -----  | |
     *                \        \    s   / /
     *
     * @param number $μ location parameter
     * @param number $s scale parameter > 0
     * @param number $x
     *
     * @return float
     */
    public static function PDF($μ, $s, $x)
    {
        if ($s <= 0) {
            throw new \Exception('Scale parameter s must be > 0');
        }
        $ℯ＾⁻⁽x⁻μ⁾／s = exp(-($x - $μ) / $s);
        return $ℯ＾⁻⁽x⁻μ⁾／s / ($s * pow(1 + $ℯ＾⁻⁽x⁻μ⁾／s, 2));
    }
    /**
     * Logistic distribution - cumulative distribution function
     * From -∞ to x (lower CDF)
     * https://en.wikipedia.org/wiki/Logistic_distribution
     *
     *                      1
     * f(x; μ, s) = -------------------
     *                      /  x - μ \
     *              1 + exp| - -----  |
     *                      \    s   /
     *
     * @param number $μ location parameter
     * @param number $s scale parameter
     * @param number $x
     *
     * @return float
     */
    public static function CDF($μ, $s, $x)
    {
        if ($s <= 0) {
            throw new \Exception('Scale parameter s must be > 0');
        }
        $ℯ＾⁻⁽x⁻μ⁾／s = exp(-($x - $μ) / $s);
        return 1 / (1 + $ℯ＾⁻⁽x⁻μ⁾／s);
    }
    
    /**
     * Mean of the distribution
     *
     * μ = μ
     *
     * @param number $μ location parameter
     * @param number $s scale parameter
     *
     * @return μ
     */
    public static function mean($μ, $s)
    {
        return $μ;
    }
}
