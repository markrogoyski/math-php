<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\Exception;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Eigenvector;
use MathPHP\LinearAlgebra\Eigenvalue;

class EigenvectorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     eigenvector using closedFormPolynomialRootMethod returns the expected eigenvalues
     * @dataProvider dataProviderForEigenvector
     * @param        array $A
     * @param        array $S
     */
    public function testEigenvectorsUsingClosedFormPolynomialRootMethod(array $A, array $S)
    {
        $A = MatrixFactory::create($A);
        $this->assertEquals($S, Eigenvector::eigenvectors($A)->getMatrix(), '', 0.0001);
        $this->assertEquals($S, $A->eigenvectors(Eigenvalue::CLOSED_FORM_POLYNOMIAL_ROOT_METHOD)->getMatrix(), '', 0.0001);
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
                    [1/sqrt(293), 2/sqrt(6), 2/sqrt(14)],
                    [6/sqrt(293), 1/sqrt(6), -3/sqrt(14)],
                    [16/sqrt(293), -1/sqrt(6), -1/sqrt(14)],
                ]
            ],
            [ // RREF is a zero matrix
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ]
            ],
            [ // Matrix has duplicate eigenvalues. One vector is on an axis.
                [
                    [2, 0, 1],
                    [2, 1, 2],
                    [3, 0, 4],
                ],
                [
                    [0, 1/sqrt(14), \M_SQRT1_2],
                    [1, 2/sqrt(14), 0],
                    [0, 3/sqrt(14), -1 * \M_SQRT1_2],
                ]
            ],
            [ // Matrix has duplicate eigenvalues. no solution on the axis
                [
                    [2, 2, -3],
                    [2, 5, -6],
                    [3, 6, -8],
                ],
                [
                    [1/\M_SQRT3, 1/sqrt(14), 5/sqrt(42)],
                    [1/\M_SQRT3, 2/sqrt(14), -4/sqrt(42)],
                    [1/\M_SQRT3, 3/sqrt(14), -1/sqrt(42)],
                ]
            ],
            [ // The top row of the rref has a solitary 1 in position 0,0
                [
                    [4, 1, 2],
                    [0, 0, -2],
                    [2, 2, 5],
                ],
                [
                    [ 5/sqrt(65), 0, 1/3],
                    [-2/sqrt(65), -2/sqrt(5), 2/3],
                    [6/sqrt(65), 1/sqrt(5), -2/3],
                ]
            ],
        ];
    }

    /**
     * @testCase eigenvectors throws a BadDataException when the matrix is not square
     */
    public function testEigenvectorMatrixNotCorrectSize()
    {
        $A = MatrixFactory::create([[1,2]]);

        $this->expectException(Exception\BadDataException::class);
        Eigenvector::eigenvectors($A, [0]);
    }

    /**
     * @testCase     eigenvectors throws a BadDataException when the array of eigenvales is too long or short
     * @dataProvider dataProviderForIncorrectNumberOfEigenvectors
     * @param        array $A
     * @param        array $B
     */
    public function testIncorrectNumberOfEigenvectors(array $A, array $B)
    {
        $A = MatrixFactory::create($A);

        $this->expectException(Exception\BadDataException::class);
        Eigenvector::eigenvectors($A, $B);
    }

    public function dataProviderForIncorrectNumberOfEigenvectors(): array
    {
        return [
            [
                [
                    [0, 1],
                    [-2, -3],
                ],
                [1,2,3],
            ],
        ];
    }

    /**
     * @testCase     eigenvectors throws a BadDataException when an eigenvalue that is provided is not a number
     * @dataProvider dataProviderForEigenvectorNotNumeric
     * @param        array $A
     * @param        array $V
     */
    public function testEigenvectorNotNumeric(array $A, array $B)
    {
        $A = MatrixFactory::create($A);

        $this->expectException(Exception\BadDataException::class);
        Eigenvector::eigenvectors($A, $B);
    }

    public function dataProviderForEigenvectorNotNumeric(): array
    {
        return [
            [
                [
                    [0, 1],
                    [-2, -3],
                ],
                ["test"],
            ],
        ];
    }

    /**
     * @testCase     eigenvectors throws a BadDataException when there is an incorrect eigenvalue provided
     * @dataProvider dataProviderForEigenvectorNotAnEigenvector
     * @param        array $A
     * @param        array $B
     */
    public function testEigenvectorNotAnEigenvector(array $A, array $B)
    {
        $A = MatrixFactory::create($A);

        $this->expectException(Exception\BadDataException::class);
        Eigenvector::eigenvectors($A, $B);
    }

    public function dataProviderForEigenvectorNotAnEigenvector(): array
    {
        return [
            [
                [
                    [0, 1],
                    [-2, -3],
                ],
                [-2, 0],
            ],
            [
                [
                    [0, 1],
                    [-2, -3],
                ],
                [0, -3],
            ],
        ];
    }

    /**
     * @testCase Matrix eigenvectors throws a MatrixException if the eigenvalue method is not valid
     */
    public function testMatrixEigenvectorInvalidMethodException()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $invalidMethod = 'SecretMethod';

        $this->expectException(Exception\MatrixException::class);
        $A->eigenvectors($invalidMethod);
    }
}
