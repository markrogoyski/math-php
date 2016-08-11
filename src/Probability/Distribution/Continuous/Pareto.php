<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Support;

/**
 * Pareto distribution
 * https://en.wikipedia.org/wiki/Pareto_distribution
 */
class Pareto extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x ∈ (0,∞)
     * a ∈ (0,∞)
     * b ∈ (0,∞)
     * @var array
     */
    const LIMITS = [
        'x' => '(0,∞)',
        'a' => '(0,∞)',
        'b' => '(0,∞)',
    ];

    /**
     * Probability density function
     *
     *          abᵃ
     * P(x) =  ----  for x ≥ b
     *         xᵃ⁺¹
     *
     * P(x) = 0      for x < b
     *
     * @param  number $x
     * @param  number $a shape parameter
     * @param  number $b scale parameter
     *
     * @return number
     */
    public static function PDF($x, $a, $b)
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'a' => $a, 'b' => $b]);

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
     * @param  number $a shape parameter
     * @param  number $b scale parameter
     * @param  number $x
     *
     * @return number
     */
    public static function CDF($x, $a, $b)
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'a' => $a, 'b' => $b]);

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
     * @param  number $a shape parameter
     * @param  number $b scale parameter
     *
     * @return number
     */
    public static function mean($a, $b)
    {
        Support::checkLimits(self::LIMITS, ['a' => $a, 'b' => $b]);

        if ($a <= 1) {
            return INF;
        }

        return $a * $b / ($a - 1);
    }
}
