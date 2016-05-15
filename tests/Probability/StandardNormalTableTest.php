<?php
namespace Math\Probability;
require_once( __DIR__ . '/../../src/Probability/StandardNormalTable.php' );

class StandardNormalTableTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider dataProviderForZScores
   */
  public function testGetZScore( $Z, $Φ ) {
    $this->assertEquals( $Φ, StandardNormalTable::getZScore($Z), '', 0.0001 );
  }

  public function dataProviderForZScores() {
    return [
      [ 0, 0.5000 ], [ 0.01, 0.5040 ], [ 0.02, 0.5080 ],
      [ 0.30, 0.6179 ], [ 0.31, 0.6217 ], [ 0.39, 0.6517 ],
      [ 2.90, 0.9981 ], [ 2.96, 0.9985 ], [ 3.09, 0.9990 ],
      [ -0, 0.5000 ], [ -0.01, 0.4960 ], [ -0.02, 0.4920 ],
      [ -0.30, 0.3821 ], [ -0.31, 0.3783 ], [ -0.39, 0.3483 ],
      [ -2.90, 0.0019 ], [ -2.96, 0.0015 ], [ -3.09, 0.0010 ],
    ];
  }
}