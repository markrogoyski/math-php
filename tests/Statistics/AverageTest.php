<?php
namespace Math\Statistics;

class AverageTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider dataProviderForMean
   */
  public function testMean( array $numbers, $mean ) {
    $this->assertEquals( $mean, Average::mean($numbers), '', 0.01 );
  }

  /**
   * Data provider for mean test
   * Data: [ [ numbers ], mean ]
   */
  public function dataProviderForMean() {
    return [
      [ [ 1, 1, 1 ], 1 ],
      [ [ 1, 2, 3 ], 2 ],
      [ [ 2, 3, 4 ], 3 ],
      [ [ 5, 5, 6 ], 5.33 ],
      [ [ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], 15 ],
      [ [ 1, 2, 4, 7 ], 3.5 ],
      [ [ 8, 9, 10, 10, 10, 11, 11, 11, 12, 13 ], 10.5 ],
      [ [ 6, 7, 8, 10, 12, 14, 14, 15, 16, 20 ], 12.2 ],
      [ [ 9, 10, 11, 13, 15, 17, 17, 18, 19, 23 ], 15.2 ],
      [ [ 12, 14, 16, 20, 24, 28, 28, 30, 32, 40 ], 24.4 ],
    ];
  }

  public function testMeanNullWhenEmptyArray() {
    $this->assertNull( Average::mean( array() ) );
  }

  /**
   * @dataProvider dataProviderForMedian
   */
  public function testMedian( array $numbers, $median ) {
    $this->assertEquals( $median, Average::median($numbers), '', 0.01 );
  }

  /**
   * Data provider for median test
   * Data: [ [ numbers ], median ]
   */
  public function dataProviderForMedian() {
    return [
      [ [ 1, 1, 1 ], 1 ],
      [ [ 1, 2, 3 ], 2 ],
      [ [ 2, 3, 4 ], 3 ],
      [ [ 5, 5, 6 ], 5 ],
      [ [ 1, 2, 3, 4, 5 ], 3 ],
      [ [ 1, 2, 3, 4, 5, 6 ], 3.5 ],
      [ [ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], 14 ],
      [ [ 1, 2, 4, 7 ], 3 ],
      [ [ 8, 9, 10, 10, 10, 11, 11, 11, 12, 13 ], 10.5 ],
      [ [ 6, 7, 8, 10, 12, 14, 14, 15, 16, 20 ], 13 ],
      [ [ 9, 10, 11, 13, 15, 17, 17, 18, 19, 23 ], 16 ],
      [ [ 12, 14, 16, 20, 24, 28, 28, 30, 32, 40 ], 26 ],
    ];
  }

  public function testMedianNullWhenEmptyArray() {
    $this->assertNull( Average::median( array() ) );
  }

  /**
   * @dataProvider dataProviderForMode
   */
  public function testMode( array $numbers, $modes ) {
    $computed_modes = Average::mode($numbers);
    sort($modes);
    sort($computed_modes);
    $this->assertEquals( $modes, $computed_modes );
  }

  /**
   * Data provider for mode test
   * Data: [ [ numbers ], mode ]
   */
  public function dataProviderForMode() {
    return [
      [ [ 1, 1, 1 ], [1] ],
      [ [ 1, 1, 2 ], [1] ],
      [ [ 1, 2, 1 ], [1] ],
      [ [ 2, 1, 1 ], [1] ],
      [ [ 1, 2, 2 ], [2] ],
      [ [ 1, 1, 1, 1 ], [1] ],
      [ [ 1, 1, 1, 2 ], [1] ],
      [ [ 1, 1, 2, 1 ], [1] ],
      [ [ 1, 2, 1, 1 ], [1] ],
      [ [ 2, 1, 1, 1 ], [1] ],
      [ [ 1, 1, 2, 2 ], [ 1, 2 ] ],
      [ [ 1, 2, 2, 1 ], [ 1, 2 ] ],
      [ [ 2, 2, 1, 1 ], [ 1, 2 ] ],
      [ [ 2, 1, 2, 1 ], [ 1, 2 ] ],
      [ [ 2, 1, 1, 2 ], [ 1, 2 ] ],
      [ [ 1, 1, 2, 2, 3, 3 ], [ 1, 2, 3 ] ],
      [ [ 1, 2, 1, 2, 3, 3 ], [ 1, 2, 3 ] ],
      [ [ 1, 2, 3, 1, 2, 3 ], [ 1, 2, 3 ] ],
      [ [ 3, 1, 2, 3, 2, 1 ], [ 1, 2, 3 ] ],
      [ [ 3, 3, 2, 2, 1, 1 ], [ 1, 2, 3 ] ],
      [ [ 1, 1, 1, 2, 2, 3 ], [1] ],
      [ [ 1, 2, 2, 2, 2, 3 ], [2] ],
      [ [ 1, 2, 2, 3, 3, 4 ], [ 2, 3 ] ],
      [ [ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], [13] ],
      [ [ 1, 2, 4, 7 ], [ 1, 2, 4, 7 ] ],
      [ [ 8, 9, 10, 10, 10, 11, 11, 11, 12, 13 ], [ 10, 11 ] ],
      [ [ 6, 7, 8, 10, 12, 14, 14, 15, 16, 20 ], [14] ],
      [ [ 9, 10, 11, 13, 15, 17, 17, 18, 19, 23 ], [17] ],
      [ [ 12, 14, 16, 20, 24, 28, 28, 30, 32, 40 ], [28] ],
    ];
  }

  public function testModeEmtyArrayWhenEmptyArray() {
    $this->assertEmpty( Average::mode( array() ) );
  }

  public function testGetAverages() {
    $averages = Average::getAverages([ 13, 18, 13, 14, 13, 16, 14, 21, 13 ]);
    $this->assertTrue( is_array($averages) );
    $this->assertArrayHasKey( 'mean',      $averages );
    $this->assertArrayHasKey( 'median',    $averages );
    $this->assertArrayHasKey( 'mode',      $averages );
    $this->assertTrue( is_numeric( $averages['mean'] ) );
    $this->assertTrue( is_numeric( $averages['median'] ) );
    $this->assertTrue( is_array( $averages['mode'] ) );
  }
}