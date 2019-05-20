<?php
namespace MathPHP\Tests\NumericalAnalysis\NumericalIntegration;

use MathPHP\Functions\Polynomial;
use MathPHP\NumericalAnalysis\NumericalIntegration\BoolesRule;

class BoolesRuleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test   approximate using sorted points
     * @throws \Exception
     *
     * f(x)                            = x³ + 2x + 1
     * Antiderivative F(x)             = (1/4)x⁴ + x² + x
     * Indefinite integral over [0, 4] = F(4) - F(0) = 84
     *
     * h           denotes the size of subintervals, or equivalently, the
     *                 distance between two points
     * ζ₁, ζ₂, ... denotes the max of the fourth derivative of f(x) on
     *                 interval 1, 2, ...
     * f'(x)    = 3x² + 2
     * f''(x)   = 6x
     * f'''(x)  = 6
     * f⁽⁴⁾(x)  = 0
     * f⁽⁵⁾(x)  = 0
     * f⁽⁶⁾(x)  = 0
     * Error    = O(h^5 * f⁽⁶⁾(x)) = 0
     */
    public function testApproximatePolynomialSortedPoints()
    {
        // Given
        $sortedPoints = [[0, 1], [1, 4], [2, 13], [3, 34], [4, 73]];
        $expected     = 84;
        $tol          = 0;

        // When
        $x = BoolesRule::approximate($sortedPoints);

        // Then
        $this->assertEquals($expected, $x, '', $tol);
    }

    /**
     * @test   approximate using non-sorted points
     * @throws \Exception
     *
     * f(x)                            = x³ + 2x + 1
     * Antiderivative F(x)             = (1/4)x⁴ + x² + x
     * Indefinite integral over [0, 4] = F(4) - F(0) = 84
     *
     * h           denotes the size of subintervals, or equivalently, the
     *                 distance between two points
     * ζ₁, ζ₂, ... denotes the max of the fourth derivative of f(x) on
     *                 interval 1, 2, ...
     * f'(x)    = 3x² + 2
     * f''(x)   = 6x
     * f'''(x)  = 6
     * f⁽⁴⁾(x)  = 0
     * f⁽⁵⁾(x)  = 0
     * f⁽⁶⁾(x)  = 0
     * Error    = O(h^5 * f⁽⁶⁾(x)) = 0
     */
    public function testApproximatePolynomialNonSortedPoints()
    {
        // Given
        $nonSortedPoints = [[0, 1], [3, 34], [2, 13], [1, 4], [4, 73]];
        $expected        = 84;
        $tol             = 0;

        // When
        $x = BoolesRule::approximate($nonSortedPoints);

        // Then
        $this->assertEquals($expected, $x, '', $tol);
    }

    /**
     * @test   approximate using callback function
     * @throws \Exception
     *
     * f(x)                            = x³ + 2x + 1
     * Antiderivative F(x)             = (1/4)x⁴ + x² + x
     * Indefinite integral over [0, 4] = F(4) - F(0) = 84
     *
     * h           denotes the size of subintervals, or equivalently, the
     *                 distance between two points
     * ζ₁, ζ₂, ... denotes the max of the fourth derivative of f(x) on
     *                 interval 1, 2, ...
     * f'(x)    = 3x² + 2
     * f''(x)   = 6x
     * f'''(x)  = 6
     * f⁽⁴⁾(x)  = 0
     * f⁽⁵⁾(x)  = 0
     * f⁽⁶⁾(x)  = 0
     * Error    = O(h^5 * f⁽⁶⁾(x)) = 0
     */
    public function testApproximatePolynomialCallback()
    {
        // Given x³ + 2x + 1
        $func = function ($x) {
            return $x**3 + 2 * $x + 1;
        };
        $start    = 0;
        $end      = 4;
        $n        = 5;
        $expected = 84;
        $tol      = 0;

        // When
        $x = BoolesRule::approximate($func, $start, $end, $n);

        // Then
        $this->assertEquals($expected, $x, '', $tol);
    }

    /**
     * @test   approximate using Polynomial
     * @throws \Exception
     *
     * f(x)                            = x³ + 2x + 1
     * Antiderivative F(x)             = (1/4)x⁴ + x² + x
     * Indefinite integral over [0, 4] = F(4) - F(0) = 84
     *
     * h           denotes the size of subintervals, or equivalently, the
     *                 distance between two points
     * ζ₁, ζ₂, ... denotes the max of the fourth derivative of f(x) on
     *                 interval 1, 2, ...
     * f'(x)    = 3x² + 2
     * f''(x)   = 6x
     * f'''(x)  = 6
     * f⁽⁴⁾(x)  = 0
     * f⁽⁵⁾(x)  = 0
     * f⁽⁶⁾(x)  = 0
     * Error    = O(h^5 * f⁽⁶⁾(x)) = 0
     */
    public function testApproximatePolynomialUsingPolynomial()
    {
        // Given x³ + 2x + 1
        $polynomial = new Polynomial([1, 0, 2, 1]);
        $start      = 0;
        $end        = 4;
        $n          = 5;
        $expected   = 84;
        $tol        = 0;

        // When
        $x = BoolesRule::approximate($polynomial, $start, $end, $n);

        // Then
        $this->assertEquals($expected, $x, '', $tol);
    }

    /**
     * @test   approximate exception when sub intervals are not a factor of four, or
     *         equivalently, the number of points minus one is not a factor of four
     * @throws \Exception
     */
    public function testSubIntervalsNotFactorFour()
    {
        // Given
        $points = [[0,0], [4,4], [2,2], [6,6], [8,8], [10, 10]];

        // Then
        $this->expectException(\Exception::class);

        // When
        BoolesRule::approximate($points);
    }

    /**
     * @test   approximate exception when there is not constant spacing between points
     * @throws \Exception
     */
    public function testNonConstantSpacingException()
    {
        // Given
        $points = [[0,0], [3,3], [2,2], [4,4], [5, 5]];

        // Then
        $this->expectException(\Exception::class);

        // When
        BoolesRule::approximate($points);
    }
}
