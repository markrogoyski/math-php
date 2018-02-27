<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\Normal;

class NormalTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $μ, $σ, $pdf)
    {
        $normal = new Normal($μ, $σ);
        $this->assertEquals($pdf, $normal->pdf($x), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [ 84, 72, 15.2, 0.01921876 ],
            [ 26, 25, 2, 0.17603266338 ],
            [ 4, 0, 1, .000133830225 ],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($x, $μ, $σ, $probability)
    {
        $normal = new Normal($μ, $σ);
        $this->assertEquals($probability, $normal->cdf($x), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [ 84, 72, 15.2, 0.7851 ],
            [ 26, 25, 2, 0.6915 ],
            [ 6, 5, 1, 0.8413 ],
            [ 39, 25, 14, 0.8413 ],
            [ 1.96, 0, 1, 0.975 ],
            [ 3.5, 4, 0.3, 0.0478 ],
            [ 1.3, 1, 1.1, 0.6075 ],
        ];
    }
    
    /**
     * @dataProvider dataProviderForBetween
     */
    public function testBetween($lower, $upper, $μ, $σ, $probability)
    {
        $normal = new Normal($μ, $σ);
        $this->assertEquals($probability, $normal->between($lower, $upper), '', 0.00001);
    }

    public function dataProviderForBetween()
    {
        return [
            [ 5, 7, 6, 1, 0.682689492 ],
            [ -1.95996398454005, 1.95996398454005, 0, 1, .95 ],
        ];
    }
    
    /**
     * @dataProvider dataProviderForOutside
     */
    public function testOutside($lower, $upper, $μ, $σ, $probability)
    {
        $normal = new Normal($μ, $σ);
        $this->assertEquals($probability, $normal->outside($lower, $upper), '', 0.00001);
    }

    public function dataProviderForOutside()
    {
        return [
            [ 5, 7, 6, 2, 0.6170750774519740000000000 ],
            [ -1.64485362695147, 1.64485362695147, 0, 1, 0.1 ],
        ];
    }
    
    /**
     * @dataProvider dataProviderForAbove
     */
    public function testAbove($x, $μ, $σ, $probability)
    {
        $normal = new Normal($μ, $σ);
        $this->assertEquals($probability, $normal->above($x), '', 0.00001);
    }

    public function dataProviderForAbove()
    {
        return [
            [ 1.64485362695147, 0, 1, 0.05 ],
            [ 1.95996398454005, 6, 2, 0.97830924 ],
        ];
    }

    public function testMean()
    {
        $μ = 5;
        $σ = 1.5;
        $normal = new Normal($μ, $σ);
        $this->assertEquals($μ, $normal->mean());
    }
}
