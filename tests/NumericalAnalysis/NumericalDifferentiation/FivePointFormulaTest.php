<?php
namespace MathPHP\Tests\NumericalAnalysis\NumericalDifferentiation;

use MathPHP\NumericalAnalysis\NumericalDifferentiation\FivePointFormula;

class FivePointFormulaTest extends \PHPUnit\Framework\TestCase
{
    public function testZeroError()
    {
        // f(x) = 13x² -92x + 96
        $f = function ($x) {
            return 13 * $x**2 - 92 * $x + 96;
        };

        /*
        *                                      h⁴
        * Error term for the Midpoint Formula: - f⁽⁵⁾(ζ₁)
        *                                      30
        *
        *     where ζ₁ lies between x₀ - 2h and x₀ + 2h
        *
        *                                      h⁴
        * Error term for the Endpoint Formula: - f⁽⁵⁾(ζ₀)
        *                                      5
        *
        *     where ζ₀ lies between x₀ and x₀ + 4h
        */

        // f'(x)   = 26x - 92
        // f''(x)  = 26
        // f⁽³⁾(x) = 0
        // ...
        // f⁽⁵⁾(x) = 0
        // Thus, our error is zero in both formulas for our function $f

        $f’ = function ($x) {
            return 26 * $x - 92;
        };

        $n = 5;
        $a = 0;
        $b = 4;

        // Check that the endpoint formula agrees with f'(x) at x = 0
        $target = 0;
        $expected = $f’($target);
        $actual = FivePointFormula::differentiate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $actual);

        // Check that the midpoint formula agrees with f'(x) at x = 2
        $target = 2;
        $expected = $f’($target);
        $actual = FivePointFormula::differentiate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $actual);

        // Check that the (backward) endpoint formula agrees with f'(x) at x = 4
        $target = 4;
        $expected = $f’($target);
        $actual = FivePointFormula::differentiate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $actual);
    }

    public function testNonzeroError()
    {
        // f(x) = x⁵ - 13x² -92x + 96
        $f = function ($x) {
            return $x**5 - 13 * $x**2 - 92 * $x + 96;
        };

        /*
        *                                      h⁴
        * Error term for the Midpoint Formula: - f⁽⁵⁾(ζ₁)
        *                                      30
        *
        *     where ζ₁ lies between x₀ - 2h and x₀ + 2h
        *
        *                                      h⁴
        * Error term for the Endpoint Formula: - f⁽⁵⁾(ζ₀)
        *                                      5
        *
        *     where ζ₀ lies between x₀ and x₀ + 4h
        */

        // f'(x)   = 5x⁴ - 26x - 92
        // f''(x)  = 20x³ - 26
        // f⁽³⁾(x) = 60x²
        // f⁽⁴⁾(x) = 120x
        // f⁽⁵⁾(x) = 120

        // Error in Midpoint Formula on [0,4] (where h=1) < 4
        // Error in Endpoint Formula on [0,4] (where h=1) < 24

        $f’ = function ($x) {
            return 5 * $x**4 - 26 * $x - 92;
        };

        $n = 5;
        $a = 0;
        $b = 4;

        // Check that the endpoint formula agrees with f'(x) at x = 0
        $target = 0;
        $tol = 24;
        $expected = $f’($target);
        $actual = FivePointFormula::differentiate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $actual, '', $tol);

        // Check that the midpoint formula agrees with f'(x) at x = 2
        $target = 2;
        $tol = 4;
        $expected = $f’($target);
        $actual = FivePointFormula::differentiate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $actual, '', $tol);

        // Check that the (backward) endpoint formula agrees with f'(x) at x = 4
        $target = 4;
        $tol = 24;
        $expected = $f’($target);
        $actual = FivePointFormula::differentiate($target, $f, $a, $b, $n);
        $this->assertEquals($expected, $actual, '', $tol);
    }

    public function testPointsZeroError()
    {
        // f(x) = 13x² -92x + 96
        $f = function ($x) {
            return 13 * $x**2 - 92 * $x + 96;
        };

        $points = [[0, $f(0)], [1, $f(1)], [2, $f(2)], [3, $f(3)], [4, $f(4)]];

        /*
        *                                      h²
        * Error term for the Midpoint Formula: - f⁽³⁾(ζ₁)
        *                                      6
        *
        *     where ζ₁ lies between x₀ - h and x₀ + h
        *
        *                                      h²
        * Error term for the Endpoint Formula: - f⁽³⁾(ζ₀)
        *                                      3
        *
        *     where ζ₀ lies between x₀ and x₀ + 2h
        */

        // f'(x)   = 26x - 92
        // f''(x)  = 26
        // f⁽³⁾(x) = 0
        // Thus, our error is zero in both formulas for our function $f

        $f’ = function ($x) {
            return 26 * $x - 92;
        };

        // Check that the endpoint formula agrees with f'(x) at x = 0
        $target = 0;
        $expected = $f’($target);
        $actual = FivePointFormula::differentiate($target, $points);
        $this->assertEquals($expected, $actual);

        // Check that the midpoint formula agrees with f'(x) at x = 2
        $target = 2;
        $expected = $f’($target);
        $actual = FivePointFormula::differentiate($target, $points);
        $this->assertEquals($expected, $actual);

        // Check that the (backward) endpoint formula agrees with f'(x) at x = 4
        $target = 4;
        $expected = $f’($target);
        $actual = FivePointFormula::differentiate($target, $points);
        $this->assertEquals($expected, $actual);
    }
}
