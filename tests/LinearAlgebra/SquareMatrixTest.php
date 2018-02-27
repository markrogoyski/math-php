<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\SquareMatrix;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\Exception;

class SquareMatrixTest extends \PHPUnit\Framework\TestCase
{
    use \MathPHP\Tests\LinearAlgebra\MatrixDataProvider;

    /**
     * @testCase     Constructor constructs a proper SquareMatrix
     * @dataProvider dataProviderForSquareMatrix
     */
    public function testConstructor(array $A)
    {
        $S = new SquareMatrix($A);
        $M = new Matrix($A);

        $this->assertInstanceOf(SquareMatrix::class, $S);
        $this->assertInstanceOf(Matrix::class, $S);

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

        $this->expectException(Exception\MatrixException::class);
        $M = new SquareMatrix($A);
    }

    /**
     * @testCase     getMatrix returns the expected matrix.
     * @dataProvider dataProviderForSquareMatrix
     */
    public function testGetMatrix(array $A)
    {
        $S = new SquareMatrix($A);
        $M = new Matrix($A);

        $this->assertEquals($M->getMatrix(), $S->getMatrix());
    }

    /**
     * @testCase     isSquare always returns true for a SquareMatrix
     * @dataProvider dataProviderForSquareMatrix
     */
    public function testIsSquare(array $A)
    {
        $S = new SquareMatrix($A);
        $M = new Matrix($A);

        $this->assertTrue($S->isSquare());
    }
}
