<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\Exponential;

class ExponentialTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     * @param number $x
     * @param number $λ
     * @param number $probability
     */
    public function testPDF($x, $λ, $probability)
    {
        $exponential = new Exponential($λ);
        $this->assertEquals($probability, $exponential->pdf($x), '', 0.001);
    }

    /**
     * @return array
     */
    public function dataProviderForPDF(): array
    {
        return [
            [ -1, 1, 0],
            [ 0, 1, 1 ],
            [ 1, 1, 0.36787944117 ],
            [ 2, 1, 0.13533528323 ],
            [ 3, 1, 0.04978706836 ],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     * @param number $x
     * @param number $λ
     * @param number $probability
     */
    public function testCDF($x, $λ, $probability)
    {
        $exponential = new Exponential($λ);
        $this->assertEquals($probability, $exponential->cdf($x), '', 0.001);
    }

    /**
     * @return array
     */
    public function dataProviderForCDF(): array
    {
        return [
            [-1, 1, 0],
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
     * @param number $λ
     * @param number $μ
     */
    public function testMean($λ, $μ)
    {
        $exponential = new Exponential($λ);
        $this->assertEquals($μ, $exponential->mean(), '', 0.0001);
    }

    /**
     * @return array
     */
    public function dataProviderForMean(): array
    {
        return [
            [1, 1],
            [2, 0.5],
            [3, 0.33333],
            [4, 0.25],
        ];
    }
}
