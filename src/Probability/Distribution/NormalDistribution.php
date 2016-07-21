<?php
namespace Math\Probability\Distribution;
use Math\Functions\Special;
class NormalDistribution extends Distribution {
  public static function PDF($x, $μ, $σ²) {
    $π = \M_PI;
    $pdf = exp(-1 * ($x - $μ) ** 2 / 2 / $σ²)/(sqrt(2 * $σ² * $π));
    return $pdf;
  }
  public static function CDF($x, $μ, $σ²) {
    $cdf = (1 + Special::erf(($x - $μ) / sqrt(2 * $σ²))) / 2;
    return $cdf;
  }
}
