<?php
namespace MathPHP\Tests\Statistics;

use MathPHP\Exception;
use MathPHP\Statistics\KernelDensityEstimation;

class KernelDensityEstimationTest extends \PHPUnit_Framework_TestCase
{
    // 100 Random normally distributed data points
    private $data = [
            -2.76, -2.59, -2.57, -2.4, -1.9, -1.77, -1.33, -1.27, -1.26, -1.19, -1.13, -1.1, -1.09, -1.05, -1.03,
            -0.9, -0.87, -0.87, -0.85, -0.83, -0.78, -0.71, -0.62, -0.61, -0.6, -0.57, -0.55, -0.51, -0.5, -0.5,
            -0.47, -0.46, -0.46, -0.45, -0.44, -0.32, -0.28, -0.27, -0.25, -0.23, -0.21, -0.21, -0.2, -0.16, -0.15,
            -0.11, -0.094, -0.07, -0.06, -0.04, 0, 0.03, 0.04, 0.06, 0.06, 0.09, 0.1, 0.12, 0.15, 0.15, 0.18, 0.22,
            0.22, 0.23, 0.24, 0.3, 0.31, 0.34, 0.34, 0.38, 0.38, 0.44, 0.52, 0.53, 0.56, 0.57, 0.61, 0.61, 0.69,
            0.7, 0.75, 0.79, 0.79, 0.79, 0.8, 0.83, 0.85, 0.99, 1.08, 1.23, 1.23, 1.23, 1.25, 1.29, 1.32, 1.34,
            1.43, 1.49, 1.62, 1.75
        ];

    /**
     * @dataProvider dataProviderForKernelDensity
     */
    public function testDefaultKernelDensity(array $data, $x, $expected)
    {
        $KDE = new KernelDensityEstimation($data);
        $this->assertEquals($expected, $KDE->evaluate($x), '', 0.0001);
    }

    public function dataProviderForKernelDensity()
    {
        return [
            [ $this->data, 1, 0.236055582 ],
            [ $this->data, .1, 0.421356196 ],
            [ $this->data, -1, 0.232277743 ],
        ];
    }

    /**
     * @dataProvider dataProviderForKernelDensityCustomH
     */
    public function testDefaultKernelDensityCustomH(array $data, $h, $x, $expected)
    {
        $KDE = new KernelDensityEstimation($data, $h);
        $this->assertEquals($expected, $KDE->evaluate($x), '', 0.0001);
    }

    public function dataProviderForKernelDensityCustomH()
    {
        $h = count($this->data)**(-1/6);
        return [
            [ $this->data, $h, 1, 0.237991168 ],
            [ $this->data, $h, .1, 0.40525027 ],
            [ $this->data, $h, -1, 0.232226496 ],
        ];
    }
    
    /**
     * @dataProvider dataProviderForKernelDensityCustomBoth
     */
    public function testDefaultKernelDensityCustomBoth(array $data, $h, $kernel, $x, $expected)
    {
        $KDE = new KernelDensityEstimation($data, $h, $kernel);
        $this->assertEquals($expected, $KDE->evaluate($x), '', 0.0001);
    }

    public function dataProviderForKernelDensityCustomBoth()
    {
        $h = 1;

        // Tricube
        $kernel = function ($x) {
            if (abs($x)>1) {
                return 0;
            } else {
                return 70 / 81 * ((1 - abs($x) ** 3) ** 3);
            }
        };

        return [
            [ $this->data, $h, $kernel, 1, 0.238712304 ],
            [ $this->data, $h, $kernel, .1, 0.420794741 ],
            [ $this->data, $h, $kernel, -1, 0.229056709 ],
        ];
    }

    public function testBadH()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        $KDE = new KernelDensityEstimation($this->data, -1);
    }

    public function testEmptyData()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        $KDE = new KernelDensityEstimation([]);
    }
}
