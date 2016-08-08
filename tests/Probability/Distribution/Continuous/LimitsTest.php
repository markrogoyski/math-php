<?php
namespace Math\Probability\Distribution\Continuous;

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

    public function testBetaParameterLimits()
    {
        $this->limitTest(Beta::LIMITS);
    }

    public function testCauchyParameterLimits()
    {
        $this->limitTest(Cauchy::LIMITS);
    }

    public function testChiSquaredParameterLimits()
    {
        $this->limitTest(ChiSquared::LIMITS);
    }

    public function testExponentialParameterLimits()
    {
        $this->limitTest(Exponential::LIMITS);
    }

    public function testFParameterLimits()
    {
        $this->limitTest(F::LIMITS);
    }

    public function testLaplaceParameterLimits()
    {
        $this->limitTest(Laplace::LIMITS);
    }

    public function testLogisticParameterLimits()
    {
        $this->limitTest(Logistic::LIMITS);
    }
}