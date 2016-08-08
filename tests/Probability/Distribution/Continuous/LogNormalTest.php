<?php
namespace Math\Probability\Distribution\Continuous;

class LogNormalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $μ, $σ, $pdf)
    {
        $this->assertEquals($pdf, LogNormal::PDF($x, $μ, $σ), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [ 4.3, 6, 2, 0.003522012 ],
            [ 4.3, 6, 1, 0.000003083 ],
            [ 4.3, 1, 1, 0.083515969 ],
            [ 1, 6, 2, 0.002215924 ],
            [ 2, 6, 2, 0.002951125 ],
            [ 2, 3, 2, 0.051281299 ],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($x, $μ, $σ, $cdf)
    {
        $this->assertEquals($cdf, LogNormal::CDF($x, $μ, $σ), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [ 4.3, 6, 2, 0.0115828 ],
            [ 4.3, 6, 1, 0.000002794 ],
            [ 4.3, 1, 1, 0.676744677 ],
            [ 1, 6, 2, 0.001349898 ],
            [ 2, 6, 2, 0.003983957 ],
            [ 2, 3, 2, 0.124367703 ],
        ];
    }

    /**
     * @dataProvider dataProviderForMean
     */
    public function testMean($μ, $σ, $mean)
    {
        $this->assertEquals($mean, LogNormal::mean($μ, $σ), '', 0.000001);
    }

    public function dataProviderForMean()
    {
        return [
            [1, 1, 4.48168907034],
            [2, 2, 54.5981500331],
            [1.3, 1.6, 13.1971381597],
            [2.6, 3.16, 1983.86055382],
        ];
    }
}
