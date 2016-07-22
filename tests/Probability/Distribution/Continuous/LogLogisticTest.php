<?php
namespace Math\Probability\Distribution\Continuous;

class LogLogisticTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($α, $β, $x, $pdf)
    {
        $this->assertEquals($pdf, LogLogistic::PDF($α, $β, $x), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [1, 1, 1, 0.25],
            [1, 1, 2, 0.1111111],
            [2, 3, 4, 0.07407407407],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($α, $β, $x, $cdf)
    {
        $this->assertEquals($cdf, LogLogistic::CDF($α, $β, $x), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [1, 1, 1, 0.5],
            [1, 1, 2, 0.667],
            [2, 3, 4, 0.889],
        ];
    }

    /**
     * @dataProvider dataProviderForParameterException
     */
    public function testPDFDataParameterException($α, $β, $x)
    {
        $this->setExpectedException('\Exception');
        LogLogistic::PDF($α, $β, $x);
    }

    /**
     * @dataProvider dataProviderForParameterException
     */
    public function testCDFDataParameterException($α, $β, $x)
    {
        $this->setExpectedException('\Exception');
        LogLogistic::CDF($α, $β, $x);
    }

    public function dataProviderForParameterException()
    {
        return [
            [0, 1, 1],
            [1, 0, 1],
            [1, 1, 0],
        ];
    }
}
