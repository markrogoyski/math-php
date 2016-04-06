<?php
namespace Math\Statistics;
require_once( __DIR__ . '/../../src/Statistics/Descriptive.php' );

class DescriptiveTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider dataProviderForRange
   */
  public function testRange( array $numbers, $range ) {
    $this->assertEquals( $range, Descriptive::range($numbers), '', 0.01 );
  }

  /**
   * Data provider for range test
   * Data: [ [ numbers ], range ]
   */
  public function dataProviderForRange() {
    return [
      [ [ 1, 1, 1 ], 0 ],
      [ [ 1, 1, 2 ], 1 ],
      [ [ 1, 2, 1 ], 1 ],
      [ [ 8, 4, 3 ], 5 ],
      [ [ 9, 7, 8 ], 2 ],
      [ [ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], 8 ],
      [ [ 1, 2, 4, 7 ], 6 ],
      [ [ 8, 9, 10, 10, 10, 11, 11, 11, 12, 13 ], 5 ],
      [ [ 6, 7, 8, 10, 12, 14, 14, 15, 16, 20 ], 14 ],
      [ [ 9, 10, 11, 13, 15, 17, 17, 18, 19, 23 ], 14 ],
      [ [ 12, 14, 16, 20, 24, 28, 28, 30, 32, 40 ], 28 ],
    ];
  }

  public function testRangeNullWhenEmptyArray() {
    $this->assertNull( Descriptive::range( array() ) );
  }

  /**
   * @dataProvider dataProviderForMidrange
   */
  public function testMidrange( array $numbers, $midrange ) {
    $this->assertEquals( $midrange, Descriptive::midrange($numbers), '', 0.01 );
  }

  /**
   * Data provider for midrange test
   * Data: [ [ numbers ], range ]
   */
  public function dataProviderForMidrange() {
    return [
      [ [ 1, 1, 1 ], 1 ],
      [ [ 1, 1, 2 ], 1.5 ],
      [ [ 1, 2, 1 ], 1.5 ],
      [ [ 8, 4, 3 ], 5.5 ],
      [ [ 9, 7, 8 ], 8 ],
      [ [ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], 17 ],
      [ [ 1, 2, 4, 7 ], 4 ],
      [ [ 8, 9, 10, 10, 10, 11, 11, 11, 12, 13 ], 10.5 ],
      [ [ 6, 7, 8, 10, 12, 14, 14, 15, 16, 20 ], 13 ],
      [ [ 9, 10, 11, 13, 15, 17, 17, 18, 19, 23 ], 16 ],
      [ [ 12, 14, 16, 20, 24, 28, 28, 30, 32, 40 ], 26 ],
    ];
  }

  public function testMidrangeNullWhenEmptyArray() {
    $this->assertNull( Descriptive::midrange( array() ) );
  }

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
      [ [ 4, 6, 2, 2, 2, 2, 3, 4, 1, 3 ], 1.89 ],
      [ [ -3432, 5, 23, 9948, -74 ], 20475035.6 ],
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
      [ [ 3, 7, 34, 25, 46, 7754, 3, 6 ], 2558.580063 ],
      [ [ 4, 6, 2, 2, 2, 2, 3, 4, 1, 3 ], 1.374772708 ],
      [ [ -3432, 5, 23, 9948, -74 ], 4524.934872 ],
    ];
  }

  public function testStandardDeviationNullWhenEmptyArray() {
    $this->assertNull( Descriptive::standardDeviation( array() ) );
  }

  public function testGetstats() {
    $stats = Descriptive::getStats([ 13, 18, 13, 14, 13, 16, 14, 21, 13 ]);
    $this->assertTrue( is_array($stats) );
    $this->assertArrayHasKey( 'mean',               $stats );
    $this->assertArrayHasKey( 'median',             $stats );
    $this->assertArrayHasKey( 'mode',               $stats );
    $this->assertArrayHasKey( 'range',              $stats );
    $this->assertArrayHasKey( 'midrange',           $stats );
    $this->assertArrayHasKey( 'variance',           $stats );
    $this->assertArrayHasKey( 'standard_deviation', $stats );
    $this->assertTrue( is_numeric( $stats['mean'] ) );
    $this->assertTrue( is_numeric( $stats['median'] ) );
    $this->assertTrue( is_array( $stats['mode'] ) );
    $this->assertTrue( is_numeric( $stats['range'] ) );
    $this->assertTrue( is_numeric( $stats['midrange'] ) );
    $this->assertTrue( is_numeric( $stats['variance'] ) );
    $this->assertTrue( is_numeric( $stats['standard_deviation'] ) );
  }
}