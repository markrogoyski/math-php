<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Support;

/**
 * Logistic distribution
 * https://en.wikipedia.org/wiki/Logistic_distribution
 */
class Logistic extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x ∈ (-∞,∞)
     * μ ∈ (-∞,∞)
     * s ∈ (0,∞)
     * p ∈ [0,1]
     * @var array
     */
    const LIMITS = [
        'x' => '(-∞,∞)',
        'μ' => '(-∞,∞)',
        's' => '(0,∞)',
        'p' => '[0,1]',
    ];

    /**
     * Probability density function
     *
     *                     /  x - μ \
     *                 exp| - -----  |
     *                     \    s   /
     * f(x; μ, s) = -----------------------
     *                /        /  x - μ \ \ ²
     *              s| 1 + exp| - -----  | |
     *                \        \    s   / /
     *
     * @param number $x
     * @param number $μ location parameter
     * @param number $s scale parameter > 0
     *
     * @return float
     */
    public static function PDF($x, $μ, $s)
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'μ' => $μ, 's' => $s]);

        $ℯ＾⁻⁽x⁻μ⁾／s = exp(-($x - $μ) / $s);
        return $ℯ＾⁻⁽x⁻μ⁾／s / ($s * pow(1 + $ℯ＾⁻⁽x⁻μ⁾／s, 2));
    }
    /**
     * Cumulative distribution function
     * From -∞ to x (lower CDF)
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
    public static function CDF($x, $μ, $s)
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'μ' => $μ, 's' => $s]);

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
        Support::checkLimits(self::LIMITS, ['μ' => $μ, 's' => $s]);

        return $μ;
    }
    
    /**
     * Inverse CDF (quantile function)
     *
     *                     /   p   \
     * Q(p;μ,s) = μ + s ln|  -----  |
     *                     \ 1 - p /
     *
     * @param number $p
     * @param number $μ
     * @param number $s
     *
     * @return number
     */
    public static function inverse($p, ...$params)
    {
        $μ = $params[0];
        $s = $params[1];
        Support::checkLimits(self::LIMITS, ['μ' => $μ, 's' => $s, 'p' => $p]);

        return $μ + $s * log($p / (1 - $p));
    }
}
