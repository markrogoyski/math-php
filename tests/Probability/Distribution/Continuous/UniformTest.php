<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\Uniform;

class UniformTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($a, $b, $x, $pdf)
    {
        $uniform = new Uniform($a, $b);
        $this->assertEquals($pdf, $uniform->pdf($x), '', 0.001);
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
        $uniform = new Uniform($a, $b);
        $this->assertEquals($cdf, $uniform->cdf($x), '', 0.001);
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
        $uniform = new Uniform($a, $b);
        $this->assertEquals($μ, $uniform->mean(), '', 0.00001);
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
