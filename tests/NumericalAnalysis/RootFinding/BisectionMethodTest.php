<?php
namespace MathPHP\Tests\NumericalAnalysis\RootFinding;

use MathPHP\Functions\Polynomial;
use MathPHP\NumericalAnalysis\RootFinding\BisectionMethod;
use MathPHP\Exception;

class BisectionMethodTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test   Solve f(x) = x⁴ + 8x³ -13x² -92x + 96
     *         Polynomial has 4 roots: 3, 1, -8 and -4
     *         Uses \Closure object
     * @throws \Exception
     */
    public function testSolvePolynomialWithFourRootsUsingClosure()
    {
        // Given f(x) = x⁴ + 8x³ -13x² -92x + 96
        // This polynomial has 4 roots: 3, 1 ,-8 and -4
        $func = function ($x) {
            return $x ** 4 + 8 * $x ** 3 - 13 * $x ** 2 - 92 * $x + 96;
        };
        $tol = 0.00001;

        // When solving for f(x) = 0 where x is -4
        $a        = -7;
        $b        = 0;
        $expected = -4;
        $x = BisectionMethod::solve($func, $a, $b, $tol);
        // Then
        $this->assertEquals($expected, $x, '', $tol);

        // When solving for f(x) = 0 where x is -8
        $a        = -10;
        $b        = -5;
        $expected = -8;
        $x = BisectionMethod::solve($func, $a, $b, $tol);
        // Then
        $this->assertEquals($expected, $x, '', $tol);

        // When solving for f(x) = 0 where x is 3
        $a        = 2;
        $b        = 5;
        $expected = 3;
        $x = BisectionMethod::solve($func, $a, $b, $tol);
        // Then
        $this->assertEquals($expected, $x, '', $tol);

        // When solving for f(x) = 0 where x is 1
        $a        = 0;
        $b        = 2;
        $expected = 1;
        $x = BisectionMethod::solve($func, $a, $b, $tol);
        // Then
        $this->assertEquals($expected, $x, '', $tol);

        // When solving for f(x) = 0 where x is 1
        // Switch a and b and test that they get reversed properly
        $b        = 0;
        $a        = 2;
        $expected = 1;
        $x = BisectionMethod::solve($func, $a, $b, $tol);
        // Then
        $this->assertEquals($expected, $x, '', $tol);
    }

    /**
     * @test   Solve f(x) = x⁴ + 8x³ -13x² -92x + 96
     *         Polynomial has 4 roots: 3, 1, -8 and -4
     *         Uses Polynomial object
     * @throws \Exception
     */
    public function testSolvePolynomialWithFourRootsUsingPolynomial()
    {
        // Given f(x) = x⁴ + 8x³ -13x² -92x + 96
        // This polynomial has 4 roots: 3, 1 ,-8 and -4
        $polynomial = new Polynomial([1, 8, -13, -92, 96]);
        $tol        = 0.00001;

        // When solving for f(x) = 0 where x is -4
        $a        = -7;
        $b        = 0;
        $expected = -4;
        $x = BisectionMethod::solve($polynomial, $a, $b, $tol);
        // Then
        $this->assertEquals($expected, $x, '', $tol);

        // When solving for f(x) = 0 where x is -8
        $a        = -10;
        $b        = -5;
        $expected = -8;
        $x = BisectionMethod::solve($polynomial, $a, $b, $tol);
        // Then
        $this->assertEquals($expected, $x, '', $tol);

        // When solving for f(x) = 0 where x is 3
        $a        = 2;
        $b        = 5;
        $expected = 3;
        $x = BisectionMethod::solve($polynomial, $a, $b, $tol);
        // Then
        $this->assertEquals($expected, $x, '', $tol);

        // When solving for f(x) = 0 where x is 1
        $a        = 0;
        $b        = 2;
        $expected = 1;
        $x = BisectionMethod::solve($polynomial, $a, $b, $tol);
        // Then
        $this->assertEquals($expected, $x, '', $tol);

        // When solving for f(x) = 0 where x is 1
        // Switch a and b and test that they get reversed properly
        $b        = 0;
        $a        = 2;
        $expected = 1;
        $x = BisectionMethod::solve($polynomial, $a, $b, $tol);
        // Then
        $this->assertEquals($expected, $x, '', $tol);
    }

    /**
     * @test   Solve more polynomials
     * @throws \Exception
     * Example from https://en.wikipedia.org/wiki/Bisection_method
     */
    public function testSolveXCubedSubtractXSubtractTwo()
    {
        // Given f(x) = x³ - x - 2
        // Find the root 1.521
        $func = function ($x) {
            return $x ** 3 - $x - 2;
        };
        $tol = 0.001;

        // When solving for f(x) = 0 where x is about 1.521
        $a        = 1;
        $b        = 2;
        $expected = 1.521;
        $x = BisectionMethod::solve($func, $a, $b, $tol);

        // Then
        $this->assertEquals($expected, $x, '', $tol);
    }

    /**
     * @test   Solve more polynomials
     * @throws \Exception
     * Example from https://ece.uwaterloo.ca/~dwharder/NumericalAnalysis/10RootFinding/bisection/examples.html
     */
    public function testSolveXSquaredSubtractThree()
    {
        // Given f(x) = x² - 3
        // Find the root 1.7344
        $func = function ($x) {
            return $x ** 2 - 3;
        };
        $tol = 0.01;

        // When solving for f(x) = 0 where x is about 1.7344
        $a        = 1;
        $b        = 2;
        $expected = 1.7344;
        $x = BisectionMethod::solve($func, $a, $b, $tol);

        // Then
        $this->assertEquals($expected, $x, '', $tol);
    }

    /**
     * @test   Solve more polynomials
     * @throws \Exception
     * Example from https://ece.uwaterloo.ca/~dwharder/NumericalAnalysis/10RootFinding/bisection/examples.html
     */
    public function testSolveEToNegativeXTimesSomeStuff()
    {
        // Given f(x) = e⁻ˣ (3.2sin(x) - 0.5cos(x))
        // Find the root 3.2968
        $func = function ($x) {
            return exp(-$x) * ((3.2 * sin($x)) - (0.5 * cos($x)));
        };
        $tol = 0.0001;

        // When solving for f(x) = 0 where x is about 3.2968
        $a        = 3;
        $b        = 4;
        $expected = 3.2968;
        $x = BisectionMethod::solve($func, $a, $b, $tol);

        // Then
        $this->assertEquals($expected, $x, '', $tol);
    }

    /**
     * @test   Solve with negative tolerance
     * @throws \Exception
     */
    public function testBisectionMethodExceptionNegativeTolerance()
    {
        // Given
        $func = function ($x) {
            return $x ** 4 + 8 * $x ** 3 - 13 * $x ** 2 - 92 * $x + 96;
        };
        $tol = -0.00001;
        $a   = 0;
        $b   = 2;

        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        $x = BisectionMethod::solve($func, $a, $b, $tol);
    }

    /**
     * @test   Solve with zero interval
     * @throws \Exception
     */
    public function testBisectionMethodExceptionZeroInterval()
    {
        // Given
        $func = function ($x) {
            return $x ** 4 + 8 * $x ** 3 - 13 * $x ** 2 - 92 * $x + 96;
        };
        $tol = 0.00001;
        $a   = 2;
        $b   = 2;

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        $x = BisectionMethod::solve($func, $a, $b, $tol);
    }

    /**
     * @test   Solve with same signs
     * @throws \Exception
     */
    public function testBisectionMethodExceptionSameSigns()
    {
        $func = function ($x) {
            return $x + 96;
        };
        $tol = 0.00001;
        $a   = 0;
        $b   = 1;

        $this->expectException(Exception\BadDataException::class);
        $x = BisectionMethod::solve($func, $a, $b, $tol);
    }
}
