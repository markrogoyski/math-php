<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\ColumnVector;
use MathPHP\LinearAlgebra\Matrix;

class ColumnVectorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForConstructor
     */
    public function testConstructor(array $M, array $V)
    {
        $C = new ColumnVector($M);
        $V = new Matrix($V);

        $this->assertInstanceOf(ColumnVector::class, $C);
        $this->assertInstanceOf(Matrix::class, $C);

        foreach ($M as $row => $value) {
            $this->assertEquals($value, $V[$row][0]);
        }
        $this->assertEquals(count($M), $V->getM());
        $this->assertEquals(1, $V->getN());
    }

    public function dataProviderForConstructor()
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
     * @dataProvider dataProviderForTranspose
     */
    public function testTranspose(array $M)
    {
        $C  = new ColumnVector($M);
        $Cᵀ = $C->transpose();

        $this->assertInstanceOf(\MathPHP\LinearAlgebra\RowVector::class, $Cᵀ);
        $this->assertInstanceOf(Matrix::class, $Cᵀ);

        $this->assertEquals(1, $Cᵀ->getM());
        $this->assertEquals(count($M), $Cᵀ->getN());

        $this->assertEquals($M, $Cᵀ->getRow(0));
    }

    public function dataProviderForTranspose()
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
