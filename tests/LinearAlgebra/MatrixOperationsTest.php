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
        $this->matrix = new Matrix($this->A);
    }

    /**
     * @dataProvider dataProviderForAdd
     */
    public function testAdd(array $A, array $B, array $R)
    {
        $A  = new Matrix($A);
        $B  = new Matrix($B);
        $R  = new Matrix($R);
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
        $A = new Matrix([
            [1, 2],
            [2, 3],
        ]);
        $B = new Matrix([
            [1, 2]
        ]);

        $this->setExpectedException('\Exception');
        $A->add($B);
    }

    public function testAddExceptionColumns()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
        ]);
        $B = new Matrix([
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
        $A  = new Matrix($A);
        $B  = new Matrix($B);
        $R  = new Matrix($R);
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
        $A  = new Matrix($A);
        $B  = new Matrix($B);
        $R  = new Matrix($R);
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
        $A = new Matrix([
            [1, 2],
            [2, 3],
        ]);
        $B = new Matrix([
            [1, 2]
        ]);

        $this->setExpectedException('\Exception');
        $A->subtract($B);
    }

    public function testSubtractExceptionColumns()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
        ]);
        $B = new Matrix([
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
        $A  = new Matrix($A);
        $B  = new Matrix($B);
        $R  = new Matrix($R);
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

    public function testMultiplyExceptionDimensionsDoNotMatch()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
        ]);
        $B = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
        ]);

        $this->setExpectedException('\Exception');
        $A->multiply($B);
    }

    /**
     * @dataProvider dataProviderForScalarMultiply
     */
    public function testScalarMultiply(array $A, $k, array $R)
    {
        $A = new Matrix($A);
        $R = new Matrix($R);

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
        $A = new Matrix([
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
        $A  = new Matrix($A);
        $R  = new Matrix($R);
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
        $A = new Matrix([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ]);

        $doubler = function ($x) {
            return $x * 2;
        };
        $R = $A->map($doubler);

        $E = new Matrix([
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
        $A = new Matrix($A);
        $this->assertEquals($tr, $A->trace());
    }

    public function dataProviderForTrace()
    {
        return [
            [
                [], 0
            ],
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
        $A = new Matrix([
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
        $A = new Matrix($A);
        $R = new Matrix($R);

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
        $A    = new Matrix($A);
        $B    = new Matrix($B);
        $⟮A∣B⟯ = new Matrix($⟮A∣B⟯);

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
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $B = new Matrix([
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
        $C    = new Matrix($C);
        $⟮C∣I⟯ = new Matrix($⟮C∣I⟯);

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

    /**
     * @dataProvider dataProviderForOneNorm
     */
    public function testOneNorm(array $A, $norm)
    {
        $A = new Matrix($A);

        $this->assertEquals($norm, $A->oneNorm(), '', 0.0001);
    }

    public function dataProviderForOneNorm()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 12
            ],
            [
                [
                    [1, 8, 3],
                    [2, 8, 4],
                    [3, 8, 5],
                ], 24
            ],
            [
                [
                    [20, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 25
            ],
            [
                [
                    [-20, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 25
            ],
            [
                [
                    [20, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                    [0, 2, 55],
                ], 67
            ],
            [
                [
                    [20, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                    [0, 2, -55],
                ], 67
            ],
            [
                [
                    [1],
                    [2],
                    [3],
                ], 6
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForInfinityNorm
     */
    public function testInfinityNorm(array $A, $norm)
    {
        $A = new Matrix($A);

        $this->assertEquals($norm, $A->infinityNorm(), '', 0.0001);
    }

    public function dataProviderForInfinityNorm()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 12
            ],
            [
                [
                    [1, 8, 3],
                    [2, 8, 4],
                    [3, 8, 5],
                ], 16
            ],
            [
                [
                    [20, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 25
            ],
            [
                [
                    [-20, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 25
            ],
            [
                [
                    [20, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                    [0, 2, 55],
                ], 57
            ],
            [
                [
                    [20, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                    [0, 2, -55],
                ], 57
            ],
            [
                [
                    [1],
                    [2],
                    [3],
                ], 3
            ],
            [
                [
                    [1, 2, 3],
                ], 6
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForMaxNorm
     */
    public function testMaxNorm(array $A, $norm)
    {
        $A = new Matrix($A);

        $this->assertEquals($norm, $A->maxNorm(), '', 0.0001);
    }

    public function dataProviderForMaxNorm()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 5
            ],
            [
                [
                    [1, 8, 3],
                    [2, 8, 4],
                    [3, 8, 5],
                ], 8
            ],
            [
                [
                    [20, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 20
            ],
            [
                [
                    [-20, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 20
            ],
            [
                [
                    [20, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                    [0, 2, 55],
                ], 55
            ],
            [
                [
                    [20, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                    [0, 2, -55],
                ], 55
            ],
            [
                [
                    [1],
                    [2],
                    [3],
                ], 3
            ],
            [
                [
                    [1, 2, 3],
                ], 3
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForFrobeniusNorm
     */
    public function testFrobeniusNorm(array $A, $norm)
    {
        $A = new Matrix($A);

        $this->assertEquals($norm, $A->frobeniusNorm(), '', 0.0001);
    }

    public function dataProviderForFrobeniusNorm()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 9.643651
            ],
            [
                [
                    [1, 5, 3, 9],
                    [2, 3, 4, 12],
                    [4, 2, 5, 11],
                ], 21.330729
            ],
            [
                [
                    [1, 5, 3],
                    [2, 3, 4],
                    [4, 2, 5],
                    [6, 6, 3],
                ], 13.784049
            ],
        ];
    }

    public function testAugmentIdentityExceptionNotSquare()
    {
        $A = new Matrix([
            [1, 2],
            [2, 3],
            [3, 4],
        ]);

        $this->setExpectedException('\Exception');
        $this->assertEquals($A->augmentIdentity());
    }

    /**
     * @dataProvider dataProviderForHadamardProduct
     */
    public function testHadamardProduct(array $A, array $B, array $A∘B)
    {
        $A   = new Matrix($A);
        $B   = new Matrix($B);
        $A∘B = new Matrix($A∘B);

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
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
        ]);
        $B = new Matrix([
            [1, 2, 3, 4],
            [2, 3, 4, 5],
        ]);

        $this->setExpectedException('\Exception');
        $A->hadamardProduct($B);
    }

    public function testToString()
    {
        $string = $this->matrix->__toString();
        $this->assertTrue(is_string($string));
        $this->assertEquals(
            "[1, 2, 3]\n[2, 3, 4]\n[4, 5, 6]",
            $string
        );
    }

    /**
     * @dataProvider dataProviderForLUDecomposition
     * Unit test data created from online calculator: https://www.easycalculation.com/matrix/lu-decomposition-matrix.php
     */
    public function testLUDecomposition(array $A, array $L, array $U, array $P)
    {
        $A = new Matrix($A);
        $L = new Matrix($L);
        $U = new Matrix($U);
        $P = new Matrix($P);

        $LU = $A->LUDecomposition();

        $this->assertEquals($L, $LU['L'], '', 0.004);
        $this->assertEquals($U, $LU['U'], '', 0.004);
        $this->assertEquals($P, $LU['P'], '', 0.004);
    }

    public function dataProviderForLUDecomposition()
    {
        return [
            [
                [
                    [4, 3],
                    [6, 3],
                ],
                [
                    [1, 0],
                    [0.667, 1],
                ],
                [
                    [6, 3],
                    [0, 1],
                ],
                [
                    [0, 1],
                    [1, 0],
                ],
            ],
            [
                [
                    [1, 3, 5],
                    [2, 4, 7],
                    [1, 1, 0],
                ],
                [
                    [1, 0, 0],
                    [.5, 1, 0],
                    [.5, -1, 1],
                ],
                [
                    [2, 4, 7],
                    [0, 1, 1.5],
                    [0, 0, -2],
                ],
                [
                    [0, 1, 0],
                    [1, 0, 0],
                    [0, 0, 1],
                ]
            ],
            [
                [
                    [1, -2, 3],
                    [2, -5, 12],
                    [0, 2, -10],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0.5, 0.25, 1],
                ],
                [
                    [2, -5, 12],
                    [0, 2, -10],
                    [0, 0, -0.5],
                ],
                [
                    [0, 1, 0],
                    [0, 0, 1],
                    [1, 0, 0],
                ],
            ],
            [
                [
                    [5, 4, 8, 9],
                    [9, 9, 9, 9],
                    [4, 5, 5, 7],
                    [1, 9, 8, 7],
                ],
                [
                    [1, 0, 0, 0],
                    [.556, 1, 0, 0],
                    [.111, -8, 1, 0],
                    [.444, -1, .129, 1],
                ],
                [
                    [9, 9, 9, 9],
                    [0, -1, 3, 4],
                    [0, 0, 31, 38],
                    [0, 0, 0, 2.097],
                ],
                [
                    [0, 1, 0, 0],
                    [1, 0, 0, 0],
                    [0, 0, 0, 1],
                    [0, 0, 1, 0],
                ],
            ],
            [
                [
                    [2, 1, 1, 0],
                    [4, 3, 3, 1],
                    [8, 7, 9, 5],
                    [6, 7, 9, 8],
                ],
                [
                    [1, 0, 0, 0],
                    [0.25, 1, 0, 0],
                    [0.5, 0.667, 1, 0],
                    [0.75, -2.333, 1, 1],
                ],
                [
                    [8, 7, 9, 5],
                    [0, -0.75, -1.25, -1.25],
                    [0, 0, -0.667, -0.667],
                    [0, 0, 0, 2],
                ],
                [
                    [0, 0, 1, 0],
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [11, 9, 24, 2],
                    [1, 5, 2, 6],
                    [3, 17, 18, 1],
                    [2, 5, 7, 1],
                ],
                [
                    [1, 0, 0, 0],
                    [.27273, 1, 0, 0],
                    [.09091, .28750, 1, 0],
                    [.18182, .23125, .00360, 1],
                ],
                [
                    [11, 9, 24, 2],
                    [0, 14.54545, 11.45455, 0.45455],
                    [0, 0, -3.47500, 5.68750],
                    [0, 0, 0, 0.51079],
                ],
                [
                    [1, 0, 0, 0],
                    [0, 0, 1, 0],
                    [0, 1, 0, 0],
                    [0, 0, 0, 1],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForDet
     */
    public function testDet(array $A, $det)
    {
        $A = new Matrix($A);

        $this->assertEquals($det, round($A->det(), 0.1)); // Test calculation
        $this->assertEquals($det, round($A->det(), 0.1)); // Test class attribute
    }

    public function dataProviderForDet()
    {
        return [
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
        ];
    }

    public function testDetExceptionNotSquareMatrix()
    {
        $A = new Matrix([1, 2, 3]);

        $this->setExpectedException('\Exception');
        $A->det();
    }

    /**
     * @dataProvider dataProviderForInverse
     */
    public function testInverse(array $A, array $A⁻¹)
    {
        $A   = new Matrix($A);
        $A⁻¹ = new Matrix($A⁻¹);

        $this->assertEquals($A⁻¹, $A->inverse(), '', 0.001); // Test calculation
        $this->assertEquals($A⁻¹, $A->inverse(), '', 0.001); // Test class attribute
    }

    /**
     * @dataProvider dataProviderForInverse
     */
    public function testMatrixTimesInverseIsIdentity(array $A, array $A⁻¹)
    {
        $A   = new Matrix($A);
        $A⁻¹ = $A->inverse();
        $I   = $A->multiply($A⁻¹);

        $this->assertEquals(Matrix::identity($A->getN()), $I);
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
        $A = new Matrix($A);

        $this->setExpectedException('\Exception');
        $A->inverse();
    }

    public function dataProviderForInverseExceptionNotSquare()
    {
        return [
            [
                [3, 4, 4],
                [6, 8, 5],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForInverseExceptionDetIsZero
     */
    public function testInverseExceptionDetIsZero(array $A)
    {
        $A = new Matrix($A);

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
}
