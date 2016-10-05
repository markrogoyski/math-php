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

        $this->assertEquals($expected, $η², 0.0000000001);
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
        ];
    }
}
