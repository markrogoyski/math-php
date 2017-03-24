<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

class SquareMatrixTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @testCase     Constructor constructs a proper SquareMatrix
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
     * @testCase SquareMatrix throws a MatrixException if the input is not a square array.
     */
    public function testConstructorExceptionNotSquareMatrix()
    {
        $A = [
            [1, 2, 3],
            [2, 3, 4],
        ];

        $this->setExpectedException(Exception\MatrixException::class);
        $M = new SquareMatrix($A);
    }

    /**
     * @testCase     getMatrix returns the expected matrix.
     * @dataProvider dataProviderMulti
     */
    public function testGetMatrix(array $A)
    {
        $S = new SquareMatrix($A);
        $M = new Matrix($A);

        $this->assertEquals($M->getMatrix(), $S->getMatrix());
    }

    /**
     * @testCase     isSquare always returns true for a SquareMatrix
     * @dataProvider dataProviderMulti
     */
    public function testIsSquare(array $A)
    {
        $S = new SquareMatrix($A);
        $M = new Matrix($A);

        $this->assertTrue($S->isSquare());
    }

    public function dataProviderMulti(): array
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
