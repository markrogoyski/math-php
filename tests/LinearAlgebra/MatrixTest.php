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
        $this->matrix = MatrixFactory::create($this->A);
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
        $matrix = MatrixFactory::create($A);
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
        $matrix = MatrixFactory::create($A);
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
        $matrix = MatrixFactory::create($A);
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
        $matrix = MatrixFactory::create($A);

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
        $matrix = MatrixFactory::create($A);

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
        $matrix = MatrixFactory::create($A);

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

    public function testArrayAccessInterfaceOffsetGet()
    {
        $A = [
            [1, 2, 3],
            [2, 3, 4],
            [4, 5, 6],
        ];
        $matrix = MatrixFactory::create($A);

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
        $this->assertTrue($this->matrix->offsetExists(0));
    }

    public function testArrayAccessInterfaceOffExists()
    {
        $this->assertTrue($this->matrix->offsetExists(0));
    }

    public function testArrayAccessOffsetUnsetException()
    {
        $this->setExpectedException('\Exception');
        unset($this->matrix[0]);
    }

    /**
     * @dataProvider dataProviderForIsSquare
     */
    public function testIsSquare(array $A)
    {
        $A = MatrixFactory::create($A);
        $this->assertTrue($A->isSquare());
    }

    public function dataProviderForIsSquare()
    {
        return [
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
        $A = MatrixFactory::create($A);
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

    /**
     * @dataProvider dataProviderForIsSymmetric
     */
    public function testIsSymmetric(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isSymmetric());
    }

    public function dataProviderForIsSymmetric()
    {
        return [
            [
                [[1]],
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                ]
            ],
            [
                [
                    [1, 7, 3],
                    [7, 4, -5],
                    [3, -5, 6],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForIsNotSymmetric
     */
    public function testIsNotSymmetric(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isSymmetric());
    }

    public function dataProviderForIsNotSymmetric()
    {
        return [
            [
                [[1, 1]],
            ],
            [
                [
                    [1, 2],
                    [5, 3],
                ]
            ],
            [
                [
                    [1, 7, 3],
                    [7, 4, 5],
                    [-3, -5, 6],
                ],
            ],
            [
                [
                    [1, 2, 3, 4],
                    [1, 2, 3, 4],
                ],
            ],
        ];
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
     * @dataProvider dataProviderForGetDiagonalElements
     */
    public function testGetDiagonalElements(array $A, $R)
    {
        $A = MatrixFactory::create($A);

        $this->assertEquals($R, $A->getDiagonalElements());
    }

    public function dataProviderForGetDiagonalElements()
    {
        return [
            [
                [
                    [1, 2]
                ],
                [],
            ],
            [
                [
                    [1],
                    [2],
                ],
                [],
            ],
            [
                [[1]],
                [1],
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                ],
                [1, 3],
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [1, 3, 5],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForJsonSerialize
     */
    public function testJsonSerialize(array $A, string $json)
    {
        $A = MatrixFactory::create($A);

        $this->assertEquals($json, json_encode($A));
    }

    public function dataProviderForJsonSerialize()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ],
                '[[1,2,3],[4,5,6],[7,8,9]]',
            ],
            [
                [
                    [1],
                ],
                '[[1]]',
            ],
        ];
    }
}
