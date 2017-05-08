<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

class EigenvalueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @testCase     quadratic returns the expected eigenvalues
     * @dataProvider dataProviderForEigenvalues
     */
    public function testQuadratic(array $A, array $S)
    {
        $A = MatrixFactory::create($A);
        $this->assertEquals($S, Eigenvalue::quadratic($A)->getDiagonalElements(), '', 0.0001);
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
     * @dataProvider dataProviderForQuadraticException
     */
    public function testQuadraticExceptionMatrixNotCorrectSize(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->setExpectedException(Exception\BadDataException::class);
        Eigenvalue::quadratic($A);
    }

    public function dataProviderForQuadraticException(): array
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
}
