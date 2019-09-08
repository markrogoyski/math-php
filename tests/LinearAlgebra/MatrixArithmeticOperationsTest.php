<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\SquareMatrix;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Exception;

class MatrixArithmeticOperationsTest extends \PHPUnit\Framework\TestCase
{
    /** @var array */
    private $A;

    /** @var Matrix */
    private $matrix;

    public function setUp()
    {
        $this->A = [
            [1, 2, 3],
            [2, 3, 4],
            [4, 5, 6],
        ];
        $this->matrix = MatrixFactory::create($this->A);
    }

    /**
     * @dataProvider dataProviderForAdd
     */
    public function testAdd(array $A, array $B, array $R)
    {
        $A  = MatrixFactory::create($A);
        $B  = MatrixFactory::create($B);
        $R  = MatrixFactory::create($R);
        $R2 = $A->add($B);
        $this->assertEquals($R, $R2);
        $this->assertInstanceOf(Matrix::class, $R2);
    }

    public function dataProviderForAdd()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [
                    [1, 1, 1],
                    [1, 1, 1],
                    [1, 1, 1],
                ],
                [
                    [2, 3, 4],
                    [3, 4, 5],
                    [4, 5, 6],
                ],
            ],
            [
                [
                    [0, 1, 2],
                    [9, 8, 7],
                ],
                [
                    [6, 5, 4],
                    [3, 4, 5],
                ],
                [
                    [ 6,  6,  6],
                    [12, 12, 12],
                ],
            ],
        ];
    }

    public function testAddExceptionRows()
    {
        $A = MatrixFactory::create([
            [1, 2],
            [2, 3],
        ]);
        $B = MatrixFactory::create([
            [1, 2]
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->add($B);
    }

    public function testAddExceptionColumns()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
        ]);
        $B = MatrixFactory::create([
            [1, 2],
            [2, 3],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->add($B);
    }

    /**
     * @dataProvider dataProviderForDirectSum
     */
    public function testDirectSum(array $A, array $B, array $R)
    {
        $A  = MatrixFactory::create($A);
        $B  = MatrixFactory::create($B);
        $R  = MatrixFactory::create($R);
        $R2 = $A->directSum($B);
        $this->assertEquals($R, $R2);
        $this->assertInstanceOf(Matrix::class, $R2);
    }

    public function dataProviderForDirectSum()
    {
        return [
            [
                [
                    [1, 3, 2],
                    [2, 3, 1],
                ],
                [
                    [1, 6],
                    [0, 1],
                ],
                [
                    [1, 3, 2, 0, 0],
                    [2, 3, 1, 0, 0],
                    [0, 0, 0, 1, 6],
                    [0, 0, 0, 0, 1],
                ],
            ],
        ];
    }

    /**
     * @testCase     kroneckerSum returns the expected SquareMatrix
     * @dataProvider dataProviderKroneckerSum
     * @param        array A
     * @param        array B
     * @param        array $expected
     */
    public function testKroneckerSum(array $A, array $B, array $expected)
    {
        $A   = new SquareMatrix($A);
        $B   = new SquareMatrix($B);
        $A⊕B = $A->kroneckerSum($B);
        $R   = new SquareMatrix($expected);

        $this->assertEquals($R, $A⊕B);
        $this->assertEquals($R->getMatrix(), $A⊕B->getMatrix());
        $this->assertInstanceOf(SquareMatrix::class, $A⊕B);
    }

    public function dataProviderKroneckerSum(): array
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
            [
                [
                    [1, 1],
                    [1, 1],
                ],
                [
                    [1, 1],
                    [1, 1],
                ],
                [
                    [2, 1, 1, 0],
                    [1, 2, 0, 1],
                    [1, 0, 2, 1],
                    [0, 1, 1, 2],
                ],
            ],
            [
                [
                    [1, 1],
                    [1, 1],
                ],
                [
                    [2, 3],
                    [4, 5],
                ],
                [
                    [3, 3, 1, 0],
                    [4, 6, 0, 1],
                    [1, 0, 3, 3],
                    [0, 1, 4, 6],
                ],
            ],
            [
                [
                    [2, 3],
                    [4, 5],
                ],
                [
                    [1, 1],
                    [1, 1],
                ],
                [
                    [3, 1, 3, 0],
                    [1, 3, 0, 3],
                    [4, 0, 6, 1],
                    [0, 4, 1, 6],
                ],
            ],
        ];
    }

    /**
     * @testCase kronecerSum throws a MatrixException if one of the matrices is not square
     * @dataProvider dataProviderForKroneckerSumSquareMatrixException
     * @param        array A
     * @param        array B
     */
    public function testKroneckerSumSquareMatrixException($A, $B)
    {
        $A   = new Matrix($A);
        $B   = new Matrix($B);

        $this->expectException(Exception\MatrixException::class);
        $A⊕B = $A->kroneckerSum($B);
    }

    public function dataProviderForKroneckerSumSquareMatrixException(): array
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                ],
                [
                    [1, 2],
                    [2, 3],
                ]
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                ],
                [
                    [1, 2, 3],
                    [2, 3, 4],
                ],
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                    [4, 5],
                ],
                [
                    [1, 2, 3],
                    [2, 3, 4],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForSubtract
     */
    public function testSubtract(array $A, array $B, array $R)
    {
        $A  = MatrixFactory::create($A);
        $B  = MatrixFactory::create($B);
        $R  = MatrixFactory::create($R);
        $R2 = $A->subtract($B);
        $this->assertEquals($R, $R2);
        $this->assertInstanceOf(Matrix::class, $R2);
    }

    public function dataProviderForSubtract()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [
                    [1, 1, 1],
                    [1, 1, 1],
                    [1, 1, 1],
                ],
                [
                    [ 0, 1, 2 ],
                    [ 1, 2, 3 ],
                    [ 2, 3, 4 ],
                ],
            ],
            [
                [
                    [0, 1, 2],
                    [9, 8, 7],
                ],
                [
                    [6, 5, 4],
                    [3, 4, 5],
                ],
                [
                    [ -6, -4, -2 ],
                    [  6,  4,  2 ],
                ],
            ],
        ];
    }

    public function testSubtractExceptionRows()
    {
        $A = MatrixFactory::create([
            [1, 2],
            [2, 3],
        ]);
        $B = MatrixFactory::create([
            [1, 2]
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->subtract($B);
    }

    public function testSubtractExceptionColumns()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
        ]);
        $B = MatrixFactory::create([
            [1, 2],
            [2, 3],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->subtract($B);
    }

    /**
     * @test         multiplication
     * @dataProvider dataProviderForMultiply
     * @param        array $A
     * @param        array $B
     * @param        array $expected
     * @throws       \Exception
     */
    public function testMultiply(array $A, array $B, array $expected)
    {
        // Given
        $A        = MatrixFactory::create($A);
        $B        = MatrixFactory::create($B);
        $expected = MatrixFactory::create($expected);

        // When
        $R = $A->multiply($B);

        // Then
        $this->assertEquals($expected, $R);
        $this->assertTrue($R->isEqual($expected));
    }

    public function dataProviderForMultiply()
    {
        return [
            [
                [
                    [2]
                ],
                [
                    [3]
                ],
                [
                    [6]
                ],
            ],
            [
                [
                    [3]
                ],
                [
                    [2]
                ],
                [
                    [6]
                ],
            ],
            [
                [
                    [1]
                ],
                [
                    [1, 2, 3]
                ],
                [
                    [1, 2, 3]
                ],
            ],
            [
                [
                    [0]
                ],
                [
                    [1, 2, 3]
                ],
                [
                    [0, 0, 0]
                ],
            ],
            [
                [
                    [4]
                ],
                [
                    [1, 2, 3]
                ],
                [
                    [4, 8, 12]
                ],
            ],
            [
                [
                    [1, 2],
                    [3, 4],
                ],
                [
                    [0, 0],
                    [0, 0],
                ],
                [
                    [0, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [0, 0],
                    [0, 0],
                ],
                [
                    [1, 2],
                    [3, 4],
                ],
                [
                    [0, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [0, 1],
                    [0, 0],
                ],
                [
                    [0, 0],
                    [1, 0],
                ],
                [
                    [1, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [0, 0],
                    [1, 0],
                ],
                [
                    [0, 1],
                    [0, 0],
                ],
                [
                    [0, 0],
                    [0, 1],
                ],
            ],
            [
                [
                    [1, 2],
                    [3, 4],
                ],
                [
                    [2, 0],
                    [1, 2],
                ],
                [
                    [4, 4],
                    [10, 8],
                ],
            ],
            [
                [
                    [2, 0],
                    [1, 2],
                ],
                [
                    [1, 2],
                    [3, 4],
                ],
                [
                    [2, 4],
                    [7, 10],
                ],
            ],
            [
                [
                    [ 1, 0, -2 ],
                    [ 0, 3, -1 ],
                ],
                [
                    [  0,  3 ],
                    [ -2, -1 ],
                    [  0,  4 ],
                ],
                [
                    [  0, -5 ],
                    [ -6, -7 ],
                ],
            ],
            [
                [
                    [ 2,  3 ],
                    [ 1, -5 ],
                ],
                [
                    [ 4,  3, 6 ],
                    [ 1, -2, 3 ],
                ],
                [
                    [ 11,  0, 21 ],
                    [ -1, 13, -9 ],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [4, 5, 6],
                ],
                [
                    [7, 8],
                    [9, 10],
                    [11, 12],
                ],
                [
                    [58, 64],
                    [139, 154],
                ],
            ],
            [
                [
                    [3, 4, 2],
                ],
                [
                    [13, 9, 7, 15],
                    [8, 7, 4, 6],
                    [6, 4, 0, 3],
                ],
                [
                    [83, 63, 37, 75],
                ],
            ],
            [
                [
                    [0, 1, 2],
                    [3, 4, 5],
                ],
                [
                    [6, 7],
                    [8, 9],
                    [10, 11],
                ],
                [
                    [28, 31],
                    [100, 112],
                ],
            ],
            [
                [
                    [1, 2, 3, 4, 5, 6, 7, 8],
                    [2, 3, 4, 5, 6, 7, 8, 9],
                    [3, 4, 5, 6, 7, 8, 9, 1],
                    [4, 5, 6, 7, 8, 9, 1, 2],
                    [5, 6, 7, 8, 9, 1, 2, 3],
                    [6, 7, 8, 9, 1, 2, 3, 4],
                    [7, 8, 9, 1, 2, 3, 4, 5],
                    [8, 9, 1, 2, 3, 4, 5, 6],
                    [9, 1, 2, 3, 4, 5, 6, 7],
                ],
                [
                    [7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8],
                    [8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                    [9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1],
                    [1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2],
                    [2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3],
                    [3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4],
                    [4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5],
                    [5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6],
                ],
                [
                    [150, 159, 177, 204, 240, 204, 177, 159, 150, 150 ,159],
                    [189, 197, 214, 240, 284, 247, 219, 200, 190, 189, 197],
                    [183, 181, 188, 204, 247, 281, 243, 214, 194, 183, 181],
                    [186, 174, 171, 177, 219, 243, 276, 237, 207, 186, 174],
                    [198, 176, 163, 159, 200, 214, 237, 269, 229, 198, 176],
                    [219, 187, 164, 150, 190, 194, 207, 229, 260, 219, 187],
                    [249, 207, 174, 150, 189, 183, 186, 198, 219, 249, 207],
                    [207, 236, 193, 159, 197, 181, 174, 176, 187, 207, 236],
                    [174, 193, 221, 177, 214, 188, 171, 163, 164, 174, 193],
                ],
            ],
            [
                [
                    [1.4, 5.3, 4.8],
                    [3.2, 2.3, 9.05],
                    [9.54, 0.2, 1.85],
                ],
                [
                    [3.5, 5.6, 6.7],
                    [6.5, 4.2, 9.05],
                    [0.6, 0.236, 4.5],
                ],
                [
                    [42.23, 31.2328, 78.945],
                    [31.58, 29.7158, 82.980],
                    [35.80, 54.7006, 74.053],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForMultiplyVector
     */
    public function testMultiplyVector(array $A, array $B, array $R)
    {
        $A  = MatrixFactory::create($A);
        $B  = new Vector($B);
        $R  = MatrixFactory::create($R);
        $R2 = $A->multiply($B);
        $this->assertInstanceOf(Matrix::class, $R2);
        $this->assertEquals($R, $R2);
    }

    public function dataProviderForMultiplyVector()
    {
        return [
            [
                [
                    [1],
                ],
                [1],
                [
                    [1],
                ],
            ],
            [
                [
                    [2],
                ],
                [3],
                [
                    [6],
                ],
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                ],
                [4, 5],
                [
                    [14],
                    [23],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [1, 2, 3],
                [
                    [14],
                    [20],
                    [26],
                ],
            ],
            [
                [
                    [3, 6, 5],
                    [1, 7, 5],
                    [2, 3, 2],
                ],
                [1, 5, 4],
                [
                    [53],
                    [56],
                    [25],
                ],
            ],
            [
                [
                    [1, 1, 1],
                    [2, 2, 2],
                ],
                [1, 2, 3],
                [
                    [6],
                    [12],
                ],
            ],
            [
                [
                    [1, 1, 1],
                    [2, 2, 2],
                    [3, 3, 3],
                    [4, 4, 4]
                ],
                [1, 2, 3],
                [
                    [6],
                    [12],
                    [18],
                    [24],
                ],
            ],
        ];
    }

    public function testMultiplyExceptionDimensionsDoNotMatch()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
        ]);
        $B = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->multiply($B);
    }

    public function testMultiplyExceptionNotMatrixOrVector()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $B = [
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ];

        $this->expectException(Exception\IncorrectTypeException::class);
        $A->multiply($B);
    }

    /**
     * @dataProvider dataProviderForScalarMultiply
     */
    public function testScalarMultiply(array $A, $k, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->scalarMultiply($k));
    }

    public function dataProviderForScalarMultiply()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 3,
                [
                    [3, 6, 9],
                    [6, 9, 12],
                    [9, 12, 15],
                ],
            ],
            [
                [
                    [1, 2, 3],
                ], 3,
                [
                    [3, 6, 9],
                ],
            ],
            [
                [
                    [1],
                    [2],
                    [3],
                ], 3,
                [
                    [3],
                    [6],
                    [9],
                ],
            ],
            [
                [
                    [1],
                ], 3,
                [
                    [3],
                ],
            ],
        ];
    }

    /**
     * @testCase     negate
     * @dataProvider dataProviderForNegate
     * @param        array $A
     * @param        array $−A
     * @throws       \Exception
     */
    public function testNegate(array $A, array $−A)
    {
        $A  = MatrixFactory::create($A);
        $−A = MatrixFactory::create($−A);

        $this->assertEquals($−A, $A->negate());
    }

    /**
     * @return array [A, −A]
     */
    public function dataProviderForNegate(): array
    {
        return [
            [
                [
                    [0]
                ],
                [
                    [0]
                ],
            ],
            [
                [
                    [1]
                ],
                [
                    [-1]
                ],
            ],
            [
                [
                    [-1]
                ],
                [
                    [1]
                ],
            ],
            [
                [
                    [1, 2],
                    [3, 4],
                ],
                [
                    [-1, -2],
                    [-3, -4],
                ],
            ],
            [
                [
                    [1, -2, 3],
                    [-4, 5, -6],
                    [7, -8, 9],
                ],
                [
                    [-1, 2, -3],
                    [4, -5, 6],
                    [-7, 8, -9],
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForScalarDivide
     */
    public function testScalarDivide(array $A, $k, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->scalarDivide($k));
    }

    public function dataProviderForScalarDivide()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 3,
                [
                    [1 / 3, 2 / 3, 1],
                    [2 / 3, 1, 4 / 3],
                    [1, 4 / 3, 5 / 3],
                ],
            ],
            [
                [
                    [3, 6, 9],
                ], 3,
                [
                    [1, 2, 3],
                ],
            ],
            [
                [
                    [1],
                    [2],
                    [3],
                ], 3,
                [
                    [1 / 3],
                    [2 / 3],
                    [1],
                ],
            ],
            [
                [
                    [1],
                ], 3,
                [
                    [1 / 3],
                ],
            ],
        ];
    }

    public function testScalarDivideByZero()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
        ]);

        $this->expectException(Exception\BadParameterException::class);
        $A->scalarDivide(0);
    }

    /**
     * @dataProvider dataProviderForHadamardProduct
     */
    public function testHadamardProduct(array $A, array $B, array $A∘B)
    {
        $A   = MatrixFactory::create($A);
        $B   = MatrixFactory::create($B);
        $A∘B = MatrixFactory::create($A∘B);

        $this->assertEquals($A∘B, $A->hadamardProduct($B));
    }

    public function dataProviderForHadamardProduct()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [
                    [1, 4, 9],
                    [4, 9, 16],
                    [9, 16, 25],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [
                    [6, 6, 4],
                    [8, 7, 8],
                    [3, 1, 7],
                ],
                [
                    [6, 12, 12],
                    [16, 21, 32],
                    [9, 4, 35],
                ]
            ],
        ];
    }

    public function testHadamardProductDimensionsDoNotMatch()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
        ]);
        $B = MatrixFactory::create([
            [1, 2, 3, 4],
            [2, 3, 4, 5],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->hadamardProduct($B);
    }

    /**
     * @dataProvider dataProviderForKroneckerProduct
     */
    public function testKroneckerProduct(array $A, array $B, array $expected)
    {
        $A        = new Matrix($A);
        $B        = new Matrix($B);
        $A⊗B      = $A->kroneckerProduct($B);
        $expected = new Matrix($expected);

        $this->assertEquals($expected->getMatrix(), $A⊗B->getMatrix());
    }

    public function dataProviderForKroneckerProduct()
    {
        return [
            [
                [
                    [1, 2],
                    [3, 4],
                ],
                [
                    [0, 5],
                    [6, 7],
                ],
                [
                    [0, 5, 0, 10],
                    [6, 7, 12, 14],
                    [0, 15, 0, 20],
                    [18, 21, 24, 28],
                ],
            ],
            [
                [
                    [1, 1],
                    [1, -1],
                ],
                [
                    [1, 1],
                    [1, -1],
                ],
                [
                    [1, 1, 1, 1],
                    [1, -1, 1, -1],
                    [1, 1, -1, -1],
                    [1, -1, -1, 1],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [4, 5, 6],
                ],
                [
                    [7, 8],
                    [9, 10],
                ],
                [
                    [7, 8, 14, 16, 21, 24],
                    [9, 10, 18, 20, 27, 30],
                    [28, 32, 35, 40, 42, 48],
                    [36, 40, 45, 50, 54, 60],
                ],
            ],
            [
                [
                    [2, 3],
                    [5, 4],
                ],
                [
                    [5, 5],
                    [4, 4],
                    [2, 9]
                ],
                [
                    [10, 10, 15, 15],
                    [8, 8, 12, 12],
                    [4, 18, 6, 27],
                    [25, 25, 20, 20],
                    [20, 20, 16, 16],
                    [10, 45, 8, 36],
                ],
            ],
            [
                [
                    [2, 3],
                    [5, 4],
                ],
                [
                    [5, 4, 2],
                    [5, 4, 9],
                ],
                [
                    [10, 8, 4, 15, 12, 6],
                    [10, 8, 18, 15, 12, 27],
                    [25, 20, 10, 20, 16, 8],
                    [25, 20, 45, 20, 16, 36],
                ],
            ],

        ];
    }
}
