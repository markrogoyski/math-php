<?php
namespace Math\Probability;
require_once( __DIR__ . '/../../src/Probability/Combinatorics.php' );

class CombinatoricsTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider dataProviderForFactorialPermutations
   */
  public function testFactorial( $n, $factorial ) {
    $this->assertEquals( $factorial, Combinatorics::factorial($n) );
  }

  /**
   * @dataProvider dataProviderForFactorialPermutations
   */
  public function testPermutations( $n, $permutations ) {
    $this->assertEquals( $permutations, Combinatorics::permutations($n) );

  }

  /**
   * Data provider for factorial and permutations tests
   * Data: [ n, permutations ]
   */
  public function dataProviderForFactorialPermutations() {
    return [
      [ 1,  1 ],
      [ 2,  2 ],
      [ 3,  6 ],
      [ 4,  24 ],
      [ 5,  120 ],
      [ 6,  720 ],
      [ 7,  5040 ],
      [ 8,  40320 ],
      [ 9,  362880 ],
      [ 10, 3628800 ],
      [ 11, 39916800 ],
      [ 12, 479001600 ],
      [ 13, 6227020800 ],
      [ 14, 87178291200 ],
      [ 15, 1307674368000 ],
      [ 16, 20922789888000 ],
      [ 17, 355687428096000 ],
      [ 18, 6402373705728000 ],
      [ 19, 121645100408832000 ],
      [ 20, 2432902008176640000 ],
    ];
  }

  public function testPermutationsChooseR() {
    $this->assertEquals( 3360, Combinatorics::permutationsChooseR( 16, 3 ) );
  }

  /**
   * @dataProvider dataProviderForcombinations
   */
  public function testCombinations( $n, $r, $combinations ) {
    $this->assertEquals( $combinations, Combinatorics::combinations( $n, $r ) );
  }

  /**
   * Data provider for combinations tests
   * Data: [ n, r, combinations ]
   */
  public function dataProviderForCombinations() {
    return [
      [ 10,  1,   10 ],
      [ 10,  2,   45 ],
      [ 10,  3,  120 ],
      [ 10,  4,  210 ],
      [ 10,  5,  252 ],
      [ 10,  6,  210 ],
      [ 10,  7,  120 ],
      [ 10,  8,   45 ],
      [ 10,  9,   10 ],
      [ 10, 10,    1 ],
      [  5,  3,   10 ],
      [  6,  4,   15 ],
      [ 16,  3,  560 ],
      [ 20,  3, 1140 ],
    ];
  }

  /**
   * @dataProvider dataProviderForCombinationsWithRepetition
   */
  public function testCombinationsWithRepetition( $n, $r, $combinations ) {
    $this->assertEquals( $combinations, Combinatorics::combinationsWithRepetition( $n, $r ) );
  }

  /**
   * Data provider for combinations with repetition tests
   * Data: [ n, r, combinations ]
   */
  public function dataProviderForCombinationsWithRepetition() {
    return [
      [ 10,  1,    10 ],
      [ 10,  2,    55 ],
      [ 10,  3,   220 ],
      [ 10,  4,   715 ],
      [ 10,  5,  2002 ],
      [ 10,  6,  5005 ],
      [ 10,  7, 11440 ],
      [ 10,  8, 24310 ],
      [ 10,  9, 48620 ],
      [ 10, 10, 92378 ],
      [ 5,   3,    35 ],
      [ 6,   4,   126 ],
      [ 16,  3,   816 ],
      [ 20,  3,  1540 ],
    ];
  }

  /**
   * @dataProvider dataProviderForMultinomialTheorem
   */
  public function testMultinomialTheorem( $n, array $groups, $divisions ) {
    $this->assertEquals( $divisions, Combinatorics::multinomialTheorem( $n, $groups ) );
  }

  /**
   * Data provider for multinomial theorem tests
   * Data: [ n, groups, divisions ]
   */
  public function dataProviderForMultinomialTheorem() {
    return [
      [ 10, [ 5, 2, 3 ], 2520 ],
      [ 10, [ 5, 5 ],     252 ],
    ];
  }
}