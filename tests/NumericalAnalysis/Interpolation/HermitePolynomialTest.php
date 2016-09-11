<?php
namespace Math\NumericalAnalysis\Interpolation;

use Math\Functions\Polynomial;
use Math\Probability\Combinatorics;

class HermitePolynomialTest extends \PHPUnit_Framework_TestCase
{
    public function testPolynomialAgrees()
    {
        $points = [[0, 0, 1], [1, 5, 4], [3, 2, -1], [7, 10, 3], [10, -4, 6]];

        $p = HermitePolynomial::interpolate($points);

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

    public function testSolve()
    {
        // f(x) = x⁴ + 8x³ -13x² -92x + 96
        $f = function ($x) {
            return $x**4 + 8 * $x**3 - 13 * $x**2 - 92 * $x + 96;
        };

        // Given n points, the error in the Hermite Polynomials is proportional
        // to the max value of the (2n)-th derivative. Thus, if we if interpolate n at
        // 3 points, the 6th derivative of our original function f(x) = 0, and so
        // our resulting polynomial will have no error.

        $a = 0;
        $b = 10;
        $n = 3;

        $p = HermitePolynomial::interpolate($f, $a, $b, $n);

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

    public function testSolveNonzeroError()
    {
        $f = new Polynomial([1, -4, 2, 8, 4, -3, 8, 10]);

        // The error is bounded by:
        // |f(x)-p(x)| = tol <= (max f⁽²ⁿ⁾(x))*(x-x₀)²*...*(x-xn)²/(2n)!

        $a = 0;
        $b = 9;
        $n = 4;

        // So, tol <= (max f⁽⁸⁾(x))*(x-x₀)²*...*(x-xn)²/(8!)

        $f′   = $f->differentiate();
        $f′′  = $f′->differentiate();
        $f′′′ = $f′′->differentiate();
        $f⁽⁴⁾ = $f′′′->differentiate();
        $f⁽⁵⁾ = $f⁽⁴⁾->differentiate();
        $f⁽⁶⁾ = $f⁽⁵⁾->differentiate();
        $f⁽⁷⁾ = $f⁽⁶⁾->differentiate();
        $f⁽⁸⁾ = $f⁽⁷⁾->differentiate(); // f⁽⁸⁾ is constant

        $x₀ = 0;
        $x₁ = 3;
        $x₂ = 6;
        $x₃ = 9;

        $roundoff = 0.000001; // round off error

        $p = HermitePolynomial::interpolate($f, $a, $b, $n);

        // Check that p(x) agrees with f(x) at x = 0
        $target = 0;
        $tol = $f⁽⁸⁾($target) * ($target - $x₀)**2 * ($target - $x₁)**2 * ($target - $x₂)**2 * ($target - $x₃) / (Combinatorics::factorial(2*n));
        $expected = $f($target);
        $x = $p($target);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 2
        $target = 2;
        $tol = $f⁽⁸⁾($target) * ($target - $x₀)**2 * ($target - $x₁)**2 * ($target - $x₂)**2 * ($target - $x₃) / (Combinatorics::factorial(2*n));
        $expected = $f($target);
        $x = $p($target);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 4
        $target = 4;
        $tol = $f⁽⁸⁾($target) * ($target - $x₀)**2 * ($target - $x₁)**2 * ($target - $x₂)**2 * ($target - $x₃) / (Combinatorics::factorial(2*n));
        $expected = $f($target);
        $x = $p($target);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 6
        $target = 6;
        $tol = $f⁽⁸⁾($target) * ($target - $x₀)**2 * ($target - $x₁)**2 * ($target - $x₂)**2 * ($target - $x₃) / (Combinatorics::factorial(2*n));
        $expected = $f($target);
        $x = $p($target);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 8
        $target = 8;
        $tol = $f⁽⁸⁾($target) * ($target - $x₀)**2 * ($target - $x₁)**2 * ($target - $x₂)**2 * ($target - $x₃) / (Combinatorics::factorial(2*n));
        $expected = $f($target);
        $x = $p($target);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = 10
        $target = 10;
        $tol = $f⁽⁸⁾($target) * ($target - $x₀)**2 * ($target - $x₁)**2 * ($target - $x₂)**2 * ($target - $x₃) / (Combinatorics::factorial(2*n));
        $expected = $f($target);
        $x = $p($target);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);

        // Check that p(x) agrees with f(x) at x = -99
        $target = -99;
        $tol = $f⁽⁸⁾($target) * ($target - $x₀)**2 * ($target - $x₁)**2 * ($target - $x₂)**2 * ($target - $x₃) / (Combinatorics::factorial(2*n));
        $expected = $f($target);
        $x = $p($target);
        $this->assertEquals($expected, $x, '', $tol + $roundoff);
    }
}
