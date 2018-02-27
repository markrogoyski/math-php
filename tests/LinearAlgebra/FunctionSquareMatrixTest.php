<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\FunctionSquareMatrix;
use MathPHP\Exception;

class FunctionSquareMatrixTest extends \PHPUnit\Framework\TestCase
{
    public function testEvaluate()
    {
        $A = [
            [
                function ($params) {
                    $x = $params['x'];
                    $y = $params['y'];
                    return $x + $y;
                },
                function ($params) {
                    $x = $params['x'];
                    $y = $params['y'];
                    return $x - $y;
                }
            ],
            [
                function ($params) {
                    $x = $params['x'];
                    $y = $params['y'];
                    return $x * $y;
                },
                function ($params) {
                    $x = $params['x'];
                    $y = $params['y'];
                    return $x / $y;
                }
            ],
        ];
        
        $M  = new FunctionSquareMatrix($A);
        $ME = $M->evaluate(['x' => 1, 'y' => 2]);

        $this->assertEquals(3, $ME[0][0], '', 0.000001);
        $this->assertEquals(-1, $ME[0][1], '', 0.000001);
        $this->assertEquals(2, $ME[1][0], '', 0.000001);
        $this->assertEquals(1/2, $ME[1][1], '', 0.000001);
    }

    public function testConstructorException()
    {
        $A = [
            [1, 2, 3],
            [2, 3, 4],
        ];

        $this->expectException(Exception\BadDataException::class);
        $A = new FunctionSquareMatrix($A);
    }
}
