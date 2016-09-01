<?php
namespace Math\NumericalAnalysis\RootFinding;

class BisectionMethodTest extends \PHPUnit_Framework_TestCase
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
        $a        = -7;
        $b        = 0;
        $expected = -4;
        $x = BisectionMethod::solve($func, $a, $b, $tol);
        $this->assertEquals($expected, $x, '', $tol);

        // Solve for f(x) = 0 where x is -8
        $a        = -10;
        $b        = -5;
        $expected = -8;
        $x = BisectionMethod::solve($func, $a, $b, $tol);
        $this->assertEquals($expected, $x, '', $tol);

        // Solve for f(x) = 0 where x is 3
        $a        = 2;
        $b        = 5;
        $expected = 3;
        $x = BisectionMethod::solve($func, $a, $b, $tol);
        $this->assertEquals($expected, $x, '', $tol);

        // Solve for f(x) = 0 where x is 1
        $a        = 0;
        $b        = 2;
        $expected = 1;
        $x = BisectionMethod::solve($func, $a, $b, $tol);
        $this->assertEquals($expected, $x, '', $tol);

        // Solve for f(x) = 0 where x is 1
        // Switch a and b and test that they get reversed properly
        $a        = 0;
        $b        = 2;
        $expected = 1;
        $x = BisectionMethod::solve($func, $a, $b, $tol);
        $this->assertEquals($expected, $x, '', $tol);
    }

    public function testBisectionMethodExceptionNegativeTolerance()
    {
        $func = function ($x) {
            return $x**4 + 8 * $x**3 - 13 * $x**2 - 92 * $x + 96;
        };

        $tol      = -0.00001;
        $a        = 0;
        $b        = 2;
        $expected = 1;

        $this->setExpectedException('\Exception');
        $x = BisectionMethod::solve($func, $a, $b, $tol);
    }

    public function testBisectionMethodExceptionZeroInterval()
    {
        $func = function ($x) {
            return $x**4 + 8 * $x**3 - 13 * $x**2 - 92 * $x + 96;
        };

        $tol      = 0.00001;
        $a        = 2;
        $b        = 2;
        $expected = 1;

        $this->setExpectedException('\Exception');
        $x = BisectionMethod::solve($func, $a, $b, $tol);
    }

    public function testBisectionMethodExceptionSameSigns()
    {
        $func = function ($x) {
            return $x + 96;
        };

        $tol      = 0.00001;
        $a        = 0;
        $b        = 1;
        $expected = 1;

        $this->setExpectedException('\Exception');
        $x = BisectionMethod::solve($func, $a, $b, $tol);
    }
}
