<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\Logistic;

class LogisticTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pdf
     * @dataProvider dataProviderForPdf
     * @param        float $x
     * @param        float $μ
     * @param        float $s
     * @param        float $pdf
     */
    public function testPdf(float $x, float $μ, float $s, float $pdf)
    {
        $logistic = new Logistic($μ, $s);
        $this->assertEquals($pdf, $logistic->pdf($x), '', 0.000001);
    }

    /**
     * @return array [x, μ, s, pdf]
     * Generated with R dlogis(x, μ, s)
     */
    public function dataProviderForPdf(): array
    {
        return [
            [0, 0, 1, 0.25],
            [1, 0, 1, 0.1966119],
            [2, 0, 1, 0.1049936],
            [3, 0, 1, 0.04517666],
            [4, 0, 1, 0.01766271],
            [5, 0, 1, 0.006648057],
            [10, 0, 1, 4.539581e-05],

            [0, 1, 1, 0.1966119],
            [1, 1, 1, 0.25],
            [2, 1, 1, 0.1966119],
            [3, 1, 1, 0.1049936],
            [4, 1, 1, 0.04517666],
            [5, 1, 1, 0.01766271],
            [10, 1, 1, 0.0001233793],

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
            [5, 2, 1.5, 0.06999572],
        ];
    }

    /**
     * @testCase     cdf
     * @dataProvider dataProviderForCdf
     * @param        float $x
     * @param        float $μ
     * @param        float $s
     * @param        float $cdf
     */
    public function testCdf(float $x, float $μ, float $s, float $cdf)
    {
        $logistic = new Logistic($μ, $s);
        $this->assertEquals($cdf, $logistic->cdf($x, $μ, $s), '', 0.000001);
    }

    /**
     * @testCase     inverse of cdf is x
     * @dataProvider dataProviderForCdf
     * @param        float $x
     * @param        float $μ
     * @param        float $s
     */
    public function testInverse(float $x, float $μ, float $s)
    {
        $logistic = new Logistic($μ, $s);
        $cdf = $logistic->cdf($x, $μ, $s);
        $this->assertEquals($x, $logistic->inverse($cdf), '', 0.000001);
    }

    /**
     * @return array [x, μ, s, cdf]
     * Generated with R plogis(x, μ, s)
     */
    public function dataProviderForCdf(): array
    {
        return [
            [0, 0, 1, 0.5],
            [1, 0, 1, 0.7310586],
            [2, 0, 1, 0.8807971],
            [3, 0, 1, 0.9525741],
            [4, 0, 1, 0.9820138],
            [5, 0, 1, 0.9933071],
            [10, 0, 1, 0.9999546],

            [0, 1, 1, 0.2689414],
            [1, 1, 1, 0.5],
            [2, 1, 1, 0.7310586],
            [3, 1, 1, 0.8807971],
            [4, 1, 1, 0.9525741],
            [5, 1, 1, 0.9820138],
            [10, 1, 1, 0.9998766],

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

    /**
     * @testCase mean
     */
    public function testMean()
    {
        $μ = 5;
        $s = 1;
        $logistic = new Logistic($μ, $s);
        $this->assertEquals($μ, $logistic->mean());
    }
}
