<?php

namespace MathPHP\Tests\LinearAlgebra\Matrix;

use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\SquareMatrix;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Exception;

class MatrixFactoryTest extends \PHPUnit\Framework\TestCase
{
    use \MathPHP\Tests\LinearAlgebra\Fixture\MatrixDataProvider;

    /**
     * @test         create diagonal matrix
     * @dataProvider dataProviderForDiagonalMatrix
     */
    public function testCreateDiagonalMatrix(array $A)
    {
        // When
        $A = MatrixFactory::diagonal($A);

        // Then
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
     * @test         create square matrix
     * @dataProvider dataProviderForSquareMatrix
     */
    public function testCreateSquareMatrix(array $A)
    {
        // When
        $A = MatrixFactory::create($A);

        // Then
        $this->assertInstanceOf(SquareMatrix::class, $A);
        $this->assertInstanceOf(Matrix::class, $A);
    }

    public function dataProviderForSquareMatrix(): array
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
     * @test         create from array of vectors
     * @dataProvider dataProviderFromArrayOfVectors
     */
    public function testCreateArrayOfVectors(array $vectors, array $expected)
    {
        // Given
        $vectors = \array_map(
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

    public function dataProviderFromArrayOfVectors(): array
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

    /**
     * @test   create from array of vectors exception - different lengths
     * @throws \Exception
     */
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
     * @test
     * @dataProvider dataProviderForFunctionSquareMatrix
     */
    public function testCreateFunctionSquareMatrix(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertInstanceOf(\MathPHP\LinearAlgebra\FunctionSquareMatrix::class, $A);
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\SquareMatrix::class, $A);
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\Matrix::class, $A);
    }

    public function dataProviderForFunctionSquareMatrix(): array
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
     * @test         create function matrix
     * @dataProvider dataProviderForFunctionMatrix
     */
    public function testCreateFunctionMatrix(array $A)
    {
        // When
        $A = MatrixFactory::create($A);

        // Then
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\FunctionMatrix::class, $A);
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\Matrix::class, $A);
    }

    public function dataProviderForFunctionMatrix(): array
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
     * @test         create matrix
     * @dataProvider dataProviderForMatrix
     */
    public function testCreateMatrix(array $A)
    {
        // When
        $A = MatrixFactory::create($A);

        // Then
        $this->assertInstanceOf(\MathPHP\LinearAlgebra\Matrix::class, $A);

        // And
        $this->assertNotInstanceOf(\MathPHP\LinearAlgebra\SquareMatrix::class, $A);
        $this->assertNotInstanceOf(\MathPHP\LinearAlgebra\FunctionMatrix::class, $A);
        $this->assertNotInstanceOf(\MathPHP\LinearAlgebra\DiagonalMatrix::class, $A);
    }

    public function dataProviderForMatrix(): array
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

    /**
     * @test   check params exception for empty array
     * @throws \Exception
     */
    public function testCheckParamsExceptionEmptyArray()
    {
        // Given
        $A = [];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        $M = MatrixFactory::create($A);
    }

    /**
     * @test   matrix unknown type exception
     * @throws \Exception
     */
    public function testMatrixUnknownTypeException()
    {
        // Given
        $A = [
            [[1], [2], [3]],
            [[2], [3], [4]],
        ];

        // Then
        $this->expectException(Exception\IncorrectTypeException::class);

        // When
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
     * @test         downshiftPermutation
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
     * @test         upshiftPermutation
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

        // When
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

    /**
     * @test   exchange exception - n less than zero
     * @throws \Exception
     */
    public function testExchangeExceptionNLessThanZero()
    {
        // When
        $n = -1;

        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        MatrixFactory::exchange($n);
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
     * @test         one
     * @dataProvider dataProviderForOne
     */
    public function testOne($m, $n, array $R)
    {
        // Given
        $R = MatrixFactory::create($R);

        // When
        $M = MatrixFactory::one($m, $n);

        // Then
        $this->assertEquals($R, $M);
    }

    public function dataProviderForOne(): array
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

    /**
     * @test   one exception - rows less than one
     * @throws \Exception
     */
    public function testOneExceptionRowsLessThanOne()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        MatrixFactory::one(0, 2);
    }

    /**
     * @test         eye
     * @dataProvider dataProviderForEye
     */
    public function testEye(int $m, int $n, int $k, int $x, array $R)
    {
        // Given
        $R = MatrixFactory::create($R);

        // When
        $A = MatrixFactory::eye($m, $n, $k, $x);

        // Then
        $this->assertEquals($R, $A);
        $this->assertEquals($R->getMatrix(), $A->getMatrix());

        // And
        $this->assertEquals($m, $R->getM());
        $this->assertEquals($n, $R->getN());
    }

    public function dataProviderForEye(): array
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
     * @test         eye exceptions
     * @dataProvider dataProviderForEyeExceptions
     */
    public function testEyeExceptions(int $m, int $n, int $k, int $x)
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        $A = MatrixFactory::eye($m, $n, $k, $x);
    }

    /**
     * @return array
     */
    public function dataProviderForEyeExceptions(): array
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
     * @test         hilbert creates the expected Hilbert matrix
     * @dataProvider dataProviderForHilbertMatrix
     * @param        int $n
     * @param        array $H
     * @throws       \Exception
     */
    public function testHilbertMatrix($n, $H)
    {
        // Given
        $H = MatrixFactory::create($H);

        // When
        $sut = MatrixFactory::hilbert($n);

        // Then
        $this->assertEquals($H, $sut);
    }

    /**
     * @test   Hilbert exception when n is less than zero
     * @throws \Exception
     */
    public function testHilbertExceptionNLessThanZero()
    {
        // Given
        $n = -1;

        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        MatrixFactory::hilbert(-1);
    }

    /**
     * @test   Creating a random matrix of a specific size
     * @throws \Exception
     */
    public function testRandomMatrix()
    {
        // Given
        for ($m = 1; $m < 5; $m++) {
            for ($n = 1; $n < 5; $n++) {
                // When
                $A = MatrixFactory::random($m, $n);

                // Then
                $this->assertEquals($m, $A->getM());
                $this->assertEquals($n, $A->getN());

                // And
                $A->map(function ($element) {
                    $this->assertTrue(\is_int($element));
                });
            }
        }
    }
}
