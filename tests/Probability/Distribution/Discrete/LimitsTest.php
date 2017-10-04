<?php
namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete;

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
        $this->limitTest(Discrete\Bernoulli::LIMITS);
    }

    public function testBinomialParameterLimits()
    {
        $this->limitTest(Discrete\Binomial::PARAMETER_LIMITS);
    }

    public function testBinomialSupportLimits()
    {
        $this->limitTest(Discrete\Binomial::SUPPORT_LIMITS);
    }

    public function testGeometricParameterLimits()
    {
        $this->limitTest(Discrete\Geometric::LIMITS);
    }

    public function testNegativeBinomialParameterLimits()
    {
        $this->limitTest(Discrete\NegativeBinomial::LIMITS);
    }

    public function testPoissonParameterLimits()
    {
        $this->limitTest(Discrete\Poisson::LIMITS);
    }

    public function testShiftedGeometricParameterLimits()
    {
        $this->limitTest(Discrete\ShiftedGeometric::LIMITS);
    }
}
