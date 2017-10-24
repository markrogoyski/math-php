<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\F;

class FTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $d1, $d2, $pdf)
    {
        $f = new F($d1, $d2);
        $this->assertEquals($pdf, $f->pdf($x), '', 0.00001);
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
        $f = new F($d1, $d2);
        $this->assertEquals($cdf, $f->cdf($x), '', 0.00001);
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
    public function testMean(int $d1, int $d2, $μ)
    {
        $f = new F($d1, $d2);
        $this->assertEquals($μ, $f->mean(), '', 0.0001);
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
        $f1 = new F(1, 1);
        $f2 = new F(1, 2);
        $this->assertNan($f1->mean());
        $this->assertNan($f2->mean());
    }
}
