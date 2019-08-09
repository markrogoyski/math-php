<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\SquareMatrix;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Exception;

class MatrixFactoryTest extends \PHPUnit\Framework\TestCase
{
    use \MathPHP\Tests\LinearAlgebra\MatrixDataProvider;
    use \phpmock\phpunit\PHPMock;

    /**
     * @dataProvider dataProviderForDiagonalMatrix
     */
    public function testCreateDiagonalMatrix(array $A)
    {
        $A = MatrixFactory::diagonal($A);

        $this->assertInstanceOf(\MathPHP\LinearAlgebra\DiagonalMatrix::class, $A);
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\Matrix::class, $A);
    }

    public function dataProviderForDiagonalMatrix()
    {
        return [
            [[1]],
            [[1, 2]],
            [[1, 2, 3]],
            [[1, 2, 3, 4]],
        ];
    }

    /**
     * @dataProvider dataProviderForSquareMatrix
     */
    public function testCreateSquareMatrix(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertInstanceOf(SquareMatrix::class, $A);
        $this->assertInstanceOf(Matrix::class, $A);
    }

    public function dataProviderForSquareMatrix()
    {
        return [
            [
                [[1]]
            ],
            [
                [
                    [1, 2],
                    [2, 3],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [3, 4, 5],
                    [5, 6, 7],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForArrayOfVectors
     */
    public function testCreateArrayOfVectors(array $vectors, array $expected)
    {
        // Given
        $vectors = array_map(
            function ($vector) {
                return new Vector($vector);
            },
            $vectors
        );

        // When
        $A = MatrixFactory::createFromVectors($vectors);

        // Then
        $this->assertInstanceOf(Matrix::class, $A);
        $this->assertEquals($expected, $A->getMatrix());
    }

    public function dataProviderForArrayOfVectors()
    {
        return [
            [
                [
                    [1, 2],
                ],
                [
                    [1],
                    [2],
                ],
            ],
            [
                [
                    [1, 2],
                    [3, 4],
                ],
                [
                    [1, 3],
                    [2, 4],
                ],
            ],
            [
                [
                    [1, 2],
                    [3, 4],
                    [5, 6],
                ],
                [
                    [1, 3, 5],
                    [2, 4, 6],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [3, 4, 5],
                    [5, 6, 6],
                ],
                [
                    [1, 3, 5],
                    [2, 4, 6],
                    [3, 5, 6],
                ],
            ],
        ];
    }

    public function testCreateFromArrayOfVectorsExceptionVectorsDifferentLengths()
    {
        // Given
        $A = [
            new Vector([1, 2]),
            new Vector([4, 5, 6]),
        ];

        // Then
        $this->expectException(Exception\MatrixException::class);

        // When
        $A = MatrixFactory::createFromVectors($A);
    }

    /**
     * @dataProvider dataProviderForFunctionSquareMatrix
     */
    public function testCreateFunctionSquareMatrix(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertInstanceOf(\MathPHP\LinearAlgebra\FunctionSquareMatrix::class, $A);
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\SquareMatrix::class, $A);
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\Matrix::class, $A);
    }

    public function dataProviderForFunctionSquareMatrix()
    {
        $function = function ($x) {
            return $x * 2;
        };

        return [
            [
                [
                    [$function]
                ]
            ],
            [
                [
                    [$function, $function],
                    [$function, $function],
                ],
            ],
            [
                [
                    [$function, $function, $function],
                    [$function, $function, $function],
                    [$function, $function, $function],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForFunctionMatrix
     */
    public function testCreateFunctionMatrix(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertInstanceOf(\MathPHP\LinearAlgebra\FunctionMatrix::class, $A);
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\Matrix::class, $A);
    }

    public function dataProviderForFunctionMatrix()
    {
        $function = function ($x) {
            return $x * 2;
        };

        return [
            [
                [
                    [$function, $function]
                ]
            ],
            [
                [
                    [$function, $function],
                    [$function, $function],
                    [$function, $function],
                ],
            ],
            [
                [
                    [$function, $function, $function],
                    [$function, $function, $function],
                    [$function, $function, $function],
                    [$function, $function, $function],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForMatrix
     */
    public function testCreateMatrix(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertInstanceOf(\MathPHP\LinearAlgebra\Matrix::class, $A);

        $this->assertNotInstanceOf(\MathPHP\LinearAlgebra\SquareMatrix::class, $A);
        $this->assertNotInstanceOf(\MathPHP\LinearAlgebra\FunctionMatrix::class, $A);
        $this->assertNotInstanceOf(\MathPHP\LinearAlgebra\DiagonalMatrix::class, $A);
    }

    public function dataProviderForMatrix()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                    [4, 5, 6],
                ],
            ],
        ];
    }

    public function testCheckParamsExceptionEmptyArray()
    {
        $A = [];

        $this->expectException(Exception\BadDataException::class);
        $M = MatrixFactory::create($A);
    }

    public function testMatrixUnknownTypeException()
    {
        $A = [
            [[1], [2], [3]],
            [[2], [3], [4]],
        ];

        $this->expectException(Exception\IncorrectTypeException::class);
        MatrixFactory::create($A);
    }

    /**
     * @test         identity
     * @dataProvider dataProviderForIdentity
     * @param        int   $n
     * @param        array $R
     * @throws       \Exception
     */
    public function testIdentity(int $n, array $R)
    {
        // Given
        $R = new SquareMatrix($R);

        // When
        $I = MatrixFactory::identity($n);

        // Then
        $this->assertEquals($R, $I);
    }

    /**
     * @return array
     */
    public function dataProviderForIdentity(): array
    {
        return [
            [
                1, [[1]],
            ],
            [
                2, [
                    [1, 0],
                    [0, 1],
                ]
            ],
            [
                3, [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1]
                ]
            ],
            [
                4, [
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 1, 0],
                    [0, 0, 0, 1],
                ]
            ],
        ];
    }

    /**
     * @testCase     downshiftPermutation
     * @dataProvider dataProviderForDownshiftPermutation
     * @param        int $n
     * @param        array $R
     * @throws       \Exception
     */
    public function testDownshiftPermutation(int $n, array $R)
    {
        $R = new SquareMatrix($R);
        $this->assertEquals($R, MatrixFactory::downshiftPermutation($n));
    }

    /**
     * @return array [n, R]
     */
    public function dataProviderForDownshiftPermutation(): array
    {
        return [
            [
                1,
                [
                    [1]
                ]
            ],
            [
                2,
                [
                    [0, 1],
                    [1, 0],
                ]
            ],
            [
                3,
                [
                    [0, 0, 1],
                    [1, 0, 0],
                    [0, 1, 0],
                ]
            ],
            [
                4,
                [
                    [0, 0, 0, 1],
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 1, 0],
                ]
            ],
        ];
    }

    /**
     * @testCase     upshiftPermutation
     * @dataProvider dataProviderForUpshiftPermutation
     * @param        int $n
     * @param        array $R
     * @throws       \Exception
     */
    public function testUpshiftPermutation(int $n, array $R)
    {
        $R = new SquareMatrix($R);
        $this->assertEquals($R, MatrixFactory::upshiftPermutation($n));
    }

    /**
     * @return array [n, R]
     */
    public function dataProviderForUpshiftPermutation(): array
    {
        return [
            [
                1,
                [
                    [1]
                ]
            ],
            [
                2,
                [
                    [0, 1],
                    [1, 0],
                ]
            ],
            [
                3,
                [
                    [0, 1, 0],
                    [0, 0, 1],
                    [1, 0, 0],
                ]
            ],
            [
                4,
                [
                    [0, 1, 0, 0],
                    [0, 0, 1, 0],
                    [0, 0, 0, 1],
                    [1, 0, 0, 0],
                ]
            ],
        ];
    }

    /**
     * @test   identity with n less than zero
     * @throws \Exception
     */
    public function testIdentityExceptionNLessThanZero()
    {
        // Given
        $n = -1;

        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        MatrixFactory::identity($n);
    }

    /**
     * @test         exchange
     * @dataProvider dataProviderForExchange
     * @param        int   $n
     * @param        array $R
     * @throws       \Exception
     */
    public function testExchange(int $n, array $R)
    {
        // Given
        $R = new SquareMatrix($R);

        $E = MatrixFactory::exchange($n);

        // Then
        $this->assertEquals($R, $E);
    }

    /**
     * @return array
     */
    public function dataProviderForExchange(): array
    {
        return [
            [
                1, [[1]],
            ],
            [
                2, [
                    [0, 1],
                    [1, 0],
                ]
            ],
            [
                3, [
                    [0, 0, 1],
                    [0, 1, 0],
                    [1, 0, 0]
                ]
            ],
            [
                4, [
                    [0, 0, 0, 1],
                    [0, 0, 1, 0],
                    [0, 1, 0, 0],
                    [1, 0, 0, 0],
                ]
            ],
        ];
    }

    public function testExchangeExceptionNLessThanZero()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        MatrixFactory::exchange(-1);
    }

    /**
     * @test         zero
     * @dataProvider dataProviderForZero
     * @param        int   $m
     * @param        int   $n
     * @param        array $R
     * @throws       \Exception
     */
    public function testZero(int $m, int $n, array $R)
    {
        // Given
        $R = MatrixFactory::create($R);

        // When
        $Z = MatrixFactory::zero($m, $n);

        // Then
        $this->assertEquals($R, $Z);
    }

    /**
     * @return array
     */
    public function dataProviderForZero(): array
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

    /**
     * @test   zero with row less than one
     * @throws \Exception
     */
    public function testZeroExceptionRowsLessThanOne()
    {
        // Given
        $m = 0;
        $n = 2;

        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        MatrixFactory::zero($m, $n);
    }

    /**
     * @dataProvider dataProviderForOne
     */
    public function testOne($m, $n, array $R)
    {
        $R = MatrixFactory::create($R);
        $this->assertEquals($R, MatrixFactory::one($m, $n));
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
        $this->expectException(Exception\OutOfBoundsException::class);
        MatrixFactory::one(0, 2);
    }

    /**
     * @dataProvider dataProviderForEye
     */
    public function testEye(int $m, int $n, int $k, int $x, array $R)
    {
        $A = MatrixFactory::eye($m, $n, $k, $x);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A);
        $this->assertEquals($R->getMatrix(), $A->getMatrix());

        $this->assertEquals($m, $R->getM());
        $this->assertEquals($n, $R->getN());
    }

    public function dataProviderForEye()
    {
        return [
            [
                1, 1, 0, 1,
                [
                    [1]
                ],
            ],
            [
                1, 1, 0, 9,
                [
                    [9]
                ],
            ],
            [
                2, 2, 0, 1,
                [
                    [1, 0],
                    [0, 1],
                ],
            ],
            [
                2, 2, 1, 1,
                [
                    [0, 1],
                    [0, 0],
                ],
            ],
            [
                3, 3, 0, 1,
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                3, 3, 1, 1,
                [
                    [0, 1, 0],
                    [0, 0, 1],
                    [0, 0, 0],
                ],
            ],
            [
                3, 3, 2, 1,
                [
                    [0, 0, 1],
                    [0, 0, 0],
                    [0, 0, 0],
                ],
            ],
            [
                3, 3, 0, 9,
                [
                    [9, 0, 0],
                    [0, 9, 0],
                    [0, 0, 9],
                ],
            ],
            [
                3, 3, 1, 9,
                [
                    [0, 9, 0],
                    [0, 0, 9],
                    [0, 0, 0],
                ],
            ],
            [
                3, 3, 0, -9,
                [
                    [-9, 0, 0],
                    [0, -9, 0],
                    [0, 0, -9],
                ],
            ],
            [
                3, 4, 0, 1,
                [
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 1, 0],
                ],
            ],
            [
                3, 4, 1, 1,
                [
                    [0, 1, 0, 0],
                    [0, 0, 1, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                3, 4, 2, 1,
                [
                    [0, 0, 1, 0],
                    [0, 0, 0, 1],
                    [0, 0, 0, 0],
                ],
            ],
            [
                3, 4, 3, 1,
                [
                    [0, 0, 0, 1],
                    [0, 0, 0, 0],
                    [0, 0, 0, 0],
                ],
            ],
            [
                3, 4, 1, 9,
                [
                    [0, 9, 0, 0],
                    [0, 0, 9, 0],
                    [0, 0, 0, 9],
                ],
            ],
            [
                4, 3, 0, 1,
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                    [0, 0, 0],
                ],
            ],
            [
                4, 3, 1, 1,
                [
                    [0, 1, 0],
                    [0, 0, 1],
                    [0, 0, 0],
                    [0, 0, 0],
                ],
            ],
            [
                4, 3, 2, 1,
                [
                    [0, 0, 1],
                    [0, 0, 0],
                    [0, 0, 0],
                    [0, 0, 0],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForEyeExceptions
     */
    public function testEyeExceptions(int $m, int $n, int $k, int $x)
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        $A = MatrixFactory::eye($m, $n, $k, $x);
    }

    public function dataProviderForEyeExceptions()
    {
        return [
            [-1, 2, 1, 1],
            [2, -1, 1, 1],
            [2, 2, -1, 1],
            [2, 2, 2, 1],
            [2, 2, 3, 1],
        ];
    }

    /**
     * @testCase     hilbert creates the expected Hilbert matrix
     * @dataProvider dataProviderForHilbertMatrix
     * @param        int $n
     * @param        array $H
     */
    public function testHilbertMatrix($n, $H)
    {
        $H = MatrixFactory::create($H);

        $this->assertEquals($H, MatrixFactory::hilbert($n));
    }

    public function testHilbertExceptionNLessThanZero()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        MatrixFactory::hilbert(-1);
    }

    /**
     * @testCase Test that the appropriate matrix is constructed
     *
     * @runInSeparateProcess
     */
    public function testRandomMatrix()
    {
        $expected = [[.31415926535]];
        $rand = $this->getFunctionMock('MathPHP\LinearAlgebra', 'rand');
        $rand->expects($this->once())->willReturn(.31415926535);
        $this->assertEquals($expected, MatrixFactory::random(1, 1)->getMatrix());
    }
}
