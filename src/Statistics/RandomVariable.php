<?php
namespace Math\Statistics;
require_once('Average.php');

class RandomVariable {

  /**
   * Population Covariance
   * A measure of how much two random variables change together.
   * Average product of their deviations from their respective means.
   * The population covariance is defined in terms of the population means μx, μy
   * https://en.wikipedia.org/wiki/Covariance
   *
   * cov(X, Y) = σxy = E[⟮X - μx⟯⟮Y - μy⟯]
   *
   *                   ∑⟮xᵢ - μₓ⟯⟮yᵢ - μy⟯
   * cov(X, Y) = σxy = -----------------
   *                           N
   *
   * @param array $X values for random variable X
   * @param array $Y values for random variable Y
   * @return number
   */
  public static function populationCovariance( array $X, array $Y ) {
    if ( count($X) !== count($Y) ) {
      throw new \Exception("X and Y must have the same number of elements.");
    }
    $μₓ = Average::mean($X);
    $μy = Average::mean($Y);
    
    $∑⟮xᵢ − μₓ⟯⟮yᵢ − μy⟯ = array_sum( array_map(
      function( $xᵢ, $yᵢ ) use ( $μₓ, $μy ) { return ( $xᵢ - $μₓ ) * ( $yᵢ - $μy ); },
      $X, $Y
    ) );
    $N = count($X);

    return $∑⟮xᵢ − μₓ⟯⟮yᵢ − μy⟯ / $N;
  }

  /**
   * Sample covariance
   * A measure of how much two random variables change together.
   * Average product of their deviations from their respective means.
   * The population covariance is defined in terms of the sample means x, y
   * https://en.wikipedia.org/wiki/Covariance
   *
   * cov(X, Y) = Sxy = E[⟮X - x⟯⟮Y - y⟯]
   *
   *                   ∑⟮xᵢ - x⟯⟮yᵢ - y⟯
   * cov(X, Y) = Sxy = ---------------
   *                         n - 1
   *
   * @param array $X values for random variable X
   * @param array $Y values for random variabel Y
   * @return number
   */
  public static function sampleCovariance( array $X, array $Y ) {
    if ( count($X) !== count($Y) ) {
      throw new \Exception("X and Y must have the same number of elements.");
    }
    $x = Average::mean($X);
    $y = Average::mean($Y);
    
    $∑⟮xᵢ − x⟯⟮yᵢ − y⟯ = array_sum( array_map(
      function( $xᵢ, $yᵢ ) use ( $x, $y ) { return ( $xᵢ - $x ) * ( $yᵢ - $y ); },
      $X, $Y
    ) );
    $n = count($X);

    return $∑⟮xᵢ − x⟯⟮yᵢ − y⟯ / ($n - 1);
  }
}