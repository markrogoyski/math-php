<?php
namespace Math\Probability\Distribution\Continuous;

class ExponentialTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($λ, $x, $probability)
    {
        $this->assertEquals($probability, Exponential::PDF($λ, $x), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [ 1, 0, 1 ],
            [ 1, 1, 0.36787944117 ],
            [ 1, 2, 0.13533528323 ],
            [ 1, 3, 0.04978706836 ],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($λ, $x, $probability)
    {
        $this->assertEquals($probability, Exponential::CDF($λ, $x), '', 0.001);
    }

    public function dataProviderForCDF()
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

}
