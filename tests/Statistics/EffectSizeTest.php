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
            [0.25, 0.33333333],
            [0.00001, 0.000010],
            [0.99999, 99999.00000046],
        ];
    }

    /**
     * @dataProvider dataProviderForCohensQ
     */
    public function testCohensQ($r₁, $r₂, $expected)
    {
        $q = EffectSize::cohensQ($r₁, $r₂);

        $this->assertEquals($expected, $q, '', 0.001);
    }

    public function dataProviderForCohensQ()
    {
        return [
            [0.1, 0.1, 0],
            [0.5, 0.5, 0],
            [0.1, 0.2, 0.102],
            [0.2, 0.1, 0.102],
            [0.1, 0.5, 0.449],
            [0.1, 0.9, 1.372],
            [0.1, 0, 0.1],
            [0.1, -0.1, 0.201],
        ];
    }

    public function testCohensQExceptionROutOfBounds()
    {
        $this->setExpectedException('\Exception');

        EffectSize::cohensQ(0.1, 2);
    }

    /**
     * @dataProvider dataProviderForCohensD
     */
    public function testCohensD($μ₁, $μ₂, $s₁, $s₂, $expected)
    {
        $d = EffectSize::cohensD($μ₁, $μ₂, $s₁, $s₂);

        $this->assertEquals($expected, $d, '', 0.00001);
    }

    public function dataProviderForCohensD()
    {
        return [
            // Test data: http://www.uccs.edu/~lbecker/
            [3, 3, 1.5811388300842, 1.5811388300842, 0],
            [3, 4, 1.5811388300842, 1.5811388300842, -0.6324555320336718],
            [6, 4.9166666666667, 1.5954480704349, 2.5030284687058, 0.5161479565960618],
            [40, 57.727272727273, 21.275964529644, 30.763910379179, -0.6702470286592815],
            [6.7, 6, 1.2, 1, 0.6337502222976299],
            [9, 3.5, 1.2, 1.5, 4.049155956077707],
            [108, 118, 15, 14.83239697419133, -0.6704015],
        ];
    }

    /**
     * @dataProvider dataProviderForHedgesG
     */
    public function testHedgesG($μ₁, $μ₂, $s₁, $s₂, $n₁, $n₂, $expected)
    {
        $g = EffectSize::hedgesG($μ₁, $μ₂, $s₁, $s₂, $n₁, $n₂);

        $this->assertEquals($expected, $g, '', 0.00001);
    }

    public function dataProviderForHedgesG()
    {
        return [
            // Test data: http://www.polyu.edu.hk/mm/effectsizefaqs/calculator/calculator.html
            [3, 3, 1.5811388300842, 1.5811388300842, 5, 5, 0],
            [3, 4, 1.5811388300842, 1.5811388300842, 5, 5, -0.57125016],
            [6, 4.9166666666667, 1.5954480704349, 2.5030284687058, 12, 12, 0.49834975],
            [40, 57.727272727273, 21.275964529644, 30.763910379179, 7, 11, -0.61190744],
            [6.7, 6, 1.2, 1, 15, 15, 0.61662184],
            [6.7, 6, 1.2, 1, 16, 15, 0.61530752],
            [6.7, 6, 1.2, 1, 45, 15, 0.59824169],
            [9, 3.5, 1.2, 1.5, 13, 15, 3.89844347],
            [108, 118, 15, 14.83239697419133, 21, 18, -0.65642092],
        ];
    }

    /**
     * @dataProvider dataProviderForGlassDelta
     */
    public function testGlassDelta($μ₁, $μ₂, $s₂, $expected)
    {
        $Δ = EffectSize::glassDelta($μ₁, $μ₂, $s₂);

        $this->assertEquals($expected, $Δ, '', 0.00001);
    }

    public function dataProviderForGlassDelta()
    {
        return [
            [40, 57.727272727273, 30.763910379179, -0.57623600],
            [3, 4, 1.5811388300842, -0.63245553],
            [3, 3, 1.5, 0],
        ];
    }
}
