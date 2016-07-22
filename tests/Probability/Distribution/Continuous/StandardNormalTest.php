<?php
namespace Math\Probability\Distribution\Continuous;

class StandardNormalTest extends \PHPUnit_Framework_TestCase
{
    public function testPDF()
    {
        $this->assertEquals(Normal::PDF(1, 0, 1), StandardNormal::PDF(1));
        $this->assertEquals(Normal::PDF(5, 0, 1), StandardNormal::PDF(5));
        $this->assertEquals(Normal::PDF(10.23, 0, 1), StandardNormal::PDF(10.23));
    }

    public function testCDF()
    {
        $this->assertEquals(Normal::CDF(1, 0, 1), StandardNormal::CDF(1));
        $this->assertEquals(Normal::CDF(5, 0, 1), StandardNormal::CDF(5));
        $this->assertEquals(Normal::CDF(10.23, 0, 1), StandardNormal::CDF(10.23));
    }
}