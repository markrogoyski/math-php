<?php
namespace Math\Probability\Distribution\Continuous;

class ParetoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($a, $b, $x, $pdf)
    {
        $this->assertEquals($pdf, Pareto::PDF($a, $b, $x), '', 0.01);
    }

    public function dataProviderForPDF()
    {
        return [
            [ 1, 2, 1, 0 ],
            [ 1, 1, 1, 1 ],
            [ 8, 2, 5, 0.001048576 ],
            [ 8, 2, 4, 0.0078125 ],
            [ 4, 5, 9, 0.0423377195 ],

        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($a, $b, $x, $cdf)
    {
        $this->assertEquals($cdf, Pareto::CDF($a, $b, $x), '', 0.01);
    }

    public function dataProviderForCDF()
    {
        return [
            [ 1, 2, 1, 0 ],
            [ 1, 1, 1, 0.001 ],
            [ 1, 1, 2, 0.500 ],
            [ 1, 1, 3.2, 0.688 ],
            [ 5.1, 5.4, 5.4, 0.001 ],
            [ 5.1, 5.4, 6.78, 0.687 ],
            [ 5.1, 5.4, 9.2, 0.934 ],
        ];
    }

    /**
     * @dataProvider dataProviderForMean
     */
    public function testMean($a, $b, $μ)
    {
        $this->assertEquals($μ, Pareto::mean($a, $b), '', 0.0001);
    }

    public function dataProviderForMean()
    {
        return [
            [1, 2, \INF],
            [0, 2, \INF],
            [-1, 2, \INF],
            [2, 1, 2],
            [3, 1, 1.5],
            [3, 2, 3],
        ];
    }
}
