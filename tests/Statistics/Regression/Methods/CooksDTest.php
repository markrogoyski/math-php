<?php

namespace MathPHP\Tests\Statistics\Regression\Methods;

use MathPHP\Statistics\Regression\Multilinear;
use MathPHP\Statistics\Regression\Polynomial;
use PHPUnit\Framework\TestCase;

class CooksDTest extends TestCase
{
    /**
     * @test CooksD calculation for Polynomial Regression
     * Points: (1, 1), (2, 3), (3, 3), (4, 5), (5, 5)
     * Using degree 1 (linear)
     * n = 5, p = 2 (slope + intercept)
     *
     * In statsmodels.OLS:
     * model = sm.OLS(y, sm.add_constant(x))
     * res = model.fit()
     * res.get_influence().cooks_distance[0]
     *
     * For y = [1, 3, 3, 5, 5], x = [1, 2, 3, 4, 5]
     * OLS results:
     * coefficients: [0.6, 1.0] (intercept=0.6, slope=1.0)
     * y_hat: [1.6, 2.6, 3.6, 4.6, 5.6]
     * residuals: [-0.6, 0.4, -0.6, 0.4, -0.6]
     * MSE: sum(res²)/(5-2) = (0.36 + 0.16 + 0.36 + 0.16 + 0.36) / 3 = 1.4 / 3 = 0.4666...
     * leverages: [0.6, 0.3, 0.2, 0.3, 0.6]
     * Cook's D:
     * Dᵢ = (eᵢ² / (MSE * p)) * (hᵢ / (1 - hᵢ)²)
     * D₁ = ((-0.6)² / (0.4666 * 2)) * (0.6 / (1 - 0.6)²)
     *    = (0.36 / 0.9333) * (0.6 / 0.16)
     *    = 0.3857 * 3.75 = 1.4464...
     */
    public function testPolynomialCooksD(): void
    {
        $points = [[1, 1], [2, 3], [3, 3], [4, 5], [5, 5]];
        $regression = new Polynomial($points, 1);
        $cooksD = $regression->cooksD();

        // Points: (1, 1), (2, 3), (3, 3), (4, 5), (5, 5)
        // y_hat = [1.4, 2.4, 3.4, 4.4, 5.4]
        // residuals = [-0.4, 0.6, -0.4, 0.6, -0.4]
        // MSE = (0.16 + 0.36 + 0.16 + 0.36 + 0.16) / 3 = 1.2 / 3 = 0.4
        // p = 2
        // leverages = [0.6, 0.3, 0.2, 0.3, 0.6]
        // D1 = ((-0.4)^2 / (0.4 * 2)) * (0.6 / (1-0.6)^2) = (0.16 / 0.8) * (0.6 / 0.16) = 0.2 * 3.75 = 0.75
        // D2 = ((0.6)^2 / (0.4 * 2)) * (0.3 / (1-0.3)^2) = (0.36 / 0.8) * (0.3 / 0.49) = 0.45 * 0.6122... = 0.27551...
        // D3 = ((-0.4)^2 / (0.4 * 2)) * (0.2 / (1-0.2)^2) = (0.16 / 0.8) * (0.2 / 0.64) = 0.2 * 0.3125 = 0.0625

        $expected = [0.75, 0.2755, 0.0625, 0.2755, 0.75];

        foreach ($expected as $i => $val) {
            $this->assertEqualsWithDelta($val, $cooksD[$i], 0.001);
        }
    }

    /**
     * @test CooksD calculation for Multilinear Regression
     */
    public function testMultilinearCooksD(): void
    {
        // Smaller n=4, p=3 case for easy verification.
        $points = [
            [[1, 1], 4],
            [[2, 1], 7],
            [[1, 2], 8],
            [[2, 2], 10],
        ];
        $regression = new Multilinear($points);
        $cooksD = $regression->cooksD();

        // Verification:
        // Leverages: [0.75, 0.75, 0.75, 0.75]
        // Residuals: [-0.25, 0.25, 0.25, -0.25]
        // MSE: 0.25
        // p = 3
        // D1 = ((-0.25)^2 / (0.25 * 3)) * (0.75 / (1-0.75)^2)
        //    = (0.0625 / 0.75) * (0.75 / 0.0625)
        //    = 1/12 * 12 = 1.0

        $expected = [1.0, 1.0, 1.0, 1.0];
        foreach ($expected as $i => $val) {
            $this->assertEqualsWithDelta($val, $cooksD[$i], 0.001);
        }
    }

    /**
     * @test CooksD for Polynomial order 2 without constant
     */
    public function testQuadraticNoConstantCooksD(): void
    {
        $points = [[1, 2], [2, 8], [3, 18], [4, 32], [5, 51]];
        // y = 2x^2 + noise
        $regression = new Polynomial($points, 2, 0); // order=2, fit_constant=0
        $cooksD = $regression->cooksD();

        // ν = n - numberOfTerms
        // numberOfTerms = getNumberOfTerms(1, 2) = 3.
        // But fit_constant=0, so numberOfTerms = 3 - 1 = 2 (x^2, x)
        // ν = 5 - 2 = 3. p = 5 - 3 = 2.
        $this->assertEquals(3, $regression->degreesOfFreedom());

        // Just check if it's computable and positive
        foreach ($cooksD as $val) {
            $this->assertGreaterThan(0, $val);
        }
    }
}
