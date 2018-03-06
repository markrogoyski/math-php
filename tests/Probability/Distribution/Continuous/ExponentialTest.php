<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\Exponential;

class ExponentialTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pdf
     * @dataProvider dataProviderForPdf
     * @param        float $λ
     * @param        float $x
     * @param        float $pdf
     */
    public function testPdf(float $λ, float $x, float $pdf)
    {
        $exponential = new Exponential($λ);
        $this->assertEquals($pdf, $exponential->pdf($x), '', 0.000001);
    }

    /**
     * @return array [λ, x, pdf]
     * Generated with R dexp(x, λ)
     */
    public function dataProviderForPdf(): array
    {
        return [
            [0.1, -100, 0],
            [0.1, -2, 0],
            [0.1, -1, 0],
            [0.1, 0, 0.1],
            [0.1, 0.1, 0.09900498],
            [0.1, 0.5, 0.09512294],
            [0.1, 1, 0.09048374],
            [0.1, 2, 0.08187308],
            [0.1, 3, 0.07408182],
            [0.1, 10, 0.03678794],
            [0.1, 50, 0.0006737947],

            [1, -100, 0],
            [1, -2, 0],
            [1, -1, 0],
            [1, 0, 1],
            [1, 0.1, 0.9048374],
            [1, 0.5, 0.6065307],
            [1, 1, 0.3678794],
            [1, 2, 0.1353353],
            [1, 3, 0.04978707],
            [1, 4, 0.01831564],

            [2, -100, 0],
            [2, -2, 0],
            [2, -1, 0],
            [2, 0, 2],
            [2, 0.1, 1.637462],
            [2, 0.5, 0.7357589],
            [2, 1, 0.2706706],
            [2, 2, 0.03663128],
            [2, 3, 0.004957504],
        ];
    }

    /**
     * @testCase     cdf
     * @dataProvider dataProviderForCdf
     * @param number $λ
     * @param number $x
     * @param number $cdf
     */
    public function testCdf($λ, $x, $cdf)
    {
        $exponential = new Exponential($λ);
        $this->assertEquals($cdf, $exponential->cdf($x), '', 0.0000001);
    }

    /**
     * @return array [λ, x, cdf]
     * Generated with R pexp(x, λ)
     */
    public function dataProviderForCdf(): array
    {
        return [
            [0.1, -100, 0],
            [0.1, -2, 0],
            [0.1, -1, 0],
            [0.1, 0, 0],
            [0.1, 0.1, 0.009950166],
            [0.1, 0.5, 0.04877058],
            [0.1, 1, 0.09516258],
            [0.1, 2, 0.1812692],
            [0.1, 3, 0.2591818],
            [0.1, 10, 0.6321206],
            [0.1, 50, 0.9932621],

            [1, -100, 0],
            [1, -2, 0],
            [1, -1, 0],
            [1, 0, 0],
            [1, 0.1, 0.09516258],
            [1, 0.5, 0.3934693],
            [1, 1, 0.6321206],
            [1, 2, 0.8646647],
            [1, 3, 0.9502129],
            [1, 4, 0.9816844],

            [2, -100, 0],
            [2, -2, 0],
            [2, -1, 0],
            [2, 0, 0],
            [2, 0.1, 0.1812692],
            [2, 0.5, 0.6321206],
            [2, 1, 0.8646647],
            [2, 2, 0.9816844],
            [2, 3, 0.9975212],

            [1/3, 2, 0.4865829],
            [1/3, 4, 0.7364029],
            [1/5, 4, 0.550671],
        ];
    }

    /**
     * @testCase     mean
     * @dataProvider dataProviderForMean
     * @param        number $λ
     * @param        number $μ
     */
    public function testMean($λ, $μ)
    {
        $exponential = new Exponential($λ);
        $this->assertEquals($μ, $exponential->mean(), '', 0.0001);
    }

    /**
     * @return array [λ, μ]
     */
    public function dataProviderForMean(): array
    {
        return [
            [1, 1],
            [2, 0.5],
            [3, 0.33333],
            [4, 0.25],
        ];
    }
}
