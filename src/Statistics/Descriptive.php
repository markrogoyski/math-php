<?php
namespace Math\Statistics;
require_once('Average.php');

class Descriptive {

  /**
   * Range - the difference between the largest and smallest values
   * It is the size of the smallest interval which contains all the data.
   * It provides an indication of statistical dispersion.
   * (https://en.wikipedia.org/wiki/Range_(statistics))
   *
   * R = max x - min x
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
   * Midrange - the mean of the largest and smallest values
   * It is the midpoint of the range; as such, it is a measure of central tendency.
   * (https://en.wikipedia.org/wiki/Mid-range)
   *
   *     max x + min x
   * M = -------------
   *           2
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
   * If used with a subset of data (sample variance), it will be a biased variance.
   *
   * Variance measures how far a set of numbers are spread out.
   * A variance of zero indicates that all the values are identical.
   * Variance is always non-negative: a small variance indicates that the data points tend to be very close to the mean (expected value) and hence to each other.
   * A high variance indicates that the data points are very spread out around the mean and from each other.
   * (https://en.wikipedia.org/wiki/Variance)
   *
   *      ∑⟮xᵢ - μ⟯²
   * σ² = ----------
   *          N
   *
   * μ is the population mean
   * N is the number of numbers in the population set
   *
   * @param array $numbers
   * @return numeric
   */
  public static function populationVariance( array $numbers ) {
    if ( empty($numbers) ) {
      return null;
    }

    $μ         = Average::mean($numbers);
    $∑⟮xᵢ − μ⟯² = array_sum( array_map(
      function($xᵢ) use ($μ) { return pow( ($xᵢ - $μ), 2 ); },
      $numbers
    ) );
    $N = count($numbers);
    
    return $∑⟮xᵢ − μ⟯² / $N;
  }

  /**
   * Unbiased sample variance - Use when only a subset of all possible observations of the system are present.
   *
   * Variance measures how far a set of numbers are spread out.
   * A variance of zero indicates that all the values are identical.
   * Variance is always non-negative: a small variance indicates that the data points tend to be very close to the mean (expected value) and hence to each other.
   * A high variance indicates that the data points are very spread out around the mean and from each other.
   * (https://en.wikipedia.org/wiki/Variance)
   *
   *      ∑⟮xᵢ - x̄⟯²
   * S² = ----------
   *        n - 1
   *
   * x̄ is the sample mean
   * n is the number of numbers in the sample set
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

    $x         = Average::mean($numbers);
    $∑⟮xᵢ − x⟯² = array_sum( array_map(
      function($xᵢ) use ($x) { return pow( ($xᵢ - $x), 2 ); },
      $numbers
    ) );
    $n = count($numbers);
    
    return $∑⟮xᵢ − x⟯² / ($n - 1);
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
   * Standard deviation
   * A measure that is used to quantify the amount of variation or dispersion of a set of data values.
   * A low standard deviation indicates that the data points tend to be close to the mean (also called the expected value) of the set
   * A high standard deviation indicates that the data points are spread out over a wider range of values.
   * (https://en.wikipedia.org/wiki/Standard_deviation)
   *
   * σ = √⟮σ²⟯ = √⟮variance⟯
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
   * MAD - mean absolute deviation
   *
   * The average of the absolute deviations from a central point.
   * It is a summary statistic of statistical dispersion or variability.
   * (https://en.wikipedia.org/wiki/Average_absolute_deviation)
   *
   *       ∑|xᵢ - x̄|
   * MAD = ---------
   *           N
   *
   * x̄ is the mean
   * N is the number of numbers in the population set
   *
   * @param array $numbers
   * @return numeric
   */
  public static function meanAbsoluteDeviation( array $numbers ) {
    if ( empty($numbers) ) {
      return null;
    }

    $x         = Average::mean($numbers);
    $∑│xᵢ − x│ = array_sum( array_map(
      function($xᵢ) use ($x) { return abs( $xᵢ - $x ); },
      $numbers
    ) );
    $N = count($numbers);

    return $∑│xᵢ − x│ / $N;
  }

  /**
   * MAD - median absolute deviation
   *
   * The average of the absolute deviations from a central point.
   * It is a summary statistic of statistical dispersion or variability.
   * It is a robust measure of the variability of a univariate sample of quantitative data.
   * (https://en.wikipedia.org/wiki/Median_absolute_deviation)
   *
   * MAD = median(|xᵢ - x̄|)
   *
   * x̄ is the median
   *
   * @param array $numbers
   * @return numeric
   */
  public static function medianAbsoluteDeviation( array $numbers ) {
    if ( empty($numbers) ) {
      return null;
    }

    $x = Average::median($numbers);
    return Average::median( array_map(
      function($xᵢ) use ($x) { return abs( $xᵢ - $x ); },
      $numbers
    ) );
  }

  /**
   * Get a report of all the descriptive statistics over a list of numbers
   * Includes mean, median, mode, range, midrange, variance, and standard deviation
   *
   * @param array $numbers
   * @param bool  $population: true means all possible observations of the system are present; false means a sample is used.
   * @return array [ mean, median, mode, range, midrange, variance, standard deviation, mean_mad, median_mad ]
   */
  public static function getStats( array $numbers, bool $population = true ): array {
    return [
      'mean'               => Average::mean($numbers),
      'median'             => Average::median($numbers),
      'mode'               => Average::mode($numbers),
      'range'              => self::range($numbers),
      'midrange'           => self::midrange($numbers),
      'variance'           => $population ? self::populationVariance($numbers) : self::sampleVariance($numbers),
      'standard_deviation' => self::standardDeviation( $numbers, $population ),
      'mean_mad'           => self::meanAbsoluteDeviation($numbers),
      'median_mad'         => self::medianAbsoluteDeviation($numbers),
    ];
  }
}