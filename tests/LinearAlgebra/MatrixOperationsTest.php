<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\SquareMatrix;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Exception;

class MatrixOperationsTest extends \PHPUnit_Framework_TestCase
{

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
     * @dataProvider dataProviderForMultiply
     */
    public function testMultiply(array $A, array $B, array $R)
    {
        $A  = MatrixFactory::create($A);
        $B  = MatrixFactory::create($B);
        $R  = MatrixFactory::create($R);
        $R2 = $A->multiply($B);
        $this->assertInstanceOf(Matrix::class, $R2);
        $this->assertEquals($R, $R2);
    }

    public function dataProviderForMultiply()
    {
        return [
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
     * @dataProvider dataProviderForVectorMultiply
     */
    public function testVectorMultiply(array $A, array $B, array $R)
    {
        $A  = MatrixFactory::create($A);
        $B  = new Vector($B);
        $R  = new Vector($R);
        $R2 = $A->vectorMultiply($B);
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\Vector::class, $R2);
        $this->assertEquals($R, $R2);
    }

    public function dataProviderForVectorMultiply()
    {
        return [
            [
                [
                    [1],
                ],
                [1],
                [1],
            ],
            [
                [
                    [2],
                ],
                [3],
                [6],
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                ],
                [4, 5],
                [14, 23]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [1, 2, 3],
                [14, 20, 26],
            ],
            [
                [
                    [3, 6, 5],
                    [1, 7, 5],
                    [2, 3, 2],
                ],
                [1, 5, 4],
                [53, 56, 25],
            ],
            [
                [
                    [1, 1, 1],
                    [2, 2, 2],
                ],
                [1, 2, 3],
                [6, 12],
            ],
            [
                [
                    [1, 1, 1],
                    [2, 2, 2],
                    [3, 3, 3],
                    [4, 4, 4]
                ],
                [1, 2, 3],
                [6, 12, 18, 24],
            ],
        ];
    }

