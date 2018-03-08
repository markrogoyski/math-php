<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\Gamma;

class GammaTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pdf
     * @dataProvider dataProviderForPdf
     * @param        float $x   x ∈ (0,1)
     * @param        float $k   shape parameter α > 0
     * @param        float $θ   scale parameter θ > 0
     * @param        float $pdf
     */
    public function testPdf(float $x, float $k, float $θ, float $pdf)
    {
        $gamma = new Gamma($k, $θ);
        $this->assertEquals($pdf, $gamma->pdf($x), '', 0.00000001);
    }

    /**
     * Data provider for PDF
     * Test data created with calculator http://keisan.casio.com/exec/system/1180573217
     * Additional data generated with R dgamma(x, shape = k, scale = θ)
     * @return array [x, k, θ, pdf]
     */
    public function dataProviderForPdf(): array
    {
        return [
            [1, 1, 1, 0.3678794411714423215955],
            [1, 2, 1, 0.3678794411714423215955],
            [1, 1, 2, 0.3032653298563167118019],
            [2, 2, 2, 0.1839397205857211607978],
            [2, 4, 1, 0.180447044315483589192],
            [4, 2, 5, 0.07189263425875545462882],
            [18, 2, 5, 0.01967308016205064377713],
            [75, 2, 5, 9.177069615054773651144E-7],
            [0.1, 0.1, 0.1, 0.386691694403023771966],
            [15, 0.1, 0.1, 8.2986014463775253874E-68],
            [4, 0.5, 6, 0.05912753695472959648351],

            [1, 4, 5, 0.0002183282],
            [2, 4, 5, 0.001430016],
            [3, 4, 5, 0.003951444],
            [5, 4, 5, 0.01226265],
            [15, 4, 5, 0.04480836],
            [115, 4, 5, 4.161876e-08],

        ];
    }

    /**
     * @testCase     cdf
     * @dataProvider dataProviderForCdf
     * @param        float $x   x ∈ (0,1)
     * @param        float $k   shape parameter α > 0
     * @param        float $θ   scale parameter θ > 0
     * @param        float $cdf
     */
    public function testCdf(float $x, float $k, float $θ, float $cdf)
    {
        $gamma = new Gamma($k, $θ);
        $this->assertEquals($cdf, $gamma->cdf($x), '', 0.000001);
    }

    /**
     * Data provider for CDF
     * Test data created with calculator http://keisan.casio.com/exec/system/1180573217
     * Additional data generated with R pgamma(x, shape = k, scale = θ)
     * @return array [x, k, θ, cdf]
     */
    public function dataProviderForCdf(): array
    {
        return [
            [1, 1, 1, 0.6321205588285576784045],
            [1, 2, 1, 0.264241117657115356809],
            [1, 1, 2, 0.3934693402873665763962],
            [2, 2, 2, 0.264241117657115356809],
            [2, 4, 1, 0.142876539501452951338],
            [4, 2, 5, 0.1912078645890011354258],
            [18, 2, 5, 0.8743108767424542203128],
            [75, 2, 5, 0.9999951055628719707874],
            [0.1, 0.1, 0.1, 0.975872656273672222617],
            [15, 0.1, 0.1, 1],
            [4, 0.5, 6, 0.7517869210100764165283],

            [1, 4, 5, 5.684024e-05],
            [2, 4, 5, 0.0007762514],
            [3, 4, 5, 0.003358069],
            [5, 4, 5, 0.01898816],
            [15, 4, 5, 0.3527681],
            [115, 4, 5, 0.9999998],
        ];
    }

    /**
     * @testCase     mean returns the expected average
     * @dataProvider dataProviderForMean
     * @param        float $k
     * @param        float $θ
     * @param        float $μ
     */
    public function testMean(float $k, float $θ, float $μ)
    {
        $gamma = new Gamma($k, $θ);
        $this->assertEquals($μ, $gamma->mean(), '', 0.0001);
    }

    /**
     * Data provider for mean
     * @return array [k, θ, μ]
     */
    public function dataProviderForMean(): array
    {
        return [
            [1, 1, 1.0],
            [1, 2, 2.0],
            [2, 1, 2.0],
            [9, 0.5, 4.5],
        ];
    }
}
