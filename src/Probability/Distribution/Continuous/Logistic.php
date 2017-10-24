<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Support;

/**
 * Logistic distribution
 * https://en.wikipedia.org/wiki/Logistic_distribution
 */
class Logistic extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * μ ∈ (-∞,∞)
     * s ∈ (0,∞)
     * @var array
     */
    const PARAMETER_LIMITS = [
        'μ' => '(-∞,∞)',
        's' => '(0,∞)',
    ];

    /**
     * Distribution support bounds limits
     * x ∈ (-∞,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'x' => '(-∞,∞)',
    ];

    /** @var number Location Parameter */
    protected $μ;
    
    /** @var number Scale Parameter */
    protected $s;

    /**
     * Constructor
     *
     * @param number $μ shape parameter
     * @param number $s shape parameter s > 0
     */
    public function __construct($μ, $s)
    {
        parent::__construct($μ, $s);
    }

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
     * @param float $x
     *
     * @return float
     */
    public function pdf(float $x)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $μ = $this->μ;
        $s = $this->s;

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
     * @param float $x
     *
     * @return float
     */
    public function cdf(float $x)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $μ = $this->μ;
        $s = $this->s;

        $ℯ＾⁻⁽x⁻μ⁾／s = exp(-($x - $μ) / $s);
        return 1 / (1 + $ℯ＾⁻⁽x⁻μ⁾／s);
    }
    
    /**
     * Mean of the distribution
     *
     * μ = μ
     *
     * @return number μ
     */
    public function mean()
    {
        return $this->μ;
    }
    
    /**
     * Inverse CDF (quantile function)
     *
     *                     /   p   \
     * Q(p;μ,s) = μ + s ln|  -----  |
     *                     \ 1 - p /
     *
     * @param float $p
     *
     * @return number
     */
    public function inverse(float $p)
    {
        Support::checkLimits(['p' => '[0,1]'], ['p' => $p]);
        $μ = $this->μ;
        $s = $this->s;

        return $μ + $s * log($p / (1 - $p));
    }
}
