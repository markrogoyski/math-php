<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Exception;

class MatrixTest extends \PHPUnit\Framework\TestCase
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
     * @testCase constructor
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(Matrix::class, $this->matrix);
    }

    /**
     * @testCase Implemented interfaces
     */
    public function testInterfaces()
    {
        $this->assertInstanceOf(\ArrayAccess::class, $this->matrix);
        $this->assertInstanceOf(\JsonSerializable::class, $this->matrix);
    }

    /**
     * @testCase constructor throws Exception\MatrixException if the number of columns is not consistent
     */
    public function testConstructorExceptionNCountDiffers()
    {
        $A = [
            [1, 2, 3],
            [2, 3, 4, 5],
            [3, 4, 5],
        ];
        $this->expectException(Exception\MatrixException::class);
        $matrix = MatrixFactory::create($A);
    }

    /**
     * @testCase constructor throws Exception\BadDataException if the number of columns is not consistent
     * @return [type] [description]
     */
    public function testRawConstructorExceptionNCountDiffers()
    {
        $A = [
            [1, 2, 3],
            [2, 3, 4, 5],
            [3, 4, 5],
        ];
        $this->expectException(Exception\BadDataException::class);
        $matrix = new Matrix($A);
    }

    /**
     * @testCase getMatrix returns the expected array representation of the matrix
     */
    public function testGetMatrix()
    {
        $this->assertEquals($this->A, $this->matrix->getMatrix());
    }

    /**
     * @testCase     getM returns the number of rows
     * @dataProvider dataProviderForGetM
     * @param        array $A
     * @param        int   $m
     */
    public function testGetM(array $A, int $m)
    {
        $matrix = MatrixFactory::create($A);
        $this->assertEquals($m, $matrix->getM());
    }

    public function dataProviderForGetM(): array
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
     * @testCase     getN returns the number of columns
     * @dataProvider dataProviderForGetN
     * @param        array $A
     * @param        int   $n
     */
    public function testGetN(array $A, int $n)
    {
        $matrix = MatrixFactory::create($A);
        $this->assertEquals($n, $matrix->getN());
    }

    public function dataProviderForGetN(): array
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

    /**
     * @testCase getRow returns the expected row as an array
     */
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

    /**
     * @testCase getRow throws Exception\MatrixException if the row does not exist
     */
    public function testGetRowException()
    {
        $this->expectException(Exception\MatrixException::class);
        $this->matrix->getRow(8);
    }

    /**
     * @testCase getColumn returns the expected column as an array
     */
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

    /**
     * @testCase getColumn throws Exception\MatrixException if the column does not exist
     */
    public function testGetColumnException()
    {
        $this->expectException(Exception\MatrixException::class);
        $this->matrix->getColumn(8);
    }

    /**
     * @testCase get returns the expected element as a scalar
     */
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

    /**
     * @testCase get throws Exception\MatrixException if the row does not exist
     */
    public function testGetExceptionRow()
    {
        $this->expectException(Exception\MatrixException::class);
        $this->matrix->get(8, 1);
    }

    /**
     * @testCase get throws Exception\MatrixException if the column does not exist
     */
    public function testGetExceptionColumn()
    {
        $this->expectException(Exception\MatrixException::class);
        $this->matrix->get(1, 8);
    }

    /**
     * @testCase asVectors returns the matrix represented as an array of Vector objects
     */
    public function testAsVectors()
    {
        $A = new Matrix([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ]);

        $expected = [
            new Vector([1, 4, 7]),
            new Vector([2, 5, 8]),
            new Vector([3, 6, 9]),
        ];

        $this->assertEquals($expected, $A->asVectors());
    }

    /**
     * @testCase Matrix implements \ArrayAccess
     */
    public function testArrayAccessInterfaceOffsetGet()
    {
        $A = [
            [1, 2, 3],
            [2, 3, 4],
            [4, 5, 6],
        ];
        $matrix = MatrixFactory::create($A);

        $this->assertInstanceOf(\ArrayAccess::class, $matrix);

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

    /**
     * @testCase Matrix implements \ArrayAccess
     */
    public function testArrayAccessInterfaceOffsetSet()
    {
        $this->expectException(Exception\MatrixException::class);
        $this->matrix[0] = [4, 3, 5];
        $this->assertTrue($this->matrix->offsetExists(0));
    }

    /**
     * @testCase Matrix implements \ArrayAccess
     */
    public function testArrayAccessInterfaceOffExists()
    {
        $this->assertTrue($this->matrix->offsetExists(0));
    }

    /**
     * @testCase Matrix implements \ArrayAccess
     */
    public function testArrayAccessOffsetUnsetException()
    {
        $this->expectException(Exception\MatrixException::class);
        unset($this->matrix[0]);
    }

    /**
     * @testCase __toStrint returns the expected string representation of the matrix
     */
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
     * @testCase     getDiagonalElements
     * @dataProvider dataProviderForGetDiagonalElements
     * @param        array $A
     * @param        array $R
     */
    public function testGetDiagonalElements(array $A, array $R)
    {
        $A = MatrixFactory::create($A);

        $this->assertEquals($R, $A->getDiagonalElements());
    }

    public function dataProviderForGetDiagonalElements(): array
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
            [
                [
                    [1, 2, 3, 4],
                    [2, 3, 4, 5],
                    [3, 4, 5, 6],
                    [4, 5, 6, 7],
                ],
                [1, 3, 5, 7],
            ],
        ];
    }

    /**
     * @testCase     getSuperdiagonalElements
     * @dataProvider dataProviderForGetSuperdiagonalElements
     * @param        array $A
     * @param        array $R
     */
    public function testGetSuperdiagonalElements(array $A, array $R)
    {
        $A = MatrixFactory::create($A);

        $this->assertEquals($R, $A->getSuperdiagonalElements());
    }

    public function dataProviderForGetSuperdiagonalElements(): array
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
                [],
            ],
            [
                [
                    [1, 2],
                    [4, 3],
                ],
                [2],
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [4, 5, 6],
                ],
                [2, 4],
            ],
            [
                [
                    [1, 2, 3, 4],
                    [2, 3, 4, 5],
                    [4, 5, 6, 7],
                    [3, 4, 5, 6],
                ],
                [2, 4, 7],
            ],
        ];
    }

    /**
     * @testCase     getSubdiagonalElements
     * @dataProvider dataProviderForGetSubdiagonalElements
     * @param        array $A
     * @param        array $R
     */
    public function testGetSubdiagonalElements(array $A, array $R)
    {
        $A = MatrixFactory::create($A);

        $this->assertEquals($R, $A->getSubdiagonalElements());
    }

    public function dataProviderForGetSubdiagonalElements(): array
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
                [],
            ],
            [
                [
                    [1, 2],
                    [4, 3],
                ],
                [4],
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [4, 5, 6],
                ],
                [2, 5],
            ],
            [
                [
                    [1, 2, 3, 4],
                    [2, 3, 4, 5],
                    [4, 5, 6, 7],
                    [3, 4, 5, 6],
                ],
                [2, 5, 5],
            ],
        ];
    }

    /**
     * @testCase     Matrix implements \JsonSerializable
     * @dataProvider dataProviderForJsonSerialize
     * @param        array $A
     * @param        string $json
     */
    public function testJsonSerialize(array $A, string $json)
    {
        $A = MatrixFactory::create($A);

        $this->assertEquals($json, json_encode($A));
    }

    public function dataProviderForJsonSerialize(): array
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
