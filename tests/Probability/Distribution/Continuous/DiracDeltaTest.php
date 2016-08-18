<?php
namespace Math\Probability\Distribution\Continuous;
class DiracDeltaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $pdf)
    {
        $this->assertEquals($pdf, DiracDelta::PDF($x), '', 0.00001);
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
        $this->assertEquals($cdf, DiracDelta::CDF($x), '', 0.00001);
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
    
    /**
     * @dataProvider dataProviderForInverse
     */
    public function testInverse($p)
    {
        $this->assertEquals($μ, DiracDelta::inverse($p), '', 0.0001);
    }
    public function dataProviderForInverse()
    {
        return [
            [.5, 0],
            [.1, 0],
            [.7, 0],
        ];
    }
    
    public function testRand()
    {
        $this->assertEquals(0, DiracDelta::rand(), '', 0.0001);
        $this->assertEquals(0, DiracDelta::rand(), '', 0.0001);
        $this->assertEquals(0, DiracDelta::rand(), '', 0.0001);
    }
}
