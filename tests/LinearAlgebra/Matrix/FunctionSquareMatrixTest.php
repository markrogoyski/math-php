<?php

namespace MathPHP\Tests\LinearAlgebra\Matrix;

use MathPHP\LinearAlgebra\FunctionSquareMatrix;
use MathPHP\Exception;

class FunctionSquareMatrixTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test   evaluate
     * @throws \Exception
     */
    public function testEvaluate()
    {
        // Given
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

        // When
        $ME = $M->evaluate(['x' => 1, 'y' => 2]);

        // Then
        $this->assertEqualsWithDelta(3, $ME[0][0], 0.000001);
        $this->assertEqualsWithDelta(-1, $ME[0][1], 0.000001);
        $this->assertEqualsWithDelta(2, $ME[1][0], 0.000001);
        $this->assertEqualsWithDelta(1 / 2, $ME[1][1], 0.000001);
    }

    /**
     * @test   constructor exception
     * @throws \Exception
     */
    public function testConstructorException()
    {
        // Given
        $A = [
            [1, 2, 3],
            [2, 3, 4],
        ];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        $A = new FunctionSquareMatrix($A);
    }
}
