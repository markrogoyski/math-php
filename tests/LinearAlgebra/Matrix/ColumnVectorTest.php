<?php

namespace MathPHP\Tests\LinearAlgebra\Matrix;

use MathPHP\Exception;
use MathPHP\LinearAlgebra\ColumnVector;
use MathPHP\LinearAlgebra\NumericMatrix;

class ColumnVectorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         constructor
     * @dataProvider dataProviderForConstructor
     */
    public function testConstructor(array $M, array $V)
    {
        // Given
        $C = new ColumnVector($M);
        $V = new NumericMatrix($V);

        // Then
        $this->assertInstanceOf(ColumnVector::class, $C);
        $this->assertInstanceOf(NumericMatrix::class, $C);

        // And
        foreach ($M as $row => $value) {
            $this->assertEquals($value, $V[$row][0]);
        }
        $this->assertEquals(count($M), $V->getM());
        $this->assertEquals(1, $V->getN());
    }

    public function dataProviderForConstructor(): array
    {
        return [
            [
                [1, 2, 3, 4],
                [
                    [1],
                    [2],
                    [3],
                    [4],
                ]
            ],
            [
                [1],
                [
                    [1],
                ]
            ],
        ];
    }

    /**
     * @test Construction failure due to not being a row vector
     */
    public function testConstructionFailure()
    {
        // Given
        $A = [
            [1, 2, 3],
            [2, 3, 4],
        ];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        $R = new ColumnVector($A);
    }

    /**
     * @test         transpose
     * @dataProvider dataProviderForTranspose
     */
    public function testTranspose(array $M)
    {
        // Given
        $C  = new ColumnVector($M);

        // When
        $Cᵀ = $C->transpose();

        // Then
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\RowVector::class, $Cᵀ);
        $this->assertInstanceOf(NumericMatrix::class, $Cᵀ);

        $this->assertEquals(1, $Cᵀ->getM());
        $this->assertEquals(count($M), $Cᵀ->getN());

        $this->assertEquals($M, $Cᵀ->getRow(0));
    }

    public function dataProviderForTranspose(): array
    {
        return [
            [
                [1, 2, 3, 4],

            ],
            [
                [1],
            ],
        ];
    }
}
