<?php
namespace Math\Statistics;

class RandomVariableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForCentralMoment
     */
    public function testCentralMoment(array $X, $n, $moment)
    {
        $this->assertEquals($moment, RandomVariable::centralMoment($X, $n), '', 0.0001);
    }

    public function dataProviderForCentralMoment()
    {
        return [
            [ [ 600, 470, 170, 430, 300 ], 1, 0 ],
            [ [ 600, 470, 170, 430, 300 ], 2, 21704 ],
            [ [ 600, 470, 170, 430, 300 ], 3, -568512 ],
        ];
    }

    public function testCentralMomentNullIfXEmpty()
    {
        $this->assertNull(RandomVariable::centralMoment(array(), 3));
    }

    /**
     * @dataProvider dataProviderForPopulationSkewness
     */
    public function testPopulationSkewness(array $X, $skewness)
    {
        $this->assertEquals($skewness, RandomVariable::populationSkewness($X), '', 0.0001);
    }

    public function dataProviderForPopulationSkewness()
    {
        return [
            [ [61,61,61,61,61,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,73,73,73,73,73,73,73,73], -0.1082 ],
            [ [2, 3, -1, 3, 4, 5, 0, 2], -0.3677 ],
            [ [1, 2, 3, 4, 5, 6, 8, 8], 0.07925 ],
            [ [1, 1, 3, 4, 5, 6, 7, 8], -0.07925 ],
            [ [3, 4, 5, 2, 3, 4, 5, 6, 4, 7 ], 0.303193 ],
            [ [1, 1, 2, 2, 2, 2, 3, 3, 3, 4, 4, 5, 6, 7, 8 ], 0.774523929 ],
            [ [1,2,3,4,5,6,7,8], 0 ],
        ];
    }

    public function testPopulationSkewnessNullWhenEmptyArray()
    {
        $this->assertNull(RandomVariable::populationSkewness(array()));
    }

    /**
     * @dataProvider dataProviderForSampleSkewness
     */
    public function testSampleSkewness(array $X, $skewness)
    {
        $this->assertEquals($skewness, RandomVariable::sampleSkewness($X), '', 0.01);
    }

    public function dataProviderForSampleSkewness()
    {
        return [
            [ [61,61,61,61,61,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,73,73,73,73,73,73,73,73], -0.1098 ],
            [ [1, 2, 3, 4, 5, 9, 23, 32, 69], 1.95 ],
            [ [3, 4, 5, 2, 3, 4, 5, 6, 4, 7 ], 0.359543 ],
            [ [1, 1, 2, 2, 2, 2, 3, 3, 3, 4, 4, 5, 6, 7, 8 ], 0.863378312 ],
            [ [2, 3, -1, 3, 4, 5, 0, 2], -0.4587 ],
            [ [-2.83, -0.95, -0.88, 1.21, -1.67, 0.83, -0.27, 1.36, -0.34, 0.48, -2.83, -0.95, -0.88, 1.21, -1.67], -0.1740 ],
            [ [1,2,3,4,5,6,7,8], 0 ],
        ];
    }

    public function testSampleSkewnessNullWhenEmptyArray()
    {
        $this->assertNull(RandomVariable::sampleSkewness(array()));
    }

    /**
     * @dataProvider dataProviderForSkewness
     */
    public function testSkewness(array $X, $skewness)
    {
        $this->assertEquals($skewness, RandomVariable::skewness($X), '', 0.01);
    }

    public function dataProviderForSkewness()
    {
        return [
            [ [61,61,61,61,61,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,64,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,67,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,70,73,73,73,73,73,73,73,73], -0.1076 ],
            [ [1, 2, 3, 4, 5, 9, 23, 32, 69], 1.514 ],
            [ [5,20,40,80,100], 0.2027 ],
            [ [3, 4, 5, 2, 3, 4, 5, 6, 4, 7], 0.2876 ],
            [ [1, 1, 3, 4, 5, 6, 7, 8], -0.07924 ],
            [ [1,2,3,4,5,6,7,8], 0 ],
        ];
    }

    public function testSkewnessNullWhenEmptyArray()
    {
        $this->assertNull(RandomVariable::skewness(array()));
    }

    /**
     * @dataProvider dataProviderForSES
     */
    public function testSES(int $n, $ses)
    {
        $this->assertEquals($ses, RandomVariable::SES($n), '', 0.001);
    }

    public function dataProviderForSES()
    {
        return [
            [5, 0.913],
            [10, 0.687],
            [20, 0.512],
            [100, 0.241],
            [1000, 0.077],
            [10000, 0.024],
        ];
    }

    /**
     * @dataProvider dataProviderForKurtosis
     */
    public function testKurtosis(array $X, $kurtosis)
    {
        $this->assertEquals($kurtosis, RandomVariable::kurtosis($X), '', 0.001);
    }

    public function dataProviderForKurtosis()
    {
        return [
            [ [ 1987, 1987, 1991, 1992, 1992, 1992, 1992, 1993, 1994, 1994, 1995 ], -0.2320107 ],
            [ [ 0, 7, 7, 6, 6, 6, 5, 5, 4, 1 ], -0.27315697 ],
            [ [ 2, 2, 4, 6, 8, 10, 10 ], -1.57407407 ],
            [ [ 1242, 1353, 1142 ], -1.5 ],
            [ [1, 2, 3, 4, 5, 9, 23, 32, 69], 1.389416 ],
            [ [5,20,40,80,100], -1.525992 ],
            [ [4, 5, 5, 5, 5, 6], 0 ],
        ];
    }

    public function testKurtosisNullWhenEmptyArray()
    {
        $this->assertNull(RandomVariable::kurtosis(array()));
    }

    public function testIsPlatykurtic()
    {
        $this->assertTrue(RandomVariable::isPlatykurtic([ 2, 2, 4, 6, 8, 10, 10 ]));
    }

    public function testIsLeptokurtic()
    {
        $this->assertTrue(RandomVariable::isLeptokurtic([ 1, 2, 3, 4, 5, 9, 23, 32, 69 ]));
    }

    public function testIsMesokurtic()
    {
        $this->assertTrue(RandomVariable::isMesokurtic([ 4, 5, 5, 5, 5, 6 ]));
    }

    /**
     * @dataProvider dataProviderForSEK
     */
    public function testSEK(int $n, $sek)
    {
        $this->assertEquals($sek, RandomVariable::SEK($n), '', 0.001);
    }

    public function dataProviderForSEK()
    {
        return [
            [5, 2],
            [10, 1.334],
            [20, 0.992],
            [100, 0.478],
            [1000, 0.154],
            [10000, 0.048],
        ];
    }

    /**
     * @dataProvider dataProviderForStandardErrorOfTheMean
     */
    public function testStandardErrorOfTheMean(array $X, float $sem)
    {
        $this->assertEquals($sem, RandomVariable::standardErrorOfTheMean($X), '', 0.0001);
    }

    /**
     * @dataProvider dataProviderForStandardErrorOfTheMean
     */
    public function testSem(array $X, float $sem)
    {
        $this->assertEquals($sem, RandomVariable::sem($X), '', 0.0001);
    }

    public function dataProviderForStandardErrorOfTheMean()
    {
        return [
            [ [1,2,3,4,5,5,6,7], 0.7180703308172536 ],
            [ [34,6,23,12,25,64,32,75], 8.509317372319423 ],
            [ [1.5,1.3,2.532,0.43,0.042,5.9,0.9942,1.549], 0.645903079859 ],
            [ [453543,235235,656,342,2235,6436,234,9239,3535,8392,3492,5933,244], 37584.225394 ],
        ];
    }

    /**
     * @dataProvider dataProviderForConfidenceInterval
     */
    public function testConfidenceInterval($μ, $n, $σ, $cl, array $ci)
    {
        $this->assertEquals($ci, RandomVariable::confidenceInterval($μ, $n, $σ, $cl), '', 0.1);
    }

    public function dataProviderForConfidenceInterval()
    {
        return [
            [90, 9, 36, 80, ['ci' => 15.38, 'lower_bound' => 74.62, 'upper_bound' => 105.38]],
            [90, 9, 36, 85, ['ci' => 17.27, 'lower_bound' => 72.73, 'upper_bound' => 107.27]],
            [90, 9, 36, 90, ['ci' => 19.74, 'lower_bound' => 70.26, 'upper_bound' => 109.74]],
            [90, 9, 36, 95, ['ci' => 23.52, 'lower_bound' => 66.48, 'upper_bound' => 113.52]],
            [90, 9, 36, 99, ['ci' => 30.91, 'lower_bound' => 59.09, 'upper_bound' => 120.91]],
            [90, 9, 36, 99.5, ['ci' => 33.68, 'lower_bound' => 56.32, 'upper_bound' => 123.68]],
            [90, 9, 36, 99.9, ['ci' => 39.49, 'lower_bound' => 50.51, 'upper_bound' => 129.49]],
        ];
    }

    /**
     * @dataProvider dataProviderForSumOfSquares
     */
    public function testSumOfSquares(array $numbers, $sos)
    {
        $this->assertEquals($sos, RandomVariable::sumOfSquares($numbers), '', 0.001);
    }

    public function dataProviderForSumOfSquares()
    {
        return [
            [ [3, 6, 7, 11, 12, 13, 17], 817],
            [ [6, 11, 12, 14, 15, 20, 21], 1563],
            [ [1, 2, 3, 6, 7, 11, 12], 364],
            [ [1, 2, 3, 4, 5, 6, 7, 8, 9, 0], 285],
            [ [34, 253, 754, 2342, 75, 23, 876, 4, 1, -34, -345, 754, -377, 3, 0], 7723027],
        ];
    }

    public function testSumOfSquaresNullWhenEmptyArray()
    {
        $this->assertNull(RandomVariable::sumOfSquares(array()));
    }

    /**
     * @dataProvider dataProviderForSumOfSquaresDeviations
     */
    public function testSumOfSquaresDeviations(array $numbers, $sos)
    {
        $this->assertEquals($sos, RandomVariable::sumOfSquaresDeviations($numbers), '', 0.001);
    }

    public function dataProviderForSumOfSquaresDeviations()
    {
        return [
            [ [3, 6, 7, 11, 12, 13, 17], 136.8571],
            [ [6, 11, 12, 14, 15, 20, 21], 162.8571],
            [ [1, 2, 3, 6, 7, 11, 12], 112],
            [ [1, 2, 3, 4, 5, 6, 7, 8, 9, 0], 82.5],
            [ [34, 253, 754, 2342, 75, 23, 876, 4, 1, -34, -345, 754, -377, 3, 0], 6453975.7333],
        ];
    }

    public function testSumOfSquaresDeviationsNullWhenEmptyArray()
    {
        $this->assertNull(RandomVariable::sumOfSquaresDeviations(array()));
    }
}
