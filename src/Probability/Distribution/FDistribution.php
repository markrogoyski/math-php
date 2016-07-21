<?php
namespace Math\Probability\Distribution;
use Math\Functions\Special;

class FDistribution extends ContinuousNew {
  public static function PDF($x, $d1, $d2) {
    return sqrt( ($d1 * $x) ** $d1 * $d2 ** $d2/ ($d1 * $x + $d2) ** ($d1 + $d2)) / $x / Special::beta($d1 / 2, $d2 / 2);
  }
  
  public static function CDF($x, $d1, $d2){
    $beta_x = $d1 * $x / ($d1 * $x + $d2);
    return Special::regularized_incomplete_beta($beta_x, $d1 / 2, $d2 / 2);
  }
}
