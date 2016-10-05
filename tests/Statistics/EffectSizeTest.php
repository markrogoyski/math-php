<?php
namespace MathPHP\Statistics;

class EffectSizeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForEtaSquared
     */
    public function testEtaSquared($SSt, $SST, $expected)
    {
        $η² = EffectSize::etaSquared($SSt, $SST);

        $this->assertEquals($expected, $η², '', 0.0000000001);
    }

    public function dataProviderForEtaSquared()
    {
        return [
            // Test data: http://www.statisticshowto.com/eta-squared/
            [4.08, 62.29, 0.06550008026971],
            [9.2, 62.29, 0.14769625943169],
            [19.54, 62.29, 0.31369401187992],
            // Test data: http://wilderdom.com/research/ExampleCalculationOfEta-SquaredFromSPSSOutput.pdf
            [4.412, 301.70, 0.01462379847531],
            [26.196, 301.70, 0.08682797480941],
            [0.090, 301.70, 0.00029830957905],
            [271.2, 301.70, 0.89890619821014],
            // Test data: http://www.uccs.edu/lbecker/glm_effectsize.html
            [24, 610, 0.03934426229508],
            [112, 610, 0.18360655737705],
            [144, 610, 0.23606557377049],
        ];
    }

    /**
     * @dataProvider dataProviderForPartialEtaSquared
     */
    public function testPartialEtaSquared($SSt, $SSE, $expected)
    {
        $η²p = EffectSize::partialEtaSquared($SSt, $SSE);

        $this->assertEquals($expected, $η²p, '', 0.000000001);
    }

    public function dataProviderForPartialEtaSquared()
    {
        return [
            // Test data: http://jalt.org/test/bro_28.htm
            [158.372, 3068.553, 0.049078302],
            [0.344, 3003.548, 0.000114518],
            [137.572, 3003.548, 0.043797116],
            // Test data: http://www.uccs.edu/lbecker/glm_effectsize.html
            [24, 330, 0.06779661016949],
            [112, 330, 0.25339366515837],
            [144, 330, 0.30379746835443],
        ];
    }

    /**
     * @dataProvider dataProviderForOmegaSquared
     */
    public function testOmegaSquared($SSt, $dft, $SST, $MSE, $expected)
    {
        $ω² = EffectSize::omegaSquared($SSt, $dft, $SST, $MSE);

        $this->assertEquals($expected, $ω², '', 0.000001);
    }

    public function dataProviderForOmegaSquared()
    {
        return [
            // Test data: http://www.uccs.edu/lbecker/glm_effectsize.html
            [24, 1, 610, 18.333, 0.00901910292791],
            [112, 2, 610, 18.333, 0.11989502381699],
            [144, 2, 610, 18.333, 0.17082343279758],
        ];
    }

    /**
     * @dataProvider dataProviderForCohensF
     */
    public function testCohensF($measure_of_variance_explained, $expected)
    {
        $ƒ² = EffectSize::cohensF($measure_of_variance_explained);

        $this->assertEquals($expected, $ƒ², '', 0.0000001);
    }

    public function dataProviderForCohensF()
    {
        return [
            [0.06550008026971, 0.07009104964783],
            [0.01462379847531, 0.01484082774953],
            [0.18360655737705, 0.22489959839358],
            [0.00901910292791, 0.00910118747451],
        ];
    }
}
