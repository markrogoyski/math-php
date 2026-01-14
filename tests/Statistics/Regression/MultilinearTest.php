<?php

namespace MathPHP\Tests\Statistics\Regression;

use MathPHP\Statistics\Regression\Multilinear;
use MathPHP\Exception;
use PHPUnit\Framework\TestCase;

class MultilinearTest extends TestCase
{
    /**
     * @test         getParameters
     * @dataProvider dataProviderForParameters
     * @param        array $points
     * @param        array $expected_parameters
     */
    public function testGetParameters(array $points, array $expected_parameters)
    {
        // Given
        $regression = new Multilinear($points);

        // When
        $parameters = $regression->getParameters();

        // Then
        foreach ($expected_parameters as $name => $value) {
            $this->assertEqualsWithDelta($value, $parameters[$name], 0.0001);
        }
    }

    /**
     * @return array [points, expected_parameters]
     */
    public function dataProviderForParameters(): array
    {
        return [
            [
                // y = 2x₁ + 3x₂ + 5
                // Vandermonde (d=2, p=1): [0,0], [1,0], [0,1]
                // β₀ = ε = 5
                // β₁ = x₁ coeff = 2
                // β₂ = x₂ coeff = 3
                [
                    [[1, 1], 10],
                    [[2, 1], 12],
                    [[1, 2], 13],
                    [[2, 2], 15],
                    [[0, 0], 5],
                ],
                [
                    'β₁' => 2,
                    'β₂' => 3,
                    'ε'  => 5,
                ],
            ],
            [
                // Example from real data or calculated elsewhere
                // y = 2x₁ - 0.5x₂ + 10
                // Order from Vandermonde (d=2, p=1): [0,0], [0,1], [1,0]
                // β₀ = ε = 10
                // β₁ = x₁ coeff = 2
                // β₂ = x₂ coeff = -0.5
                [
                    [[1, 5], 9.5],   // 2(1) - 0.5(5) + 10 = 2 - 2.5 + 10 = 9.5
                    [[2, 8], 10],    // 2(2) - 0.5(8) + 10 = 4 - 4 + 10 = 10
                    [[3, 2], 15],    // 2(3) - 0.5(2) + 10 = 6 - 1 + 10 = 15
                    [[0, 0], 10],
                ],
                [
                    'β₁' => 2,
                    'β₂' => -0.5,
                    'ε'  => 10,
                ],
            ],
        ];
    }

    /**
     * @test         evaluateVector
     * @dataProvider dataProviderForEvaluateVector
     * @param        array $points
     * @param        array $vector
     * @param        float $expected_y
     */
    public function testEvaluateVector(array $points, array $vector, float $expected_y)
    {
        // Given
        $regression = new Multilinear($points);

        // When
        $y = $regression->evaluateVector($vector);

        // Then
        $this->assertEqualsWithDelta($expected_y, $y, 0.0001);
    }

    /**
     * @return array [points, vector, expected_y]
     */
    public function dataProviderForEvaluateVector(): array
    {
        return [
            [
                // y = 2x₁ + 3x₂ + 5
                [
                    [[1, 1], 10],
                    [[2, 1], 12],
                    [[1, 2], 13],
                    [[2, 2], 15],
                    [[0, 0], 5],
                ],
                [3, 4], // 2(3) + 3(4) + 5 = 6 + 12 + 5 = 23
                23,
            ],
        ];
    }

    /**
     * @test         getEquation
     * @dataProvider dataProviderForEquation
     * @param        array  $points
     * @param        string $expected_equation
     */
    public function testGetEquation(array $points, string $expected_equation)
    {
        // Given
        $regression = new Multilinear($points);

        // When
        $equation = $regression->getEquation();

        // Then
        $this->assertEquals($expected_equation, $equation);
    }

    /**
     * @return array [points, expected_equation]
     */
    public function dataProviderForEquation(): array
    {
        return [
            [
                [
                    [[1, 1], 10],
                    [[2, 1], 12],
                    [[1, 2], 13],
                    [[2, 2], 15],
                    [[0, 0], 5],
                ],
                'y = 2.000000x₁ + 3.000000x₂ + 5.000000',
            ],
        ];
    }

    /**
     * @test yHat
     */
    public function testYHat()
    {
        // Given
        $points = [
            [[1, 1], 10],
            [[2, 1], 12],
            [[1, 2], 13],
            [[2, 2], 15],
            [[0, 0], 5],
        ];
        $regression = new Multilinear($points);

        // When
        $yHat = $regression->yHat();

        // Then
        $this->assertCount(5, $yHat);
        $this->assertEqualsWithDelta(10, $yHat[0], 0.0001);
        $this->assertEqualsWithDelta(12, $yHat[1], 0.0001);
        $this->assertEqualsWithDelta(13, $yHat[2], 0.0001);
        $this->assertEqualsWithDelta(15, $yHat[3], 0.0001);
        $this->assertEqualsWithDelta(5, $yHat[4], 0.0001);
    }

    /**
     * @test evaluate throws exception
     */
    public function testEvaluateThrowsException()
    {
        // Given
        $points = [
            [[1, 1], 10],
            [[2, 5], 12],
            [[3, 2], 14],
            [[4, 8], 16],
        ];
        $regression = new Multilinear($points);

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        $regression->evaluate(1.0);
    }
}
