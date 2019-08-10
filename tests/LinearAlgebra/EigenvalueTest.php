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
     */
    public function testClosedFormPolynomialRootMethod(array $A, array $S)
    {
        $A = MatrixFactory::create($A);
        $this->assertEquals($S, Eigenvalue::closedFormPolynomialRootMethod($A), '', 0.0001);
        $this->assertEquals($S, $A->eigenvalues(Eigenvalue::CLOSED_FORM_POLYNOMIAL_ROOT_METHOD), '', 0.0001);
    }

    /**
     * @test         jacobiMethod returns the expected eigenvalues
     * @dataProvider dataProviderForSymmetricEigenvalues
     * @param        array $A
     * @param        array $S
     */
    public function testJacobiMethod(array $A, array $S)
    {
        $A = MatrixFactory::create($A);
        $this->assertEquals($S, Eigenvalue::jacobiMethod($A), '', 0.0001);
        $this->assertEquals($S, $A->eigenvalues(Eigenvalue::JACOBI_METHOD), '', 0.0001);
    }

    /**
     * @test         powerIterationMethod returns the expected eigenvalues
     * @dataProvider dataProviderForEigenvalues
     * @param        array $A
     * @param        array $S
     * @param        float $max_abs_eigenvalue maximum absolute eigenvalue
     */
    public function testPowerIteration(array $A, array $S, float $max_abs_eigenvalue)
    {
        $A = MatrixFactory::create($A);
        $this->assertEquals([$max_abs_eigenvalue], Eigenvalue::powerIteration($A), '', 0.0001);
        $this->assertEquals([$max_abs_eigenvalue], $A->eigenvalues(Eigenvalue::POWER_ITERATION), '', 0.0001);
    }

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
                [4, 5],
                5,
            ],
            [
                [
                    [1, -2],
                    [-2, 0],
                ],
                [(1 - sqrt(17))/2, (1 + sqrt(17))/2],
                (1 + sqrt(17))/2,
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
                [2, 1, 2],
                2,
            ],
            [
                [
                    [1, 2, 1],
                    [6, -1, 0],
                    [-1, -2, -1],
                ],
                [3, -4, 0],
                -4,
            ],
            [
                [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ],
                [(3*(5 + sqrt(33)))/2, (-3*(sqrt(33) - 5))/2, 0],
                3*(5 + sqrt(33))/2,
            ],
        ];
    }

    /**
     * @test         closedFormPolynomialRootMethod throws a BadDataException if the matrix is not the correct size (2x2 or 3x3)
     * @dataProvider dataProviderForEigenvalueException
     * @param        array $A
     */
    public function testClosedFormPolynomialRootMethodExceptionMatrixNotCorrectSize(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->expectException(Exception\BadDataException::class);
        Eigenvalue::closedFormPolynomialRootMethod($A);
    }

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
        ];
    }

    /**
     * @test Matrix eigenvalues throws a MatrixException if the eigenvalue method is not valid
     */
    public function testMatrixEigenvalueInvalidMethodException()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $invalidMethod = 'SecretMethod';

        $this->expectException(Exception\MatrixException::class);
        $A->eigenvalues($invalidMethod);
    }

    /**
     * @testCase     JacobiMethod throws a BadDataException if the matrix is not the correct size.
     * @dataProvider dataProviderForSymmetricException
     * @param        array $A
     */
    public function testJacobiExceptionMatrixNotCorrectSize(array $A)
    {
        $A = MatrixFactory::create($A);
        $this->expectException(Exception\BadDataException::class);
        Eigenvalue::jacobiMethod($A);
    }

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
     */
    public function testPowerIterationFail(array $A)
    {
        $A = MatrixFactory::create($A);
        $this->expectException(Exception\FunctionFailedToConvergeException::class);
        Eigenvalue::powerIteration($A, 0);
    }

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
}
