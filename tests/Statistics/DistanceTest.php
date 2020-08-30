<?php

namespace MathPHP\Tests\Statistics;

use MathPHP\LinearAlgebra\Matrix;
use MathPHP\Statistics\Distance;
use MathPHP\Exception;

class DistanceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         bhattacharyyaDistance
     * @dataProvider dataProviderForBhattacharyyaDistance
     * @param        array $p
     * @param        array $q
     * @param        float $expected
     */
    public function testBhattacharyyaDistance(array $p, array $q, float $expected)
    {
        // When
        $BD = Distance::bhattacharyyaDistance($p, $q);

        // Then
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
     * @test bhattacharyyaDistance when arrays are different lengths
     */
    public function testBhattacharyyaDistanceExceptionArraysDifferentLength()
    {
        // Given
        $p = [0.4, 0.5, 0.1];
        $q = [0.2, 0.8];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Distance::bhattacharyyaDistance($p, $q);
    }

    /**
     * @test bhattacharyyaDistance when probabilities do not add up to one
     */
    public function testBhattacharyyaDistanceExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        // Given
        $p = [0.2, 0.2, 0.1];
        $q = [0.2, 0.4, 0.6];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Distance::bhattacharyyaDistance($p, $q);
    }

    /**
     * @test         kullbackLeiblerDivergence
     * @dataProvider dataProviderForKullbackLeiblerDivergence
     * @param        array $p
     * @param        array $q
     * @param        float $expected
     */
    public function testKullbackLeiblerDivergence(array $p, array $q, float $expected)
    {
        // When
        $BD = Distance::kullbackLeiblerDivergence($p, $q);

        // Then
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
     * @test kullbackLeiblerDivergence when arrays are different lengths
     */
    public function testKullbackLeiblerDivergenceExceptionArraysDifferentLength()
    {
        // Given
        $p = [0.4, 0.5, 0.1];
        $q = [0.2, 0.8];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Distance::kullbackLeiblerDivergence($p, $q);
    }

    /**
     * @test kullbackLeiblerDivergence when probabilities do not add up to one
     */
    public function testKullbackLeiblerDivergenceExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        // Given
        $p = [0.2, 0.2, 0.1];
        $q = [0.2, 0.4, 0.6];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Distance::kullbackLeiblerDivergence($p, $q);
    }

    /**
     * @test         hellingerDistance
     * @dataProvider dataProviderForHellingerDistance
     * @param        array $p
     * @param        array $q
     * @param        float $expected
     */
    public function testHellingerDistance(array $p, array $q, float $expected)
    {
        // When
        $BD = Distance::hellingerDistance($p, $q);

        // Then
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
     * @test hellingerDistance when the arrays are different lengths
     */
    public function testHellingerDistanceExceptionArraysDifferentLength()
    {
        // Given
        $p = [0.4, 0.5, 0.1];
        $q = [0.2, 0.8];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Distance::hellingerDistance($p, $q);
    }

    /**
     * @test hellingerDistance when the probabilities do not add up to one
     */
    public function testHellingerDistanceExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        // Given
        $p = [0.2, 0.2, 0.1];
        $q = [0.2, 0.4, 0.6];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Distance::hellingerDistance($p, $q);
    }

    /**
     * @test         jensenShannonDivergence
     * @dataProvider dataProviderForJensenShannonDivergence
     * @param        array $p
     * @param        array $q
     * @param        float $expected
     */
    public function testJensenShannonDivergence(array $p, array $q, float $expected)
    {
        // When
        $BD = Distance::jensenShannonDivergence($p, $q);

        // Then
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
     * @test jensenShannonDivergence when the arrays are different lengths
     */
    public function testJensenShannonDivergenceExceptionArraysDifferentLength()
    {
        // Given
        $p = [0.4, 0.5, 0.1];
        $q = [0.2, 0.8];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Distance::jensenShannonDivergence($p, $q);
    }

    /**
     * @test jensenShannonDivergence when the probabilities do not add up to one
     */
    public function testJensenShannonDivergenceExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        // Given
        $p = [0.2, 0.2, 0.1];
        $q = [0.2, 0.4, 0.6];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Distance::jensenShannonDivergence($p, $q);
    }

    /**
     * @test         jensenShannon
     * @dataProvider dataProviderForJensenShannon
     * @param        array $p
     * @param        array $q
     * @param        float $expected
     */
    public function testJensenShannon(array $p, array $q, float $expected)
    {
        // When
        $BD = Distance::jensenShannon($p, $q);

        // Then
        $this->assertEquals($expected, $BD, '', 0.0001);
    }

    /**
     * Test data created with Python scipy.spatial.distance.jensenshannon
     * distance.jensenshannon(p, q)
     * @return array [p, q, distance]
     */
    public function dataProviderForJensenShannon(): array
    {
        return [
            [
                [0.4, 0.6],
                [0.5, 0.5],
                0.07112938864483229,
            ],
            [
                [0.1, 0.2, 0.2, 0.2, 0.2, 0.1],
                [0.0, 0.1, 0.4, 0.4, 0.1, 0.0],
                0.346820456568833
            ],
            [
                [0.25, 0.5, 0.25],
                [0.5, 0.3, 0.2],
                0.18778369857844396,
            ],
            [
                [0.5, 0.3, 0.2],
                [0.25, 0.5, 0.25],
                0.18778369857844396,
            ],
        ];
    }

    /**
     * @test jensenShannon when the arrays are different lengths
     */
    public function testJensenShannonExceptionArraysDifferentLength()
    {
        // Given
        $p = [0.4, 0.5, 0.1];
        $q = [0.2, 0.8];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Distance::jensenShannon($p, $q);
    }

    /**
     * @test jensenShannon when the probabilities do not add up to one
     */
    public function testJensenShannonExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        // Given
        $p = [0.2, 0.2, 0.1];
        $q = [0.2, 0.4, 0.6];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        Distance::jensenShannon($p, $q);
    }

    /**
     * @test         Mahalanobis from a point to the center of the data
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
        $distance = Distance::mahalanobis($x_m, $data);

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
     * @test         Mahalanobis between two points
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
        $distance = Distance::mahalanobis($x_m, $data, $y_m);

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
     * @test   Mahalanobis between two datasets
     * https://rdrr.io/rforge/GenAlgo/man/maha.html
     * @throws \Exception
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
        $distance = Distance::mahalanobis($data2, $data1);

        // Then
        $this->assertEquals(0.1863069, $distance, '', 0.0001);
    }

    /**
     * @test         Minkowski distance
     * @dataProvider dataProviderForMinkowskiDistance
     * @param        float[] $x
     * @param        float[] $y
     * @param        int     $p
     * @param        float   $expected
     */
    public function testMinkowski(array $x, array $y, int $p, float $expected)
    {
        // When
        $distanceXy = Distance::minkowski($x, $y, $p);
        $distanceYx = Distance::minkowski($y, $x, $p);

        // Then
        $this->assertEquals($expected, $distanceXy, '', 0.0000000001);
        $this->assertEquals($expected, $distanceYx, '', 0.0000000001);
    }

    /**
     * Test data created using Python: from scipy.spatial import distance
     * distance.minkowski(x, y, p)
     * @return array
     */
    public function dataProviderForMinkowskiDistance(): array
    {
        return [
            [
                [1, 0, 0],
                [0, 1, 0],
                1,
                2,
            ],
            [
                [1, 0, 0],
                [0, 1, 0],
                2,
                1.4142135623730951,
            ],
            [
                [1, 0, 0],
                [0, 1, 0],
                3,
                1.2599210498948732,
            ],
            [
                [1, 1, 0],
                [0, 1, 0],
                1,
                1,
            ],
            [
                [1, 1, 0],
                [0, 1, 0],
                2,
                1,
            ],
            [
                [1, 1, 0],
                [0, 1, 0],
                3,
                1,
            ],
            [
                [1, 2, 3],
                [0, 0, 0],
                1,
                6,
            ],
            [
                [1, 2, 3],
                [0, 0, 0],
                2,
                3.7416573867739413,
            ],
            [
                [1, 2, 3],
                [0, 0, 0],
                3,
                3.3019272488946263,
            ],
            [
                [0, 0, 0],
                [0, 0, 0],
                1,
                0,
            ],
            [
                [0, 0, 0],
                [0, 0, 0],
                2,
                0,
            ],
            [
                [0, 0, 0],
                [0, 0, 0],
                3,
                0,
            ],
            [
                [1, 1, 1],
                [1, 1, 1],
                1,
                0,
            ],
            [
                [1, 1, 1],
                [1, 1, 1],
                2,
                0,
            ],
            [
                [1, 1, 1],
                [1, 1, 1],
                3,
                0,
            ],
            [
                [56, 26, 83],
                [11, 82, 95],
                1,
                113,
            ],
            [
                [56, 26, 83],
                [11, 82, 95],
                2,
                72.83543093852057,
            ],
            [
                [56, 26, 83],
                [11, 82, 95],
                3,
                64.51064463863402,
            ],
        ];
    }

    /**
     * @test minkowski error when vectors are of different sizes
     */
    public function testMinkowskiErrorDifferentSizedVectors()
    {
        // Given
        $x                   = [1, 2, 3];
        $y                   = [1, 2];
        $irrelevantValueForP = 1;

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        $distance = Distance::minkowski($x, $y, $irrelevantValueForP);
    }

    /**
     * @test minkowski error p value is < 1
     */
    public function testMinkowskiErrorPLessThanOne()
    {
        // Given
        $x = [1, 2, 3];
        $y = [1, 2, 3];
        $p = 0;

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        $distance = Distance::minkowski($x, $y, $p);
    }

    /**
     * @test         Euclidean distance
     * @dataProvider dataProviderForEuclideanDistance
     * @param        float[] $x
     * @param        float[] $y
     * @param        float   $expected
     */
    public function testEuclidean(array $x, array $y, float $expected)
    {
        // When
        $distance = Distance::euclidean($x, $y);

        // Then
        $this->assertEquals($expected, $distance, '', 0.0000000001);
    }

    /**
     * Test data created using Python: from scipy.spatial import distance
     * distance.euclidean(x, y)
     * @return array
     */
    public function dataProviderForEuclideanDistance(): array
    {
        return [
            [
                [1, 0, 0],
                [0, 1, 0],
                1.4142135623730951,
            ],
            [
                [1, 1, 0],
                [0, 1, 0],
                1,
            ],
            [
                [1, 2, 3],
                [0, 0, 0],
                3.7416573867739413,
            ],
            [
                [0, 0, 0],
                [0, 0, 0],
                0,
            ],
            [
                [1, 1, 1],
                [1, 1, 1],
                0,
            ],
            [
                [56, 26, 83],
                [11, 82, 95],
                72.83543093852057,
            ],
        ];
    }

    /**
     * @test euclidean error when vectors are of different sizes
     */
    public function testEuclideanErrorDifferentSizedVectors()
    {
        // Given
        $x = [1, 2, 3];
        $y = [1, 2];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        $distance = Distance::euclidean($x, $y);
    }

    /**
     * @test         Manhattan distance
     * @dataProvider dataProviderForManhattanDistance
     * @param        float[] $x
     * @param        float[] $y
     * @param        float   $expected
     */
    public function testManhattan(array $x, array $y, float $expected)
    {
        // When
        $distance = Distance::manhattan($x, $y);

        // Then
        $this->assertEquals($expected, $distance, '', 0.0000000001);
    }

    /**
     * Test data created using Python: from scipy.spatial import distance
     * distance.minkowski(x, y, 1)
     * @return array
     */
    public function dataProviderForManhattanDistance(): array
    {
        return [
            [
                [1, 0, 0],
                [0, 1, 0],
                2,
            ],
            [
                [1, 1, 0],
                [0, 1, 0],
                1,
            ],
            [
                [1, 2, 3],
                [0, 0, 0],
                6,
            ],
            [
                [0, 0, 0],
                [0, 0, 0],
                0,
            ],
            [
                [1, 1, 1],
                [1, 1, 1],
                0,
            ],
            [
                [56, 26, 83],
                [11, 82, 95],
                113,
            ],
        ];
    }

    /**
     * @test manhattan error when vectors are of different sizes
     */
    public function testManhattanErrorDifferentSizedVectors()
    {
        // Given
        $x = [1, 2, 3];
        $y = [1, 2];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        $distance = Distance::manhattan($x, $y);
    }

    /**
     * @test         cosine distance
     * @dataProvider dataProviderForCosineDistance
     * @param        array $A
     * @param        array $B
     * @param        float $expected
     */
    public function testCosineDistance(array $A, array $B, float $expected)
    {
        // When
        $distance = Distance::cosine($A, $B);

        // Then
        $this->assertEquals($expected, $distance, '', 0.0000000001);
    }

    /**
     * Test data created using Python: from scipy.spatial import distance
     * distance.cosine(x, y)
     * @return array
     */
    public function dataProviderForCosineDistance(): array
    {
        return [
            [
                [1, 0, 0],
                [0, 1, 0],
                1,
            ],
            [
                [100, 0, 0],
                [0, 1, 0],
                1,
            ],
            [
                [1, 1, 0],
                [0, 1, 0],
                0.29289321881345254,
            ],
            [
                [1, 1, 1],
                [1, 1, 1],
                0,
            ],
            [
                [2, 2, 2],
                [2, 2, 2],
                0,
            ],
            [
                [1, 1, 1],
                [2, 2, 2],
                0,
            ],
            [
                [56, 26, 83],
                [11, 82, 95],
                0.1840657409250167,
            ],
        ];
    }

    /**
     * @test         cosine distance exception for null vector
     * @dataProvider dataProviderForCosineDistanceException
     * @param        array $A
     * @param        array $B
     */
    public function testCosineDistanceException(array $A, array $B)
    {
        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        $distance = Distance::cosine($A, $B);
    }

    /**
     * @return array
     */
    public function dataProviderForCosineDistanceException(): array
    {
        return [
            [
                [1, 2, 3],
                [0, 0, 0],
            ],
            [
                [0, 0, 0],
                [1, 2, 3],
            ],
            [
                [0, 0, 0],
                [0, 0, 0],
            ],

        ];
    }

    /**
     * @test         cosineSimilarity
     * @dataProvider dataProviderForCosineSimilarity
     * @param        array $A
     * @param        array $B
     * @param        float $expected
     */
    public function testCosineSimilarity(array $A, array $B, float $expected)
    {
        // When
        $distance = Distance::cosineSimilarity($A, $B);

        // Then
        $this->assertEquals($expected, $distance, '', 0.0000000001);
    }

    /**
     * Test data created using Python: from scipy.spatial import distance
     * 1 - distance.cosine(x, y)
     * Cross referenced with online calculator: https://www.emathhelp.net/calculators/linear-algebra/angle-between-two-vectors-calculator
     * @return array
     */
    public function dataProviderForCosineSimilarity(): array
    {
        return [
            [
                [1, 2, 3],
                [3, 2, 1],
                0.7142857142857143,
            ],
            [
                [1, 0, 0],
                [0, 1, 0],
                0,
            ],
            [
                [1, 0, 0],
                [0, 0, 1],
                0,
            ],
            [
                [1, 0, 0],
                [1, 0, 0],
                1,
            ],
            [
                [100, 0, 0],
                [0, 1, 0],
                0,
            ],
            [
                [1, 1, 0],
                [0, 1, 0],
                0.7071067811865475,
            ],
            [
                [1, 1, 1],
                [1, 1, 1],
                1,
            ],
            [
                [2, 2, 2],
                [2, 2, 2],
                1,
            ],
            [
                [1, 1, 1],
                [2, 2, 2],
                1,
            ],
            [
                [56, 26, 83],
                [11, 82, 95],
                0.8159342590749833,
            ],
            [
                [-1, 1, 0],
                [0, 1, -1],
                0.5,
            ],
            [
                [23, 41, 33],
                [31, 56, 21],
                0.9567820320723087,
            ],
        ];
    }

    /**
     * @test         cosineSimilarity exception for null vector
     * @dataProvider dataProviderForCosineSimilarityException
     * @param        array $A
     * @param        array $B
     */
    public function testCosineSimilarityException(array $A, array $B)
    {
        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        $distance = Distance::cosineSimilarity($A, $B);
    }

    /**
     * @return array
     */
    public function dataProviderForCosineSimilarityException(): array
    {
        return [
            [
                [1, 2, 3],
                [0, 0, 0],
            ],
            [
                [0, 0, 0],
                [1, 2, 3],
            ],
            [
                [0, 0, 0],
                [0, 0, 0],
            ],

        ];
    }

    /**
     * @test         brayCurtis
     * @dataProvider dataProviderForBrayCurtis
     * @param        array $u
     * @param        array $v
     * @param        float $expected
     */
    public function testBrayCurtis(array $u, array $v, float $expected)
    {
        // When
        $distance = Distance::brayCurtis($u, $v);

        // Then
        $this->assertEquals($expected, $distance, '', 0.0001);
    }

    /**
     * Test data created with Python scipy.spatial.distance.braycurtis
     * distance.braycurtis(u, v)
     * @return array [u, v, distance]
     */
    public function dataProviderForBrayCurtis(): array
    {
        return [
            [
                [1, 0, 0],
                [0, 1, 0],
                1,
            ],
            [
                [1, 1, 0],
                [0, 1, 0],
                0.33333333333333331,
            ],
            [
                [1, 2, 3],
                [1, 2, 3],
                0,
            ],
            [
                [1, 2, 3],
                [3, 2, 1],
                0.3333333333333333,
            ],
            [
                [0.4, 0.6],
                [0.5, 0.5],
                0.09999999999999998,
            ],
            [
                [0.1, 0.2, 0.2, 0.2, 0.2, 0.1],
                [0.0, 0.1, 0.4, 0.4, 0.1, 0.0],
                0.4
            ],
            [
                [0.25, 0.5, 0.25],
                [0.5, 0.3, 0.2],
                0.25,
            ],
            [
                [0.5, 0.3, 0.2],
                [0.25, 0.5, 0.25],
                0.25,
            ],
            [
                [1],
                [0],
                1,
            ],
            [
                [0],
                [1],
                1,
            ],
            [
                [1],
                [1],
                0,
            ],
            [
                [-1],
                [-1],
                0,
            ],
            [
                [-2],
                [-3],
                0.2,
            ],
        ];
    }

    /**
     * @test         brayCurtis NAN
     * @dataProvider dataProviderForBrayCurtisNan
     * @param        array $u
     * @param        array $v
     */
    public function testBrayCurtisNan(array $u, array $v)
    {
        // When
        $distance = Distance::brayCurtis($u, $v);

        // Then
        $this->assertNan($distance);
    }

    /**
     * @return array
     */
    public function dataProviderForBrayCurtisNan(): array
    {
        return [
            'both zero ' => [
                [0],
                [0],
            ],
            '∑｜uᵢ + vᵢ｜ denominator is zero (1)' => [
                [1],
                [-1],
            ],
            '∑｜uᵢ + vᵢ｜ demoninator is zero (2)' => [
                [1, 2],
                [-1, -2],
            ],
        ];
    }

    /**
     * @test   brayCurtis exception when inputs are different lengths
     * @throws Exception\BadDataException
     */
    public function testBrayCurtisExceptionDifferentNumberElements()
    {
        // Given
        $u = [1, 2, 3];
        $v = [2, 3];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        $distance = Distance::brayCurtis($u, $v);
    }
}
