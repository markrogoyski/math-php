<?php
namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\Bernoulli;
use MathPHP\Probability\Distribution\Discrete\Binomial;

class BernoulliTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPMF
     */
    public function testPMF(int $k, float $p, $pmf)
    {
        $this->assertEquals($pmf, Bernoulli::pmf($k, $p), '', 0.001);
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
        $binomial = new Binomial(1, 0.6);
        $this->assertEquals($binomial->pmf(0), Bernoulli::pmf(0, 0.6));
        $this->assertEquals($binomial->pmf(1), Bernoulli::pmf(1, 0.6));
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF(int $k, float $p, $cdf)
    {
        $this->assertEquals($cdf, Bernoulli::cdf($k, $p), '', 0.001);
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
