<?php
namespace Math\Statistics\Regression;

class WeightedLinearTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForParameters
     */
    public function testGetParameters(array $points, $weights, $m, $b)
    {
        $regression = new WeightedLinear($points, $weights);
        $parameters = $regression->getParameters();
        $this->assertEquals($m, $parameters['m'], '', 0.0001);
        $this->assertEquals($b, $parameters['b'], '', 0.0001);
    }

    public function dataProviderForParameters()
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
