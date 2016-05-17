<?php
namespace Math\Statistics;

class RegressionTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider dataProviderForLinear
   */
  public function testLinear( array $points, $sample, $slope, $y_intercept, $r, $r2 ) {
    $linear = Regression::linear($points);
    $this->assertEquals( $sample,      $linear['sample size'] );
    $this->assertEquals( $slope,       $linear['slope'], '', 0.001 );
    $this->assertEquals( $y_intercept, $linear['y intercept'], '', 0.001 );
    $this->assertEquals( $r,           $linear['correlation coefficient'], '', 0.001 );
    $this->assertEquals( $r2,          $linear['coefficient of determination'], '', 0.001 );
  }

  public function dataProviderForLinear() {
    return [
      [
        [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
        5, 1.2209302325581, 0.60465116279069, 0.993, 0.986049
      ],
      [
        [ [4,390], [9,580], [10,650], [14,730], [4,410], [7,530], [12,600], [22,790], [1,350], [3,400], [8,590], [11,640], [5,450], [6,520], [10,690], [11,690], [16,770], [13,700], [13,730], [10,640] ],
        20, 25.326467777896, 353.16487949889, 0.9336, 0.87160896,
      ],
    ];
  }

  /**
   * @dataProvider dataProviderForLinearEvaluate
   */
  public function testsLinearEvaluate( $x, $β, $α, $y ) {
    $this->assertEquals( $y, Regression::linearEvaluate( $x, $β, $α ) );
  }

  public function dataProviderForLinearEvaluate() {
    return [
      [ 0, 1, 0, 0 ],
      [ 3, 1, 0, 3 ],
      [ 4, 2, 0, 8 ],
      [ 5, 2.5, 1, 13.5 ],
      [ 3, -1, 5, 2 ],
    ];
  }

  /**
   * @dataProvider dataProviderForR
   */
  public function testCorrelationCoefficient( array $points, $r ) {
    $this->assertEquals( $r, Regression::correlationCoefficient($points), '', 0.001 );
  }

  /**
   * @dataProvider dataProviderForR
   */
  public function testR( array $points, $r ) {
    $this->assertEquals( $r, Regression::r($points), '', 0.001 );
  }

  public function dataProviderForR() {
    return [
      [
        [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
        0.993
      ],
      [
        [ [4,390], [9,580], [10,650], [14,730], [4,410], [7,530], [12,600], [22,790], [1,350], [3,400], [8,590], [11,640], [5,450], [6,520], [10,690], [11,690], [16,770], [13,700], [13,730], [10,640] ],
        0.9336
      ],
    ];
  }

  /**
   * @dataProvider dataProviderForR2
   */
  public function testCoefficientOfDetermination( array $points, $r2 ) {
    $this->assertEquals( $r2, Regression::coefficientOfDetermination($points), '', 0.001 );
  }

  /**
   * @dataProvider dataProviderForR2
   */
  public function testR2( array $points, $r2 ) {
    $this->assertEquals( $r2, Regression::r2($points), '', 0.001 );
  }

  public function dataProviderForR2() {
    return [
      [
        [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
        0.986049
      ],
      [
        [ [4,390], [9,580], [10,650], [14,730], [4,410], [7,530], [12,600], [22,790], [1,350], [3,400], [8,590], [11,640], [5,450], [6,520], [10,690], [11,690], [16,770], [13,700], [13,730], [10,640] ],
        0.87160896
      ],
    ];
  }

  /**
   * @dataProvider dataProviderForPowerLaw
   */
  public function testPowerLaw( array $points, $a, $b ) {
    $power = Regression::powerLaw($points);
    $this->assertEquals( $a, $power['a'], '', 0.0001 );
    $this->assertEquals( $b, $power['b'], '', 0.0001 );
  }

  public function dataProviderForPowerLaw() {
    return [
      [
        [ [ 83, 183 ], [ 71, 168 ], [ 64, 171 ], [ 69, 178 ], [ 69, 176 ], [ 64, 172 ], [ 68, 165 ], [ 59, 158 ], [ 81, 183 ], [ 91, 182 ], [ 57, 163 ], [ 65, 175 ], [ 58, 164 ], [ 62, 175 ] ],
        56.48338, 0.2641538,
      ],
    ];
  }

  /**
   * @dataProvider dataProviderForPowerLawEvaulate
   */
  public function testPowerLawEvaluate( $x, $a, $b, $y ) {
    $this->assertEquals( $y, Regression::powerLawEvaluate( $x, $a, $b ), '', 0.0001 );
  }

  public function dataProviderForPowerLawEvaulate() {
    return [
      [ 83, 56.48338, 0.2641538, 181.4898448 ],
      [ 71, 56.48338, 0.2641538, 174.1556182 ],
      [ 64, 56.48338, 0.2641538, 169.4454327 ],
      [ 57, 56.48338, 0.2641538, 164.3393562 ],
      [ 91, 56.48338, 0.2641538, 185.955396 ],

    ];
  }
}