<?php
namespace Math\Probability\Distribution\Continuous;

class ExponentialTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $λ, $probability)
    {
        $this->assertEquals($probability, Exponential::PDF($x, $λ), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [ 0, 1, 1 ],
            [ 1, 1, 0.36787944117 ],
            [ 2, 1, 0.13533528323 ],
            [ 3, 1, 0.04978706836 ],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($x, $λ, $probability)
    {
        $this->assertEquals($probability, Exponential::CDF($x, $λ), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [ 0, 1, 0 ],
            [ 1, 1, 0.6321206 ],
            [ 2, 1, 0.8646647 ],
            [ 3, 1, 0.9502129 ],
            [ 2, 1/3, 0.4865829 ],
            [ 4, 1/3, 0.7364029 ],
            [ 4, 1/5, 0.550671 ],
        ];
    }

    /**
     * @dataProvider dataProviderForMean
     */
    public function testMean($λ, $μ)
    {
        $this->assertEquals($μ, Exponential::mean($λ), '', 0.0001);
    }

    public function dataProviderForMean()
    {
        return [
            [1, 1],
            [2, 0.5],
            [3, 0.33333],
            [4, 0.25],
        ];
    }
}
