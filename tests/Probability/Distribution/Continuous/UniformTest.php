<?php
namespace Math\Probability\Distribution\Continuous;

class UniformTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($a, $b, $x, $pdf)
    {
        $this->assertEquals($pdf, Uniform::PDF($a, $b, $x), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [1, 4, 2, 0.3333333333333333333333],
            [1, 4, 3.4, 0.3333333333333333333333],
            [1, 5.4, 3, 0.2272727272727272727273],
            [1, 5.4, 0.3, 0],
            [1, 5.4, 6, 0],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($a, $b, $x, $cdf)
    {
        $this->assertEquals($cdf, Uniform::CDF($a, $b, $x), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [1, 4, 2, 0.3333333333333333333333],
            [1, 4, 3.4, 0.8],
            [1, 5.4, 3, 0.4545454545454545454545],
            [1, 5.4, 0.3, 0],
            [1, 5.4, 6, 1],
        ];
    }

    /**
     * @dataProvider dataProviderForMean
     */
    public function testMean($a, $b, $μ)
    {
        $this->assertEquals($μ, Uniform::mean($a, $b), '', 0.00001);
    }

    public function dataProviderForMean()
    {
        return [
            [0, 0, 0],
            [0, 1, 0.5],
            [1, 0, 0.5],
            [1, 1, 1],
            [2, 1, 1.5],
            [2, 2, 2],
            [5, 4, 4.5],
        ];
    }
}
