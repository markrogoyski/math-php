<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Support;

/**
 * Pareto distribution
 * https://en.wikipedia.org/wiki/Pareto_distribution
 */
class Pareto extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * a ∈ (0,∞)
     * b ∈ (0,∞)
     * @var array
     */
    const PARAMETER_LIMITS = [
        'a' => '(0,∞)',
        'b' => '(0,∞)',
    ];

    /**
     * Distribution support bounds limits
     * x ∈ (0,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'x' => '(0,∞)',
        'a' => '(0,∞)',
        'b' => '(0,∞)',
    ];

    /** @var number Shape Parameter */
    protected $a;

    /** @var number Scale Parameter */
    protected $b;

    /**
     * Constructor
     *
     * @param number $a shape parameter
     * @param number $b scale parameter
     */
    public function __construct($a, $b)
    {
        parent::__construct($a, $b);
    }

    /**
     * Probability density function
     *
     *          abᵃ
     * P(x) =  ----  for x ≥ b
     *         xᵃ⁺¹
     *
     * P(x) = 0      for x < b
     *
     * @param  float $x
     *
     * @return number
     */
    public function pdf(float $x)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $a = $this->a;
        $b = $this->b;
        if ($x < $b) {
            return 0;
        }

        $abᵃ  = $a * $b**$a;
        $xᵃ⁺¹ = pow($x, $a + 1);
        return $abᵃ / $xᵃ⁺¹;
    }
    /**
     * Cumulative distribution function
     *
     *             / b \ᵃ
     * D(x) = 1 - |  -  | for x ≥ b
     *             \ x /
     *
     * D(x) = 0           for x < b
     *
     * @param  float $x
     *
     * @return number
     */
    public function cdf(float $x)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $a = $this->a;
        $b = $this->b;
        if ($x < $b) {
            return 0;
        }
        return 1 - pow($b / $x, $a);
    }

    /**
     * Mean of the distribution
     *
     * μ = ∞ for a ≤ 1
     *
     *      ab
     * μ = ----- for a > 1
     *     a - 1
     *
     *
     * @return number
     */
    public function mean()
    {

        $a = $this->a;
        $b = $this->b;
        if ($a <= 1) {
            return INF;
        }

        return $a * $b / ($a - 1);
    }
}
