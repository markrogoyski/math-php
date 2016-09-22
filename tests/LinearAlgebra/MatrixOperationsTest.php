<?php
namespace Math\LinearAlgebra;

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
        $this->assertInstanceOf('Math\LinearAlgebra\Matrix', $R2);
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

        $this->setExpectedException('\Exception');
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

        $this->setExpectedException('\Exception');
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
        $this->assertInstanceOf('Math\LinearAlgebra\Matrix', $R2);
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
     * @dataProvider dataProviderForSubtract
     */
    public function testSubtract(array $A, array $B, array $R)
    {
        $A  = MatrixFactory::create($A);
        $B  = MatrixFactory::create($B);
        $R  = MatrixFactory::create($R);
        $R2 = $A->subtract($B);
        $this->assertEquals($R, $R2);
        $this->assertInstanceOf('Math\LinearAlgebra\Matrix', $R2);
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

        $this->setExpectedException('\Exception');
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

        $this->setExpectedException('\Exception');
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
        $this->assertInstanceOf('Math\LinearAlgebra\Matrix', $R2);
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
        $this->assertInstanceOf('Math\LinearAlgebra\Matrix', $R2);
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

        $this->setExpectedException('\Exception');
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

        $this->setExpectedException('\Exception');
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

    public function testScalarMultiplyExceptionKNotNumber()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
        ]);

        $this->setExpectedException('\Exception');
        $A->scalarMultiply('k');
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
        $this->setExpectedException('\Exception');
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

        $this->setExpectedException('\Exception');
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

        $this->setExpectedException('\Exception');
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

        $this->setExpectedException('\Exception');
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

        $this->setExpectedException('\Exception');
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

        $this->setExpectedException('\Exception');
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

        $this->setExpectedException('\Exception');
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

        $this->setExpectedException('\Exception');
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

        $this->setExpectedException('\Exception');
        $A->minorMatrix(4, 1);
    }

    public function testMinorMatrixExceptionBadColumn()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        $this->setExpectedException('\Exception');
        $A->minorMatrix(1, 4);
    }

    public function testMinorMatrixExceptionNotSquare()
    {
        $A = MatrixFactory::create([
            [1, 2, 3, 4],
            [2, 3, 4, 4],
            [3, 4, 5, 4],
        ]);

        $this->setExpectedException('\Exception');
        $A->minorMatrix(1, 1);
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

        $this->setExpectedException('\Exception');
        $A->minor(4, 1);
    }

    public function testMinorExceptionBadColumn()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        $this->setExpectedException('\Exception');
        $A->minor(1, 4);
    }

    public function testMinorExceptionNotSquare()
    {
        $A = MatrixFactory::create([
            [1, 2, 3, 4],
            [2, 3, 4, 4],
            [3, 4, 5, 4],
        ]);

        $this->setExpectedException('\Exception');
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

        $this->setExpectedException('\Exception');
        $A->cofactor(4, 1);
    }

    public function testCofactorExceptionBadColumn()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        $this->setExpectedException('\Exception');
        $A->cofactor(1, 4);
    }

    public function testCofactorExceptionNotSquare()
    {
        $A = MatrixFactory::create([
            [1, 2, 3, 4],
            [2, 3, 4, 4],
            [3, 4, 5, 4],
        ]);

        $this->setExpectedException('\Exception');
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

        $this->setExpectedException('\Exception');
        $A->cofactorMatrix();
    }
}
