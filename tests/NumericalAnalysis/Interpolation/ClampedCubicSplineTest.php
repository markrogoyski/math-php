<?php
namespace MathPHP\Tests\NumericalAnalysis\Interpolation;

use MathPHP\NumericalAnalysis\Interpolation\ClampedCubicSpline;
use MathPHP\Functions\Polynomial;
use MathPHP\Exception;

class ClampedCubicSplineTest extends \PHPUnit\Framework\TestCase
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

    public function testSolveZeroError()
    {
        // f(x) = 8x³ -13x² -92x + 96
        $f = new Polynomial([8, -13, -92, 96]);
        $f’ = $f->differentiate();

        // The error in the Cubic Spline Interpolating Polynomial is proportional
        // to the max value of the 4th derivative. Thus, if our input Function
        // is a 3rd-degree polynomial, the fourth derivative will be zero, and
        // thus we will have zero error.

        $a = 0;
        $b = 10;
        $n = 50;
        $tol = 0;
        $roundoff = 0.0001; // round off error

        $p = ClampedCubicSpline::interpolate($f, $f’, $a, $b, $n);

        // Check that p(x) agrees with f(x) at x = 0
        $expected = $f(0);
        $actual = $p(0);
        $this->assertEquals($expected, $actual, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 2
        $expected = $f(2);
        $actual = $p(2);
        $this->assertEquals($expected, $actual, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 4
        $expected = $f(4);
        $actual = $p(4);
        $this->assertEquals($expected, $actual, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 6
        $expected = $f(6);
        $actual = $p(6);
        $this->assertEquals($expected, $actual, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 8
        $expected = $f(8);
        $actual = $p(8);
        $this->assertEquals($expected, $actual, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 10
        $expected = $f(10);
        $actual = $p(10);
        $this->assertEquals($expected, $actual, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 7.32 (not a node)
        $expected = $f(10);
        $actual = $p(10);
        $this->assertEquals($expected, $actual, '', $tol + $roundoff);
    }

    public function testSolveNonzeroError()
    {
        // f(x) = x⁴ + 8x³ -13x² -92x + 96
        $f = new Polynomial([1, 8, -13, -92, 96]);
        $f’ = $f->differentiate();
        $f⁽⁴⁾ = $f’->differentiate()->differentiate()->differentiate();

        // The error is bounded by:
        // |f(x)-p(x)| = tol <= (5/384) * h⁴ * max f⁽⁴⁾(x)
        // where h = max hᵢ
        // and max f⁽⁴⁾(x) = f⁽⁴⁾(x) for all x given a 4th-degree polynomial f(x)

        $a = 0;
        $b = 10;
        $n = 51;

        // So, tol <= (1/24) * (1/5)⁴ * 24 = (1/5)⁴

        $h = ($b-$a)/($n-1);
        $tol = (5/384) * ($h**4) * $f⁽⁴⁾(0);

        $roundoff = 0.000001; // round off error

        $p = ClampedCubicSpline::interpolate($f, $f’, $a, $b, $n);

        // Check that p(x) agrees with f(x) at x = 0
        $target = 0;
        $expected = $f($target);
        $x = $p($target);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 2
        $target = 2;
        $expected = $f($target);
        $x = $p($target);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 4
        $target = 4;
        $expected = $f($target);
        $x = $p($target);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 6
        $target = 6;
        $expected = $f($target);
        $x = $p($target);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 8
        $target = 8;
        $expected = $f($target);
        $x = $p($target);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 10
        $target = 10;
        $expected = $f($target);
        $x = $p($target);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 7.32 (not a node)
        $target = 7.32;
        $expected = $f($target);
        $x = $p($target);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);
    }

    public function testIncorrectInput()
    {
        // The input $source is neither a callback or a set of arrays
        $this->expectException(Exception\BadDataException::class);
        $x                 = 10;
        $incorrectFunction = $x**2 + 2 * $x + 1;
        ClampedCubicSpline::getSplinePoints($incorrectFunction, [0,4,5]);
    }

    public function testNotCoordinatesException()
    {
        // An array doesn't have precisely three numbers (coordinates)
        $this->expectException(Exception\BadDataException::class);
        ClampedCubicSpline::validateSpline([[0,0,1], [1,2,3], [2,2]]);
    }

    public function testNotEnoughArraysException()
    {
        // There are not enough arrays in the input
        $this->expectException(Exception\BadDataException::class);
        ClampedCubicSpline::validateSpline([[0,0,1]]);
    }

    public function testNotAFunctionException()
    {
        // Two arrays share the same first number (x-component)
        $this->expectException(Exception\BadDataException::class);
        ClampedCubicSpline::validateSpline([[0,0,1], [0,5,0], [1,1,3]]);
    }
}
