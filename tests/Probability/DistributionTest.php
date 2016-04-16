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

  /**
   * @dataProvider dataProviderForPoisson
   */
  public function testPoisson( $k, $λ, $probability ) {
    $this->assertEquals( $probability, Distribution::poisson( $k, $λ ), '', 0.001 );
  }

  /**
   * Data provider for poisson
   * Data: [ k, λ, poisson distribution ]
   */
  public function dataProviderForPoisson() {
    return [
      [ 3, 2, 0.180 ],
      [ 3, 5, 0.140373895814280564513 ],
      [ 8, 6, 0.103257733530844 ],
      [ 2, 0.45, 0.065 ],
    ];
  }

  /**
   * @dataProvider dataProviderForCulmulativePoisson
   */
  public function testCulmulativePoisson( $k, $λ, $probability ) {
    $this->assertEquals( $probability, Distribution::culmulativePoisson( $k, $λ ), '', 0.001 );
  }

  /**
   * Data provider for culmulative poisson
   * Data: [ k, λ, culmulative poisson distribution ]
   */
  public function dataProviderForCulmulativePoisson() {
    return [
      [ 3, 2, 0.857123460498547048662 ],
      [ 3, 5, 0.2650 ],
      [ 8, 6, 0.8472374939845613089968 ],
      [ 2, 0.45, 0.99 ],
    ];
  }

  public function testPoissonExceptionWhenKLessThanZero() {
    $this->setExpectedException('\Exception');
    Distribution::poisson( -1, 2 );
  }

  public function testPoissonExceptionWhenλLessThanZero() {
    $this->setExpectedException('\Exception');
    Distribution::poisson( 2, -1 );
  }
}