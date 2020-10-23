<?php

namespace MathPHP\Tests\LinearAlgebra\Matrix;

use MathPHP\LinearAlgebra\RowVector;
use MathPHP\LinearAlgebra\Matrix;

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
        $V = new Matrix($V);

        // Then
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\RowVector::class, $R);
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\Matrix::class, $R);

        $this->assertEquals($V[0], $R[0]);

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
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\Matrix::class, $Rᵀ);

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
