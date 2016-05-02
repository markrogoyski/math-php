<?php
namespace Math\Statistics;
require_once('Average.php');
require_once('Descriptive.php');

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

  /**
   * Population correlation coefficient
   * Pearson product-moment correlation coefficient (PPMCC or PCC or Pearson's r)
   *
   * A normalized measure of the linear correlation between two variables X and Y, giving a value between +1 and −1 inclusive, where 1 is total positive correlation, 0 is no correlation, and −1 is total negative correlation.
   * It is widely used in the sciences as a measure of the degree of linear dependence between two variables.
   * https://en.wikipedia.org/wiki/Pearson_product-moment_correlation_coefficient
   *
   * The correlation coefficient of two variables in a data sample is their covariance divided by the product of their individual standard deviations.
   *
   *        cov(X,Y)
   * ρxy = ----------
   *         σx σy
   *
   *  conv(X,Y) is the population covariance
   *  σx is the population standard deviation of X
   *  σy is the population standard deviation of Y
   *
   * @param array $x values for random variable X
   * @param array $y values for random variabel Y
   * @return number
   */
  public static function populationCorrelationCoefficient( array $X, array $Y ) {
    $cov⟮X，Y⟯ = self::populationCovariance( $X, $Y );
    $σx      = Descriptive::standardDeviation($X);
    $σy      = Descriptive::standardDeviation($Y);

    return $cov⟮X，Y⟯ / ( $σx * $σy );
  }

  /**
   * Sample correlation coefficient
   * Pearson product-moment correlation coefficient (PPMCC or PCC or Pearson's r)
   *
   * A normalized measure of the linear correlation between two variables X and Y, giving a value between +1 and −1 inclusive, where 1 is total positive correlation, 0 is no correlation, and −1 is total negative correlation.
   * It is widely used in the sciences as a measure of the degree of linear dependence between two variables.
   * https://en.wikipedia.org/wiki/Pearson_product-moment_correlation_coefficient
   *
   * The correlation coefficient of two variables in a data sample is their covariance divided by the product of their individual standard deviations.
   *
   *          Sxy
   * rxy = ----------
   *         sx sy
   *
   *  Sxy is the sample covariance
   *  σx is the sample standard deviation of X
   *  σy is the sample standard deviation of Y
   *
   * @param array $x values for random variable X
   * @param array $y values for random variabel Y
   * @return number
   */
  public static function sampleCorrelationCoefficient( array $X, array $Y ) {
    $Sxy = self::sampleCovariance( $X, $Y );
    $sx  = Descriptive::standardDeviation( $X, Descriptive::SAMPLE );
    $sy  = Descriptive::standardDeviation( $Y, Descriptive::SAMPLE );

    return $Sxy / ( $sx * $sy );
  }

  /**
   * n-th Central moment
   * A moment of a probability distribution of a random variable about the random variable's mean.
   * It is the expected value of a specified integer power of the deviation of the random variable from the mean.
   * https://en.wikipedia.org/wiki/Central_moment
   *
   *      ∑⟮xᵢ - μ⟯ⁿ
   * μn = ----------
   *          N
   *
   * @param array $X list of numbers (random variable X)
   * @param array $n n-th central moment to calculate
   * @return number n-th central moment
   */
  public static function centralMoment( array $X, $n ) {
    if ( empty($X) ) {
      return null;
    }

    $μ         = Average::mean($X);
    $∑⟮xᵢ − μ⟯ⁿ = array_sum( array_map(
      function($xᵢ) use ( $μ, $n ) { return pow( ($xᵢ - $μ), $n ); },
      $X
    ) );
    $N = count($X);
    
    return $∑⟮xᵢ − μ⟯ⁿ / $N;
  }

  /**
   * Popluation skewness
   * A measure of the asymmetry of the probability distribution of a real-valued random variable about its mean.
   * https://en.wikipedia.org/wiki/Skewness
   * http://brownmath.com/stat/shape.htm
   *
   * This method tends to match Excel's SKEW.P function.
   *
   *         μ₃
   * γ₁ = -------
   *       μ₂³′²
   *
   * μ₂ is the second central moment
   * μ₃ is the third central moment
   *
   * @param array $X list of numbers (random variable X)
   * @return number
   */
  public static function populationSkewness( array $X ) {
    $μ₃ = self::centralMoment( $X, 3 );
    $μ₂ = self::centralMoment( $X, 2 );
    
    $μ₂³′² = pow( $μ₂, 3/2 );

    return ($μ₃ /  $μ₂³′²);
  }

  /**
   * Sample skewness
   * A measure of the asymmetry of the probability distribution of a real-valued random variable about its mean.
   * https://en.wikipedia.org/wiki/Skewness
   * http://brownmath.com/stat/shape.htm
   *
   * This method tends to match Excel's SKEW function.
   *
   *         μ₃     √(n(n - 1))
   * γ₁ = ------- × -----------
   *       μ₂³′²       n - 2
   *
   * μ₂ is the second central moment
   * μ₃ is the third central moment
   * n is the sample size
   *
   * @param array $X list of numbers (random variable X)
   * @return number
   */
  public static function sampleSkewness( array $X ) {
    $n = count($X);
    $μ₃ = self::centralMoment( $X, 3 );
    $μ₂ = self::centralMoment( $X, 2 );
    $μ₂³′² = pow( $μ₂, 3/2 );
    $√⟮n⟮n − 1⟯⟯ = sqrt( $n * ($n - 1) );

    return ($μ₃ / $μ₂³′²) * ( $√⟮n⟮n − 1⟯⟯ / ($n - 2) );
  }

  /**
   * Skewness (alternative method)
   * This method tends to match most of the online skewness calculators and examples.
   * https://en.wikipedia.org/wiki/Skewness
   *
   *         1     ∑⟮xᵢ - μ⟯³
   * γ₁ =  ----- × ---------
   *       N - 1       σ³
   *
   * μ is the mean
   * σ³ is the standard deviation cubed, or, the variance raised to the 3/2 power.
   * N is the sample size
   *
   * @param array $X list of numbers (random variable X)
   * @return number
   */
  public static function skewness( array $X ) {
    if ( empty($X) ) {
      return null;
    }

    $μ         = Average::mean($X);
    $∑⟮xᵢ − μ⟯³ = array_sum( array_map(
      function($xᵢ) use ($μ) { return pow( ($xᵢ - $μ), 3 ); },
      $X
    ) );
    $σ³ = pow( Descriptive::standardDeviation($X, false), 3 );
    $N = count($X);
    
    return $∑⟮xᵢ − μ⟯³ / ($σ³ * ($N - 1));
  }
}