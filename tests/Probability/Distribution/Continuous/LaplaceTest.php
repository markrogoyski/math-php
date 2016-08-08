<?php
namespace Math\Probability\Distribution\Continuous;

class LaplaceTest extends \PHPUnit_Framework_TestCase
{
     /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $μ, $b, $pdf)
    {
        $this->assertEquals($pdf, Laplace::PDF($x, $μ, $b), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [ 1, 0, 1, 0.1839397206 ],
            [ 1.1, 0, 1, 0.1664355418 ],
            [ 1.2, 0, 1, 0.150597106 ],
            [ 5, 0, 1, 0.0033689735 ],
            [ 1, 2, 1.4, 0.174836307 ],
            [ 1.1, 2, 1.4, 0.1877814373 ],
            [ 2.9, 2, 1.4, 0.1877814373 ],
        ];
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
            [ 1, 0, 1, 0.8160602794 ],
            [ 1.1, 0, 1, 0.8335644582 ],
            [ 1.2, 0, 1, 0.849402894 ],
            [ 5, 0, 1, 0.9966310265 ],
            [ 1, 2, 1.4, 0.2447708298 ],
            [ 1.1, 2, 1.4, 0.2628940122 ],
            [ 2.9, 2, 1.4, 0.7371059878 ],
        ];
    }

    public function testMean()
    {
        $μ = 5;
        $b = 1;
        $this->assertEquals($μ, Laplace::mean($μ, $b));
    }
}
