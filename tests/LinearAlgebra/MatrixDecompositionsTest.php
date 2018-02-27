<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Exception;

class MatrixDecompositionsTest extends \PHPUnit\Framework\TestCase
{
    use \MathPHP\Tests\LinearAlgebra\MatrixDataProvider;

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

        $LU = $A->luDecomposition();

        $this->assertEquals($L, $LU['L'], '', 0.001);
        $this->assertEquals($U, $LU['U'], '', 0.001);
        $this->assertEquals($P, $LU['P'], '', 0.001);
    }

    public function dataProviderForLuDecomposition()
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

        $LU = $A->luDecomposition();

        $this->assertEquals($P, $LU['P'], '', 0.000001);
    }

    public function testLUDecompositionExceptionNotSquare()
    {
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
        ]);
        $this->expectException(Exception\MatrixException::class);
        $A->luDecomposition();
    }

    /**
     * @dataProvider dataProviderForRref
     */
    public function testRref(array $A, array $R)
    {
        $A    = MatrixFactory::create($A);
        $R    = MatrixFactory::create($R);
        $rref = $A->rref();

        $this->assertEquals($R, $rref, '', 0.000001);
        $this->assertTrue($rref->isRref());
        $this->assertTrue($rref->isRef());
    }

    public function dataProviderForRref()
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
            [
                [
                    [4, 7],
                    [2, 6],
                ],
                [
                    [1, 0],
                    [0, 1],
                ],
            ],
            [
                [
                    [4, 3],
                    [3, 2],
                ],
                [
                    [1, 0],
                    [0, 1],
                ],
            ],
            [
                [
                    [1, 2],
                    [3, 4],
                ],
                [
                    [1, 0],
                    [0, 1],
                ],
            ],
            [
                [
                    [3, 1],
                    [3, 4],
                ],
                [
                    [1, 0],
                    [0, 1],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [0, 4, 5],
                    [1, 0, 6],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [7, 2, 1],
                    [0, 3, -1],
                    [-3, 4, -2],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [3, 6, 6, 8],
                    [4, 5, 3, 2],
                    [2, 2, 2, 3],
                    [6, 8, 4, 2],
                ],
                [
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 1, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [0, 0],
                    [0, 1],
                ],
                [
                    [0, 1],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 1, 1, 1, 1],
                    [0, 1, 1, 1, 1],
                    [0, 0, 0, 0, 1],
                ],
                [
                    [1, 0, 0, 0, 0],
                    [0, 1, 1, 1, 0],
                    [0, 0, 0 ,0, 1],
                ],
            ],
            [
                [
                    [0, 0],
                    [1, 1],
                    [-1, 0],
                    [0, -1],
                    [0, 0],
                    [0, 0],
                    [0, 0],
                    [0, 0],
                    [1, 1],
                ],
                [
                    [1, 0],
                    [0, 1],
                    [0, 0],
                    [0, 0],
                    [0, 0],
                    [0, 0],
                    [0, 0],
                    [0, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [1,  2,  3,  4,  3,  1],
                    [2,  4,  6,  2,  6,  2],
                    [3,  6, 18,  9,  9, -6],
                    [4,  8, 12, 10, 12,  4],
                    [5, 10, 24, 11, 15, -4],
                ],
                [
                    [1,  2,  0,  0,  3, 4],
                    [0,  0,  1,  0,  0, -1],
                    [0,  0,  0,  1,  0, 0],
                    [0,  0,  0,  0,  0, 0],
                    [0,  0,  0,  0,  0, 0],
                ],
            ],
            [
                [
                    [1, 2, 3, 4, 3, 1],
                    [2, 4, 6, 2, 6, 2],
                    [3, 6, 18, 9, 9, -6],
                    [4, 8, 12, 10, 12, 4],
                    [5, 10, 24, 11, 15, -4]
                ],
                [
                    [1, 2, 0, 0, 3, 4],
                    [0, 0, 1, 0, 0, -1],
                    [0, 0, 0, 1, 0, 0],
                    [0, 0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [0, 1],
                    [1, 2],
                    [0, 5],
                ],
                [
                    [1, 0],
                    [0, 1],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 0, 1, 0, 1, 0],
                    [1, 0, 1, 0, 0, 1],
                    [1, 0, 0, 1, 1, 0],
                    [1, 0, 0, 1, 0, 1],
                    [0, 1, 0, 1, 1, 0],
                    [0, 1, 0, 1, 0, 1],
                    [0, 1, 1, 0, 1, 0],
                    [0, 1, 1, 0, 0, 1],
                ],
                [
                    [1, 0, 0, 1, 0, 1],
                    [0, 1, 0, 1, 0, 1],
                    [0, 0, 1, -1, 0, 0],
                    [0, 0, 0, 0, 1, -1],
                    [0, 0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [3, -4, 2],
                    [-2, 6, 2],
                    [4, 2, 10],
                ],
                [
                    [1, 0, 2],
                    [0, 1, 1],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [3, 4, 2],
                    [-2, 6, 2],
                    [4, 2, 10],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [3, -4, 2],
                    [2, 6, 2],
                    [4, 2, 10],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [3, -4, 2],
                    [2, 6, 2],
                    [-4, 2, 10],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [3, -4, 2],
                    [-2, 6, 2],
                    [-4, 2, 10],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [-3, -4, 2],
                    [-2, 6, 2],
                    [4, 2, 10],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [2, 0, -1, 0, 0],
                    [1, 0, 0, -1, 0],
                    [3, 0, 0, -2, -1],
                    [0, 1, 0, 0, -2],
                    [0, 1, -1, 0, 0]
                ],
                [
                    [1, 0, 0, 0, -1],
                    [0, 1, 0, 0, -2],
                    [0, 0, 1, 0, -2],
                    [0, 0, 0, 1, -1],
                    [0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [2, -1, 4, 3, 2, 3, 4, 4],
                    [-1, 2, 3, 2, 1, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 4, 4],
                    [2, 1, 2, 1, 2, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 1, 2],
                    [4, 3, 4, 3, 2, 1, 2, 2],
                    [4, 3, 4, 3, 2, 2, 2, 2],
                ],
                [
                    [1, 0, 0, 0, 0, 0, 0, 0],
                    [0, 1, 0, 0, 0, 0, 0, 0],
                    [0, 0, 1, 0, 0, 0, 0, 0],
                    [0, 0, 0, 1, 0, 0, 0, 0],
                    [0, 0, 0, 0, 1, 0, 0, 0],
                    [0, 0, 0, 0, 0, 1, 0, 0],
                    [0, 0, 0, 0, 0, 0, 1, 0],
                    [0, 0, 0, 0, 0, 0, 0, 1],
                ],
            ],
            [
                [
                    [5, -7, 6],
                    [-9, 5, 5],
                    [1, 3, 11],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [1, 2, 3, 0, 0, 0],
                    [0, 0, 1, 1, 0, 1],
                    [0, 0, 0, 1, 1, 1],
                ],
                [
                    [1, 2, 0, 0, 3, 0],
                    [0, 0, 1, 0, -1, 0],
                    [0, 0, 0, 1, 1, 1],
                ],
            ],
            [
                [
                    [1, 2, 1],
                    [-2, -3, 1],
                    [3, 5, 0]
                ],
                [
                    [1, 0, -5],
                    [0, 1, 3],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [-7, -6, -12, -33],
                    [5, 5, 7, 24],
                    [1, 0, 4, 5],
                ],
                [
                    [1, 0, 0, -3],
                    [0, 1, 0, 5],
                    [0, 0, 1, 2],
                ],
            ],
            [
                [
                    [1, -1, 2, 1],
                    [2, 1, 1, 8],
                    [1, 1, 0, 5],
                ],
                [
                    [1, 0, 1, 3],
                    [0, 1, -1, 2],
                    [0, 0, 0, 0],
                ],
            ],
            [
                [
                    [2, 1, 7, -7, 2],
                    [-3, 4, -5, -6, 3],
                    [1, 1, 4, -5, 2],
                ],
                [
                    [1, 0, 3, -2, 0],
                    [0, 1, 1, -3, 0],
                    [0, 0, 0, 0, 1],
                ],
            ],
            [
                [
                    [2, -3, 1, 7, 14],
                    [2, 8, -4, 5, -1],
                    [1, 3, -3, 0, 4],
                    [-5, 2, 3, 4, -19],
                ],
                [
                    [1, 0, 0, 0, 1],
                    [0, 1, 0, 0, -3],
                    [0, 0, 1, 0, -4],
                    [0, 0, 0, 1, 1],
                ],
            ],
            [
                [
                    [3, 4, -1, 2, 6],
                    [1, -2, 3, 1, 2],
                    [0, 10, -10, -1, 1],
                ],
                [
                    [1, 0, 1, 4/5, 0],
                    [0, 1, -1, -1/10, 0],
                    [0, 0, 0, 0, 1],
                ],
            ],
            [
                [
                    [2, 4, 5, 7, -26],
                    [1, 2, 1, -1, -4],
                    [-2, -4, 1, 11, -10],
                ],
                [
                    [1, 2, 0, -4, 2],
                    [0, 0, 1, 3, -6],
                    [0, 0, 0, 0 ,0],
                ],
            ],
            [
                [
                    [1, 2, 8, -7, -2],
                    [3, 2, 12, -5, 6],
                    [-1, 1, 1, -5, -10],
                ],
                [
                    [1, 0, 2, 1, 0],
                    [0, 1, 3, -4, 0],
                    [0, 0, 0, 0, 1],
                ],
            ],
            [
                [
                    [2, 1, 7, -2, 4],
                    [3, -2, 0, 11, 13],
                    [1, 1, 5, -3, 1],
                ],
                [
                    [1, 0, 2, 1, 3],
                    [0, 1, 3, -4, -2],
                    [0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [2, 3, -1, -9, -16],
                    [1, 2, 1, 0, 0],
                    [-1, 2, 3, 4, 8],
                ],
                [
                    [1, 0, 0, 2, 3],
                    [0, 1, 0, -3, -5],
                    [0, 0, 1, 4, 7],
                ],
            ],
            [
                [
                    [2, 3, 19, -4, 2],
                    [1, 2, 12, -3, 1],
                    [-1, 2, 8, -5, 1],
                ],
                [
                    [1, 0, 2, 1, 0],
                    [0, 1, 5, -2, 0],
                    [0, 0, 0 ,0, 1],
                ],
            ],
            [
                [
                    [-1, 5, 0, 0, -8],
                    [-2, 5, 5, 2, 9],
                    [-3, -1, 3, 1, 3],
                    [7, 6, 5, 1, 30],
                ],
                [
                    [1, 0, 0, 0, 3],
                    [0, 1, 0, 0, -1],
                    [0, 0, 1, 0, 2],
                    [0, 0, 0, 1, 5],
                ],
            ],
            [
                [
                    [1, 2, -4, -1, 0, 32],
                    [1, 3, -7, 0, -1, 33],
                    [1, 0, 2, -2, 3, 22],
                ],
                [
                    [1, 0, 2, 0, 5, 6],
                    [0, 1, -3, 0, -2, 9],
                    [0, 0, 0, 1, 1, -8],
                ],
            ],
            [
                [
                    [2, 1, 6],
                    [-1, -1, -2],
                    [3, 4, 4],
                    [3, 5, 2],
                ],
                [
                    [1, 0, 4],
                    [0, 1, -2],
                    [0, 0, 0],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [2, 1, 5, 10],
                    [1, -3, -1, -2],
                    [4, -2, 6, 12],
                ],
                [
                    [1, 0, 2, 4],
                    [0, 1, 1, 2],
                    [0, 0, 0, 0],
                ],
            ],
            [
                [
                    [1, 2, -4],
                    [-3, -1, -3],
                    [-2, 1, -7],
                ],
                [
                    [1, 0, 2],
                    [0, 1, -3],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, 1, 1],
                    [-4, -3, -2],
                    [3, 2, 1],
                ],
                [
                    [1, 0, -1],
                    [0, 1, 2],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, 2, -1, -1],
                    [2, 4, -1, 4],
                    [-1, -2, 3, 5],
                ],
                [
                    [1, 2, 0, 0],
                    [0, 0, 1, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [1, 1, -1, 1],
                    [2, 1, -1, 3],
                    [1, 4, -4, -2],
                    [2, 0, 1, 2],
                ],
                [
                    [1, 0, 0, 2],
                    [0, 1, 0, -3],
                    [0, 0, 1, -2],
                    [0, 0, 0, 0],
                ],
            ],
            [
                [
                    [4, 4, 2, 108],
                    [1, 1, 1, 30],
                    [2, -1, 0, 0],
                ],
                [
                    [1, 0, 0, 8],
                    [0, 1, 0, 16],
                    [0, 0, 1, 6],
                ],
            ],
            [
                [
                    [1, 1, 1, 1, 66],
                    [1, -4, 0, 0, 0],
                    [4, 4, 2, 2, 252],
                ],
                [
                    [1, 0, 0, 0, 48],
                    [0, 1, 0, 0, 12],
                    [0, 0, 1, 1, 6],
                ],
            ],
        ];
    }

    /**
     * @testCase     isRref on rref matrix should return true
     * @dataProvider dataProviderForNonsingularMatrix
     */
    public function testRrefIsRref(array $A)
    {
        $A   = MatrixFactory::create($A);
        $rref = $A->rref();

        $this->assertTrue($rref->isRref());
        $this->assertTrue($rref->isRef());
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
    public function testSolveRref(array $A, array $b, array $expected)
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

        $this->expectException(Exception\IncorrectTypeException::class);
        $A->solve($b);
    }

    public function dataProviderForSolveExceptionNotVectorOrArray()
    {
        return [
            [new Matrix([[1], [2], [3]])],
            [25],
        ];
    }

    /**
     * @testCase     isRef on ref matrix should return true
     * @dataProvider dataProviderForNonsingularMatrix
     */
    public function testRefIsRef(array $A)
    {
        $A   = MatrixFactory::create($A);
        $ref = $A->ref();

        $this->assertTrue($ref->isRef());
    }

    /**
     * @testCase rref lazy load is the same as the computed and returned value.
     */
    public function testRrefAlreadyComputed()
    {
        $A = new Matrix([
            [ 4,  1,  2,  -3],
            [-3,  3, -1,   4],
            [-1,  2,  5,   1],
            [ 5,  4,  3,  -1],
        ]);
        $rref1 = $A->rref(); // computes rref
        $rref2 = $A->rref(); // simply gets already-computed rref

        $this->assertEquals($rref1, $rref2);
    }

    /**
     * @testCase     Test ref by solving the system of linear equations.
     *               There is no single row echelon form for a matrix (as opposed to reduced row echelon form).
     *               Therefore, instead of directly testing the REF obtained,
     *               use the REF to then solve for x using back substitution.
     *               The result should be the expected solution to the system of linear equations.
     * @dataProvider dataProviderForSolve
     * @param        array $A
     * @param        array $b
     * @param        array $expected_x
     */
    public function testRefUsingSolve(array $A, array $b, array $expected_x)
    {
        $m        = count($b);
        $A        = MatrixFactory::create($A);
        $b_matrix = MatrixFactory::create([new Vector($b)]);
        $Ab       = $A->augment($b_matrix);
        $ref      = $Ab->ref();

        // Solve for x using back substituion on the REF matrix
        $x = [];
        for ($i = $m - 1; $i >= 0; $i--) {
            $x[$i] = $ref[$i][$m];
            for ($j = $i + 1; $j < $m; $j++) {
                $x[$i] -= $ref[$i][$j] * $x[$j];
            }
            $x[$i] /= $ref[$i][$i];
        }

        $this->assertEquals($expected_x, $x, '', 0.001);

        // As an extra check, solve the original matrix and compare the result.
        $solved_x = $A->solve($b);
        $this->assertEquals($x, $solved_x->getVector(), '', 0.00001);
    }

    /**
     * @testCase ref lazy load is the same as the computed and returned value.
     */
    public function testRefAlreadyComputed()
    {
        $A = new Matrix([
            [ 4,  1,  2,  -3],
            [-3,  3, -1,   4],
            [-1,  2,  5,   1],
            [ 5,  4,  3,  -1],
        ]);
        $ref1 = $A->ref(); // computes ref
        $ref2 = $A->ref(); // simply gets already-computed ref

        $this->assertEquals($ref1, $ref2);
    }

    /**
     * @testCase     choleskyDecomposition computes the expected lower triangular matrix
     * @dataProvider dataProviderForCholeskyDecomposition
     * @param        array $A
     * @param        array $expected_L
     */
    public function testCholeskyDecomposition(array $A, array $expected_L)
    {
        $A          = MatrixFactory::create($A);
        $expected_L = MatrixFactory::create($expected_L);
        $L          = $A->choleskyDecomposition();

        $this->assertEquals($expected_L, $L, '', 0.00001);
        $this->assertEquals($expected_L->getMatrix(), $L->getMatrix(), '', 0.00001);
    }

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
     * @testCase choleskyDecomposition throws a MatrixException if the matrix is not positive definite
     */
    public function testCholeskyDecompositionException()
    {
        $A = [
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ];
        $A = MatrixFactory::create($A);

        $this->expectException(Exception\MatrixException::class);
        $L = $A->choleskyDecomposition();
    }

    /**
     * @dataProvider dataProviderForRowReductionToEchelonForm
     */
    public function testRowReductionToEchelonForm(array $A, array $R)
    {
        $method = new \ReflectionMethod(Matrix::class, 'rowReductionToEchelonForm');
        $method->setAccessible(true);

        $A   = MatrixFactory::create($A);
        $R   = MatrixFactory::create($R);
        $ref = MatrixFactory::create($method->invoke($A));

        $this->assertEquals($R, $ref, '', 0.000001);
        $this->assertTrue($ref->isRef());
    }

    public function dataProviderForRowReductionToEchelonForm(): array
    {
        return [
            [
                [
                    [1, 2, 0],
                    [-1, 1, 1],
                    [1, 2, 3],
                ],
                [
                    [1, 2, 0],
                    [0, 1, 1/3],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [0, 2, 0],
                    [-1, 1, 1],
                    [1, 2, 3],
                ],
                [
                    [1, -1, -1],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [0, 2, 0],
                    [0, 1, 1],
                    [1, 2, 3],
                ],
                [
                    [1, 2, 3],
                    [0, 1, 1],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [1, 2, 0],
                    [0, 1, 1],
                    [0, 2, 3],
                ],
                [
                    [1, 2, 0],
                    [0, 1, 1],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [2, 5, 4],
                    [2, 4, 6],
                    [8, 7, 5],
                    [6, 4, 5],
                    [6, 2, 3],
                ],
                [
                    [1, 5/2, 2],
                    [0, 1, -2],
                    [0, 0, 1],
                    [0, 0, 0],
                    [0, 0, 0],
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
                    [1, 0, -2, 1, 0],
                    [0, 1, 3, -1, -3],
                    [0, 0, 0, 1, -1],
                    [0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [5, 4, 8],
                    [7, 7, 5],
                    [6, 2, 4],
                ],
                [
                    [1, 4/5, 8/5],
                    [0, 1, -31/7],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [2, 0, -1, 0, 0],
                    [1, 0, 0, -1, 0],
                    [3, 0, 0, -2, -1],
                    [0, 1, 0, 0, -2],
                    [0, 1, -1, 0, 0]
                ],
                [
                    [1, 0, -1/2, 0, 0],
                    [0, 1, 0, 0, -2],
                    [0, 0, 1, -4/3, -2/3],
                    [0, 0, 0, 1, -1],
                    [0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [2, -1, 4, 3, 2, 3, 4, 4],
                    [-1, 2, 3, 2, 1, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 4, 4],
                    [2, 1, 2, 1, 2, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 1, 2],
                    [4, 3, 4, 3, 2, 1, 2, 2],
                    [4, 3, 4, 3, 2, 2, 2, 2],
                ],
                [
                    [1, -1/2, 2, 3/2, 1, 3/2, 2, 2],
                    [0, 1, 10/3, 7/3, 4/3, 7/3, 10/3, 10/3],
                    [0, 0, 1, 25/34, 13/34, 11/17, 31/34, 31/34],
                    [0, 0, 0, 1, -11/5, 18/5, 13/5, 13/5],
                    [0, 0, 0, 0, 1, 2, 2, 2],
                    [0, 0, 0, 0, 0, 1, 1, 1],
                    [0, 0, 0, 0, 0, 0, 1, 1/2],
                    [0, 0, 0, 0, 0, 0, 0, 1],
                ],
            ],
            [
                [
                    [0]
                ],
                [
                    [0],
                ],
            ],
            [
                [
                    [1]
                ],
                [
                    [1],
                ],
            ],
            [
                [
                    [5]
                ],
                [
                    [1],
                ],
            ],
            [
                [
                    [0, 0],
                    [0, 0]
                ],
                [
                    [0, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [0, 0],
                    [0, 1]
                ],
                [
                    [0, 1],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 0],
                    [0, 0]
                ],
                [
                    [1, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [0, 0],
                    [1, 0]
                ],
                [
                    [1, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [0, 0],
                    [1, 1]
                ],
                [
                    [1, 1],
                    [0, 0],
                ],
            ],
            [
                [
                    [0, 1],
                    [0, 1]
                ],
                [
                    [0, 1],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 0],
                    [1, 0]
                ],
                [
                    [1, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 1],
                    [1, 1]
                ],
                [
                    [1, 1],
                    [0, 0],
                ],
            ],
            [
                [
                    [2, 6],
                    [1, 3]
                ],
                [
                    [1, 3],
                    [0, 0],
                ],
            ],
            [
                [
                    [3, 6],
                    [1, 2]
                ],
                [
                    [1, 2],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 2],
                    [1, 2]
                ],
                [
                    [1, 2],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [
                    [1, 2, 3],
                    [0, 1, 2],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, 2, 1],
                    [-2, -3, 1],
                    [3, 5, 0],
                ],
                [
                    [1, 2, 1],
                    [0, 1, 3],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, -1, 2],
                    [2, 1, 1],
                    [1, 1, 0],
                ],
                [
                    [1, -1, 2],
                    [0, 1, -1],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, 0, 1],
                    [0, 1, -1],
                    [0, 0, 0],
                ],
                [
                    [1, 0, 1],
                    [0, 1, -1],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [1, 3, 1],
                    [3, 4, 7],
                ],
                [
                    [1, 2, 3],
                    [0, 1, -2],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [1, 0, 0],
                    [-2, 0, 0],
                    [4, 6, 1],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 1/6],
                    [0, 0, 0],
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
                    [1, 1, 4, 1, 2],
                    [0, 1, 2, 1, 1],
                    [0, 0, 0, 1, 2],
                    [0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0],
                ],
            ],
            // This is an interesting case because of the miniscule values that are for all intents and purposes zero.
            // If the miniscule zero-like values are not handled properly, the zero value will be used as a pivot,
            // instead of being interchanged with a non-zero row.
            [
                [
                    [0, 1, 4, 2, 3, 3, 4, 4],
                    [1, 0, 3, 1, 2, 2, 3, 3],
                    [4, 3, 0, 2, 3, 3, 4, 4],
                    [3, 2, 1, 1, 2, 2, 3, 3],
                    [2, 1, 2, 0, 1, 1, 2, 2],
                    [3, 2, 3, 1, 2, 0, 1, 2],
                    [4, 3, 4, 2, 3, 1, 0, 2],
                    [4, 3, 4, 2, 3, 2, 2, 0],
                ],
                [
                    [1, 0, 3, 1, 2, 2, 3, 3],
                    [0, 1, 4, 2, 3, 3, 4, 4],
                    [0, 0, 1, 1/3, 7/12, 7/12, 5/6, 5/6],
                    [0, 0, 0, 1, 1, 1, 1, 1],
                    [0, 0, 0, 0, 1, 5, 6, 4],
                    [0, 0, 0, 0, 0, 1, 0, 0],
                    [0, 0, 0, 0, 0, 0, 1, -1],
                    [0, 0, 0, 0, 0, 0, 0, 0],
                ],
            ],
        ];
    }

    /**
     * @testCase     croutDecomposition returns the expected array of L and U factorized matrices
     * @dataProvider dataProviderForCroutDecomposition
     * @param        array $A
     * @param        array $expected
     */
    public function testCroutDecomposition(array $A, array $expected)
    {
        $A = MatrixFactory::create($A);
        $L = MatrixFactory::create($expected['L']);
        $U = MatrixFactory::create($expected['U']);

        $lu = $A->croutDecomposition();

        $this->assertEquals($L->getMatrix(), $lu['L']->getMatrix(), '', 0.00001);
        $this->assertEquals($U->getMatrix(), $lu['U']->getMatrix(), '', 0.00001);
    }

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
                        [2, 2, 7/2],
                    ],
                    'U' => [
                        [1, 0, 1/4],
                        [0, 1, -1/2],
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
                        [1, 4/5, 1/5],
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
                        [0, 1, 5/4],
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
                        [1, 7/2, 0],
                        [2, -1, 9/7],
                    ],
                    'U' => [
                        [1, -1/2, 3/2],
                        [0, 1, -5/7],
                        [0, 0, 1],
                    ],
                ],
            ],
        ];
    }

    /**
     * @testCase croutDecomposition throws a MatrixException if the det(L) is close to zero
     */
    public function testCroutDecompositionException()
    {
        $A = MatrixFactory::create([
            [3, 4],
            [6, 8],
        ]);

        $this->expectException(Exception\MatrixException::class);
        $lu = $A->croutDecomposition();
    }
}
