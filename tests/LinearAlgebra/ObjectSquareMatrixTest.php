<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Functions\Polynomial;
use MathPHP\Exception\MatrixException;

class DiagonalMatrixTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderException
     */
    public function testMatrixException(array $A, array $B)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);
        $this->expectException(MatrixException::class);
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

    /**
     * @dataProvider dataProviderDetException
     */
    public function testMatrixDetException(array $A)
    {
        $A = MatrixFactory::create($A);
        $this->expectException(MatrixException::class);
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
