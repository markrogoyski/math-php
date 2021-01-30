<?php

namespace MathPHP\Tests\LinearAlgebra\Matrix;

use MathPHP\Expression\Polynomial;
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
     * @test         Cannot compute the determinant of a non-square matrix
     * @dataProvider dataProviderDetException
     * @throws       \Exception
     */
    public function testMatrixDetException(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // Then
        $this->expectException(Exception\MatrixException::class);

        // When
        $det = $A->det();
    }

    /**
     * @return array
     */
    public function dataProviderDetException(): array
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
     * @test         isEqual
     * @dataProvider dataProviderisEqual
     * @param        array $A
     * @param        array $B
     * @param        bool $expected
     * @throws       \Exception
     */
    public function testIsEqual(array $A, array $B, bool $expected)
    {
        // Given
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);

        // When
        $comparison = $A->isEqual($B);

        // Then
        $this->assertEquals($expected, $comparison);
    }

    /**
     * @return array
     */
    public function dataProviderisEqual()
    {
        return [
            'same' => [
                [[new Polynomial([1, 0])]],
                [[new Polynomial([1, 0])]],
                true,
            ],
            'different types' => [
                [[new Polynomial([1, 0])]],
                [[1]],
                false,
            ],
            'different contents' => [
                [[new Polynomial([1, 0])]],
                [[new Polynomial([1, 1])]],
                false,
            ],
            'different shapes' => [
                [[new Polynomial([1, 0]), new Polynomial([1, 0])]],
                [[new Polynomial([1, 0])], [new Polynomial([1, 0])]],
                false,
            ],
        ];
    }

    /**
     * @test         add
     * @dataProvider dataProviderAdd
     * @param        array $A
     * @param        array $B
     * @param        array $expected
     * @throws       \Exception
     */
    public function testAdd(array $A, array $B, array $expected)
    {
        // Given
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);

        // And
        $expected = matrixFactory::create($expected);

        // When
        $sum = $A->add($B);

        // Then
        $this->assertEquals($expected, $sum);
    }

    /**
     * @return array
     */
    public function dataProviderAdd(): array
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
     * @test         subtract
     * @dataProvider dataProviderSubtract
     * @param        array $A
     * @param        array $B
     * @param        array $expected
     * @throws       \Exception
     */
    public function testSubtract(array $A, array $B, array $expected)
    {
        // Given
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);
        $expected = matrixFactory::create($expected);

        // When
        $difference = $A->subtract($B);

        // Then
        $this->assertEquals($expected, $difference);
    }

    /**
     * @return array
     */
    public function dataProviderSubtract(): array
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
     * @test         multiply
     * @dataProvider dataProviderMul
     * @param        array $A
     * @param        array $B
     * @param        array $expected
     * @throws       \Exception
     */
    public function testMul(array $A, array $B, array $expected)
    {
        // Given
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);

        // And
        $expected = matrixFactory::create($expected);

        // When
        $sum = $A->multiply($B);

        // Then
        $this->assertEquals($expected, $sum);
    }

    /**
     * @return array
     */
    public function dataProviderMul(): array
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
        $this->assertEquals($expected, $sum);
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
     * @test         det
     * @dataProvider dataProviderDet
     * @param        array $A
     * @param        Polynomial $expected
     * @throws       \Exception
     */
    public function testDet(array $A, Polynomial $expected)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $det = $A->det();

        // Then
        $this->assertEquals($det, $expected);

        // And when
        $det = $A->det();

        // Then
        $this->assertEquals($expected, $det);
    }

    /**
     * @return array
     */
    public function dataProviderDet(): array
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
