<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\LogNormal;

class LogNormalTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pdf
     * @dataProvider dataProviderForPDF
     * @param        number $x
     * @param        number $μ
     * @param        number $σ
     * @param        number $pdf
     */
    public function testPDF($x, $μ, $σ, $pdf)
    {
        $log_normal = new LogNormal($μ, $σ);
        $this->assertEquals($pdf, $log_normal->pdf($x, $μ, $σ), '', 0.001);
    }

    /**
     * @return array
     */
    public function dataProviderForPDF(): array
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
     * @param        number $x
     * @param        number $μ
     * @param        number $σ
     * @param        number $cdf
     */
    public function testCDF($x, $μ, $σ, $cdf)
    {
        $log_normal = new LogNormal($μ, $σ);
        $this->assertEquals($cdf, $log_normal->cdf($x, $μ, $σ), '', 0.001);
    }

    /**
     * @return array
     */
    public function dataProviderForCDF(): array
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
     * @param        number $μ
     * @param        number $σ
     * @param        number $mean
     */
    public function testMean($μ, $σ, $mean)
    {
        $log_normal = new LogNormal($μ, $σ);
        $this->assertEquals($mean, $log_normal->mean($μ, $σ), '', 0.000001);
    }

    /**
     * @return array
     */
    public function dataProviderForMean(): array
    {
        return [
            [1, 1, 4.48168907034],
            [2, 2, 54.5981500331],
            [1.3, 1.6, 13.1971381597],
            [2.6, 3.16, 1983.86055382],
        ];
    }
}
