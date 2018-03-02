<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\StandardNormal;
use MathPHP\Probability\Distribution\Continuous\Normal;

class StandardNormalTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase pdf
     */
    public function testPdf()
    {
        $standardNormal = new StandardNormal();
        $normal = new Normal(0, 1);
        $this->assertEquals($normal->pdf(1), $standardNormal->pdf(1));
        $this->assertEquals($normal->pdf(5), $standardNormal->pdf(5));
        $this->assertEquals($normal->pdf(10.23), $standardNormal->pdf(10.23));
    }

    /**
     * @testCase     cdf
     * @dataProvider dataProviderForCdf
     * @param        float $z
     * @param        float $cdf
     */
    public function testCdf(float $z, float $cdf)
    {
        $μ = 0;
        $σ = 1;
        $standardNormal = new StandardNormal();
        $normal = new Normal($μ, $σ);
        $this->assertEquals($cdf, $standardNormal->cdf($z), '', 0.0001);
        $this->assertEquals($normal->cdf($z), $standardNormal->cdf($z));
    }

    /**
     * @return array
     */
    public function dataProviderForCdf(): array
    {
        return [
            [1, 0.84134], [5, 0.99997], [10.23, 1],
            [1.96, 0.97500], [-1.96, 0.02500], [-01.960, 0.02500],
            [0, 0.5000], [0.01, 0.5040], [0.02, 0.5080],
            [0.30, 0.6179], [0.31, 0.6217], [0.39, 0.6517],
            [2.90, 0.9981], [2.96, 0.9985], [3.09, 0.9990],
            [-0, 0.5000], [-0.01, 0.4960], [-0.02, 0.4920],
            [-0.30, 0.3821], [-0.31, 0.3783], [-0.39, 0.3483],
            [-2.90, 0.0019], [-2.96, 0.0015], [-3.09, 0.0010],
        ];
    }

    /**
     * @testCase mean
     */
    public function testMean()
    {
        $standardNormal = new StandardNormal();
        $this->assertEquals(0, $standardNormal->mean());
    }

    /**
     * @testCase     inverse
     * @dataProvider dataProviderForInverse
     * @param        float $target
     * @param        float $inverse
     */
    public function testInverse(float $target, float $inverse)
    {
        $standardNormal = new StandardNormal();
        $this->assertEquals($inverse, $standardNormal->inverse($target), '', 0.000001);
    }

    /**
     * @return array
     */
    public function dataProviderForInverse(): array
    {
        return [
            [0.99, 2.32634787],
            [0.9, 1.28155157],
            [0.8, 0.84162123],
            [0.7, 0.52440051],
            [0.6, 0.2533471],
            [0.51, 0.02506891],
            [0.501, 0.00250663],
            [0.500000005, 1e-8],
            [0.50000000005, 0],
            [0.5, 0],
            [0.49999999995, 0],
            [0.499999995, -1e-8],
            [0.499, -0.00250663],
            [0.49, -0.02506891],
            [0.4, -0.2533471],
            [0.3, -0.52440051],
            [0.2, -0.84162123],
            [0.1, -1.28155157],
            [0.01, -2.32634787],
        ];
    }
}
