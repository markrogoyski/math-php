<?php
namespace Math\Probability\Distribution\Continuous;

class NormalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $μ, $σ, $pdf)
    {
        $this->assertEquals($pdf, Normal::PDF($x, $μ, $σ), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [ 84, 72, 15.2, 0.01921876 ],
            [ 26, 25, 2, 0.17603266338 ],
            [ 4, 0, 1, .000133830225 ],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($x, $μ, $σ, $probability)
    {
        $this->assertEquals($probability, Normal::CDF($x, $μ, $σ), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [ 84, 72, 15.2, 0.7851 ],
            [ 26, 25, 2, 0.6915 ],
            [ 6, 5, 1, 0.8413 ],
            [ 39, 25, 14, 0.8413 ],
            [ 1.96, 0, 1, 0.975 ],
            [ 3.5, 4, 0.3, 0.0478 ],
            [ 1.3, 1, 1.1, 0.6075 ],
        ];
    }
}
