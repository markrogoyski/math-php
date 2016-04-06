<?php
namespace Math\Statistics;
require_once('Average.php');

class Descriptive {

  /**
   * Variance measures how far a set of numbers are spread out.
   * A variance of zero indicates that all the values are identical.
   * Variance is always non-negative: a small variance indicates that the data points tend to be very close to the mean (expected value) and hence to each other.
   * A high variance indicates that the data points are very spread out around the mean and from each other.
   *
   * σ^2 = Σ(X - μ)^2
   *       ----------
   *            N
   *
   * μ is the mean
   * N is the number of numbers in the set
   *
   * @param array $numbers
   * @return numeric
   */
  public static function variance( array $numbers ) {
    if ( empty($numbers) ) {
      return null;
    }

    $mean = Average::mean($numbers);
    $numerator = array_sum( array_map( function($n) use ($mean) {
      return pow( ($n - $mean), 2 );
    }, $numbers ) );
    $denominator = count($numbers);
    
    return $numerator / $denominator;
  }

  /**
   * Standard deviationis a measure that is used to quantify the amount of variation or dispersion of a set of data values.
   * A low standard deviation indicates that the data points tend to be close to the mean (also called the expected value) of the set
   * A high standard deviation indicates that the data points are spread out over a wider range of values.
   *
   * σ = √(variance)
   *
   * @param array $numbers
   * @return numeric
   */
  public static function standardDeviation( array $numbers ) {
    if ( empty($numbers) ) {
      return null;
    }
    return sqrt( self::variance($numbers) );
  }
}