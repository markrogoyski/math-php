<?php
namespace MathPHP\Statistics;

class EffectSizeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForEtaSquared
     */
    public function testEtaSquared($SSB, $SST, $expected)
    {
        $η² = EffectSize::etaSquared($SSB, $SST);

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
    public function testPartialEtaSquared($SSB, $SSE, $expected)
    {
        $η²p = EffectSize::partialEtaSquared($SSB, $SSE);

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
}
