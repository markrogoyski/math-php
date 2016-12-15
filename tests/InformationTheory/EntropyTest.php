<?php
namespace MathPHP\InformationTheory;

class EntropyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForShannonEntropy
     */
    public function testShannonEntropy(array $p, $expected)
    {
        $H = Entropy::shannonEntropy($p);

        $this->assertEquals($expected, $H, '', 0.001);
    }

    public function dataProviderForShannonEntropy()
    {
        return [
            [
                [1],
                0
            ],
            [
                [0.6, 0.4],
                0.97095,
            ],
            [
                [0.514, 0.486],
                0.99941,
            ],
            [
                [0.231, 0.385, 0.308, 0.077],
                1.82625,
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForBhattacharyyaDistance
     */
    public function testBhattacharyyaDistance(array $p, array $q, $expected)
    {
        $BD = Entropy::bhattacharyyaDistance($p, $q);

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
        Entropy::bhattacharyyaDistance($p, $q);
    }

    public function testBhattacharyyaDistanceExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        $p = [0.2, 0.2, 0.1];
        $q = [0.2, 0.4, 0.6];

        $this->setExpectedException('MathPHP\Exception\BadDataException');
        Entropy::bhattacharyyaDistance($p, $q);
    }

    /**
     * @dataProvider dataProviderForKullbackLeiblerDivergence
     */
    public function testKullbackLeiblerDivergence(array $p, array $q, $expected)
    {
        $BD = Entropy::kullbackLeiblerDivergence($p, $q);

        $this->assertEquals($expected, $BD, '', 0.0001);
    }

    public function dataProviderForKullbackLeiblerDivergence()
    {
        // Test data created using Python's scipi.stats.entropy
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
        Entropy::kullbackLeiblerDivergence($p, $q);
    }

    public function testKullbackLeiblerDivergenceExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        $p = [0.2, 0.2, 0.1];
        $q = [0.2, 0.4, 0.6];

        $this->setExpectedException('MathPHP\Exception\BadDataException');
        Entropy::kullbackLeiblerDivergence($p, $q);
    }
}
