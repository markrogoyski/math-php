<?php
namespace MathPHP\Tests\NumericalAnalysis\RootFinding;

use MathPHP\NumericalAnalysis\RootFinding\SecantMethod;

class SecantMethodTest extends \PHPUnit\Framework\TestCase
{
    public function testSolve()
    {
        // f(x) = x⁴ + 8x³ -13x² -92x + 96
        // This polynomial has 4 roots: 3,1,-8 and -4
        $func = function ($x) {
            return $x**4 + 8 * $x**3 - 13 * $x**2 - 92 * $x + 96;
        };
        $tol      = 0.00001;

        // Solve for f(x) = 0 where x is -4
        $expected = -4;
        $p₀       = -5;
        $p₁       = -2;
        $x = SecantMethod::solve($func, $p₀, $p₁, $tol);
        $this->assertEquals($expected, $x, '', $tol);

        // Solve for f(x) = 0 where x is -8
        $expected = -8;
        $p₀       = -10;
        $p₁       = -7;
        $x = SecantMethod::solve($func, $p₀, $p₁, $tol);
        $this->assertEquals($expected, $x, '', $tol);

        // Solve for f(x) = 0 where x is 3
        $expected = 3;
        $p₀       = 2;
        $p₁       = 5;
        $x = SecantMethod::solve($func, $p₀, $p₁, $tol);
        $this->assertEquals($expected, $x, '', $tol);

        // Solve for f(x) = 0 where x is 1
        $expected = 1;
        $p₀       = -1;
        $p₁       = 2;
        $x = SecantMethod::solve($func, $p₀, $p₁, $tol);
        $this->assertEquals($expected, $x, '', $tol);

        // Switch p₀ and p₁ and test that they get reversed properly
        // Solve for f(x) = 0 where x is 1
        $expected = 1;
        $p₁       = -1;
        $p₀       = 2;
        $x = SecantMethod::solve($func, $p₀, $p₁, $tol);
        $this->assertEquals($expected, $x, '', $tol);
    }

    public function testExceptionNegativeTolerance()
    {
        $func = function ($x) {
            return $x**4 + 8 * $x**3 - 13 * $x**2 - 92 * $x + 96;
        };

        $tol      = -0.00001;
        $expected = 1;
        $p₀       = -1;
        $p₁       = 2;

        $this->expectException('\Exception');
        $x = SecantMethod::solve($func, $p₀, $p₁, $tol);
    }

    public function testExceptionZeroInterval()
    {
        $func = function ($x) {
            return $x**4 + 8 * $x**3 - 13 * $x**2 - 92 * $x + 96;
        };

        $tol      = 0.00001;
        $expected = 1;
        $p₀       = 1;
        $p₁       = 1;

        $this->expectException('\Exception');
        $x = SecantMethod::solve($func, $p₀, $p₁, $tol);
    }
}
