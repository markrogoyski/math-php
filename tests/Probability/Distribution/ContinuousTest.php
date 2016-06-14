<?php
namespace Math\Probability\Distribution;

class ContinuousTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForUniform
     */
    public function testUniform($a, $b, $x₁, $x₂, $probability)
    {
        $this->assertEquals($probability, Continuous::uniform($a, $b, $x₁, $x₂), '', 0.001);
    }

    public function dataProviderForUniform()
    {
        return [
            [ 1, 4, 2, 3, 0.3333 ],
            [ 0.6, 12.2, 2.1, 3.4, 0.11206897 ],
            [ 1.6, 14, 4, 9, 0.40322581 ],
        ];
    }

    public function testUniformExceptionXOutOfBounds()
    {
        $this->setExpectedException('\Exception');
        Continuous::uniform(1, 2, 3, 4);
    }

    /**
     * @dataProvider dataProviderForExponentialPDF
     */
    public function testExponential($λ, $x, $probability)
    {
        $this->assertEquals($probability, Continuous::exponentialPDF($λ, $x), '', 0.001);
    }

    public function dataProviderForExponentialPDF()
    {
        return [
            [ 1, 0, 1 ],
            [ 1, 1, 0.36787944117 ],
            [ 1, 2, 0.13533528323 ],
            [ 1, 3, 0.04978706836 ],
        ];
    }

    /**
     * @dataProvider dataProviderForExponentialCDF
     */
    public function testExponentialCDF($λ, $x, $probability)
    {
        $this->assertEquals($probability, Continuous::ExponentialCDF($λ, $x), '', 0.001);
    }

    public function dataProviderForExponentialCDF()
    {
        return [
            [ 1, 0, 0 ],
            [ 1, 1, 0.6321206 ],
            [ 1, 2, 0.8646647 ],
            [ 1, 3, 0.9502129 ],
            [ 1/3, 2, 0.4865829 ],
            [ 1/3, 4, 0.7364029 ],
            [ 1/5, 4, 0.550671 ],
        ];
    }

    /**
     * @dataProvider dataProviderForExponentialCDFBetween
     */
    public function testExponentialCDFBetween($λ, $x₁, $x₂, $probability)
    {
        $this->assertEquals($probability, Continuous::ExponentialCDFBetween($λ, $x₁, $x₂), '', 0.001);
    }

    public function dataProviderForExponentialCDFBetween()
    {
        return [
            [ 1, 2, 3, 0.0855 ],
            [ 1, 3, 2, -0.0855 ],
            [ 0.5, 2, 4, 0.23254 ],
            [ 1/3, 2, 4, 0.24982 ],
            [ 0.125, 5.4, 5.6, 0.01257 ],
        ];
    }

    /**
     * @dataProvider dataProviderForNormalPDF
     */
    public function testNormalPDF($x, $μ, $σ, $pdf)
    {
        $this->assertEquals($pdf, Continuous::normalPDF($x, $μ, $σ), '', 0.001);
    }

    public function dataProviderForNormalPDF()
    {
        return [
            [ 84, 72, 15.2, 0.01921876 ],
            [ 26, 25, 2, 0.17603266338 ],
            [ 4, 0, 1, .000133830225 ],
        ];
    }

    /**
     * @dataProvider dataProviderForNormalCDF
     */
    public function testNormalCDF($x, $μ, $σ, $probability)
    {
        $this->assertEquals($probability, Continuous::normalCDF($x, $μ, $σ), '', 0.001);
    }

    public function dataProviderForNormalCDF()
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

    /**
     * @dataProvider dataProviderForNormalCDFAbove
     */
    public function testNormalCDFAbove($x, $μ, $σ, $probability)
    {
        $this->assertEquals($probability, Continuous::normalCDFAbove($x, $μ, $σ), '', 0.001);
    }

    public function dataProviderForNormalCDFAbove()
    {
        return [
            [ 1.96, 0, 1, 0.025 ],
            [ 3.5, 4, 0.3, 0.9522  ],
            [ 1.3, 1, 1.1, 0.3925 ],
        ];
    }

    /**
     * @dataProvider dataProviderForNormalCDFBetween
     */
    public function testNormalCDFBetween($x₁, $x₂, $μ, $σ, $probability)
    {
        $this->assertEquals($probability, Continuous::normalCDFBetween($x₁, $x₂, $μ, $σ), '', 0.001);
    }

    public function dataProviderForNormalCDFBetween()
    {
        return [
            [ -1.96, 1.96, 0, 1, 0.95 ],
            [ 3.5, 4.4, 4, 0.3, 0.861 ],
            [ -1.3, 1.3, 1, 1.1, 0.5892 ],
        ];
    }

    /**
     * @dataProvider dataProviderForNormalCDFOutside
     */
    public function testNormalCDFOutside($x₁, $x₂, $μ, $σ, $probability)
    {
        $this->assertEquals($probability, Continuous::normalCDFOutside($x₁, $x₂, $μ, $σ), '', 0.001);
    }

    public function dataProviderForNormalCDFOutside()
    {
        return [
            [ -1.96, 1.96, 0, 1, 0.05 ],
            [ 3.5, 4.4, 4, 0.3, 0.139 ],
            [ -1.3, 1.3, 1, 1.1, 0.4108 ],
        ];
    }

    /**
     * @dataProvider dataProviderForLogNormalPDF
     */
    public function testLogNormalPDF($x, $μ, $σ, $pdf)
    {
        $this->assertEquals($pdf, Continuous::logNormalPDF($x, $μ, $σ), '', 0.001);
    }

    public function dataProviderForLogNormalPDF()
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
     * @dataProvider dataProviderForLogNormalCDF
     */
    public function testLogNormalCDF($x, $μ, $σ, $cdf)
    {
        $this->assertEquals($cdf, Continuous::logNormalCDF($x, $μ, $σ), '', 0.001);
    }

    public function dataProviderForLogNormalCDF()
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
     * @dataProvider dataProviderForParetoPDF
     */
    public function testParetoPDF($a, $b, $x, $pdf)
    {
        $this->assertEquals($pdf, Continuous::paretoPDF($a, $b, $x), '', 0.01);
    }

    public function dataProviderForParetoPDF()
    {
        return [
            [ 1, 2, 1, 0 ],
            [ 1, 1, 1, 1 ],
            [ 8, 2, 5, 0.001048576 ],
            [ 8, 2, 4, 0.0078125 ],
            [ 4, 5, 9, 0.0423377195 ],

        ];
    }

    /**
     * @dataProvider dataProviderForParetoCDF
     */
    public function testParetoCDF($a, $b, $x, $cdf)
    {
        $this->assertEquals($cdf, Continuous::paretoCDF($a, $b, $x), '', 0.01);
    }

    public function dataProviderForParetoCDF()
    {
        return [
            [ 1, 2, 1, 0 ],
            [ 1, 1, 1, 0.001 ],
            [ 1, 1, 2, 0.500 ],
            [ 1, 1, 3.2, 0.688 ],
            [ 5.1, 5.4, 5.4, 0.001 ],
            [ 5.1, 5.4, 6.78, 0.687 ],
            [ 5.1, 5.4, 9.2, 0.934 ],
        ];
    }
}
