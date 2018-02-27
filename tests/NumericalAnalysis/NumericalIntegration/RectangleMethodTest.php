<?php
namespace MathPHP\Tests\NumericalAnalysis\NumericalIntegration;

use MathPHP\NumericalAnalysis\NumericalIntegration\RectangleMethod;

class RectangleMethodTest extends \PHPUnit\Framework\TestCase
{
    public function testSolvePolynomial()
    {
        // f(x)                            = -x² + 2x + 1
        // Antiderivative F(x)             = -(1/3)x³ + x² + x
        // Indefinite integral over [0, 3] = F(3) - F(0) = 3

        $expected = 3;

        // h₁, h₂, ... denotes the size on interval 1, 2, ...
        // ζ₁, ζ₂, ... denotes the max of the second derivative of f(x) on
        //             interval 1, 2, ...
        // f'(x)  = -2x + 2
        // f''(x) = -2
        // ζ      = |f''(x)| = 2
        // Error  = Sum(ζ₁h₁³ + ζ₂h₂³ + ...) = 2 * Sum(h₁³ + h₂³ + ...)

        // Approximate with endpoints: (0, 1) and (3, 16)
        // Error = 2 * ((3 - 0)²) = 18
        $tol = 18;
        $x = RectangleMethod::approximate([[0, 1], [3, -2]]);
        $this->assertEquals($expected, $x, '', $tol);

        // Approximate with endpoints and one interior point: (0, 1), (1, 4),
        // and (3, 16)
        // Error = 2 * ((1 - 0)² + (3 - 1)²) = 10
        $tol = 10;
        $x = RectangleMethod::approximate([[0, 1], [1, 2], [3, -2]]);
        $this->assertEquals($expected, $x, '', $tol);

        // Approximate with endpoints and two interior points: (0, 1), (1, 4),
        // (2, 9), and (3, 16)
        // Error = 2 * ((1 - 0)² + (2 - 1)² + (3 - 2)²) = 6
        $tol = 6;
        $x = RectangleMethod::approximate([[0, 1], [1, 2], [2, 1], [3, -2]]);
        $this->assertEquals($expected, $x, '', $tol);

        // Same test as above but with points not sorted to test sorting works
        $tol = 6;
        $x = RectangleMethod::approximate([[1, 2], [3, -2], [0, 1], [2, 1]]);
        $this->assertEquals($expected, $x, '', $tol);

        // Similar test to above (same function, number of points, tolerance) but
        // with a callback function to make sure this type of input is compatible
        $func = function ($x) {
            return -$x**2 + 2 * $x + 1;
        };
        $start = 0;
        $end   = 3;
        $n     = 4;
        $tol   = 6;
        $x     = RectangleMethod::approximate($func, $start, $end, $n);
        $this->assertEquals($expected, $x, '', $tol);
    }
}
