<?php
namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\Geometric;

class GeometricTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pmf
     * @dataProvider dataProviderForPMF
     * @param        int   $k
     * @param        float $p
     * @param        float $pmf
     *
     */
    public function testPmf(int $k, float $p, float $pmf)
    {
        $geometric = new Geometric($p);
        $this->assertEquals($pmf, $geometric->pmf($k), '', 0.001);
    }

    public function dataProviderForPMF()
    {
        return [
            [ 5, 0.1, 0.059049 ],
            [ 5, 0.2, 0.065536 ],
            [ 1, 0.4, 0.24 ],
            [ 2, 0.4, 0.144 ],
            [ 3, 0.4, 0.0864 ],
            [ 5, 0.5, 0.015625 ],
            [ 5, 0.09, 0.056162893059 ],
            [ 1, 1, 0 ],
            [ 2, 1, 0 ],
        ];
    }

    /**
     * @testCase     cdf
     * @dataProvider dataProviderForCDF
     * @param        int   $k
     * @param        float $p
     * @param        float $cdf
     */
    public function testCdf(int $k, float $p, float $cdf)
    {
        $geometric = new Geometric($p);
        $this->assertEquals($cdf, $geometric->cdf($k), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [ 5, 0.1, 0.468559 ],
            [ 5, 0.2, 0.737856 ],
            [ 1, 0.4, 0.64  ],
            [ 2, 0.4, 0.784 ],
            [ 3, 0.4, 0.8704 ],
            [ 5, 0.5, 0.984375 ],
            [ 5, 0.09, 0.432130747959 ],
            [ 1, 1, 1 ],
            [ 2, 1, 1 ],
        ];
    }
}
