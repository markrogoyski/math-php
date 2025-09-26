<?php

namespace MathPHP\Tests\LinearAlgebra\Matrix\Other;

use MathPHP\LinearAlgebra\Householder;
use MathPHP\LinearAlgebra\MatrixFactory;

class HouseholderTest extends \PHPUnit\Framework\TestCase
{
    use \MathPHP\Tests\LinearAlgebra\Fixture\MatrixDataProvider;

    /**
     * @test         Householder transformation creates a matrix that is involutory
     * @dataProvider dataProviderForHouseholder
     * @param        array $A
     * @throws       \Exception
     */
    public function testHouseholderTransformMatrixInvolutoryProperty(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $H = Householder::transform($A);

        // Then
        $this->assertTrue($H->isInvolutory());
    }

    /**
     * @test         Householder transformation creates a matrix with a determinant that is -1
     * @dataProvider dataProviderForHouseholder
     * @param        array $A
     * @throws       \Exception
     */
    public function testHouseholderTransformMatrixDeterminant(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $H = Householder::transform($A);

        // Then
        $this->assertEqualsWithDelta(-1, $H->det(), 0.000001);
    }

    /**
     * @test         Householder transformation creates a matrix that has eigenvalues 1 and -1
     * @dataProvider dataProviderForHouseholder
     * @param        array $A
     * @throws       \Exception
     */
    public function testHouseholderTransformMatrixEigenvalues(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $H = Householder::transform($A);

        // Then
        $eigenvalues = \array_filter(
            $H->eigenvalues(),
            function ($x) {
                return !\is_nan($x);
            }
        );
        $this->assertEqualsWithDelta(1, max($eigenvalues), 0.00001);
        $this->assertEqualsWithDelta(-1, \min($eigenvalues), 0.00001);
    }

    /**
     * @return array
     */
    public function dataProviderForHouseholder(): array
    {
        return [
            [
               [
                    [4, 1, -2, 2],
                    [1, 2, 0, 1],
                    [-2, 0, 3, -2],
                    [2, 1, -2, -1],
                ]
            ],
            [
                [
                    [1, 0, 0, 0],
                    [0, -1 / 3, 2 / 3, -2 / 3],
                    [0, 2 / 3, 2 / 3, 1 / 3],
                    [0, -2 / 3, 1 / 3, 2 / 3],
                ]
            ],
            [
                [
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, -3 / 5, -4 / 5],
                    [0, 0, -4 / 5, 3 / 5],
                ]
            ],
            [
                [
                    [2, -2, 18],
                    [2, 1, 0],
                    [1, 2, 0],
                ],
            ],
            [
                [
                    [-1.8, 12],
                    [2.4, -6],
                ],
            ],
            [
                [
                    [1,  0, 0,  0,  0],
                    [0,  0, 1,  0,  0],
                    [1, -7, 0,  4,  2],
                    [0,  4, 2, -7,  1],
                    [0,  2, 0,  1, -7],
                ],
            ],
            [
                [
                    [0,  1, 0,  0],
                    [4.9497,  0, -2.8284,  -1.4142],
                    [4, 2, -7, 1],
                    [2, 0, 1, -7],
                ],
            ],
            [
                [
                    [-1.6318, 1.6208, 0.4767],
                    [0.6813, -3.4045, 2.5281],
                    [-0.6594, 2.7978, -6.236],
                ],
            ],
            [
                [
                    [1.8953, -5.8889],
                    [-2.3316, 1.9103],
                ],
            ],
            [
                [
                    [6, 3],
                    [8, 4],
                ]
            ],
            [
                [
                    [2, -2, 18],
                    [2, 1, 0],
                    [1, 2, 0],
                ]
            ],
            [
                [
                    [12, -51,    4],
                    [ 6,  167, -68],
                    [-4,  24,  -41],
                ]
            ],
            [
                [
                    [4, 3, 7],
                    [1, 3, 6],
                    [8, 5, 7],
                ]
            ],
            [
                [
                    [1, 2, 3, 2],
                    [4, 5, 6, 2],
                    [7, 8, 9, 2],
                    [4, 5, 5, 6],
                ]
            ],
            [
                [
                    [7, 8, 9, 2],
                    [1, 2, 3, 2],
                    [4, -3, 2, 12],
                    [4, 1, -6, 6],
                ]
            ],
            [
                [
                    [3, 7, 6, 4, 5],
                    [2, 3, 6, 5, 8],
                    [2, 3, 4, 1, 0],
                    [3, 7, 6, 7, 7],
                    [1, 3, 4, 9, 4],
                ]
            ],
            [
                [
                    [2, -2, -3],
                    [0, -6, -1],
                    [0, 0, 1],
                    [0, 0, 4],
                ]
            ],
            [
                [
                    [1,0,0],
                    [0,0,0],
                    [0,0,0],
                    [0,0,0],
                ]
            ],
            [
                [
                    [3, 7, 6, 4, 5, 8],
                    [2, 3, 6, 5, 8, 9],
                    [2, 3, 4, 1, 0, 9],
                    [3, 7, 6, 7, 7, 3],
                    [1, 3, 4, 9, 4, 8],
                ]
            ],
        ];
    }

    /**
     * @test   Householder large value, potential for cancellation
     * @throws \Exception
     */
    public function testHouseholderDirectCancellationBug()
    {
        // Given a column vector that triggers cancellation: [large_positive, small, small]
        $x = MatrixFactory::create([
            [1e16],
            [1],
            [1]
        ]);

        // When
        $H = Householder::transform($x);

        // Then the transformation should zero out all elements below the first
        $Hx = $H->multiply($x);

        // First element should equal -||x|| (negative due to sign choice)
        // Other elements should be effectively zero
        $norm = $x->frobeniusNorm();
        $this->assertEqualsWithDelta(-$norm, $Hx->get(0, 0), 1e-14 * $norm);
        $this->assertEqualsWithDelta(0, $Hx->get(1, 0), 1e-14 * $norm);
        $this->assertEqualsWithDelta(0, $Hx->get(2, 0), 1e-14 * $norm);

        // H should be orthogonal: H^T * H = I
        $I = MatrixFactory::identity(3);
        $HH = $H->transpose()->multiply($H);
        $this->assertEqualsWithDelta($I->getMatrix(), $HH->getMatrix(), 1e-14);
    }
}
