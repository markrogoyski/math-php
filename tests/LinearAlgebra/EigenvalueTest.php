<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

class EigenvalueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @testCase     quadratic returns the expected eigenvalues
     * @dataProvider dataProviderForEigenvalues
     */
    public function testEigenvalues(array $A, array $S)
    {
        $A = MatrixFactory::create($A);
        $this->assertEquals($S, Eigenvalue::eigenvalue($A), '', 0.0001);
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
     * @testCase     quadratic throws a BadDataException if the matrix is not the correct size (2x2 or 3x3)
     * @dataProvider dataProviderForEigenvalueException
     */
    public function testQuadraticExceptionMatrixNotCorrectSize(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->setExpectedException(Exception\BadDataException::class);
        Eigenvalue::eigenvalue($A);
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
     * @testCase     quadratic returns the expected eigenvalues
     * @dataProvider dataProviderForEigenvector
     */
    public function testEigenvectors(array $A, array $S)
    {
        $A = MatrixFactory::create($A);
        $this->assertEquals($S, Eigenvalue::eigenvector($A)->getMatrix(), '', 0.0001);
    }

    public function dataProviderForEigenvector(): array
    {
        return [
            [
                [
                    [0, 1],
                    [-2, -3],
                ],
                [
                    [1/sqrt(5), \M_SQRT1_2],
                    [-2/sqrt(5), -\M_SQRT1_2],
                ]
            ],
            [
                [
                    [6, -1],
                    [2, 3],
                ],
                [
                    [1/sqrt(5), \M_SQRT1_2],
                    [2/sqrt(5), \M_SQRT1_2],
                ]
            ],
            [
                [
                    [-2, -4, 2],
                    [-2, 1, 2],
                    [4, 2, 5],
                ],
                [
                    [2/sqrt(6), 2/sqrt(14), 1/sqrt(293)],
                    [1/sqrt(6), -3/sqrt(14), 6/sqrt(293)],
                    [-1/sqrt(6), -1/sqrt(14), 16/sqrt(293)],
                ]
            ],
        ];
    }
}
