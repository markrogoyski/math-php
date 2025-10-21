<?php

namespace MathPHP\Tests\Statistics;

use MathPHP\Statistics\Correlation;
use MathPHP\Exception;

class CorrelationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         covariance - population covariance
     * @dataProvider dataProviderForPopulationCovariance
     * @param        array $X
     * @param        array $Y
     * @param        float $expected
     * @throws       \Exception
     */
    public function testCovariancePopulation(array $X, array $Y, float $expected)
    {
        // When
        $covariance = Correlation::covariance($X, $Y, true);

        // Then
        $this->assertEqualsWithDelta($expected, $covariance, 0.01);
    }

    /**
     * @test         populationCovariance
     * @dataProvider dataProviderForPopulationCovariance
     * @param        array $X
     * @param        array $Y
     * @param        float $expected
     * @throws       \Exception
     */
    public function testPopulationCovariance(array $X, array $Y, float $expected)
    {
        // When
        $covariance = Correlation::populationCovariance($X, $Y);

        // Then
        $this->assertEqualsWithDelta($expected, $covariance, 0.01);
    }

    /**
     * Data provider for population covariance test
     * @return array [X, Y, covariance]
     */
    public function dataProviderForPopulationCovariance(): array
    {
        return [
            [ [ 1, 2, 3, 4 ], [ 2, 3, 4, 5 ], 1.25 ],
            [ [ 1, 2, 4, 7, 9, 10 ], [ 2, 3, 5, 8, 11, 12.5 ], 13.29167 ],
            [ [ 1, 3, 2, 5, 8, 7, 12, 2, 4], [ 8, 6, 9, 4, 3, 3, 2, 7, 7 ], -7.1728 ],
        ];
    }

    /**
     * @test         weightedCovariance
     * @dataProvider dataProviderForWeightedCovariance
     * @param        array $X
     * @param        array $Y
     * @param        array $w
     * @param        float $expected
     * @throws       \Exception
     */
    public function testWeightedCovariance(array $X, array $Y, array $w, float $expected)
    {
        // When
        $covariance = Correlation::weightedCovariance($X, $Y, $w);

        // Then
        $this->assertEqualsWithDelta($expected, $covariance, 0.001);
    }

    /**
     * Data provider for weighted covariance test
     * @return array [X, Y, w, covariance]
     */
    public function dataProviderForWeightedCovariance(): array
    {
        return [
            [ [ 1, 2, 3, 4 ], [ 2, 3, 4, 5 ], [ 1, 1, 1, 1 ], 1.25 ],
            [ [ 1, 2, 4, 7, 9, 10 ], [ 2, 3, 5, 8, 11, 12.5 ],  [ 1.0, 1, 1, 1, 1, 1 ], 13.29167 ],
            [ [ 1, 3, 2, 5, 8, 7, 12, 2, 4], [ 8, 6, 9, 4, 3, 3, 2, 7, 7 ], [ 1, 1, 1, 1, 1, 1, 1, 1, 1 ], -7.1728 ],
            [ [1.4, 1.9, 2.6, 0.3, 0.3, 0.8, 1, 2.5, 2.9, 0.9], [2.6, 3, 1.4, 1.5, 2.8, 1.9, 1.9, 0.6, 2.0, 2.1], [0.87, 0.68, 0.69, 0.83, 0.17, 0.56, 0.01, 0.79, 0.26, 0.72], -0.179768 ],
            [ [9, 18, 10, 29, 22], [2, 11, 5, 12, 21], [8, 15, 10, 0, 6], 29.0178]
        ];
    }

    /**
     * @test         weightedCovariance throws a BadDataException if the counts of any of the arrays are different
     * @dataProvider dataProviderForWeightedCovarianceException
     * @param        array $X
     * @param        array $Y
     * @param        array $w
     * @throws       Exception\BadDataException
     */
    public function testWeightedCovarianceException(array $X, array $Y, array $w)
    {
        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Correlation::weightedCovariance($X, $Y, $w);
    }

    /**
     * @return array [X, Y, weights]
     */
    public function dataProviderForWeightedCovarianceException(): array
    {
        return [
            [
                [1, 2, 3],
                [2, 3],
                [1, 1, 1],
            ],
            [
                [1, 2, 3],
                [2, 3, 4],
                [1, 1,],
            ],
        ];
    }

    /**
     * @test     populationCovariance when X and Y have different counts
     * @throws   \Exception
     */
    public function testPopulationCovarianceExceptionWhenXAndYHaveDifferentCounts()
    {
        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Correlation::populationCovariance([ 1, 2 ], [ 2, 3, 4 ]);
    }

    /**
     * @test         covariance - sample covariance
     * @dataProvider dataProviderForSampleCovariance
     * @param        array $X
     * @param        array $Y
     * @param        float $expected
     * @throws       \Exception
     */
    public function testCovarianceSample(array $X, array $Y, float $expected)
    {
        // When
        $covariance = Correlation::covariance($X, $Y);

        // Then
        $this->assertEqualsWithDelta($expected, $covariance, 0.01);
    }

    /**
     * @test         sampleCoveriance
     * @dataProvider dataProviderForSampleCovariance
     * @param        array $X
     * @param        array $Y
     * @param        float $expected
     * @throws       \Exception
     */
    public function testSampleCovariance(array $X, array $Y, float $expected)
    {
        // When
        $covariance = Correlation::sampleCovariance($X, $Y);

        // Then
        $this->assertEqualsWithDelta($expected, $covariance, 0.01);
    }

    /**
     * Data provider for sample covariance test
     * @return array [X, Y, covariance]
     */
    public function dataProviderForSampleCovariance(): array
    {
        return [
            [ [ 1, 2, 3, 4 ], [ 2, 3, 4, 5 ], 1.66667 ],
            [ [ 1, 2, 4, 7, 9, 10 ], [ 2, 3, 5, 8, 11, 12.5 ], 15.95 ],
            [ [ 1, 3, 2, 5, 8, 7, 12, 2, 4], [ 8, 6, 9, 4, 3, 3, 2, 7, 7 ], -8.0694 ],
        ];
    }

    /**
     * @test     sampleCovariance when X and Y have different counts
     * @throws   \Exception
     */
    public function testSampleCovarianceExceptionWhenXAndYHaveDifferentCounts()
    {
        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Correlation::sampleCovariance([ 1, 2 ], [ 2, 3, 4 ]);
    }

    /**
     * @test         r - poluation
     * @dataProvider dataProviderForPopulationCorrelationCoefficient
     * @param        array $x
     * @param        array $y
     * @param        float $expected
     * @throws       \Exception
     */
    public function testRPopulation(array $x, array $y, float $expected)
    {
        // When
        $r = Correlation::r($x, $y, true);

        // Then
        $this->assertEqualsWithDelta($expected, $r, 0.000001);
    }

    /**
     * @test         populationCorrelationCoefficient
     * @dataProvider dataProviderForPopulationCorrelationCoefficient
     * @param        array $x
     * @param        array $y
     * @param        float $expected
     * @throws       \Exception
     */
    public function testPopulationCorrelationCoefficient(array $x, array $y, float $expected)
    {
        // When
        $pcc = Correlation::populationCorrelationCoefficient($x, $y);

        // Then
        $this->assertEqualsWithDelta($expected, $pcc, 0.000001);
    }

    /**
     * Data generated with R: cor(x, y, method="pearson)
     * Data generated with numpy.corrcoef(X, Y)[0,1]
     * @return array [x, y, ppc]
     */
    public function dataProviderForPopulationCorrelationCoefficient(): array
    {
        return [
            // Data generated with R cor(x, y, method="pearson)
            [
                [1, 2, 4, 5, 8],
                [5, 20, 40, 80, 100],
                0.9684134
            ],
            [
                [1, 2, 4, 5, 8],
                [5, 20, 30, 50, 120],
                0.963586
            ],
            [
                [106, 100, 86, 101, 99, 103, 97, 113, 112, 110],
                [7, 27, 2, 50, 28, 29, 20, 12, 6, 17],
                -0.07021633
            ],
            [
                [0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10],
                [1.6, 2.7, 4.5, 7.4, 12.2, 20.1, 33.1, 54.6, 90, 148.4, 244.7, 403.4, 665.1, 1096.6, 1808.0, 2981.0, 4914.8, 8103.1, 13359.7, 22026.5],
                0.6990668
            ],
            [
                [679.10, 818.93, 302.38, 1149.60, 573.14, 1034.55, 633.25, 1095.42, 1122.58, 686.51, 1172.84, 593.70, 1247.95, 533.99, 605.51, 696.96, 1282.95, 531.16, 788.36, 956.06, 1149.38, 1069.82, 1124.17],
                [.80, 1.93, .97, 11.80, 1.41, 2.41, 3.40, .98, 2.46, .26, 9.97, .37, 6.70, .09, 1.72, 6.76, 10.27, .13, 2.87, 3.10, .96, 3.77, 7.09],
                0.6323985
            ],
            [
                [1760, 2040, 2440, 2550, 2730, 2740, 3010, 3080, 3370, 3740, 4910, 5090, 5090, 5380, 5850, 6730, 6990, 7960],
                [529, 566, 473, 461, 465, 532, 484, 527, 488, 485, 478, 434, 468, 449, 425, 389, 421, 416],
                -0.8203196
            ],
            [
                [99, 120, 98, 102, 123, 105, 85, 110, 117, 90],
                [2, 0, 25, 45, 14, 20, 15, 19, 22, 4],
                -0.03609958
            ],
            [
                [35, 23, 47, 17, 10, 43, 9, 6, 28],
                [30, 33, 45, 23, 8, 49, 12, 4, 31],
                0.9498663
            ],
            [
                [50, 175, 270, 375, 425, 580, 710, 790, 890, 980],
                [1.80, 1.20, 2.00, 1.00, 1.00, 1.20, 0.80, 0.60, 1.00, 0.85],
                -0.7271081
            ],
            [
                [2.5, 2.5, 2.5, 3, 3, 2.5, 2.25, 2.75, 2, 2.75],
                [2.25, 2.75, 2.75, 2.25, 2.25, 3.25, 2, 2, 2.75, 1.25],
                -0.3676942
            ],
            [
                [2.5, 2.5, 2.5, 3, 3, 2.5, 2.25, 2.75, 2, 2.75],
                [6, 7, 8, 3, 6, 5, 4, 6, 6, 4],
                -0.264683
            ],
            [
                [2.5, 2.5, 2.5, 3, 3, 2.5, 2.25, 2.75, 2, 2.75],
                [4, 6, 3, 2, 9, 7, 8, 9, 5, 4],
                -0.003555568
            ],
            [
                [2.25, 2.75, 2.75, 2.25, 2.25, 3.25, 2, 2, 2.75, 1.25],
                [6, 7, 8, 3, 6, 5, 4, 6, 6, 4],
                0.4640445
            ],
            [
                [2.25, 2.75, 2.75, 2.25, 2.25, 3.25, 2, 2, 2.75, 1.25],
                [4, 6, 3, 2, 9, 7, 8, 9, 5, 4],
                0.004007348
            ],
            [
                [6, 7, 8, 3, 6, 5, 4, 6, 6, 4],
                [4, 6, 3, 2, 9, 7, 8, 9, 5, 4],
                0.1032071
            ],
            // Floating-point precision edge cases
            // Generated using numpy.corrcoef(X, Y)[0,1]
            // Repeated decimal multiplication (0.1 and 0.2)
            [
                [0.1, 0.2, 0.30000000000000004, 0.4, 0.5, 0.6000000000000001, 0.7000000000000001, 0.8, 0.9, 1.0],
                [0.2, 0.4, 0.6000000000000001, 0.8, 1.0, 1.2000000000000002, 1.4000000000000001, 1.6, 1.8, 2.0],
                1.00000000,
            ],
            // Very small values (1e-10 scale)
            [
                [1e-10, 2e-10, 3e-10, 4e-10, 5e-10],
                [5e-10, 4e-10, 3e-10, 2e-10, 1e-10],
                -1.00000000,
            ],
            // Mix of very different magnitudes (1e-5 to 1e5)
            [
                [1e-05, 2e-05, 100000.0, 200000.0, 300000.0],
                [1.0, 2.0, 3.0, 4.0, 5.0],
                0.97014250,
            ],
        ];
    }

    /**
     * @test         weightedCorrelationCoefficient
     * @dataProvider dataProviderForWeightedCorrelationCoefficient
     * @param        array $x
     * @param        array $y
     * @param        array $w
     * @param        float $expected
     * @throws       \Exception
     */
    public function testWeightedCorrelationCoefficient(array $x, array $y, array $w, float $expected)
    {
        // When
        $wcc = Correlation::weightedCorrelationCoefficient($x, $y, $w);

        // Then
        $this->assertEqualsWithDelta($expected, $wcc, 0.00001);
    }

    /**
     * Data provider for weighted correlation coefficient test
     * Test data created using R package wCorr: weightedCorr(x, y, weights = w, method = "Pearson")
     * @return array [x, y, w, wcc]
     */
    public function dataProviderForWeightedCorrelationCoefficient(): array
    {
        return [
            [ [ 1, 2, 4, 5, 8 ], [ 5, 20, 40, 80, 100 ], [1, 1, 1, 1, 1], 0.9684134 ],
            [ [ 1, 2, 4, 5, 8 ], [ 5, 20, 30, 50, 120 ], [1, 1, 1, 1, 1], 0.963586 ],
            [ [ 1, 2, 4, 5, 8 ], [ 5, 20, 30, 50, 120 ], [0.2, 0.3, 0.2, 0.2, 0.1], 0.9510173 ],
            [ [1.1, 1.6, 1.7, 2.3, 1.3], [1.7, 0.5, 1.7, 0.3, 1.2], [1.14, 0.88, 0.64, 1.78, 1.64], -0.8127747],
            [[9, 18, 10, 29, 22], [2, 11, 5, 12, 21], [8, 15, 10, 0, 6], 0.9490861]
        ];
    }

    /**
     * @test         r - sample
     * @dataProvider dataProviderForSampleCorrelationCoefficient
     * @param        array $x
     * @param        array $y
     * @param        float $expected
     * @throws       \Exception
     */
    public function testRSample(array $x, array $y, float $expected)
    {
        // When
        $scc = Correlation::r($x, $y);

        // Then
        $this->assertEqualsWithDelta($expected, $scc, 0.0001);
    }

    /**
     * @test         sampleCorrelationCoefficient
     * @dataProvider dataProviderForSampleCorrelationCoefficient
     * @param        array $x
     * @param        array $y
     * @param        float $expected
     * @throws       \Exception
     */
    public function testSampleCorrelationCoefficient(array $x, array $y, float $expected)
    {
        // When
        $scc = Correlation::sampleCorrelationCoefficient($x, $y);

        // Then
        $this->assertEqualsWithDelta($expected, $scc, 0.0001);
    }

    /**
     * Data provider for sample correlation coefficient test
     * @return array [x, y, ppc]
     */
    public function dataProviderForSampleCorrelationCoefficient(): array
    {
        return [
            [ [ 1, 2, 4, 5, 8 ], [ 5, 20, 40, 80, 100 ], 0.9684 ],
            [ [ 1, 2, 4, 5, 8 ], [ 5, 20, 30, 50, 120 ], 0.9636 ],
        ];
    }

    /**
     * @test         coefficientOfDetermination
     * @dataProvider dataProviderForR2
     * @param        array $X
     * @param        array $Y
     * @param        float $expected
     * @throws       \Exception
     */
    public function testCoefficientOfDetermination(array $X, array $Y, float $expected)
    {
        // When
        $r2 = Correlation::coefficientOfDetermination($X, $Y);

        // Then
        $this->assertEqualsWithDelta($expected, $r2, 0.001);
    }

    /**
     * @test         r2
     * @dataProvider dataProviderForR2
     * @param        array $X
     * @param        array $Y
     * @param        float $expected
     * @throws       \Exception
     */
    public function testR2(array $X, array $Y, float $expected)
    {
        // When
        $r2 = Correlation::r2($X, $Y);

        // Then
        $this->assertEqualsWithDelta($expected, $r2, 0.000001);
    }

    /**
     * Test data generated with Python numpy: np.corrcoef(x, y)[0, 1]**2
     * @return array [X, Y, r2]
     */
    public function dataProviderForR2(): array
    {
        return [
            [
                [1, 2, 4, 5, 6],
                [2, 3, 5, 7, 8],
                0.98613595706619
            ],
            [
                [4, 9, 10, 14, 4, 7, 12, 22, 1, 3, 8, 11, 5, 6, 10, 11, 16, 13, 13, 10],
                [390, 580, 650, 730, 410, 530, 600, 790, 350, 400, 590, 640, 450, 520, 690, 690, 770, 700, 730, 640],
                0.8716192582293918
            ],
            [
                [1, 2, 3],
                [1, 5, 25],
                0.8709677419354838
            ],
            [
                [2.5, 2.5, 2.5, 3, 3, 2.5, 2.25, 2.75, 2, 2.75],
                [2.25, 2.75, 2.75, 2.25, 2.25, 3.25, 2, 2, 2.75, 1.25],
                0.13519902881605012
            ],
            [
                [2.5, 2.5, 2.5, 3, 3, 2.5, 2.25, 2.75, 2, 2.75],
                [6, 7, 8, 3, 6, 5, 4, 6, 6, 4],
                0.07005708354955892
            ],
            [
                [2.5, 2.5, 2.5, 3, 3, 2.5, 2.25, 2.75, 2, 2.75],
                [4, 6, 3, 2, 9, 7, 8, 9, 5, 4],
                1.2642065207772269e-05
            ],
            [
                [2.25, 2.75, 2.75, 2.25, 2.25, 3.25, 2, 2, 2.75, 1.25],
                [6, 7, 8, 3, 6, 5, 4, 6, 6, 4],
                0.21533728850802022
            ],
            [
                [2.25, 2.75, 2.75, 2.25, 2.25, 3.25, 2, 2, 2.75, 1.25],
                [4, 6, 3, 2, 9, 7, 8, 9, 5, 4],
                1.6058839588251395e-05
            ],
            [
                [6, 7, 8, 3, 6, 5, 4, 6, 6, 4],
                [4, 6, 3, 2, 9, 7, 8, 9, 5, 4],
                0.010651710795182817
            ],
        ];
    }

    /**
     * @test         kendallsTau
     * @dataProvider dataProviderForKendallsTau
     * @param        array $X
     * @param        array $Y
     * @param        float $expected
     * @throws       \Exception
     */
    public function testKendallsTau(array $X, array $Y, float $expected)
    {
        // When
        $τ = Correlation::kendallsTau($X, $Y);

        // Then
        $this->assertEqualsWithDelta($expected, $τ, 0.00001);
    }

    /**
     * Test data generated with R: cor(x, y, method="kendall")
     * Test data generated with scipy.stats.kendalltau(X, Y)
     * @return array [X, Y, τ]
     */
    public function dataProviderForKendallsTau(): array
    {
        return [
            // Generated with R: cor(x, y, method="kendall")
            // No ties for tau-a
            [
                [1, 2, 5, 3, 4],
                [1, 4, 2, 3, 5],
                0.2,
            ],
            [
                [2, 4, 7, 9],
                [4, 8, 7, 9],
                0.666667,
            ],
            [
                [5, 4, 3, 2],
                [4, 5, 6, 7],
                -1,
            ],
            [
                [85, 98, 90, 83, 57, 63, 77, 99, 80, 96, 69],
                [85, 95, 80, 75, 70, 65, 73, 93, 79, 88, 74],
                0.8181818,
            ],
            // Ties for tau-b
            [
                [4, 5, 5, 6, 5, 8],
                [4, 6, 7, 8, 7, 8],
                0.8807048,
            ],
            [
                [12, 14, 14, 17, 19, 19, 19, 19, 19, 20, 21, 21, 21, 21, 21, 22, 23, 24, 24, 24, 26, 26, 27],
                [11, 4, 4, 2, 0, 0, 0, 0, 0, 0, 4, 0, 4, 0, 0, 0, 0, 4, 0, 0, 0, 0, 0],
                -0.3762015,
            ],
            [
                [0.7, 0.8, 0.8, 0.8, 1.2, 1.3, 1.6, 1.8, 1.9, 2.4, 2.5, 2.7, 2.9, 2.9, 3.9, 5.8, 6.5, 7.9, 9.1],
                [300, 211, 227, 297, 199, 285, 207, 167, 266, 191, 211, 172, 131, 220, 167, 115, 86, 107, 71],
                -0.6964409
            ],
            [
                [2.5, 2.5, 2.5, 3, 3, 2.5, 2.25, 2.75, 2, 2.75],
                [2.25, 2.75, 2.75, 2.25, 2.25, 3.25, 2, 2, 2.75, 1.25],
                -0.2666904
            ],
            [
                [2.5, 2.5, 2.5, 3, 3, 2.5, 2.25, 2.75, 2, 2.75],
                [6, 7, 8, 3, 6, 5, 4, 6, 6, 4],
                -0.2133523
            ],
            [
                [2.5, 2.5, 2.5, 3, 3, 2.5, 2.25, 2.75, 2, 2.75],
                [4, 6, 3, 2, 9, 7, 8, 9, 5, 4],
                -0.02507061
            ],
            [
                [2.25, 2.75, 2.75, 2.25, 2.25, 3.25, 2, 2, 2.75, 1.25],
                [6, 7, 8, 3, 6, 5, 4, 6, 6, 4],
                0.3684211
            ],
            [
                [2.25, 2.75, 2.75, 2.25, 2.25, 3.25, 2, 2, 2.75, 1.25],
                [4, 6, 3, 2, 9, 7, 8, 9, 5, 4],
                -0.04947707
            ],
            [
                [6, 7, 8, 3, 6, 5, 4, 6, 6, 4],
                [4, 6, 3, 2, 9, 7, 8, 9, 5, 4],
                0.0742156
            ],
            // Floating-point precision edge cases
            // Generated using scipy.stats.kendalltau(X, Y)
            // Floating-point arithmetic: 0.1 + 0.2 vs 0.3 (should be treated as tie)
            [
                [0.30000000000000004, 0.3, 0.5, 0.7, 0.9],
                [1.0, 2.0, 3.0, 4.0, 5.0],
                0.80000000,
            ],
            // Very close values: 1.0 vs 1.0000000001
            [
                [1.0, 1.0000000001, 2.0, 3.0, 4.0],
                [10.0, 20.0, 30.0, 40.0, 50.0],
                1.00000000,
            ],
            // Multiple near-ties in both X and Y
            [
                [1.1, 1.1000001, 2.2, 2.2000001, 3.3],
                [5.5, 5.5000001, 6.6, 6.6000001, 7.7],
                1.00000000,
            ],
            // Accumulated floating-point errors from repeated multiplication by 0.1
            [
                [0.1, 0.2, 0.30000000000000004, 0.4, 0.5, 0.6000000000000001, 0.7000000000000001, 0.8, 0.9, 1.0],
                [0.3, 0.6, 0.8999999999999999, 1.2, 1.5, 1.7999999999999998, 2.1, 2.4, 2.6999999999999997, 3.0],
                1.00000000,
            ],
            // Mix of exact ties and near-ties
            [
                [1.0, 1.0, 2.0, 2.0000000001, 3.0, 3.0],
                [10.0, 10.0000000001, 20.0, 20.0, 30.0, 30.0],
                0.92307692,
            ],
            // Negative correlation with division-generated floats (1/3, 2/3, etc.)
            [
                [0.3333333333333333, 0.6666666666666666, 1.0, 1.3333333333333333, 1.6666666666666667],
                [5.0, 4.0, 3.0, 2.0, 1.0],
                -1.00000000,
            ],
            // Values from transcendental functions (sin and cos)
            [
                [0.8414709848078965, 0.9092974268256817, 0.1411200080598672, -0.7568024953079283, -0.9589242746631385],
                [0.5403023058681398, -0.4161468365471424, -0.9899924966004454, -0.6536436208636119, 0.28366218546322625],
                0.00000000,
            ],
            // Square roots (irrational numbers with representation limits)
            [
                [1.4142135623730951, 1.7320508075688772, 2.23606797749979, 2.6457513110645907, 3.3166247903554],
                [1.0, 2.0, 3.0, 4.0, 5.0],
                1.00000000,
            ],
        ];
    }

    /**
     * @test     kendallsTau with different length arrays
     * @throws   \Exception
     */
    public function testKendallsTauExceptionDifferentLengthArrays()
    {
        // Given
        $X = [1, 2, 3];
        $Y = [2, 3, 4, 5];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Correlation::kendallsTau($X, $Y);
    }

    /**
     * @test         spearmansRho
     * @dataProvider dataProviderForSpearmansRho
     * @param        array $X
     * @param        array $Y
     * @param        float $expected
     * @throws       \Exception
     */
    public function testSpearmansRho(array $X, array $Y, float $expected)
    {
        // When
        $ρ = Correlation::spearmansRho($X, $Y);

        // Then
        $this->assertEqualsWithDelta($expected, $ρ, 0.00001);
    }

    /**
     * Data generated with R: cor(X, Y, method="spearman")
     * Data generated with enerated using scipy.stats.spearmanr(X, Y)
     * Data from various online sources
     * @return array [X, Y, ρ]
     */
    public function dataProviderForSpearmansRho(): array
    {
        return [
            [
                [56, 75, 45, 71, 62, 64, 58, 80, 76, 61],
                [66, 70, 40, 60, 65, 56, 59, 77, 67, 63],
                0.6727273
            ],
            [
                [1, 2, 3, 4, 5],
                [2, 3, 4, 4, 6],
                0.9746794
            ],
            [
                [4, 10, 3, 1, 9, 2, 6, 7, 8, 5],
                [5, 8, 6, 2, 10, 3, 9, 4, 7, 1],
                0.6848485
            ],
            [
                [13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25],
                [26, 25, 18, 33, 70, 55, 50, 49, 70, 80, 76, 74, 73],
                0.8583227
            ],
            [
                [1, 5, 2, 5, 2],
                [2, 2, 3, 1, 3],
                -0.4722222
            ],
            // Wikipedia test case (https://en.wikipedia.org/wiki/Spearman%27s_rank_correlation_coefficient)
            [
                [106, 100, 86, 101, 99, 103, 97, 113, 112, 110],
                [7, 27, 2, 50, 28, 29, 20, 12, 6, 17],
                -0.1757576
            ],
            // http://www.statstutor.ac.uk/resources/uploaded/spearmans.pdf
            [
                [0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5, 9, 9.5, 10],
                [1.6, 2.7, 4.5, 7.4, 12.2, 20.1, 33.1, 54.6, 90, 148.4, 244.7, 403.4, 665.1, 1096.6, 1808.0, 2981.0, 4914.8, 8103.1, 13359.7, 22026.5],
                1
            ],
            [
                [679.10, 818.93, 302.38, 1149.60, 573.14, 1034.55, 633.25, 1095.42, 1122.58, 686.51, 1172.84, 593.70, 1247.95, 533.99, 605.51, 696.96, 1282.95, 531.16, 788.36, 956.06, 1149.38, 1069.82, 1124.17],
                [.80, 1.93, .97, 11.80, 1.41, 2.41, 3.40, .98, 2.46, .26, 9.97, .37, 6.70, .09, 1.72, 6.76, 10.27, .13, 2.87, 3.10, .96, 3.77, 7.09],
                0.708498
            ],
            // http://www.biostathandbook.com/spearman.html
            [
                [1760, 2040, 2440, 2550, 2730, 2740, 3010, 3080, 3370, 3740, 4910, 5090, 5090, 5380, 5850, 6730, 6990, 7960],
                [529, 566, 473, 461, 465, 532, 484, 527, 488, 485, 478, 434, 468, 449, 425, 389, 421, 416],
                -0.7630357
            ],
            // http://www.real-statistics.com/correlation/spearmans-rank-correlation/
            [
                [99, 120, 98, 102, 123, 105, 85, 110, 117, 90],
                [2, 0, 25, 45, 14, 20, 15, 19, 22, 4],
                -0.1151515
            ],
            // https://www.worldsupporter.org/en/chapter/66927-example-how-calculate-spearman-correlation
            [
                [35, 23, 47, 17, 10, 43, 9, 6, 28],
                [30, 33, 45, 23, 8, 49, 12, 4, 31],
                0.9
            ],
            [
                [50, 175, 270, 375, 425, 580, 710, 790, 890, 980],
                [1.80, 1.20, 2.00, 1.00, 1.00, 1.20, 0.80, 0.60, 1.00, 0.85],
                -0.7570127
            ],
            // Github issue 380 test cases
            [
                [2.5, 2.5, 2.5, 3, 3, 2.5, 2.25, 2.75, 2, 2.75],
                [2.25, 2.75, 2.75, 2.25, 2.25, 3.25, 2, 2, 2.75, 1.25],
                -0.3721858
            ],
            [
                [2.5, 2.5, 2.5, 3, 3, 2.5, 2.25, 2.75, 2, 2.75],
                [6, 7, 8, 3, 6, 5, 4, 6, 6, 4],
                -0.254073
            ],
            [
                [2.5, 2.5, 2.5, 3, 3, 2.5, 2.25, 2.75, 2, 2.75],
                [4, 6, 3, 2, 9, 7, 8, 9, 5, 4],
                -0.01266457
            ],
            [
                [2.25, 2.75, 2.75, 2.25, 2.25, 3.25, 2, 2, 2.75, 1.25],
                [6, 7, 8, 3, 6, 5, 4, 6, 6, 4],
                0.4709775
            ],
            [
                [2.25, 2.75, 2.75, 2.25, 2.25, 3.25, 2, 2, 2.75, 1.25],
                [4, 6, 3, 2, 9, 7, 8, 9, 5, 4],
                -0.1410998
            ],
            [
                [6, 7, 8, 3, 6, 5, 4, 6, 6, 4],
                [4, 6, 3, 2, 9, 7, 8, 9, 5, 4],
                0.1009871
            ],
            // Floating-point precision edge cases
            // Generated using scipy.stats.spearmanr(X, Y)
            // Floating-point arithmetic: 0.1 + 0.2 = 0.30000000000000004 vs 0.3
            [
                [0.30000000000000004, 0.3, 0.5, 0.7, 0.9],
                [1.0, 2.0, 3.0, 4.0, 5.0],
                0.9,
            ],
            // Very close values: 1.0 vs 1.0000000001
            [
                [1.0, 1.0000000001, 2.0, 3.0, 4.0, 5.0],
                [10.0, 20.0, 30.0, 40.0, 50.0, 60.0],
                1.00000000,
            ],
            // Division-generated floats (fractions with 3 and 7)
            [
                [0.3333333333333333, 0.6666666666666666, 1.0, 1.3333333333333333, 1.6666666666666667, 2.0],
                [0.14285714285714285, 0.2857142857142857, 0.42857142857142855, 0.5714285714285714, 0.7142857142857143, 0.8571428571428571],
                1.00000000,
            ],
            // Irrational numbers (sqrt and pi multiples)
            [
                [1.0, 1.4142135623730951, 1.7320508075688772, 2.0, 2.23606797749979, 2.449489742783178, 2.6457513110645907],
                [3.141592653589793, 6.283185307179586, 9.42477796076938, 12.566370614359172, 15.707963267948966, 18.84955592153876, 21.991148575128552],
                1.00000000,
            ],
        ];
    }

    /**
     * @test     spearmansRho with different length arrays
     * @throws   \Exception
     */
    public function testSpearmansRhoExceptionDifferentLengthArrays()
    {
        // Given
        $X = [1, 2, 3];
        $Y = [2, 3, 4, 5];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Correlation::spearmansRho($X, $Y);
    }

    /**
     * @test     describe
     * @throws   \Exception
     */
    public function testDescribe()
    {
        // Given
        $X = [1, 2, 3, 4, 5];
        $Y = [2, 3, 4, 4, 6];

        // When
        $stats = Correlation::describe($X, $Y);

        // Then
        $this->assertTrue(\is_array($stats));
        $this->assertArrayHasKey('cov', $stats);
        $this->assertArrayHasKey('r', $stats);
        $this->assertArrayHasKey('r2', $stats);
        $this->assertArrayHasKey('tau', $stats);
        $this->assertArrayHasKey('rho', $stats);
        $this->assertTrue(\is_numeric($stats['cov']));
        $this->assertTrue(\is_numeric($stats['r']));
        $this->assertTrue(\is_numeric($stats['r2']));
        $this->assertTrue(\is_numeric($stats['tau']));
        $this->assertTrue(\is_numeric($stats['rho']));
    }

    /**
     * @test         confidenceEllipse
     * @dataProvider dataProviderForEllipse
     * @param        array $data
     * @param        float $sd
     * @param        array $results
     * @throws       \Exception
     */
    public function testEllipse(array $data, float $sd, array $results)
    {
        // When
        $calc = Correlation::confidenceEllipse(\array_column($data, 0), \array_column($data, 1), $sd);

        // Then
        $this->assertEqualsWithDelta($results, $calc, 0.0001);
    }

    /**
     * @return array [data, sd, results]
     */
    public function dataProviderForEllipse(): array
    {
        return [
            [
                [
                    [1.00787, 1.09905],
                    [1.23724, 0.98834],
                    [1.02175, 0.67245],
                    [0.88458, 0.36003],
                    [0.66582, 1.22097],
                    [1.24408, 0.59735],
                    [1.03421, 0.88595],
                    [1.66279, 0.84183],
                ],
                1,
                [
                    [1.47449429236742, 0.555004169940273],
                    [1.54091626950741, 0.797745563446301],
                    [1.43693412988479, 1.05404701259189],
                    [1.20226551661247, 1.22601007516927],
                    [0.926545863867666, 1.24795070608341],
                    [0.71509070763258, 1.11148833005973],
                    [0.648668730492593, 0.868746936553699],
                    [0.752650870115211, 0.612445487408114],
                    [0.987319483387533, 0.440482424830733],
                    [1.26303913613233, 0.418541793916591],
                    [1.47449429236742, 0.555004169940273],
                ],
            ],
        ];
    }

    /**
     * @test populationCovariance with empty arrays
     * @throws \Exception
     */
    public function testPopulationCovarianceEmptyArrays()
    {
        // Given
        $X = [];
        $Y = [1, 2, 3];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Correlation::populationCovariance($X, $Y);
    }

    /**
     * @test sampleCovariance with empty arrays
     * @throws \Exception
     */
    public function testSampleCovarianceEmptyArrays()
    {
        // Given
        $X = [];
        $Y = [1, 2, 3];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Correlation::sampleCovariance($X, $Y);
    }

    /**
     * @test weightedCovariance with empty X array
     * @throws \Exception
     */
    public function testWeightedCovarianceEmptyArrays()
    {
        // Given
        $X = [];
        $Y = [1, 2, 3];
        $w = [1, 1, 1];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Correlation::weightedCovariance($X, $Y, $w);
    }

    /**
     * @test populationCorrelationCoefficient with empty arrays
     * @throws \Exception
     */
    public function testPopulationCorrelationCoefficientEmptyArrays()
    {
        // Given
        $X = [];
        $Y = [1, 2, 3];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Correlation::populationCorrelationCoefficient($X, $Y);
    }

    /**
     * @test sampleCorrelationCoefficient with empty arrays
     * @throws \Exception
     */
    public function testSampleCorrelationCoefficientEmptyArrays()
    {
        // Given
        $X = [];
        $Y = [1, 2, 3];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Correlation::sampleCorrelationCoefficient($X, $Y);
    }

    /**
     * @test kendallsTau with empty arrays
     * @throws \Exception
     */
    public function testKendallsTauEmptyArrays()
    {
        // Given
        $X = [];
        $Y = [1, 2, 3];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Correlation::kendallsTau($X, $Y);
    }

    /**
     * @test spearmansRho with empty arrays
     * @throws \Exception
     */
    public function testSpearmansRhoEmptyArrays()
    {
        // Given
        $X = [];
        $Y = [1, 2, 3];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Correlation::spearmansRho($X, $Y);
    }

    /**
     * @test sampleCorrelationCoefficient with single element arrays
     * Single element arrays have undefined sample standard deviation (division by n-1=0)
     * @throws \Exception
     */
    public function testSampleCorrelationCoefficientSingleElement()
    {
        // Given
        $X = [1];
        $Y = [2];

        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        Correlation::sampleCorrelationCoefficient($X, $Y);
    }

    /**
     * @test sampleCovariance with single element arrays
     * Single element results in division by n-1=0
     * @throws \Exception
     */
    public function testSampleCovarianceSingleElement()
    {
        // Given
        $X = [1];
        $Y = [2];

        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        Correlation::sampleCovariance($X, $Y);
    }

    /**
     * @test Correlation coefficient with constant arrays (zero variance)
     * Zero variance results in division by zero in correlation coefficient
     * @throws \Exception
     */
    public function testCorrelationCoefficientConstantArrays()
    {
        // Given - both arrays are constant (zero variance)
        $X = [5, 5, 5, 5, 5];
        $Y = [10, 10, 10, 10, 10];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Correlation::populationCorrelationCoefficient($X, $Y);
    }

    /**
     * @test Correlation coefficient with one constant array
     * @throws \Exception
     */
    public function testCorrelationCoefficientOneConstantArray()
    {
        // Given - X is constant (zero variance)
        $X = [5, 5, 5, 5, 5];
        $Y = [1, 2, 3, 4, 5];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Correlation::populationCorrelationCoefficient($X, $Y);
    }

    /**
     * @test Kendall's Tau with epsilon-based tie detection
     * @dataProvider dataProviderForKendallsTauEpsilonBugs
     * @param array $X
     * @param array $Y
     * @param float $expected_tau
     * @throws \Exception
     */
    public function testKendallsTauWithEpsilonTieDetection(array $X, array $Y, float $expected_tau)
    {
        // When
        $tau = Correlation::kendallsTau($X, $Y);

        // Then
        $this->assertEqualsWithDelta($expected_tau, $tau, 0.00001);
    }

    /**
     * Data provider for Kendall's tau epsilon tests
     * Generated using scipy.stats.kendalltau()
     * @return array
     */
    public function dataProviderForKendallsTauEpsilonBugs(): array
    {
        return [
            // Near-equal values within 1e-12
            [
                [1.0, 1.000000000001, 2.0, 3.0, 4.0],
                [10.0, 20.0, 30.0, 40.0, 50.0],
                1.0000000000,
            ],
            // Floating-point arithmetic 0.1 + 0.2
            [
                [0.30000000000000004, 0.3, 0.5, 0.7, 0.9],
                [1.0, 2.0, 3.0, 4.0, 5.0],
                0.8000000000,
            ],
            // Multiple near-ties in both X and Y
            [
                [1.0, 1.0000000000001, 2.0, 2.0000000000001, 3.0],
                [5.0, 5.0000000000001, 6.0, 6.0000000000001, 7.0],
                1.0000000000,
            ],
            // Division-generated floats
            [
                [0.3333333333333333, 0.6666666666666666, 1.0, 1.3333333333333333, 1.6666666666666667],
                [0.14285714285714285, 0.2857142857142857, 0.42857142857142855, 0.5714285714285714, 0.7142857142857143],
                1.0000000000,
            ],
        ];
    }

    /**
     * @test Spearman's Rho with epsilon-based ranking
     * @dataProvider dataProviderForSpearmansRhoEpsilonBugs
     * @param array $X
     * @param array $Y
     * @param float $expected_rho
     * @throws \Exception
     */
    public function testSpearmansRhoWithEpsilonRanking(array $X, array $Y, float $expected_rho)
    {
        // When
        $rho = Correlation::spearmansRho($X, $Y);

        // Then
        $this->assertEqualsWithDelta($expected_rho, $rho, 0.00001);
    }

    /**
     * Data provider for Spearman's rho epsilon tests
     * Generated using scipy.stats.spearmanr()
     * @return array
     */
    public function dataProviderForSpearmansRhoEpsilonBugs(): array
    {
        return [
            // 0.1 + 0.2 vs 0.3
            [
                [0.30000000000000004, 0.3, 0.5, 0.7, 0.9],
                [1.0, 2.0, 3.0, 4.0, 5.0],
                0.9000000000, // SciPy with epsilon-based ranking
            ],
            // Values differing by 1e-14
            [
                [1.0, 1.00000000000001, 2.0, 3.0, 4.0, 5.0],
                [10.0, 20.0, 30.0, 40.0, 50.0, 60.0],
                1.0000000000,
            ],
            // Accumulated errors from repeated multiplication
            [
                [0.1, 0.2, 0.30000000000000004, 0.4, 0.5, 0.6000000000000001, 0.7000000000000001, 0.8, 0.9, 1.0],
                [0.2, 0.4, 0.6000000000000001, 0.8, 1.0, 1.2000000000000002, 1.4000000000000001, 1.6, 1.8, 2.0],
                1.0000000000,
            ],
        ];
    }
}
