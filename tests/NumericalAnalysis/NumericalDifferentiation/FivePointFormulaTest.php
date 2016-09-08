<?php
namespace Math\NumericalAnalysis\NumericalDifferentiation;

class FivePointFormulaTest extends \PHPUnit_Framework_TestCase
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
}
