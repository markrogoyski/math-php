<?php

namespace MathPHP\Tests\Statistics\Regression;

use MathPHP\Statistics\Regression\Multilinear;
use MathPHP\Statistics\Regression\Polynomial;

class MultiLeastSquaresTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests regression statistics for k > 1.
     * Oracle: statsmodels OLS, scikit-learn LinearRegression
     */
    public function testDegreesOfFreedomAndStatistics(): void
    {
        // y ≈ 2x₁ + 3x₂ + 5 with noise, k=2 features, n=10 points
        $points = [
            [[1.0, 2.0], 13.3], [[3.0, 1.0], 13.8],
            [[2.0, 4.0], 21.5], [[5.0, 2.0], 20.9],
            [[4.0, 3.0], 22.4], [[1.0, 5.0], 21.7],
            [[6.0, 1.0], 20.2], [[3.0, 3.0], 20.1],
            [[2.0, 6.0], 26.6], [[4.0, 4.0], 25.3],
        ];
        $regression = new Multilinear($points);

        // Degrees of freedom: should be n - k - 1 = 10 - 2 - 1 = 7
        $this->assertEquals(7, $regression->degreesOfFreedom());

        // F-statistic: should be 705.22 (statsmodels OLS)
        $this->assertEqualsWithDelta(705.2243, $regression->fStatistic(), 0.001);
    }

    /**
     * Tests polynomial regression of degree 2.
     * Equation: y = 2x² + 3x + 5
     * Points: (1, 10), (2, 19), (3, 32), (4, 49), (5, 70)
     */
    public function testQuadraticRegression(): void
    {
        $points = [
            [1, 10], [2, 19], [3, 32], [4, 49], [5, 70]
        ];
        $order = 2; // Quadratic
        $regression = new Polynomial($points, $order);

        // Parameters: [5, 3, 2] (constant first due to Vandermonde)
        $params = $regression->getParameters();
        $this->assertEqualsWithDelta(5.0, $params['β₀'], 0.001);
        $this->assertEqualsWithDelta(3.0, $params['β₁'], 0.001);
        $this->assertEqualsWithDelta(2.0, $params['β₂'], 0.001);

        // Degrees of freedom: n - (order + 1) = 5 - (2 + 1) = 2
        $this->assertEquals(2, $regression->degreesOfFreedom());

        // Prediction
        $this->assertEqualsWithDelta(95.0, $regression->evaluate(6), 0.001);
    }

    /**
     * Tests polynomial regression of degree 3.
     * Equation: y = x³ - 2x² + 5x + 10
     * Points: (0, 10), (1, 14), (2, 20), (3, 34), (4, 62)
     */
    public function testCubicRegression(): void
    {
        $points = [
            [0, 10], [1, 14], [2, 20], [3, 34], [4, 62]
        ];
        $order = 3;
        $regression = new Polynomial($points, $order);

        $params = $regression->getParameters();
        $this->assertEqualsWithDelta(10.0, $params['β₀'], 0.001);
        $this->assertEqualsWithDelta(5.0, $params['β₁'], 0.001);
        $this->assertEqualsWithDelta(-2.0, $params['β₂'], 0.001);
        $this->assertEqualsWithDelta(1.0, $params['β₃'], 0.001);

        // Degrees of freedom: n - (order + 1) = 5 - (3 + 1) = 1
        $this->assertEquals(1, $regression->degreesOfFreedom());

        // Prediction: 5³ - 2(5²) + 5(5) + 10 = 125 - 50 + 25 + 10 = 110
        $this->assertEqualsWithDelta(110.0, $regression->evaluate(5), 0.001);
    }
}
