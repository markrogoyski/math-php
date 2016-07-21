<?php
namespace Math\Probability\Distribution;
use Math\Functions\Special;

class TDistribution extends Continuous {
  public static function PDF($t, $ν){
    if(!is_int($ν)) return false;
    $π = \M_PI;
     $Γ⟮⟮ν＋1⟯∕2⟯ = Special::gamma(($ν + 1) / 2);
    $Γ⟮ν∕2⟯ = Special::gamma($ν / 2);
    $√⟮νπ⟯ = sqrt($ν * $π);
    $⟮1＋t²∕ν⟯ = 1 + $t ** 2 / $ν;
    $−⟮ν＋1⟯∕2 = -1 * ($ν + 1) / 2;
    
    $pdf = $Γ⟮⟮ν＋1⟯∕2⟯ * $⟮1＋t²∕ν⟯ ** $−⟮ν＋1⟯∕2 / $√⟮νπ⟯ / $Γ⟮ν∕2⟯;
    return $pdf;
  }
/***********
* Calculate the cumulative t value up to a point, left tail
* 
*/
  public static function CDF($t, $ν){
    if(!is_int($ν)) return false;
    if ($t == 0) return .5;
    $x = $ν / ($t ** 2 + $ν);
    $a = $ν / 2;
    $b = .5;
    return 1 - .5 * Special::regularized_incomplete_beta($x, $a, $b);
  }
  /****************************************************************************
   * Find t such that the area greater than t and the area beneath -t is $p
   */
  public function inverse_2_tails($p, $ν){
    return self::inverse(1 - $p / 2, $ν);
  }
}
