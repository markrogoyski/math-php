<?php

namespace MathPHP\Tests\Probability\Distribution\Multivariate;

use MathPHP\Probability\Distribution\Multivariate;

class LimitsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Limits should look like:
     *  (a,b)
     *  [a,b)
     *  (a,b]
     *  [a,b]
     */
    private function limitTest(array $limits)
    {
        foreach ($limits as $parameter => $limit) {
            $this->assertRegExp('/^ ([[(]) (.+) , (.+?) ([])]) $/x', $limit);
        }
    }

    /**
     * @test Limits constant is correct format
     */
    public function testDirichletParameterLimits()
    {
        $this->limitTest(Multivariate\Dirichlet::PARAMETER_LIMITS);
    }

    /**
     * @test Limits constant is correct format
     */
    public function testDirichletSupportLimits()
    {
        $this->limitTest(Multivariate\Dirichlet::SUPPORT_LIMITS);
    }

    /**
     * @test Limits constant is correct format
     */
    public function testHypergeometricParameterLimits()
    {
        $this->limitTest(Multivariate\Hypergeometric::PARAMETER_LIMITS);
    }

    /**
     * @test Multinomial and Normal distributions do not define LIMITS constants
     *
     * Multinomial probabilities must satisfy: sum to 1, all > 0
     * These constraints are complex and validated at construction time, not easily
     * expressible as simple interval limits.
     *
     * Normal covariance must be: positive definite symmetric matrix
     * This constraint is validated using isPositiveDefinite() method and cannot
     * be expressed as simple interval limits.
     */
    public function testMultinomialAndNormalDoNotHaveLimitsConstants()
    {
        // Multinomial has no PARAMETER_LIMITS constant (complex validation)
        $this->assertFalse(\defined('MathPHP\Probability\Distribution\Multivariate\Multinomial::PARAMETER_LIMITS'));

        // Normal has no PARAMETER_LIMITS or SUPPORT_LIMITS constants (complex validation)
        $this->assertFalse(\defined('MathPHP\Probability\Distribution\Multivariate\Normal::PARAMETER_LIMITS'));
        $this->assertFalse(\defined('MathPHP\Probability\Distribution\Multivariate\Normal::SUPPORT_LIMITS'));
    }
}
