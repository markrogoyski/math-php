<?php
namespace MathPHP\Tests\NumericalAnalysis\NumericalIntegration;

use MathPHP\NumericalAnalysis\NumericalIntegration\BoolesRule;

class BoolesRuleTest extends \PHPUnit\Framework\TestCase
{
    public function testapproximatePolynomial()
    {
        // f(x)                            = x³ + 2x + 1
        // Antiderivative F(x)             = (1/4)x⁴ + x² + x
        // Indefinite integral over [0, 4] = F(4) - F(0) = 84

        $expected = 84;

        // h           denotes the size of subintervals, or equivalently, the
        //                 distance between two points
        // ζ₁, ζ₂, ... denotes the max of the fourth derivative of f(x) on
        //                 interval 1, 2, ...
        // f'(x)    = 3x² + 2
        // f''(x)   = 6x
        // f'''(x)  = 6
        // f⁽⁴⁾(x)  = 0
        // f⁽⁵⁾(x)  = 0
        // f⁽⁶⁾(x)  = 0
        // Error    = O(h^5 * f⁽⁶⁾(x)) = 0

        $tol = 0;

        // Approximate with [0, 1], [1, 4], [2, 13], [3, 34], [4, 73]
        $x = BoolesRule::approximate([[0, 1], [1, 4], [2, 13], [3, 34], [4, 73]]);
        $this->assertEquals($expected, $x, '', $tol);

        // Same test as above but with points not sorted to test sorting works
        $x = BoolesRule::approximate([[0, 1], [3, 34], [2, 13], [1, 4], [4, 73]]);
        $this->assertEquals($expected, $x, '', $tol);

        // Similar test to above (same function, number of points, tolerance) but
        // with a callback function to make sure this type of input is compatible
        $func = function ($x) {
            return $x**3 + 2 * $x + 1;
        };
        $start = 0;
        $end   = 4;
        $n     = 5;
        $x     = BoolesRule::approximate($func, $start, $end, $n);
        $this->assertEquals($expected, $x, '', $tol);
    }

    public function testSubintervalsNotFactorFour()
    {
        // The number of subintervals is not a factor of four, or
        // equivalently, the number of points minus one is not a factor of four
        $this->expectException('\Exception');
        BoolesRule::approximate([[0,0], [4,4], [2,2], [6,6], [8,8], [10, 10]]);
    }

    public function testNonConstantSpacingException()
    {
        // There is not constant spacing between points
        $this->expectException('\Exception');
        BoolesRule::approximate([[0,0], [3,3], [2,2], [4,4], [5, 5]]);
    }
}
