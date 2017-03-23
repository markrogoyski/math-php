<?php
namespace MathPHP\LinearAlgebra;

class SquareMatrixTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderMulti
     */
    public function testConstructor(array $A)
    {
        $S = new SquareMatrix($A);
        $M = new Matrix($A);

        $this->assertInstanceOf('MathPHP\LinearAlgebra\SquareMatrix', $S);
        $this->assertInstanceOf('MathPHP\LinearAlgebra\Matrix', $S);

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
    
    /**
     * @dataProvider dataProviderKroneckerSum
     */
    public function testKroneckerSum($A, $B, $expected)
    {
        $A        = new SquareMatrix($A);
        $B        = new SquareMatrix($B);
        $A⊕B      = $A->kroneckerSum($B);
        $expected = new SquareMatrix($expected);
        $this->assertEquals($expected->getMatrix(), $A⊕B->getMatrix());
    }
    
    public function dataProviderKroneckerSum()
    {
        return [
            [
                [
                    [1, 2],
                    [3, 4],
                ],
                [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ],
                [
                    [2, 2, 3, 2, 0, 0],
                    [4, 6, 6, 0, 2, 0],
                    [7, 8,10, 0, 0, 2],
                    [3, 0, 0, 5, 2, 3],
                    [0, 3, 0, 4, 9, 6],
                    [0, 0, 3, 7, 8,13],
                ],
            ],
        ];
    }
}
