<?php
namespace Math\Statistics;
require_once( __DIR__ . '/../../src/Statistics/Descriptive.php' );

class DescriptiveTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider dataProviderForVariance
   */
  public function testVariance( array $numbers, $variance ) {
    $this->assertEquals( $variance, Descriptive::variance($numbers), '', 0.01 );
  }

  /**
   * Data provider for variance test
   * Data: [ [ numbers ], mean ]
   */
  public function dataProviderForVariance() {
    return [
      [ [ -10, 0, 10, 20, 30 ], 200 ],
      [ [ 8, 9, 10, 11, 12 ], 2 ],
      [ [ 600, 470, 170, 430, 300], 21704 ],
      [ [ -5, 1, 8, 7, 2], 21.84 ],
      [ [ 3, 7, 34, 25, 46, 7754, 3, 6], 6546331.937 ],

    ];
  }

  public function testVarianceNullWhenEmptyArray() {
    $this->assertNull( Descriptive::variance( array() ) );
  }

  /**
   * @dataProvider dataProviderForStandardDeviation
   */
  public function testStandardDeviation( array $numbers, $standard_deviation ) {
    $this->assertEquals( $standard_deviation, Descriptive::standardDeviation($numbers), '', 0.01 );
  }

  /**
   * Data provider for standard deviation test
   * Data: [ [ numbers ], mean ]
   */
  public function dataProviderForStandardDeviation() {
    return [
      [ [ -10, 0, 10, 20, 30 ], 10 * sqrt(2) ],
      [ [ 8, 9, 10, 11, 12 ], sqrt(2) ],
      [ [ 600, 470, 170, 430, 300], 147.32 ],
      [ [ -5, 1, 8, 7, 2], 4.67 ],
      [ [ 3, 7, 34, 25, 46, 7754, 3, 6], 2558.580063 ],

    ];
  }

  public function testStandardDeviationNullWhenEmptyArray() {
    $this->assertNull( Descriptive::standardDeviation( array() ) );
  }
}