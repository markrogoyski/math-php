<?php
namespace Math\Statistics;
require_once( __DIR__ . '/../../src/Statistics/Distribution.php' );

class DistributionTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider dataProviderForFrequency
   */
  public function testFrequency( array $values, array $frequencies ) {
    $this->assertEquals( $frequencies, Distribution::frequency($values) );
  }

  public function dataProviderForFrequency() {
    return [
      [
        [ 'A', 'A', 'B', 'B', 'B', 'B', 'C', 'C', 'D', 'F' ],
        [ 'A' => 2, 'B' => 4, 'C' => 2, 'D' => 1, 'F' => 1 ],
      ],
      [
        [ 'yes', 'yes', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'no' ],
        [ 'yes' => 6, 'no' => 4 ],
      ],
      [
        [ 'agree', 'disagree', 'agree', 'agree', 'no opinion', 'agree', 'disagree' ],
        [ 'agree' => 4, 'disagree' => 2, 'no opinion' => 1 ],
      ],

    ];
  }
}