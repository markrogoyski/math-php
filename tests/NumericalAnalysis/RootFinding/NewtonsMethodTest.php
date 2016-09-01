<?php
namespace Math\NumericalAnalysis\RootFinding;

class NewtonsMethodTest extends \PHPUnit_Framework_TestCase
{
    public function testSolve()
    {
        // f(x) = x⁴ + 8x³ -13x² -92x + 96
        // This polynomial has 4 roots: 3,1,-8 and -4
        $func = function ($x) {
            return $x**4 + 8 * $x**3 - 13 * $x**2 - 92 * $x + 96;
        };

        $args     = [-4.1];
        $target   = 0;
        $position = 0;
        $tol      = 0.00001;

        // Solve for f(x) = 0 where x is -4
        $expected = -4;
        $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);
        $this->assertEquals($expected, $x, '', $tol);

        // Solve for f(x) = 0 where x is -8
        $args   = [-8.4];
        $expected = -8;
        $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);
        $this->assertEquals($expected, $x, '', $tol);

        // Solve for f(x) = 0 where x is 3
        $args   = [3.5];
        $expected = 3;
        $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);
        $this->assertEquals($expected, $x, '', $tol);

        // Solve for f(x) = 0 where x is 1
        $args   = [.3];
        $expected = 1;
        $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);
        $this->assertEquals($expected, $x, '', $tol);
    }

    public function testNewtonsMethodExceptionNegativeTolerance()
    {
        $func = function ($x) {
            return $x**4 + 8 * $x**3 - 13 * $x**2 - 92 * $x + 96;
        };

        $args     = [-4.1];
        $target   = 0;
        $position = 0;
        $tol      = -0.00001;

        $this->setExpectedException('\Exception');
        $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);
    }
}
