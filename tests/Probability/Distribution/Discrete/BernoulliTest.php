<?php
namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\Bernoulli;
use MathPHP\Probability\Distribution\Discrete\Binomial;

class BernoulliTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pmf
     * @dataProvider dataProviderForPMF
     * @param        int $k
     * @param        float $p
     * @param        float $pmf
     */
    public function testPmf(int $k, float $p, float $pmf)
    {
        $bernoulli = new Bernoulli($p);
        $this->assertEquals($pmf, $bernoulli->pmf($k), '', 0.001);
    }

    /**
     * @return array
     */
    public function dataProviderForPMF(): array
    {
        return [
            [ 0, 0.6, 0.4 ],
            [ 1, 0.6, 0.6 ],
            [ 0, 0.3, 0.7 ],
            [ 1, 0.3, 0.3 ],
        ];
    }

    /**
     * @testCase pmf is same as Binomial with n = 1
     */
    public function testPmfIsBinomialWithNEqualsOne()
    {
        $p         = 0.6;
        $bernoulli = new Bernoulli($p);
        $binomial  = new Binomial(1, $p);
        $this->assertEquals($binomial->pmf(0), $bernoulli->pmf(0));
        $this->assertEquals($binomial->pmf(1), $bernoulli->pmf(1));
    }

    /**
     * @testCase     cdf
     * @dataProvider dataProviderForCDF
     * @param        int $k
     * @param        float $p
     * @param        float $cdf
     */
    public function testCdf(int $k, float $p, $cdf)
    {
        $bernoulli = new Bernoulli($p);
        $this->assertEquals($cdf, $bernoulli->cdf($k), '', 0.001);
    }

    /**
     * @return array
     */
    public function dataProviderForCDF(): array
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
