<?php
namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\ShiftedGeometric;

class ShiftedGeometricTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForPMF
     */
    public function testPMF(int $k, float $p, float $pmf)
    {
        $shiftedGeometric = new ShiftedGeometric($p);
        $this->assertEquals($pmf, $shiftedGeometric->pmf($k), '', 0.001);
    }

    public function dataProviderForPMF()
    {
        return [
            [ 5, 0.1, 0.065610 ],
            [ 5, 0.2, 0.081920 ],
            [ 1, 0.4, 0.400000 ],
            [ 2, 0.4, 0.240000 ],
            [ 3, 0.4, 0.144 ],
            [ 5, 0.5, 0.031512 ],
            [ 5, 0.09, 0.061717 ],
            [ 1, 1, 1 ],
            [ 2, 1, 0 ],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF(int $k, float $p, float $cdf)
    {
        $shiftedGeometric = new ShiftedGeometric($p);
        $this->assertEquals($cdf, $shiftedGeometric->cdf($k), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [ 5, 0.1, 0.40951 ],
            [ 5, 0.2, 0.67232 ],
            [ 1, 0.4, 0.4  ],
            [ 2, 0.4, 0.64 ],
            [ 3, 0.4, 0.784 ],
            [ 5, 0.5, 0.9688 ],
            [ 5, 0.09, 0.3759678549 ],
            [ 1, 1, 1 ],
            [ 2, 1, 1 ],
        ];
    }
}
