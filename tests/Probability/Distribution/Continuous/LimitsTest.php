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

    public function testLogLogisticParameterLimits()
    {
        $this->limitTest(LogLogistic::LIMITS);
    }

    public function testNormalParameterLimits()
    {
        $this->limitTest(Normal::LIMITS);
    }

    public function testParetoParameterLimits()
    {
        $this->limitTest(Pareto::LIMITS);
    }

    public function testStandardNormalParameterLimits()
    {
        $this->limitTest(StandardNormal::LIMITS);
    }

    public function testStudentTParameterLimits()
    {
        $this->limitTest(StudentT::LIMITS);
    }

    public function testUniformParameterLimits()
    {
        $this->limitTest(Uniform::LIMITS);
    }

    public function testWeibullParameterLimits()
    {
        $this->limitTest(Weibull::LIMITS);
    }
}
