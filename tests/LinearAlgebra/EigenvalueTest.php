<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\Exception;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Eigenvalue;

class EigenvalueTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     closedFormPolynomialRootMethod returns the expected eigenvalues
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

    public function dataProviderForEigenvalues(): array
    {
        return [
            [
                [
                    [0, 1],
                    [-2, -3],
                ],
                [-2, -1],
            ],
            [
                [
                    [6, -1],
                    [2, 3],
                ],
                [4, 5],
            ],
            [
                [
                    [1, -2],
                    [-2, 0],
                ],
                [(1 - sqrt(17))/2, (1 + sqrt(17))/2],
            ],
            [
                [
                    [-2, -4, 2],
                    [-2, 1, 2],
                    [4, 2, 5],
                ],
                [6, -5, 3],
            ],
            [
                [
                    [2, 0, 0],
                    [1, 2, 1],
                    [-1, 0, 1],
                ],
                [2, 1, 2],
            ],
            [
                [
                    [1, 2, 1],
                    [6, -1, 0],
                    [-1, -2, -1],
                ],
                [3, -4, 0],
            ],
            [
                [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ],
                [(3*(5 + sqrt(33)))/2, (-3*(sqrt(33) - 5))/2, 0],
            ],
        ];
    }

    /**
     * @testCase     closedFormPolynomialRootMethod throws a BadDataException if the matrix is not the correct size (2x2 or 3x3)
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

    /**
     * @testCase Matrix eigenvalues throws a MatrixException if the eigenvalue method is not valid
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
}
