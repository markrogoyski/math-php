<?php
namespace Math\LinearAlgebra;

class MatrixTest extends \PHPUnit_Framework_TestCase
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

    public function testConstructor()
    {
        $this->assertInstanceOf('Math\LinearAlgebra\Matrix', $this->matrix);
    }

    public function testConstructorExceptionNCountDiffers()
    {
        $A = [
            [1, 2, 3],
            [2, 3, 4, 5],
            [3, 4, 5],
        ];
        $this->setExpectedException('\Exception');
        $matrix = new Matrix($A);
    }

    public function testGetMatrix()
    {
        $this->assertEquals($this->A, $this->matrix->getMatrix());
    }

    /**
     * @dataProvider dataProviderForGetM
     */
    public function testGetM(array $A, int $m)
    {
        $matrix = new Matrix($A);
        $this->assertEquals($m, $matrix->getM());
    }

    public function dataProviderForGetM()
    {
        return [
            [
                [[1]], 1
            ], 
            [
                [
                    [1, 2],
                    [2, 3],
                ], 2
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                    [3, 4],
                ], 3
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                    [3, 4],
                    [4, 5],
                ], 4
            ],
            [
                [
                    [1, 2, 0],
                    [2, 3, 0],
                    [3, 4, 0],
                    [4, 5, 0],
                ], 4
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForGetN
     */
    public function testGetN(array $A, int $n)
    {
        $matrix = new Matrix($A);
        $this->assertEquals($n, $matrix->getN());
    }

    public function dataProviderForGetN()
    {
        return [
            [
                [[1]], 1
            ], 
            [
                [
                    [1, 2],
                    [2, 3],
                ], 2
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                    [3, 4],
                ], 2
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                    [3, 4],
                    [4, 5],
                ], 2
            ],
            [
                [
                    [1, 2, 0],
                    [2, 3, 0],
                    [3, 4, 0],
                    [4, 5, 0],
                ], 3
            ],
        ];
    }

    public function testGetRow()
    {
        $A = [
            [1, 2, 3],
            [2, 3, 4],
            [4, 5, 6],
        ];
        $matrix = new Matrix($A);

        $this->assertEquals([1, 2, 3], $matrix->getRow(0));
        $this->assertEquals([2, 3, 4], $matrix->getRow(1));
        $this->assertEquals([4, 5, 6], $matrix->getRow(2));
    }

    public function testGetRowException()
    {
        $this->setExpectedException('\Exception');
        $this->matrix->getRow(8);
    }

    public function testGetColumn()
    {
        $A = [
            [1, 2, 3],
            [2, 3, 4],
            [4, 5, 6],
        ];
        $matrix = new Matrix($A);

        $this->assertEquals([1, 2, 4], $matrix->getColumn(0));
        $this->assertEquals([2, 3, 5], $matrix->getColumn(1));
        $this->assertEquals([3, 4, 6], $matrix->getColumn(2));
    }

    public function testGetColumnException()
    {
        $this->setExpectedException('\Exception');
        $this->matrix->getColumn(8);
    }

    public function testGet()
    {
        $A = [
            [1, 2, 3],
            [2, 3, 4],
            [4, 5, 6],
        ];
        $matrix = new Matrix($A);

        $this->assertEquals(1, $matrix->get(0, 0));
        $this->assertEquals(2, $matrix->get(0, 1));
        $this->assertEquals(3, $matrix->get(0, 2));

        $this->assertEquals(2, $matrix->get(1, 0));
        $this->assertEquals(3, $matrix->get(1, 1));
        $this->assertEquals(4, $matrix->get(1, 2));

        $this->assertEquals(4, $matrix->get(2, 0));
        $this->assertEquals(5, $matrix->get(2, 1));
        $this->assertEquals(6, $matrix->get(2, 2));
    }

    public function testGetExceptionRow()
    {
        $this->setExpectedException('\Exception');
        $this->matrix->get(8, 1);
    }

    public function testGetExceptionColumn()
    {
        $this->setExpectedException('\Exception');
        $this->matrix->get(1, 8);
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

    public function dataProviderForAdd() {
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

    public function dataProviderForDirectSum() {
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

    public function dataProviderForSubtract() {
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

    public function dataProviderForMultiply() {
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

    public function testArrayAccessInterfaceOffsetGet()
    {
        $A = [
            [1, 2, 3],
            [2, 3, 4],
            [4, 5, 6],
        ];
        $matrix = new Matrix($A);

        $this->assertInstanceOf('ArrayAccess', $matrix);

        $this->assertEquals([1, 2, 3], $matrix[0]);
        $this->assertEquals([2, 3, 4], $matrix[1]);
        $this->assertEquals([4, 5, 6], $matrix[2]);

        $this->assertEquals(1, $matrix[0][0]);
        $this->assertEquals(2, $matrix[0][1]);
        $this->assertEquals(3, $matrix[0][2]);

        $this->assertEquals(2, $matrix[1][0]);
        $this->assertEquals(3, $matrix[1][1]);
        $this->assertEquals(4, $matrix[1][2]);

        $this->assertEquals(4, $matrix[2][0]);
        $this->assertEquals(5, $matrix[2][1]);
        $this->assertEquals(6, $matrix[2][2]);
    }

    public function testArrayAccessInterfaceOffsetSet()
    {
        $this->setExpectedException('\Exception');
        $this->matrix[0] = [4, 3, 5];
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

    /**
     * @dataProvider dataProviderForIdentity
     */
    public function testIdentity(int $n, $x, array $R)
    {
        $R = new Matrix($R);
        $this->assertEquals($R, Matrix::identity($n, $x));
    }

    public function dataProviderForIdentity()
    {
        return [
            [
                0, 1, [],
            ],
            [
                1, 1, [[1]],
            ],
            [
                2, 1, [
                    [1, 0],
                    [0, 1],
                ]
            ],
            [
                3, 1, [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1]
                ]
            ],
            [
                4, 1, [
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 1, 0],
                    [0, 0, 0, 1],
                ]
            ],
            [
                4, 5, [
                    [5, 0, 0, 0],
                    [0, 5, 0, 0],
                    [0, 0, 5, 0],
                    [0, 0, 0, 5],
                ]
            ],

        ];
    }

    /**
     * @dataProvider dataProviderForIsSquare
     */
    public function testIsSquare(array $A)
    {
        $A = new Matrix($A);
        $this->assertTrue($A->isSquare());
    }

    public function dataProviderForIsSquare()
    {
        return [
            [
                [],
            ],
            [
                [[1]]
            ],
            [
                [
                  [1, 2],
                  [2, 3],
                ]
            ],
            [
                [
                  [1, 2, 3],
                  [4, 5, 6],
                  [7, 8, 9],
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForIsNotSquare
     */
    public function testIsNotSquare(array $A)
    {
        $A = new Matrix($A);
        $this->assertFalse($A->isSquare());
    }

    public function dataProviderForIsNotSquare()
    {
        return [
            [
                [[1,2]]
            ],
            [
                [
                  [1, 2, 4],
                  [2, 3, 5],
                ]
            ],
            [
                [
                  [1, 2, 3, 5],
                  [4, 5, 6, 5],
                  [7, 8, 9, 5],
                ]
            ],
            [
                [
                  [1, 2, 3],
                  [4, 5, 6],
                  [7, 8, 9],
                  [1, 2, 3],
                ]
            ],
        ];
    }

    public function testMap()
    {
        $A = new Matrix([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ]);

        $doubler = function($x) {
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
     * @dataProvider dataProviderForZero
     */
    public function testZero($m, $n, array $R)
    {
        $R = new Matrix($R);
        $this->assertEquals($R, Matrix::zero($m, $n));
    }

    public function dataProviderForZero()
    {
        return [
            [
                1, 1, [[0]]
            ],
            [
                2, 2, [
                    [0, 0],
                    [0, 0],
                ]
            ],
            [
                3, 3, [
                    [0, 0, 0],
                    [0, 0, 0],
                    [0, 0, 0],
                ]
            ],
            [
                2, 3, [
                    [0, 0, 0],
                    [0, 0, 0],
                ]
            ],
            [
                3, 2, [
                    [0, 0],
                    [0, 0],
                    [0, 0],
                ]
            ]
        ];
    }

    public function testZeroExceptionRowsLessThanOne()
    {
        $this->setExpectedException('\Exception');
        Matrix::zero(0, 2);
    }

    /**
     * @dataProvider dataProviderForOne
     */
    public function testOne($m, $n, array $R)
    {
        $R = new Matrix($R);
        $this->assertEquals($R, Matrix::one($m, $n));
    }

    public function dataProviderForOne()
    {
        return [
            [
                1, 1, [[1]]
            ],
            [
                2, 2, [
                    [1, 1],
                    [1, 1],
                ]
            ],
            [
                3, 3, [
                    [1, 1, 1],
                    [1, 1, 1],
                    [1, 1, 1],
                ]
            ],
            [
                2, 3, [
                    [1, 1, 1],
                    [1, 1, 1],
                ]
            ],
            [
                3, 2, [
                    [1, 1],
                    [1, 1],
                    [1, 1],
                ]
            ]
        ];
    }

    public function testOneExceptionRowsLessThanOne()
    {
        $this->setExpectedException('\Exception');
        Matrix::one(0, 2);
    }

    /**
     * @dataProvider dataProviderForRowInterchange
     */
    public function testRowInterchange(array $A, int $mᵢ, int $mⱼ, array $R)
    {
        $A = new Matrix($A);
        $R = new Matrix($R);

        $this->assertEquals($R, $A->rowInterchange($mᵢ, $mⱼ));
    }

    public function dataProviderForRowInterchange()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 1,
                [
                    [2, 3, 4],
                    [1, 2, 3],
                    [3, 4, 5],
                ]
            ],
            [
                [
                    [5, 5],
                    [4, 4],
                    [2, 7],
                    [9, 0],
                ], 2, 3,
                [
                    [5, 5],
                    [4, 4],
                    [9, 0],
                    [2, 7],
                ]
            ],
            [
                [
                    [5, 5],
                    [4, 4],
                    [2, 7],
                    [9, 0],
                ], 1, 2,
                [
                    [5, 5],
                    [2, 7],
                    [4, 4],
                    [9, 0],
                ]
            ]
        ];
    }

    public function testRowInterchangeExceptionRowGreaterThanM()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->setExpectedException('\Exception');
        $A->rowInterchange(4, 5);
    }

    /**
     * @dataProvider dataProviderForRowMultiply
     */
    public function testRowMultiply(array $A, int $mᵢ, $k, array $R)
    {
        $A = new Matrix($A);
        $R = new Matrix($R);

        $this->assertEquals($R, $A->rowMultiply($mᵢ, $k));
    }

    public function dataProviderForRowMultiply()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 5,
                [
                    [5, 10, 15],
                    [2, 3, 4],
                    [3, 4, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 1, 4,
                [
                    [1, 2, 3],
                    [8, 12, 16],
                    [3, 4, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 2, 8,
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [24, 32, 40],
                ]
            ],
        ];
    }

    public function testRowMultiplyExceptionRowGreaterThanM()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->setExpectedException('\Exception');
        $A->rowMultiply(4, 5);
    }

    public function testRowMultiplyExceptionKIsZero()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->setExpectedException('\Exception');
        $A->rowMultiply(4, 0);
    }

    /**
     * @dataProvider dataProviderForRowAdd
     */
    public function testRowAdd(array $A, int $mᵢ, $mⱼ, int $k, array $R)
    {
        $A = new Matrix($A);
        $R = new Matrix($R);

        $this->assertEquals($R, $A->rowAdd($mᵢ, $mⱼ, $k));
    }

    public function dataProviderForRowAdd()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 1, 2,
                [
                    [1, 2, 3],
                    [4, 7, 10],
                    [3, 4, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 1, 2, 3,
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [9, 13, 17],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 2, 4,
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [7, 12, 17],
                ]
            ],
        ];
    }

    public function testRowAddExceptionRowGreaterThanM()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->setExpectedException('\Exception');
        $A->rowAdd(4, 5, 2);
    }

    public function testRowAddExceptionKIsZero()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->setExpectedException('\Exception');
        $A->rowAdd(1, 2, 0);
    }

    /**
     * @dataProvider dataProviderForColulmnInterchange
     */
    public function testColulmnInterchange(array $A, int $nᵢ, int $nⱼ, array $R)
    {
        $A = new Matrix($A);
        $R = new Matrix($R);

        $this->assertEquals($R, $A->columnInterchange($nᵢ, $nⱼ));
    }

    public function dataProviderForColulmnInterchange()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 1,
                [
                    [2, 1, 3],
                    [3, 2, 4],
                    [4, 3, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 1, 2,
                [
                    [1, 3, 2],
                    [2, 4, 3],
                    [3, 5, 4],
                ]
            ],
            [
                [
                    [5, 5],
                    [4, 4],
                    [2, 7],
                    [9, 0],
                ], 0, 1,
                [
                    [5, 5],
                    [4, 4],
                    [7, 2],
                    [0, 9],
                ]
            ],
        ];
    }

    public function testRowInterchangeExceptionColumnGreaterThanN()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->setExpectedException('\Exception');
        $A->columnInterchange(4, 5);
    }

    /**
     * @dataProvider dataProviderForColumnMultiply
     */
    public function testColumnMultiply(array $A, int $nᵢ, $k, array $R)
    {
        $A = new Matrix($A);
        $R = new Matrix($R);

        $this->assertEquals($R, $A->columnMultiply($nᵢ, $k));
    }

    public function dataProviderForColumnMultiply()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 5,
                [
                    [5, 2, 3],
                    [10, 3, 4],
                    [15, 4, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 1, 4,
                [
                    [1, 8, 3],
                    [2, 12, 4],
                    [3, 16, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 2, 8,
                [
                    [1, 2, 24],
                    [2, 3, 32],
                    [3, 4, 40],
                ]
            ],
        ];
    }

    public function testColumnMultiplyExceptionColumnGreaterThanN()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->setExpectedException('\Exception');
        $A->columnMultiply(4, 5);
    }

    public function testColumnMultiplyExceptionKIsZero()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->setExpectedException('\Exception');
        $A->columnMultiply(2, 0);
    }

    /**
     * @dataProvider dataProviderForColumnAdd
     */
    public function testColumnAdd(array $A, int $nᵢ, $nⱼ, int $k, array $R)
    {
        $A = new Matrix($A);
        $R = new Matrix($R);

        $this->assertEquals($R, $A->columnAdd($nᵢ, $nⱼ, $k));
    }

    public function dataProviderForColumnAdd()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 1, 2,
                [
                    [1, 4, 3],
                    [2, 7, 4],
                    [3, 10, 5],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 1, 2, 3,
                [
                    [1, 2, 9],
                    [2, 3, 13],
                    [3, 4, 17],
                ]
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ], 0, 2, 4,
                [
                    [1, 2, 7],
                    [2, 3, 12],
                    [3, 4, 17],
                ]
            ],
        ];
    }


    public function testColumnAddExceptionRowGreaterThanN()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->setExpectedException('\Exception');
        $A->columnAdd(4, 5, 2);
    }

    public function testColumnAddExceptionKIsZero()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $this->setExpectedException('\Exception');
        $A->columnAdd(1, 2, 0);
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
}
