<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\Pareto;

class ParetoTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pdf
     * @dataProvider dataProviderForPdf
     * @param        float $x
     * @param        float $a
     * @param        float $b
     * @param        float $pdf
     */
    public function testPdf(float $x, float $a, float $b, float $pdf)
    {
        $pareto = new Pareto($a, $b);
        $this->assertEquals($pdf, $pareto->pdf($x), '', 0.00001);
    }

    /**
     * @return array
     * Generated with R dpareto(x, b a) from package EnvStats
     */
    public function dataProviderForPdf(): array
    {
        return [
            [0.1, 1, 1, 0],
            [1, 1, 1, 1],
            [2, 1, 1, 0.25],
            [3, 1, 1, 0.1111111],
            [4, 1, 1, 0.0625],
            [5, 1, 1, 0.04],
            [10, 1, 1, 0.01],

            [0.1, 2, 1, 0],
            [1, 2, 1, 2],
            [2, 2, 1, 0.25],
            [3, 2, 1, 0.07407407],
            [4, 2, 1, 0.03125],
            [5, 2, 1, 0.016],
            [10, 2, 1, 0.002],

            [0.1, 2, 1, 0],
            [1, 1, 2, 0],
            [2, 1, 2, 0.5],
            [3, 1, 2, 0.2222222],
            [4, 1, 2, 0.125],
            [5, 1, 2, 0.08],
            [10, 1, 2, 0.02],

            [0.1, 2, 2, 0],
            [1, 2, 2, 0],
            [2, 2, 2, 1],
            [3, 2, 2, 0.2962963],
            [4, 2, 2, 0.125],
            [5, 2, 2, 0.064],
            [10, 2, 2, 0.008],

            [4, 8, 2, 0.0078125],
            [5, 8, 2, 0.001048576],
            [9, 4, 5, 0.04233772],
        ];
    }

    /**
     * @testCase     cdf
     * @dataProvider dataProviderForCdf
     * @param        float $x
     * @param        float $a
     * @param        float $b
     * @param        float $cdf
     */
    public function testCdf(float $x, float $a, float $b, float $cdf)
    {
        $pareto = new Pareto($a, $b);
        $this->assertEquals($cdf, $pareto->cdf($x), '', 0.00001);
    }

    /**
     * @return array
     * Generated with R ppareto(x, b a) from package EnvStats
     */
    public function dataProviderForCdf(): array
    {
        return [
            [0.1, 1, 1, 0],
            [1, 1, 1, 0],
            [2, 1, 1, 0.5],
            [3, 1, 1, 0.6666667],
            [4, 1, 1, 0.75],
            [5, 1, 1, 0.8],
            [10, 1, 1, 0.9],

            [0.1, 2, 1, 0],
            [1, 2, 1, 0],
            [2, 2, 1, 0.75],
            [3, 2, 1, 0.8888889],
            [4, 2, 1, 0.9375],
            [5, 2, 1, 0.96],
            [10, 2, 1, 0.99],

            [0.1, 2, 1, 0],
            [1, 1, 2, 0],
            [2, 1, 2, 0],
            [3, 1, 2, 0.3333333],
            [4, 1, 2, 0.5],
            [5, 1, 2, 0.6],
            [10, 1, 2, 0.8],

            [0.1, 2, 2, 0],
            [1, 2, 2, 0],
            [2, 2, 2, 0],
            [3, 2, 2, 0.5555556],
            [4, 2, 2, 0.75],
            [5, 2, 2, 0.84],
            [10, 2, 2, 0.96],

            [4, 8, 2, 0.9960938],
            [5, 8, 2, 0.9993446],
            [9, 4, 5, 0.9047401],
        ];
    }

    /**
     * @testCase     mean
     * @dataProvider dataProviderForMean
     * @param        float $a
     * @param        float $b
     * @param        float $μ
     */
    public function testMean(float $a, float $b, float $μ)
    {
        $pareto = new Pareto($a, $b);
        $this->assertEquals($μ, $pareto->mean(), '', 0.0001);
    }

    /**
     * @return array [a, b, μ]
     */
    public function dataProviderForMean(): array
    {
        return [
            [1, 2, \INF],
            [0.4, 2, \INF],
            [0.001, 2, \INF],
            [2, 1, 2],
            [3, 1, 1.5],
            [3, 2, 3],
        ];
    }
}
