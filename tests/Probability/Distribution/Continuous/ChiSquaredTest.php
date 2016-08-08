<?php
namespace Math\Probability\Distribution\Continuous;

class ChiSquaredTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, int $k, $pdf)
    {
        $this->assertEquals($pdf, ChiSquared::PDF($x, $k), '', 0.00001);
    }

    public function dataProviderForPDF()
    {
        return [
            [2.5, 1, 0.07228896],
            [2.5, 10, 0.01457239],
            [2.5, 15, 0.00032650],
            [6, 5, 0.09730435],
            [6, 20, 0.00135025],
            [17.66, 6, 0.00285129],
            [0.09, 6, 0.00048397],
            
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($x, int $k, $cdf)
    {
        $this->assertEquals($cdf, ChiSquared::CDF($x, $k), '', 0.01);
    }

    public function dataProviderForCDF()
    {
        return [
            [7.26, 15, 0.05],
            [7.26, 12, 0.16],
            [7.26, 1, 0.993],
            [1, 1, 0.68],
            [1, 2, 0.39],
            [1, 30, 0],
            [1, 7, 0.005],
            [4.6, 1, 0.97],
            [4.6, 2, 0.9],
            [4.6, 6, 0.4],
            [4.6, 10, 0.08],
        ];
    }

    public function testMean()
    {
        $k = 5;
        $this->assertEquals($k, ChiSquared::mean($k));
    }
}
