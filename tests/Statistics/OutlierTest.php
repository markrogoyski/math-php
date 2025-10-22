<?php

namespace MathPHP\Tests\Statistics;

use MathPHP\Exception;
use MathPHP\Statistics\Outlier;

class OutlierTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         Grubbs' statistic for two-sided test
     * @dataProvider dataProviderForGrubbsStatisticTwoSided
     * @param        array $data
     * @param        float $expectedG
     * @throws       \Exception
     */
    public function testGrubbsStatisticTwoSided(array $data, float $expectedG)
    {
        // When
        $G = Outlier::grubbsStatistic($data, 'two');

        // Then
        $this->assertEqualsWithDelta($expectedG, $G, 0.0001);
    }

    /**
     * Calculated with R outliers grubbs.test(z, type=10, two.sided=TRUE)
     * @return array
     */
    public function dataProviderForGrubbsStatisticTwoSided(): array
    {
        return [
            [
                [199.31, 199.53, 200.19, 200.82, 201.92, 201.95, 202.18, 245.57],
                2.4688000,
            ],
            [
                [145, 125, 190, 135, 220, 130, 210, 3, 165, 165, 150],
                2.52390,
            ],
            [
                [1, 1, 2, 2, 3, 3, 3, 3, 4, 4, 4, 5, 5, 6, 7, 46, -48],
                3.04240,
            ],
        ];
    }

    /**
     * @test         Grubbs' statistic for one-sided lower test
     * @dataProvider dataProviderForGrubbsStatisticOneSidedLower
     * @param        array $data
     * @param        float $expectedG
     * @throws       \Exception
     */
    public function testGrubbsStatisticOneSidedLower(array $data, float $expectedG)
    {
        // When
        $G = Outlier::grubbsStatistic($data, 'lower');

        // Then
        $this->assertEqualsWithDelta($expectedG, $G, 0.0001);
    }

    /**
     * Calculated by (μ - min) / sd
     * @return array
     */
    public function dataProviderForGrubbsStatisticOneSidedLower(): array
    {
        return [
            'min 199.31, mean 206.4338, sd 15.85256' => [
                [199.31, 199.53, 200.19, 200.82, 201.92, 201.95, 202.18, 245.57],
                0.449378523090277,
            ],
            'min 3, mean 148.9091, sd 57.81082' => [
                [145, 125, 190, 135, 220, 130, 210, 3, 165, 165, 150],
                2.523906424437501,
            ],
            'min -48, mean 3, sd 16.76305' => [
                [1, 1, 2, 2, 3, 3, 3, 3, 4, 4, 4, 5, 5, 6, 7, 46, -48],
                3.042405767446855,
            ],
        ];
    }

    /**
     * @test         Grubbs' statistic for one-sided upper test
     * @dataProvider dataProviderForGrubbsStatisticOneSidedUpper
     * @param        array $data
     * @param        float $expectedG
     * @throws       \Exception
     */
    public function testGrubbsStatisticOneSidedUpper(array $data, float $expectedG)
    {
        // When
        $G = Outlier::grubbsStatistic($data, 'upper');

        // Then
        $this->assertEqualsWithDelta($expectedG, $G, 0.0001);
    }

    /**
     * Calculated by (max - μ) / sd
     * @return array
     */
    public function dataProviderForGrubbsStatisticOneSidedUpper(): array
    {
        return [
            'max 245.57, mean 206.4338, sd 15.85256' => [
                [199.31, 199.53, 200.19, 200.82, 201.92, 201.95, 202.18, 245.57],
                2.468762143149119,
            ],
            'max 220, mean 148.9091, sd 57.81082' => [
                [145, 125, 190, 135, 220, 130, 210, 3, 165, 165, 150],
                1.229716167319543,
            ],
            'max 46, mean 3, sd 16.76305' => [
                [1, 1, 2, 2, 3, 3, 3, 3, 4, 4, 4, 5, 5, 6, 7, 46, -48],
                2.56520,
            ],
        ];
    }

    /**
     * @test         Critical value for two-sided test
     * @dataProvider dataProviderForCriticalValueOneSided
     * @param        float $𝛼
     * @param        int   $n
     * @param        float $expectedCriticalValue
     * @throws       Exception\BadParameterException
     */
    public function testCriticalGrubsOneSided(float $𝛼, int $n, float $expectedCriticalValue)
    {
        // Given
        $oneSided = Outlier::ONE_SIDED;

        // When
        $criticalValue = Outlier::grubbsCriticalValue($𝛼, $n, $oneSided);

        // Then
        $this->assertEqualsWithDelta($expectedCriticalValue, $criticalValue, 0.0001);
    }

    /**
     * Reference table: http://www.statistics4u.com/fundstat_eng/ee_grubbs_outliertest.html
     * @return array (𝛼, n, critical value)
     */
    public function dataProviderForCriticalValueOneSided(): array
    {
        return [
            // 𝛼 = 0.05
            [0.05, 3, 1.1531],
            [0.05, 4, 1.4625],
            [0.05, 5, 1.6714],
            [0.05, 6, 1.8221],
            [0.05, 7, 1.9381],
            [0.05, 8, 2.0317],
            [0.05, 9, 2.1096],
            [0.05, 10, 2.1761],
            [0.05, 11, 2.2339],
            [0.05, 12, 2.2850],
            [0.05, 13, 2.3305],
            [0.05, 14, 2.3717],
            [0.05, 15, 2.4090],
            [0.05, 16, 2.4433],
            [0.05, 17, 2.4748],
            [0.05, 18, 2.5040],
            [0.05, 19, 2.5312],
            [0.05, 20, 2.5566],
            [0.05, 25, 2.6629],
            [0.05, 30, 2.7451],
            [0.05, 40, 2.8675],
            [0.05, 50, 2.9570],
            [0.05, 60, 3.0269],
            [0.05, 70, 3.0839],
            [0.05, 80, 3.1319],
            [0.05, 90, 3.1733],
            [0.05, 100, 3.2095],
            [0.05, 120, 3.2706],
            [0.05, 140, 3.3208],
            [0.05, 160, 3.3633],
            [0.05, 180, 3.4001],
            [0.05, 200, 3.4324],
            [0.05, 300, 3.5525],
            [0.05, 400, 3.6339],
            [0.05, 500, 3.6952],
            [0.05, 600, 3.7442],

            // 𝛼 = 0.01
            [0.01, 3, 1.1546],
            [0.01, 4, 1.4925],
            [0.01, 5, 1.7489],
            [0.01, 6, 1.9442],
            [0.01, 7, 2.0973],
            [0.01, 8, 2.2208],
            [0.01, 9, 2.3231],
            [0.01, 10, 2.4097],
            [0.01, 11, 2.4843],
            [0.01, 12, 2.5494],
            [0.01, 13, 2.6070],
            [0.01, 14, 2.6585],
            [0.01, 15, 2.7049],
            [0.01, 16, 2.7470],
            [0.01, 17, 2.7854],
            [0.01, 18, 2.8208],
            [0.01, 19, 2.8535],
            [0.01, 20, 2.8838],
            [0.01, 25, 3.0086],
            [0.01, 30, 3.1029],
            [0.01, 40, 3.2395],
            [0.01, 50, 3.3366],
            [0.01, 60, 3.4111],
            [0.01, 70, 3.4710],
            [0.01, 80, 3.5208],
            [0.01, 90, 3.5632],
            [0.01, 100, 3.6002],
            [0.01, 120, 3.6619],
            [0.01, 140, 3.7121],
            [0.01, 160, 3.7542],
            [0.01, 180, 3.7904],
            [0.01, 200, 3.8220],
            [0.01, 300, 3.9385],
            [0.01, 400, 4.0166],
            [0.01, 500, 4.0749],
            [0.01, 600, 4.1214],
        ];
    }

    /**
     * @test         Critical value for two-sided test
     * @dataProvider dataProviderForCriticalValueTwoSided
     * @param        float $𝛼
     * @param        int   $n
     * @param        float $expectedCriticalValue
     * @throws       Exception\BadParameterException
     */
    public function testCriticalGrubsTwoSided(float $𝛼, int $n, float $expectedCriticalValue)
    {
        // Given
        $twoSided = Outlier::TWO_SIDED;

        // When
        $criticalValue = Outlier::grubbsCriticalValue($𝛼, $n, $twoSided);

        // Then
        $this->assertEqualsWithDelta($expectedCriticalValue, $criticalValue, 0.0001);
    }

    /**
     * Reference table: http://www.statistics4u.com/fundstat_eng/ee_grubbs_outliertest.html
     * @return array (𝛼, n, critical value)
     */
    public function dataProviderForCriticalValueTwoSided(): array
    {
        return [
            // 𝛼 = 0.05
            [0.05, 3, 1.1543],
            [0.05, 4, 1.4812],
            [0.05, 5, 1.7150],
            [0.05, 6, 1.8871],
            [0.05, 7, 2.0200],
            [0.05, 8, 2.1266],
            [0.05, 9, 2.2150],
            [0.05, 10, 2.2900],
            [0.05, 11, 2.3547],
            [0.05, 12, 2.4116],
            [0.05, 13, 2.4620],
            [0.05, 14, 2.5073],
            [0.05, 15, 2.5483],
            [0.05, 16, 2.5857],
            [0.05, 17, 2.6200],
            [0.05, 18, 2.6516],
            [0.05, 19, 2.6809],
            [0.05, 20, 2.7082],
            [0.05, 25, 2.8217],
            [0.05, 30, 2.9085],
            [0.05, 40, 3.0361],
            [0.05, 50, 3.1282],
            [0.05, 60, 3.1997],
            [0.05, 70, 3.2576],
            [0.05, 80, 3.3061],
            [0.05, 90, 3.3477],
            [0.05, 100, 3.3841],
            [0.05, 120, 3.4451],
            [0.05, 140, 3.4951],
            [0.05, 160, 3.5373],
            [0.05, 180, 3.5736],
            [0.05, 200, 3.6055],
            [0.05, 300, 3.7236],
            [0.05, 400, 3.8032],
            [0.05, 500, 3.8631],
            [0.05, 600, 3.9109],

            // 𝛼 = 0.01
            [0.01, 3,  1.1547],
            [0.01, 4,  1.4962],
            [0.01, 5,  1.7637],
            [0.01, 6,  1.9728],
            [0.01, 7,  2.1391],
            [0.01, 8,  2.2744],
            [0.01, 9,  2.3868],
            [0.01, 10, 2.4821],
            [0.01, 11, 2.5641],
            [0.01, 12, 2.6357],
            [0.01, 13, 2.6990],
            [0.01, 14, 2.7554],
            [0.01, 15, 2.8061],
            [0.01, 16, 2.8521],
            [0.01, 17, 2.8940],
            [0.01, 18, 2.9325],
            [0.01, 19, 2.9680],
            [0.01, 20, 3.0008],
            [0.01, 25, 3.1353],
            [0.01, 30, 3.2361],
            [0.01, 40, 3.3807],
            [0.01, 50, 3.4825],
            [0.01, 60, 3.5599],
            [0.01, 70, 3.6217],
            [0.01, 80, 3.6729],
            [0.01, 90, 3.7163],
            [0.01, 100, 3.7540],
            [0.01, 120, 3.8167],
            [0.01, 140, 3.8673],
            [0.01, 160, 3.9097],
            [0.01, 180, 3.9460],
            [0.01, 200, 3.9777],
            [0.01, 300, 4.0935],
            [0.01, 400, 4.1707],
            [0.01, 500, 4.2283],
            [0.01, 600, 4.2740],
        ];
    }

    /**
     * @test   Grubbs statistic error when test type is invalid
     * @throws \Exception
     */
    public function testGrubbsStatisticTestTypeException()
    {
        // Given
        $irrelevantData  = [1, 2, 3, 4];
        $wrongTypeOfTest = 'wrong type of test';

        // Then
        $this->expectException(Exception\BadParameterException::class);

        // When
        $G = Outlier::grubbsStatistic($irrelevantData, $wrongTypeOfTest);
    }

    /**
     * @test         Critical value tails must be 1 or 2
     * @dataProvider dataProviderForInvalidTails
     * @param        string $typeOfTest
     * @throws       \Exception
     */
    public function testCriticalValueException(string $typeOfTest)
    {
        // Given
        $𝛼 = 0.05;
        $n = 10;

        // Then
        $this->expectException(Exception\BadParameterException::class);

        // When
        $criticalValue = Outlier::grubbsCriticalValue($𝛼, $n, $typeOfTest);
    }

    /**
     * @return array
     */
    public function dataProviderForInvalidTails(): array
    {
        return [
            ['zero'],
            ['three'],
            ['ten'],
        ];
    }

    /**
     * @test   Grubbs statistic throws exception when standard deviation is zero (identical values)
     * @dataProvider dataProviderForZeroStandardDeviation
     * @param        array  $data
     * @param        string $typeOfTest
     * @throws       \Exception
     */
    public function testGrubbsStatisticZeroStandardDeviationException(array $data, string $typeOfTest)
    {
        // Then
        $this->expectException(Exception\BadDataException::class);
        $this->expectExceptionMessage('Standard deviation is zero');

        // When
        $G = Outlier::grubbsStatistic($data, $typeOfTest);
    }

    /**
     * Test data where all values are identical (σ = 0) with sufficient sample size (n ≥ 3)
     * Verified: Standard deviation of identical values is 0 (basic statistical principle)
     * @return array
     */
    public function dataProviderForZeroStandardDeviation(): array
    {
        return [
            'all fives, two-sided' => [[5, 5, 5, 5, 5], 'two'],
            'all fives, lower' => [[5, 5, 5, 5, 5], 'lower'],
            'all fives, upper' => [[5, 5, 5, 5, 5], 'upper'],
            'all zeros, two-sided' => [[0, 0, 0, 0], 'two'],
            'all negatives, lower' => [[-10, -10, -10, -10, -10, -10], 'lower'],
            'three identical values, two-sided' => [[100, 100, 100], 'two'],
            'three identical values, upper' => [[7.5, 7.5, 7.5], 'upper'],
        ];
    }

    /**
     * @test   Grubbs statistic throws exception when sample size is less than 3
     * @dataProvider dataProviderForSampleSizeTooSmall
     * @param        array  $data
     * @param        string $typeOfTest
     * @throws       \Exception
     */
    public function testGrubbsStatisticSampleSizeTooSmallException(array $data, string $typeOfTest)
    {
        // Then
        $this->expectException(Exception\BadDataException::class);
        $this->expectExceptionMessage('at least 3 observations');

        // When
        $G = Outlier::grubbsStatistic($data, $typeOfTest);
    }

    /**
     * Test data with insufficient sample size (n < 3)
     * Reference: Grubbs' test requires n ≥ 3 for statistical validity
     * Source: https://www.itl.nist.gov/div898/handbook/eda/section3/eda35h1.htm
     * @return array
     */
    public function dataProviderForSampleSizeTooSmall(): array
    {
        return [
            'two values, two-sided' => [[1, 100], 'two'],
            'two values, lower' => [[1, 100], 'lower'],
            'two values, upper' => [[1, 100], 'upper'],
            'two different values, two-sided' => [[5.5, 10.2], 'two'],
            'single value, two-sided' => [[42], 'two'],
            'single value, lower' => [[42], 'lower'],
            'single value, upper' => [[42], 'upper'],
        ];
    }

    /**
     * @test   Critical value throws exception when sample size is less than 3
     * @dataProvider dataProviderForCriticalValueSampleSizeTooSmall
     * @param        float  $𝛼
     * @param        int    $n
     * @param        string $typeOfTest
     * @throws       \Exception
     */
    public function testGrubbsCriticalValueSampleSizeTooSmallException(float $𝛼, int $n, string $typeOfTest)
    {
        // Then
        $this->expectException(Exception\BadDataException::class);
        $this->expectExceptionMessage('at least 3 observations');

        // When
        $criticalValue = Outlier::grubbsCriticalValue($𝛼, $n, $typeOfTest);
    }

    /**
     * Test critical value with insufficient sample size
     * Reference: Grubbs' test requires n ≥ 3 for statistical validity
     * @return array
     */
    public function dataProviderForCriticalValueSampleSizeTooSmall(): array
    {
        return [
            'n=2, two-sided' => [0.05, 2, 'two'],
            'n=1, two-sided' => [0.05, 1, 'two'],
            'n=2, one-sided' => [0.05, 2, 'one'],
            'n=1, one-sided' => [0.01, 1, 'one'],
            'n=0, two-sided' => [0.05, 0, 'two'],
        ];
    }

    /**
     * @test         Critical value accepts 'lower' and 'upper' as aliases for 'one'
     * @dataProvider dataProviderForCriticalValueOneSidedWithLowerUpper
     * @param        string $typeOfTest
     * @param        float  $𝛼
     * @param        int    $n
     * @param        float  $expectedCriticalValue
     * @throws       Exception\BadParameterException
     */
    public function testCriticalGrubbsOneSidedWithLowerUpperAliases(string $typeOfTest, float $𝛼, int $n, float $expectedCriticalValue)
    {
        // When
        $criticalValue = Outlier::grubbsCriticalValue($𝛼, $n, $typeOfTest);

        // Then
        $this->assertEqualsWithDelta($expectedCriticalValue, $criticalValue, 0.001);
    }

    /**
     * Test that 'lower' and 'upper' work as aliases for 'one' in critical value
     * Reference table: http://www.statistics4u.com/fundstat_eng/ee_grubbs_outliertest.html
     * Both one-sided lower and upper tests use the same critical value (one-sided)
     * @return array (typeOfTest, 𝛼, n, critical value)
     */
    public function dataProviderForCriticalValueOneSidedWithLowerUpper(): array
    {
        return [
            // Using 'lower' should work and return same value as 'one'
            ['lower', 0.05, 10, 2.1761],
            ['lower', 0.05, 20, 2.5566],
            ['lower', 0.01, 10, 2.4097],

            // Using 'upper' should work and return same value as 'one'
            ['upper', 0.05, 10, 2.1761],
            ['upper', 0.05, 20, 2.5566],
            ['upper', 0.01, 10, 2.4097],
        ];
    }
}
