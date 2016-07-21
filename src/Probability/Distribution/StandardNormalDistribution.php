<?php
namespace Math\Probability\Distribution;

class StandardNormalDistribution extends NormalDistribution {
  public static function PDF ($x) {
    return parent::PDF($x,0,1);
  }
  public static function CDF ($x) {
    return parent::CDF($x,0,1);
  }
}
