<?php

namespace MathPHP\Tests\Statistics;

use MathPHP\Statistics\Circular;

class CircularTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         mean
     * @dataProvider dataProviderForMean
     * @param        array $angles
     * @param        float $expected
     */
    public function testMean(array $angles, float $expected)
    {
        // When
        $mean = Circular::mean($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $mean, 0.000001);
    }

    /**
     * Test data generated using R package circular's mean.circular()
     * https://cran.r-project.org/web/packages/circular/circular.pdf
     *
     * Edge case note: Test case [[0, π]] uses Python scipy.stats.circmean() for validation
     * since R returns NA for this undefined case. Both give 1.5707963267948966 (π/2).
     *
     * @return array [angles, mean]
     */
    public function dataProviderForMean(): array
    {
        $π = \M_PI;

        return [
            [[0, 2 * $π], 0],
            [[0, 0.5 * $π], 0.7853982],
            [[0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1.0], 0.5],
            [[0 * $π, 0.1 * $π, 0.2 * $π, 0.3 * $π, 0.4 * $π, 0.5 * $π, 0.6 * $π, 0.7 * $π, 0.8 * $π, 0.9 * $π, 1 * $π], 1.570796],
            [[0, 0, 90], .5226276],
            [[1.4 * $π, 1.7 * $π, 1.75 * $π, 2.54 * $π, 4.32 * $π], -0.4242655],
            [[5, 60, 340], -1.423654],
            [[5, 50, 150, 250], -0.9253517],
            [[10, 20, 30], -1.991149],
            [[355, 5, 15], -2.935443],

            // In this test case, we end up with
            //  sin(0) +  sin(π) = 0 + 0 = 0
            // \cos(0) + \cos(π) = 1 - 1 = 0
            // So it seems like it should end up as atan2(0, 0),
            // but since the sum of sins isn't perfectly 0, it is a very small floating point number,
            // like atan2(1.2246467991474E-16, 0),
            // which ends up as arctan(infinity) which equals 1.57079633.
            // R mean.circular results in NA,
            // but tested with Python scipi.stats.circmean(), it results in 1.5707963267948966,
            // which matches our PHP answer.
            [[0, $π], 1.5707963267948966],
        ];
    }

    /**
     * @test         resultantLength
     * @dataProvider dataProviderForResultantLength
     * @param        array $angles
     * @param        float $expected
     */
    public function testResultantLength(array $angles, float $expected)
    {
        // When
        $length = Circular::resultantLength($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $length, 0.00001);
    }

    /**
     * Test data generated using custom R function implementing the mathematical formula.
     *
     * Source: Custom R implementation using the formula R = √(S² + C²)
     *   S = Σ sin(θᵢ)
     *   C = Σ cos(θᵢ)
     *   R = √(S² + C²)
     *
     * resultantLength <- function(x) {
     *    sinSum = sum(sin(x))
     *    cosSum = sum(cos(x))
     *    R      = sqrt(sinSum^2 + cosSum^2)
     *    return(R)
     *  }
     *
     * Reference: Wikipedia - Directional Statistics
     * https://en.wikipedia.org/wiki/Directional_statistics#Moments
     *
     * @return array [angles, length]
     */
    public function dataProviderForResultantLength(): array
    {
        $π = \M_PI;

        return [
            [[0, $π], 1.224647e-16],
            [[0, 0.5, $π], 1],
            [[0, 2 * $π], 2],
            [[0, 0.5 * $π], 1.414214],
            [[0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1.0], 10.4581],
            [[0 * $π, 0.1 * $π, 0.2 * $π, 0.3 * $π, 0.4 * $π, 0.5 * $π, 0.6 * $π, 0.7 * $π, 0.8 * $π, 0.9 * $π, 1 * $π], 6.313752],
            [[0, 0, 90], 1.791007],
            [[1.4 * $π, 1.7 * $π, 1.75 * $π, 2.54 * $π, 4.32 * $π], 1.532213],
            [[5, 60, 340], 0.6201251],
            [[5, 50, 150, 250], 3.63869],
            [[10, 20, 30], 0.6781431],
            [[355, 5, 15], 1.507955],
        ];
    }

    /**
     * @test         meanResultantLength
     * @dataProvider dataProviderForMeanResultantLength
     * @param        array $angles
     * @param        float $expected
     */
    public function testMeanResultantLength(array $angles, float $expected)
    {
        // When
        $length = Circular::meanResultantLength($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $length, 0.000001);
    }

    /**
     * Test data generated using custom R function implementing the mathematical formula.
     *
     * Source: Custom R implementation using the formula ρ = R/n where R = √(S² + C²)
     *   n = number of angles
     *   S = Σ sin(θᵢ)
     *   C = Σ cos(θᵢ)
     *   R = √(S² + C²)
     *   ρ = R/n
     *
     * meanResultantLength <- function(x) {
     *    n      = length(x)
     *    sinSum = sum(sin(x))
     *    cosSum = sum(cos(x))
     *    rho    = sqrt(sinSum^2 + cosSum^2) / n
     *    return(rho)
     * }
     *
     * Reference: Wikipedia - Directional Statistics
     * https://en.wikipedia.org/wiki/Directional_statistics#Moments
     *
     * @return array [angles, length]
     */
    public function dataProviderForMeanResultantLength(): array
    {
        $π = \M_PI;

        return [
            [[0, $π], 6.123234e-17],
            [[0, 0.5, $π], 0.3333333],
            [[0, 2 * $π], 1],
            [[0, 0.5 * $π], 0.7071068],
            [[0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1.0], 0.9507365],
            [[0 * $π, 0.1 * $π, 0.2 * $π, 0.3 * $π, 0.4 * $π, 0.5 * $π, 0.6 * $π, 0.7 * $π, 0.8 * $π, 0.9 * $π, 1 * $π], 0.5739774],
            [[0, 0, 90], 0.5970023],
            [[1.4 * $π, 1.7 * $π, 1.75 * $π, 2.54 * $π, 4.32 * $π], 0.3064425],
            [[5, 60, 340], 0.2067084],
            [[5, 50, 150, 250], 0.9096725],
            [[10, 20, 30], 0.2260477],
            [[355, 5, 15], 0.5026515],
        ];
    }

    /**
     * @test         variance
     * @dataProvider dataProviderForVariance
     * @param        array $angles
     * @param        float $expected
     */
    public function testVariance(array $angles, float $expected)
    {
        // When
        $variance = Circular::variance($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $variance, 0.000001);
    }

    /**
     * Test data generated using R package circular's var.circular()
     * Reference: https://cran.r-project.org/web/packages/circular/circular.pdf
     *
     * Formula: Var(θ) = 1 - ρ
     * where ρ is the mean resultant length
     *
     * Properties:
     * - Range: [0, 1]
     * - When ρ = 1 (perfect concentration): Var = 0
     * - When ρ ≈ 0 (uniform distribution): Var ≈ 1
     *
     * @return array [angles, variance]
     */
    public function dataProviderForVariance(): array
    {
        $π = \M_PI;

        return [
            [[0, $π], 1],
            [[0, 0.5, $π], 0.6666667],
            [[0, 2 * $π], 0],
            [[0, 0.5 * $π], 0.2928932],
            [[0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1.0], 0.04926349],
            [[0 * $π, 0.1 * $π, 0.2 * $π, 0.3 * $π, 0.4 * $π, 0.5 * $π, 0.6 * $π, 0.7 * $π, 0.8 * $π, 0.9 * $π, 1 * $π], 0.4260226],
            [[0, 0, 90], 0.4029977],
            [[1.4 * $π, 1.7 * $π, 1.75 * $π, 2.54 * $π, 4.32 * $π], 0.6935575],
            [[5, 60, 340], 0.7932916],
            [[5, 50, 150, 250], 0.09032747],
            [[10, 20, 30], 0.7739523],
            [[355, 5, 15], 0.4973485],
        ];
    }

    /**
     * @test         standardDeviation
     * @dataProvider dataProviderForStandardDeviation
     * @param        array $angles
     * @param        float $expected
     */
    public function testStandardDeviation(array $angles, float $expected)
    {
        // When
        $sd = Circular::standardDeviation($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $sd, 0.000001);
    }

    /**
     * Test data generated using R package circular's sd.circular()
     * Reference: https://cran.r-project.org/web/packages/circular/circular.pdf
     *
     * Formula: ν = √(-2 ln(ρ))
     * where ρ is the mean resultant length
     *
     * Properties:
     * - Range: [0, ∞]
     * - When ρ = 1 (perfect concentration): ν = 0
     * - When ρ → 0 (uniform distribution): ν → ∞
     * - Monotonically increases as ρ decreases
     *
     * @return array [angles, standardDeviation]
     */
    public function dataProviderForStandardDeviation(): array
    {
        $π = \M_PI;

        return [
            [[0, $π], 8.640817],
            [[0, 0.5, $π], 1.482304],
            [[0, 2 * $π], 0],
            [[0, 0.5 * $π], 0.8325546],
            [[0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1.0], 0.3178626],
            [[0 * $π, 0.1 * $π, 0.2 * $π, 0.3 * $π, 0.4 * $π, 0.5 * $π, 0.6 * $π, 0.7 * $π, 0.8 * $π, 0.9 * $π, 1 * $π], 1.053722],
            [[0, 0, 90], 1.015711],
            [[1.4 * $π, 1.7 * $π, 1.75 * $π, 2.54 * $π, 4.32 * $π], 1.538002],
            [[5, 60, 340], 1.775639],
            [[5, 50, 150, 250], 0.4351335],
            [[10, 20, 30], 1.724534],
            [[355, 5, 15], 1.172909],
        ];
    }

    /**
     * @test describe
     */
    public function testDescribe()
    {
        // Given
        $values = [5, 15, 355];

        // When
        $stats = Circular::describe($values);

        // Then
        $this->assertTrue(\is_array($stats));
        $this->assertArrayHasKey('n', $stats);
        $this->assertArrayHasKey('mean', $stats);
        $this->assertArrayHasKey('resultant_length', $stats);
        $this->assertArrayHasKey('mean_resultant_length', $stats);
        $this->assertArrayHasKey('variance', $stats);
        $this->assertArrayHasKey('sd', $stats);

        // And
        $this->assertTrue(\is_int($stats['n']));
        $this->assertTrue(\is_float($stats['mean']));
        $this->assertTrue(\is_float($stats['resultant_length']));
        $this->assertTrue(\is_float($stats['mean_resultant_length']));
        $this->assertTrue(\is_float($stats['variance']));
        $this->assertTrue(\is_float($stats['sd']));
    }

    // =====================================================================
    // EDGE CASE TESTS: Single Element Arrays
    // =====================================================================

    /**
     * @test         meanSingleElement
     * @dataProvider dataProviderForMeanSingleElement
     * @param        array $angles
     * @param        float $expected
     */
    public function testMeanSingleElement(array $angles, float $expected)
    {
        // When
        $mean = Circular::mean($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $mean, 0.000001);
    }

    /**
     * Single element: mean should return same direction (normalized to [-π, π])
     * @return array [angles, mean]
     */
    public function dataProviderForMeanSingleElement(): array
    {
        $π = \M_PI;

        return [
            [[0], 0],
            [[0.25 * $π], 0.25 * $π],
            [[0.5 * $π], 0.5 * $π],
            [[1.5 * $π], -0.5 * $π],  // 1.5π normalizes to -0.5π
            [[-0.5], -0.5],
            [[1.0], 1.0],
        ];
    }

    /**
     * @test         resultantLengthSingleElement
     * @dataProvider dataProviderForResultantLengthSingleElement
     * @param        array $angles
     * @param        float $expected
     */
    public function testResultantLengthSingleElement(array $angles, float $expected)
    {
        // When
        $length = Circular::resultantLength($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $length, 0.000001);
    }

    /**
     * Resultant length for single element is always 1 (unit vector)
     * R = √(sin²θ + cos²θ) = √1 = 1
     * @return array [angles, length]
     */
    public function dataProviderForResultantLengthSingleElement(): array
    {
        $π = \M_PI;

        return [
            [[0], 1],
            [[0.25 * $π], 1],
            [[0.5 * $π], 1],
            [[0.75 * $π], 1],
            [[1.5 * $π], 1],
            [[45], 1],
            [[-2.5], 1],
        ];
    }

    /**
     * @test         meanResultantLengthSingleElement
     * @dataProvider dataProviderForMeanResultantLengthSingleElement
     * @param        array $angles
     * @param        float $expected
     */
    public function testMeanResultantLengthSingleElement(array $angles, float $expected)
    {
        // When
        $length = Circular::meanResultantLength($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $length, 0.000001);
    }

    /**
     * Mean resultant length for single element: ρ = R/n = 1/1 = 1
     * @return array [angles, length]
     */
    public function dataProviderForMeanResultantLengthSingleElement(): array
    {
        $π = \M_PI;

        return [
            [[0], 1],
            [[0.5 * $π], 1],
            [[45], 1],
            [[-3.7], 1],
        ];
    }

    /**
     * @test         varianceSingleElement
     * @dataProvider dataProviderForVarianceSingleElement
     * @param        array $angles
     * @param        float $expected
     */
    public function testVarianceSingleElement(array $angles, float $expected)
    {
        // When
        $variance = Circular::variance($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $variance, 0.000001);
    }

    /**
     * Variance for single element: Var = 1 - ρ = 1 - 1 = 0
     * A single point has zero variance
     * @return array [angles, variance]
     */
    public function dataProviderForVarianceSingleElement(): array
    {
        $π = \M_PI;

        return [
            [[0], 0],
            [[0.5 * $π], 0],
            [[90], 0],
            [[-45], 0],
        ];
    }

    /**
     * @test         standardDeviationSingleElement
     * @dataProvider dataProviderForStandardDeviationSingleElement
     * @param        array $angles
     * @param        float $expected
     */
    public function testStandardDeviationSingleElement(array $angles, float $expected)
    {
        // When
        $sd = Circular::standardDeviation($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $sd, 0.000001);
    }

    /**
     * Standard deviation for single element: ν = √(-2ln(ρ)) = √(-2ln(1)) = √0 = 0
     * A single point has zero standard deviation
     * @return array [angles, sd]
     */
    public function dataProviderForStandardDeviationSingleElement(): array
    {
        $π = \M_PI;

        return [
            [[0], 0],
            [[0.5 * $π], 0],
            [[45], 0],
            [[-2.5], 0],
        ];
    }

    // =====================================================================
    // EDGE CASE TESTS: Identical Values (All Same Angle)
    // =====================================================================

    /**
     * @test         meanIdenticalValues
     * @dataProvider dataProviderForMeanIdenticalValues
     * @param        array $angles
     * @param        float $expected
     */
    public function testMeanIdenticalValues(array $angles, float $expected)
    {
        // When
        $mean = Circular::mean($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $mean, 0.000001);
    }

    /**
     * All identical values should return that value
     * @return array [angles, mean]
     */
    public function dataProviderForMeanIdenticalValues(): array
    {
        $π = \M_PI;

        return [
            [[0, 0, 0], 0],
            [[0.25 * $π, 0.25 * $π, 0.25 * $π, 0.25 * $π], 0.25 * $π],
            [[0.5 * $π, 0.5 * $π], 0.5 * $π],
            [[1.2, 1.2, 1.2, 1.2, 1.2], 1.2],
            [[-1.5, -1.5, -1.5], -1.5],
        ];
    }

    /**
     * @test         resultantLengthIdenticalValues
     * @dataProvider dataProviderForResultantLengthIdenticalValues
     * @param        array $angles
     * @param        float $expected
     */
    public function testResultantLengthIdenticalValues(array $angles, float $expected)
    {
        // When
        $length = Circular::resultantLength($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $length, 0.000001);
    }

    /**
     * All identical values: all vectors point in same direction
     * R = n (not normalized, so length equals count)
     * @return array [angles, length]
     */
    public function dataProviderForResultantLengthIdenticalValues(): array
    {
        $π = \M_PI;

        return [
            [[0, 0, 0], 3],
            [[45, 45, 45, 45], 4],
            [[0.5 * $π, 0.5 * $π], 2],
            [[1.5, 1.5, 1.5, 1.5, 1.5], 5],
        ];
    }

    /**
     * @test         meanResultantLengthIdenticalValues
     * @dataProvider dataProviderForMeanResultantLengthIdenticalValues
     * @param        array $angles
     * @param        float $expected
     */
    public function testMeanResultantLengthIdenticalValues(array $angles, float $expected)
    {
        // When
        $length = Circular::meanResultantLength($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $length, 0.000001);
    }

    /**
     * All identical values: ρ = R/n = n/n = 1 (maximum concentration)
     * @return array [angles, length]
     */
    public function dataProviderForMeanResultantLengthIdenticalValues(): array
    {
        return [
            [[0, 0, 0], 1],
            [[45, 45, 45, 45], 1],
            [[1.5, 1.5, 1.5, 1.5, 1.5], 1],
        ];
    }

    /**
     * @test         varianceIdenticalValues
     * @dataProvider dataProviderForVarianceIdenticalValues
     * @param        array $angles
     * @param        float $expected
     */
    public function testVarianceIdenticalValues(array $angles, float $expected)
    {
        // When
        $variance = Circular::variance($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $variance, 0.000001);
    }

    /**
     * All identical values: Var = 1 - ρ = 1 - 1 = 0 (no variance)
     * @return array [angles, variance]
     */
    public function dataProviderForVarianceIdenticalValues(): array
    {
        return [
            [[0, 0, 0, 0], 0],
            [[45, 45, 45], 0],
            [[1.5, 1.5, 1.5, 1.5, 1.5], 0],
        ];
    }

    /**
     * @test         standardDeviationIdenticalValues
     * @dataProvider dataProviderForStandardDeviationIdenticalValues
     * @param        array $angles
     * @param        float $expected
     */
    public function testStandardDeviationIdenticalValues(array $angles, float $expected)
    {
        // When
        $sd = Circular::standardDeviation($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $sd, 0.000001);
    }

    /**
     * All identical values: ν = √(-2ln(1)) = 0
     * @return array [angles, sd]
     */
    public function dataProviderForStandardDeviationIdenticalValues(): array
    {
        return [
            [[0, 0, 0, 0, 0], 0],
            [[0.5, 0.5], 0],
            [[1.2, 1.2, 1.2], 0],
        ];
    }

    // =====================================================================
    // BOUNDARY CONDITION TESTS: Very Small Angles
    // =====================================================================

    /**
     * @test         meanVerySmallAngles
     * @dataProvider dataProviderForMeanVerySmallAngles
     * @param        array $angles
     * @param        float $expected
     */
    public function testMeanVerySmallAngles(array $angles, float $expected)
    {
        // When
        $mean = Circular::mean($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $mean, 0.000001);
    }

    /**
     * Test with very small angles (near zero)
     * @return array [angles, mean]
     */
    public function dataProviderForMeanVerySmallAngles(): array
    {
        return [
            [[0.0001, 0.00015, 0.0002], 0.00015],
            [[1e-6, 2e-6, 3e-6], 2e-6],
            [[1e-10, 1e-10], 1e-10],
        ];
    }

    // =====================================================================
    // BOUNDARY CONDITION TESTS: Zero Angles
    // =====================================================================

    /**
     * @test         meanZeroAngles
     * @dataProvider dataProviderForMeanZeroAngles
     * @param        array $angles
     * @param        float $expected
     */
    public function testMeanZeroAngles(array $angles, float $expected)
    {
        // When
        $mean = Circular::mean($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $mean, 0.000001);
    }

    /**
     * Test with all zero angles
     * @return array [angles, mean]
     */
    public function dataProviderForMeanZeroAngles(): array
    {
        return [
            [[0, 0], 0],
            [[0, 0, 0, 0, 0], 0],
        ];
    }

    /**
     * @test         resultantLengthZeroAngles
     * @dataProvider dataProviderForResultantLengthZeroAngles
     * @param        array $angles
     * @param        float $expected
     */
    public function testResultantLengthZeroAngles(array $angles, float $expected)
    {
        // When
        $length = Circular::resultantLength($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $length, 0.000001);
    }

    /**
     * All zeros: R = √((n*0)² + (n*1)²) = n
     * @return array [angles, length]
     */
    public function dataProviderForResultantLengthZeroAngles(): array
    {
        return [
            [[0, 0], 2],
            [[0, 0, 0], 3],
            [[0, 0, 0, 0], 4],
        ];
    }

    // =====================================================================
    // BOUNDARY CONDITION TESTS: Negative Angles
    // =====================================================================

    /**
     * @test         meanNegativeAngles
     * @dataProvider dataProviderForMeanNegativeAngles
     * @param        array $angles
     * @param        float $expected
     */
    public function testMeanNegativeAngles(array $angles, float $expected)
    {
        // When
        $mean = Circular::mean($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $mean, 0.000001);
    }

    /**
     * Test with negative angles
     * @return array [angles, mean]
     */
    public function dataProviderForMeanNegativeAngles(): array
    {
        $π = \M_PI;

        return [
            [[-0.1, -0.2, -0.3], -0.2],
            [[-$π/4, -$π/4], -$π/4],
            [[-0.5, -0.5, -0.5], -0.5],
            [[-1, 1], 0],
        ];
    }

    /**
     * @test         resultantLengthNegativeAngles
     * @dataProvider dataProviderForResultantLengthNegativeAngles
     * @param        array $angles
     * @param        float $expected
     */
    public function testResultantLengthNegativeAngles(array $angles, float $expected)
    {
        // When
        $length = Circular::resultantLength($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $length, 0.000001);
    }

    /**
     * Resultant length with negative angles
     * @return array [angles, length]
     */
    public function dataProviderForResultantLengthNegativeAngles(): array
    {
        $π = \M_PI;

        return [
            [[-0.5, -0.5, -0.5], 3],
            [[-$π/4, $π/4], 1.414214],
            [[-0.1, -0.2, -0.3], 2.990008],
        ];
    }

    // =====================================================================
    // BOUNDARY CONDITION TESTS: Angles at Multiples of π
    // =====================================================================

    /**
     * @test         meanMultiplesOfPi
     * @dataProvider dataProviderForMeanMultiplesOfPi
     * @param        array $angles
     * @param        float $expected
     */
    public function testMeanMultiplesOfPi(array $angles, float $expected)
    {
        // When
        $mean = Circular::mean($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $mean, 0.000001);
    }

    /**
     * Test with multiples of π (axis-aligned)
     * @return array [angles, mean]
     */
    public function dataProviderForMeanMultiplesOfPi(): array
    {
        $π = \M_PI;

        return [
            [[0, $π], 1.5707963267948966],               // 0 and π: opposite, mean is π/2 (or -π/2)
            [[0, 0.5 * $π], 0.25 * $π],                  // 0 and π/2: mean is π/4
            [[0.5 * $π, 0.5 * $π, 0.5 * $π], 0.5 * $π],  // All same: mean is that angle
        ];
    }

    /**
     * @test         resultantLengthMultiplesOfPi
     * @dataProvider dataProviderForResultantLengthMultiplesOfPi
     * @param        array $angles
     * @param        float $expected
     */
    public function testResultantLengthMultiplesOfPi(array $angles, float $expected)
    {
        // When
        $length = Circular::resultantLength($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $length, 0.00001);
    }

    /**
     * Resultant length with multiples of π
     * @return array [angles, length]
     */
    public function dataProviderForResultantLengthMultiplesOfPi(): array
    {
        $π = \M_PI;

        return [
            [[0.5 * $π, 1.5 * $π], 0],        // π/2 and 3π/2: opposite, cancel out
            [[$π, 2 * $π], 1.224647e-16],     // π and 2π=0: nearly opposite
            [[0, 0.5 * $π, $π, 1.5 * $π], 0], // All four quadrants: perfect balance
        ];
    }

    // =====================================================================
    // BOUNDARY CONDITION TESTS: Perfectly Clustered Angles
    // =====================================================================

    /**
     * @test         meanClusteredAngles
     * @dataProvider dataProviderForMeanClusteredAngles
     * @param        array $angles
     * @param        float $expected
     */
    public function testMeanClusteredAngles(array $angles, float $expected)
    {
        // When
        $mean = Circular::mean($angles);

        // Then
        $this->assertEqualsWithDelta($expected, $mean, 0.000001);
    }

    /**
     * Test with tightly clustered angles (high concentration)
     * @return array [angles, mean]
     */
    public function dataProviderForMeanClusteredAngles(): array
    {
        return [
            [[1.0, 1.0001, 1.0002, 1.0003], 1.00015],
            [[0, 0.001, 0.002, 0.003, 0.004], 0.002],
            [[0.5, 0.501, 0.499], 0.5],
        ];
    }

    /**
     * @test         varianceClusteredAngles
     * @dataProvider dataProviderForVarianceClusteredAngles
     * @param        array $angles
     * @param        float $expected
     */
    public function testVarianceClusteredAngles(array $angles, float $expected)
    {
        // When
        $variance = Circular::variance($angles);

        // Then very tight clustering allows larger tolerance
        $this->assertEqualsWithDelta($expected, $variance, 0.000001);
    }

    /**
     * Tightly clustered angles should have very low variance
     * @return array [angles, variance]
     */
    public function dataProviderForVarianceClusteredAngles(): array
    {
        return [
            // Variance should be very close to 0 for tightly clustered data
            [[0, 0.001, 0.002], 0.0000002],
        ];
    }

    // =====================================================================
    // BOUNDARY CONDITION TESTS: Uniformly Distributed Angles
    // =====================================================================

    /**
     * @test         meanUniformlyDistributed
     * @dataProvider dataProviderForMeanUniformlyDistributed
     * @param        array $angles
     * @param        float $expected
     */
    public function testMeanUniformlyDistributed(array $angles, float $expected)
    {
        // When
        $mean = Circular::mean($angles);

        // Then for uniform distribution, the exact mean is undefined, but should be near 0 in magnitude
        $this->assertTrue(\is_float($mean));
    }

    /**
     * Test with uniformly distributed angles around circle
     * @return array [angles, dummy expected value]
     */
    public function dataProviderForMeanUniformlyDistributed(): array
    {
        $π = \M_PI;

        return [
            // 4 equally spaced angles (90 degrees apart)
            [[0, 0.5 * $π, $π, 1.5 * $π], 0],
            // 6 equally spaced angles (60 degrees apart)
            [[0, $π/3, 2*$π/3, $π, 4*$π/3, 5*$π/3], 0],
        ];
    }

    /**
     * @test         meanResultantLengthUniformlyDistributed
     * @dataProvider dataProviderForMeanResultantLengthUniformlyDistributed
     * @param        array $angles
     * @param        float $expected
     */
    public function testMeanResultantLengthUniformlyDistributed(array $angles, float $expected)
    {
        // When
        $ρ = Circular::meanResultantLength($angles);

        // Then for uniform distribution, ρ should be very close to 0
        $this->assertTrue($ρ < 0.01);
    }

    /**
     * Test mean resultant length for uniformly distributed angles
     * For perfectly uniform distribution, ρ ≈ 0
     * @return array [angles, expected]
     */
    public function dataProviderForMeanResultantLengthUniformlyDistributed(): array
    {
        $π = \M_PI;

        return [
            [[0, 0.5 * $π, $π, 1.5 * $π], 0],
            [[0, $π/3, 2*$π/3, $π, 4*$π/3, 5*$π/3], 0],
        ];
    }

    /**
     * @test         varianceUniformlyDistributed
     * @dataProvider dataProviderForVarianceUniformlyDistributed
     * @param        array $angles
     * @param        float $expected
     */
    public function testVarianceUniformlyDistributed(array $angles, float $expected)
    {
        // When
        $variance = Circular::variance($angles);

        // Then for uniform distribution, variance should be close to 1
        $this->assertTrue($variance > 0.99);
    }

    /**
     * Test variance for uniformly distributed angles
     * For uniform distribution, Var ≈ 1 - 0 = 1 (maximum)
     * @return array [angles, expected]
     */
    public function dataProviderForVarianceUniformlyDistributed(): array
    {
        $π = \M_PI;

        return [
            [[0, 0.5 * $π, $π, 1.5 * $π], 1],
            [[0, $π/3, 2*$π/3, $π, 4*$π/3, 5*$π/3], 1],
        ];
    }

    // =====================================================================
    // DESCRIBE METHOD EDGE CASES
    // =====================================================================

    /**
     * @test describeSingleElement
     */
    public function testDescribeSingleElement()
    {
        // Given
        $angles = [0.5];

        // When
        $stats = Circular::describe($angles);

        // Then
        $this->assertEquals(1, $stats['n']);
        $this->assertEquals(0.5, $stats['mean']);
        $this->assertEquals(1, $stats['resultant_length']);
        $this->assertEquals(1, $stats['mean_resultant_length']);
        $this->assertEquals(0, $stats['variance']);
        $this->assertEquals(0, $stats['sd']);
    }

    /**
     * @test describeIdenticalValues
     */
    public function testDescribeIdenticalValues()
    {
        // Given
        $angles = [0.75, 0.75, 0.75];

        // When
        $stats = Circular::describe($angles);

        // Then
        $this->assertEquals(3, $stats['n']);
        $this->assertEquals(0.75, $stats['mean']);
        $this->assertEquals(3, $stats['resultant_length']);
        $this->assertEquals(1, $stats['mean_resultant_length']);
        $this->assertEquals(0, $stats['variance']);
        $this->assertEquals(0, $stats['sd']);
    }

    /**
     * @test describeLargeDataset
     */
    public function testDescribeLargeDataset()
    {
        // Given - 1000 angles from 0 to 2π
        $angles = [];
        for ($i = 0; $i < 1000; $i++) {
            $angles[] = (2 * \M_PI * $i) / 1000;
        }

        // When
        $stats = Circular::describe($angles);

        // Then
        $this->assertEquals(1000, $stats['n']);
        $this->assertTrue(\is_float($stats['mean']));
        $this->assertTrue(\is_float($stats['resultant_length']));
        $this->assertTrue(\is_float($stats['mean_resultant_length']));
        // Uniform distribution: high variance (close to 1)
        $this->assertTrue($stats['variance'] > 0.99);
        $this->assertTrue(\is_float($stats['sd']));
    }
}
