<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\LogLogistic;

class LogLogisticTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $α, $β, $pdf)
    {
        $logLogistic = new LogLogistic($α, $β);
        $this->assertEquals($pdf, $logLogistic->pdf($x), '', 0.001);
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
        $logLogistic = new LogLogistic($α, $β);
        $p = $logLogistic->cdf($x);
        $this->assertEquals($cdf, $p, '', 0.001);
        $this->assertEquals($x, $logLogistic->inverse($p), '', 0.001);
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
        $logLogistic = new LogLogistic($α, $β);
        $this->assertEquals($μ, $logLogistic->mean(), '', 0.00001);
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
        $logLogistic = new LogLogistic(1, 1);
        $this->assertNan($logLogistic->mean());
    }
}
