<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\Exception;

class MatrixDecompositionsTest extends \PHPUnit\Framework\TestCase
{
    use \MathPHP\Tests\LinearAlgebra\MatrixDataProvider;

    /**
     * @test         LU decomposition
     * @dataProvider dataProviderForLUDecomposition
     * Unit test data created from online calculator: https://www.easycalculation.com/matrix/lu-decomposition-matrix.php
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
        $this->assertEquals($L, $LU->L, '', 0.001);
        $this->assertEquals($U, $LU->U, '', 0.001);
        $this->assertEquals($P, $LU->P, '', 0.001);

        // And
        $this->assertTrue($LU->L->isLowerTriangular());
        $this->assertTrue($LU->U->isUpperTriangular());
    }

    /**
     * @return array
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
     * @testCase     choleskyDecomposition computes the expected lower triangular matrix
     * @dataProvider dataProviderForCholeskyDecomposition
     * @param        array $A
     * @param        array $expected_L
     * @throws       \Exception
     */
    public function testCholeskyDecomposition(array $A, array $expected_L)
    {
        // Given
        $A           = MatrixFactory::create($A);
        $expected_L  = MatrixFactory::create($expected_L);
        $expected_Lᵀ = $expected_L->transpose();

        // When
        $cholesky = $A->choleskyDecomposition();
        $L        = $cholesky->L;
        $Lᵀ       = $cholesky->LT;

        // Then
        $this->assertEquals($expected_L->getMatrix(), $L->getMatrix(), '', 0.00001);
        $this->assertEquals($expected_Lᵀ->getMatrix(), $Lᵀ->getMatrix(), '', 0.00001);

        // And LLᵀ = A
        $LLᵀ = $L->multiply($Lᵀ);
        $this->assertEquals($A->getMatrix(), $LLᵀ->getMatrix());
    }

    /**
     * @return array
     */
    public function dataProviderForCholeskyDecomposition(): array
    {
        return [
            // Example data from Wikipedia
            [
                [
                    [4, 12, -16],
                    [12, 37, -43],
                    [-16, -43, 98],
                ],
                [
                    [2, 0, 0],
                    [6, 1, 0],
                    [-8, 5, 3],
                ],
            ],
            // Example data from rosettacode.org
            [
                [
                    [25, 15, -5],
                    [15, 18,  0],
                    [-5,  0, 11],
                ],
                [
                    [5, 0, 0],
                    [3, 3, 0],
                    [-1, 1, 3],
                ],
            ],
            [
                [
                    [18, 22,  54,  42],
                    [22, 70,  86,  62],
                    [54, 86, 174, 134],
                    [42, 62, 134, 106],
                ],
                [
                    [4.24264,  0.00000, 0.00000, 0.00000],
                    [5.18545,  6.56591, 0.00000, 0.00000],
                    [12.72792, 3.04604, 1.64974, 0.00000],
                    [9.89949,  1.62455, 1.84971, 1.39262],
                ],
            ],
            // Example data from https://ece.uwaterloo.ca/~dwharder/NumericalAnalysis/04LinearAlgebra/cholesky/
            [
                [
                    [5, 1.2, 0.3, -0.6],
                    [1.2, 6, -0.4, 0.9],
                    [0.3, -0.4, 8, 1.7],
                    [-0.6, 0.9, 1.7, 10],
                ],
                [
                    [2.236067977499790, 0, 0, 0],
                    [0.536656314599949,   2.389979079406345, 0, 0],
                    [0.134164078649987,  -0.197491268466351,   2.818332343581848, 0],
                    [-0.268328157299975,   0.436823907370487,   0.646577012719190,  3.052723872310221],
                ],
            ],
            [
                [
                    [9.0000,  0.6000,  -0.3000,   1.5000],
                    [0.6000,  16.0400,  1.1800,  -1.5000],
                    [-0.3000, 1.1800,   4.1000,  -0.5700],
                    [1.5000, -1.5000,  -0.5700,  25.4500],
                ],
                [
                    [3, 0, 0, 0],
                    [0.2, 4, 0, 0],
                    [-0.1, 0.3, 2, 0],
                    [0.5, -0.4, -0.2, 5],
                ],
            ],
            // Example data created with http://calculator.vhex.net/post/calculator-result/cholesky-decomposition
            [
                [
                    [2, -1],
                    [-1, 2],
                ],
                [
                    [1.414214, 0],
                    [-0.707107, 1.224745],
                ],
            ],
            [
                [
                    [1, -1],
                    [-1, 4],
                ],
                [
                    [1, 0],
                    [-1, 1.732051],
                ],
            ],
            [
                [
                    [6, 4],
                    [4, 5],
                ],
                [
                    [2.44949, 0],
                    [1.632993, 1.527525],
                ],
            ],
            [
                [
                    [4, 1, -1],
                    [1, 2, 1],
                    [-1, 1, 2],
                ],
                [
                    [2, 0, 0],
                    [0.5, 1.322876, 0],
                    [-0.5, 0.944911, 0.92582],
                ],
            ],
            [
                [
                    [9, -3, 3, 9],
                    [-3, 17, -1, -7],
                    [3, -1, 17, 15],
                    [9, -7, 15, 44],
                ],
                [
                    [3, 0, 0, 0],
                    [-1, 4, 0, 0],
                    [1, 0, 4, 0],
                    [3, -1, 3, 5],
                ],
            ],
        ];
    }

