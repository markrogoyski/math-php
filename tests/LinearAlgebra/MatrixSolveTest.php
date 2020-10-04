<?php

namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Exception;

class MatrixSolveTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         Solve array
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected
     * @throws       \Exception
     */
    public function testSolveArray(array $A, array $b, array $expected)
    {
        // Given
        $A        = MatrixFactory::create($A);
        $expected = new Vector($expected);

        // When
        $x = $A->solve($b);

        // Then
        $this->assertEquals($expected, $x, '', 0.00001);
    }

    /**
     * @test         Solve vector
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected
     * @throws       \Exception
     */
    public function testSolveVector(array $A, array $b, array $expected)
    {
        // Given
        $A        = MatrixFactory::create($A);
        $b        = new Vector($b);
        $expected = new Vector($expected);

        // When
        $x = $A->solve($b);

        // Then
        $this->assertEquals($expected, $x, '', 0.00001);
    }

    /**
     * @test         Compute the inverse before trying to solve
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected
     * @throws       \Exception
     */
    public function testSolveInverse(array $A, array $b, array $expected)
    {
        // Given
        $A        = MatrixFactory::create($A);
        $b        = new Vector($b);
        $expected = new Vector($expected);

        // When
        $A->inverse();
        $x = $A->solve($b);

        // Then
        $this->assertEquals($expected, $x, '', 0.00001);
    }

    /**
     * @test         Compute the RREF before trying to solve.
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected
     * @throws       \Exception
     */
    public function testSolveRref(array $A, array $b, array $expected)
    {
        // Given
        $A        = MatrixFactory::create($A);
        $b        = new Vector($b);
        $expected = new Vector($expected);

        // When
        $A->rref();
        $x = $A->solve($b);

        // Then
        $this->assertEquals($expected, $x, '', 0.00001);
    }

    /**
     * Test cases generated using various online sources and various applications.
     * For example, SciPy scipy.linalg.solve(a,b)
     * @return array (A, b, x) for Ax = b
     */
    public function dataProviderForSolve(): array
    {
        return [
            [
                [
                    [3, 4],
                    [2, -1],
                ],
                [5, 7],
                [3, -1],
            ],
            [
                [
                    [3, 1],
                    [2, -1],
                ],
                [5, 0],
                [1, 2],
            ],
            [
                [
                    [3, 4],
                    [5, 3],
                ],
                [-2, 4],
                [2, -2],
            ],
            [
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
                [2, 3, -4],
                [2, 3, -4],
            ],
            [
                [
                    [1, 1, -1],
                    [3, 1, 1],
                    [1, -1, 4],
                ],
                [1, 9, 8],
                [3, -1, 1],
            ],
            [
                [
                    [2, 4, 1],
                    [4, -10, 2],
                    [1, 2, 4],
                ],
                [5, -8, 13],
                [-1, 1, 3],
            ],
            [
                [
                    [1, 1, 1],
                    [0, 2, 5],
                    [2, 5, -1],
                ],
                [6, -4, 27],
                [5, 3, -2],
            ],
            [
                [
                    [1, 2, 3],
                    [2, -1, 1],
                    [3, 0, -1],
                ],
                [9, 8, 3],
                [2, -1, 3],
            ],
            [
                [
                    [2, 1, -3],
                    [4, -2, 1],
                    [3, 5, -2],
                ],
                [-4, 9, 5],
                [2, 1, 3],
            ],
            [
                [
                    [4, 9, 0],
                    [8, 0, 6],
                    [0, 6, 6],
                ],
                [8, -1, -1],
                [1 / 2, 2 / 3, -5 / 6],
            ],
            [
                [
                    [1, 1, 1],
                    [1, -2, 2],
                    [1, 2, -1],
                ],
                [0, 4, 2],
                [4, -2, -2],
            ],
            [
                [
                    [3, 3, 4],
                    [3, 5, 9],
                    [5, 9, 17],
                ],
                [1, 2, 4],
                [1, -2, 1],
            ],
            [
                [
                    [2, 1, 1],
                    [-1, 1, -1],
                    [1, 2, 3],
                ],
                [2, 3, -10],
                [3, 1, -5],
            ],
            [
                [
                    [3, 2, 0],
                    [1, -1, 0],
                    [0, 5, 1],
                ],
                [2, 4, -1],
                [2, -2, 9],
            ],
            [
                [
                    [4, 1, 0],
                    [1, 4, 1],
                    [0, 1, 4],
                ],
                [1, 4, 1],
                [0, 1, 0],
            ],
            [
                [
                    [6, 4, 24],
                    [1, -1, 0],
                    [0, 5, 1]
                ],
                [2, 4, -1],
                [3.98181818, -0.01818182, -0.90909091],
            ],
            [
                [
                    [4, 2, -1, 3],
                    [3, -4, 2, 5],
                    [-2, 6, -5, -2],
                    [5, 1, 6, -3],
                ],
                [16.9, -14, 25, 9.4],
                [4.5, 1.6, -3.8, -2.7],
            ],
            [
                [
                    [4, 2, -1, 3],
                    [3, -4, 2, 5],
                    [-2, 6, -5, -2],
                    [5, 1, 6, -3],
                ],
                [-12, 34, 27, -19],
                [-101.48484848,  101.24242424,  115.72727273,  102.39393939],
            ],
            [
                [
                    [ 4,  1,  2,  -3],
                    [-3,  3, -1,   4],
                    [-1,  2,  5,   1],
                    [ 5,  4,  3,  -1],
                ],
                [-16, 20, -4, -10],
                [-1, 1, -2, 3],
            ],
            [
                [
                    [ 4,  1,  2,  -3,  5],
                    [-3,  3, -1,   4, -2],
                    [-1,  2,  5,   1,  3],
                    [ 5,  4,  3,  -1,  2],
                    [ 1, -2,  3,  -4,  5],
                ],
                [-16, 20, -4, -10,  3],
                [-15.35406699, 15.81339713, -1.77033493, -22.14832536, -6.66028708],
            ],
            [
                [
                    [1, 1, -2, 1, 3, -1],
                    [2, -1, 1, 2, 1, -3],
                    [1, 3, -3, -1, 2, 1],
                    [5, 2, -1, -1, 2, 1],
                    [-3, -1, 2, 3, 1, 3],
                    [4, 3, 1, -6, -3, -2],
                ],
                [4, 20, -15, -3, 16, -27],
                [1, -2, 3, 4, 2, -1],
            ],
            // SciPy test cases - scipy.linalg.solve(a, b)
            [
                [
                    [1, 20],
                    [-30, 4],
                ],
                [1, 0],
                [0.00662252, 0.04966887],
            ],
            [
                [
                    [1, 20],
                    [-30, 4],
                ],
                [0, 1],
                [-0.03311258,  0.00165563],
            ],
            [
                [
                    [1, 20],
                    [-30, 4],
                ],
                [2, 1],
                [-0.01986755,  0.10099338],
            ],
            [
                [
                    [1, 20],
                    [-30, 4],
                ],
                [-30, 4],
                [-0.33112583, -1.48344371],
            ],
            [
                [
                    [2, 3],
                    [3, 5],
                ],
                [1, 0],
                [5, -3],
            ],
            [
                [
                    [2, 3],
                    [3, 5],
                ],
                [0, 1],
                [-3, 2],
            ],
            [
                [
                    [1.80, 2.88, 2.05, -0.89],
                    [525.00, -295.00, -95.00, -380.00],
                    [1.58, -2.69, -2.90, -1.04],
                    [-1.11, -0.66, -0.59, 0.80],
                ],
                [9.52, 2435, .77, -6.22],
                [1, -1, 3, -5],
            ],
            [
                [
                    [1.80, 2.88, 2.05, -0.89],
                    [525.00, -295.00, -95.00, -380.00],
                    [1.58, -2.69, -2.90, -1.04],
                    [-1.11, -0.66, -0.59, 0.80],
                ],
                [18.47, 225, -13.28, -6.21],
                [3., 2., 4., 1.],
            ],
            [
                [
                    [1, 0],
                    [1, 2],
                ],
                [1, 1],
                [1, 0],
            ],
        ];
    }

    /**
     * @test         solve exception
     * @dataProvider dataProviderForSolveExceptionNotVectorOrArray
     * @throws       \Exception
     */
    public function testSolveExceptionNotVectorOrArray($b)
    {
        // Given
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        // Then
        $this->expectException(Exception\IncorrectTypeException::class);

        // When
        $A->solve($b);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function dataProviderForSolveExceptionNotVectorOrArray(): array
    {
        return [
            [new Matrix([[1], [2], [3]])],
            [25],
        ];
    }

    /**
     * @test         Test ref by solving the system of linear equations.
     *               There is no single row echelon form for a matrix (as opposed to reduced row echelon form).
     *               Therefore, instead of directly testing the REF obtained,
     *               use the REF to then solve for x using back substitution.
     *               The result should be the expected solution to the system of linear equations.
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected_x
     * @throws       \Exception
     */
    public function testRefUsingSolve(array $A, array $b, array $expected_x)
    {
        // Given
        $m        = count($b);
        $A        = MatrixFactory::create($A);
        $b_matrix = MatrixFactory::createFromVectors([new Vector($b)]);
        $Ab       = $A->augment($b_matrix);
        $ref      = $Ab->ref();

        // When solve for x using back substitution on the REF matrix
        $x = [];
        for ($i = $m - 1; $i >= 0; $i--) {
            $x[$i] = $ref[$i][$m];
            for ($j = $i + 1; $j < $m; $j++) {
                $x[$i] -= $ref[$i][$j] * $x[$j];
            }
            $x[$i] /= $ref[$i][$i];
        }

        // Then
        $this->assertEquals($expected_x, $x, '', 0.00001);

        // And as an extra check, solve the original matrix and compare the result.
        $solved_x = $A->solve($b);
        $this->assertEquals($x, $solved_x->getVector(), '', 0.00001);
    }

    /**
     * @test         After solving, multiplying Ax = b
     *               In Python you could do numpy.dot(A, x) == b for this verification
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @throws       \Exception
     */
    public function testAxEqualsBAfterSolving(array $A, array $b)
    {
        // Given
        $A = MatrixFactory::create($A);

        // And
        $x = $A->solve($b);

        // When Ax
        $Ax = $A->multiply($x);

        // Then Ax = b
        $this->assertEquals($b, $Ax->asVectors()[0]->getVector(), '', 0.00001);
    }
}
