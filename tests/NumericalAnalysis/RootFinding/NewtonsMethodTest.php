<?php

namespace MathPHP\Tests\NumericalAnalysis\RootFinding;

use MathPHP\Functions\Polynomial;
use MathPHP\NumericalAnalysis\RootFinding\NewtonsMethod;

class NewtonsMethodTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test   Solve f(x) = x⁴ + 8x³ -13x² -92x + 96
     *         Polynomial has 4 roots: 3, 1, -8 and -4
     *         Uses \Closure object
     * @throws \Exception
     */
    public function testSolvePolynomialWithFourRootsUsingClosure()
    {
        // Given
        $func = function ($x) {
            return $x ** 4 + 8 * $x ** 3 - 13 * $x ** 2 - 92 * $x + 96;
        };
        $args     = [-4.1];
        $target   = 0;
        $position = 0;
        $tol      = 0.00001;

        // When solving for f(x) = 0 where x is -4
        $expected = -4;
        $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);
        // Then
        $this->assertEquals($expected, $x, '', $tol);

        // When solving for f(x) = 0 where x is -8
        $args   = [-8.4];
        $expected = -8;
        $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);
        // Then
        $this->assertEquals($expected, $x, '', $tol);

        // When solving for f(x) = 0 where x is 3
        $args   = [3.5];
        $expected = 3;
        $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);
        // Then
        $this->assertEquals($expected, $x, '', $tol);

        // When solving f(x) = 0 where x is 1
        $args   = [.3];
        $expected = 1;
        $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);
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
        // Given
        $polynomial = new Polynomial([1, 8, -13, -92, 96]);
        $args       = [-4.1];
        $target     = 0;
        $position   = 0;
        $tol        = 0.00001;

        // When solving for f(x) = 0 where x is -4
        $expected = -4;
        $x = NewtonsMethod::solve($polynomial, $args, $target, $tol, $position);
        // Then
        $this->assertEquals($expected, $x, '', $tol);

        // When solving for f(x) = 0 where x is -8
        $args   = [-8.4];
        $expected = -8;
        $x = NewtonsMethod::solve($polynomial, $args, $target, $tol, $position);
        // Then
        $this->assertEquals($expected, $x, '', $tol);

        // When solving for f(x) = 0 where x is 3
        $args   = [3.5];
        $expected = 3;
        $x = NewtonsMethod::solve($polynomial, $args, $target, $tol, $position);
        // Then
        $this->assertEquals($expected, $x, '', $tol);

        // When solving f(x) = 0 where x is 1
        $args   = [.3];
        $expected = 1;
        $x = NewtonsMethod::solve($polynomial, $args, $target, $tol, $position);
        // Then
        $this->assertEquals($expected, $x, '', $tol);
    }

    /**
     * @test   Solve f(x) = x³ - x + 1
     *         Polynomial has a root of approximately -1.324717
     * @throws \Exception
     */
    public function testXCubedSubtractXPlusOne()
    {
        // Given
        $func = function ($x) {
            return $x ** 3 - $x + 1;
        };
        $expected = -1.324717;
        $args     = [-1];
        $target   = 0;
        $position = 0;
        $tol      = 0.00001;

        // When
        $root = NewtonsMethod::solve($func, $args, $target, $tol, $position);

        // Then
        $this->assertEquals($expected, $root, '', $tol);
    }

    /**
     * @test   Solve f(x) = x² - 5
     *         Polynomial has a root of √5
     * @throws \Exception
     */
    public function testXSquaredSubtractFive()
    {
        // Given
        $func = function ($x) {
            return $x ** 2 - 5;
        };
        $expected = sqrt(5);
        $args     = [2];
        $target   = 0;
        $position = 0;
        $tol      = 0.00001;

        // When
        $root = NewtonsMethod::solve($func, $args, $target, $tol, $position);

        // Then
        $this->assertEquals($expected, $root, '', $tol);
    }

    /**
     * @test   Solve cos(x) - 2x
     *         Has a root of approximately 0.450183
     * @throws \Exception
     */
    public function testCosXSubtractTwoX()
    {
        // Given
        $func = function ($x) {
            return cos($x) - 2 * $x;
        };
        $expected = 0.450183;
        $args     = [0];
        $target   = 0;
        $position = 0;
        $tol      = 0.00001;

        // When
        $root = NewtonsMethod::solve($func, $args, $target, $tol, $position);

        // Then
        $this->assertEquals($expected, $root, '', $tol);
    }

    /**
     * @test   Solve cos(x) = x
     *         Has a root of approximately 0.7390851332
     * @throws \Exception
     */
    public function testCosXEqualsX()
    {
        // Given
        $func = function ($x) {
            return cos($x);
        };
        $x        = 0.7390851332;
        $args     = [0.6];
        $target   = $x;
        $position = 0;
        $tol      = 0.00001;

        // When
        $root = NewtonsMethod::solve($func, $args, $target, $tol, $position);

        // Then
        $this->assertEquals($x, $root, '', $tol);
    }

    /**
     * @test   Solve with negative tolerance
     * @throws \Exception
     */
    public function testNewtonsMethodExceptionNegativeTolerance()
    {
        // Given
        $func = function ($x) {
            return $x ** 4 + 8 * $x ** 3 - 13 * $x ** 2 - 92 * $x + 96;
        };
        $args     = [-4.1];
        $target   = 0;
        $position = 0;
        $tol      = -0.00001;

        // Then
        $this->expectException('\Exception');

        // When
        $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);
    }

    /**
     * @test   Solve with near zero slope
     * @throws \Exception
     */
    public function testNewtonsMethodNearZeroSlopeNAN()
    {
        // Given
        $func = function ($x) {
            return $x / $x;
        };
        $args     = [0.1];
        $target   = 0;
        $position = 0;
        $tol      = 0.00001;

        // When
        $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);

        // Then
        $this->assertNan($x);
    }

    /**
     * @test   Solve with no real solutions
     * @throws \Exception
     */
    public function testNewtonsMethodNoRealSolutionsNAN()
    {
        // Given
        $func = function ($x) {
            return $x ** 2 + 3 * $x + 3;
        };
        $args     = [0.1];
        $target   = 0;
        $position = 0;
        $tol      = 0.00001;

        // When
        $x = NewtonsMethod::solve($func, $args, $target, $tol, $position);

        // Then
        $this->assertNan($x);
    }
}
