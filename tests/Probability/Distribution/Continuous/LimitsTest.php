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
        $this->limitTest(Continuous\Beta::LIMITS);
    }

    public function testCauchyParameterLimits()
    {
        $this->limitTest(Continuous\Cauchy::LIMITS);
    }

    public function testChiSquaredParameterLimits()
    {
        $this->limitTest(Continuous\ChiSquared::LIMITS);
    }

    public function testExponentialParameterLimits()
    {
        $this->limitTest(Continuous\Exponential::LIMITS);
    }

    public function testFParameterLimits()
    {
        $this->limitTest(Continuous\F::LIMITS);
    }

    public function testLaplaceParameterLimits()
    {
        $this->limitTest(Continuous\Laplace::LIMITS);
    }

    public function testLogisticParameterLimits()
    {
        $this->limitTest(Continuous\Logistic::LIMITS);
    }

    public function testLogLogisticParameterLimits()
    {
        $this->limitTest(Continuous\LogLogistic::LIMITS);
    }

    public function testNormalParameterLimits()
    {
        $this->limitTest(Continuous\Normal::LIMITS);
    }

    public function testParetoParameterLimits()
    {
        $this->limitTest(Continuous\Pareto::LIMITS);
    }

    public function testStandardNormalParameterLimits()
    {
        $this->limitTest(Continuous\StandardNormal::LIMITS);
    }

    public function testStudentTParameterLimits()
    {
        $this->limitTest(Continuous\StudentT::LIMITS);
    }

    public function testUniformParameterLimits()
    {
        $this->limitTest(Continuous\Uniform::LIMITS);
    }

    public function testWeibullParameterLimits()
    {
        $this->limitTest(Continuous\Weibull::LIMITS);
    }
}
