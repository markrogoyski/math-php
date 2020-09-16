<?php

namespace MathPHP\Tests\NumericalAnalysis\Interpolation;

use MathPHP\Exception\BadDataException;
use MathPHP\NumericalAnalysis\Interpolation\RegularGridInterpolator;

class RegularGridInterpolatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         Interpolated regular grid function computes expected values: p(x) = expected
     * @dataProvider dataProviderForRegularGridAgrees
     * @param array $point
     * @param float $expected
     * @throws       \Exception
     */
    public function testRegularGridAgrees(array $point, float $expected)
    {
        list($points, $values) = $this->getSample4d();

        // And
        $p = RegularGridInterpolator::interpolate($points, $values);

        // When
        $evaluated = $p($point);
        // Then
        $this->assertEquals($expected, $evaluated);
    }


    /**
     * @return array (x, expected)
     */
    public function dataProviderForRegularGridAgrees(): array
    {
        return [
            //test_linear_xi3d
            [[0.1, 0.1, 1., .9], 1001.1],
            [[0.2, 0.1, .45, .8], 846.2],
            [[0.5, 0.5, .5, .5], 555.5],

            //test_linear_edges
            [[0., 0., 0., 0.], 0.],
            [[1., 1., 1., 1.], 1111.],
        ];
    }

    /**
     * @test         Interpolated regular grid function computes expected values: p(x) = expected
     * @dataProvider dataProviderForRegularGridNearestAgrees
     * @param array $point
     * @throws       \Exception
     */
    public function testRegularGridNearestAgrees(array $point, $expected)
    {
        list($points, $values) = $this->getSample4d();

        // And
        $p = RegularGridInterpolator::interpolate($points, $values, RegularGridInterpolator::METHOD_NEAREST);

        // When
        $evaluated = $p($point);
        // Then
        $this->assertEquals($expected, $evaluated);
    }


    /**
     * @return array (x, expected)
     */
    public function dataProviderForRegularGridNearestAgrees(): array
    {
        return [
            [[0.1, 0.1, .9, .9], 1100],
            [[0.1, 0.1, 0.1, 0.1], 0.],
            [[0., 0., 0., 0.], 0.],
            [[1., 1., 1., 1.], 1111.],
            [[0.1, 0.4, 0.6, 0.9], 1055.]
        ];
    }


    public function testBadMethodException()
    {
        // Method 'abc' is not defined
        $this->expectException(BadDataException::class);
        RegularGridInterpolator::interpolate([0], [0], 'abc');
    }

    public function testBadValuesException()
    {
        // There are 2 point arrays, but values has 1 dimensions
        $this->expectException(BadDataException::class);
        RegularGridInterpolator::interpolate([[0], [1]], [0]);
    }

    public function testInvokeBadMethodException()
    {
        // Method 'abc' is not defined
        $this->expectException(BadDataException::class);
        $interp = RegularGridInterpolator::interpolate([0], [0]);
        $interp([0], 'abc');
    }

    public function testInvokeBadPointDimensionException()
    {
        // The requested sample points xi have dimension 2, but this RegularGridInterpolator has dimension 1
        $this->expectException(BadDataException::class);
        $interp = RegularGridInterpolator::interpolate([0], [0]);
        $interp([0, 2]);
    }


    public function getSample4d(): array
    {
        # create a 4-D grid of 3 points in each dimension
        $points = [[0.0, 0.5, 1.0], [0.0, 0.5, 1.0], [0.0, 0.5, 1.0], [0.0, 0.5, 1.0]];
        $v = [0, 0.5, 1.];
        for ($x = 0; $x < 3; $x++) {
            for ($y = 0; $y < 3; $y++) {
                for ($z = 0; $z < 3; $z++) {
                    for ($m = 0; $m < 3; $m++) {
                        $values[$x][$y][$z][$m] = $v[$x] + $v[$y] * 10 + $v[$z] * 100 + +$v[$m] * 1000;
                    }
                }
            }
        }
        return [$points, $values];
    }
}
