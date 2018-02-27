<?php
namespace MathPHP\Tests\Probability\Distribution\Multivariate;

use MathPHP\Probability\Distribution\Multivariate\Normal;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\Exception;

class NormalTest extends \PHPUnit\Framework\TestCase
{
    use \MathPHP\Tests\LinearAlgebra\MatrixDataProvider;

    /**
     * @testCase     pdf returns the expected density
     * @dataProvider dataProviderForPdf
     * @param        array $x
     * @param        array $μ
     * @param        array $∑
     * @param        float $pdf
     */
    public function testPdf(array $x, array $μ, array $∑, float $pdf)
    {
        $∑ = new Matrix($∑);
        $normal = new Normal($μ, $∑);
        $this->assertEquals($pdf, $normal->pdf($x), '', 0.00000000000001);
    }

    /**
     * Test data created with scipy.stats.multivariate_normal.pdf
     * @return array
     */
    public function dataProviderForPdf(): array
    {
        return [
            [
                [0, 0],
                [0, 0],
                [
                    [1, 0],
                    [0, 1],
                ],
                0.15915494309189535,
            ],
            [
                [1, 1],
                [0, 0],
                [
                    [1, 0],
                    [0, 1],
                ],
                0.058549831524319168,
            ],
            [
                [1, 1],
                [1, 1],
                [
                    [1, 0],
                    [0, 1],
                ],
                0.15915494309189535,
            ],
            [
                [0.7, 1.4],
                [1, 1.1],
                [
                    [1, 0],
                    [0, 1],
                ],
                0.14545666578175082,
            ],
            [
                [0.7, 1.4],
                [1, 1.1],
                [
                    [1, 0],
                    [0, 2],
                ],
                0.10519382725436884,
            ],
            [
                [4.5, 7.6],
                [3.2, 6.7],
                [
                    [1, 0],
                    [0, 1],
                ],
                0.045598654639838636,
            ],
            [
                [20.3, 12.6],
                [20, 15],
                [
                    [25, 10],
                    [10, 16],
                ],
                0.0070398507893074313,
            ],
            [
                [7, 12],
                [4.8, 8.4],
                [
                    [1.7, 2.6],
                    [2.6, 6.3],
                ],
                0.019059723382617431,
            ],
            [
                [4, 9],
                [4.8, 8.4],
                [
                    [1.7, 2.6],
                    [2.6, 6.3],
                ],
                0.032434509200433989,
            ],
            [
                [4, 5],
                [4.8, 8.4],
                [
                    [1.7, 2.6],
                    [2.6, 6.3],
                ],
                0.023937002571148978,
            ],
            [
                [5, 8],
                [4.8, 8.4],
                [
                    [1.7, 2.6],
                    [2.6, 6.3],
                ],
                0.07109614254107853,
            ],
            [
                [4, 8],
                [4.8, 8.4],
                [
                    [1.7, 2.6],
                    [2.6, 6.3],
                ],
                0.057331098511004673,
            ],
            [
                [30, 50],
                [26.95, 24.8],
                [
                    [88.57632, 67.51579],
                    [67.51579, 64.27368],
                ],
                6.0531136999164446e-12,
            ],
            [
                [4.5, 7.6, 9.3],
                [3.2, 6.7, 8.0],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
                0.0078141772449033566,
            ],
            [
                [4.5, 7.6, 9.3],
                [3.2, 6.7, 8.0],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 2],
                ],
                0.0084305843631899829,
            ],
            [
                [2, 11, 3],
                [1, 12, 2],
                [
                    [1, 2, 0],
                    [2, 5, 0.5],
                    [0, 0.5, 3],
                ],
                8.2808512671378126e-05,
            ],
        ];
    }

    /**
     * @testCase     pdf throws an Exception\BadDataException if the covariance matrix is not positive definite
     * @dataProvider dataProviderForNotPositiveDefiniteMatrix
     * @param        array $M Non-positive definite matrix
     */
    public function testPdfCovarianceMatrixNotPositiveDefiniteException(array $M)
    {
        $μ = [0, 0];
        $∑ = new Matrix($M);

        $this->expectException(Exception\BadDataException::class);
        $normal = new Normal($μ, $∑);
    }

    /**
     * @testCase pdf throws an Exception\BadDataException if x and μ don't have the same number of elements
     */
    public function testPdfXAndMuDifferentNumberOfElementsException()
    {
        $μ = [0, 0];
        $∑ = new Matrix([
            [1, 0],
            [0, 1],
        ]);
        $x = [0, 0, 0];
        $normal = new Normal($μ, $∑);
        $this->expectException(Exception\BadDataException::class);
        $pdf = $normal->pdf($x);
    }

    /**
     * @testCase pdf throws an Exception\BadDataException if the covariance matrix has a different number of elements.
     */
    public function testPdfCovarianceMatrixDifferentNumberOfElementsException()
    {
        $μ = [0, 0];
        $∑ = new Matrix([
            [1, 0, 0],
            [0, 1, 0],
        ]);

        $this->expectException(Exception\BadDataException::class);
        $normal = new Normal($μ, $∑);
    }
}
