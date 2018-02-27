<?php
namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\Poisson;

class PoissonTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForPMF
     */
    public function testPMF(int $k, float $λ, float $probability)
    {
        $poisson = new Poisson($λ);
        $this->assertEquals($probability, $poisson->pmf($k), '', 0.001);
    }

    /**
     * Data provider for
     * Data: [ k, λ,  distribution ]
     */
    public function dataProviderForPMF()
    {
        return [
            [ 3, 2, 0.180 ],
            [ 3, 5, 0.140373895814280564513 ],
            [ 8, 6, 0.103257733530844 ],
            [ 2, 0.45, 0.065 ],
            [ 16, 12, 0.0542933401099791 ],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF(int $k, float $λ, float $probability)
    {
        $poisson = new Poisson($λ);
        $this->assertEquals($probability, $poisson->cdf($k), '', 0.001);
    }

    /**
     * Data provider for cumulative
     * Data: [ k, λ, culmulative  distribution ]
     */
    public function dataProviderForCDF()
    {
        return [
            [ 3, 2, 0.857123460498547048662 ],
            [ 3, 5, 0.2650 ],
            [ 8, 6, 0.8472374939845613089968 ],
            [ 2, 0.45, 0.99 ],
            [ 16, 12, 0.898708992560164 ],
        ];
    }
}
