<?php
namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\Poisson;

class PoissonTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pmf
     * @dataProvider dataProviderForPmf
     * @param        int $k
     * @param        float $λ
     * @param        float $expectedPmf
     */
    public function testPmf(int $k, float $λ, float $expectedPmf)
    {
        // Given
        $poisson = new Poisson($λ);

        // When
        $pmf = $poisson->pmf($k);

        // Then
        $this->assertEquals($expectedPmf, $pmf, '', 0.001);
    }

    /**
     * @return array [k, λ, pmf]
     */
    public function dataProviderForPmf(): array
    {
        return [
            [3, 2, 0.180],
            [3, 5, 0.140373895814280564513],
            [8, 6, 0.103257733530844],
            [2, 0.45, 0.065],
            [16, 12, 0.0542933401099791],
        ];
    }

    /**
     * @testCase     cdf
     * @dataProvider dataProviderForCdf
     * @param        int $k
     * @param        float $λ
     * @param        float $expectedCdf
     */
    public function testCdf(int $k, float $λ, float $expectedCdf)
    {
        // Given
        $poisson = new Poisson($λ);

        // When
        $cdf = $poisson->cdf($k);

        // Then
        $this->assertEquals($expectedCdf, $cdf, '', 0.001);
    }

    /**
     * @return array[k, λ, cdf]
     */
    public function dataProviderForCdf(): array
    {
        return [
            [3, 2, 0.857123460498547048662],
            [3, 5, 0.2650],
            [8, 6, 0.8472374939845613089968],
            [2, 0.45, 0.99],
            [16, 12, 0.898708992560164],
        ];
    }

    /**
     * @testCase     mean
     * @dataProvider dataProviderForMean
     * @param        float $λ
     * @param        float $μ
     */
    public function testMean(float $λ, float $μ)
    {
        // Given
        $poisson = new Poisson($λ);

        // When
        $mean = $poisson->mean();

        // Then
        $this->assertEquals($μ, $mean, '', 0.000001);
    }

    /**
     * @return array
     */
    public function dataProviderForMean(): array
    {
        return [
            [0.1, 0.1],
            [1, 1],
            [2, 2],
            [5.6, 5.6],
            [848, 848],
        ];
    }

    /**
     * @testCase     median
     * @dataProvider dataProviderForMedian
     * @param        float $λ
     * @param        float $expected
     */
    public function testMedian(float $λ, float $expected)
    {
        // Given
        $poisson = new Poisson($λ);

        // When
        $median = $poisson->median();

        // Then
        $this->assertEquals($expected, $median, '', 0.000001);
    }

    /**
     * @return array
     */
    public function dataProviderForMedian(): array
    {
        return [
            [0.1, 0],
            [1, 1],
            [2, 2],
            [5.6, 5],
            [848, 848],
        ];
    }

    /**
     * @testCase     mode
     * @dataProvider dataProviderForMode
     * @param        float $λ
     * @param        array $expected
     */
    public function testMode(float $λ, array $expected)
    {
        // Given
        $poisson = new Poisson($λ);

        // When
        $mode = $poisson->mode();

        // Then
        $this->assertEquals($expected, $mode, '', 0.000001);
    }

    /**
     * @return array
     */
    public function dataProviderForMode(): array
    {
        return [
            [0.1, [0, 0]],
            [1, [0, 1]],
            [2, [1, 2]],
            [5.6, [5, 5]],
            [848, [847, 848]],
        ];
    }

    /**
     * @testCase     variance
     * @dataProvider dataProviderForVariance
     * @param        float $λ
     * @param        float $σ²
     */
    public function testVariance(float $λ, float $σ²)
    {
        // Given
        $poisson = new Poisson($λ);

        // When
        $variance = $poisson->variance();

        // Then
        $this->assertEquals($σ², $variance, '', 0.000001);
    }

    /**
     * @return array
     */
    public function dataProviderForVariance(): array
    {
        return [
            [0.1, 0.1],
            [1, 1],
            [2, 2],
            [5.6, 5.6],
            [848, 848],
        ];
    }
}
