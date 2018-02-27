<?php
namespace MathPHP\Tests\NumericalAnalysis\NumericalIntegration;

use MathPHP\NumericalAnalysis\NumericalIntegration\SimpsonsThreeEighthsRule;

class SimpsonsThreeEighthsRuleTest extends \PHPUnit\Framework\TestCase
{
    public function testapproximatePolynomial()
    {
        // f(x)                            = x² + 2x + 1
        // Antiderivative F(x)             = (1/3)x³ + x² + x
        // Indefinite integral over [0, 3] = F(3) - F(0) = 21

        $expected = 21;

        // h           denotes the size of subintervals, or equivalently, the
        //                 distance between two points
        // ζ₁, ζ₂, ... denotes the max of the fourth derivative of f(x) on
        //                 interval 1, 2, ...
        // f'(x)    = 2x + 2
        // f''(x)   = 2
        // f'''(x)  = 0
        // f''''(x) = 0
        // ζ        = f''''(x) = 0
        // Error    = O(h^5 * ζ) = 0

        $tol = 0;

        // Approximate with: (0, 1), (1.5, 6.25) and (3, 16)
        $x = SimpsonsThreeEighthsRule::approximate([[0, 1], [1, 4], [2, 9], [3, 16]]);
        $this->assertEquals($expected, $x, '', $tol);

        // Same test as above but with points not sorted to test sorting works
        $x = SimpsonsThreeEighthsRule::approximate([[2, 9], [3, 16], [0, 1], [1, 4]]);
        $this->assertEquals($expected, $x, '', $tol);

        // Similar test to above (same function, number of points, tolerance) but
        // with a callback function to make sure this type of input is compatible
        $func = function ($x) {
            return $x**2 + 2 * $x + 1;
        };
        $start = 0;
        $end   = 3;
        $n     = 4;
        $x     = SimpsonsThreeEighthsRule::approximate($func, $start, $end, $n);
        $this->assertEquals($expected, $x, '', $tol);
    }

    public function testSubintervalsNotFactorThree()
    {
        // There are not even even number of subintervals, or
        // equivalently, there are not an add number of points
        $this->expectException('\Exception');
        SimpsonsThreeEighthsRule::approximate([[0,0], [4,4], [2,2], [6,6], [8,8]]);
    }

    public function testNonConstantSpacingException()
    {
        // There is not constant spacing between points
        $this->expectException('\Exception');
        SimpsonsThreeEighthsRule::approximate([[0,0], [3,3], [2,2], [4,4]]);
    }
}
