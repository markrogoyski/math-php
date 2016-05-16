<?php
namespace Math\Arithmetic;
require_once( __DIR__ . '/../../src/Arithmetic/Functions.php' );

class FunctionsTest extends \PHPUnit_Framework_TestCase {

  /**
   * @dataProvider dataProviderForSignum
   */
  public function testSignum( $x, $sign ) {
    $this->assertEquals( $sign, Functions::signum($x) );
  }

  /**
   * @dataProvider dataProviderForSignum
   */
  public function testSgn( $x, $sign ) {
    $this->assertEquals( $sign, Functions::sgn($x) );
  }

  public function dataProviderForSignum() {
    return [
      [ 0, 0 ],
      [ 1, 1 ], [ 0.5, 1 ], [ 1.5, 1 ], [ 4, 1 ], [ 123241.342, 1 ],
      [ -1, -1 ], [ -0.5, -1 ], [ -1.5, -1 ], [ -4, -1 ], [ -123241.342, -1 ],
    ];
  }
}