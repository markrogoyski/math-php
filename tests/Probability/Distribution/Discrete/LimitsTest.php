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

    /**
     * @testCase Limits constant is correct format
     */
    public function testBernoulliParameterLimits()
    {
        $this->limitTest(Discrete\Bernoulli::PARAMETER_LIMITS);
    }

    /**
     * @testCase Limits constant is correct format
     */
    public function testBernoulliSupportLimits()
    {
        $this->limitTest(Discrete\Bernoulli::SUPPORT_LIMITS);
    }

    /**
     * @testCase Limits constant is correct format
     */
    public function testBinomialParameterLimits()
    {
        $this->limitTest(Discrete\Binomial::PARAMETER_LIMITS);
    }

    /**
     * @testCase Limits constant is correct format
     */
    public function testBinomialSupportLimits()
    {
        $this->limitTest(Discrete\Binomial::SUPPORT_LIMITS);
    }

    /**
     * @testCase Limits constant is correct format
     */
    public function testGeometricParameterLimits()
    {
        $this->limitTest(Discrete\Geometric::PARAMETER_LIMITS);
    }

    /**
     * @testCase Limits constant is correct format
     */
    public function testGeometricSupportLimits()
    {
        $this->limitTest(Discrete\Geometric::SUPPORT_LIMITS);
    }

    /**
     * @testCase Limits constant is correct format
     */
    public function testNegativeBinomialParameterLimits()
    {
        $this->limitTest(Discrete\NegativeBinomial::LIMITS);
    }

    /**
     * @testCase Limits constant is correct format
     */
    public function testPoissonParameterLimits()
    {
        $this->limitTest(Discrete\Poisson::LIMITS);
    }

    /**
     * @testCase Limits constant is correct format
     */
    public function testShiftedGeometricParameterLimits()
    {
        $this->limitTest(Discrete\ShiftedGeometric::LIMITS);
    }
}
