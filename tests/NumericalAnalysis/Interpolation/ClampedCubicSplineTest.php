<?php
namespace Math\NumericalAnalysis\Interpolation;

use Math\Functions\Polynomial;

class ClampedCubicSplineTest extends \PHPUnit_Framework_TestCase
{
    public function testPolynomialAgrees()
    {
        $points = [[0, 0, 1], [1, 5, -2], [3, 2, 0], [7, 10, 3], [10, -4, 3]];

        $p = ClampedCubicSpline::interpolate($points);

        // Assure p(0) = 0 agrees with input [0, 0]
        $expected = 0;
        $actual = $p(0);
        $this->assertEquals($expected, $actual);

        // Assure p(1) = 5 agrees with input [1, 5]
        $expected = 5;
        $actual = $p(1);
        $this->assertEquals($expected, $actual);

        // Assure p(3) = 2 agrees with input [3, 2]
        $expected = 2;
        $actual = $p(3);
        $this->assertEquals($expected, $actual);

        // Assure p(7) = 10 agrees with input [7, 10]
        $expected = 10;
        $actual = $p(7);
        $this->assertEquals($expected, $actual);

        // Assure p(10) = -4 agrees with input [10, -4]
        $expected = -4;
        $actual = $p(10);
        $this->assertEquals($expected, $actual);
    }
}
