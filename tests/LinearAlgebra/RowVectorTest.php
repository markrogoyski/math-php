<?php
namespace Math\LinearAlgebra;

class RowVectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForConstructor
     */
    public function testConstructor(array $M, array $V)
    {
        $R = new RowVector($M);
        $V = new Matrix($V);

        $this->assertInstanceOf('Math\LinearAlgebra\RowVector', $R);
        $this->assertInstanceOf('Math\LinearAlgebra\Matrix', $R);

        $this->assertEquals($V[0], $R[0]);

        $this->assertEquals(1, $V->getM());
        $this->assertEquals(count($M), $V->getN());
    }

    public function dataProviderForConstructor()
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
     * @dataProvider dataProviderForTranspose
     */
    public function testTranspose(array $M)
    {
        $R  = new RowVector($M);
        $Rᵀ = $R->transpose();

        $this->assertInstanceOf('Math\LinearAlgebra\ColumnVector', $Rᵀ);
        $this->assertInstanceOf('Math\LinearAlgebra\Matrix', $Rᵀ);

        $this->assertEquals(count($M), $Rᵀ->getM());
        $this->assertEquals(1, $Rᵀ->getN());

        foreach ($M as $row => $value) {
            $this->assertEquals($value, $Rᵀ[$row][0]);
        }
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
