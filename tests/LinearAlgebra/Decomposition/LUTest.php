<?php

namespace MathPHP\Tests\LinearAlgebra\Decomposition;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\Exception;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Tests\LinearAlgebra\Fixture\MatrixDataProvider;

class LUTest extends \PHPUnit\Framework\TestCase
{
    use MatrixDataProvider;

    /**
     * @test         LU decomposition - expected values for L, U, and P
     * @dataProvider dataProviderForLUDecomposition
     * @param        array $A
     * @param        array $L
     * @param        array $U
     * @param        array $P
     * @throws       \Exception
     */
    public function testLUDecomposition(array $A, array $L, array $U, array $P)
    {
        // Given
        $A = MatrixFactory::create($A);
        $L = MatrixFactory::create($L);
        $U = MatrixFactory::create($U);
        $P = MatrixFactory::create($P);

        // When
        $LU = $A->luDecomposition();

        // Then
        $this->assertEqualsWithDelta($L, $LU->L, 0.001);
        $this->assertEqualsWithDelta($U, $LU->U, 0.001);
        $this->assertEqualsWithDelta($P, $LU->P, 0.001);
    }

    /**
     * @test         LU decomposition - PA = LU
     * @dataProvider dataProviderForLUDecomposition
     * @param        array $A
     * @throws       \Exception
     */
    public function testLUDecompositionPaEqualsLu(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $LU = $A->luDecomposition();

        // Then PA = LU;
        $PA = $LU->P->multiply($A);
        $LU = $LU->L->multiply($LU->U);
        $this->assertEqualsWithDelta($PA->getMatrix(), $LU->getMatrix(), 0.01);
    }

    /**
     * @test         LU decomposition - L and U properties
     * @dataProvider dataProviderForLUDecomposition
     * @param        array $A
     * @throws       \Exception
     */
    public function testLUDecompositionLAndUProperties(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $LU = $A->luDecomposition();

        // Then
        $this->assertTrue($LU->L->isLowerTriangular());
        $this->assertTrue($LU->U->isUpperTriangular());
    }

    /**
     * @test         Solve
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected
     * @throws       \Exception
     */
    public function testSolve(array $A, array $b, array $expected)
    {
        // Given
        $A  = MatrixFactory::create($A);
        $LU = $A->luDecomposition();

        // And
        $expected = new Vector($expected);

        // When
        $x = $LU->solve($b);

        // Then
        $this->assertEqualsWithDelta($expected, $x, 0.00001);
    }

    /**
     * @test LU decomposition with small pivots
     *       (http://buzzard.ups.edu/courses/2014spring/420projects/math420-UPS-spring-2014-reid-LU-pivoting.pdf)
     *       Results computed with SciPy scipy.linalg.lu(A)
     * @throws \Exception
     */
    public function testLuDecompositionSmallPivots()
    {
        // Given
        $A = MatrixFactory::create([
            [10e-20, 1],
            [1, 2],
        ]);

        // And
        $L = MatrixFactory::create([
            [1, 0],
            [1e-19, 1],
        ]);
        $U = MatrixFactory::create([
            [1, 2],
            [0, 1],
        ]);
        $P = MatrixFactory::create([
            [0, 1],
            [1, 0],
        ]);

        // When
        $LU = $A->luDecomposition();

        // Then
        $this->assertEqualsWithDelta($L, $LU->L, 1e-20);
        $this->assertEqualsWithDelta($U, $LU->U, 1e-20);
        $this->assertEqualsWithDelta($P, $LU->P, 1e-20);

        // And
        $this->assertTrue($LU->L->isLowerTriangular());
        $this->assertTrue($LU->U->isUpperTriangular());

        // And PA = LU;
        $PA = $LU->P->multiply($A);
        $LU = $LU->L->multiply($LU->U);
        $this->assertEqualsWithDelta($PA->getMatrix(), $LU->getMatrix(), 0.01);
    }

