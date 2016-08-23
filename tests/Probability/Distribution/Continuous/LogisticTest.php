<?php
namespace Math\Probability\Distribution\Continuous;

class LogisticTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $μ, $s, $pdf)
    {
        $this->assertEquals($pdf, Logistic::PDF($x, $μ, $s), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [-5, 0, 0.7, 0.001127488648],
            [-4.2, 0, 0.7, 0.003523584702],
            [-3.5, 0, 0.7, 0.009497223815],
            [-3.0, 0, 0.7, 0.01913226324],
            [-2.0, 0, 0.7, 0.07337619322],
            [-0.1, 0, 0.7, 0.3553268797],
            [0, 0, 0.7, 0.3571428571],
            [0.1, 0, 0.7, 0.3553268797],
            [3.5, 0, 0.7, 0.009497223815],
            [4.2, 0, 0.7, 0.003523584702],
            [5, 0, 0.7, 0.001127488648],

            [-5, 2, 1.5, 0.006152781498],
            [-3.7, 2, 1.5, 0.01426832061],
            [0, 2, 1.5, 0.1100606731],
            [3.7, 2, 1.5, 0.1228210582],
            [5, 2, 1.5, 0.0699957236],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($x, $μ, $s, $cdf)
    {
        $p = Logistic::CDF($x, $μ, $s);
        $this->assertEquals($cdf, $p, '', 0.001);
        $this->assertEquals($x, Logistic::inverse($p, $μ, $s), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [-4.8, 0, 0.7, 0.001050809752],
            [-3.5, 0, 0.7, 0.006692850924],
            [-3.0, 0, 0.7, 0.01357691694],
            [-2.0, 0, 0.7, 0.05431326613],
            [-0.1, 0, 0.7, 0.4643463292],
            [0, 0, 0.7, 0.5],
            [0.1, 0, 0.7, 0.5356536708],
            [3.5, 0, 0.7, 0.9933071491],
            [4.2, 0, 0.7, 0.9975273768],
            [5, 0, 0.7, 0.9992101341],

            [-5, 2, 1.5, 0.009315959345],
            [-3.7, 2, 1.5, 0.02188127094],
            [0, 2, 1.5, 0.2086085273],
            [3.7, 2, 1.5, 0.7564535292],
            [5, 2, 1.5, 0.880797078],
        ];
    }

    public function testMean()
    {
        $μ = 5;
        $s = 1;
        $this->assertEquals($μ, Logistic::mean($μ, $s));
    }
}
