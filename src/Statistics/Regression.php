<?php
namespace Math\Statistics;

use Math\Statistics\Average;

class Regression {

  /**
   * Array indexes for points
   * @var int
   */
  const X = 0;
  const Y = 1;

  /**
   * Simple linear regression - least squares method
   *
   * A model with a single explanatory variable.
   * Fits a straight line through the set of n points in such a way that makes the sum of squared residuals of the model
   * (that is, vertical distances between the points of the data set and the fitted line) as small as possible.
   * https://en.wikipedia.org/wiki/Simple_linear_regression
   *
   * Having data points {(xᵢ, yᵢ), i = 1 ..., n }
   * Find the equation y = α + βx
   *
   *      _ _   __
   * β =  x y - xy
   *     _________
   *      _     __
   *     (x)² - x²
   *
   *     _    _
   * α = y - βx
   *
   * @param array $points [ [x, y], [x, y], ... ]
   * @return array [ regression equation, slope, y intercept, correlation coefficient, coefficient of determiniation, sample size, mean x, mean y ]
   */
  public static function linear( array $points ) {
    // Get list of x points and y points.
    $xs = array_map( function($point) { return $point[self::X]; }, $points );
    $ys = array_map( function($point) { return $point[self::Y]; }, $points );

    // Averages used in β (slope) calculation
    $x   = Average::mean($xs);
    $y   = Average::mean($ys);
    $xy  = Average::mean( array_map( function($point) { return $point[self::X] * $point[self::Y]; }, $points ) );
    $⟮x⟯² = pow( $x, 2 );
    $x²  = Average::mean( array_map( function($i) { return $i**2;}, $xs) );

    // Calculate slope (β) and y intercept (α)
    $β = (( $x * $y ) - $xy) / ($⟮x⟯² - $x²);
    $α = $y - ($β * $x);

    $r = self::correlationCoefficient($points);

    return [
      'regression equation'          => sprintf( '%s + %sx', $α, $β ),
      'slope'                        => $β,
      'y intercept'                  => $α,
      'correlation coefficient'      => $r,
      'coefficient of determination' => $r * $r,
      'sample size'                  => count($points),
      'mean x'                       => $x,
      'mean y'                       => $y,
    ];
  }

  /**
   * Evaluate the line equation from linear regression parameters for a value of x
   * y = α + βx
   * Where α = y intercept
   * Where β = slope
   *
   * @param number $x
   * @param number $β slope
   * @param number $α y intercept
   * @return number y evaluated
   */
  public static function linearEvaluate( $x, $β, $α ) {
    return $β*$x + $α;
  }

  /**
   * R - correlation coefficient (Pearson's r)
   *
   * A measure of the strength and direction of the linear relationship between two variables
   * that is defined as the (sample) covariance of the variables divided by the product of their (sample) standard deviations.
   *
   *      n∑⟮xy⟯ − ∑⟮x⟯∑⟮y⟯
   * --------------------------------
   * √［（n∑x² − ⟮∑x⟯²）（n∑y² − ⟮∑y⟯²）］
   *
   * @param array $points [ [x, y], [x, y], ... ]
   * @return number
   */
  public static function correlationCoefficient( array $points ) {
    // Get list of x points and y points.
    $xs = array_map( function($point) { return $point[self::X]; }, $points );
    $ys = array_map( function($point) { return $point[self::Y]; }, $points );
    $n  = count($points);

    // Numerator calculations
    $n∑⟮xy⟯ = $n * array_sum( array_map(
      function( $x, $y ) { return $x * $y; },
      $xs, $ys
    ) );
    $∑⟮x⟯∑⟮y⟯ = array_sum($xs) * array_sum($ys);

    // Denominator calculations
    $n∑x² = $n * array_sum( array_map(
      function($x) { return $x**2; },
      $xs
    ) );
    $⟮∑x⟯² = pow( array_sum($xs), 2 );

    $n∑y² = $n * array_sum( array_map(
      function($y) { return $y**2; },
      $ys
    ) );
    $⟮∑y⟯² = pow( array_sum($ys), 2 );

    return ( $n∑⟮xy⟯ - $∑⟮x⟯∑⟮y⟯ ) / sqrt( ($n∑x² - $⟮∑x⟯²) * ($n∑y² - $⟮∑y⟯²) );
  }

  /**
   * R - correlation coefficient
   * Convenience wrapper for correlationCoefficient
   *
   * @param array $points [ [x, y], [x, y], ... ]
   * @return number
   */
  public static function r( array $points ) {
    return self::correlationCoefficient($points);
  }

  /**
   * R² - coefficient of determination
   *
   * Indicates the proportion of the variance in the dependent variable that is predictable from the independent variable.
   * Range of 0 - 1. Close to 1 means the regression line is a good fit
   * https://en.wikipedia.org/wiki/Coefficient_of_determination
   *
   * @param array $points [ [x, y], [x, y], ... ]
   * @return number
   */
  public static function coefficientOfDetermination( array $points ) {
    return pow( self::correlationCoefficient($points), 2 );
  }

  /**
   * R² - coefficient of determination
   * Convenience wrapper for coefficientOfDetermination
   *
   * @param array $points [ [x, y], [x, y], ... ]
   * @return number
   */
  public static function r2( array $points ) {
    return pow( self::correlationCoefficient($points), 2 );
  }
}