    public function testVectorMultiplyExceptionDimensionsDoNotMatch()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
        ]);
        $B = new Vector([1, 2, 3, 4, 5]);

        $this->expectException(Exception\MatrixException::class);
        $A->vectorMultiply($B);
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

    public function testScalarMultiplyExceptionKNotNumber()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
        ]);

        $this->expectException(Exception\BadParameterException::class);
        $A->scalarMultiply('k');
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
                    [1/3, 2/3, 1],
                    [2/3, 1, 4/3],
                    [1, 4/3, 5/3],
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
                    [1/3],
                    [2/3],
                    [1],
                ],
            ],
            [
                [
                    [1],
                ], 3,
                [
                    [1/3],
                ],
            ],
        ];
    }

    public function testScalarDivideExceptionKNotNumber()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
        ]);

        $this->expectException(Exception\BadParameterException::class);
        $A->scalarDivide('k');
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
     * @dataProvider dataProviderForTranspose
     */
    public function testTranspose(array $A, array $R)
    {
        $A  = MatrixFactory::create($A);
        $R  = MatrixFactory::create($R);
        $Aᵀ = $A->transpose();
        $this->assertEquals($R, $Aᵀ);

        // Transpose of transpose is the original matrix
        $Aᵀᵀ = $Aᵀ->transpose();
        $this->assertEquals($A, $Aᵀᵀ);
    }

    public function dataProviderForTranspose()
    {
        return [
            [
                [
                    [1, 2],
                    [3, 4],
                    [5, 6],
                ],
                [
                    [1, 3, 5],
                    [2, 4, 6],
                ]
            ],
            [
                [
                    [5, 4, 3],
                    [4, 0, 4],
                    [7, 10, 3],
                ],
                [
                    [5, 4, 7],
                    [4, 0, 10],
                    [3, 4, 3],
                ]
            ],
            [
                [
                    [5, 4],
                    [4, 0],
                    [7, 10],
                    [-1, 8],
                ],
                [
                    [5, 4, 7, -1],
                    [4, 0, 10, 8],
                ]
            ]
        ];
    }

    public function testMap()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ]);

        $doubler = function ($x) {
            return $x * 2;
        };
        $R = $A->map($doubler);

        $E = MatrixFactory::create([
            [2, 4, 6],
            [8, 10, 12],
            [14, 16, 18],
        ]);
        $this->assertEquals($E, $R);
    }

    /**
     * @dataProvider dataProviderForTrace
     */
    public function testTrace(array $A, $tr)
    {
        $A = MatrixFactory::create($A);
        $this->assertEquals($tr, $A->trace());
    }

    public function dataProviderForTrace()
    {
        return [
            [
                [[1]], 1
            ],
            [
                [
                  [1, 2],
                  [2, 3],
                ], 4
            ],
            [
                [
                  [1, 2, 3],
                  [4, 5, 6],
                  [7, 8, 9],
                ], 15
            ],
        ];
    }

    public function testTraceExceptionNotSquareMatrix()
    {
        $A = MatrixFactory::create([
            [1, 2],
            [2, 3],
            [3, 4],
        ]);
        $this->expectException(Exception\MatrixException::class);
        $A->trace();
    }

    /**
     * @dataProvider dataProviderForDiagonal
     */
    public function testDiagonal(array $A, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->diagonal());
    }

    public function dataProviderForDiagonal()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [
                    [1, 0, 0],
                    [0, 3, 0],
                    [0, 0, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                    [4, 5, 6],
                ],
                [
                    [1, 0, 0],
                    [0, 3, 0],
                    [0, 0, 5],
                    [0, 0, 0],
                ]
            ],
            [
                [
                    [1, 2, 3, 4],
                    [2, 3, 4, 5],
                    [3, 4, 5, 6],
                ],
                [
                    [1, 0, 0, 0],
                    [0, 3, 0, 0],
                    [0, 0, 5, 0],
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForAugment
     */
    public function testAugment(array $A, array $B, array $⟮A∣B⟯)
    {
        $A    = MatrixFactory::create($A);
        $B    = MatrixFactory::create($B);
        $⟮A∣B⟯ = MatrixFactory::create($⟮A∣B⟯);

        $this->assertEquals($⟮A∣B⟯, $A->augment($B));
    }

    public function dataProviderForAugment()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [
                    [4],
                    [5],
                    [6],
                ],
                [
                    [1, 2, 3, 4],
                    [2, 3, 4, 5],
                    [3, 4, 5, 6],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [
                    [4, 7, 8],
                    [5, 7, 8],
                    [6, 7, 8],
                ],
                [
                    [1, 2, 3, 4, 7, 8],
                    [2, 3, 4, 5, 7, 8],
                    [3, 4, 5, 6, 7, 8],
                ]
            ],
            [
                [
                    [1, 2, 3],

                ],
                [
                    [4],

                ],
                [
                    [1, 2, 3, 4],
                ]
            ],
            [
                [
                    [1],

                ],
                [
                    [4],
                ],
                [
                    [1, 4],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [
                    [4, 7, 8, 9],
                    [5, 7, 8, 9],
                    [6, 7, 8, 9],
                ],
                [
                    [1, 2, 3, 4, 7, 8, 9],
                    [2, 3, 4, 5, 7, 8, 9],
                    [3, 4, 5, 6, 7, 8, 9],
                ]
            ],
        ];
    }

    public function testAugmentExceptionRowsDoNotMatch()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $B = MatrixFactory::create([
            [4, 5],
            [5, 6],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->augment($B);
    }

    /**
     * @dataProvider dataProviderForAugmentIdentity
     */
    public function testAugmentIdentity(array $C, array $⟮C∣I⟯)
    {
        $C    = MatrixFactory::create($C);
        $⟮C∣I⟯ = MatrixFactory::create($⟮C∣I⟯);

        $this->assertEquals($⟮C∣I⟯, $C->augmentIdentity());
    }

    public function dataProviderForAugmentIdentity()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [
                    [1, 2, 3, 1, 0, 0],
                    [2, 3, 4, 0, 1, 0],
                    [3, 4, 5, 0, 0, 1],
                ]
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                ],
                [
                    [1, 2, 1, 0],
                    [2, 3, 0, 1],
                ]
            ],
            [
                [
                    [1]
                ],
                [
                    [1, 1],
                ]
            ],
        ];
    }

    public function testAugmentIdentityExceptionNotSquare()
    {
        $A = MatrixFactory::create([
            [1, 2],
            [2, 3],
            [3, 4],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $this->assertEquals($A->augmentIdentity());
    }

    /**
     * @dataProvider dataProviderForAugmentBelow
     */
    public function testAugmentBelow(array $A, array $B, array $⟮A∣B⟯)
    {
        $A    = MatrixFactory::create($A);
        $B    = MatrixFactory::create($B);
        $⟮A∣B⟯ = MatrixFactory::create($⟮A∣B⟯);

        $this->assertEquals($⟮A∣B⟯, $A->augmentBelow($B));
    }

    public function dataProviderForAugmentBelow()
    {
        return [
            [
                [
                    [1],
                ],
                [
                    [2],
                ],
                [
                    [1],
                    [2],
                ],
            ],
            [
                [
                    [1],
                    [2],
                ],
                [
                    [3],
                ],
                [
                    [1],
                    [2],
                    [3],
                ],
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                ],
                [
                    [3, 4],
                ],
                [
                    [1, 2],
                    [2, 3],
                    [3, 4],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                ],
                [
                    [3, 4, 5],
                ],
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                ],
                [
                    [3, 4, 5],
                    [4, 5, 6]
                ],
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                    [4, 5, 6],
                ],
            ],
        ];
    }

    public function testAugmentBelowExceptionColumnssDoNotMatch()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $B = MatrixFactory::create([
            [4, 5],
            [5, 6],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->augmentBelow($B);
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

    /**
     * @dataProvider dataProviderForDet
     */
    public function testDet(array $A, $det)
    {
        $A = MatrixFactory::create($A);

        $this->assertEquals($det, round($A->det(), 0.1)); // Test calculation
        $this->assertEquals($det, round($A->det(), 0.1)); // Test class attribute
    }

    public function dataProviderForDet()
    {
        return [
            [
                [[1]], 1
            ],
            [
                [
                    [0]
                ],
                0
            ],
            [
                [
                    [1]
                ],
                1
            ],
            [
                [
                    [2]
                ],
                2
            ],
            [
                [
                    [5]
                ],
                5
            ],
            [
                [
                    [-5]
                ],
                -5
            ],
            [
                [
                    [0, 0],
                    [0, 0],
                ], 0
            ],
            [
                [
                    [0, 0],
                    [0, 1],
                ], 0
            ],
            [
                [
                    [0, 0],
                    [1, 0],
                ], 0
            ],
            [
                [
                    [0, 0],
                    [1, 1],
                ], 0
            ],
            [
                [
                    [0, 1],
                    [0, 0],
                ], 0
            ],
            [
                [
                    [0, 1],
                    [0, 1],
                ], 0
            ],
            [
                [
                    [1, 0],
                    [0, 0],
                ], 0
            ],
            [
                [
                    [1, 0],
                    [1, 0],
                ], 0
            ],
            [
                [
                    [1, 1],
                    [0, 0],
                ], 0
            ],
            [
                [
                    [1, 1],
                    [1, 1],
                ], 0
            ],
            [
                [
                    [0, 1],
                    [1, 0],
                ], -1
            ],
            [
                [
                    [0, 1],
                    [1, 1],
                ], -1
            ],
            [
                [
                    [1, 0],
                    [0, 1],
                ], 1
            ],
            [
                [
                    [1, 0],
                    [1, 1],
                ], 1
            ],
            [
                [
                    [1, 1],
                    [0, 1],
                ], 1
            ],
            [
                [
                    [1, 1],
                    [1, 0],
                ], -1
            ],
            [
                [
                    [3, 8],
                    [4, 6],
                ], -14
            ],
            [
                [
                    [4, 3],
                    [3, 2],
                ], -1
            ],
            [
                [
                    [6, 1, 1],
                    [4, -2, 5],
                    [2, 8, 7],
                ], -306
            ],
            [
                [
                    [1, 2, 0],
                    [-1, 1, 1],
                    [1, 2, 3],
                ], 9
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0
            ],
            [
                [
                    [1, 2, 1],
                    [-2, -3, 1],
                    [3, 5, 0],
                ], 0
            ],
            [
                [
                    [1, -1, 2],
                    [2, 1, 1],
                    [1, 1, 0],
                ], 0
            ],
            [
                [
                    [1, 0, 1],
                    [0, 1, -1],
                    [0, 0, 0],
                ], 0
            ],
            [
                [
                    [1, 1, 4, 1, 2],
                    [0, 1, 2, 1, 1],
                    [0, 0, 0, 1, 2],
                    [1, -1, 0, 0, 2],
                    [2, 1, 6, 0, 1],
                ], 0
            ],
            [
                [
                    [4, 6, 3, 2],
                    [3, 6, 5, 3],
                    [5, 7, 8, 6],
                    [5, 4, 3, 2],
                ], -43
            ],
            [
                [
                    [3, 2, 0, 1],
                    [4, 0, 1, 2],
                    [3, 0, 2, 1],
                    [9, 2, 3, 1],
                ], 24
            ],
            [
                [
                    [1, 2, 3, 4],
                    [5, 6, 7, 8],
                    [2, 6, 4, 8],
                    [3, 1, 1, 2],
                ], 72
            ],
            [
                [
                    [7, 4, 2, 0],
                    [6, 3, -1, 2],
                    [4, 6, 2, 5],
                    [8, 2, -7, 1],
                ], -279
            ],
            [
                [
                  [-4, 3, 1, 5, -8],
                  [6, 0, 9, 2, 6],
                  [-1, 4, 4, 0, 2],
                  [8, -1, 3, 4, 0],
                  [5, 9, -7, -7, 1]
                ],
                -5763
            ],
            [
                [
                  [4, 3, 1, 5, -8],
                  [6, 0, 9, 2, 6],
                  [-1, 4, 4, 0, 2],
                  [8, -1, 3, 4, 0],
                  [5, 9, -7, -7, 1]
                ],
                -10035
            ],
            [
                [
                  [4, 3, 1, 5, 8],
                  [6, 0, 9, 2, 6],
                  [-1, 4, 4, 0, 2],
                  [8, -1, 3, 4, 0],
                  [5, 9, -7, -7, 1]
                ],
                32157
            ],
            [
                [
                  [4, 3, 1, 5, 8],
                  [6, 0, 9, 2, 6],
                  [1, 4, 4, 0, 2],
                  [8, -1, 3, 4, 0],
                  [5, 9, -7, -7, 1]
                ],
                28287
            ],
            [
                [
                  [4, 3, 1, 5, 8],
                  [6, 0, 9, 2, 6],
                  [1, 4, 4, 0, 2],
                  [8, 1, 3, 4, 0],
                  [5, 9, -7, -7, 1]
                ],
                30357
            ],
            [
                [
                  [4, 3, 1, 5, 8],
                  [6, 0, 9, 2, 6],
                  [1, 4, 4, 0, 2],
                  [8, 1, 3, 4, 0],
                  [5, 9, 7, -7, 1]
                ],
                17953
            ],
            [
                [
                  [4, 3, 1, 5, 8],
                  [6, 0, 9, 2, 6],
                  [1, 4, 4, 0, 2],
                  [8, 1, 3, 4, 0],
                  [5, 9, 7, 7, 1]
                ],
                -11615
            ],
            [
                [
                    [5, 2, 0, 0, -2],
                    [0, 1, 4, 3, 2],
                    [0, 0, 2, 6, 3],
                    [0, 0, 3, 4, 1],
                    [0, 0, 0, 0, 2],
                ],
                -100
            ],
            [
                [
                    [5, 2, 0, 0, 2],
                    [0, 1, 4, 3, 2],
                    [0, 0, 2, 6, 3],
                    [0, 0, 3, 4, 1],
                    [0, 0, 0, 0, 2],
                ],
                -100
            ],
            [
                [
                    [5, 2, 0, 0, -2],
                    [0, -1, 4, 3, 2],
                    [0, 0, 2, 6, 3],
                    [0, 0, 3, 4, 1],
                    [0, 0, 0, 0, 2],
                ],
                100
            ],
            [
                [
                    [2, -9, 1, 8, 4],
                    [-10, -1, 2, 7, 0],
                    [0, 4, -6, 1, -8],
                    [6, -14, 11, 0, 3],
                    [5, 1, -3, 2, -1],
                ],
                30143
            ],
            [
                [
                    [2, 9, 1, 8, 4],
                    [-10, -1, 2, 7, 0],
                    [0, 4, -6, 1, -8],
                    [6, -14, 11, 0, 3],
                    [5, 1, -3, 2, -1],
                ],
                -55105
            ],
            [
                [
                    [2, 9, 1, 8, 4],
                    [10, -1, 2, 7, 0],
                    [0, 4, -6, 1, -8],
                    [6, -14, 11, 0, 3],
                    [5, 1, -3, 2, -1],
                ],
                -5
            ],
            [
                [
                    [2, 9, 1, 8, 4],
                    [10, 1, 2, 7, 0],
                    [0, 4, -6, 1, -8],
                    [6, -14, 11, 0, 3],
                    [5, 1, -3, 2, -1],
                ],
                6929
            ],
            [
                [
                    [276,1,179,23, 9387],
                    [0, 0, 78, 0, 0],
                    [0, 0, -1, 0, 1],
                    [0, 0, 1994, -1, 1089],
                    [1, 0, 212, 726, -378],
                ],
                78
            ],
            [
                [
                    [276,1,179,23, 9387],
                    [0, 0, 78, 0, 0],
                    [0, 0, 1, 0, 1],
                    [0, 0, 1994, -1, 1089],
                    [1, 0, 212, 726, -378],
                ],
                78
            ],
            [
                [
                    [276,1,179,23, 9387],
                    [0, 0, 78, 0, 0],
                    [0, 0, 1, 0, 1],
                    [0, 0, 1994, 1, 1089],
                    [1, 0, 212, 726, -378],
                ],
                -78
            ],
            [
                [
                    [276,1,179,23, 9387],
                    [0, 0, 78, 0, 0],
                    [0, 0, 1, 0, 1],
                    [0, 0, 1994, 1, 1089],
                    [1, 0, 212, 726, 378],
                ],
                -78
            ],
            [
                [
                    [276,1,179,23, -9387],
                    [0, 0, 78, 0, 0],
                    [0, 0, 1, 0, 1],
                    [0, 0, 1994, 1, 1089],
                    [1, 0, 212, 726, 378],
                ],
                -78
            ],
            [
                [
                    [1, 2, 3, 4, 5],
                    [2, 3, 4, 5, 6],
                    [5, 4, 3, 2, 1],
                    [4, 3, 2, 1, 0],
                    [1, 3, 5, 7, 9],
                ],
                0
            ],
            [
                [
                    [1, 0, 3, 5, 1],
                    [0, 1, 5, 1, 0],
                    [0, 4, 0, 0, 2],
                    [2, 3, 1, 2, 0],
                    [1, 0, 0, 1, 1],
                ],
                230
            ],
            [
                [
                    [2, 3, 4, 1, 3],
                    [6, 1, 3, 1, 2],
                    [6, 3, 1, 2, 5],
                    [4, 2, 4, 7, 8],
                    [2, 1, 2, 4, 2],
                ],
                -1016
            ],
            [
                [
                    [2, 3, -4, 1, 3],
                    [6, 1, 3, 1, 2],
                    [6, 3, 1, 2, 5],
                    [4, 2, 4, 7, 8],
                    [2, 1, 2, 4, 2],
                ],
                296
            ],
            [
                [
                    [2, 3, -4, 1, 3],
                    [6, 1, -3, 1, 2],
                    [6, 3, 1, 2, 5],
                    [4, 2, 4, 7, 8],
                    [2, 1, 2, 4, 2],
                ],
                1280
            ],
            [
                [
                    [2, 3, -4, 1, 3],
                    [6, 1, -3, 1, 2],
                    [6, 3, -1, 2, 5],
                    [4, 2, 4, 7, 8],
                    [2, 1, 2, 4, 2],
                ],
                808
            ],
            [
                [
                    [2, 3, -4, 1, 3],
                    [6, 1, -3, 1, 2],
                    [6, 3, -1, 2, 5],
                    [4, 2, -4, 7, 8],
                    [2, 1, 2, 4, 2],
                ],
                1320
            ],
            [
                [
                    [2, 3, -4, 1, 3],
                    [6, 1, -3, 1, 2],
                    [6, 3, -1, 2, 5],
                    [4, 2, -4, 7, 8],
                    [2, 1, -2, 4, 2],
                ],
                1016
            ],
            [
                [
                    [2, 3, -4, -1, 3],
                    [6, 1, -3, 1, 2],
                    [6, 3, -1, 2, 5],
                    [4, 2, -4, 7, 8],
                    [2, 1, -2, 4, 2],
                ],
                1176
            ],
            [
                [
                    [2, 3, -4, -1, -3],
                    [6, 1, -3, -1, 2],
                    [6, 3, -1, 2, 5],
                    [4, 2, -4, 7, -8],
                    [2, 1, -2, 4, 2],
                ],
                -3664
            ],
            [
                [
                    [2, 1, 2],
                    [1, 1, 1],
                    [2, 2, 5],
                ],
                3
            ],
            [
                [
                    [1, 0, 2, -1],
                    [3, 0, 0, 5],
                    [2, 1, 4, -3],
                    [1, 0, 5, 0],
                ],
                30
            ],
            [
                [
                    [1, 0, 2, 0, 0, 4],
                    [18, 1, 5, 0, 0, 9],
                    [3, 5, 3, 6, 0, 4],
                    [2, 0, 8, 0, 0, 7],
                    [7, 0, 4, 0, 6, 0],
                    [0, 0, 1, 0, 0, 0]
                ],
                -36
            ],
            [
                [
                    [-1, 0, 2, 0, 0, 4],
                    [18, 1, 5, 0, 0, 9],
                    [3, 5, 3, 6, 0, 4],
                    [2, 0, 8, 0, 0, 7],
                    [7, 0, 4, 0, 6, 0],
                    [0, 0, 1, 0, 0, 0]
                ],
                -540
            ],
            [
                [
                    [-1, 0, -2, 0, 0, 4],
                    [18, 1, 5, 0, 0, 9],
                    [3, 5, 3, 6, 0, 4],
                    [2, 0, 8, 0, 0, 7],
                    [7, 0, 4, 0, 6, 0],
                    [0, 0, 1, 0, 0, 0]
                ],
                -540
            ],
            [
                [
                    [-1, 0, -2, 0, 0, -4],
                    [18, 1, 5, 0, 0, 9],
                    [3, 5, 3, 6, 0, 4],
                    [2, 0, 8, 0, 0, 7],
                    [7, 0, 4, 0, 6, 0],
                    [0, 0, 1, 0, 0, 0]
                ],
                36
            ],
            [
                [
                    [1, 1, 1, 1, 1,  1],
                    [1, 3, 1, 3, 1,  3],
                    [1, 1, 4, 1, 1,  4],
                    [1, 3, 1, 7, 1,  3],
                    [1, 1, 1, 1, 6,  1],
                    [1, 3, 4, 3, 1, 12]
                ],
                720
            ],
            [
                [
                    [-1, 1, 1, 1, 1, 1],
                    [1, 3, 1, 3, 1,  3],
                    [1, 1, 4, 1, 1,  4],
                    [1, 3, 1, 7, 1,  3],
                    [1, 1, 1, 1, 6,  1],
                    [1, 3, 4, 3, 1, 12]
                ],
                -2448
            ],
            [
                [
                    [-1, 1, 1, 1, 1, 1],
                    [1, 3, 1, 3, 1,  3],
                    [1, 1, 4, 1, 1,  4],
                    [1, -3, 1, 7, 1, 3],
                    [1, 1, 1, 1, 6,  1],
                    [1, 3, 4, 3, 1, 12]
                ],
                -6120
            ],
            [
                [
                    [1, 0, 0, 0, 0, 0],
                    [0, 2, 0, 0, 0, 0],
                    [0, 0, 3, 0, 0, 0],
                    [0, 0, 0, 4, 0, 0],
                    [0, 0, 0, 0, 5, 0],
                    [0, 0, 0, 0, 0, 6]
                ],
                720
            ],
            [
                [
                    [-1, 0, 0, 0, 0, 0],
                    [0, 2, 0, 0, 0, 0],
                    [0, 0, 3, 0, 0, 0],
                    [0, 0, 0, 4, 0, 0],
                    [0, 0, 0, 0, 5, 0],
                    [0, 0, 0, 0, 0, 6]
                ],
                -720
            ],
            [
                [
                    [-1, 0, 0, 0, 0, 0],
                    [0, 2, 0, 0, 0, 0],
                    [0, 0, 0, 4, 0, 0],
                    [0, 0, 3, 0, 0, 0],
                    [0, 0, 0, 0, 5, 0],
                    [0, 0, 0, 0, 0, 6]
                ],
                720
            ],
            [
                [
                    [1, 0, 0, 0, 0, 0],
                    [1, 2, 0, 0, 0, 0],
                    [1, 0, 3, 0, 0, 0],
                    [1, 2, 0, 4, 0, 0],
                    [1, 0, 0, 0, 5, 0],
                    [1, 2, 3, 0, 0, 6],
                ],
                720
            ],
            [
                [
                    [0, 1, 4, 3, 2, 3, 3, 4, 4],
                    [1, 0, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 0, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 0, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 0, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 0, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 0, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 0, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 0],
                ],
                1472
            ],
            [
                [
                    [0, 1, 4, 3, 2, 3, 3, 4, 4],
                    [-1, 0, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 0, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 0, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 0, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 0, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 0, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 0, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 0],
                ],
                0
            ],
            [
                [
                    [2, 1, 4, 3, 2, 3, 3, 4, 4],
                    [1, 2, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 2, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 2, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 2, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 2],
                ],
                128
            ],
            [
                [
                    [2, -1, 4, 3, 2, 3, 3, 4, 4],
                    [1, 2, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 2, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 2, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 2, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 2],
                ],
                256
            ],
            [
                [
                    [2, 1, 4, 3, 2, 3, 3, 4, 4],
                    [-1, 2, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 2, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 2, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 2, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 2],
                ],
                256
            ],
            [
                [
                    [2, -1, 4, 3, 2, 3, 3, 4, 4],
                    [-1, 2, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 3, 4, 4],
                    [3, 2, 1, 2, 1, 2, 2, 3, 3],
                    [2, 1, 2, 1, 2, 1, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 2, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 2],
                ],
                512
            ],
            [
                [
                    [2, -1, 4, 3, 2, 3, 3, 4, 4],
                    [-1, 2, 3, 2, 1, 2, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 3, 4, 4],
                    [2, 1, 2, 1, 2, 1, 1, 2, 2],
                    [3, 2, 1, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 2, 1, 2],
                    [4, 3, 4, 3, 2, 3, 1, 2, 2],
                    [4, 3, 4, 3, 2, 3, 2, 2, 2],
                ],
                -512
            ],
        ];
    }

    public function testDetExceptionNotSquareMatrix()
    {
        $A = MatrixFactory::create([[1, 2, 3]]);

        $this->expectException(Exception\MatrixException::class);
        $A->det();
    }

    /**
     * @dataProvider dataProviderForInverse
     */
    public function testInverse(array $A, array $A⁻¹)
    {
        $A   = MatrixFactory::create($A);
        $A⁻¹ = MatrixFactory::create($A⁻¹);

        $this->assertEquals($A⁻¹, $A->inverse(), '', 0.001); // Test calculation
        $this->assertEquals($A⁻¹, $A->inverse(), '', 0.001); // Test class attribute
    }

    public function dataProviderForInverse()
    {
        return [
            [
                [
                    [4, 7],
                    [2, 6],
                ],
                [
                    [0.6, -0.7],
                    [-0.2, 0.4],
                ],
            ],
            [
                [
                    [4, 3],
                    [3, 2],
                ],
                [
                    [-2, 3],
                    [3, -4],
                ],
            ],
            [
                [
                    [1, 2],
                    [3, 4],
                ],
                [
                    [-2, 1],
                    [3/2, -1/2],
                ],
            ],
            [
                [
                    [3, 3.5],
                    [3.2, 3.6],
                ],
                [
                    [-9, 8.75],
                    [8, -7.5],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [0, 4, 5],
                    [1, 0, 6],
                ],
                [
                    [12/11, -6/11, -1/11],
                    [5/22, 3/22, -5/22],
                    [-2/11, 1/11, 2/11],
                ],
            ],
            [
                [
                    [7, 2, 1],
                    [0, 3, -1],
                    [-3, 4, -2],
                ],
                [
                    [-2, 8, -5],
                    [3, -11, 7],
                    [9, -34, 21],
                ],
            ],
            [
                [
                    [3, 6, 6, 8],
                    [4, 5, 3, 2],
                    [2, 2, 2, 3],
                    [6, 8, 4, 2],
                ],
                [
                    [-0.333, 0.667, 0.667, -0.333],
                    [0.167, -2.333, 0.167, 1.417],
                    [0.167, 4.667, -1.833, -2.583],
                    [0.000, -2.000, 1.000, 1.000],
                ],
            ],
            [
                [
                    [4, 23, 6, 4, 7],
                    [3, 64, 23, 52, 2],
                    [65, 45, 3, 23, 1],
                    [2, 3, 4, 3, 9],
                    [53, 99, 54, 32, 105],
                ],
                [
                    [-0.142, 0.006, 0.003, -0.338, 0.038],
                    [0.172, -0.012, 0.010, 0.275, -0.035],
                    [-0.856, 0.082, -0.089, -2.344, 0.257],
                    [0.164, -0.001, 0.026, 0.683, -0.070],
                    [0.300, -0.033, 0.027, 0.909, -0.088],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForInverseExceptionNotSquare
     */
    public function testInverseExceptionNotSquare(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->expectException(Exception\MatrixException::class);
        $A->inverse();
    }

    public function dataProviderForInverseExceptionNotSquare()
    {
        return [
            [
                [
                    [3, 4, 4],
                    [6, 8, 5],
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForInverseExceptionDetIsZero
     */
    public function testInverseExceptionDetIsZero(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->expectException(Exception\MatrixException::class);
        $A->inverse();
    }

    public function dataProviderForInverseExceptionDetIsZero()
    {
        return [
            [
                [
                    [3, 4],
                    [6, 8],
                ]

            ],
        ];
    }

    /**
     * @dataProvider dataProviderForMinorMatrix
     */
    public function testMinorMatrix(array $A, int $mᵢ, int $nⱼ, array $Mᵢⱼ)
    {
        $A   = MatrixFactory::create($A);
        $Mᵢⱼ = MatrixFactory::create($Mᵢⱼ);

        $this->assertEquals($Mᵢⱼ, $A->minorMatrix($mᵢ, $nⱼ));
    }

    public function dataProviderForMinorMatrix()
    {
        return [
            [
                [
                    [1, 4, 7],
                    [3, 0, 5],
                    [-1, 9, 11],
                ],
                0, 0,
                [
                    [0, 5],
                    [9, 11],
                ],
            ],
            [
                [
                    [1, 4, 7],
                    [3, 0, 5],
                    [-1, 9, 11],
                ],
                0, 1,
                [
                    [3, 5],
                    [-1, 11],
                ],
            ],
            [
                [
                    [1, 4, 7],
                    [3, 0, 5],
                    [-1, 9, 11],
                ],
                0, 2,
                [
                    [3, 0],
                    [-1, 9],
                ],
            ],
            [
                [
                    [1, 4, 7],
                    [3, 0, 5],
                    [-1, 9, 11],
                ],
                1, 0,
                [
                    [4, 7],
                    [9, 11],
                ],
            ],
            [
                [
                    [1, 4, 7],
                    [3, 0, 5],
                    [-1, 9, 11],
                ],
                1, 1,
                [
                    [1, 7],
                    [-1, 11],
                ],
            ],
            [
                [
                    [1, 4, 7],
                    [3, 0, 5],
                    [-1, 9, 11],
                ],
                1, 2,
                [
                    [1, 4],
                    [-1, 9],
                ],
            ],
            [
                [
                    [1, 4, 7],
                    [3, 0, 5],
                    [-1, 9, 11],
                ],
                2, 0,
                [
                    [4, 7],
                    [0, 5],
                ],
            ],
            [
                [
                    [1, 4, 7],
                    [3, 0, 5],
                    [-1, 9, 11],
                ],
                2, 1,
                [
                    [1, 7],
                    [3, 5],
                ],
            ],
            [
                [
                    [1, 4, 7],
                    [3, 0, 5],
                    [-1, 9, 11],
                ],
                2, 2,
                [
                    [1, 4],
                    [3, 0],
                ],
            ],
        ];
    }

    public function testMinorMatrixExceptionBadRow()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->minorMatrix(4, 1);
    }

    public function testMinorMatrixExceptionBadColumn()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->minorMatrix(1, 4);
    }

    public function testMinorMatrixExceptionNotSquare()
    {
        $A = MatrixFactory::create([
            [1, 2, 3, 4],
            [2, 3, 4, 4],
            [3, 4, 5, 4],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->minorMatrix(1, 1);
    }

    /**
     * @testCase     leadingPrincipalMinor returns the expected SquareMatrix
     * @dataProvider dataProviderForLeadingPrincipalMinor
     * @param        array $A
     * @param        int $k
     * @param        array $R
     */
    public function testLeadingPrincipalMinor(array $A, int $k, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->leadingPrincipalMinor($k));
    }

    public function dataProviderForLeadingPrincipalMinor(): array
    {
        return [
            [
                [
                    [1],
                ],
                1,
                [
                    [1],
                ],
            ],
            [
                [
                    [1, 2],
                    [4, 5],
                ],
                1,
                [
                    [1],
                ],
            ],
            [
                [
                    [1, 2],
                    [4, 5],
                ],
                2,
                [
                    [1, 2],
                    [4, 5],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ],
                1,
                [
                    [1],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ],
                2,
                [
                    [1, 2],
                    [4, 5],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ],
                3,
                [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ],
            ],
            [
                [
                    [1, 2, 3, 4],
                    [5, 6, 7, 8],
                    [9, 0, 1, 2],
                    [3, 4, 5, 6],
                ],
                1,
                [
                    [1],
                ],
            ],
            [
                [
                    [1, 2, 3, 4],
                    [5, 6, 7, 8],
                    [9, 0, 1, 2],
                    [3, 4, 5, 6],
                ],
                2,
                [
                    [1, 2],
                    [5, 6],
                ],
            ],
            [
                [
                    [1, 2, 3, 4],
                    [5, 6, 7, 8],
                    [9, 0, 1, 2],
                    [3, 4, 5, 6],
                ],
                3,
                [
                    [1, 2, 3],
                    [5, 6, 7],
                    [9, 0, 1],
                ],
            ],
            [
                [
                    [1, 2, 3, 4],
                    [5, 6, 7, 8],
                    [9, 0, 1, 2],
                    [3, 4, 5, 6],
                ],
                4,
                [
                    [1, 2, 3, 4],
                    [5, 6, 7, 8],
                    [9, 0, 1, 2],
                    [3, 4, 5, 6],
                ],
            ],
        ];
    }

    /**
     * @testCase leadingPrincipalMinor throws an OutOfBoundsException when k is < 0.
     */
    public function testLeadingPrincipalMinorExceptionKLessThanZero()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        $this->expectException(Exception\OutOfBoundsException::class);
        $R = $A->leadingPrincipalMinor(-1);
    }

    /**
     * @testCase leadingPrincipalMinor throws an OutOfBoundsException when k is > n.
     */
    public function testLeadingPrincipalMinorExceptionKGreaterThanN()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        $this->expectException(Exception\OutOfBoundsException::class);
        $R = $A->leadingPrincipalMinor($A->getN() + 1);
    }

    /**
     * @testCase leadingPrincipalMinor throws a MatrixException if the Matrix is not square.
     */
    public function testLeadingPrincipalMinorExceptionMatrixNotSquare()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
            [4, 5, 6],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $R = $A->leadingPrincipalMinor(2);
    }

    /**
     * @dataProvider dataProviderForMinor
     */
    public function testMinor(array $A, int $mᵢ, int $nⱼ, $Mᵢⱼ)
    {
        $A = MatrixFactory::create($A);

        $this->assertEquals($Mᵢⱼ, $A->minor($mᵢ, $nⱼ));
    }

    public function dataProviderForMinor()
    {
        return [
            [
                [
                    [1, 4, 7],
                    [3, 0, 5],
                    [-1, 9, 11],
                ],
                0, 0, -45
            ],
            [
                [
                    [1, 4, 7],
                    [3, 0, 5],
                    [-1, 9, 11],
                ],
                0, 1, 38
            ],
            [
                [
                    [1, 4, 7],
                    [3, 0, 5],
                    [-1, 9, 11],
                ],
                1, 2, 13
            ],
        ];
    }

    public function testMinorExceptionBadRow()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->minor(4, 1);
    }

    public function testMinorExceptionBadColumn()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->minor(1, 4);
    }

    public function testMinorExceptionNotSquare()
    {
        $A = MatrixFactory::create([
            [1, 2, 3, 4],
            [2, 3, 4, 4],
            [3, 4, 5, 4],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->minor(1, 1);
    }

    /**
     * @dataProvider dataProviderForCofactor
     */
    public function testCofactor(array $A, int $mᵢ, int $nⱼ, $Cᵢⱼ)
    {
        $A = MatrixFactory::create($A);

        $this->assertEquals($Cᵢⱼ, $A->cofactor($mᵢ, $nⱼ));
    }

    public function dataProviderForCofactor()
    {
        return [
            [
                [
                    [1, 4, 7],
                    [3, 0, 5],
                    [-1, 9, 11],
                ],
                0, 0, -45
            ],
            [
                [
                    [1, 4, 7],
                    [3, 0, 5],
                    [-1, 9, 11],
                ],
                0, 1, -38
            ],
            [
                [
                    [1, 4, 7],
                    [3, 0, 5],
                    [-1, 9, 11],
                ],
                1, 2, -13
            ],
        ];
    }

    public function testCofactorExceptionBadRow()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->cofactor(4, 1);
    }

    public function testCofactorExceptionBadColumn()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->cofactor(1, 4);
    }

    public function testCofactorExceptionNotSquare()
    {
        $A = MatrixFactory::create([
            [1, 2, 3, 4],
            [2, 3, 4, 4],
            [3, 4, 5, 4],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->cofactor(1, 1);
    }

    /**
     * @dataProvider dataProviderForCofactorMatrix
     */
    public function testCofactorMatrix(array $A, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = new SquareMatrix($R);

        $this->assertEquals($R, $A->cofactorMatrix());
    }

    public function dataProviderForCofactorMatrix()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [0, 4, 5],
                    [1, 0, 6],
                ],
                [
                    [24, 5, -4],
                    [-12, 3, 2],
                    [-2, -5, 4],
                ],
            ],
            [
                [
                    [-1, 2, 3],
                    [1, 5, 6],
                    [0, 4, 3],
                ],
                [
                    [-9, -3, 4],
                    [6, -3, 4],
                    [-3, 9, -7],
                ],
            ],
            [
                [
                    [3, 65, 23],
                    [98, 35, 86],
                    [5, 2, 10],
                ],
                [
                    [178, -550, 21],
                    [-604, -85, 319],
                    [4785, 1996, -6265],
                ],
            ],
        ];
    }

    public function testCofactorMatrixExceptionNotSquare()
    {
        $A = MatrixFactory::create([
            [1, 2, 3, 4],
            [2, 3, 4, 4],
            [3, 4, 5, 4],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $A->cofactorMatrix();
    }

    /**
     * @dataProvider dataProviderForSampleMean
     */
    public function testSampleMean(array $A, array $M)
    {
        $A = MatrixFactory::create($A);
        $M = new Vector($M);

        $this->assertEquals($M, $A->sampleMean());
    }

    public function dataProviderForSampleMean()
    {
        return [
            // Test data from: http://www.maths.manchester.ac.uk/~mkt/MT3732%20(MVA)/Intro.pdf
            [
                [
                    [4, -1, 3],
                    [1, 3, 5],
                ],
                [2, 3],
            ],
            // Test data from Linear Algebra and Its Aplications (Lay)
            [
                [
                    [1, 4, 7, 8],
                    [2, 2, 8, 4],
                    [1, 13, 1, 5],
                ],
                [5, 4, 5],
            ],
            [
                [
                    [19, 22, 6, 3, 2, 20],
                    [12, 6, 9, 15, 13, 5],
                ],
                [12, 10],
            ],
            [
                [
                    [1, 5, 2, 6, 7, 3],
                    [3, 11, 6, 8, 15, 11],
                ],
                [4, 9],
            ],
            // Test data from: http://www.itl.nist.gov/div898/handbook/pmc/section5/pmc541.htm
            [
                [
                    [4, 4.2, 3.9, 4.3, 4.1],
                    [2, 2.1, 2, 2.1, 2.2],
                    [.6, .59, .58, .62, .63]
                ],
                [
                    4.10, 2.08, 0.604
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForMeanDeviation
     */
    public function testMeanDeviation(array $A, array $B)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);

        $this->assertEquals($B, $A->meanDeviation());
    }

    public function dataProviderForMeanDeviation()
    {
        return [
            // Test data from: http://www.maths.manchester.ac.uk/~mkt/MT3732%20(MVA)/Intro.pdf
            [
                [
                    [4, -1, 3],
                    [1, 3, 5],
                ],
                [
                    [2, -3, 1],
                    [-2, 0, 2],
                ],
            ],
            // Test data from Linear Algebra and Its Aplications (Lay)
            [
                [
                    [1, 4, 7, 8],
                    [2, 2, 8, 4],
                    [1, 13, 1, 5],
                ],
                [
                    [-4, -1, 2, 3],
                    [-2, -2, 4, 0],
                    [-4, 8, -4, 0],
                ],
            ],
            [
                [
                    [19, 22, 6, 3, 2, 20],
                    [12, 6, 9, 15, 13, 5],
                ],
                [
                    [7, 10, -6, -9, -10, 8],
                    [2, -4, -1, 5, 3, -5],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForCovarianceMatrix
     */
    public function testCovarianceMatrix(array $A, array $S)
    {
        $A = MatrixFactory::create($A);

        $this->assertEquals($S, $A->covarianceMatrix()->getMatrix(), '', 0.0001);
    }

    public function dataProviderForCovarianceMatrix()
    {
        return [
            // Test data from Linear Algebra and Its Aplications (Lay)
            [
                [
                    [1, 4, 7, 8],
                    [2, 2, 8, 4],
                    [1, 13, 1, 5],
                ],
                [
                    [10, 6, 0],
                    [6, 8, -8],
                    [0, -8, 32],
                ],
            ],
            [
                [
                    [19, 22, 6, 3, 2, 20],
                    [12, 6, 9, 15, 13, 5],
                ],
                [
                    [86, -27],
                    [-27, 16],
                ],
            ],
            // Test data from: http://www.itl.nist.gov/div898/handbook/pmc/section5/pmc541.htm
            [
                [
                    [4, 4.2, 3.9, 4.3, 4.1],
                    [2, 2.1, 2, 2.1, 2.2],
                    [.6, .59, .58, .62, .63]
                ],
                [
                    [0.025, 0.0075, 0.00175],
                    [0.0075, 0.007, 0.00135],
                    [0.00175, 0.00135, 0.00043],
                ],
            ],
            [
                [
                    [2.5, 0.5, 2.2, 1.9, 3.1, 2.3, 2, 1, 1.5, 1.1],
                    [2.4, 0.7, 2.9, 2.2, 3.0, 2.7, 1.6, 1.1, 1.6, 0.9],
                ],
                [
                    [0.616555556, 0.615444444],
                    [0.615444444, 0.716555556],
                ],
            ],
            // Test data from: https://www.mathworks.com/help/matlab/ref/cov.html
            [
                [
                    [5, 1, 4],
                    [0, -5, 9],
                    [3, 7, 8],
                    [7, 3, 10],
                ],
                [
                    [4.3333, 8.8333, -3.0000, 5.6667],
                    [8.8333, 50.3333, 6.5000, 24.1667],
                    [-3.0000, 6.5000, 7.0000, 1.0000],
                    [5.6667, 24.1667, 1.0000, 12.3333],
                ],
            ],
            // Test data from: http://stats.seandolinar.com/making-a-covariance-matrix-in-r/
            [
                [
                    [1, 2, 3, 4, 5, 6],
                    [2, 3, 5, 6, 1, 9],
                    [3, 5, 5, 5, 10, 8],
                    [10, 20, 30,40, 50, 55],
                    [7, 8, 9, 4, 6, 10],
                ],
                [
                    [ 3.5,  3.000000,  4.0,  32.500000, 0.400000],
                    [ 3.0,  8.666667,  0.4,  25.333333, 2.466667],
                    [ 4.0,  0.400000,  6.4,  38.000000, 0.400000],
                    [32.5, 25.333333, 38.0, 304.166667, 1.333333],
                    [ 0.4,  2.466667,  0.4,   1.333333, 4.666667],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForEigenvalues
     */
    public function testEigenvalues(array $A, array $S)
    {
        $A = MatrixFactory::create($A);
        $this->assertEquals($S, $A->eigenvalues()->getMatrix(), '', 0.0001);
    }
    public function dataProviderForEigenvalues()
    {
        return [
            // Test data from Linear Algebra and Its Aplications (Lay)
            [
                [
                    [0, 1],
                    [-2, -3],
                ],
                [
                    [-1, 0],
                    [0, -2],
                ],
            ],
        ];
    }
}
