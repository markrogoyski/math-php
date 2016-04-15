<?php
namespace Math\Probability;
require_once( __DIR__ . '/../../src/Probability/Distribution.php' );

class DistributionTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider dataProviderForBinomial
   */
  public function testBinomial( int $n, int $r, float $p, float $binomial_distribution ) {
    $this->assertEquals( $binomial_distribution, Distribution::binomial( $n, $r, $p ), '', 0.001 );
  }

  /**
   * Data provider for binomial
   * Data: [ n, r, p, binomial distribution ]
   */
  public function dataProviderForBinomial() {
    return [
      [ 2, 1, 0.5, 0.5 ],
      [ 2, 1, 0.4, 0.48 ],
      [ 6, 2, 0.7, 0.0595350 ],
      [ 8, 7, 0.83, 0.3690503 ],
      [ 10, 5, 0.85, 0.0084909 ],
      [ 50, 48, 0.97, 0.2555182 ],
      [ 5, 4, 1, 0.0 ],
    ];
  }

  public function testBinomialProbabilityLowerBoundException() {
    $this->setExpectedException('\Exception');
    Distribution::binomial( 6, 2, -0.1 );
  }

  public function testBinomialProbabilityUpperBoundException() {
    $this->setExpectedException('\Exception');
    Distribution::binomial( 6, 2, 1.1 );
  }
}