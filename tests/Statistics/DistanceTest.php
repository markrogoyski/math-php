<?php
namespace MathPHP\Tests\Statistics;

use MathPHP\Statistics\Distance;

class DistanceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForBhattacharyyaDistance
     */
    public function testBhattacharyyaDistance(array $p, array $q, $expected)
    {
        $BD = Distance::bhattacharyyaDistance($p, $q);

        $this->assertEquals($expected, $BD, '', 0.0001);
    }

    public function dataProviderForBhattacharyyaDistance()
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

    public function testBhattacharyyaDistanceExceptionArraysDifferentLength()
    {
        $p = [0.4, 0.5, 0.1];
        $q = [0.2, 0.8];

        $this->setExpectedException('MathPHP\Exception\BadDataException');
        Distance::bhattacharyyaDistance($p, $q);
    }

    public function testBhattacharyyaDistanceExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        $p = [0.2, 0.2, 0.1];
        $q = [0.2, 0.4, 0.6];

        $this->setExpectedException('MathPHP\Exception\BadDataException');
        Distance::bhattacharyyaDistance($p, $q);
    }

    /**
     * @dataProvider dataProviderForKullbackLeiblerDivergence
     */
    public function testKullbackLeiblerDivergence(array $p, array $q, $expected)
    {
        $BD = Distance::kullbackLeiblerDivergence($p, $q);

        $this->assertEquals($expected, $BD, '', 0.0001);
    }

    public function dataProviderForKullbackLeiblerDivergence()
    {
        // Test data created using Python's scipi.stats.Distance
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

    public function testKullbackLeiblerDivergenceExceptionArraysDifferentLength()
    {
        $p = [0.4, 0.5, 0.1];
        $q = [0.2, 0.8];

        $this->setExpectedException('MathPHP\Exception\BadDataException');
        Distance::kullbackLeiblerDivergence($p, $q);
    }

    public function testKullbackLeiblerDivergenceExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        $p = [0.2, 0.2, 0.1];
        $q = [0.2, 0.4, 0.6];

        $this->setExpectedException('MathPHP\Exception\BadDataException');
        Distance::kullbackLeiblerDivergence($p, $q);
    }

    /**
     * @dataProvider dataProviderForHellingerDistance
     */
    public function testHellingerDistance(array $p, array $q, $expected)
    {
        $BD = Distance::hellingerDistance($p, $q);

        $this->assertEquals($expected, $BD, '', 0.0001);
    }

    public function dataProviderForHellingerDistance()
    {
        // Test data created with Python's numpy/scipy: norm(np.sqrt(p) - np.sqrt(q)) / np.sqrt(2)
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

    public function testHellingerDistanceExceptionArraysDifferentLength()
    {
        $p = [0.4, 0.5, 0.1];
        $q = [0.2, 0.8];

        $this->setExpectedException('MathPHP\Exception\BadDataException');
        Distance::hellingerDistance($p, $q);
    }

    public function testHellingerDistanceExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        $p = [0.2, 0.2, 0.1];
        $q = [0.2, 0.4, 0.6];

        $this->setExpectedException('MathPHP\Exception\BadDataException');
        Distance::hellingerDistance($p, $q);
    }

    /**
     * @dataProvider dataProviderForJensenShannonDivergence
     */
    public function testJensenShannonDivergence(array $p, array $q, $expected)
    {
        $BD = Distance::jensenShannonDivergence($p, $q);

        $this->assertEquals($expected, $BD, '', 0.0001);
    }

    public function dataProviderForJensenShannonDivergence()
    {
        // Test data created with Python's numpy/scipi where p and q are numpy.arrays:
        // def jsd(p, q):
        //     M = (p + q) / 2
        //     return (scipy.stats.Distance(p, M) + scipy.stats.Distance(q, M)) / 2
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

    public function testJensenShannonDivergenceExceptionArraysDifferentLength()
    {
        $p = [0.4, 0.5, 0.1];
        $q = [0.2, 0.8];

        $this->setExpectedException('MathPHP\Exception\BadDataException');
        Distance::jensenShannonDivergence($p, $q);
    }

    public function testJensenShannonDivergenceExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        $p = [0.2, 0.2, 0.1];
        $q = [0.2, 0.4, 0.6];

        $this->setExpectedException('MathPHP\Exception\BadDataException');
        Distance::jensenShannonDivergence($p, $q);
    }
}
