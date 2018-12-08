<?php
namespace MathPHP\Tests\Statistics;

use MathPHP\LinearAlgebra\Matrix;
use MathPHP\Statistics\Distance;
use MathPHP\Exception;

class DistanceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     bhattacharyyaDistance
     * @dataProvider dataProviderForBhattacharyyaDistance
     * @param        array $p
     * @param        array $q
     * @param        float $expected
     */
    public function testBhattacharyyaDistance(array $p, array $q, float $expected)
    {
        $BD = Distance::bhattacharyyaDistance($p, $q);

        $this->assertEquals($expected, $BD, '', 0.0001);
    }

    /**
     * @return array [p, q, distance]
     */
    public function dataProviderForBhattacharyyaDistance(): array
    {
        return [
            [
                [0.2, 0.5, 0.3],
                [0.1, 0.4, 0.5],
                0.024361049046679,
            ],
            [
                [0.4, 0.6],
                [0.3, 0.7],
                0.005531036666445
            ],
            [
                [0.9, 0.1],
                [0.1, 0.9],
                0.510825623765991
            ],
        ];
    }

    /**
     * @testCase bhattacharyyaDistance when arrays are different lengths
     */
    public function testBhattacharyyaDistanceExceptionArraysDifferentLength()
    {
        $p = [0.4, 0.5, 0.1];
        $q = [0.2, 0.8];

        $this->expectException(Exception\BadDataException::class);
        Distance::bhattacharyyaDistance($p, $q);
    }

    /**
     * @testCase bhattacharyyaDistance when probabilities do not add up to one
     */
    public function testBhattacharyyaDistanceExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        $p = [0.2, 0.2, 0.1];
        $q = [0.2, 0.4, 0.6];

        $this->expectException(Exception\BadDataException::class);
        Distance::bhattacharyyaDistance($p, $q);
    }

    /**
     * @testCase     kullbackLeiblerDivergence
     * @dataProvider dataProviderForKullbackLeiblerDivergence
     * @param        array $p
     * @param        array $q
     * @param        float $expected
     */
    public function testKullbackLeiblerDivergence(array $p, array $q, float $expected)
    {
        $BD = Distance::kullbackLeiblerDivergence($p, $q);

        $this->assertEquals($expected, $BD, '', 0.0001);
    }

    /**
     * Test data created using Python's scipi.stats.Distance
     * @return array [p, q, distance]
     */
    public function dataProviderForKullbackLeiblerDivergence(): array
    {
        return [
            [
                [0.5, 0.5],
                [0.75, 0.25],
                0.14384103622589045,
            ],
            [
                [0.75, 0.25],
                [0.5, 0.5],
                0.13081203594113694,
            ],
            [
                [0.2, 0.5, 0.3],
                [0.1, 0.4, 0.5],
                0.096953524639296684,
            ],
            [
                [0.4, 0.6],
                [0.3, 0.7],
                0.022582421084357374
            ],
            [
                [0.9, 0.1],
                [0.1, 0.9],
                1.7577796618689758
            ],
        ];
    }

    /**
     * @testCase kullbackLeiblerDivergence when arrays are different lengths
     */
    public function testKullbackLeiblerDivergenceExceptionArraysDifferentLength()
    {
        $p = [0.4, 0.5, 0.1];
        $q = [0.2, 0.8];

        $this->expectException(Exception\BadDataException::class);
        Distance::kullbackLeiblerDivergence($p, $q);
    }

    /**
     * @testCase kullbackLeiblerDivergence when probabilities do not add up to one
     */
    public function testKullbackLeiblerDivergenceExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        $p = [0.2, 0.2, 0.1];
        $q = [0.2, 0.4, 0.6];

        $this->expectException(Exception\BadDataException::class);
        Distance::kullbackLeiblerDivergence($p, $q);
    }

    /**
     * @testCase     hellingerDistance
     * @dataProvider dataProviderForHellingerDistance
     * @param        array $p
     * @param        array $q
     * @param        float $expected
     */
    public function testHellingerDistance(array $p, array $q, float $expected)
    {
        $BD = Distance::hellingerDistance($p, $q);

        $this->assertEquals($expected, $BD, '', 0.0001);
    }

    /**
     * Test data created with Python's numpy/scipy: norm(np.sqrt(p) - np.sqrt(q)) / np.sqrt(2)
     * @return array [p, q, distance]
     */
    public function dataProviderForHellingerDistance(): array
    {
        return [
            [
                [0.2905, 0.4861, 0.2234],
                [0.2704, 0.5259, 0.2137],
                0.025008343695279284,
            ],
            [
                [0.5, 0.5],
                [0.75, 0.25],
                0.18459191128251448,
            ],
            [
                [0.2, 0.5, 0.3],
                [0.1, 0.4, 0.5],
                0.15513450177826621,
            ],
            [
                [0.4, 0.6],
                [0.3, 0.7],
                0.074268220965891737
            ],
            [
                [0.9, 0.1],
                [0.1, 0.9],
                0.63245553203367577
            ],
        ];
    }

    /**
     * @testCase hellingerDistance when the arrays are different lengths
     */
    public function testHellingerDistanceExceptionArraysDifferentLength()
    {
        $p = [0.4, 0.5, 0.1];
        $q = [0.2, 0.8];

        $this->expectException(Exception\BadDataException::class);
        Distance::hellingerDistance($p, $q);
    }

    /**
     * @testCase hellingerDistance when the probabilities do not add up to one
     */
    public function testHellingerDistanceExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        $p = [0.2, 0.2, 0.1];
        $q = [0.2, 0.4, 0.6];

        $this->expectException(Exception\BadDataException::class);
        Distance::hellingerDistance($p, $q);
    }

    /**
     * @testCase     jensenShannonDivergence
     * @dataProvider dataProviderForJensenShannonDivergence
     * @param        array $p
     * @param        array $q
     * @param        float $expected
     */
    public function testJensenShannonDivergence(array $p, array $q, float $expected)
    {
        $BD = Distance::jensenShannonDivergence($p, $q);

        $this->assertEquals($expected, $BD, '', 0.0001);
    }

    /**
     * Test data created with Python's numpy/scipi where p and q are numpy.arrays:
     * def jsd(p, q):
     *     M = (p + q) / 2
     *     return (scipy.stats.Distance(p, M) + scipy.stats.Distance(q, M)) / 2
     * @return array [p, q, distance]
     */
    public function dataProviderForJensenShannonDivergence(): array
    {
        return [
            [
                [0.4, 0.6],
                [0.5, 0.5],
                0.0050593899289876343,
            ],
            [
                [0.1, 0.2, 0.2, 0.2, 0.2, 0.1],
                [0.0, 0.1, 0.4, 0.4, 0.1, 0.0],
                0.12028442909461383
            ],
            [
                [0.25, 0.5, 0.25],
                [0.5, 0.3, 0.2],
                0.035262717451799902,
            ],
            [
                [0.5, 0.3, 0.2],
                [0.25, 0.5, 0.25],
                0.035262717451799902,
            ],
        ];
    }

    /**
     * @testCase jensenShannonDivergence when the arrays are different lengths
     */
    public function testJensenShannonDivergenceExceptionArraysDifferentLength()
    {
        $p = [0.4, 0.5, 0.1];
        $q = [0.2, 0.8];

        $this->expectException(Exception\BadDataException::class);
        Distance::jensenShannonDivergence($p, $q);
    }

    /**
     * @testCase jensenShannonDivergence when the probabilities do not add up to one
     */
    public function testJensenShannonDivergenceExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        $p = [0.2, 0.2, 0.1];
        $q = [0.2, 0.4, 0.6];

        $this->expectException(Exception\BadDataException::class);
        Distance::jensenShannonDivergence($p, $q);
    }
    
    /**
     * @testCase     Mahalanobis from a point to the center of the data
     * @dataProvider dataProviderForMahalanobisCenter
     * @param        array $x
     * @param        Matrix $data
     * @param        float $expectedDistance
     * @throws       \Exception
     */
    public function testMahalanobisCenter(array $x, Matrix $data, float $expectedDistance)
    {
        // Given
        $x_m = new Matrix($x);

        // When
        $distance = Distance::Mahalanobis($x_m, $data);

        // Then
        $this->assertEquals($expectedDistance, $distance, '', 0.0001);
    }

    /**
     * @return array [x, data, distance]
     * @throws \Exception
     */
    public function dataProviderForMahalanobisCenter(): array
    {
        $data = [
            [4, 4, 5, 2, 3, 6, 9, 7, 4, 5],
            [3, 7, 5, 7, 9, 5, 6, 2, 2, 7],
        ];
        $data_matrix = new Matrix($data);

        return [
            [
                [[4], [3]],
                $data_matrix,
                1.24017
            ],
            [
                [[4], [7]],
                $data_matrix,
                0.76023
            ],
            [
                [[5], [5]],
                $data_matrix,
                0.12775
            ],
            [
                [[2], [7]],
                $data_matrix,
                1.46567
            ],
            [
                [[3], [9]],
                $data_matrix,
                1.64518
            ],
        ];
    }

    /**
     * @testCase     Mahalanobis between two points
     * @dataProvider dataProviderForMahalanobisPoint
     * @param        array $x
     * @param        array $y
     * @param        Matrix $data
     * @param        float $expectedDistance
     * @throws       \Exception
     */
    public function testMahalanobisPoint(array $x, array $y, Matrix $data, float $expectedDistance)
    {
        // Given
        $x_m = new Matrix($x);
        $y_m = new Matrix($y);

        // when
        $distance = Distance::Mahalanobis($x_m, $data, $y_m);

        // Then
        $this->assertEquals($expectedDistance, $distance, '', 0.0001);
    }

    /**
     * @return array [x, y, data, distance]
     * @throws \Exception
     */
    public function dataProviderForMahalanobisPoint(): array
    {
        $data = [
            [4, 4, 5, 2, 3, 6, 9, 7, 4, 5],
            [3, 7, 5, 7, 9, 5, 6, 2, 2, 7],
        ];
        $data_matrix = new Matrix($data);

        return [
            [
                [[6], [5]],
                [[2], [2]],
                $data_matrix,
                2.76992
            ],
            [
                [[9], [6]],
                [[2], [2]],
                $data_matrix,
                4.47614
            ],
            [
                [[7], [2]],
                [[2], [2]],
                $data_matrix,
                2.58465
            ],
            [
                [[4], [2]],
                [[2], [2]],
                $data_matrix,
                1.03386
            ],
            [
                [[5], [7]],
                [[2], [-2]],
                $data_matrix,
                4.6909
            ],
        ];
    }

    /**
     * @testCase Mahalanobis between two datasets
     * https://rdrr.io/rforge/GenAlgo/man/maha.html
     * @throws  \Exception
     */
    public function testMahalanobisTwoData()
    {
        // Given
        $data1 = new Matrix([
            [4, 4, 5, 2, 3, 6, 9, 7, 4, 5],
            [3, 7, 5, 7, 9, 5, 6, 2, 2, 7],
        ]);
        $data2 = new Matrix([
            [5, 3, 6, 3, 9],
            [7, 6, 1, 2, 9],
        ]);

        // When
        $distance = Distance::Mahalanobis($data2, $data1);

        // Then
        $this->assertEquals(0.1863069, $distance, '', 0.0001);
    }
}
