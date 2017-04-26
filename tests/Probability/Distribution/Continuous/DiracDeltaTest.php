<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\DiracDelta;

class DiracDeltaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $pdf)
    {
        $this->assertEquals($pdf, DiracDelta::pdf($x), '', 0.00001);
    }
    public function dataProviderForPDF()
    {
        return [
            [1, 0],
            [2, 0],
            [-12, 0],
            [0, \INF],
        ];
    }
    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($x, $cdf)
    {
        $this->assertEquals($cdf, DiracDelta::cdf($x), '', 0.00001);
    }
    public function dataProviderForCDF()
    {
        return [
            [1, 1],
            [2, 1],
            [-12, 0],
            [0, 1],
        ];
    }
    
    public function testInverse()
    {
        $this->assertEquals(0, DiracDelta::inverse(.5), '', 0.0001);
        $this->assertEquals(0, DiracDelta::inverse(.1), '', 0.0001);
        $this->assertEquals(0, DiracDelta::inverse(.7), '', 0.0001);
    }
    
    public function testRand()
    {
        $this->assertEquals(0, DiracDelta::rand(), '', 0.0001);
        $this->assertEquals(0, DiracDelta::rand(), '', 0.0001);
        $this->assertEquals(0, DiracDelta::rand(), '', 0.0001);
    }
}
