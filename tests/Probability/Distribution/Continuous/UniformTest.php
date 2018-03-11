<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\Uniform;

class UniformTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pdf
     * @dataProvider dataProviderForPdf
     * @param        float $a
     * @param        float $b
     * @param        float $x
     * @param        float $pdf
     */
    public function testPdf(float $a, float $b, float $x, float $pdf)
    {
        $uniform = new Uniform($a, $b);
        $this->assertEquals($pdf, $uniform->pdf($x), '', 0.001);
    }

    /**
     * @return array [a, b, x, pdf]
     * Generated with R dunif(x, min, max)
     */
    public function dataProviderForPdf(): array
    {
        return [
            [0, 1, -1, 0],
            [0, 1, 0, 1],
            [0, 1, 0.5, 1],
            [0, 1, 1, 1],
            [0, 1, 2, 0],

            [0, 2, -1, 0],
            [0, 2, 0, 0.5],
            [0, 2, 0.5, 0.5],
            [0, 2, 1, 0.5],
            [0, 2, 2, 0.5],
            [0, 2, 3, 0],

            [1, 4, 2, 0.3333333],
            [1, 4, 3.4, 0.3333333],
            [1, 5.4, 3, 0.2272727],
            [1, 5.4, 0.3, 0],
            [1, 5.4, 6, 0],
        ];
    }

    /**
     * @testCase     cdf
     * @dataProvider dataProviderForCdf
     * @param        float $a
     * @param        float $b
     * @param        float $x
     * @param        float $cdf
     */
    public function testCdf(float $a, float $b, float $x, float $cdf)
    {
        $uniform = new Uniform($a, $b);
        $this->assertEquals($cdf, $uniform->cdf($x), '', 0.001);
    }

    /**
     * @return array [a, b, x, cdf]
     * Generated with R punif(x, min, max)
     */
    public function dataProviderForCdf(): array
    {
        return [
            [0, 1, -1, 0],
            [0, 1, 0, 0],
            [0, 1, 0.5, 0.5],
            [0, 1, 1, 1],
            [0, 1, 2, 1],

            [0, 2, -1, 0],
            [0, 2, 0, 0],
            [0, 2, 0.5, 0.25],
            [0, 2, 1, 0.5],
            [0, 2, 2, 1],
            [0, 2, 3, 1],

            [1, 4, 2, 0.3333333],
            [1, 4, 3.4, 0.8],
            [1, 5.4, 3, 0.4545455],
            [1, 5.4, 0.3, 0],
            [1, 5.4, 6, 1],
        ];
    }

    /**
     * @testCase     mean
     * @dataProvider dataProviderForMean
     * @param        float $a
     * @param        float $b
     * @param        float $μ
     */
    public function testMean(float $a, float $b, float $μ)
    {
        $uniform = new Uniform($a, $b);
        $this->assertEquals($μ, $uniform->mean(), '', 0.00001);
    }

    /**
     * @return array [a, b, μ]
     */
    public function dataProviderForMean(): array
    {
        return [
            [0, 0, 0],
            [0, 1, 0.5],
            [1, 0, 0.5],
            [1, 1, 1],
            [2, 1, 1.5],
            [2, 2, 2],
            [5, 4, 4.5],
        ];
    }
}
