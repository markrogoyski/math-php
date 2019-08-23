<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\Exception;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Eigenvalue;

class EigenvalueTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         closedFormPolynomialRootMethod returns the expected eigenvalues
     * @dataProvider dataProviderForEigenvalues
     * @param        array $A
     * @param        array $S
     * @throws       \Exception
     */
    public function testClosedFormPolynomialRootMethod(array $A, array $S)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $eigenvalues = Eigenvalue::closedFormPolynomialRootMethod($A);

        // Then
        $this->assertEquals($S, $eigenvalues, '', 0.0001);
        $this->assertEquals($S, $A->eigenvalues(Eigenvalue::CLOSED_FORM_POLYNOMIAL_ROOT_METHOD), '', 0.0001);
    }

    /**
     * @test         Matrix eigenvalues using closedFormPolynomialRootMethod returns the expected eigenvalues
     * @dataProvider dataProviderForEigenvalues
     * @param        array $A
     * @param        array $S
     * @throws       \Exception
     */
    public function testClosedFormPolynomialRootMethodViaMatrix(array $A, array $S)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $eigenvalues = $A->eigenvalues(Eigenvalue::CLOSED_FORM_POLYNOMIAL_ROOT_METHOD);

        // Then
        $this->assertEquals($S, $eigenvalues, '', 0.0001);
    }

    /**
     * @test         jacobiMethod returns the expected eigenvalues
     * @dataProvider dataProviderForSymmetricEigenvalues
     * @param        array $A
     * @param        array $S
     * @throws       \Exception
     */
    public function testJacobiMethod(array $A, array $S)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $eigenvalues = Eigenvalue::jacobiMethod($A);

        // Then
        $this->assertEquals($S, $eigenvalues, '', 0.0001);
    }

    /**
     * @test         Matrix eigenvalues using jacobiMethod returns the expected eigenvalues
     * @dataProvider dataProviderForSymmetricEigenvalues
     * @param        array $A
     * @param        array $S
     * @throws       \Exception
     */
    public function testJacobiMethodViaMatrix(array $A, array $S)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $eigenvalues = $A->eigenvalues(Eigenvalue::JACOBI_METHOD);

        // Then
        $this->assertEquals($S, $eigenvalues, '', 0.0001);
    }

    /**
     * @test         powerIterationMethod returns the expected eigenvalues
     * @dataProvider dataProviderForEigenvalues
     * @param        array $A
     * @param        array $S
     * @param        float $max_abs_eigenvalue maximum absolute eigenvalue
     * @throws       \Exception
     */
    public function testPowerIteration(array $A, array $S, float $max_abs_eigenvalue)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $eigenvalues = Eigenvalue::powerIteration($A);

        // Then
        $this->assertEquals([$max_abs_eigenvalue], $eigenvalues, '', 0.0001);
    }

    /**
     * @test         Matrix eigenvalues using powerIterationMethod returns the expected eigenvalues
     * @dataProvider dataProviderForEigenvalues
     * @param        array $A
     * @param        array $S
     * @param        float $max_abs_eigenvalue maximum absolute eigenvalue
     * @throws       \Exception
     */
    public function testPowerIterationViaMatrix(array $A, array $S, float $max_abs_eigenvalue)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $eigenvalues = $A->eigenvalues(Eigenvalue::POWER_ITERATION);

        // Then
        $this->assertEquals([$max_abs_eigenvalue], $eigenvalues, '', 0.0001);
    }

    /**
     * @return array
     */
    public function dataProviderForEigenvalues(): array
    {
        return [
            [
                [
                    [0, 1],
                    [-2, -3],
                ],
                [-2, -1],
                -2,
            ],
            [
                [
                    [6, -1],
                    [2, 3],
                ],
                [5, 4],
                5,
            ],
            [
                [
                    [1, -2],
                    [-2, 0],
                ],
                [(1 + sqrt(17)) / 2, (1 - sqrt(17)) / 2],
                (1 + sqrt(17)) / 2,
            ],
            [
                [
                    [-2, -4, 2],
                    [-2, 1, 2],
                    [4, 2, 5],
                ],
                [6, -5, 3],
                6,
            ],
            [
                [
                    [2, 0, 0],
                    [1, 2, 1],
                    [-1, 0, 1],
                ],
                [2, 2, 1],
                2,
            ],
            [
                [
                    [1, 2, 1],
                    [6, -1, 0],
                    [-1, -2, -1],
                ],
                [-4, 3, 0],
                -4,
            ],
            [
                [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ],
                [(3 * (5 + sqrt(33))) / 2, (-3 * (sqrt(33) - 5)) / 2, 0],
                3 * (5 + sqrt(33)) / 2,
            ],
            [
                [
                    [8, -6, 2],
                    [-6, 7, -4],
                    [2, -4, -3],
                ],
                [14.528807, -4.404176, 1.875369],
                14.528807,
            ],
        ];
    }

    /**
     * @test         closedFormPolynomialRootMethod throws a BadDataException if the matrix is not the correct size (2x2 or 3x3)
     * @dataProvider dataProviderForEigenvalueException
     * @param        array $A
     * @throws       \Exception
     */
    public function testClosedFormPolynomialRootMethodExceptionMatrixNotCorrectSize(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Eigenvalue::closedFormPolynomialRootMethod($A);
    }

    /**
     * @return array
     */
    public function dataProviderForEigenvalueException(): array
    {
        return [
            '1x1' => [
                [
                    [1],
                ],
            ],
            '5x5' => [
                [
                    [1, 2, 3, 4, 5],
                    [2, 3, 4, 5, 6],
                    [3, 4, 5, 6, 7],
                    [4, 5, 6, 7, 8],
                    [5, 6, 7, 8, 9],
                ]
            ],
            'not_square' => [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function dataProviderForSymmetricEigenvalues(): array
    {
        return [
            [
                [
                    [1, 4],
                    [4, 1],
                ],
                [5.000, -3.000],
            ],
            [
                [
                    [1, 1, 1],
                    [1, 2, 1],
                    [1, 1, 1],
                ],
                [2 + M_SQRT2, 2 - M_SQRT2, 0.00],
            ],
            [
                [
                    [0, 0, 0],
                    [0, 0, 0],
                    [0, 0, 0],
                ],
                [0, 0, 0],
            ],
            [
                [
                    [1, 1, 1],
                    [1, 1, 1],
                    [1, 1, 1],
                ],
                [3, 8.881784e-16, 0],
            ],
            [
                [
                    [4, -30, 60, -35],
                    [-30, 300, -675, 420],
                    [60, -675, 1620, -1050],
                    [-35, 420, -1050, 700],
                ],
                [2585.2538, 37.10149, 1.47805, .166642],
            ],
            [
                [
                    [4, -1, -1, -1],
                    [-1, 4, -1, -1],
                    [-1, -1, 4, -1],
                    [-1, -1, -1, 4],
                ],
                [5, 5, 5, 1],
            ],
            [
                [
                    [2, 7, 3],
                    [7, 9, 4],
                    [3, 4, 7],
                ],
                [16.065129, 4.287057, -2.352186],
            ],
            [
                [
                    [1, 5, 6, 8],
                    [5, 2, 7, 9],
                    [6, 7, 3, 10],
                    [8, 9, 10, 4],
                ],
                [25.527715, -7.381045, -4.652925, -3.493745],
            ],
            [
                [
                    [1, 7, 3, 6],
                    [7, 4, -5, 3],
                    [3, -5, 6, 2],
                    [6, 3, 2, 4],
                ],
                [13.6856756, 9.5813577, -7.2742130, -0.9928203],
            ],
            [
                [
                    [-12, -16, -18, 11, 11, 13, -20, 1],
                    [-16, -18, 10, -1, -18, 18, -16, 6],
                    [-18, 10, 4, -17, 2, -14, -11, -16],
                    [11, -1, -17, -1, -19, 5, 8, -20],
                    [11, -18, 2, -19, -13, 8, 5, -4],
                    [13, 18, -14, 5, 8, 10, -19, 19],
                    [-20, -16, -11, 8, 5, -19, 1, -3],
                    [1, 6, -16, -20, -4, 19, -3, 15],
                ],
                [53.85777, -49.65359, -48.21567, 35.73664, -33.18637, 22.86811, 15.47171, -10.87861],
            ],
            [
                [
                    [3, 0, 0, 0],
                    [0, 1, 0, 1],
                    [0, 0, 2, 0],
                    [0, 1, 0, 1],
                ],
                [3, 2, 2, 0],
            ],
            [
                [
                    [1, 0, 0],
                    [0, 2, 0],
                    [0, 0, 4],
                ],
                [4, 2, 1],
            ],
            [
                [
                    [8, -6, 2],
                    [-6, 7, -4],
                    [2, -4, -3],
                ],
                [14.528807, -4.404176, 1.875369],
            ],
        ];
    }

    /**
     * @test   Matrix eigenvalues throws a MatrixException if the eigenvalue method is not valid
     * @throws \Exception
     */
    public function testMatrixEigenvalueInvalidMethodException()
    {
        // Given
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $invalidMethod = 'SecretMethod';

        // Then
        $this->expectException(Exception\MatrixException::class);

        // When
        $A->eigenvalues($invalidMethod);
    }

    /**
     * @testCase     JacobiMethod throws a BadDataException if the matrix is not the correct size.
     * @dataProvider dataProviderForSymmetricException
     * @param        array $A
     * @throws       \Exception
     */
    public function testJacobiExceptionMatrixNotCorrectSize(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Eigenvalue::jacobiMethod($A);
    }

    /**
     * @return array
     */
    public function dataProviderForSymmetricException(): array
    {
        return [
            '1x1' => [
                [
                    [1],
                ],
            ],
            'not_symetric' => [
                [
                    [1, 2, 3, 4, 6],
                    [2, 3, 4, 5, 6],
                    [3, 4, 5, 6, 7],
                    [4, 5, 6, 7, 8],
                    [5, 6, 7, 8, 9],
                ]
            ],
            'not_square' => [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                ]
            ],
        ];
    }

    /**
     * @test         Power Iteration throws exception if number of iterations is exceeded
     * @dataProvider dataProviderForIterationFailure
     * @param        array $A
     * @throws       \Exception
     */
    public function testPowerIterationFail(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // Then
        $this->expectException(Exception\FunctionFailedToConvergeException::class);

        // When
        Eigenvalue::powerIteration($A, 0);
    }

    /**
     * @return array
     */
    public function dataProviderForIterationFailure(): array
    {
        return [
            [
                [
                    [4, -30, 60, -35],
                    [-30, 300, -675, 420],
                    [60, -675, 1620, -1050],
                    [-35, 420, -1050, 700],
                ],
            ],
        ];
    }

    /**
     * @test that a variety of matrix types can have eigenvalues calulated
     * @dataProvider dataProviderForSymmetricEigenvalues
     * @dataProvider dataProviderForEigenvalues
     * @dataProvider dataProviderForTriangularEigenvalues
     */
    public function testSmartEigenvalues(array $A, array $S)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $eigenvalues = $A->eigenvalues();

        // Then
        $this->assertEquals($S, $eigenvalues, '', 0.0001);
    }
    
    public function dataProviderForTriangularEigenvalues()
    {
        return [
            [
                [
                    [2, 0, 0, 0, 0, 0],
                    [4, 3, 0, 0, 0, 0],
                    [8, 2, 3, 0, 0, 0],
                    [1, 7, 3, 9, 0, 0],
                    [5, 4, 3, 2, 1, 0],
                    [1, 6, 2, 9, 3, 6],
                ],
                [9, 6, 3, 3, 2, 1],
            ],
            [
                [
                    [1, 0, 0, 1, 0, 0],
                    [0, 2, 0, 0, 1, 0],
                    [0, 0, 3, 0, 0, 1],
                    [0, 0, 0, 4, 0, 0],
                    [0, 0, 0, 0, 5, 0],
                    [0, 0, 0, 0, 0, 6],
                ],
                [6, 5, 4, 3, 2, 1],
            ],
            [
                [[6]],
                [6],
            ],
        ];
    }
}
