<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Vector;

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
        $this->assertInstanceOf('MathPHP\LinearAlgebra\Matrix', $this->matrix);
    }

    public function testConstructorExceptionNCountDiffers()
    {
        $A = [
            [1, 2, 3],
            [2, 3, 4, 5],
            [3, 4, 5],
        ];
        $this->expectException('MathPHP\Exception\MatrixException');
        $matrix = MatrixFactory::create($A);
    }

    public function testRawConstructorExceptionNCountDiffers()
    {
        $A = [
            [1, 2, 3],
            [2, 3, 4, 5],
            [3, 4, 5],
        ];
        $this->expectException('MathPHP\Exception\BadDataException');
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
        $this->expectException('MathPHP\Exception\MatrixException');
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
        $this->expectException('MathPHP\Exception\MatrixException');
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
        $this->expectException('MathPHP\Exception\MatrixException');
        $this->matrix->get(8, 1);
    }

    public function testGetExceptionColumn()
    {
        $this->expectException('MathPHP\Exception\MatrixException');
        $this->matrix->get(1, 8);
    }

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
        $this->expectException('MathPHP\Exception\MatrixException');
        $this->matrix[0] = [4, 3, 5];
        $this->assertTrue($this->matrix->offsetExists(0));
    }

    public function testArrayAccessInterfaceOffExists()
    {
        $this->assertTrue($this->matrix->offsetExists(0));
    }

    public function testArrayAccessOffsetUnsetException()
    {
        $this->expectException('MathPHP\Exception\MatrixException');
        unset($this->matrix[0]);
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
