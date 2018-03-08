<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\F;

class FTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForPdf
     * @param        int   $x
     * @param        int   $d₁
     * @param        int   $d₂
     * @param        float $pdf
     */
    public function testPdf(int $x, int $d₁, int $d₂, float $pdf)
    {
        $f = new F($d₁, $d₂);
        $this->assertEquals($pdf, $f->pdf($x), '', 0.00001);
    }

    /**
     * @return array [x, d₁, d₂, pdf]
     * Generated with R df(x, d₁, d₂)
     */
    public function dataProviderForPdf(): array
    {
        return [
            [1, 1, 1, 0.1591549],
            [2, 1, 1, 0.07502636],
            [3, 1, 1, 0.04594407],
            [4, 1, 1, 0.03183099],
            [5, 1, 1, 0.02372542],
            [10, 1, 1, 0.009150766],

            [1, 2, 1, 0.1924501],
            [2, 2, 1, 0.08944272],
            [3, 2, 1, 0.05399492],
            [4, 2, 1, 0.03703704],
            [5, 2, 1, 0.02741012],
            [10, 2, 1, 0.01039133],

            [1, 1, 2, 0.1924501],
            [2, 1, 2, 0.08838835],
            [3, 1, 2, 0.05163978],
            [4, 1, 2, 0.03402069],
            [5, 1, 2, 0.02414726],
            [10, 1, 2, 0.007607258],

            [1, 2, 2, 0.25],
            [2, 2, 2, 0.1111111],
            [3, 2, 2, 0.0625],
            [4, 2, 2, 0.04],
            [5, 2, 2, 0.02777778],
            [10, 2, 2, 0.008264463],

            [5, 3, 7, 0.01667196],
            [7, 6, 2, 0.016943],
            [7, 20, 14, 0.0002263343],
            [45, 2, 3, 0.0001868942],
        ];
    }

    /**
     * @testCase     cdf
     * @dataProvider dataProviderForCdf
     * @param        int   $x
     * @param        int   $d₁
     * @param        int   $d₂
     * @param        float $cdf
     */
    public function testCdf(int $x, int $d₁, int $d₂, float $cdf)
    {
        $f = new F($d₁, $d₂);
        $this->assertEquals($cdf, $f->cdf($x), '', 0.00001);
    }

    /**
     * @return array [x, d₁, d₂, cdf]
     * Generated with R pf(x, d₁, d₂)
     */
    public function dataProviderForCdf(): array
    {
        return [
            [0, 1, 1, 0],
            [0, 1, 2, 0],
            [0, 2, 1, 0],
            [0, 2, 2, 0],
            [0, 2, 3, 0],

            [1, 1, 1, 0.5],
            [2, 1, 1, 0.6081734],
            [3, 1, 1, 0.6666667],
            [4, 1, 1, 0.7048328],
            [5, 1, 1, 0.7322795],
            [10, 1, 1, 0.8050178],

            [1, 2, 1, 0.4226497],
            [2, 2, 1, 0.5527864],
            [3, 2, 1, 0.6220355],
            [4, 2, 1, 0.6666667],
            [5, 2, 1, 0.6984887],
            [10, 2, 1, 0.7817821],

            [1, 1, 2, 0.5773503],
            [2, 1, 2, 0.7071068],
            [3, 1, 2, 0.7745967],
            [4, 1, 2, 0.8164966],
            [5, 1, 2, 0.8451543],
            [10, 1, 2, 0.9128709],

            [1, 2, 2, 0.5],
            [2, 2, 2, 0.6666667],
            [3, 2, 2, 0.75],
            [4, 2, 2, 0.8],
            [5, 2, 2, 0.8333333],
            [10, 2, 2, 0.9090909],

            [5, 3, 7, 0.9633266],
            [7, 6, 2, 0.8697408],
            [7, 20, 14, 0.9997203],
            [45, 2, 3, 0.9942063],
        ];
    }

    /**
     * @testCase     mean
     * @dataProvider dataProviderForMean
     * @param        int   $d₁
     * @param        int   $d₂
     * @param        float $μ
     */
    public function testMean(int $d₁, int $d₂, float $μ)
    {
        $f = new F($d₁, $d₂);
        $this->assertEquals($μ, $f->mean(), '', 0.0001);
    }

    /**
     * @return array [d₁, d₂, $μ]
     */
    public function dataProviderForMean(): array
    {
        return [
            [1, 3, 3,],
            [1, 4, 2],
            [1, 5, 1.66666667],
            [1, 6, 1.5],
        ];
    }

    /**
     * @testCase     mean is not a number if d₂ ≤ 2
     * @dataProvider dataProviderForMeanNan
     * @param        int $d₁
     * @param        int $d₂
     */
    public function testMeanNAN(int $d₁, int $d₂)
    {
        $F = new F($d₁, $d₂);
        $this->assertNan($F->mean());
    }

    /**
     * @return array [d₁, d₂]
     */
    public function dataProviderForMeanNan(): array
    {
        return [
            [1, 1],
            [1, 2],
        ];
    }
}
