<?php
namespace Math\LinearAlgebra;

class MatrixDecompositionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForLUDecomposition
     * Unit test data created from online calculator: https://www.easycalculation.com/matrix/lu-decomposition-matrix.php
     */
    public function testLUDecomposition(array $A, array $L, array $U, array $P)
    {
        $A = MatrixFactory::create($A);
        $L = MatrixFactory::create($L);
        $U = MatrixFactory::create($U);
        $P = MatrixFactory::create($P);

        $LU = $A->LUDecomposition();

        $this->assertEquals($L, $LU['L'], '', 0.001);
        $this->assertEquals($U, $LU['U'], '', 0.001);
        $this->assertEquals($P, $LU['P'], '', 0.001);
    }

    public function dataProviderForLUDecomposition()
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
     * @dataProvider dataProviderForLUDecomposition
     * Unit test data created from online calculator: https://www.easycalculation.com/matrix/lu-decomposition-matrix.php
     */
    public function testLUDecompositionPivotize(array $A, array $_, array $__, array $P)
    {
        $A = MatrixFactory::create($A);
        $P = MatrixFactory::create($P);

        $LU = $A->LUDecomposition();

        $this->assertEquals($P, $LU['P'], '', 0.000001);
    }

    public function testLUDecompositionExceptionNotSquare()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
        ]);
        $this->setExpectedException('\Exception');
        $A->LUDecomposition();
    }

    /**
     * @dataProvider dataProviderForRREF
     */
    public function testRREF(array $A, array $R)
    {
        $A = MatrixFactory::create($A);
        $R = MatrixFactory::create($R);

        $this->assertEquals($R, $A->rref());
    }

    public function dataProviderForRREF()
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [
                    [1, 0, -1],
                    [0, 1, 2],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, 3, -1],
                    [0, 1, 7],
                ],
                [
                    [1, 0, -22],
                    [0, 1, 7],
                ],
            ],
            [
                [
                    [1, 2, 1],
                    [-2, -3, 1],
                    [3, 5, 0],
                ],
                [
                    [1, 0, -5],
                    [0, 1, 3],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [0, 3, -6, 6, 4, -5],
                    [3, -7, 8, -5, 8, 9],
                    [3, -9, 12, -9, 6, 15],
                ],
                [
                    [1, 0, -2, 3, 0, -24],
                    [0, 1, -2, 2, 0, -7],
                    [0, 0, 0, 0, 1, 4],
                ],
            ],
            [
                [
                    [0, 2, 8, -7],
                    [2, -2, 4, 0],
                    [-3, 4, -2, -5],
                ],
                [
                    [1, 0, 6, 0],
                    [0, 1, 4, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [1, -2, 3, 9],
                    [-1, 3, 0, -4],
                    [2, -5, 5, 17],
                ],
                [
                    [1, 0, 0, 1],
                    [0, 1, 0, -1],
                    [0, 0, 1, 2],
                ],
            ],
            [
                [
                    [1, 0, -2, 1, 0],
                    [0, -1, -3, 1, 3],
                    [-2, -1, 1, -1, 3],
                    [0, 3, 9, 0, -12],
                ],
                [
                    [1, 0, -2, 0, 1],
                    [0, 1, 3, 0, -4],
                    [0, 0, 0, 1, -1],
                    [0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [1, 1, 4, 1, 2],
                    [0, 1, 2, 1, 1],
                    [0, 0, 0, 1, 2],
                    [1, -1, 0, 0, 2],
                    [2, 1, 6, 0, 1],
                ],
                [
                    [1, 0, 2, 0, 1],
                    [0, 1, 2, 0, -1],
                    [0, 0, 0, 1, 2],
                    [0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [1, 2, 0, -1, 1, -10],
                    [1, 3, 1, 1, -1, -9],
                    [2, 5, 1, 0, 0, -19],
                    [3, 6, 0, 0, -6, -27],
                    [1, 5, 3, 5, -5, -7],
                ],
                [
                    [1, 0, -2, 0, -10, -7],
                    [0, 1, 1, 0, 4, -1],
                    [0, 0, 0, 1, -3, 1],
                    [0, 0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                  [-4, 3, 1, 5, -8],
                  [6, 0, 9, 2, 6],
                  [-1, 4, 4, 0, 2],
                  [8, -1, 3, 4, 0],
                  [5, 9, -7, -7, 1],
                ],
                [
                  [1, 0, 0, 0, 0],
                  [0, 1, 0, 0, 0],
                  [0, 0, 1, 0, 0],
                  [0, 0, 0, 1, 0],
                  [0, 0, 0, 0, 1],
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForSolve
     */
    public function testSolveArray(array $A, array $b, array $expected)
    {
        $A        = MatrixFactory::create($A);
        $expected = new Vector($expected);
        $x        = $A->solve($b);

        $this->assertEquals($expected, $x, '', 0.001);
    }

    /**
     * @dataProvider dataProviderForSolve
     */
    public function testSolveVector(array $A, array $b, array $expected)
    {
        $A        = MatrixFactory::create($A);
        $b        = new Vector($b);
        $expected = new Vector($expected);
        $x        = $A->solve($b);

        $this->assertEquals($expected, $x, '', 0.001);
    }

    /**
     * @dataProvider dataProviderForSolve
     * Compute the inverse before trying to solve.
     */
    public function testSolveInverse(array $A, array $b, array $expected)
    {
        $A        = MatrixFactory::create($A);
        $b        = new Vector($b);
        $expected = new Vector($expected);

        $A->inverse();
        $x = $A->solve($b);

        $this->assertEquals($expected, $x, '', 0.001);
    }

    /**
     * @dataProvider dataProviderForSolve
     * Compute the RREF before trying to solve.
     */
    public function testSolveRREF(array $A, array $b, array $expected)
    {
        $A        = MatrixFactory::create($A);
        $b        = new Vector($b);
        $expected = new Vector($expected);

        $A->rref();
        $x = $A->solve($b);

        $this->assertEquals($expected, $x, '', 0.001);
    }

    public function dataProviderForSolve()
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
                [1/2, 2/3, -5/6],
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
                [-101.485, 101.242, 115.727, 102.394],
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
                [-15.354, 15.813, -1.770, -22.148, -6.660],
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
        ];
    }

    /**
     * @dataProvider dataProviderForSolveExceptionNotVectorOrArray
     */
    public function testSolveExceptionNotVectorOrArray($b)
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        $this->setExpectedException('\Exception');
        $A->solve($b);
    }

    public function dataProviderForSolveExceptionNotVectorOrArray()
    {
        return [
            [new Matrix([[1], [2], [3]])],
            [25],
        ];
    }

    public function testRREFAlreadyComputed()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);
        $rref1 = $A->rref(); // computes rref
        $rref2 = $A->rref(); // simply gets already-computed rref

        $this->assertEquals($rref1, $rref2);
    }
}
