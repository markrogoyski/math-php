<?php
namespace Math\Probability\Distribution\Discrete;

class BinomialTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPMF
     */
    public function testPMF(int $n, int $r, float $p, float $pmf)
    {
        $this->assertEquals($pmf, Binomial::PMF($n, $r, $p), '', 0.001);
    }

    /**
     * Data provider for binomial
     * Data: [ n, r, p, binomial distribution ]
     */
    public function dataProviderForPMF()
    {
        return [
        [ 2, 1, 0.5, 0.5 ],
        [ 2, 1, 0.4, 0.48 ],
        [ 6, 2, 0.7, 0.0595350 ],
        [ 8, 7, 0.83, 0.3690503 ],
        [ 10, 5, 0.85, 0.0084909 ],
        [ 50, 48, 0.97, 0.2555182 ],
        [ 5, 4, 1, 0.0 ],
        [ 12, 4, 0.2, 0.1329 ]
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF(int $n, int $r, float $p, float $cdf)
    {
        $this->assertEquals($cdf, Binomial::CDF($n, $r, $p), '', 0.001);
    }

    /**
     * Data provider for cdf
     * Data: [ n, r, p, cdf ]
     */
    public function dataProviderForCDF()
    {
        return [
            [ 2, 1, 0.5, 0.75 ],
            [ 2, 1, 0.4, 0.84 ],
            [ 6, 2, 0.7, 0.07047 ],
            [ 8, 7, 0.83, 0.7747708 ],
            [ 10, 5, 0.85, 0.009874091 ],
            [ 50, 48, 0.97, 0.4447201 ],
            [ 5, 4, 1, 0.0 ],
            [ 12, 4, 0.2, 0.92744 ],
        ];
    }
}
