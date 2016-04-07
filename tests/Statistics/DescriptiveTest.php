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
   * @dataProvider dataProviderForPopulationVariance
   */
  public function testPopulationVariance( array $numbers, $variance ) {
    $this->assertEquals( $variance, Descriptive::populationVariance($numbers), '', 0.01 );
  }

  /**
   * Data provider for population variance test
   * Data: [ [ numbers ], variance ]
   */
  public function dataProviderForPopulationVariance() {
    return [
      [ [ -10, 0, 10, 20, 30 ], 200 ],
      [ [ 8, 9, 10, 11, 12 ], 2 ],
      [ [ 600, 470, 170, 430, 300 ], 21704 ],
      [ [ -5, 1, 8, 7, 2], 21.84 ],
      [ [ 3, 7, 34, 25, 46, 7754, 3, 6 ], 6546331.937 ],
      [ [ 4, 6, 2, 2, 2, 2, 3, 4, 1, 3 ], 1.89 ],
      [ [ -3432, 5, 23, 9948, -74 ], 20475035.6 ],
    ];
  }

  public function testPopulationVarianceNullWhenEmptyArray() {
    $this->assertNull( Descriptive::populationVariance( array() ) );
  }

  /**
   * @dataProvider dataProviderForSampleVariance
   */
  public function testSampleVariance( array $numbers, $variance ) {
    $this->assertEquals( $variance, Descriptive::sampleVariance($numbers), '', 0.01 );
  }

  /**
   * Data provider for sample variance test
   * Data: [ [ numbers ], variance ]
   */
  public function dataProviderForSampleVariance() {
    return [
      [ [ -10, 0, 10, 20, 30 ], 250 ],
      [ [ 8, 9, 10, 11, 12 ], 2.5 ],
      [ [ 600, 470, 170, 430, 300 ], 27130 ],
      [ [ -5, 1, 8, 7, 2 ], 27.3 ],
      [ [ 3, 7, 34, 25, 46, 7754, 3, 6 ], 7481522.21429 ],
      [ [ 4, 6, 2, 2, 2, 2, 3, 4, 1, 3 ], 2.1 ],
      [ [ -3432, 5, 23, 9948, -74 ], 25593794.5 ],
      [ [ 3, 21, 98, 203, 17, 9 ],  6219.9 ],
      [ [ 170, 300, 430, 470, 600 ], 27130 ],
      [ [ 1550, 1700, 900, 850, 1000, 950 ], 135416.66668 ],
      [ [ 1245, 1255, 1654, 1547, 1787, 1989, 1878, 2011, 2145, 2545, 2656 ], 210804.29090909063 ],
    ];
  }

  public function testSampleVarianceNullWhenEmptyArray() {
    $this->assertNull( Descriptive::sampleVariance( array() ) );
  }

  public function testSampleVarianceZeroWhenListContainsOnlyOneItem() {
    $this->assertEquals( 0, Descriptive::sampleVariance([5]) );
  }

  public function testVariancePopulationAndSample() {
    $numbers = [ -10, 0, 10, 20, 30 ];
    $this->assertEquals( 200, Descriptive::variance( $numbers, true ) );
    $this->assertEquals( 250, Descriptive::variance( $numbers, false ) );
  }

  public function testVarianceDefaultsToPopulationVariance() {
    $numbers = [ -10, 0, 10, 20, 30 ];
    $this->assertEquals( 200, Descriptive::variance( $numbers ) );
  }

  /**
   * @dataProvider dataProviderForStandardDeviationUsingPopulationVariance
   */
  public function testStandardDeviationUsingPopulationVariance( array $numbers, $standard_deviation ) {
    $this->assertEquals( $standard_deviation, Descriptive::standardDeviation($numbers), '', 0.01 );
  }

  /**
   * Data provider for standard deviation test
   * Data: [ [ numbers ], mean ]
   */
  public function dataProviderForStandardDeviationUsingPopulationVariance() {
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

  /**
   * @dataProvider dataProviderForStandardDeviationUsingSampleVariance
   */
  public function testStandardDeviationUsingSampleVariance( array $numbers, $standard_deviation ) {
    $this->assertEquals( $standard_deviation, Descriptive::standardDeviation( $numbers, false ), '', 0.01 );
  }

  /**
   * Data provider for standard deviation using sample variance test
   * Data: [ [ numbers ], mean ]
   */
  public function dataProviderForStandardDeviationUsingSampleVariance() {
    return [
      [ [ 3, 21, 98, 203, 17, 9 ],  78.86634 ],
      [ [ 170, 300, 430, 470, 600 ], 164.7118696390761 ],
      [ [ 1550, 1700, 900, 850, 1000, 950 ], 367.99 ],
      [ [ 1245, 1255, 1654, 1547, 1787, 1989, 1878, 2011, 2145, 2545, 2656 ], 459.13 ],
    ];
  }

  public function testStandardDeviationNullWhenEmptyArray() {
    $this->assertNull( Descriptive::standardDeviation( array() ) );
  }

  public function testGetStatsPopulation() {
    $stats = Descriptive::getStats( [ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], true );
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

  public function testGetStatsSample() {
    $stats = Descriptive::getStats( [ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], false );
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