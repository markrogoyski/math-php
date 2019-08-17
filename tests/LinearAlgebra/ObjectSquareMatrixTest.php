<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\Functions\Polynomial;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\ObjectSquareMatrix;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Number\Complex;
use MathPHP\Exception;

class ObjectSquareMatrixTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test The constructor throws the proper exceptions
     * @dataProvider dataProviderConstructorException
     */
    public function testMatrixConstructorException(array $A, $exception)
    {
        $this->expectException($exception);
        $A = new ObjectSquareMatrix($A);
    }

    public function dataProviderConstructorException()
    {
        return [
            [
                [[new \stdClass()]],
                Exception\IncorrectTypeException::class,
            ],
            [
                [[new \stdClass(), new Polynomial([1, 2, 3])],
                [new \stdClass(), new Polynomial([1, 2, 3])]],
                Exception\IncorrectTypeException::class,
            ],
        ];
    }

    /**
     * @test Addition throws the proper exceptions
     * @dataProvider dataProviderForArithmaticExceptions
     */
    public function testMatrixAddException($A, $B, $exception)
    {
        $A = MatrixFactory::create($A);
        $this->expectException($exception);
        $C = $A->add($B);
    }

    /**
     * @test Subtraction throws the proper exceptions
     * @dataProvider dataProviderForArithmaticExceptions
     */
    public function testMatrixSubtractException($A, $B, $exception)
    {
        $A = MatrixFactory::create($A);
        $this->expectException($exception);
        $C = $A->subtract($B);
    }

    /**
     * @test Subtraction throws the proper exceptions
     * @dataProvider dataProviderForArithmaticExceptions
     */
    public function testMatrixMultiplyException($A, $B, $exception)
    {
        $A = new ObjectSquareMatrix($A);
        $this->expectException($exception);
        $C = $A->multiply($B);
    }

    public function dataProviderForArithmaticExceptions()
    {
        return[
            [ // Different Sizes
                [[new Polynomial([1, 2, 3]), new Polynomial([1, 2, 3])],
                [new Polynomial([1, 2, 3]), new Polynomial([1, 2, 3])]],
                MatrixFactory::create([[new Polynomial([1, 2, 3])]]),
                Exception\MatrixException::class,
            ],
            [ // Different Types
                [[new Polynomial([1, 2, 3])]],
                new ObjectSquareMatrix([[new Complex(1, 2)]]),
                Exception\IncorrectTypeException::class,
            ],
            [ // Not a Matrix
                [[new Polynomial([1, 2, 3])]],
                new Complex(1, 2),
                Exception\IncorrectTypeException::class,
            ],
        ];
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
     * @test Matrix can be multiplied by a vector
     * @dataProvider dataProviderMultiplyVector
     */
    public function testMultiplyVector(array $A, array $B, array $expected)
    {
        $A = MatrixFactory::create($A);
        $B = new Vector($B);
        $sum = $A->multiply($B);
        $expected = matrixFactory::create($expected);
        $this->assertEquals($sum, $expected);
    }
    public function dataProviderMultiplyVector()
    {
        return [
            [
                [
                    [new Polynomial([1, 0]), new Polynomial([0, 0])],
                    [new Polynomial([0, 0]), new Polynomial([1, 0])],
                ],
                [new Polynomial([1, 0]), new Polynomial([1, 1])],
                [
                    [new Polynomial([1, 0, 0])],
                    [new Polynomial([1, 1, 0])],
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
