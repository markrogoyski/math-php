<?php
namespace Math\Probability\Distribution\Continuous;

class NormalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $μ, $σ, $pdf)
    {
        $this->assertEquals($pdf, Normal::PDF($x, $μ, $σ), '', 0.001);
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
        $this->assertEquals($probability, Normal::CDF($x, $μ, $σ), '', 0.001);
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
        $this->assertEquals($probability, Normal::between($lower, $upper, $μ, $σ), '', 0.00001);
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
        $this->assertEquals($probability, Normal::outside($lower, $upper, $μ, $σ), '', 0.00001);
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
        $this->assertEquals($probability, Normal::above($x, $μ, $σ), '', 0.00001);
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
        $this->assertEquals($μ, Normal::mean($μ, $σ));
    }
}
