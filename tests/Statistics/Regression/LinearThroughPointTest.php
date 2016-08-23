<?php
namespace Math\Statistics\Regression;

class LinearThroughPointTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $force = [0,0];
        $regression = new LinearThroughPoint($points, $force);
        $this->assertInstanceOf('Math\Statistics\Regression\Regression', $regression);
        $this->assertInstanceOf('Math\Statistics\Regression\LinearThroughPoint', $regression);
    }

    public function testGetPoints()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $force = [0,0];
        $regression = new LinearThroughPoint($points, $force);
        $this->assertEquals($points, $regression->getPoints());
    }

    public function testGetXs()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $force = [0,0];
        $regression = new LinearThroughPoint($points, $force);
        $this->assertEquals([1,2,4,5,6], $regression->getXs());
    }

    public function testGetYs()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $force = [0,0];
        $regression = new LinearThroughPoint($points, $force);
        $this->assertEquals([2,3,5,7,8], $regression->getYs());
    }

    /**
     * @dataProvider dataProviderForEquation
     * Equation matches pattern y = mx + b
     */
    public function testGetEquation(array $points)
    {
        $force = [0,0];
        $regression = new LinearThroughPoint($points, $force);
        $this->assertRegExp('/^y = [-]?\d+[.]\d+x [+\-] \d+[.]\d+$/', $regression->getEquation());
    }

    public function dataProviderForEquation()
    {
        return [
            [ [ [0,0], [1,1], [2,2], [3,3], [4,4] ] ],
            [ [ [1,2], [2,3], [4,5], [5,7], [6,8] ] ],
            [ [ [4,390], [9,580], [10,650], [14,730], [4,410], [7,530], [12,600], [22,790], [1,350], [3,400], [8,590], [11,640], [5,450], [6,520], [10,690], [11,690], [16,770], [13,700], [13,730], [10,640] ] ],
        ];
    }

    /**
     * @dataProvider dataProviderForParameters
     */
    public function testGetParameters(array $points, array $force_point, $m, $b)
    {
        $regression = new LinearThroughPoint($points, $force_point);
        $parameters = $regression->getParameters();
        $this->assertEquals($m, $parameters['m'], '', 0.0001);
        $this->assertEquals($b, $parameters['b'], '', 0.0001);
    }

    public function dataProviderForParameters()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ], [0,0],
                1.35365853658537, 0
            ],
            [
                [ [4,390], [9,580], [10,650], [14,730], [4,410], [7,530], [12,600], [22,790], [1,350], [3,400], [8,590], [11,640], [5,450], [6,520], [10,690], [11,690], [16,770], [13,700], [13,730], [10,640] ], [0,0],
                54.9003101462118, 0
            ],
            [
                [ [11,15], [17,23], [23,31], [29,39] ], [0,0],
                1.348314, 0
            ],
            [
                [ [100, 140], [200,230], [300,310], [400,400], [500,480] ], [300, 310],
                0.85, 55
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForSampleSize
     */
    public function testGetSampleSize(array $points, $n)
    {
        $force = [0,0];
        $regression = new LinearThroughPoint($points, $force);
        $this->assertEquals($n, $regression->getSampleSize());
    }

    public function dataProviderForSampleSize()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ], 5
            ],
            [
                [ [4,390], [9,580], [10,650], [14,730], [4,410], [7,530], [12,600], [22,790], [1,350], [3,400], [8,590], [11,640], [5,450], [6,520], [10,690], [11,690], [16,770], [13,700], [13,730], [10,640] ], 20
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForEvaluate
     */
    public function testEvaluate(array $points, $x, $y)
    {
        $force = [0,0];
        $regression = new LinearThroughPoint($points, $force);
        $this->assertEquals($y, $regression->evaluate($x), '', 0.0001);
    }

    public function dataProviderForEvaluate()
    {
        return [
            [
                [ [0,0], [1,1], [2,2], [3,3], [4,4] ], // y = x + 0
                5, 5,
            ],
            [
                [ [0,0], [1,1], [2,2], [3,3], [4,4] ], // y = x + 0
                18, 18,
            ],
            [
                [ [0,0], [1,2], [2,4], [3,6] ], // y = 2x + 0
                4, 8,
            ],
            [
                [ [0,1], [1,3.5], [2,6] ], // y = 2.5x + 1
                5, 15.5
            ],
            [
                [ [0,2], [1,1], [2,0], [3,-1] ], // y = -x + 2
                4, -0.571428571
            ],
        ];
    }
    
    /**
     * @dataProvider dataProviderForCI
     */
    public function testCI(array $points, $x, $p, $ci)
    {
        $regression = new LinearThroughPoint($points);
        $this->assertEquals($ci, $regression->CI($x, $p), '', .0000001);
    }
    
    public function dataProviderForCI()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                2, .05, 0.2644479205,
            ],
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                3, .05, 0.3966718808,
            ],
            [
               [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                3, .1, 0.3045778477,
            ],
        ];
    }
    
    /**
     * @dataProvider dataProviderForPI
     */
    public function testPI(array $points, $x, $p, $q, $pi)
    {
        $regression = new LinearThroughPoint($points);
        $this->assertEquals($pi, $regression->PI($x, $p, $q), '', .0000001);
    }
    
    public function dataProviderForPI()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                2, .05, 1, 1.226194563,
            ],
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                3, .05, 1, 1.261336191,
            ],
            [
               [ [1,2], [2,3], [4,5], [5,7], [6,8] ],  // when q gets large, pi approaches ci.
                3, .1, 10000000, 0.3045779864,
            ],
        ];
    }
    
    /**
     * @dataProvider dataProviderForSumSquares
     */
    public function testSumSquares(array $points, $force, $sums)
    {
        $regression = new LinearThroughPoint($points, $force);
        $this->assertEquals($sums['sse'], $regression->sumOfSquaresResidual(), '', .0000001);
        $this->assertEquals($sums['ssr'], $regression->sumOfSquaresRegression(), '', .0000001);
        $this->assertEquals($sums['sst'], $regression->sumOfSquaresTotal(), '', .0000001);
    }
    
    public function dataProviderForSumSquares()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                [0,0],
                [
                    'sse' => 0.743902439,
                    'ssr' => 150.2560976,
                    'sst' => 151,
                ],
            ],
            [
                [ [2,3], [3,4], [5,6], [6,8], [7,9] ],
                [1,1],
                [
                    'sse' => 0.743902439,
                    'ssr' => 150.2560976,
                    'sst' => 151,
                ],
            ],
        ];
    }
}
