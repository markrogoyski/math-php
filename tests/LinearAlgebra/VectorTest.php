<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\Vector;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\Exception;

class VectorTest extends \PHPUnit\Framework\TestCase
{

    public function setUp()
    {
        $this->A = [1, 2, 3, 4, 5];
        $this->vector = new Vector($this->A);
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(Vector::class, $this->vector);
    }

    public function testGetVector()
    {
        $this->assertEquals($this->A, $this->vector->getVector());
    }

    /**
     * @dataProvider dataProviderForGetN
     */
    public function testGetN(array $A, int $n)
    {
        $vector = new Vector($A);
        $this->assertEquals($n, $vector->getN());
    }

    public function dataProviderForGetN()
    {
        return [
            [[], 0],
            [[1], 1],
            [[1,2], 2],
            [[1,2,3], 3],
            [[1,2,3,4], 4],
        ];
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->vector->get(0));
        $this->assertEquals(2, $this->vector->get(1));
        $this->assertEquals(3, $this->vector->get(2));
        $this->assertEquals(4, $this->vector->get(3));
        $this->assertEquals(5, $this->vector->get(4));
    }

    public function testGetException()
    {
        $this->expectException(Exception\VectorException::class);
        $this->vector->get(100);
    }

    public function testArrayAccessInterfaceOffsetGet()
    {
        $this->assertEquals(1, $this->vector[0]);
        $this->assertEquals(2, $this->vector[1]);
        $this->assertEquals(3, $this->vector[2]);
        $this->assertEquals(4, $this->vector[3]);
        $this->assertEquals(5, $this->vector[4]);
    }

    public function testArrayAccessInterfaceOffsetSet()
    {
        $this->expectException(Exception\VectorException::class);
        $this->vector[0] = 1;
    }

    public function testArrayAccessOffsetExists()
    {
        $this->assertTrue($this->vector->offsetExists(0));
    }

    public function testArrayAccessOffsetUnsetException()
    {
        $this->expectException(Exception\VectorException::class);
        unset($this->vector[0]);
    }

    public function testToString()
    {
        $A      = new Vector([1, 2, 3]);
        $string = $A->__toString();
        $this->assertTrue(is_string($string));
        $this->assertEquals('[1, 2, 3]', $string);
    }

    /**
     * @dataProvider dataProviderForAsColumnMatrix
     */
    public function testAsColumnMatrix(array $A, array $R)
    {
        $A = new Vector($A);
        $R = new Matrix($R);
        $M = $A->asColumnMatrix();

        $this->assertEquals($R, $M);
    }

    public function dataProviderForAsColumnMatrix()
    {
        return [
            [
                [],
                [],
            ],
            [
                [1],
                [
                    [1],
                ],
            ],
            [
                [1, 2],
                [
                    [1],
                    [2],
                ],
            ],
            [
                [1, 2, 3],
                [
                    [1],
                    [2],
                    [3],
                ],
            ],
            [
                [1, 2, 3, 4],
                [
                    [1],
                    [2],
                    [3],
                    [4],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForAsRowMatrix
     */
    public function testAsRowMatrix(array $A, array $R)
    {
        $A = new Vector($A);
        $R = new Matrix($R);
        $M = $A->asRowMatrix();

        $this->assertEquals($R, $M);
    }

    public function dataProviderForAsRowMatrix()
    {
        return [
            [
                [],
                [
                    [],
                ],
            ],
            [
                [1],
                [
                    [1],
                ],
            ],
            [
                [1, 2],
                [
                    [1, 2],
                ],
            ],
            [
                [1, 2, 3],
                [
                    [1, 2, 3],
                ],
            ],
            [
                [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                [
                    [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForCountable
     */
    public function testCountableInterface(array $A, $n)
    {
        $A = new Vector($A);

        $this->assertEquals($n, count($A));
    }

    public function dataProviderForCountable()
    {
        return [
            [[], 0],
            [[1], 1],
            [[1, 1], 2],
            [[1, 1, 1], 3],
            [[1, 1, 1, 1], 4],
            [[1, 1, 1, 1, 1], 5],
            [[1, 1, 1, 1, 1, 1], 6],
            [[1, 1, 1, 1, 1, 1, 1], 7],
            [[1, 1, 1, 1, 1, 1, 1, 1], 8],
            [[1, 1, 1, 1, 1, 1, 1, 1, 1], 9],
            [[1, 1, 1, 1, 1, 1, 1, 1, 1, 1], 10],
        ];
    }

    /**
     * @dataProvider dataProviderForJsonSerializable
     */
    public function testJsonSerializable(array $A, string $json)
    {
        $A    = new Vector($A);

        $this->assertEquals($json, json_encode($A));
    }

    public function dataProviderForJsonSerializable()
    {
        return [
            [
                [],
                '[]',
            ],
            [
                [1],
                '[1]',
            ],
            [
                [1, 2, 3],
                '[1,2,3]',
            ],
        ];
    }

    public function testInterfaces()
    {
        $interfaces = class_implements('\MathPHP\LinearAlgebra\Vector');

        $this->assertContains('Countable', $interfaces);
        $this->assertContains('ArrayAccess', $interfaces);
        $this->assertContains('JsonSerializable', $interfaces);
    }
}
