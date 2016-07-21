<?php
namespace Math\Probability\Distribution

class ParetoDistribution extends Continuous {

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
     */
    public static function CDF($a, $b, $x)
    {
        if ($x < $b) {
            return 0;
        }
        return 1 - pow($b / $x, $a);
    }
}
