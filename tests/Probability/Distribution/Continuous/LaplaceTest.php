<?php
namespace Math\Probability\Distribution\Continuous;

class LaplaceTest extends \PHPUnit_Framework_TestCase
{
     /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($μ, $b, $x, $pdf)
    {
        $this->assertEquals($pdf, Laplace::PDF($μ, $b, $x), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [ 0, 1, 1, 0.1839397206 ],
            [ 0, 1, 1.1, 0.1664355418 ],
            [ 0, 1, 1.2, 0.150597106 ],
            [ 0, 1, 5, 0.0033689735 ],
            [ 2, 1.4, 1, 0.174836307 ],
            [ 2, 1.4, 1.1, 0.1877814373 ],
            [ 2, 1.4, 2.9, 0.1877814373 ],
        ];
    }

    public function testPDFExceptionBLessThanZero()
    {
        $this->setExpectedException('\Exception');
        Laplace::PDF(1, -3, 2);
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($μ, $b, $x, $cdf)
    {
        $this->assertEquals($cdf, Laplace::CDF($μ, $b, $x), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [ 0, 1, 1, 0.8160602794 ],
            [ 0, 1, 1.1, 0.8335644582 ],
            [ 0, 1, 1.2, 0.849402894 ],
            [ 0, 1, 5, 0.9966310265 ],
            [ 2, 1.4, 1, 0.2447708298 ],
            [ 2, 1.4, 1.1, 0.2628940122 ],
            [ 2, 1.4, 2.9, 0.7371059878 ],
        ];
    }

    public function testCDFExceptionBLessThanZero()
    {
        $this->setExpectedException('\Exception');
        Laplace::CDF(1, -3, 2);
    }

    public function testMean()
    {
        $μ = 5;
        $b = 1;
        $this->assertEquals($μ, Laplace::mean($μ, $b));
    }
}
