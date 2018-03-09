<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\Laplace;

class LaplaceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pdf
     * @dataProvider dataProviderForPdf
     * @param        number $x
     * @param        number $μ
     * @param        number $b
     * @param        number $pdf
     */
    public function testPdf($x, $μ, $b, $pdf)
    {
        $laplace = new Laplace($μ, $b);
        $this->assertEquals($pdf, $laplace->pdf($x), '', 0.000001);
    }

    /**
     * @return array [x, μ, b, pdf]
     */
    public function dataProviderForPdf(): array
    {
        return [
            [1, 0, 1, 0.1839397206],
            [1.1, 0, 1, 0.1664355418],
            [1.2, 0, 1, 0.150597106],
            [5, 0, 1, 0.0033689735],
            [1, 2, 1.4, 0.174836307],
            [1.1, 2, 1.4, 0.1877814373],
            [2.9, 2, 1.4, 0.1877814373],
        ];
    }

    /**
     * @testCase     cdf
     * @dataProvider dataProviderForCdf
     * @param        number $x
     * @param        number $μ
     * @param        number $b
     * @param        number $cdf
     */
    public function testCdf($x, $μ, $b, $cdf)
    {
        $laplace = new Laplace($μ, $b);
        $this->assertEquals($cdf, $laplace->cdf($x), '', 0.000001);
    }

    /**
     * @return array [x, μ, b, cdf]
     */
    public function dataProviderForCdf(): array
    {
        return [
            [1, 0, 1, 0.8160602794],
            [1.1, 0, 1, 0.8335644582],
            [1.2, 0, 1, 0.849402894],
            [5, 0, 1, 0.9966310265],
            [1, 2, 1.4, 0.2447708298],
            [1.1, 2, 1.4, 0.2628940122],
            [2.9, 2, 1.4, 0.7371059878],
        ];
    }

    /**
     * @testCase mean
     */
    public function testMean()
    {
        $μ = 5;
        $b = 1;
        $laplace = new Laplace($μ, $b);
        $this->assertEquals($μ, $laplace->mean());
    }
}
