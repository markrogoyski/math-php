<?php

namespace MathPHP\Tests\LinearAlgebra\Matrix;

use MathPHP\LinearAlgebra\ColumnVector;
use MathPHP\LinearAlgebra\Matrix;

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
        $V = new Matrix($V);

        // Then
        $this->assertInstanceOf(ColumnVector::class, $C);
        $this->assertInstanceOf(Matrix::class, $C);

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
        $this->assertInstanceOf(Matrix::class, $Cᵀ);

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
