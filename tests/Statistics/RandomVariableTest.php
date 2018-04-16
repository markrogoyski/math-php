<?php
namespace MathPHP\Tests\Statistics;

use MathPHP\Exception;
use MathPHP\Statistics\RandomVariable;

class RandomVariableTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     centralMoment
     * @dataProvider dataProviderForCentralMoment
     * @param        array $X
     * @param        int   $n
     * @param        float $moment
     */
    public function testCentralMoment(array $X, int $n, float $moment)
    {
        $this->assertEquals($moment, RandomVariable::centralMoment($X, $n), '', 0.0001);
    }

    /**
     * @return array [X, n, moment]
     */
    public function dataProviderForCentralMoment(): array
    {
        return [
            [ [ 600, 470, 170, 430, 300 ], 1, 0 ],
            [ [ 600, 470, 170, 430, 300 ], 2, 21704 ],
            [ [ 600, 470, 170, 430, 300 ], 3, -568512 ],
        ];
    }

    /**
     * @testCasel centralMoment is null if X is empty
     */
    public function testCentralMomentNullIfXEmpty()
    {
        $this->assertNull(RandomVariable::centralMoment(array(), 3));
    }

    /**
     * @testCase     populationSkewness
     * @dataProvider dataProviderForPopulationSkewness
     * @param        array $X
     * @param        float $skewness
     */
    public function testPopulationSkewness(array $X, float $skewness)
    {
        $this->assertEquals($skewness, RandomVariable::populationSkewness($X), '', 0.0001);
    }

    /**
     * @return array [X, skewness]
     */
    public function dataProviderForPopulationSkewness(): array
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

    /**
     * @testCase populationSkewness is null if array is empty
     */
    public function testPopulationSkewnessNullWhenEmptyArray()
    {
        $this->assertNull(RandomVariable::populationSkewness(array()));
    }

    /**
     * @testCase     sampleSkewness
     * @dataProvider dataProviderForSampleSkewness
     * @param        array $X
     * @param        float $skewness
     */
    public function testSampleSkewness(array $X, float $skewness)
    {
        $this->assertEquals($skewness, RandomVariable::sampleSkewness($X), '', 0.01);
    }

    /**
     * @return array [X, skewness]
     */
    public function dataProviderForSampleSkewness(): array
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

    /**
     * @testCase sampleSkewness is null when array is empty
     */
    public function testSampleSkewnessNullWhenEmptyArray()
    {
        $this->assertNull(RandomVariable::sampleSkewness(array()));
    }

    /**
     * @testCase sampleSkewness is null when array has fewer than 3 elements
     */
    public function testSampleSkewnessNullWhenSmallArray()
    {
        $this->assertNull(RandomVariable::sampleSkewness([1]));
        $this->assertNull(RandomVariable::sampleSkewness([1, 2]));
    }

    /**
     * @testCasel    skewness
     * @dataProvider dataProviderForSkewness
     * @param        array $X
     * @param        float $skewness
     */
    public function testSkewness(array $X, float $skewness)
    {
        $this->assertEquals($skewness, RandomVariable::skewness($X), '', 0.01);
    }

    /**
     * @return array [X, skewness]
     */
    public function dataProviderForSkewness(): array
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

    /**
     * @testCase skewness is null when array is empty
     */
    public function testSkewnessNullWhenEmptyArray()
    {
        $this->assertNull(RandomVariable::skewness(array()));
    }

    /**
     * @testCase     ses
     * @dataProvider dataProviderForSes
     * @param        int   $n
     * @param        float $ses
     */
    public function testSes(int $n, float $ses)
    {
        $this->assertEquals($ses, RandomVariable::ses($n), '', 0.001);
    }

    /**
     * @return array [n, ses]
     */
    public function dataProviderForSes(): array
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
     * @testCase     ses throws a BadDataException if n is < 3
     * @dataProvider dataProviderForSesException
     * @param        int $n
     */
    public function testSesException(int $n)
    {
        $this->expectException(Exception\BadDataException::class);
        RandomVariable::ses($n);
    }

    /**
     * @return array [n]
     */
    public function dataProviderForSesException(): array
    {
        return [
            [-1],
            [0],
            [1],
            [2],
        ];
    }

    /**
     * @testCase     kurtosis
     * @dataProvider dataProviderForKurtosis
     * @param        array $X
     * @param        float $kurtosis
     */
    public function testKurtosis(array $X, float $kurtosis)
    {
        $this->assertEquals($kurtosis, RandomVariable::kurtosis($X), '', 0.001);
    }

    /**
     * @return array [X, kurtosis]
     */
    public function dataProviderForKurtosis(): array
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

    /**
     * @testCase kurtosis returns null when array is empty
     */
    public function testKurtosisNullWhenEmptyArray()
    {
        $this->assertNull(RandomVariable::kurtosis(array()));
    }

    /**
     * @testCase     isPlatykurtic
     * @dataProvider dataProviderForPlatykurtic
     * @param        array $data
     */
    public function testIsPlatykurtic(array $data)
    {
        $this->assertTrue(RandomVariable::isPlatykurtic($data));
    }

    /**
     * @testCase     isLeptokurtic
     * @dataProvider dataProviderForLeptokurtic
     * @param        array $data
     */
    public function testIsLeptokurtic(array $data)
    {
        $this->assertTrue(RandomVariable::isLeptokurtic($data));
    }

    /**
     * @testCase     isMesokurtic
     * @dataProvider dataProviderForMesokurtic
     * @param        array $data
     */
    public function testIsMesokurtic(array $data)
    {
        $this->assertTrue(RandomVariable::isMesokurtic($data));
    }

    /**
     * @testCase     isPlatykurtic returns false for a leptokurtic data set
     * @dataProvider dataProviderForLeptokurtic
     * @param        array $data
     */
    public function testIsNotPlatykurtic(array $data)
    {
        $this->assertFalse(RandomVariable::isPlatykurtic($data));
    }

    /**
     * @testCase     isLeptokurtic returns false for a platykurtic data set
     * @dataProvider dataProviderForPlatykurtic
     * @param        array $data
     */
    public function testIsNotLeptokurtic(array $data)
    {
        $this->assertFalse(RandomVariable::isLeptokurtic($data));
    }

    /**
     * @return array [data]
     */
    public function dataProviderForPlatykurtic(): array
    {
        return [
            [[2, 2, 4, 6, 8, 10, 10]],
        ];
    }

    /**
     * @return array [data]
     */
    public function dataProviderForLeptokurtic(): array
    {
        return [
            [[1, 2, 3, 4, 5, 9, 23, 32, 69]],
        ];
    }

    /**
     * @return array [data]
     */
    public function dataProviderForMesokurtic(): array
    {
        return [
            [[4, 5, 5, 5, 5, 6]],
        ];
    }

    /**
     * @testCase     sek
     * @dataProvider dataProviderForSek
     * @param        int   $n
     * @param        float $sek
     */
    public function testSek(int $n, float $sek)
    {
        $this->assertEquals($sek, RandomVariable::sek($n), '', 0.001);
    }

    /**
     * @return array [n, sek]
     */
    public function dataProviderForSek(): array
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
     * @testCase     sek throws a BadDataException if n is < 4
     * @dataProvider dataProviderForSekException
     * @param        int $n
     */
    public function testSekException(int $n)
    {
        $this->expectException(Exception\BadDataException::class);
        RandomVariable::sek($n);
    }

    /**
     * @return array [n]
     */
    public function dataProviderForSekException(): array
    {
        return [
            [-1],
            [0],
            [1],
            [2],
            [3],
        ];
    }

    /**
     * @testCase     standardErrorOfTheMean
     * @dataProvider dataProviderForStandardErrorOfTheMean
     * @param        array $X
     * @param        float $sem
     */
    public function testStandardErrorOfTheMean(array $X, float $sem)
    {
        $this->assertEquals($sem, RandomVariable::standardErrorOfTheMean($X), '', 0.0001);
    }

    /**
     * @testCase     standardErrorOfTheMean
     * @dataProvider dataProviderForStandardErrorOfTheMean
     * @param        array $X
     * @param        float $sem
     */
    public function testSem(array $X, float $sem)
    {
        $this->assertEquals($sem, RandomVariable::sem($X), '', 0.0001);
    }

    /**
     * @return array [X, sem]
     */
    public function dataProviderForStandardErrorOfTheMean(): array
    {
        return [
            [ [1,2,3,4,5,5,6,7], 0.7180703308172536 ],
            [ [34,6,23,12,25,64,32,75], 8.509317372319423 ],
            [ [1.5,1.3,2.532,0.43,0.042,5.9,0.9942,1.549], 0.645903079859 ],
            [ [453543,235235,656,342,2235,6436,234,9239,3535,8392,3492,5933,244], 37584.225394 ],
        ];
    }

    /**
     * @testCase standardErrorOfTheMean is null when array is empty
     */
    public function testStandardErrorOfTheMeanNullWhenEmptyArray()
    {
        $this->assertNull(RandomVariable::standardErrorOfTheMean(array()));
    }

    /**
     * @testCase     confidenceInterval
     * @dataProvider dataProviderForConfidenceInterval
     * @param        number $μ
     * @param        number $n
     * @param        float  $σ
     * @param        float  $cl
     * @param        array  $ci
     */
    public function testConfidenceInterval($μ, $n, float $σ, float $cl, array $ci)
    {
        $this->assertEquals($ci, RandomVariable::confidenceInterval($μ, $n, $σ, $cl), '', 0.1);
    }

    /**
     * @return array [μ, n, σ, cl, ci]
     */
    public function dataProviderForConfidenceInterval(): array
    {
        return [
            [90, 9, 36, 80, ['ci' => 15.38, 'lower_bound' => 74.62, 'upper_bound' => 105.38]],
            [90, 9, 36, 85, ['ci' => 17.27, 'lower_bound' => 72.73, 'upper_bound' => 107.27]],
            [90, 9, 36, 90, ['ci' => 19.74, 'lower_bound' => 70.26, 'upper_bound' => 109.74]],
            [90, 9, 36, 95, ['ci' => 23.52, 'lower_bound' => 66.48, 'upper_bound' => 113.52]],
            [90, 9, 36, 99, ['ci' => 30.91, 'lower_bound' => 59.09, 'upper_bound' => 120.91]],
            [90, 9, 36, 99.5, ['ci' => 33.68, 'lower_bound' => 56.32, 'upper_bound' => 123.68]],
            [90, 9, 36, 99.9, ['ci' => 39.49, 'lower_bound' => 50.51, 'upper_bound' => 129.49]],
            [90, 0, 36, 99.9, ['ci' => null, 'lower_bound' => null, 'upper_bound' => null]],
        ];
    }

    /**
     * @testCase     sumOfSquares
     * @dataProvider dataProviderForSumOfSquares
     * @param        array  $numbers
     * @param        number $sos
     */
    public function testSumOfSquares(array $numbers, $sos)
    {
        $this->assertEquals($sos, RandomVariable::sumOfSquares($numbers), '', 0.001);
    }

    /**
     * @return array
     */
    public function dataProviderForSumOfSquares(): array
    {
        return [
            [ [3, 6, 7, 11, 12, 13, 17], 817],
            [ [6, 11, 12, 14, 15, 20, 21], 1563],
            [ [1, 2, 3, 6, 7, 11, 12], 364],
            [ [1, 2, 3, 4, 5, 6, 7, 8, 9, 0], 285],
            [ [34, 253, 754, 2342, 75, 23, 876, 4, 1, -34, -345, 754, -377, 3, 0], 7723027],
        ];
    }

    /**
     * @testCase sumOfSquares is null when the array is empty
     */
    public function testSumOfSquaresNullWhenEmptyArray()
    {
        $this->assertNull(RandomVariable::sumOfSquares(array()));
    }

    /**
     * @testCase     sumOfSquaresDeviations
     * @dataProvider dataProviderForSumOfSquaresDeviations
     * @param        array $numbers
     * @param        float $sos
     */
    public function testSumOfSquaresDeviations(array $numbers, float $sos)
    {
        $this->assertEquals($sos, RandomVariable::sumOfSquaresDeviations($numbers), '', 0.001);
    }

    /**
     * @return array [numbers, sos]
     */
    public function dataProviderForSumOfSquaresDeviations(): array
    {
        return [
            [ [3, 6, 7, 11, 12, 13, 17], 136.8571],
            [ [6, 11, 12, 14, 15, 20, 21], 162.8571],
            [ [1, 2, 3, 6, 7, 11, 12], 112],
            [ [1, 2, 3, 4, 5, 6, 7, 8, 9, 0], 82.5],
            [ [34, 253, 754, 2342, 75, 23, 876, 4, 1, -34, -345, 754, -377, 3, 0], 6453975.7333],
        ];
    }

    /**
     * @testCase sumOfSquaresDeviations is null when the array is empty
     */
    public function testSumOfSquaresDeviationsNullWhenEmptyArray()
    {
        $this->assertNull(RandomVariable::sumOfSquaresDeviations(array()));
    }
}