    /**
     * @test   choleskyDecomposition throws a MatrixException if the matrix is not positive definite
     * @throws \Exception
     */
    public function testCholeskyDecompositionException()
    {
        // Given
        $A = [
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ];
        $A = MatrixFactory::create($A);

        // Then
        $this->expectException(Exception\MatrixException::class);

        // When
        $L = $A->choleskyDecomposition();
    }

    /**
     * @test   Cholesky Decomposition invalid property
     * @throws \Exception
     */
    public function testCholeskyDecompositionInvalidProperty()
    {
        // Given
        $A = MatrixFactory::create([
            [4, 1, -1],
            [1, 2, 1],
            [-1, 1, 2],
        ]);
        $cholesky = $A->choleskyDecomposition();

        // Then
        $this->expectException(Exception\MathException::class);

        // When
        $doesNotExist = $cholesky->doesNotExist;
    }

    /**
     * @test         croutDecomposition returns the expected array of L and U factorized matrices
     * @dataProvider dataProviderForCroutDecomposition
     * @param        array $A
     * @param        array $expected
     * @throws       \Exception
     */
    public function testCroutDecomposition(array $A, array $expected)
    {
        // Given
        $A = MatrixFactory::create($A);
        $L = MatrixFactory::create($expected['L']);
        $U = MatrixFactory::create($expected['U']);

        // When
        $lu = $A->croutDecomposition();

        // Then
        $this->assertEquals($L->getMatrix(), $lu->L->getMatrix(), '', 0.00001);
        $this->assertEquals($U->getMatrix(), $lu->U->getMatrix(), '', 0.00001);
    }

