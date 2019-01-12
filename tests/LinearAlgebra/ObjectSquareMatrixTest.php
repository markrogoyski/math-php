<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Functions\Polynomial;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Number\Complex;
use MathPHP\Exception;

class ObjectMatrixTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderException
     */
    public function testMatrixException(array $A, array $B)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);
        $this->expectException(Exception\MatrixException::class);
        $sum = $A->add($B);
    }

    public function dataProviderException()
    {
        return [
            [
                [
                    [new Polynomial([1, 2]), new Polynomial([2, 1])],
                    [new Polynomial([2, 2]), new Polynomial([2, 0])],
                ],
                [
                    [new Polynomial([1, 2])],
                ],
            ],
        ];
    }

    public function testMatrixConstructException()
    {
        $object = new Vector([1, 4, 7]);
        $this->expectException(Exception\IncorrectTypeException::class);
        $A = MatrixFactory::create([[$object]]);
    }

    public function testMatrixAddException()
    {
        $polynomial = new Polynomial([1, 4, 7]);
        $complex = new Complex(1, 4);
        $A = MatrixFactory::create([[$polynomial]]);
        $B = MatrixFactory::create([[$complex]]);
        $this->expectException(Exception\IncorrectTypeException::class);
        $C = $A->add($B);
    }

    public function testMatrixMulSizeException()
    {
        $polynomial = new Polynomial([1, 4, 7]);
        $A = MatrixFactory::create([[$polynomial, $polynomial]]);
        $B = MatrixFactory::create([[$polynomial]]);
        $this->expectException(Exception\MatrixException::class);
        $C = $A->multiply($B);
    }

    public function testMatrixMulTypeException()
    {
        $polynomial = new Polynomial([1, 4, 7]);
        $A = MatrixFactory::create([[$polynomial, $polynomial]]);
        $this->expectException(Exception\IncorrectTypeException::class);
        $C = $A->multiply(21);
    }
    
    /**
     * @dataProvider dataProviderDetException
     */
    public function testMatrixDetException(array $A)
    {
        $A = MatrixFactory::create($A);
        $this->expectException(Exception\MatrixException::class);
        $sum = $A->det();
    }

    public function dataProviderDetException()
    {
        return [
            [
                [
                    [new Polynomial([1, 2]), new Polynomial([2, 1])],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderAdd
     */
    public function testAdd(array $A, array $B, array $expected)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);
        $sum = $A->add($B);
        $expected = matrixFactory::create($expected);
        $this->assertEquals($sum, $expected);
    }

    public function dataProviderAdd()
    {
        return [
            [
                [
                    [new Polynomial([1, 0]), new Polynomial([0, 0])],
                    [new Polynomial([0, 0]), new Polynomial([1, 0])],
                ],
                [
                    [new Polynomial([1, 0]), new Polynomial([1, 1])],
                    [new Polynomial([1, 1]), new Polynomial([1, 0])],
                ],
                [
                    [new Polynomial([2, 0]), new Polynomial([1, 1])],
                    [new Polynomial([1, 1]), new Polynomial([2, 0])],
                ],
            ],
            [
                [
                    [new Polynomial([1, 0]), new Polynomial([1, 0])],
                    [new Polynomial([1, 0]), new Polynomial([1, 0])],
                ],
                [
                    [new Polynomial([1, 0]), new Polynomial([1, 1])],
                    [new Polynomial([1, 1]), new Polynomial([1, 0])],
                ],
                [
                    [new Polynomial([2, 0]), new Polynomial([2, 1])],
                    [new Polynomial([2, 1]), new Polynomial([2, 0])],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderSubtract
     */
    public function testSubtract(array $A, array $B, array $expected)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);
        $difference = $A->subtract($B);
        $expected = matrixFactory::create($expected);
        $this->assertEquals($difference, $expected);
    }

    public function dataProviderSubtract()
    {
        return [
            [
                [
                    [new Polynomial([1, 0]), new Polynomial([0, 0])],
                    [new Polynomial([0, 0]), new Polynomial([1, 0])],
                ],
                [
                    [new Polynomial([2, 1]), new Polynomial([2, 1])],
                    [new Polynomial([1, -1]), new Polynomial([-1, 0])],
                ],
                [
                    [new Polynomial([-1, -1]), new Polynomial([-2, -1])],
                    [new Polynomial([-1, 1]), new Polynomial([2, 0])],
                ],
            ],
            [
                [
                    [new Polynomial([1, 0]), new Polynomial([1, 0])],
                    [new Polynomial([1, 0]), new Polynomial([1, 0])],
                ],
                [
                    [new Polynomial([-2, 0]), new Polynomial([1, -1])],
                    [new Polynomial([-2, 2]), new Polynomial([4, 4])],
                ],
                [
                    [new Polynomial([3, 0]), new Polynomial([0, 1])],
                    [new Polynomial([3, -2]), new Polynomial([-3, -4])],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderMul
     */
    public function testMul(array $A, array $B, array $expected)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);
        $sum = $A->multiply($B);
        $expected = matrixFactory::create($expected);
        $this->assertEquals($sum, $expected);
    }

    public function dataProviderMul()
    {
        return [
            [
                [
                    [new Polynomial([1, 0]), new Polynomial([0, 0])],
                    [new Polynomial([0, 0]), new Polynomial([1, 0])],
                ],
                [
                    [new Polynomial([1, 0]), new Polynomial([1, 1])],
                    [new Polynomial([1, 1]), new Polynomial([1, 0])],
                ],
                [
                    [new Polynomial([1, 0, 0]), new Polynomial([1, 1, 0])],
                    [new Polynomial([1, 1, 0]), new Polynomial([1, 0, 0])],
                ],
            ],
            [
                [
                    [new Polynomial([1, 0]), new Polynomial([1, 0])],
                    [new Polynomial([1, 0]), new Polynomial([1, 0])],
                ],
                [
                    [new Polynomial([1, 0]), new Polynomial([1, 1])],
                    [new Polynomial([1, 1]), new Polynomial([1, 0])],
                ],
                [
                    [new Polynomial([2, 1, 0]), new Polynomial([2, 1, 0])],
                    [new Polynomial([2, 1, 0]), new Polynomial([2, 1, 0])],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderDet
     */
    public function testDet(array $A, Polynomial $expected)
    {
        $A = MatrixFactory::create($A);
        $det = $A->det();
        $this->assertEquals($det, $expected);
        $det = $A->det();
        $this->assertEquals($det, $expected);
    }

    public function dataProviderDet()
    {
        return [
            [
                [
                    [new Polynomial([1, 0])],
                ],
                new Polynomial([1, 0]),
            ],
            [
                [
                    [new Polynomial([1, 0]), new Polynomial([1, 0])],
                    [new Polynomial([1, 0]), new Polynomial([0, 4])],
                ],
                new Polynomial([-1, 4, 0]),
            ],
        ];
    }
}
