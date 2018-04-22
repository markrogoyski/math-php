<?php
namespace MathPHP\Tests\Statistics\Regression;

use MathPHP\Statistics\Regression\WeightedLinear;

class WeightedLinearTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     getParameters
     * @dataProvider dataProviderForParameters
     * @param        array $points
     * @param        array $weights
     * @param        float $m
     * @param        float $b
     */
    public function testGetParameters(array $points, array $weights, float $m, float $b)
    {
        $regression = new WeightedLinear($points, $weights);
        $parameters = $regression->getParameters();
        $this->assertEquals($m, $parameters['m'], '', 0.0001);
        $this->assertEquals($b, $parameters['b'], '', 0.0001);
    }

    /**
     * @return array [points, weights, m, b]
     */
    public function dataProviderForParameters(): array
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                [ 1, 1, 1, 1, 1],
                1.2209302325581, 0.60465116279069
            ],
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                [ 1, 2, 3, 4, 5],
                1.259717314, 0.439929329
            ],
        ];
    }
}
