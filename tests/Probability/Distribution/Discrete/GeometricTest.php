<?php
namespace Math\Probability\Distribution\Discrete;

class GeometricTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPMF
     */
    public function testPMF(int $k, float $p, float $pmf)
    {
        $this->assertEquals($pmf, Geometric::PMF($k, $p), '', 0.001);
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
     * @dataProvider dataProviderForCDF
     */
    public function testCDF(int $k, float $p, float $cdf)
    {
        $this->assertEquals($cdf, Geometric::CDF($k, $p), '', 0.001);
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
