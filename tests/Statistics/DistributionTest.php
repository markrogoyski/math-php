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

  /**
   * @dataProvider dataProviderForRelativeFrequency
   */
  public function testRelativeFrequency( array $values, array $frequencies ) {
    $this->assertEquals( $frequencies, Distribution::relativeFrequency($values), '', 0.0001 );
  }

  public function dataProviderForRelativeFrequency() {
    return [
      [
        [ 'A', 'A', 'B', 'B', 'B', 'B', 'C', 'C', 'D', 'F' ],
        [ 'A' => 0.2, 'B' => 0.4, 'C' => 0.2, 'D' => 0.1, 'F' => 0.1 ],
      ],
      [
        [ 1, 1, 1, 1, 1, 2, 2, 2, 3, 3, 3, 3, 3, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
        [ 1 => 0.16129032, 2 => 0.09677419, 3 => 0.29032258, 4 => 0.45161290 ],
      ],
    ];
  }
}