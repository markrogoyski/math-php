<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\Cauchy;

class CauchyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pdf
     * @dataProvider dataProviderForPdf
     * @param        float $x
     * @param        float $x₀
     * @param        float $γ
     * @param        float $pdf
     */
    public function testPdf(float $x, float $x₀, float $γ, float $pdf)
    {
        $cauchy = new Cauchy($x₀, $γ);
        $this->assertEquals($pdf, $cauchy->pdf($x), '', 0.000000001);
    }

    /**
     * @return array [x, x₀, γ, pdf]
     * Generated with http://keisan.casio.com/exec/system/1180573169
     */
    public function dataProviderForPdf(): array
    {
        return [
            [1, 0, 1, 0.1591549430918953357689],
            [1, 0, 2, 0.1273239544735162686151],
            [1, 0, 3, 0.09549296585513720146133],
            [1, 0, 4, 0.07489644380795074624418],
            [1, 0, 5, 0.06121343965072897529573],
            [1, 0, 6, 0.05161781938115524403315],

            [0, 1, 1, 0.1591549430918953357689],
            [0, 1, 2, 0.1273239544735162686151],
            [0, 1, 3, 0.09549296585513720146133],
            [0, 1, 4, 0.07489644380795074624418],
            [0, 1, 5, 0.06121343965072897529573],
            [0, 1, 6, 0.05161781938115524403315],

            [1, 1, 1, 0.3183098861837906715378],
            [2, 3, 4, 0.07489644380795074624418],
            [4, 3, 2, 0.1273239544735162686151],
            [5, 5, 5, 0.06366197723675813430755],

            [-20, 7.3, 4.3, 0.001792050735277566691472],
            [-3, 7.3, 4.3, 0.01098677565090945486926],
            [-2, 7.3, 4.3, 0.01303803115441322049545],
            [-1, 7.3, 4.3, 0.01566413951236323973006],
            [0, 7.3, 4.3, 0.01906843843118277915314],
            [1, 7.3, 4.3, 0.02352582520780852333469],
            [2, 7.3, 4.3, 0.0293845536837762964279],
            [3, 7.3, 4.3, 0.03701277746323147343462],
            [20, 7.3, 4.3, 0.007613374739071642494229],
        ];
    }

    /**
     * @testCase     cdf
     * @dataProvider dataProviderForCdf
     * @param        float $x
     * @param        float $x₀
     * @param        float $γ
     * @param        float $expectedCdf
     */
    public function testCdf(float $x, float $x₀, float $γ, float $expectedCdf)
    {
        $cauchy = new Cauchy($x₀, $γ);
        $cdf = $cauchy->cdf($x);
        $this->assertEquals($expectedCdf, $cdf, '', 0.000000001);
    }

    /**
     * @testCase     inverse of CDF is original support x
     * @dataProvider dataProviderForCdf
     * @param        float $x
     * @param        float $x₀
     * @param        float $γ
     */
    public function testInverse(float $x, float $x₀, float $γ)
    {
        $cauchy = new Cauchy($x₀, $γ);
        $cdf = $cauchy->cdf($x);
        $this->assertEquals($x, $cauchy->inverse($cdf), '', 0.000000001);
    }

    /**
     * @return array [x, x₀, γ, cdf]
     * Generated with http://keisan.casio.com/exec/system/1180573169
     */
    public function dataProviderForCdf(): array
    {
        return [
            [1, 0, 1, 0.75],
            [1, 0, 2, 0.6475836176504332741754],
            [1, 0, 3, 0.6024163823495667258246],
            [1, 0, 4, 0.5779791303773693254605],
            [1, 0, 5, 0.5628329581890011838138],
            [1, 0, 6, 0.5525684567112534299508],

            [0, 1, 1, 0.25],
            [0, 1, 2, 0.3524163823495667258246],
            [0, 1, 3, 0.3975836176504332741754],
            [0, 1, 4, 0.4220208696226306745395],
            [0, 1, 5, 0.4371670418109988161863],
            [0, 1, 6, 0.4474315432887465700492],

            [1, 1, 1, 0.5],
            [2, 3, 4, 0.4220208696226306745395],
            [4, 3, 2, 0.6475836176504332741754],
            [5, 5, 5, 0.5],

            [-20, 7.3, 4.3, 0.04972817023155424541129],
            [-3, 7.3, 4.3, 0.1258852891111436766445],
            [-2, 7.3, 4.3, 0.1378566499474175095298],
            [-1, 7.3, 4.3, 0.1521523453170898354801],
            [0, 7.3, 4.3, 0.1694435179635968563959],
            [1, 7.3, 4.3, 0.1906393755555404651458],
            [2, 7.3, 4.3, 0.2169618719223694455636],
            [3, 7.3, 4.3, 0.25],
            [20, 7.3, 4.3, 0.8960821670587991836005],
        ];
    }

    /**
     * @testCase     mean is not a number
     * @dataProvider dataProviderForAverages
     * @param        float $x₀
     * @param        float $γ
     */
    public function testMean(float $x₀, float $γ)
    {
        $cauchy = new Cauchy($x₀, $γ);
        $this->assertNan($cauchy->mean());
    }

    /**
     * @testCase     medidan is $x₀
     * @dataProvider dataProviderForAverages
     * @param        float $x₀
     * @param        float $γ
     */
    public function testMedian(float $x₀, float $γ)
    {
        $cauchy = new Cauchy($x₀, $γ);
        $this->assertEquals($x₀, $cauchy->median());
    }

    /**
     * @testCase     mode is $x₀
     * @dataProvider dataProviderForAverages
     * @param        float $x₀
     * @param        float $γ
     */
    public function testMode(float $x₀, float $γ)
    {
        $cauchy = new Cauchy($x₀, $γ);
        $this->assertEquals($x₀, $cauchy->mode());
    }

    /**
     * @return array [x₀, γ]
     */
    public function dataProviderForAverages(): array
    {
        return [
            [-1, 0.1],
            [-1, 1],
            [-1, 2],
            [0, 0.1],
            [0, 1],
            [0, 2],
            [1, 0.1],
            [1, 1],
            [1, 2],
            [2, 3],
            [5, 3],
        ];
    }
}