    /**
     * Test data from various sources:
     *   SciPy scipy.linalg.lu(A)
     *   Online calculator: https://www.easycalculation.com/matrix/lu-decomposition-matrix.php
     *   Various other sources.
     * @return array (A, L, U, P)
     */
    public function dataProviderForLuDecomposition(): array
    {
        return [
            [
                [
                    [4, 3],
                    [6, 3],
                ],
                [
                    [1, 0],
                    [0.667, 1],
                ],
                [
                    [6, 3],
                    [0, 1],
                ],
                [
                    [0, 1],
                    [1, 0],
                ],
            ],
            // Matrix Computations 3.4 Pivoting example - pivoting prevents large entries in the triangular factors L and U
            [
                [
                    [.0001, 1],
                    [1, 1],
                ],
                [
                    [1, 0],
                    [0.0001, 1],
                ],
                [
                    [1, 1],
                    [0, 0.999],
                ],
                [
                    [0, 1],
                    [1, 0],
                ],
            ],
            // Zero at first pivot element would cause a divide by zero error without pivoting (http://buzzard.ups.edu/courses/2014spring/420projects/math420-UPS-spring-2014-reid-LU-pivoting.pdf)
            [
                [
                    [0, 1],
                    [1, 2],
                ],
                [
                    [1, 0],
                    [0, 1],
                ],
                [
                    [1, 2],
                    [0, 1],
                ],
                [
                    [0, 1],
                    [1, 0],
                ],
            ],
            // Small pivots
            [
                [
                    [10e-20, 1],
                    [1, 2],
                ],
                [
                    [1, 0],
                    [1e-19, 1],
                ],
                [
                    [1, 2],
                    [0, 1],
                ],
                [
                    [0, 1],
                    [1, 0],
                ],
            ],
            [
                [
                    [1, 3, 5],
                    [2, 4, 7],
                    [1, 1, 0],
                ],
                [
                    [1, 0, 0],
                    [.5, 1, 0],
                    [.5, -1, 1],
                ],
                [
                    [2, 4, 7],
                    [0, 1, 1.5],
                    [0, 0, -2],
                ],
                [
                    [0, 1, 0],
                    [1, 0, 0],
                    [0, 0, 1],
                ]
            ],
            [
                [
                    [1, -2, 3],
                    [2, -5, 12],
                    [0, 2, -10],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0.5, 0.25, 1],
                ],
                [
                    [2, -5, 12],
                    [0, 2, -10],
                    [0, 0, -0.5],
                ],
                [
                    [0, 1, 0],
                    [0, 0, 1],
                    [1, 0, 0],
                ],
            ],
            [
                [
                    [4, 2, 3],
                    [-3, 1, 4],
                    [2, 4, 5],
                ],
                [
                    [1, 0, 0],
                    [0.5, 1, 0],
                    [-0.75, 0.833, 1],
                ],
                [
                    [4, 2, 3],
                    [0, 3, 3.5],
                    [0, 0, 3.333]
                ],
                [
                    [1, 0, 0],
                    [0, 0, 1],
                    [0, 1, 0],
                ],
            ],
            // Partial pivoting example - (http://buzzard.ups.edu/courses/2014spring/420projects/math420-UPS-spring-2014-reid-LU-pivoting.pdf)
            [
                [
                    [1, 2, 4],
                    [2, 1, 3],
                    [3, 2, 4],
                ],
                [
                    [1, 0, 0],
                    [1 / 3, 1, 0],
                    [2 / 3, -1 / 4, 1],
                ],
                [
                    [3, 2, 4],
                    [0, 4 / 3, 8 / 3],
                    [0, 0, 1]
                ],
                [
                    [0, 0, 1],
                    [1, 0, 0],
                    [0, 1, 0],
                ],
            ],
            [
                [
                    [2, 3, 4],
                    [4, 7, 5],
                    [4, 9, 5],
                ],
                [
                    [1, 0, 0],
                    [1, 1, 0],
                    [0.5, -0.25, 1],
                ],
                [
                    [4, 7, 5],
                    [0, 2, 0],
                    [0, 0, 1.5]
                ],
                [
                    [0, 1, 0],
                    [0, 0, 1],
                    [1, 0, 0],
                ],
            ],
            [
                [
                    [5, 4, 8, 9],
                    [9, 9, 9, 9],
                    [4, 5, 5, 7],
                    [1, 9, 8, 7],
                ],
                [
                    [1, 0, 0, 0],
                    [.556, 1, 0, 0],
                    [.111, -8, 1, 0],
                    [.444, -1, .129, 1],
                ],
                [
                    [9, 9, 9, 9],
                    [0, -1, 3, 4],
                    [0, 0, 31, 38],
                    [0, 0, 0, 2.097],
                ],
                [
                    [0, 1, 0, 0],
                    [1, 0, 0, 0],
                    [0, 0, 0, 1],
                    [0, 0, 1, 0],
                ],
            ],
            [
                [
                    [2, 1, 1, 0],
                    [4, 3, 3, 1],
                    [8, 7, 9, 5],
                    [6, 7, 9, 8],
                ],
                [
                    [1, 0, 0, 0],
                    [0.25, 1, 0, 0],
                    [0.5, 0.667, 1, 0],
                    [0.75, -2.333, 1, 1],
                ],
                [
                    [8, 7, 9, 5],
                    [0, -0.75, -1.25, -1.25],
                    [0, 0, -0.667, -0.667],
                    [0, 0, 0, 2],
                ],
                [
                    [0, 0, 1, 0],
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [11, 9, 24, 2],
                    [1, 5, 2, 6],
                    [3, 17, 18, 1],
                    [2, 5, 7, 1],
                ],
                [
                    [1, 0, 0, 0],
                    [.27273, 1, 0, 0],
                    [.09091, .28750, 1, 0],
                    [.18182, .23125, .00360, 1],
                ],
                [
                    [11, 9, 24, 2],
                    [0, 14.54545, 11.45455, 0.45455],
                    [0, 0, -3.47500, 5.68750],
                    [0, 0, 0, 0.51079],
                ],
                [
                    [1, 0, 0, 0],
                    [0, 0, 1, 0],
                    [0, 1, 0, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [5, 3, 8],
                    [6, 4, 5],
                    [1, 8, 9],
                ],
                [
                    [1, 0, 0],
                    [0.167, 1, 0],
                    [.833, -0.045, 1],
                ],
                [
                    [6, 4, 5],
                    [0, 7.333, 8.167],
                    [0, 0, 4.205]
                ],
                [
                    [0, 1, 0],
                    [0, 0, 1],
                    [1, 0, 0],
                ],
            ],
            [
                [
                    [3, 2, 6, 7],
                    [4, 3, -6, 2],
                    [12, 14, 14, -6],
                    [4, 6, 4, -42],
                ],
                [
                    [1, 0, 0, 0],
                    [0.25, 1, 0, 0],
                    [0.333, 1.111, 1, 0],
                    [0.333, -0.889, -0.116, 1],
                ],
                [
                    [12, 14, 14, -6],
                    [0, -1.5, 2.5, 8.5],
                    [0, 0, -13.444, -5.444],
                    [0, 0, 0, -33.074],
                ],
                [
                    [0, 0, 1, 0],
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [5, 3, 4, 1],
                    [5, 6, 4, 3],
                    [7, 6, 5, 3],
                    [2, 7, 4, 7],
                ],
                [
                    [1, 0, 0, 0],
                    [0.286, 1, 0, 0],
                    [0.714, -0.243, 1, 0],
                    [0.714, 0.324, -0.385, 1],
                ],
                [
                    [7, 6, 5, 3],
                    [0, 5.286, 2.571, 6.143],
                    [0, 0, 1.054, 0.351],
                    [0, 0, 0, -1],
                ],
                [
                    [0, 0, 1, 0],
                    [0, 0, 0, 1],
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                ],
            ],
            // Near-singular matrix with very small determinant
            [
                [
                    [1.0, 2.0, 3.0],
                    [4.0, 5.0, 6.0],
                    [7.0, 8.0, 9.00001]
                ],
                [
                    [1, 0, 0],
                    [0.14285714, 1, 0],
                    [0.57142857, 0.5, 1]
                ],
                [
                    [7.0, 8.0, 9.00001],
                    [0, 0.857142857, 1.71428429],
                    [0, 0, -5.0e-6]
                ],
                [
                    [0, 0, 1],
                    [1, 0, 0],
                    [0, 1, 0]
                ]
            ],
            // Near-singular 2x2 matrix
            [
                [
                    [2.0, 4.0],
                    [1.0, 2.00001]
                ],
                [
                    [1, 0],
                    [0.5, 1]
                ],
                [
                    [2, 4],
                    [0, 1.0E-5]
                ],
                [
                    [1, 0],
                    [0, 1]
                ]
            ],
            // Hilbert matrix (ill-conditioned)
            [
                [
                    [1.0, 0.5, 0.3333333333],
                    [0.5, 0.3333333333, 0.25],
                    [0.3333333333, 0.25, 0.2]
                ],
                [
                    [1.0, 0, 0],
                    [0.5, 1.0, 0],
                    [0.3333333333, 1, 1]
                ],
                [
                    [1.0, 0.5, 0.3333333333],
                    [0, 0.08333333, 0.08333333335],
                    [0, 0, 0.00555556]
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1]
                ]
            ],
            // Diagonally dominant 3x3
            [
                [
                    [10.0, 1.0, 2.0],
                    [1.0, 15.0, 3.0],
                    [2.0, 1.0, 20.0]
                ],
                [
                    [1, 0, 0],
                    [0.1, 1, 0],
                    [0.2, 0.05369128, 1]
                ],
                [
                    [10.0, 1, 2],
                    [0, 14.9, 2.8],
                    [0, 0, 19.44966443]
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1]
                ]
            ],
            // Diagonally dominant 4x4
            [
                [
                    [20.0, 1.0, 2.0, 3.0],
                    [1.0, 25.0, 2.0, 1.0],
                    [2.0, 1.0, 30.0, 2.0],
                    [3.0, 1.0, 2.0, 35.0]
                ],
                [
                    [1, 0, 0, 0],
                    [0.05, 1, 0, 0],
                    [0.1, 0.03607214, 1, 0],
                    [0.15, 0.03406814, 0.05500135, 1]
                ],
                [
                    [20, 1, 2, 3],
                    [0, 24.95, 1.9, 0.85],
                    [0, 0, 29.73146293, 1.66933868],
                    [0, 0, 0, 34.42922621]
                ],
                [
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 1, 0],
                    [0, 0, 0, 1]
                ]
            ],
            // Numerical stability test - large and small numbers
            [
                [
                    [1000000.0, 0.000001, 1.0],
                    [0.000001, 1000000.0, 1.0],
                    [1.0, 1.0, 1000000.0]
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0.000001, 1.e-06, 1]
                ],
                [
                    [1000000, 0.000001, 1],
                    [0, 1000000, 1],
                    [0, 0, 1.e+06]
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1]
                ]
            ],
            // Numerical stability test - moderate scaling
            [
                [
                    [100.0, 0.01, 1.0],
                    [0.01, 100.0, 1.0],
                    [1.0, 1.0, 100.0]
                ],
                [
                    [1, 0, 0],
                    [0.0001, 1.0, 0],
                    [0.01, 0.0099990001, 1]
                ],
                [
                    [100, 0.01, 1],
                    [0, 99.999999, 0.9999],
                    [0, 0, 99.980002]
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1]
                ]
            ],
        ];
    }

    /**
     * @test   LU decomposition exception for matrix not being square
     * @throws \Exception
     */
    public function testLUDecompositionExceptionNotSquare()
    {
        // Given
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
        ]);

        // Then
        $this->expectException(Exception\MatrixException::class);

        // When
        $A->luDecomposition();
    }

    /**
     * @test   LU Decomposition invalid property
     * @throws \Exception
     */
    public function testLUDecompositionInvalidProperty()
    {
        // Given
        $A = MatrixFactory::create([
            [5, 3, 4, 1],
            [5, 6, 4, 3],
            [7, 6, 5, 3],
            [2, 7, 4, 7],
        ]);
        $LU = $A->luDecomposition();

        // Then
        $this->expectException(Exception\MathException::class);

        // When
        $doesNotExist = $LU->doesNotExist;
    }

    /**
     * @test   LU Decomposition solve incorrect type on b
     * @throws \Exception
     */
    public function testLUDecompositionSolveIncorrectTypeError()
    {
        // Given
        $A = MatrixFactory::create([
            [5, 3, 4, 1],
            [5, 6, 4, 3],
            [7, 6, 5, 3],
            [2, 7, 4, 7],
        ]);
        $LU = $A->luDecomposition();

        // And
        $b = new \stdClass();

        // Then
        $this->expectException(Exception\IncorrectTypeException::class);

        // When
        $LU->solve($b);
    }

    /**
     * @test         Permutation matrix verification - P should be orthogonal
     * @dataProvider dataProviderForLUDecomposition
     * @param        array $A
     * @throws       \Exception
     */
    public function testPermutationMatrixProperties(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $LU = $A->luDecomposition();

        // Then - P should be orthogonal (P * P^T = I)
        $P = $LU->P;
        $PT = $P->transpose();
        $PxPT = $P->multiply($PT);
        $I = MatrixFactory::identity($A->getN());

        $this->assertEqualsWithDelta($I->getMatrix(), $PxPT->getMatrix(), 0.0001);

        // And - determinant should be ±1
        $det = abs($P->det());
        $this->assertEqualsWithDelta(1.0, $det, 0.0001);
    }

    /**
     * @test         Round-trip accuracy test - solve Ax=b, then verify A*x ≈ b
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @throws       \Exception
     */
    public function testRoundTripAccuracy(array $A, array $b)
    {
        // Given
        $A  = MatrixFactory::create($A);
        $b_vec = new Vector($b);
        $LU = $A->luDecomposition();

        // When
        $x = $LU->solve($b);

        // Then - A*x should equal b
        $A_times_x = $A->multiply($x);
        $this->assertEqualsWithDelta($b_vec->getVector(), $A_times_x->getColumn(0), 0.0001);
    }
}
