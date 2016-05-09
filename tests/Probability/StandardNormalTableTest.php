<?php
namespace Math\Probability;
require_once( __DIR__ . '/../../src/Probability/StandardNormalTable.php' );

class StandardNormalTableTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider dataProviderForGetCumulativeFromMean
   */
  public function testGetCumulativeFromMean( $Z, $Φ ) {
    $this->assertEquals( $Φ, StandardNormalTable::getCumulativeFromMean($Z) );
  }

  public function dataProviderForGetCumulativeFromMean() {
    return [
      [ 0, 0.00000 ], [ 0.01, 0.00399 ], [ 0.02, 0.00798 ],
      [ 0.30, 0.11791 ], [ 0.31, 0.12172 ], [ 0.39, 0.15173 ],
      [ 2.90, 0.49813 ], [ 2.96, 0.49846 ], [ 3.09, 0.49900 ],
    ];
  }

  /**
   * @dataProvider dataProviderForGetCumulative
   */
  public function testGetCumulative( $Z, $Φ ) {
    $this->assertEquals( $Φ, StandardNormalTable::getCumulative($Z) );
  }

  public function dataProviderForGetCumulative() {
    return [
      [ 0, 0.50000 ], [ 0.01, 0.50399 ], [ 0.02, 0.50798 ],
      [ 0.30, 0.61791 ], [ 0.31, 0.62172 ], [ 0.39, 0.65173 ],
      [ 2.90, 0.99813 ], [ 2.96, 0.99846 ], [ 3.09, 0.99900 ],
    ];
  }

  /**
   * @dataProvider dataProviderForGetComplementaryCumulative
   */
  public function testGetComplementaryCumulative( $Z, $Φ ) {
    $this->assertEquals( $Φ, StandardNormalTable::getComplementaryCumulative($Z) );
  }

  public function dataProviderForGetComplementaryCumulative() {
    return [
      [ 0, 0.50000 ], [ 0.01, 0.49601 ], [ 0.02, 0.49202 ],
      [ 0.30, 0.38209 ], [ 0.31, 0.37828 ], [ 0.39, 0.34827 ],
      [ 2.90, 0.00187 ], [ 2.96, 0.00154 ], [ 3.09, 0.00100 ],
    ];
  }
}