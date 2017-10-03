<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\Pareto;

class ParetoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $a, $b, $pdf)
    {
        $pareto = new Pareto($a, $b);
        $this->assertEquals($pdf, $pareto->pdf($x), '', 0.01);
    }

    public function dataProviderForPDF()
    {
        return [
            [ 1, 1, 2, 0 ],
            [ 1, 1, 1, 1 ],
            [ 5, 8, 2, 0.001048576 ],
            [ 4, 8, 2, 0.0078125 ],
            [ 9, 4, 5, 0.0423377195 ],

        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($a, $b, $x, $cdf)
    {
        $pareto = new Pareto($a, $b);
        $this->assertEquals($cdf, $pareto->cdf($x), '', 0.01);
    }

    public function dataProviderForCDF()
    {
        return [
            [ 1, 1, 2, 0 ],
            [ 1, 1, 1, 0.001 ],
            [ 2, 1, 1, 0.500 ],
            [ 3.2, 1, 1, 0.688 ],
            [ 5.4, 5.1, 5.4, 0.001 ],
            [ 6.78, 5.1, 5.4, 0.687 ],
            [ 9.2, 5.1, 5.4, 0.934 ],
        ];
    }

    /**
     * @dataProvider dataProviderForMean
     */
    public function testMean($a, $b, $μ)
    {
        $pareto = new Pareto($a, $b);
        $this->assertEquals($μ, $pareto->mean(), '', 0.0001);
    }

    public function dataProviderForMean()
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
