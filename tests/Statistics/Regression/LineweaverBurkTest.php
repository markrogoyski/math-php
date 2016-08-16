<?php
namespace Math\Statistics\Regression;

class LineweaverBurkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForEquation
     * Equation matches pattern y = V * X / (K + x)
     */
    public function testGetEquation(array $points)
    {
        $regression = new LineweaverBurk($points);
        $this->assertRegExp('/^y = \d+[.]\d+x\/\(\d+[.]\d+\+x\)$/', $regression->getEquation());
    }

    public function dataProviderForEquation()
    {
        return [
            [
                [ [.038, .05], [.194, .127], [.425, .094], [.626, .2122], [1.253, .2729], [2.5, .2665], [3.740, .3317] ]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForParameters
     */
    public function testGetParameters(array $points, $V, $K)
    {
        $regression = new LineweaverBurk($points);
        $parameters = $regression->getParameters();
        $this->assertEquals($V, $parameters['V'], '', 0.0001);
        $this->assertEquals($K, $parameters['K'], '', 0.0001);
    }

    public function dataProviderForParameters()
    {
        return [
            [
                [ [.038, .05], [.194, .127], [.425, .094], [.626, .2122], [1.253, .2729], [2.5, .2665], [3.740, .3317] ],
                0.222903511, 0.13447224,
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForEvaluate
     */
    public function testEvaluate(array $points, $x, $y)
    {
        $regression = new LineweaverBurk($points);
        $this->assertEquals($y, $regression->evaluate($x), '', 0.0001);
    }

    public function dataProviderForEvaluate()
    {
        return [
            [
                [ [.038, .05], [.194, .127], [.425, .094], [.626, .2122], [1.253, .2729], [2.5, .2665], [3.740, .3317] ],
                0.038, 0.049111286,
            ],
        ];
    }
}
