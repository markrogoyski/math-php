<?php
namespace Math\Statistics;
require_once( __DIR__ . '/../../src/Statistics/RandomVariable.php' );

class RandomVariableTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider dataProviderForPopulationCovariance
   */
  public function testPopulationCovariance( $X, $Y, $covariance ) {
    $this->assertEquals( $covariance, RandomVariable::populationCovariance( $X, $Y ), '', 0.01 );
  }

  /**
   * Data provider for population covariance test
   * Data: [ X, Y, covariance ]
   */
  public function dataProviderForPopulationCovariance() {
    return [
      [ [ 1, 2, 3, 4 ], [ 2, 3, 4, 5 ], 1.25 ],
      [ [ 1, 2, 4, 7, 9, 10 ], [ 2, 3, 5, 8, 11, 12.5 ], 13.29167 ],
      [ [ 1, 3, 2, 5, 8, 7, 12, 2, 4], [ 8, 6, 9, 4, 3, 3, 2, 7, 7 ], -7.1728 ],
    ];
  }

  public function testPopulationCovarianceExceptionWhenXAndYHaveDifferentCounts() {
    $this->setExpectedException('\Exception');
    RandomVariable::populationCovariance( [ 1, 2 ], [ 2, 3, 4 ] );
  }

  /**
   * @dataProvider dataProviderForSampleCovariance
   */
  public function testSampleCovariance( $X, $Y, $covariance ) {
    $this->assertEquals( $covariance, RandomVariable::sampleCovariance( $X, $Y ), '', 0.01 );
  }

  /**
   * Data provider for sample covariance test
   * Data: [ X, Y, covariance ]
   */
  public function dataProviderForSampleCovariance() {
    return [
      [ [ 1, 2, 3, 4 ], [ 2, 3, 4, 5 ], 1.66667 ],
      [ [ 1, 2, 4, 7, 9, 10 ], [ 2, 3, 5, 8, 11, 12.5 ], 15.95 ],
      [ [ 1, 3, 2, 5, 8, 7, 12, 2, 4], [ 8, 6, 9, 4, 3, 3, 2, 7, 7 ], -8.0694 ],
    ];
  }

  public function testSampleCovarianceExceptionWhenXAndYHaveDifferentCounts() {
    $this->setExpectedException('\Exception');
    RandomVariable::sampleCovariance( [ 1, 2 ], [ 2, 3, 4 ] );
  }

  /**
   * @dataProvider dataProviderForPopulationCorrelationCoefficient
   */
  public function testPopulationCorrelationCoefficient( array $x, array $y, $pcc ) {
    $this->assertEquals( $pcc, RandomVariable::populationCorrelationCoefficient( $x, $y ), '', 0.0001 );
  }

  /**
   * Data provider for population correlation coefficient test
   * Data: [ x, y, ppc ]
   */
  public function dataProviderForPopulationCorrelationCoefficient() {
    return [
      [ [ 1, 2, 4, 5, 8 ], [ 5, 20, 40, 80, 100 ], 0.96841 ],
      [ [ 1, 2, 4, 5, 8 ], [ 5, 20, 30, 50, 120 ], 0.96359 ],
    ];
  }

  /**
   * @dataProvider dataProviderForSampleCorrelationCoefficient
   */
  public function testSampleCorrelationCoefficient( array $x, array $y, $scc ) {
    $this->assertEquals( $scc, RandomVariable::sampleCorrelationCoefficient( $x, $y ), '', 0.0001 );
  }

  /**
   * Data provider for sample correlation coefficient test
   * Data: [ x, y, ppc ]
   */
  public function dataProviderForSampleCorrelationCoefficient() {
    return [
      [ [ 1, 2, 4, 5, 8 ], [ 5, 20, 40, 80, 100 ], 0.9684 ],
      [ [ 1, 2, 4, 5, 8 ], [ 5, 20, 30, 50, 120 ], 0.9636 ],
    ];
  }
}