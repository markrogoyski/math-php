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
            // Test data created from: http://www.shannonentropy.netmark.pl/
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
            // Test data from http://www.csun.edu/~twang/595DM/Slides/Information%20&%20Entropy.pdf
            [
                [4/9, 3/9, 2/9],
                1.5304755,
            ],
        ];
    }

    public function testShannonEntropyExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        $p = [0.2, 0.2, 0.1];

        $this->setExpectedException('MathPHP\Exception\BadDataException');
        Entropy::shannonEntropy($p);
    }

    /**
     * @dataProvider dataProviderForShannonNatEntropy
     */
    public function testShannonNatEntropy(array $p, $expected)
    {
        $H = Entropy::shannonNatEntropy($p);

        $this->assertEquals($expected, $H, '', 0.000001);
    }

    public function dataProviderForShannonNatEntropy()
    {
        return [
            [
                [1],
                0
            ],
            [
                [0.6, 0.4],
                0.67301166700925,
            ],
            [
                [0.514, 0.486],
                0.69275512932254,
            ],
            [
                [0.231, 0.385, 0.308, 0.077],
                1.2661221087912,
            ],
            [
                [4/9, 3/9, 2/9],
                1.06085694715802,
            ],
        ];
    }

    public function testShannonNatEntropyExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        $p = [0.2, 0.2, 0.1];

        $this->setExpectedException('MathPHP\Exception\BadDataException');
        Entropy::shannonNatEntropy($p);
    }

    /**
     * @dataProvider dataProviderForShannonHartleyEntropy
     */
    public function testShannonHartleyEntropy(array $p, $expected)
    {
        $H = Entropy::shannonHartleyEntropy($p);

        $this->assertEquals($expected, $H, '', 0.000001);
    }

    public function dataProviderForShannonHartleyEntropy()
    {
        return [
            [
                [1],
                0
            ],
            [
                [0.6, 0.4],
                0.29228525323863,
            ],
            [
                [0.514, 0.486],
                0.30085972997496,
            ],
            [
                [0.231, 0.385, 0.308, 0.077],
                0.54986984526372,
            ],
            [
                [4/9, 3/9, 2/9],
                0.46072431823946,
            ],
        ];
    }

    public function testShannonHartleyEntropyExceptionNotProbabilityDistributionThatAddsUpToOne()
    {
        $p = [0.2, 0.2, 0.1];

        $this->setExpectedException('MathPHP\Exception\BadDataException');
        Entropy::shannonHartleyEntropy($p);
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
