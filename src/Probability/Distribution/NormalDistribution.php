<?php
namespace Math\Probability\Distribution;
use Math\Functions\Special;
class NormalDistribution extends Continuous {
  
  /**
     * Normal distribution - probability density function
     *
     * https://en.wikipedia.org/wiki/Normal_distribution
     *
     *              1
     * f(x|μ,σ) = ----- ℯ^−⟮x − μ⟯²∕2σ²
     *            σ√⟮2π⟯
     *
     * @param number $x random variable
     * @param number $μ mean
     * @param number $σ standard deviation
     *
     * @return float f(x|μ,σ)
     */
  public static function PDF($x, $μ, $σ²) {
    $π = \M_PI;
    $pdf = exp(-1 * ($x - $μ) ** 2 / 2 / $σ²)/(sqrt(2 * $σ² * $π));
    return $pdf;
  }
  
  /**
     * Normal distribution - cumulative distribution function
     * Probability of being below X.
     * Area under the normal distribution from -∞ to X.
     *             _                  _
     *          1 |         / x - μ \  |
     * cdf(x) = - | 1 + erf|  ----- |  |
     *          2 |_        \  σ√2  / _|
     *
     * @param number $x upper bound
     * @param number $μ mean
     * @param number $σ standard deviation
     *
     * @return float cdf(x) below
     */
  public static function CDF($x, $μ, $σ²) {
    $cdf = (1 + Special::erf(($x - $μ) / sqrt(2 * $σ²))) / 2;
    return $cdf;
  }
}
