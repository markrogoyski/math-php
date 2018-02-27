<?php
namespace MathPHP\Tests\NumericalAnalysis\Interpolation;

use MathPHP\NumericalAnalysis\Interpolation\NevillesMethod;

class NevillesMethodTest extends \PHPUnit\Framework\TestCase
{
    public function testPolynomialAgrees()
    {
        $points = [[0, 0], [1, 5], [3, 2], [7, 10], [10, -4]];

        $roundoff = 0.0000001; // round off error

        // Assure p(0) = 0 agrees with input [0, 0]
        $expected = 0;
        $target = 0;
        $actual = NevillesMethod::interpolate($target, $points);
        $this->assertEquals($expected, $actual, '', + $roundoff);

        // Assure p(1) = 5 agrees with input [1, 5]
        $expected = 5;
        $target = 1;
        $actual = NevillesMethod::interpolate($target, $points);
        $this->assertEquals($expected, $actual, '', + $roundoff);

        // Assure p(3) = 2 agrees with input [3, 2]
        $expected = 2;
        $target = 3;
        $actual = NevillesMethod::interpolate($target, $points);
        $this->assertEquals($expected, $actual, '', + $roundoff);

        // Assure p(7) = 10 agrees with input [7, 10]
        $expected = 10;
        $target = 7;
        $actual = NevillesMethod::interpolate($target, $points);
        $this->assertEquals($expected, $actual, '', + $roundoff);

        // Assure p(10) = -4 agrees with input [10, -4]
        $expected = -4;
        $target = 10;
        $actual = NevillesMethod::interpolate($target, $points);
        $this->assertEquals($expected, $actual, '', + $roundoff);
    }

    public function testSolve()
    {
        // f(x) = x⁴ + 8x³ -13x² -92x + 96
        $f = function ($x) {
            return $x**4 + 8 * $x**3 - 13 * $x**2 - 92 * $x + 96;
        };

        // Given n points, the error in Nevilles Method (Lagrange polynomials) is proportional
        // to the max value of the nth derivative. Thus, if we if interpolate n at
        // 6 points, the 5th derivative of our original function f(x) = 0, and so
        // our resulting polynomial will have no error.

        $a = 0;
        $b = 10;
        $n = 5;

        $roundoff = 0.0000001; // round off error

        // Check that p(x) agrees with f(x) at x = 0
        $target = 0;
        $expected = $f($target);
        $actual = NevillesMethod::interpolate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $actual, '', + $roundoff);

        // Check that p(x) agrees with f(x) at x = 2
        $target = 2;
        $expected = $f($target);
        $actual = NevillesMethod::interpolate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $actual, '', + $roundoff);

        // Check that p(x) agrees with f(x) at x = 4
        $target = 4;
        $expected = $f($target);
        $actual = NevillesMethod::interpolate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $actual, '', + $roundoff);

        // Check that p(x) agrees with f(x) at x = 6
        $target = 6;
        $expected = $f($target);
        $actual = NevillesMethod::interpolate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $actual, '', + $roundoff);

        // Check that p(x) agrees with f(x) at x = 8
        $target = 8;
        $expected = $f($target);
        $actual = NevillesMethod::interpolate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $actual, '', + $roundoff);

        // Check that p(x) agrees with f(x) at x = 10
        $target = 10;
        $expected = $f($target);
        $actual = NevillesMethod::interpolate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $actual, '', + $roundoff);

        // Check that p(x) agrees with f(x) at x = -90
        $target = -90;
        $expected = $f($target);
        $actual = NevillesMethod::interpolate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $actual, '', + $roundoff);
    }

    public function testSolveNonzeroError()
    {
        // f(x) = x⁴ + 8x³ -13x² -92x + 96
        $f = function ($x) {
            return $x**4 + 8 * $x**3 - 13 * $x**2 - 92 * $x + 96;
        };

        // The error is bounded by:
        // |f(x)-p(x)| = tol <= (max f⁽ⁿ⁺¹⁾(x))*(x-x₀)*...*(x-xn)/(n+1)!

        // f'(x)  = 4x³ +24x² -26x - 92
        // f''(x) = 12x² - 48x - 26
        // f'''(x) = 24x - 48
        // f⁽⁴⁾(x) = 24

        $a = 0;
        $b = 9;
        $n = 4;

        // So, tol <= 24*(x-x₀)*...*(x-xn)/(4!) = (x-x₀)*...*(x-xn) where

        $x₀ = 0;
        $x₁ = 3;
        $x₂ = 6;
        $x₃ = 9;

        $roundoff = 0.0000001; // round off error

        // Check that p(x) agrees with f(x) at x = 0

        $target = 0;
        $tol = ($target - $x₀)*($target - $x₁)*($target - $x₂)*($target - $x₃);
        $expected = $f($target);
        $x = NevillesMethod::interpolate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 2
        $target = 2;
        $tol = abs(($target - $x₀)*($target - $x₁)*($target - $x₂)*($target - $x₃));
        $expected = $f($target);
        $x = NevillesMethod::interpolate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 4
        $target = 4;
        $tol = abs(($target - $x₀)*($target - $x₁)*($target - $x₂)*($target - $x₃));
        $expected = $f($target);
        $x = NevillesMethod::interpolate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 6
        $target = 6;
        $tol = abs(($target - $x₀)*($target - $x₁)*($target - $x₂)*($target - $x₃));
        $expected = $f($target);
        $x = NevillesMethod::interpolate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 8
        $target = 8;
        $tol = abs(($target - $x₀)*($target - $x₁)*($target - $x₂)*($target - $x₃));
        $expected = $f($target);
        $x = NevillesMethod::interpolate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 10
        $target = 10;
        $tol = abs(($target - $x₀)*($target - $x₁)*($target - $x₂)*($target - $x₃));
        $expected = $f($target);
        $x = NevillesMethod::interpolate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = -99
        $target = -99;
        $tol = abs(($target - $x₀)*($target - $x₁)*($target - $x₂)*($target - $x₃));
        $expected = $f($target);
        $x = NevillesMethod::interpolate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);
    }
}
