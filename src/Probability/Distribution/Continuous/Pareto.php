<?php
namespace Math\Probability\Distribution\Continuous;

class Pareto extends Continuous
{
    /**
     * Pareto distribution - probability density function
     *
     * https://en.wikipedia.org/wiki/Pareto_distribution
     *
     *          abᵃ
     * P(x) =  ----  for x ≥ b
     *         xᵃ⁺¹
     *
     * P(x) = 0      for x < b
     *
     * @param  number $a shape parameter
     * @param  number $b scale parameter
     * @param  number $x
     *
     * @return number
     */
    public static function PDF($a, $b, $x)
    {
        if ($x < $b) {
            return 0;
        }
        $abᵃ  = $a * $b**$a;
        $xᵃ⁺¹ = pow($x, $a + 1);
        return $abᵃ / $xᵃ⁺¹;
    }
    /**
     * Pareto distribution - cumulative distribution function
     *
     * https://en.wikipedia.org/wiki/Pareto_distribution
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
    public static function CDF($a, $b, $x)
    {
        if ($x < $b) {
            return 0;
        }
        return 1 - pow($b / $x, $a);
    }

    /**
     * Mean of the distribution
     *
     * μ = ∞ for a < 1
     *
     *      ab
     * μ = -----
     *     a - 1
     *
     * @param  number $a shape parameter
     * @param  number $b scale parameter
     *
     * @return number
     */
    public static function mean($a, $b)
    {
        if ($a <= 0) {
            return INF;
        }

        return $a * $b / ($a - 1);
    }
}
