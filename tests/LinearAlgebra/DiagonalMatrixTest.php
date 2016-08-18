<?php
namespace Math\LinearAlgebra;

class DiagonalMatrixTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderMulti
     */
    public function testConstructor(array $A, array $R)
    {
        $D = new DiagonalMatrix($A);
        $R = new Matrix($R);

        $this->assertInstanceOf('Math\LinearAlgebra\DiagonalMatrix', $D);
        $this->assertInstanceOf('Math\LinearAlgebra\Matrix', $D);

        $m = $D->getM();
        for ($i = 0; $i < $m; $i++) {
            $this->assertEquals($R[$i], $D[$i]);
        }
        $m = $R->getM();
        for ($i = 0; $i < $m; $i++) {
            $this->assertEquals($R[$i], $D[$i]);
        }
    }

    /**
     * @dataProvider dataProviderMulti
     */
    public function testGetMatrix(array $A, array $R)
    {
        $D = new DiagonalMatrix($A);

        $this->assertEquals($R, $D->getMatrix());
    }

    /**
     * @dataProvider dataProviderMulti
     */
    public function testIsSquare(array $A, array $R)
    {
        $D = new DiagonalMatrix($A);

        $this->assertTrue($D->isSquare());
    }

    /**
     * @dataProvider dataProviderMulti
     */
    public function testIsSymmetric(array $A, array $R)
    {
        $D = new DiagonalMatrix($A);

        $this->assertTrue($D->isSymmetric());
    }

    public function dataProviderMulti()
    {
        return [
            [
                [1, 2, 3],
                [
                    [1, 0, 0],
                    [0, 2, 0],
                    [0, 0, 3],
                ],
            ],
            [
                [1],
                [
                    [1]
                ]
            ],
        ];
    }
}
