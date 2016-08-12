<?php
namespace Math\Statistics\Regression;

class LinearTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $regression = new Linear($points);
        $this->assertInstanceOf('Math\Statistics\Regression\Regression', $regression);
        $this->assertInstanceOf('Math\Statistics\Regression\Linear', $regression);
    }

    public function testGetPoints()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $regression = new Linear($points);
        $this->assertEquals($points, $regression->getPoints());
    }

    public function testGetXs()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $regression = new Linear($points);
        $this->assertEquals([1,2,4,5,6], $regression->getXs());
    }

    public function testGetYs()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $regression = new Linear($points);
        $this->assertEquals([2,3,5,7,8], $regression->getYs());
    }

    /**
     * @dataProvider dataProviderForEquation
     * Equation matches pattern y = mx + b
     */
    public function testGetEquation(array $points)
    {
        $regression = new Linear($points);
        $this->assertRegExp('/^y = \d+[.]\d+x [+] \d+[.]\d+$/', $regression->getEquation());
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
    public function testGetParameters(array $points, $m, $b)
    {
        $regression = new Linear($points);
        $parameters = $regression->getParameters();
        $this->assertEquals($m, $parameters['m'], '', 0.0001);
        $this->assertEquals($b, $parameters['b'], '', 0.0001);
    }

    public function dataProviderForParameters()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                1.2209302325581, 0.60465116279069
            ],
            [
                [ [4,390], [9,580], [10,650], [14,730], [4,410], [7,530], [12,600], [22,790], [1,350], [3,400], [8,590], [11,640], [5,450], [6,520], [10,690], [11,690], [16,770], [13,700], [13,730], [10,640] ],
                25.326467777896, 353.16487949889
            ],
            // Example data from http://reliawiki.org/index.php/Simple_Linear_Regression_Analysis
            [
                [ [50,122], [53,118], [54,128], [55,121], [56,125], [59,136], [62,144], [65,142], [67,149], [71,161], [72,167], [74,168], [75,162], [76,171], [79,175], [80,182], [82,180], [85,183], [87,188], [90,200], [93,194], [94,206], [95,207], [97,210], [100,219] ],
                1.9952, 17.0016
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForSampleSize
     */
    public function testGetSampleSize(array $points, $n)
    {
        $regression = new Linear($points);
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
        $regression = new Linear($points);
        $this->assertEquals($y, $regression->evaluate($x), '', 0.01);
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
                5, 13.5
            ],
            [
                [ [0,2], [1,1], [2,0], [3,-1] ], // y = -x + 2
                4, -2
            ],
            // Example data from http://reliawiki.org/index.php/Simple_Linear_Regression_Analysis
            [
                [ [50,122], [53,118], [54,128], [55,121], [56,125], [59,136], [62,144], [65,142], [67,149], [71,161], [72,167], [74,168], [75,162], [76,171], [79,175], [80,182], [82,180], [85,183], [87,188], [90,200], [93,194], [94,206], [95,207], [97,210], [100,219] ],
                93, 202.5552
            ],
        ];
    }
    
    /**
     * @dataProvider dataProviderForCI
     */
    public function testGetCI(array $points, $x, $p, $ci)
    {
        $regression = new Linear($points);
        $this->assertEquals($ci, $regression->getCI($x, $p), '', .0000001);
    }
    
    public function dataProviderForCI()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                2, .05, 0.651543596,
            ],
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                3, .05, 0.518513005,
            ],
            [
               [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                3, .1, 0.383431307,
            ],
        ];
    }
    
    /**
     * @dataProvider dataProviderForPI
     */
    public function testGetPI(array $points, $x, $p, $q, $pi)
    {
        $regression = new Linear($points);
        $this->assertEquals($pi, $regression->getPI($x, $p, $q), '', .0000001);
    }
    
    public function dataProviderForPI()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                2, .05, 1, 1.281185007,
            ],
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                3, .05, 1, 1.218926455,
            ],
            [
               [ [1,2], [2,3], [4,5], [5,7], [6,8] ],  // when q gets large, pi approaches ci.
                3, .1, 10000000, 0.383431394
            ],
        ];
    }
    
    /**
     * @dataProvider dataProviderForFProbability
     */
    public function testFProbability(array $points, $probability)
    {
        $regression = new Linear($points);
        $Fprob = $regression->FProbability();
        $this->assertEquals($probability, $Fprob, '', .0000001);
    }
    
    public function dataProviderForFProbability()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                .999304272,
            ],
        ];
    }
    
    /**
     * @dataProvider dataProviderForTProbability
     */
    public function testTProbability(array $points, $beta0, $beta1)
    {
        $regression = new Linear($points);
        $Tprob = $regression->tProbability();
        $this->assertEquals($beta0, $Tprob['m'], '', .0000001);
        $this->assertEquals($beta1, $Tprob['b'], '', .0000001);
    }
    
    public function dataProviderForTProbability()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                0.999652136, 0.913994632,
            ],
        ];
    }
    
}
