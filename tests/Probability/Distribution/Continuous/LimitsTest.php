<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous;

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
        $this->limitTest(Continuous\Beta::PARAMETER_LIMITS);
    }

    public function testCauchyParameterLimits()
    {
        $this->limitTest(Continuous\Cauchy::PARAMETER_LIMITS);
    }

    public function testChiSquaredParameterLimits()
    {
        $this->limitTest(Continuous\ChiSquared::PARAMETER_LIMITS);
    }

    public function testExponentialParameterLimits()
    {
        $this->limitTest(Continuous\Exponential::LIMITS);
    }

    public function testFParameterLimits()
    {
        $this->limitTest(Continuous\F::PARAMETER_LIMITS);
    }

    public function testLaplaceParameterLimits()
    {
        $this->limitTest(Continuous\Laplace::LIMITS);
    }

    public function testLogisticParameterLimits()
    {
        $this->limitTest(Continuous\Logistic::PARAMETER_LIMITS);
    }

    public function testLogLogisticParameterLimits()
    {
        $this->limitTest(Continuous\LogLogistic::PARAMETER_LIMITS);
    }

    public function testNormalParameterLimits()
    {
        $this->limitTest(Continuous\Normal::PARAMETER_LIMITS);
    }

    public function testParetoParameterLimits()
    {
        $this->limitTest(Continuous\Pareto::LIMITS);
    }

    public function testStandardNormalParameterLimits()
    {
        $this->limitTest(Continuous\StandardNormal::PARAMETER_LIMITS);
    }

    public function testStudentTParameterLimits()
    {
        $this->limitTest(Continuous\StudentT::PARAMETER_LIMITS);
    }

    public function testUniformParameterLimits()
    {
        $this->limitTest(Continuous\Uniform::LIMITS);
    }

    public function testWeibullParameterLimits()
    {
        $this->limitTest(Continuous\Weibull::PARAMETER_LIMITS);
    }
}