    /**
     * @return array
     */
    public function dataProviderForCroutDecomposition(): array
    {
        return [
            [
                [
                    [4, 0, 1],
                    [2, 1, 0],
                    [2, 2, 3],
                ],
                [
                    'L' => [
                        [4, 0, 0],
                        [2, 1, 0],
                        [2, 2, 7 / 2],
                    ],
                    'U' => [
                        [1, 0, 1 / 4],
                        [0, 1, -1 / 2],
                        [0, 0, 1],
                    ],
                ],
            ],
            [
                [
                    [5, 4, 1],
                    [10, 9, 4],
                    [10, 13, 15],
                ],
                [
                    'L' => [
                        [5, 0, 0],
                        [10, 1, 0],
                        [10, 5, 3],
                    ],
                    'U' => [
                        [1, 4 / 5, 1 / 5],
                        [0, 1, 2],
                        [0, 0, 1],
                    ],
                ],
            ],
            [
                [
                    [2, -4, 1],
                    [6, 2, -1],
                    [-2, 6, -2],
                ],
                [
                    'L' => [
                        [2, 0, 0],
                        [6, 14, 0],
                        [-2, 2, -0.428571],
                    ],
                    'U' => [
                        [1, -2, 0.5],
                        [0, 1, -0.285714],
                        [0, 0, 1],
                    ],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [2, 20, 26],
                    [3, 26, 70],
                ],
                [
                    'L' => [
                        [1, 0, 0],
                        [2, 16, 0],
                        [3, 20, 36],
                    ],
                    'U' => [
                        [1, 2, 3],
                        [0, 1, 5 / 4],
                        [0, 0, 1],
                    ],
                ],
            ],
            [
                [
                    [2, -1, 3],
                    [1, 3, -1],
                    [2, -2, 5],
                ],
                [
                    'L' => [
                        [2, 0, 0],
                        [1, 7 / 2, 0],
                        [2, -1, 9 / 7],
                    ],
                    'U' => [
                        [1, -1 / 2, 3 / 2],
                        [0, 1, -5 / 7],
                        [0, 0, 1],
                    ],
                ],
            ],
        ];
    }

    /**
     * @test    croutDecomposition throws a MatrixException if the det(L) is close to zero
     * @throws \Exception
     */
    public function testCroutDecompositionException()
    {
        // Given
        $A = MatrixFactory::create([
            [3, 4],
            [6, 8],
        ]);

        // Then
        $this->expectException(Exception\MatrixException::class);

        // When
        $lu = $A->croutDecomposition();
    }

    /**
     * @test   Crout Decomposition invalid property
     * @throws \Exception
     */
    public function testCountDecompositionInvalidProperty()
    {
        // Given
        $A = MatrixFactory::create([
            [4, 1, -1],
            [1, 2, 1],
            [-1, 1, 2],
        ]);
        $crout = $A->croutDecomposition();

        // Then
        $this->expectException(Exception\MathException::class);

        // When
        $doesNotExist = $crout->doesNotExist;
    }

    /**
     * @test         qrDecomposition returns the expected array of Q and R factorized matrices
     * @dataProvider dataProviderForQrDecomposition
     * @param        array $A
     * @param        array $expected
     * @throws       \Exception
     */
    public function testQrDecomposition(array $A, array $expected)
    {
        // Given
        $A = MatrixFactory::create($A);
        $Q = MatrixFactory::create($expected['Q']);
        $R = MatrixFactory::create($expected['R']);

        // When
        $qr  = $A->qrDecomposition();
        $qrQ = $qr->Q;
        $qrR = $qr->R;

        // Then A = QR
        $this->assertEquals($A->getMatrix(), $qrQ->multiply($qrR)->getMatrix(), '', 0.00001);

        // And Q and R are expected solution to QR decomposition
        $this->assertEquals($R->getMatrix(), $qrR->getMatrix(), '', 0.00001);
        $this->assertEquals($Q->getMatrix(), $qrQ->getMatrix(), '', 0.00001);
    }

