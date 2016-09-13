<?php
namespace Math\Probability\Distribution\Continuous;

class NoncentralTTest extends \PHPUnit_Framework_TestCase
{
    const ε = .000001;
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($t, $ν, $μ, $expected)
    {
        $actual = NoncentralT::PDF($t, $ν, $μ);
        $tol = abs(self::ε * $expected);
        $this->assertEquals($expected, $actual, '', $tol);
    }

    public function dataProviderForPDF()
    {
        return [
            [0, 25, -2, 0.053453889],
            [-2, 2, -2, 0.25505237245],
            [8, 50, 10, .09559962614195],
            [12, 50, 10, .10778431492038],
        ];
    }
    
    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($t, $ν, $μ, $expected)
    {
        $actual = NoncentralT::CDF($t, $ν, $μ);
        $tol = abs(self::ε * $expected);
        $this->assertEquals($expected, $actual, '', $tol);
    }
    public function dataProviderForCDF()
    {
        return [
            [0, 25, -2, 0.97724986805],
            [2, 2, 2, 0.4204754808637],
            [8, 50, 10, 0.05611822106520649788],
            [12, 50, 10, 0.8939939602826094285],
        ];
    }
    
    /**
     * @dataProvider dataProviderForMean
     */
    public function testMean($ν, $μ, $expected)
    {
        $actual = NoncentralT::mean($ν, $μ);
        $tol = abs(self::ε * $expected);
        $this->assertEquals($expected, $actual, '', $tol);
    }

    public function dataProviderForMean()
    {
        return [
            [25, -2, -2.06260931008523],
            [2, -2, -3.54490770181103],
            [50, 10, 10.1531919459648],
            [10, 10, 10.8372230793914],
        ];
    }
}
