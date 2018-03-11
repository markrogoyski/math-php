<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\Weibull;

class WeibullTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pdf
     * @dataProvider dataProviderForPdf
     * @param        float $x
     * @param        float $k
     * @param        float $λ
     * @param        float $pdf
     */
    public function testPdf(float $x, float $k, float $λ, float $pdf)
    {
        $weibull = new Weibull($k, $λ);
        $this->assertEquals($pdf, $weibull->pdf($x), '', 0.0000001);
    }

    /**
     * @return array [x, k, λ. pdf]
     * Generated with R dweibull(x, shape, scale)
     */
    public function dataProviderForPdf(): array
    {
        return [
            [-1, 1, 1, 0],
            [0, 1, 1, 1],
            [1, 1, 1, 0.3678794],
            [2, 1, 1, 0.1353353],
            [3, 1, 1, 0.04978707],
            [4, 1, 1, 0.01831564],
            [5, 1, 1, 0.006737947],
            [10, 1, 1, 4.539993e-05],

            [-1, 1, 2, 0],
            [0, 1, 2, 0.5],
            [1, 1, 2, 0.3032653],
            [2, 1, 2, 0.1839397],
            [3, 1, 2, 0.1115651],
            [4, 1, 2, 0.06766764],
            [5, 1, 2, 0.0410425],
            [10, 1, 2, 0.003368973],

            [-1, 2, 1, 0],
            [0, 2, 1, 0],
            [1, 2, 1, 0.7357589],
            [2, 2, 1, 0.07326256],
            [3, 2, 1, 0.0007404588],
            [4, 2, 1, 9.002814e-07],
            [5, 2, 1, 1.388794e-10],
            [10, 2, 1, 7.440152e-43],

            [3, 4, 5, 0.1517956],
            [3, 5, 5, 0.1199042],
            [33, 34, 45, 2.711453e-05],
        ];
    }

    /**
     * @testCase     cdf
     * @dataProvider dataProviderForCdf
     * @param        float $x
     * @param        float $k
     * @param        float $λ
     * @param        float $cdf
     */
    public function testCdf(float $x, float $k, float $λ, float $cdf)
    {
        $weibull = new Weibull($k, $λ);
        $this->assertEquals($cdf, $weibull->cdf($x), '', 0.0000001);
    }

    /**
     * @return array [x, k, λ. cdf]
     * Generated with R pweibull(x, shape, scale)
     */
    public function dataProviderForCdf(): array
    {
        return [
            [-1, 1, 1, 0],
            [0, 1, 1, 0],
            [1, 1, 1, 0.6321206],
            [2, 1, 1, 0.8646647],
            [3, 1, 1, 0.9502129],
            [4, 1, 1, 0.9816844],
            [5, 1, 1, 0.9932621],
            [10, 1, 1, 0.9999546],

            [-1, 1, 2, 0],
            [0, 1, 2, 0],
            [1, 1, 2, 0.3934693],
            [2, 1, 2, 0.6321206],
            [3, 1, 2, 0.7768698],
            [4, 1, 2, 0.8646647],
            [5, 1, 2, 0.917915],
            [10, 1, 2, 0.9932621],

            [-1, 2, 1, 0],
            [0, 2, 1, 0],
            [1, 2, 1, 0.6321206],
            [2, 2, 1, 0.9816844],
            [3, 2, 1, 0.9998766],
            [4, 2, 1, 0.9999999],
            [5, 2, 1, 1],

            [3, 4, 5, 0.1215533],
            [3, 5, 5, 0.07481356],
            [33, 34, 45, 2.631739e-05],
        ];
    }

    /**
     * @testCase     inverse of cdf is x
     * @dataProvider dataProviderForInverse
     * @param        float $x
     * @param        float $k
     * @param        float $λ
     */
    public function testInverse(float $x, float $k, float $λ)
    {
        $weibull = new Weibull($k, $λ);
        $cdf = $weibull->cdf($x);
        $this->assertEquals($x, $weibull->inverse($cdf), '', 0.000001);
    }

    /**
     * @return array [x, k, λ]
     */
    public function dataProviderForInverse(): array
    {
        return [
            [1, 1, 1],
            [2, 1, 1],
            [3, 1, 1],
            [4, 1, 1],
            [5, 1, 1],
            [10, 1, 1],

            [1, 1, 2],
            [2, 1, 2],
            [3, 1, 2],
            [4, 1, 2],
            [5, 1, 2],
            [10, 1, 2],

            [1, 2, 1],
            [2, 2, 1],
            [3, 2, 1],
            [4, 2, 1],
            [5, 2, 1],

            [3, 4, 5],
            [3, 5, 5],
            [33, 34, 45],
        ];
    }

    /**
     * @testCase     mean
     * @dataProvider dataProviderForMean
     * @param        float $k
     * @param        float $λ
     * @param        float $μ
     */
    public function testMean(float $k, float $λ, float $μ)
    {
        $weibull = new Weibull($k, $λ);
        $this->assertEquals($μ, $weibull->mean(), '', 0.0001);
    }

    /**
     * @return array [k, λ, μ]
     */
    public function dataProviderForMean(): array
    {
        return [
            [1, 1, 1],
            [1, 2, 2],
            [2, 1, 0.88622692545275801365],
            [2, 2, 1.77245386],
        ];
    }
}