    /**
     * @return array
     */
    public function dataProviderForQrDecomposition(): array
    {
        return [
            [
                [
                    [2, -2, 18],
                    [2, 1, 0],
                    [1, 2, 0],
                ],
                [
                    'Q' => [
                        [-2 / 3,  2 / 3, 1 / 3],
                        [-2 / 3, -1 / 3, -2 / 3],
                        [-1 / 3, -2 / 3, 2 / 3],
                    ],
                    'R' => [
                        [-3,  0, -12],
                        [ 0, -3,  12],
                        [ 0,  0,  6],
                    ],
                ],
            ],
            [
                [
                    [12, -51,    4],
                    [ 6,  167, -68],
                    [-4,  24,  -41],
                ],
                [
                    'Q' => [
                        [ -0.85714286,  0.39428571,  0.33142857],
                        [ -0.42857143, -0.90285714, -0.03428571],
                        [0.28571429, -0.17142857,  0.94285714],
                    ],
                    'R' => [
                        [-14,  -21, 14],
                        [ 0, -175, 70],
                        [ 0,   0,  -35],
                    ],
                ],
            ],
            [
                [
                    [2, -2, -3],
                    [0, -6, -1],
                    [0, 0, 1],
                    [0, 0, 4],
                ],
                [
                    'Q' => [
                        [-1.0, 0.0, 0.0],
                        [0.0, -1.0, 0.0],
                        [0.0, 0.0, -1 / sqrt(17)],
                        [0.0, 0.0, -4 / sqrt(17)],
                    ],
                    'R' => [
                        [-2.0, 2.0, 3.0],
                        [0.0, 6.0, 1.0],
                        [0.0, 0.0, -1 * sqrt(17)],
                    ],
                ]
            ],
            [
                [
                    [0],
                    [0],
                ],
                [
                    'Q' => [
                        [1],
                        [0],
                    ],
                    'R' => [
                        [0],
                    ],
                ],
            ],
            [
                [
                    [1,0,0],
                    [0,0,0],
                    [0,0,0],
                    [0,0,0],
                ],
                [
                    'Q' => [
                        [-1,0,0],
                        [0,1,0],
                        [0,0,1],
                        [0,0,0],
                    ],
                    'R' => [
                        [-1,0,0],
                        [0,0,0],
                        [0,0,0],
                    ],
                ],
            ],
            [
                [
                    [0],
                ],
                [
                    'Q' => [
                        [1],
                    ],
                    'R' => [
                        [0],
                    ],
                ],
            ],
            [
                [
                    [1],
                ],
                [
                    'Q' => [
                        [1],
                    ],
                    'R' => [
                        [1],
                    ],
                ],
            ],
            [
                [
                    [1],
                    [1],
                ],
                [
                    'Q' => [
                        [-0.7071068],
                        [-0.7071068],
                    ],
                    'R' => [
                        [-1.414214],
                    ],
                ],
            ],
        ];
    }

    /**
     * @test         qrDecomposition properties
     * @dataProvider dataProviderForQrDecompositionResultingInSquareMatrices
     * @param        array $A
     * @throws       \Exception
     */
    public function testQrDecompositionProperties(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $qr = $A->qrDecomposition();

        // Then
        $this->assertTrue($qr->R->isUpperTriangular(), 'Failed asserting that R is upper-triangular.');
        $this->assertTrue($qr->Q->isOrthogonal());
    }

    /**
     * @return array
     */
    public function dataProviderForQrDecompositionResultingInSquareMatrices(): array
    {
        return [
            [
                [
                    [2, -2, 18],
                    [2, 1, 0],
                    [1, 2, 0],
                ],
                [
                    'Q' => [
                        [-2 / 3,  2 / 3, 1 / 3],
                        [-2 / 3, -1 / 3, -2 / 3],
                        [-1 / 3, -2 / 3, 2 / 3],
                    ],
                    'R' => [
                        [-3,  0, -12],
                        [ 0, -3,  12],
                        [ 0,  0,  6],
                    ],
                ],
            ],
            [
                [
                    [12, -51,    4],
                    [ 6,  167, -68],
                    [-4,  24,  -41],
                ],
                [
                    'Q' => [
                        [ -0.85714286,  0.39428571,  0.33142857],
                        [ -0.42857143, -0.90285714, -0.03428571],
                        [0.28571429, -0.17142857,  0.94285714],
                    ],
                    'R' => [
                        [-14,  -21, 14],
                        [ 0, -175, 70],
                        [ 0,   0,  -35],
                    ],
                ],
            ],
        ];
    }

    /**
     * @test   QR Decomposition invalid property
     * @throws \Exception
     */
    public function testQRDecompositionInvalidProperty()
    {
        // Given
        $A = MatrixFactory::create([
            [4, 1, -1],
            [1, 2, 1],
            [-1, 1, 2],
        ]);
        $qr = $A->qrDecomposition();

        // Then
        $this->expectException(Exception\MathException::class);

        // When
        $doesNotExist = $qr->doesNotExist;
    }
}
