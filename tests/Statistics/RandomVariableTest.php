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

  /**
   * @dataProvider dataProviderForCentralMoment
   */
  public function testCentralMoment( array $X, $n, $moment ) {
    $this->assertEquals( $moment, RandomVariable::centralMoment( $X, $n ), '', 0.0001 );
  }

  public function dataProviderForCentralMoment() {
    return [
      [ [ 600, 470, 170, 430, 300 ], 1, 0 ],
      [ [ 600, 470, 170, 430, 300 ], 2, 21704 ],
      [ [ 600, 470, 170, 430, 300 ], 3, -568512 ],
    ];
  }

  public function testCentralMomentNullIfXEmpty() {
    $this->assertNull( RandomVariable::centralMoment( array(), 3 ) );
  }

  /**
   * @dataProvider dataProviderForPopulationSkewness
   */
  public function testPopulationSkewness( array $X, $skewness ) {
    $this->assertEquals( $skewness, RandomVariable::populationSkewness($X), '', 0.0001 );
  }

  public function dataProviderForPopulationSkewness() {
    return [
      [ [61,61,61,61,61,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,73,73,73,73,73,73,73,73], -0.1082 ],
      [ [2, 3, -1, 3, 4, 5, 0, 2], -0.3677 ],
      [ [1, 2, 3, 4, 5, 6, 8, 8], 0.07925 ],
      [ [1, 1, 3, 4, 5, 6, 7, 8], -0.07925 ],
      [ [3, 4, 5, 2, 3, 4, 5, 6, 4, 7 ], 0.303193 ],
      [ [1, 1, 2, 2, 2, 2, 3, 3, 3, 4, 4, 5, 6, 7, 8 ], 0.774523929 ],
      [ [1,2,3,4,5,6,7,8], 0 ],
    ];
  }

  /**
   * @dataProvider dataProviderForSampleSkewness
   */
  public function testSampleSkewness( array $X, $skewness ) {
    $this->assertEquals( $skewness, RandomVariable::sampleSkewness($X), '', 0.01 );
  }

  public function dataProviderForSampleSkewness() {
    return [
      [ [61,61,61,61,61,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,73,73,73,73,73,73,73,73], -0.1098 ],
      [ [1, 2, 3, 4, 5, 9, 23, 32, 69], 1.95 ],
      [ [3, 4, 5, 2, 3, 4, 5, 6, 4, 7 ], 0.359543 ],
      [ [1, 1, 2, 2, 2, 2, 3, 3, 3, 4, 4, 5, 6, 7, 8 ], 0.863378312 ],
      [ [2, 3, -1, 3, 4, 5, 0, 2], -0.4587 ],
      [ [-2.83, -0.95, -0.88, 1.21, -1.67, 0.83, -0.27, 1.36, -0.34, 0.48, -2.83, -0.95, -0.88, 1.21, -1.67], -0.1740 ],
      [ [1,2,3,4,5,6,7,8], 0 ],
    ];
  }

  /**
   * @dataProvider dataProviderForSkewness
   */
  public function testSkewness( array $X, $skewness ) {
    $this->assertEquals( $skewness, RandomVariable::skewness($X), '', 0.01 );
  }

  public function dataProviderForSkewness() {
    return [
      [ [61,61,61,61,61,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,73,73,73,73,73,73,73,73], -0.1076 ],
      [ [1, 2, 3, 4, 5, 9, 23, 32, 69], 1.514 ],
      [ [5,20,40,80,100], 0.2027 ],
      [ [3, 4, 5, 2, 3, 4, 5, 6, 4, 7], 0.2876 ],
      [ [1, 1, 3, 4, 5, 6, 7, 8], -0.07924 ],
      [ [1,2,3,4,5,6,7,8], 0 ],
    ];
  }

  /**
   * @dataProvider dataProviderForKurtosis
   */
  public function testKurtosis( array $X, $kurtosis ) {
    $this->assertEquals( $kurtosis, RandomVariable::kurtosis($X), '', 0.001 );
  }

  public function dataProviderForKurtosis() {
    return [
      [ [ 1987, 1987, 1991, 1992, 1992, 1992, 1992, 1993, 1994, 1994, 1995 ], -0.2320107 ],
      [ [ 0, 7, 7, 6, 6, 6, 5, 5, 4, 1 ], -0.27315697 ],
      [ [ 2, 2, 4, 6, 8, 10, 10 ], -1.57407407 ],
      [ [ 1242, 1353, 1142 ], -1.5 ],
      [ [1, 2, 3, 4, 5, 9, 23, 32, 69], 1.389416 ],
      [ [5,20,40,80,100], -1.525992 ],
      [ [4, 5, 5, 5, 5, 6], 0 ],
    ];
  }

  public function testIsPlatykurtic() {
    $this->assertTrue( RandomVariable::isPlatykurtic([ 2, 2, 4, 6, 8, 10, 10 ]) );
  }

  public function testIsLeptokurtic() {
    $this->assertTrue( RandomVariable::isLeptokurtic([ 1, 2, 3, 4, 5, 9, 23, 32, 69 ]) );
  }

  public function testIsMesokurtic() {
    $this->assertTrue( RandomVariable::isMesokurtic([ 4, 5, 5, 5, 5, 6 ]) );
  }

  /**
   * @dataProvider dataProviderForErrorFunction
   */
  public function testErrorFunction( $x, $error ) {
    $this->assertEquals( $error, RandomVariable::errorFunction($x), '', 0.0001 );
  }

  /**
   * @dataProvider dataProviderForErrorFunction
   */
  public function testErf( $x, $error ) {
    $this->assertEquals( $error, RandomVariable::erf($x), '', 0.0001 );
  }

  public function dataProviderForErrorFunction() {
    return [
      [ 0, 0 ],
      [ 1, 0.8427007929497148693412 ],
      [ -1, -0.8427007929497148693412 ],
      [ 2, 0.9953222650189527341621 ],
      [ 3.4, 0.9999984780066371377146 ],
      [ 0.154, 0.1724063976196591819236 ],
    ];
  }
}