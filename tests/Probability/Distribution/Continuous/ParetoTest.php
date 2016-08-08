<?php
namespace Math\Probability\Distribution\Continuous;

class ParetoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $a, $b, $pdf)
    {
        $this->assertEquals($pdf, Pareto::PDF($x, $a, $b), '', 0.01);
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
        $this->assertEquals($cdf, Pareto::CDF($a, $b, $x), '', 0.01);
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
        $this->assertEquals($μ, Pareto::mean($a, $b), '', 0.0001);
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
