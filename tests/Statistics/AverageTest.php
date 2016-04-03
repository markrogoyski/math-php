<?php
namespace Math\Statistics;
require_once( __DIR__ . '/../../src/Statistics/Average.php' );

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
      [ [ 1, 1, 1 ],    1 ],
      [ [ 1, 2, 3 ],    2 ],
      [ [ 2, 3, 4 ],    3 ],
      [ [ 5, 5, 6 ], 5.33 ],
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
      [ [ 1, 1, 1 ],    1 ],
      [ [ 1, 2, 3 ],    2 ],
      [ [ 2, 3, 4 ],    3 ],
      [ [ 5, 5, 6 ], 5 ],
      [ [ 1, 2, 3, 4, 5 ], 3 ],
      [ [ 1, 2, 3, 4, 5, 6 ], 3.5 ],
    ];
  }

  public function testMedianNullWhenEmptyArray() {
    $this->assertNull( Average::median( array() ) );
  }

  /**
   * @dataProvider dataProviderForMode
   */
  public function testMode( array $numbers, $mode ) {
    $this->assertEquals( $mode, Average::mode($numbers), '', 0.01 );
  }

  /**
   * Data provider for mode test
   * Data: [ [ numbers ], mode ]
   */
  public function dataProviderForMode() {
    return [
      [ [ 1, 1, 1 ], 1 ],
      [ [ 1, 1, 2 ], 1 ],
      [ [ 1, 2, 1 ], 1 ],
      [ [ 2, 1, 1 ], 1 ],
      [ [ 1, 2, 2 ], 2 ],
      [ [ 1, 1, 1, 1 ], 1 ],
      [ [ 1, 1, 1, 2 ], 1 ],
      [ [ 1, 1, 2, 1 ], 1 ],
      [ [ 1, 2, 1, 1 ], 1 ],
      [ [ 2, 1, 1, 1 ], 1 ],
      [ [ 1, 1, 2, 2 ], 1.5 ],
      [ [ 1, 2, 2, 1 ], 1.5 ],
      [ [ 2, 2, 1, 1 ], 1.5 ],
      [ [ 2, 1, 2, 1 ], 1.5 ],
      [ [ 2, 1, 1, 2 ], 1.5 ],
      [ [ 1, 1, 2, 2, 3, 3 ], 2 ],
      [ [ 1, 2, 1, 2, 3, 3 ], 2 ],
      [ [ 1, 2, 3, 1, 2, 3 ], 2 ],
      [ [ 3, 1, 2, 3, 2, 1 ], 2 ],
      [ [ 3, 3, 2, 2, 1, 1 ], 2 ],
      [ [ 1, 1, 1, 2, 2, 3 ], 1 ],
      [ [ 1, 2, 2, 2, 2, 3 ], 2 ],
      [ [ 1, 2, 2, 3, 3, 4 ], 2.5 ],
    ];
  }

  public function testModeNullWhenEmptyArray() {
    $this->assertNull( Average::mode( array() ) );
  }
}