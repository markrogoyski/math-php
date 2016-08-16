<?php
namespace Math\Probability\Distribution\Discrete;

class LimitsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Limits should look like:
     *  (a,b)
     *  [a,b)
     *  (a,b]
     *  [a,b]
     */
    private function limitTest($limits)
    {
        foreach ($limits as $parameter => $limit) {
            $this->assertRegExp('/^ ([[(]) (.+) , (.+?) ([])]) $/x', $limit);
        }
    }

    public function testBernoulliParameterLimits()
    {
        $this->limitTest(Bernoulli::LIMITS);
    }

    public function testBinomialParameterLimits()
    {
        $this->limitTest(Binomial::LIMITS);
    }

    public function testGeometricParameterLimits()
    {
        $this->limitTest(Geometric::LIMITS);
    }

    public function testNegativeBinomialParameterLimits()
    {
        $this->limitTest(NegativeBinomial::LIMITS);
    }

    public function testPoissonParameterLimits()
    {
        $this->limitTest(Poisson::LIMITS);
    }

    public function testShiftedGeometricParameterLimits()
    {
        $this->limitTest(ShiftedGeometric::LIMITS);
    }
}
