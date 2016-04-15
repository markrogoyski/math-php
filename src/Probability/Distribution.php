<?php
namespace Math\Probability;
require_once('Combinatorics.php');

class Distribution {

  /**
   * Binomial distribution
   *
   * P(X = r) = nCr pʳ (1 - p)ⁿ⁻ʳ
   *
   * @param int   $n number of events
   * @param int   $r number of successful events
   * @param float $p probability of success
   */
  public static function binomial( int $n, int $r, float $p ) {
    if ( $p < 0 || $p > 1 ) {
      throw new \Exception("Probability $p must be between 0 and 1.");
    }

    $nCr       = Combinatorics::combinations( $n, $r );
    $pʳ        = pow( $p, $r );
    $⟮1 − p⟯ⁿ⁻ʳ = pow( 1 - $p, $n - $r );

    return $nCr * $pʳ * $⟮1 − p⟯ⁿ⁻ʳ;
  }
}