<?php
namespace Math\Probability\Distribution\Continuous;

class LogisticTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($μ, $s, $x, $pdf)
    {
        $this->assertEquals($pdf, Logistic::PDF($μ, $s, $x), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [0, 0.7, -5, 0.001127488648],
            [0, 0.7, -4.2, 0.003523584702],
            [0, 0.7, -3.5, 0.009497223815],
            [0, 0.7, -3.0, 0.01913226324],
            [0, 0.7, -2.0, 0.07337619322],
            [0, 0.7, -0.1, 0.3553268797],
            [0, 0.7, 0, 0.3571428571],
            [0, 0.7, 0.1, 0.3553268797],
            [0, 0.7, 3.5, 0.009497223815],
            [0, 0.7, 4.2, 0.003523584702],
            [0, 0.7, 5, 0.001127488648],

            [2, 1.5, -5, 0.006152781498],
            [2, 1.5, -3.7, 0.01426832061],
            [2, 1.5, 0, 0.1100606731],
            [2, 1.5, 3.7, 0.1228210582],
            [2, 1.5, 5, 0.0699957236],
        ];
    }

    public function testPDFScaleParameterException()
    {
        $this->setExpectedException('\Exception');
        $s = 0;
        Logistic::PDF(2, $s, 2);
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($μ, $s, $x, $cdf)
    {
        $this->assertEquals($cdf, Logistic::CDF($μ, $s, $x), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [0, 0.7, -4.8, 0.001050809752],
            [0, 0.7, -3.5, 0.006692850924],
            [0, 0.7, -3.0, 0.01357691694],
            [0, 0.7, -2.0, 0.05431326613],
            [0, 0.7, -0.1, 0.4643463292],
            [0, 0.7, 0, 0.5],
            [0, 0.7, 0.1, 0.5356536708],
            [0, 0.7, 3.5, 0.9933071491],
            [0, 0.7, 4.2, 0.9975273768],
            [0, 0.7, 5, 0.9992101341],

            [2, 1.5, -5, 0.009315959345],
            [2, 1.5, -3.7, 0.02188127094],
            [2, 1.5, 0, 0.2086085273],
            [2, 1.5, 3.7, 0.7564535292],
            [2, 1.5, 5, 0.880797078],
        ];
    }

    public function testCDFScaleParameterException()
    {
        $this->setExpectedException('\Exception');
        $s = 0;
        Logistic::CDF(2, $s, 2);
    }
}
