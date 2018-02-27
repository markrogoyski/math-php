<?php
namespace MathPHP\Tests\NumericalAnalysis\RootFinding;

use MathPHP\NumericalAnalysis\RootFinding\FixedPointIteration;
use MathPHP\Exception;

class FixedPointIterationTest extends \PHPUnit\Framework\TestCase
{
    public function testSolve()
    {
        // f(x) = x⁴ + 8x³ -13x² -92x + 96 = 0
        // Note that f(x) has a root at 1
        // Rewrite f(x) = 0 as (x⁴ + 8x³ -13x² + 96)/92 = x
        // Thus, g(x) = (x⁴ + 8x³ -13x² + 96)/92
        $func = function ($x) {
            return ($x**4 + 8 * $x**3 - 13 * $x**2 + 96)/92;
        };

        // g(0)  = 96/92, where 0 < 96/92 < 2
        // g(2)  = 124/92, where 0 < 124/92 < 2
        // g'(x) = (4x³ + 24x² - 26x)/92 is continuous
        // g'(x) has no root on [0, 2]. Thus, the derivative of g(x) does not
        // change direction on [0, 2]. So, if g(2) > g(0), then 0 < g(x) < 2
        // for all x in [0, 2]. So, there is a root in [0, 2]

        // Solve for f(x) = 0 where x is 1

        $tol      = 0.00001;
        $a        = 0;
        $b        = 2;
        $p        = 0;
        $expected = 1;
        $x = FixedPointIteration::solve($func, $a, $b, $p, $tol);
        $this->assertEquals($expected, $x, '', $tol);

        // Switch a and b and test that they get reversed properly
        $tol      = 0.00001;
        $b        = 0;
        $a        = 2;
        $p        = 0;
        $expected = 1;
        $x = FixedPointIteration::solve($func, $a, $b, $p, $tol);
        $this->assertEquals($expected, $x, '', $tol);
    }

    public function testFixedPointIterationExceptionNegativeTolerance()
    {
        $func = function ($x) {
            return ($x**4 + 8 * $x**3 - 13 * $x**2 + 96)/92;
        };

        $tol      = -0.00001;
        $a        = 0;
        $b        = 3;
        $p        = 0;

        $this->expectException(Exception\OutOfBoundsException::class);
        $x = FixedPointIteration::solve($func, $a, $b, $p, $tol);
    }

    public function testFixedPointIterationExceptionZeroInterval()
    {
        $func = function ($x) {
            return ($x**4 + 8 * $x**3 - 13 * $x**2 + 96)/92;
        };

        $tol      = 0.00001;
        $a        = 3;
        $b        = 3;
        $p        = 3;

        $this->expectException(Exception\BadDataException::class);
        $x = FixedPointIteration::solve($func, $a, $b, $p, $tol);
    }

    public function testFixedPointIterationExceptionGuessNotInInterval()
    {
        $func = function ($x) {
            return ($x**4 + 8 * $x**3 - 13 * $x**2 + 96)/92;
        };

        $tol      = 0.00001;
        $a        = 0;
        $b        = 3;
        $p        = -1;

        $this->expectException(Exception\OutOfBoundsException::class);
        $x = FixedPointIteration::solve($func, $a, $b, $p, $tol);
    }
}
