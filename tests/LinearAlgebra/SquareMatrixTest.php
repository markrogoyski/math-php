<?php
namespace Math\LinearAlgebra;

class SquareMatrixTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderMulti
     */
    public function testConstructor(array $A)
    {
        $S = new SquareMatrix($A);
        $M = new Matrix($A);

        $this->assertInstanceOf('Math\LinearAlgebra\SquareMatrix', $S);
        $this->assertInstanceOf('Math\LinearAlgebra\Matrix', $S);

        $m = $S->getM();
        for ($i = 0; $i < $m; $i++) {
            $this->assertEquals($M[$i], $S[$i]);
        }
        $m = $M->getM();
        for ($i = 0; $i < $m; $i++) {
            $this->assertEquals($M[$i], $S[$i]);
        }
    }

    /**
     * @dataProvider dataProviderMulti
     */
    public function testGetMatrix(array $A)
    {
        $S = new SquareMatrix($A);
        $M = new Matrix($A);

        $this->assertEquals($M->getMatrix(), $S->getMatrix());
    }

    /**
     * @dataProvider dataProviderMulti
     */
    public function testIsSquare(array $A)
    {
        $S = new SquareMatrix($A);
        $M = new Matrix($A);

        $this->assertTrue($S->isSquare());
    }

    public function dataProviderMulti()
    {
        return [
            [
                [
                    [1, 0, 5],
                    [4, 2, 0],
                    [3, 0, 3],
                ],
            ],
            [
                [
                    [1]
                ]
            ],
        ];
    }
}
