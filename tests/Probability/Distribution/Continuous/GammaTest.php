<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\Gamma;

class GammaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPdf
     * @param number $x   x ∈ (0,1)
     * @param number $k   shape parameter α > 0
     * @param number $θ   scale parameter θ > 0
     * @param number $pdf
     */
    public function testPdf($x, $k, $θ, $pdf)
    {
        $this->assertEquals($pdf, Gamma::pdf($x, $k, $θ), '', 0.00000001);
    }

    /**
     * Data provider for PDF
     * Test data created with calculator http://keisan.casio.com/exec/system/1180573217
     * @return array
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
        ];
    }

    /**
     * @dataProvider dataProviderForCdf
     * @param number $x   x ∈ (0,1)
     * @param number $k   shape parameter α > 0
     * @param number $θ   scale parameter θ > 0
     * @param number $pdf
     */
    public function testCdf($x, $k, $θ, $cdf)
    {
        $this->assertEquals($cdf, Gamma::cdf($x, $k, $θ), '', 0.000001);
    }

    /**
     * Data provider for CDF
     * Test data created with calculator http://keisan.casio.com/exec/system/1180573217
     * @return array
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
        ];
    }
}
