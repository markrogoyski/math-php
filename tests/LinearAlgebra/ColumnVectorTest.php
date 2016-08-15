<?php
namespace Math\LinearAlgebra;

class ColumnVectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForConstructor
     */
    public function testConstructor(array $M, array $V)
    {
        $C = new ColumnVector($M);
        $V = new Matrix($V);

        $this->assertInstanceOf('Math\LinearAlgebra\ColumnVector', $C);
        $this->assertInstanceOf('Math\LinearAlgebra\Matrix', $C);

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

        $this->assertInstanceOf('Math\LinearAlgebra\RowVector', $Cᵀ);
        $this->assertInstanceOf('Math\LinearAlgebra\Matrix', $Cᵀ);

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
