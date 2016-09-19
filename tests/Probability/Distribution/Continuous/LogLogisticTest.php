<?php
namespace Math\Probability\Distribution\Continuous;

class LogLogisticTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $α, $β, $pdf)
    {
        $this->assertEquals($pdf, LogLogistic::PDF($x, $α, $β), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [1, 1, 1, 0.25],
            [2, 1, 1, 0.1111111],
            [4, 2, 3, 0.07407407407],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($x, $α, $β, $cdf)
    {
        $p = LogLogistic::CDF($x, $α, $β);
        $this->assertEquals($cdf, $p, '', 0.001);
        $this->assertEquals($x, LogLogistic::inverse($p, $α, $β), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [1, 1, 1, 0.5],
            [2, 1, 1, 0.667],
            [4, 2, 3, 0.889],
        ];
    }

    /**
     * @dataProvider dataProviderForMean
     */
    public function testMean($α, $β, $μ)
    {
        $this->assertEquals($μ, LogLogistic::mean($α, $β), '', 0.00001);
    }

    public function dataProviderForMean()
    {
        return [
            [1, 2, 1.570795],
            [2, 2, 3.14159],
            [3, 3, 3.62759751692],
            [5, 4, 5.55360266602],
        ];
    }
    
    public function testMeanNan()
    {
        $this->assertNan(LogLogistic::mean(1, 1));
    }
}
