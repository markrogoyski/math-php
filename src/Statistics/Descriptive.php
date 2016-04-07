<?php
namespace Math\Statistics;
require_once('Average.php');

class Descriptive {

  /**
   * Calculate the range--the difference between the largest and smallest values
   *
   * @param array $numbers
   * @return number
   */
  public static function range( array $numbers ) {
    if ( empty($numbers) ) {
      return null;
    }
    return max($numbers) - min($numbers);
  }

  /**
   * Calculate the range--the mean of the largest and smallest values
   *
   * @param array $numbers
   * @return number
   */
  public static function midrange( array $numbers ) {
    if ( empty($numbers) ) {
      return null;
    }
    return Average::mean([ min($numbers), max($numbers) ]);
  }

  /**
   * Population variance - Use when all possible observations of the system are present.
   * Variance measures how far a set of numbers are spread out.
   * A variance of zero indicates that all the values are identical.
   * Variance is always non-negative: a small variance indicates that the data points tend to be very close to the mean (expected value) and hence to each other.
   * A high variance indicates that the data points are very spread out around the mean and from each other.
   *
   * σ² = Σ(X - μ)^2
   *      ----------
   *           N
   *
   * μ is the mean
   * N is the number of numbers in the set
   *
   * @param array $numbers
   * @return numeric
   */
  public static function populationVariance( array $numbers ) {
    if ( empty($numbers) ) {
      return null;
    }

    $mean      = Average::mean($numbers);
    $numerator = array_sum( array_map(
      function($x) use ($mean) { return pow( ($x - $mean), 2 ); },
      $numbers
    ) );
    $denominator = count($numbers);
    
    return $numerator / $denominator;
  }

  /**
   * Sample variance - Use when only a subset of all possible observations of the system are present.
   * Variance measures how far a set of numbers are spread out.
   * A variance of zero indicates that all the values are identical.
   * Variance is always non-negative: a small variance indicates that the data points tend to be very close to the mean (expected value) and hence to each other.
   * A high variance indicates that the data points are very spread out around the mean and from each other.
   *
   * σ² = Σ(X - μ)^2
   *      ----------
   *         N - 1
   *
   * μ is the mean
   * N is the number of numbers in the set
   *
   * @param array $numbers
   * @return numeric
   */
  public static function sampleVariance( array $numbers ) {
    if ( empty($numbers) ) {
      return null;
    }
    if ( count($numbers) == 1 ) {
      return 0;
    }

    $mean      = Average::mean($numbers);
    $numerator = array_sum( array_map(
      function($x) use ($mean) { return pow( ($x - $mean), 2 ); },
      $numbers
    ) );
    $denominator = count($numbers) - 1;
    
    return $numerator / $denominator;
  }

  /**
   * Wrapper for computing the variance
   * @param array $numbers
   * @param bool  $population: true uses population variance; false uses sample variance; Default is true (population variance)
   * @return numeric
   */
  public static function variance( array $numbers, bool $population = true ) {
    return $population
      ? self::populationVariance($numbers)
      : self::sampleVariance($numbers);
  }

  /**
   * Standard deviationis a measure that is used to quantify the amount of variation or dispersion of a set of data values.
   * A low standard deviation indicates that the data points tend to be close to the mean (also called the expected value) of the set
   * A high standard deviation indicates that the data points are spread out over a wider range of values.
   *
   * σ = √(σ²) = √(variance)
   *
   * @param array $numbers
   * @param bool  $population_variance: true uses population variance; false uses sample variance; Default is true (population variance)
   * @return numeric
   */
  public static function standardDeviation( array $numbers, bool $population_variance = true ) {
    if ( empty($numbers) ) {
      return null;
    }
    return $population_variance
      ? sqrt( self::populationVariance($numbers) )
      : sqrt( self::sampleVariance($numbers) );
  }

  /**
   * Get a report of all the descriptive statistics over a list of numbers
   * Includes mean, median, mode, range, midrange, variance, and standard deviation
   *
   * @param array $numbers
   * @param bool  $population: true means all possible observations of the system are present; false means a sample is used.
   * @return array [ mean, median, mode, range, midrange, variance, standard deviation ]
   */
  public static function getStats( array $numbers, bool $population = true ) {
    return [
      'mean'               => Average::mean($numbers),
      'median'             => Average::median($numbers),
      'mode'               => Average::mode($numbers),
      'range'              => self::range($numbers),
      'midrange'           => self::midrange($numbers),
      'variance'           => $population ? self::populationVariance($numbers) : self::sampleVariance($numbers),
      'standard_deviation' => self::standardDeviation( $numbers, $population ),
    ];
  }
}