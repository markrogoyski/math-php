<?php
namespace Math\Probability\Distribution\Continuous;

class FTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $d1, $d2, $pdf)
    {
        $this->assertEquals($pdf, F::PDF($x, $d1, $d2), '', 0.00001);
    }

    public function dataProviderForPDF()
    {
        return [
            [1, 1, 1, 0.1591549430918953357689],
            [1, 2, 1, 0.192450089729875254836],
            [2, 2, 1, 0.089442719099991587856],
            [2, 2, 2, 0.1111111111111111111111],
            [5, 3, 7, 0.0166719620407089336431],
            [7, 6, 2, 0.01694300252714978485076],
            [7, 20, 14, 2.26334301221754722112E-4],
            [45, 2, 3, 1.86894174845759522074E-4],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($x, $d1, $d2, $cdf)
    {
        $this->assertEquals($cdf, F::CDF($x, $d1, $d2), '', 0.00001);
    }

    public function dataProviderForCDF()
    {
        return [
            [1, 1, 1, 0.5],
            [1, 2, 1, 0.42264973081037423549],
            [2, 2, 1, 0.552786404500042060718],
            [2, 2, 2, 0.6666666666666666666667],
            [5, 3, 7, 0.9633266457818135420699],
            [7, 6, 2, 0.8697407963936889556724],
            [7, 20, 14, 0.9997203495295769287577],
            [45, 2, 3, 0.9942062805797814548157],
            [0, 2, 3, 0],
        ];
    }

    /**
     * @dataProvider dataProviderForMean
     */
    public function testMean(int $d₁, int $d₂, $μ)
    {
        $this->assertEquals($μ, F::mean($d₁, $d₂), '', 0.0001);
    }

    public function dataProviderForMean()
    {
        return [
            [1, 3, 3,],
            [1, 4, 2],
            [1, 5, 1.66666667],
            [1, 6, 1.5],
        ];
    }
    
    public function testMeanNAN()
    {
        $this->assertNan(F::mean(1, 0));
        $this->assertNan(F::mean(1, 1));
        $this->assertNan(F::mean(1, 2));
    }
}
