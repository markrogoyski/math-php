<?php
namespace Math\Probability\Distribution\Discrete;

class BernoulliTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPMF
     */
    public function testPMF(int $k, float $p, $pmf)
    {
        $this->assertEquals($pmf, Bernoulli::PMF($k, $p), '', 0.001);
    }

    public function dataProviderForPMF()
    {
        return [
            [ 0, 0.6, 0.4 ],
            [ 1, 0.6, 0.6 ],
            [ 0, 0.3, 0.7 ],
            [ 1, 0.3, 0.3 ],
        ];
    }

    public function testPFMIsBinomialWithNEqualsOne()
    {
        $this->assertEquals(Binomial::PMF(1, 0, 0.6), Bernoulli::PMF(0, 0.6));
        $this->assertEquals(Binomial::PMF(1, 1, 0.6), Bernoulli::PMF(1, 0.6));
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF(int $k, float $p, $cdf)
    {
        $this->assertEquals($cdf, Bernoulli::CDF($k, $p), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [ 0, 0.6, 0.4 ],
            [ 1, 0.6, 1 ],
            [ 0, 0.3, 0.7 ],
            [ 1, 0.3, 1 ],
            [ -1, 0.5, 0 ],
        ];
    }
}
