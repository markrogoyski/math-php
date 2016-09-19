<?php
namespace Math\NumericalAnalysis\Interpolation;

class CubicSplineTest extends \PHPUnit_Framework_TestCase
{
    public function testPolynomialAgrees()
    {
        $points = [[0, 0], [1, 5], [3, 2], [7, 10], [10, -4]];

        $p = CubicSpline::interpolate($points);

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

    public function testSolveZeroError()
    {
        // f(x) = 8x³ -13x² -92x + 96
        $f = function ($x) {
            return 8 * $x**3 - 13 * $x**2 - 92 * $x + 96;
        };

        // The error in the Cubic Spline Interpolating Polynomial is proportional
        // to the max value of the 4th derivative. Thus, if our input Function
        // is a 3rd-degree polynomial, the fourth derivative will be zero, and
        // thus we will have zero error.

        $a = 0;
        $b = 10;
        $n = 5;

        $p = CubicSpline::interpolate($f, $a, $b, $n);

        // Check that p(x) agrees with f(x) at x = 0
        $expected = $f(0);
        $actual = $p(0);
        $this->assertEquals($expected, $actual);

        // Check that p(x) agrees with f(x) at x = 2
        $expected = $f(2);
        $actual = $p(2);
        $this->assertEquals($expected, $actual);

        // Check that p(x) agrees with f(x) at x = 4
        $expected = $f(4);
        $actual = $p(4);
        $this->assertEquals($expected, $actual);

        // Check that p(x) agrees with f(x) at x = 6
        $expected = $f(6);
        $actual = $p(6);
        $this->assertEquals($expected, $actual);

        // Check that p(x) agrees with f(x) at x = 8
        $expected = $f(8);
        $actual = $p(8);
        $this->assertEquals($expected, $actual);

        // Check that p(x) agrees with f(x) at x = 10
        $expected = $f(10);
        $actual = $p(10);
        $this->assertEquals($expected, $actual);

        // Check that p(x) agrees with f(x) at x = -99
        // Allow a tolerance of 0.0000001
        $roundoff = 0.000001; // round off error
        $expected = $f(-99);
        $actual = $p(-99);
        $this->assertEquals($expected, $actual, '', $roundoff);
    }
}
