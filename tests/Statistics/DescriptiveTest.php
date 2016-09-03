<?php
namespace Math\Statistics;

class DescriptiveTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataProviderForRange
     */
    public function testRange(array $numbers, $range)
    {
        $this->assertEquals($range, Descriptive::range($numbers), '', 0.01);
    }

    /**
     * Data provider for range test
     * Data: [ [ numbers ], range ]
     */
    public function dataProviderForRange()
    {
        return [
            [ [ 1, 1, 1 ], 0 ],
            [ [ 1, 1, 2 ], 1 ],
            [ [ 1, 2, 1 ], 1 ],
            [ [ 8, 4, 3 ], 5 ],
            [ [ 9, 7, 8 ], 2 ],
            [ [ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], 8 ],
            [ [ 1, 2, 4, 7 ], 6 ],
            [ [ 8, 9, 10, 10, 10, 11, 11, 11, 12, 13 ], 5 ],
            [ [ 6, 7, 8, 10, 12, 14, 14, 15, 16, 20 ], 14 ],
            [ [ 9, 10, 11, 13, 15, 17, 17, 18, 19, 23 ], 14 ],
            [ [ 12, 14, 16, 20, 24, 28, 28, 30, 32, 40 ], 28 ],
        ];
    }

    public function testRangeNullWhenEmptyArray()
    {
        $this->assertNull(Descriptive::range(array()));
    }

    /**
     * @dataProvider dataProviderForMidrange
     */
    public function testMidrange(array $numbers, $midrange)
    {
        $this->assertEquals($midrange, Descriptive::midrange($numbers), '', 0.01);
    }

    /**
     * Data provider for midrange test
     * Data: [ [ numbers ], range ]
     */
    public function dataProviderForMidrange()
    {
        return [
            [ [ 1, 1, 1 ], 1 ],
            [ [ 1, 1, 2 ], 1.5 ],
            [ [ 1, 2, 1 ], 1.5 ],
            [ [ 8, 4, 3 ], 5.5 ],
            [ [ 9, 7, 8 ], 8 ],
            [ [ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], 17 ],
            [ [ 1, 2, 4, 7 ], 4 ],
            [ [ 8, 9, 10, 10, 10, 11, 11, 11, 12, 13 ], 10.5 ],
            [ [ 6, 7, 8, 10, 12, 14, 14, 15, 16, 20 ], 13 ],
            [ [ 9, 10, 11, 13, 15, 17, 17, 18, 19, 23 ], 16 ],
            [ [ 12, 14, 16, 20, 24, 28, 28, 30, 32, 40 ], 26 ],
        ];
    }

    public function testMidrangeNullWhenEmptyArray()
    {
        $this->assertNull(Descriptive::midrange(array()));
    }

    /**
     * @dataProvider dataProviderForPopulationVariance
     */
    public function testPopulationVariance(array $numbers, $variance)
    {
        $this->assertEquals($variance, Descriptive::populationVariance($numbers), '', 0.01);
    }

    /**
     * Data provider for population variance test
     * Data: [ [ numbers ], variance ]
     */
    public function dataProviderForPopulationVariance()
    {
        return [
            [ [ -10, 0, 10, 20, 30 ], 200 ],
            [ [ 8, 9, 10, 11, 12 ], 2 ],
            [ [ 600, 470, 170, 430, 300 ], 21704 ],
            [ [ -5, 1, 8, 7, 2], 21.84 ],
            [ [ 3, 7, 34, 25, 46, 7754, 3, 6 ], 6546331.937 ],
            [ [ 4, 6, 2, 2, 2, 2, 3, 4, 1, 3 ], 1.89 ],
            [ [ -3432, 5, 23, 9948, -74 ], 20475035.6 ],
        ];
    }

    public function testPopulationVarianceNullWhenEmptyArray()
    {
        $this->assertNull(Descriptive::populationVariance(array()));
    }

    /**
     * @dataProvider dataProviderForSampleVariance
     */
    public function testSampleVariance(array $numbers, $variance)
    {
        $this->assertEquals($variance, Descriptive::sampleVariance($numbers), '', 0.01);
    }

    /**
     * Data provider for sample variance test
     * Data: [ [ numbers ], variance ]
     */
    public function dataProviderForSampleVariance()
    {
        return [
            [ [ -10, 0, 10, 20, 30 ], 250 ],
            [ [ 8, 9, 10, 11, 12 ], 2.5 ],
            [ [ 600, 470, 170, 430, 300 ], 27130 ],
            [ [ -5, 1, 8, 7, 2 ], 27.3 ],
            [ [ 3, 7, 34, 25, 46, 7754, 3, 6 ], 7481522.21429 ],
            [ [ 4, 6, 2, 2, 2, 2, 3, 4, 1, 3 ], 2.1 ],
            [ [ -3432, 5, 23, 9948, -74 ], 25593794.5 ],
            [ [ 3, 21, 98, 203, 17, 9 ],  6219.9 ],
            [ [ 170, 300, 430, 470, 600 ], 27130 ],
            [ [ 1550, 1700, 900, 850, 1000, 950 ], 135416.66668 ],
            [ [ 1245, 1255, 1654, 1547, 1787, 1989, 1878, 2011, 2145, 2545, 2656 ], 210804.29090909063 ],
        ];
    }

    public function testSampleVarianceNullWhenEmptyArray()
    {
        $this->assertNull(Descriptive::sampleVariance(array()));
    }

    public function testSampleVarianceZeroWhenListContainsOnlyOneItem()
    {
        $this->assertEquals(0, Descriptive::sampleVariance([5]));
    }

    public function testVarianceExceptionDFLessThanZero()
    {
        $this->setExpectedException('\Exception');
        Descriptive::variance([1, 2, 3], -1);
    }

    /**
     * @dataProvider dataProviderForStandardDeviationUsingPopulationVariance
     */
    public function testStandardDeviationUsingPopulationVariance(array $numbers, $standard_deviation)
    {
        $this->assertEquals($standard_deviation, Descriptive::standardDeviation($numbers, true), '', 0.01);
    }

    /**
     * @dataProvider dataProviderForStandardDeviationUsingPopulationVariance
     */
    public function testSDeviationUsingPopulationVariance(array $numbers, $standard_deviation)
    {
        $this->assertEquals($standard_deviation, Descriptive::sd($numbers, true), '', 0.01);
    }

    /**
     * Data provider for standard deviation test
     * Data: [ [ numbers ], mean ]
     */
    public function dataProviderForStandardDeviationUsingPopulationVariance()
    {
        return [
            [ [ -10, 0, 10, 20, 30 ], 10 * sqrt(2) ],
            [ [ 8, 9, 10, 11, 12 ], sqrt(2) ],
            [ [ 600, 470, 170, 430, 300], 147.32 ],
            [ [ -5, 1, 8, 7, 2], 4.67 ],
            [ [ 3, 7, 34, 25, 46, 7754, 3, 6 ], 2558.580063 ],
            [ [ 4, 6, 2, 2, 2, 2, 3, 4, 1, 3 ], 1.374772708 ],
            [ [ -3432, 5, 23, 9948, -74 ], 4524.934872 ],
        ];
    }

    /**
     * @dataProvider dataProviderForStandardDeviationUsingSampleVariance
     */
    public function testStandardDeviationUsingSampleVariance(array $numbers, $standard_deviation)
    {
        $this->assertEquals($standard_deviation, Descriptive::standardDeviation($numbers), '', 0.01);
    }

    /**
     * @dataProvider dataProviderForStandardDeviationUsingSampleVariance
     */
    public function testSDeviationUsingSampleVariance(array $numbers, $standard_deviation)
    {
        $this->assertEquals($standard_deviation, Descriptive::sd($numbers), '', 0.01);
    }

    /**
     * Data provider for standard deviation using sample variance test
     * Data: [ [ numbers ], mean ]
     */
    public function dataProviderForStandardDeviationUsingSampleVariance()
    {
        return [
            [ [ 3, 21, 98, 203, 17, 9 ],  78.86634 ],
            [ [ 170, 300, 430, 470, 600 ], 164.7118696390761 ],
            [ [ 1550, 1700, 900, 850, 1000, 950 ], 367.99 ],
            [ [ 1245, 1255, 1654, 1547, 1787, 1989, 1878, 2011, 2145, 2545, 2656 ], 459.13 ],
        ];
    }

    public function testStandardDeviationNullWhenEmptyArray()
    {
        $this->assertNull(Descriptive::standardDeviation(array()));
    }

    public function testSDNullWhenEmptyArray()
    {
        $this->assertNull(Descriptive::sd(array()));
    }

    /**
     * @dataProvider dataProviderForMeanAbsoluteDeviation
     */
    public function testMeanAbsoluteDeviation(array $numbers, $mad)
    {
        $this->assertEquals($mad, Descriptive::meanAbsoluteDeviation($numbers), '', 0.01);
    }

    /**
     * Data provider for MAD (mean) test
     * Data: [ [ numbers ], mad ]
     */
    public function dataProviderForMeanAbsoluteDeviation()
    {
        return [
            [ [ 92, 83, 88, 94, 91, 85, 89, 90 ], 2.75 ],
            [ [ 2, 2, 3, 4, 14 ], 3.6 ],
        ];
    }

    public function testMeanAbsoluteDeviationNullWhenEmptyArray()
    {
        $this->assertNull(Descriptive::meanAbsoluteDeviation(array()));
    }

    /**
     * @dataProvider dataProviderForMedianAbsoluteDeviation
     */
    public function testMedianAbsoluteDeviation(array $numbers, $mad)
    {
        $this->assertEquals($mad, Descriptive::medianAbsoluteDeviation($numbers), '', 0.01);
    }

    /**
     * Data provider for MAD (median) test
     * Data: [ [ numbers ], mad ]
     */
    public function dataProviderForMedianAbsoluteDeviation()
    {
        return [
            [ [ 1, 1, 2, 2, 4, 6, 9 ], 1 ],
            [ [ 92, 83, 88, 94, 91, 85, 89, 90 ], 2 ],
            [ [ 2, 2, 3, 4, 14 ], 1 ],
        ];
    }

    public function testMedianAbsoluteDeviationNullWhenEmptyArray()
    {
        $this->assertNull(Descriptive::medianAbsoluteDeviation(array()));
    }

    /**
     * @dataProvider dataProviderForQuartilesExclusive
     */
    public function testQuartilesExclusive(array $numbers, array $quartiles)
    {
        $this->assertEquals($quartiles, Descriptive::quartilesExclusive($numbers));
    }

    public function dataProviderForQuartilesExclusive()
    {
        return [
            [
                [ 6, 7, 15, 36, 39, 40, 41, 42, 43, 47, 49],
                [ '0%' => 6, 'Q1' => 15, 'Q2' => 40, 'Q3' => 43, '100%' => 49, 'IQR' => 28 ],
            ],
            [
                [ 7, 15, 36, 39, 40, 41 ],
                [ '0%' => 7, 'Q1' => 15, 'Q2' => 37.5, 'Q3' => 40, '100%' => 41, 'IQR' => 25 ],
            ],
            [
                [ 0, 2, 2, 4, 5, 6, 7, 7, 8, 9, 34, 34, 43, 54, 54, 76, 234 ],
                [ '0%' => 0, 'Q1' => 4.5, 'Q2' => 8, 'Q3' => 48.5, '100%' => 234, 'IQR' => 44 ],
            ]
        ];
    }

    public function testQuartilesExclusiveEmptyWhenEmptyArray()
    {
        $this->assertEmpty(Descriptive::quartilesExclusive(array()));
    }

    /**
     * @dataProvider dataProviderForQuartilesInclusive
     */
    public function testQuartilesInclusive(array $numbers, array $quartiles)
    {
        $this->assertEquals($quartiles, Descriptive::quartilesInclusive($numbers));
    }

    public function dataProviderForQuartilesInclusive()
    {
        return [
            [
                [ 6, 7, 15, 36, 39, 40, 41, 42, 43, 47, 49],
                [ '0%' => 6, 'Q1' => 25.5, 'Q2' => 40, 'Q3' => 42.5, '100%' => 49, 'IQR' => 17 ],
            ],
            [
                [ 7, 15, 36, 39, 40, 41 ],
                [ '0%' => 7, 'Q1' => 15, 'Q2' => 37.5, 'Q3' => 40, '100%' => 41, 'IQR' => 25 ],
            ],
            [
                [ 0, 2, 2, 4, 5, 6, 7, 7, 8, 9, 34, 34, 43, 54, 54, 76, 234 ],
                [ '0%' => 0, 'Q1' => 5, 'Q2' => 8, 'Q3' => 43, '100%' => 234, 'IQR' => 38 ],
            ]
        ];
    }

    public function testQuartilesInclusiveEmptyWhenEmptyArray()
    {
        $this->assertEmpty(Descriptive::quartilesInclusive(array()));
    }

    /**
     * @dataProvider dataProviderForQuartiles
     */
    public function testQuartiles(array $numbers, string $method, array $quartiles)
    {
        $this->assertEquals($quartiles, Descriptive::quartiles($numbers, $method));
    }

    public function dataProviderForQuartiles()
    {
        return [
            [
                [ 6, 7, 15, 36, 39, 40, 41, 42, 43, 47, 49 ], 'Exclusive',
                [ '0%' => 6, 'Q1' => 15, 'Q2' => 40, 'Q3' => 43, '100%' => 49, 'IQR' => 28 ],
            ],
            [
                [ 7, 15, 36, 39, 40, 41 ], 'Exclusive',
                [ '0%' => 7, 'Q1' => 15, 'Q2' => 37.5, 'Q3' => 40, '100%' => 41, 'IQR' => 25 ],
            ],
            [
                [ 0, 2, 2, 4, 5, 6, 7, 7, 8, 9, 34, 34, 43, 54, 54, 76, 234 ], 'Exclusive',
                [ '0%' => 0, 'Q1' => 4.5, 'Q2' => 8, 'Q3' => 48.5, '100%' => 234, 'IQR' => 44 ],
            ],
            [
                [ 6, 7, 15, 36, 39, 40, 41, 42, 43, 47, 49 ], 'Inclusive',
                [ '0%' => 6, 'Q1' => 25.5, 'Q2' => 40, 'Q3' => 42.5, '100%' => 49, 'IQR' => 17 ],
            ],
            [
                [ 7, 15, 36, 39, 40, 41 ], 'Inclusive',
                [ '0%' => 7, 'Q1' => 15, 'Q2' => 37.5, 'Q3' => 40, '100%' => 41, 'IQR' => 25 ],
            ],
            [
                [ 0, 2, 2, 4, 5, 6, 7, 7, 8, 9, 34, 34, 43, 54, 54, 76, 234 ], 'Inclusive',
                [ '0%' => 0, 'Q1' => 5, 'Q2' => 8, 'Q3' => 43, '100%' => 234, 'IQR' => 38 ],
            ],
            [
                [ 6, 7, 15, 36, 39, 40, 41, 42, 43, 47, 49 ], 'Not_A_Real_Method_So_Default_Is_Used_Which_Is_Exclusive',
                [ '0%' => 6, 'Q1' => 15, 'Q2' => 40, 'Q3' => 43, '100%' => 49, 'IQR' => 28 ],
            ],
            [
                [ 7, 15, 36, 39, 40, 41 ], 'Not_A_Real_Method_So_Default_Is_Used_Which_Is_Exclusive',
                [ '0%' => 7, 'Q1' => 15, 'Q2' => 37.5, 'Q3' => 40, '100%' => 41, 'IQR' => 25 ],
            ],
            [
                [ 0, 2, 2, 4, 5, 6, 7, 7, 8, 9, 34, 34, 43, 54, 54, 76, 234 ], 'Not_A_Real_Method_So_Default_Is_Used_Which_Is_Exclusive',
                [ '0%' => 0, 'Q1' => 4.5, 'Q2' => 8, 'Q3' => 48.5, '100%' => 234, 'IQR' => 44 ],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForIQR
     */
    public function testInterquartileRange(array $numbers, $IQR)
    {
        $this->assertEquals($IQR, Descriptive::interquartileRange($numbers));
    }

    /**
     * @dataProvider dataProviderForIQR
     */
    public function testIQR(array $numbers, $IQR)
    {
        $this->assertEquals($IQR, Descriptive::IQR($numbers));
    }

    public function dataProviderForIQR()
    {
        return [
            [ [ 6, 7, 15, 36, 39, 40, 41, 42, 43, 47, 49], 28 ],
            [ [ 7, 15, 36, 39, 40, 41 ], 25 ],
        ];
    }

    /**
     * @dataProvider dataProviderForPercentile
     */
    public function testPercentile(array $numbers, int $percentile, $value)
    {
        $this->assertEquals($value, Descriptive::percentile($numbers, $percentile));
    }

    public function dataProviderForPercentile()
    {
        return [
            [ [ 15, 20, 35, 40, 50 ], 30, 20 ],
            [ [ 15, 20, 35, 40, 50 ], 40, 20 ],
            [ [ 15, 20, 35, 40, 50 ], 50, 35 ],
            [ [ 15, 20, 35, 40, 50 ], 100, 50 ],
            [ [ 3, 6, 7, 8, 8, 10, 13, 15, 16, 20 ], 25, 7 ],
            [ [ 3, 6, 7, 8, 8, 10, 13, 15, 16, 20 ], 50, 8 ],
            [ [ 3, 6, 7, 8, 8, 10, 13, 15, 16, 20 ], 75, 15 ],
            [ [ 3, 6, 7, 8, 8, 10, 13, 15, 16, 20 ], 100, 20 ],
            [ [ 3, 6, 7, 8, 8, 9, 10, 13, 15, 16, 20 ], 25, 7 ],
            [ [ 3, 6, 7, 8, 8, 9, 10, 13, 15, 16, 20 ], 50, 9 ],
            [ [ 3, 6, 7, 8, 8, 9, 10, 13, 15, 16, 20 ], 75, 15 ],
            [ [ 3, 6, 7, 8, 8, 9, 10, 13, 15, 16, 20 ], 100, 20 ],
        ];
    }

    public function testPercentileOutOfLowerBoundsP()
    {
        $this->setExpectedException('\Exception');
        Descriptive::percentile([1, 2, 3], -4);
    }

    public function testPercentileOutOfUpperBoundsP()
    {
        $this->setExpectedException('\Exception');
        Descriptive::percentile([1, 2, 3], 101);
    }

    /**
     * @dataProvider dataProviderForMidhinge
     */
    public function testMidhinge(array $numbers, $midhinge)
    {
        $this->assertEquals($midhinge, Descriptive::midhinge($numbers), '', 0.01);
    }

    public function dataProviderForMidhinge()
    {
        return [
            [ [1, 2, 3, 4, 5, 6], 3.5 ],
            [ [5, 5, 7, 8, 8, 11, 12, 12, 14, 15, 16, 19, 21, 22, 22, 26, 26, 26, 28, 29, 29, 32, 33, 34, 34, 34, 34, 35, 35, 37, 38, 38], 23.5],
            [ [36, 34, 21, 10, 20, 24, 31, 30, 30, 30, 30, 24, 30, 24, 39, 6, 32, 33, 33, 25, 26, 35, 8, 5, 30, 40, 9, 32, 25, 40, 24, 38], 28.5],
            [ [8, 10, 11, 12, 12, 13, 17, 18, 19, 19, 21, 23, 24, 24, 25, 25, 27, 27, 28, 28, 29, 29, 30, 30, 32, 33, 34, 35, 36, 37, 37, 40], 24.75 ],
        ];
    }

    /**
     * @dataProvider dataProviderForCoefficientOfVariation
     */
    public function testsCoefficientOfVariation(array $numbers, $cv)
    {
        $this->assertEquals($cv, Descriptive::coefficientOfVariation($numbers), '', 0.0001);
    }

    public function dataProviderForCoefficientOfVariation()
    {
        return [
            [ [1, 2, 3, 4, 5, 6 ,7, 8], 0.54433 ],
            [ [4, 7, 43, 12, 23, 76, 45, 3, 62, 23, 34, 44, 41], 0.70673 ],
            [ [3, 3, 3, 6, 6, 5, 9], 0.44721 ],
            [ [100, 100, 100], 0 ],
            [ [0, 10, 20, 30, 40], 0.7905 ],
            [ [32, 50, 68, 86, 104], 0.41852941176471 ],
        ];
    }

    public function testDescribePopulation()
    {
        $stats = Descriptive::describe([ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], true);
        $this->assertTrue(is_array($stats));
        $this->assertArrayHasKey('n', $stats);
        $this->assertArrayHasKey('min', $stats);
        $this->assertArrayHasKey('max', $stats);
        $this->assertArrayHasKey('mean', $stats);
        $this->assertArrayHasKey('median', $stats);
        $this->assertArrayHasKey('mode', $stats);
        $this->assertArrayHasKey('range', $stats);
        $this->assertArrayHasKey('midrange', $stats);
        $this->assertArrayHasKey('variance', $stats);
        $this->assertArrayHasKey('sd', $stats);
        $this->assertArrayHasKey('cv', $stats);
        $this->assertArrayHasKey('mean_mad', $stats);
        $this->assertArrayHasKey('median_mad', $stats);
        $this->assertArrayHasKey('quartiles', $stats);
        $this->assertArrayHasKey('midhinge', $stats);
        $this->assertArrayHasKey('skewness', $stats);
        $this->assertArrayHasKey('ses', $stats);
        $this->assertArrayHasKey('kurtosis', $stats);
        $this->assertArrayHasKey('sek', $stats);
        $this->assertArrayHasKey('sem', $stats);
        $this->assertArrayHasKey('ci_95', $stats);
        $this->assertArrayHasKey('ci_99', $stats);
        $this->assertTrue(is_int($stats['n']));
        $this->assertTrue(is_numeric($stats['min']));
        $this->assertTrue(is_numeric($stats['max']));
        $this->assertTrue(is_numeric($stats['mean']));
        $this->assertTrue(is_numeric($stats['median']));
        $this->assertTrue(is_array($stats['mode']));
        $this->assertTrue(is_numeric($stats['range']));
        $this->assertTrue(is_numeric($stats['midrange']));
        $this->assertTrue(is_numeric($stats['variance']));
        $this->assertTrue(is_numeric($stats['sd']));
        $this->assertTrue(is_numeric($stats['cv']));
        $this->assertTrue(is_numeric($stats['mean_mad']));
        $this->assertTrue(is_numeric($stats['median_mad']));
        $this->assertTrue(is_array($stats['quartiles']));
        $this->assertTrue(is_numeric($stats['midhinge']));
        $this->assertTrue(is_numeric($stats['skewness']));
        $this->assertTrue(is_numeric($stats['ses']));
        $this->assertTrue(is_numeric($stats['kurtosis']));
        $this->assertTrue(is_numeric($stats['sek']));
        $this->assertTrue(is_numeric($stats['sem']));
        $this->assertTrue(is_array($stats['ci_95']));
        $this->assertTrue(is_array($stats['ci_99']));
    }

    public function testDescribeSample()
    {
        $stats = Descriptive::describe([ 13, 18, 13, 14, 13, 16, 14, 21, 13 ], false);
        $this->assertTrue(is_array($stats));
        $this->assertArrayHasKey('n', $stats);
        $this->assertArrayHasKey('min', $stats);
        $this->assertArrayHasKey('max', $stats);
        $this->assertArrayHasKey('mean', $stats);
        $this->assertArrayHasKey('median', $stats);
        $this->assertArrayHasKey('mode', $stats);
        $this->assertArrayHasKey('range', $stats);
        $this->assertArrayHasKey('midrange', $stats);
        $this->assertArrayHasKey('variance', $stats);
        $this->assertArrayHasKey('sd', $stats);
        $this->assertArrayHasKey('cv', $stats);
        $this->assertArrayHasKey('quartiles', $stats);
        $this->assertArrayHasKey('midhinge', $stats);
        $this->assertArrayHasKey('skewness', $stats);
        $this->assertArrayHasKey('ses', $stats);
        $this->assertArrayHasKey('kurtosis', $stats);
        $this->assertArrayHasKey('sek', $stats);
        $this->assertArrayHasKey('sem', $stats);
        $this->assertArrayHasKey('ci_95', $stats);
        $this->assertArrayHasKey('ci_99', $stats);
        $this->assertTrue(is_int($stats['n']));
        $this->assertTrue(is_numeric($stats['min']));
        $this->assertTrue(is_numeric($stats['max']));
        $this->assertTrue(is_numeric($stats['mean']));
        $this->assertTrue(is_numeric($stats['median']));
        $this->assertTrue(is_array($stats['mode']));
        $this->assertTrue(is_numeric($stats['range']));
        $this->assertTrue(is_numeric($stats['midrange']));
        $this->assertTrue(is_numeric($stats['variance']));
        $this->assertTrue(is_numeric($stats['sd']));
        $this->assertTrue(is_numeric($stats['cv']));
        $this->assertTrue(is_array($stats['quartiles']));
        $this->assertTrue(is_numeric($stats['midhinge']));
        $this->assertTrue(is_numeric($stats['skewness']));
        $this->assertTrue(is_numeric($stats['ses']));
        $this->assertTrue(is_numeric($stats['kurtosis']));
        $this->assertTrue(is_numeric($stats['sek']));
        $this->assertTrue(is_numeric($stats['sem']));
        $this->assertTrue(is_array($stats['ci_95']));
        $this->assertTrue(is_array($stats['ci_99']));
    }

    /**
     * @dataProvider dataProviderForFiveNumberSummary
     */
    public function testFiveNumberSummary(array $numbers, array $summary)
    {
        $this->assertEquals($summary, Descriptive::fiveNumberSummary($numbers), '', 0.0001);
    }

    public function dataProviderForFiveNumberSummary()
    {
        return [
            [
                [0, 0, 1, 2, 63, 61, 27, 13],
                ['min' => 0, 'Q1' => 0.5, 'median' => 7.5, 'Q3' => 44.0, 'max' => 63],
            ],
        ];
    }
}
