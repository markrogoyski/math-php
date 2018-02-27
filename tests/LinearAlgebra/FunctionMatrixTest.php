<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\FunctionMatrix;
use MathPHP\Exception;

class FunctionMatrixTest extends \PHPUnit\Framework\TestCase
{
    public function testEvaluate()
    {
        $A = [
            [
                function ($params) {
                    $x = $params['x'];
                    $y = $params['y'];
                    return $x ** 2 * $y;
                }
            ],
            [
                function ($params) {
                    $x = $params['x'];
                    $y = $params['y'];
                    return 5 * $x + sin($y);
                }
            ],
        ];
        
        $M  = new FunctionMatrix($A);
        $ME = $M->evaluate(['x' => 1, 'y' => 2]);

        $this->assertEquals(2, $ME->get(0, 0), '', 0.000001);
        $this->assertEquals(5.90929742683, $ME->get(1, 0), '', 0.000001);
    }
}
