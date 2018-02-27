<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\DiagonalMatrix;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;

class DiagonalMatrixTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     constructor builds the expected DiagonalMatrix
     * @dataProvider dataProviderMulti
     * @param        array $A
     * @param        array $R
     */
    public function testConstructor(array $A, array $R)
    {
        $D = new DiagonalMatrix($A);
        $R = new Matrix($R);

        $this->assertInstanceOf(DiagonalMatrix::class, $D);
        $this->assertInstanceOf(Matrix::class, $D);

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
     * @testCase     getMatrix returns the expected array
     * @dataProvider dataProviderMulti
     * @param        array $A
     * @param        array $R
     */
    public function testGetMatrix(array $A, array $R)
    {
        $D = new DiagonalMatrix($A);

        $this->assertEquals($R, $D->getMatrix());
    }

    /**
     * @testCase     isSquare returns true
     * @dataProvider dataProviderMulti
     * @param        array $A
     * @param        array $R
     */
    public function testIsSquare(array $A, array $R)
    {
        $D = new DiagonalMatrix($A);

        $this->assertTrue($D->isSquare());
    }

    /**
     * @testCase     isSymmetric returns true
     * @dataProvider dataProviderMulti
     * @param        array $A
     * @param        array $R
     */
    public function testIsSymmetric(array $A, array $R)
    {
        $D = new DiagonalMatrix($A);

        $this->assertTrue($D->isSymmetric());
    }

    /**
     * @testCase     isLowerTriangular returns true
     * @dataProvider dataProviderMulti
     * @param        array $A
     * @param        array $R
     */
    public function testIsLowerTriangular(array $A, array $R)
    {
        $D = new DiagonalMatrix($A);

        $this->assertTrue($D->isLowerTriangular());
    }

    /**
     * @testCase     isUpperTriangular returns true
     * @dataProvider dataProviderMulti
     * @param        array $A
     * @param        array $R
     */
    public function testIsUpperTriangular(array $A, array $R)
    {
        $D = new DiagonalMatrix($A);

        $this->assertTrue($D->isUpperTriangular());
    }

    /**
     * @testCase     isTriangular returns true
     * @dataProvider dataProviderMulti
     * @param        array $A
     * @param        array $R
     */
    public function testIsTriangular(array $A, array $R)
    {
        $D = new DiagonalMatrix($A);

        $this->assertTrue($D->isTriangular());
    }

    /**
     * @testCase     isDiagonal returns true
     * @dataProvider dataProviderMulti
     * @param        array $A
     * @param        array $R
     */
    public function testIsDiagonal(array $A, array $R)
    {
        $D = new DiagonalMatrix($A);

        $this->assertTrue($D->isDiagonal());
    }

    /**
     * @testCase     isSquareAndSymmetric returns true
     * @dataProvider dataProviderMulti
     * @param        array $A
     * @param        array $R
     */
    public function testIsSquareAndSymmetric(array $D)
    {
        $D = MatrixFactory::create($D);

        $reflection_method = new \ReflectionMethod(DiagonalMatrix::class, 'isSquareAndSymmetric');
        $reflection_method->setAccessible(true);

        $this->assertTrue($reflection_method->invoke($D));
    }

    public function dataProviderMulti(): array
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
