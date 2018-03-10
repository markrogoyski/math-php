<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\LogLogistic;

class LogLogisticTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pdf
     * @dataProvider dataProviderForPdf
     * @param        float $x
     * @param        float $α
     * @param        float $β
     * @param        float $pdf
     */
    public function testPdf(float $x, float $α, float $β, float $pdf)
    {
        $logLogistic = new LogLogistic($α, $β);
        $this->assertEquals($pdf, $logLogistic->pdf($x), '', 0.000001);
    }

    /**
     * @return array [x, α, β, pdf]
     * Generated with R dllogis(x, scale = α, shape = β) [From eha package]
     */
    public function dataProviderForPdf(): array
    {
        return [
            [0, 1, 1, 1],
            [1, 1, 1, 0.25],
            [2, 1, 1, 0.1111111],
            [3, 1, 1, 0.0625],
            [4, 1, 1, 0.04],
            [5, 1, 1, 0.02777778],
            [10, 1, 1, 0.008264463],

            [0, 1, 2, 0],
            [1, 1, 2, 0.5],
            [2, 1, 2, 0.16],
            [3, 1, 2, 0.06],
            [4, 1, 2, 0.02768166],
            [5, 1, 2, 0.0147929],
            [10, 1, 2, 0.001960592],

            [0, 2, 2, 0],
            [1, 2, 2, 0.32],
            [2, 2, 2, 0.25],
            [3, 2, 2, 0.1420118],
            [4, 2, 2, 0.08],
            [5, 2, 2, 0.04756243],
            [10, 2, 2, 0.00739645],

            [4, 2, 3, 0.07407407],
        ];
    }

    /**
     * @testCase     cdf
     * @dataProvider dataProviderForCdf
     * @param        float $x
     * @param        float $α
     * @param        float $β
     * @param        float $cdf
     */
    public function testCdf(float $x, float $α, float $β, float $cdf)
    {
        $logLogistic = new LogLogistic($α, $β);
        $p = $logLogistic->cdf($x);
        $this->assertEquals($cdf, $p, '', 0.000001);
    }

    /**
     * @testCase     inverse of cdf is x
     * @dataProvider dataProviderForCdf
     * @param        float $x
     * @param        float $α
     * @param        float $β
     */
    public function testInverse(float $x, float $α, float $β)
    {
        $logLogistic = new LogLogistic($α, $β);
        $cdf = $logLogistic->cdf($x);
        $this->assertEquals($x, $logLogistic->inverse($cdf), '', 0.000001);
    }

    /**
     * @return array [x, α, β, cdf]
     * Generated with R pllogis(x, scale = α, shape = β) [From eha package]
     */
    public function dataProviderForCdf(): array
    {
        return [
            [0, 1, 1, 0],
            [1, 1, 1, 0.5],
            [2, 1, 1, 0.6666667],
            [3, 1, 1, 0.75],
            [4, 1, 1, 0.8],
            [5, 1, 1, 0.8333333],
            [10, 1, 1, 0.9090909],

            [0, 1, 2, 0],
            [1, 1, 2, 0.5],
            [2, 1, 2, 0.8],
            [3, 1, 2, 0.9],
            [4, 1, 2, 0.9411765],
            [5, 1, 2, 0.9615385],
            [10, 1, 2, 0.990099],

            [0, 2, 2, 0],
            [1, 2, 2, 0.2],
            [2, 2, 2, 0.5],
            [3, 2, 2, 0.6923077],
            [4, 2, 2, 0.8],
            [5, 2, 2, 0.862069],
            [10, 2, 2, 0.9615385],

            [4, 2, 3, 0.8888889],
        ];
    }

    /**
     * @testCase     mean
     * @dataProvider dataProviderForMean
     * @param        float $α
     * @param        float $β
     * @param        float $μ
     */
    public function testMean(float $α, float $β, float $μ)
    {
        $logLogistic = new LogLogistic($α, $β);
        $this->assertEquals($μ, $logLogistic->mean(), '', 0.00001);
    }

    /**
     * @return array [α, β, μ]
     */
    public function dataProviderForMean(): array
    {
        return [
            [1, 2, 1.570795],
            [2, 2, 3.14159],
            [3, 3, 3.62759751692],
            [5, 4, 5.55360266602],
        ];
    }

    /**
     * @testCase     mean is not a number when shape is not greater than 1
     * @dataProvider dataProviderForMeanNan
     * @param        float $α
     * @param        float $β
     */
    public function testMeanNan(float $α, float $β)
    {
        $logLogistic = new LogLogistic($α, $β);
        $this->assertNan($logLogistic->mean());
    }

    /**
     * @return array [α, β]
     */
    public function dataProviderForMeanNan(): array
    {
        return [
            [1, 1],
            [2, 1],
            [3, 1],
            [5, 1],
        ];
    }
}
