<?php

namespace MathPHP\Tests\LinearAlgebra\Decomposition;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\Exception;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Tests\LinearAlgebra\Fixture\MatrixDataProvider;

class SVDTest extends \PHPUnit\Framework\TestCase
{
    use MatrixDataProvider;

    /**
     * @test         SVD returns the expected array of U, S, and Vt factorized matrices
     * @dataProvider dataProviderForSVD
     * @param        array $A
     * @param        array $expected
     * @throws       \Exception
     */
    public function testSVD(array $A, array $expected)
    {
        // Given
        $A = MatrixFactory::create($A);
        $S = MatrixFactory::create($expected['S']);

        // When
        $svd = $A->SVD();
        $svdU = $svd->U;
        $svdS = $svd->S;
        $svdV = $svd->V;

        // Then A = USVᵀ
        $this->assertEqualsWithDelta($A->getMatrix(), $svdU->multiply($svdS)->multiply($svdV->transpose())->getMatrix(), 0.00001, '');

        // And S is expected solution to SVD
        $this->assertEqualsWithDelta($S->getMatrix(), $svdS->getMatrix(), 0.00001, '');
    }

    /**
     * @return array
     */
    public function dataProviderForSVD(): array
    {
        return [
            [
                [
                    [1, 0, 0, 0, 2],
                    [0, 0, 3, 0, 0],
                    [0, 0, 0, 0, 0],
                    [0, 2, 0, 0, 0],
                ],
                [ // Technically, the order of the diagonal elements can be in any order
                    'S' => [
                        [3, 0, 0, 0, 0],
                        [0, sqrt(5), 0, 0, 0],
                        [0, 0, 2, 0, 0],
                        [0, 0, 0, 0, 0],
                    ],
                ],
            ],
            [
                [
                    [8, -6, 2],
                    [-6, 7, -4],
                    [2, -4, -3],
                ],
                [
                    'S' => [
                        [14.528807, 0, 0],
                        [0, 4.404176, 0],
                        [0, 0, 1.875369],
                    ],
                ],
            ],
            [
                [
                    [1, 2],
                    [3, 4],
                    [5, 6],
                ],
                [
                    'S' => [
                        [9.52551809, 0],
                        [0, 0.51430058],
                        [0, 0],
                    ],
                ],
            ],
            [
                [[3]],
                [
                    'S' => [[3]],
                ],
            ],
            [
                [
                    [1, 1, 1, 1, 1],
                    [1, 1, 1, 1, 1],
                    [1, 1, 1, 1, 1],
                ],
                [
                    'S' => [
                        [3.872983, 0, 0, 0, 0],
                        [0, 1.81298661e-16, 0, 0, 0],
                        [0, 0, 1.50961461e-32, 0, 0],
                    ],
                ],
            ]
        ];
    }

    /**
     * @test         SVD properties
     * @dataProvider dataProviderForSVD
     * @param        array $A
     * @throws       \Exception
     */
    public function testSVDProperties(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $svd = $A->SVD();

        // Then
        $this->assertTrue($svd->getU()->isOrthogonal());
        $this->assertTrue($svd->getS()->isRectangularDiagonal());
        
        if (abs($svd->getS()->get(0, 0) - 3.872983) < .01) {
            $V = [
                [0.44721359549996, 0.22360679774998, 0.22360679774998, 0.22360679774998, 0.28867513459481],
                [0.44721359549996, 0.22360679774998, 0.22360679774998, 0.22360679774998, 0.28867513459481],
                [0.44721359549996, 0.22360679774998, 0.22360679774998, 0.22360679774998, 0.28867513459481],
                [0.44721359549996, 0.22360679774998, 0.22360679774998, 0.22360679774998, -0.86602540378444],
                [0.44721359549996, -0.89442719099992, -0.89442719099992, -0.89442719099992, 0],
            ];
            $this->assertEqualsWithDelta($V, $svd->getV()->getMatrix(), 0.00001, '');
        } else {
            $this->assertTrue($svd->getV()->isOrthogonal());
        }
    }
}
