<?php

namespace MathPHP\Tests\LinearAlgebra\Matrix;

use MathPHP\Exception;
use MathPHP\LinearAlgebra\RowVector;
use MathPHP\LinearAlgebra\NumericMatrix;

class RowVectorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         construction
     * @dataProvider dataProviderForConstructor
     * @param        array $M
     * @param        array $V
     * @throws       \Exception
     */
    public function testConstructor(array $M, array $V)
    {
        // Given
        $R = new RowVector($M);

        // When
        $V = new NumericMatrix($V);

        // Then
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\RowVector::class, $R);
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\NumericMatrix::class, $R);

        // And
        $this->assertEquals($V[0], $R[0]);

        // And
        $this->assertEquals(1, $V->getM());
        $this->assertEquals(count($M), $V->getN());
    }

    public function dataProviderForConstructor(): array
    {
        return [
            [
                [1, 2, 3, 4],
                [ [1, 2, 3, 4] ],
            ],
            [
                [1],
                [ [1] ],
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
        $R = new RowVector($A);
    }

    /**
     * @test         transpose
     * @dataProvider dataProviderForTranspose
     * @param        array $M
     * @throws       \Exception
     */
    public function testTranspose(array $M)
    {
        // Given
        $R = new RowVector($M);

        // When
        $Rᵀ = $R->transpose();

        // Then
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\ColumnVector::class, $Rᵀ);
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\NumericMatrix::class, $Rᵀ);

        $this->assertEquals(count($M), $Rᵀ->getM());
        $this->assertEquals(1, $Rᵀ->getN());

        foreach ($M as $row => $value) {
            $this->assertEquals($value, $Rᵀ[$row][0]);
        }
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
