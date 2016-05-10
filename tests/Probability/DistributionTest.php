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
      [ 12, 4, 0.2, 0.1329 ]
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
   * @dataProvider dataProviderForCumulativeBinomial
   */
  public function testCumulativeBinomial( int $n, int $r, float $p, float $cumulative_binomial_distribution ) {
    $this->assertEquals( $cumulative_binomial_distribution, Distribution::cumulativeBinomial( $n, $r, $p ), '', 0.001 );
  }

  /**
   * Data provider for cumulative binomial
   * Data: [ n, r, p, cumulative binomial distribution ]
   */
  public function dataProviderForCumulativeBinomial() {
    return [
      [ 2, 1, 0.5, 0.75 ],
      [ 2, 1, 0.4, 0.84 ],
      [ 6, 2, 0.7, 0.07047 ],
      [ 8, 7, 0.83, 0.7747708 ],
      [ 10, 5, 0.85, 0.009874091 ],
      [ 50, 48, 0.97, 0.4447201 ],
      [ 5, 4, 1, 0.0 ],
      [ 12, 4, 0.2, 0.92744 ],
    ];
  }

  /**
   * @dataProvider dataProviderForNegativeBinomial
   */
  public function testNegativeBinomial( int $x, int $r, float $P, float $neagative_binomial_distribution ) {
    $this->assertEquals( $neagative_binomial_distribution, Distribution::negativeBinomial( $x, $r, $P ), '', 0.001 );
  }

  /**
   * Data provider for neagative binomial
   * Data: [ x, r, P, negative binomial distribution ]
   */
  public function dataProviderForNegativeBinomial() {
    return [
      [ 2, 1, 0.5, 0.25 ],
      [ 2, 1, 0.4, 0.24 ],
      [ 6, 2, 0.7, 0.019845 ],
      [ 8, 7, 0.83, 0.322919006776561 ],
      [ 10, 5, 0.85, 0.00424542789316406 ],
      [ 50, 48, 0.97, 0.245297473979909 ],
      [ 5, 4, 1, 0.0 ],
      [ 2, 2, 0.5, 0.25 ],

    ];
  }

  public function testNegativeBinomialProbabilityLowerBoundException() {
    $this->setExpectedException('\Exception');
    Distribution::negativeBinomial( 6, 2, -0.1 );
  }

  public function testNegativeBinomialProbabilityUpperBoundException() {
    $this->setExpectedException('\Exception');
    Distribution::negativeBinomial( 6, 2, 1.1 );
  }

  /**
   * @dataProvider dataProviderForPascal
   */
  public function testPascal( int $x, int $r, float $P, float $neagative_binomial_distribution ) {
    $this->assertEquals( $neagative_binomial_distribution, Distribution::pascal( $x, $r, $P ), '', 0.001 );
  }

  /**
   * Data provider for Pascal
   * Data: [ x, r, P, negative binomial distribution ]
   */
  public function dataProviderForPascal() {
    return [
      [ 2, 1, 0.5, 0.25 ],
      [ 2, 1, 0.4, 0.24 ],
      [ 6, 2, 0.7, 0.019845 ],
      [ 8, 7, 0.83, 0.322919006776561 ],
      [ 10, 5, 0.85, 0.00424542789316406 ],
      [ 50, 48, 0.97, 0.245297473979909 ],
      [ 5, 4, 1, 0.0 ],
      [ 2, 2, 0.5, 0.25 ],

    ];
  }

  public function testPascalProbabilityLowerBoundException() {
    $this->setExpectedException('\Exception');
    Distribution::pascal( 6, 2, -0.1 );
  }

  public function testPascalProbabilityUpperBoundException() {
    $this->setExpectedException('\Exception');
    Distribution::pascal( 6, 2, 1.1 );
  }

  /**
   * @dataProvider dataProviderForPoisson
   */
  public function testPoisson( int $k, float $λ, float $probability ) {
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
      [ 16, 12, 0.0542933401099791 ],
    ];
  }

  /**
   * @dataProvider dataProviderForCumulativePoisson
   */
  public function testCumulativePoisson( int $k, float $λ, float $probability ) {
    $this->assertEquals( $probability, Distribution::cumulativePoisson( $k, $λ ), '', 0.001 );
  }

  /**
   * Data provider for cumulative poisson
   * Data: [ k, λ, culmulative poisson distribution ]
   */
  public function dataProviderForCumulativePoisson() {
    return [
      [ 3, 2, 0.857123460498547048662 ],
      [ 3, 5, 0.2650 ],
      [ 8, 6, 0.8472374939845613089968 ],
      [ 2, 0.45, 0.99 ],
      [ 16, 12, 0.898708992560164 ],
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

  /**
   * @dataProvider dataProviderForContinuousUniform
   */
  public function testContinuousUniform( $a, $b, $x₁, $x₂, $probability ) {
    $this->assertEquals( $probability, Distribution::continuousUniform( $a, $b, $x₁, $x₂ ), '', 0.001 );
  }

  public function dataProviderForContinuousUniform() {
    return [
      [ 1, 4, 2, 3, 0.3333 ],
      [ 0.6, 12.2, 2.1, 3.4, 0.11206897 ],
      [ 1.6, 14, 4, 9, 0.40322581 ],
    ];
  }

  public function testContinuousUniformExceptionXOutOfBounds() {
    $this->setExpectedException('\Exception');
    Distribution::continuousUniform( 1, 2, 3, 4 );
  }

  /**
   * @dataProvider dataProviderForExponential
   */
  public function testExponential( $λ, $x, $probability ) {
    $this->assertEquals( $probability, Distribution::exponential( $λ, $x ), '', 0.001 );
  }

  public function dataProviderForExponential() {
    return [
      [ 1, 0, 1 ],
      [ 1, 1, 0.36787944117 ],
      [ 1, 2, 0.13533528323 ],
      [ 1, 3, 0.04978706836 ],
    ];
  }

  /**
   * @dataProvider dataProviderForCumulativeExponential
   */
  public function testCumulativeExponential( $λ, $x, $probability ) {
    $this->assertEquals( $probability, Distribution::cumulativeExponential( $λ, $x ), '', 0.001 );
  }

  public function dataProviderForCumulativeExponential() {
    return [
      [ 1, 0, 0 ],
      [ 1, 1, 0.6321206 ],
      [ 1, 2, 0.8646647 ],
      [ 1, 3, 0.9502129 ],
      [ 1/3, 2, 0.4865829 ],
      [ 1/3, 4, 0.7364029 ],
      [ 1/5, 4, 0.550671 ],
    ];
  }

  /**
   * @dataProvider dataProviderForCumulativeExponentialBewteenTwoNumbers
   */
  public function testCumulativeExponentialBetweenTwoNumbers( $λ, $x₁, $x₂, $probability ) {
    $this->assertEquals( $probability, Distribution::cumulativeExponentialBetweenTwoNumbers( $λ, $x₁, $x₂ ), '', 0.001 );
  }

  public function dataProviderForCumulativeExponentialBewteenTwoNumbers() {
    return [
      [ 1, 2, 3, 0.0855 ],
      [ 1, 3, 2, -0.0855 ],
      [ 0.5, 2, 4, 0.23254 ],
      [ 1/3, 2, 4, 0.24982 ],
      [ 0.125, 5.4, 5.6, 0.01257 ],
    ];
  }

  /**
   * @dataProvider dataProviderForNormal
   */
  public function testNormal( $x, $μ, $σ, $pdf ) {
    $this->assertEquals( $pdf, Distribution::normal( $x, $μ, $σ ), '', 0.001 );
  }

  public function dataProviderForNormal() {
    return [
      [ 84, 72, 15.2, 0.01921876 ],
      [ 26, 25, 2, 0.17603266338 ],
      [ 4, 0, 1, .000133830225 ],
    ];
  }
}