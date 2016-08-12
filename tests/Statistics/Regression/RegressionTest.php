<?php
namespace Math\Statistics\Regression;

class RegressionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForR
     */
    public function testCorrelationCoefficient(array $points, $r)
    {
        $regression = new Linear($points);
        $this->assertEquals($r, $regression->correlationCoefficient(), '', 0.001);
    }

    /**
     * @dataProvider dataProviderForR
     */
    public function testR(array $points, $r)
    {
        $regression = new Linear($points);
        $this->assertEquals($r, $regression->r($points), '', 0.001);
    }

    public function dataProviderForR()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                0.993
            ],
            [
                [ [4,390], [9,580], [10,650], [14,730], [4,410], [7,530], [12,600], [22,790], [1,350], [3,400], [8,590], [11,640], [5,450], [6,520], [10,690], [11,690], [16,770], [13,700], [13,730], [10,640] ],
                0.9336
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForR2
     */
    public function testCoefficientOfDetermination(array $points, $r2)
    {
        $regression = new Linear($points);
        $this->assertEquals($r2, $regression->coefficientOfDetermination($points), '', 0.001);
    }

    /**
     * @dataProvider dataProviderForR2
     */
    public function testR2(array $points, $r2)
    {
        $regression = new Linear($points);
        $this->assertEquals($r2, $regression->r2($points), '', 0.001);
    }

    public function dataProviderForR2()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                0.986049
            ],
            [
                [ [4,390], [9,580], [10,650], [14,730], [4,410], [7,530], [12,600], [22,790], [1,350], [3,400], [8,590], [11,640], [5,450], [6,520], [10,690], [11,690], [16,770], [13,700], [13,730], [10,640] ],
                0.87160896
            ],
        ];
    }

    public function testToString()
    {
        $regression = new Linear([[1,2],[3,3],[3,4],[4,6]]);
        $this->assertTrue(is_string($regression->__toString()));
    }

    /**
     * @dataProvider dataProviderForSumOfSquaresTotal
     */
    public function testSumOfSquaresTotal(array $points, $SUStot)
    {
        $regression = new Linear($points);
        $this->assertEquals($SUStot, $regression->sumOfSquaresTotal(), '', 0.0001);
    }

    public function dataProviderForSumOfSquaresTotal()
    {
        return [
            [ [[1,3], [2,6], [3,7], [4,11], [5,12], [6,13], [7,17]], 136.8571],

        ];
    }

    /**
     * @dataProvider dataProviderForYHat()
     */
    public function testYHat(array $points, array $yhat)
    {
        $regression = new Linear($points);
        $this->assertEquals($yhat, $regression->yHat());
    }

    public function dataProviderForYHat()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ], // m = 1.2209302325581, b = 0.60465116279069
                [ 1.82558139534879, 3.04651162790689, 5.48837209302309, 6.70930232558119, 7.93023255813929] // evaluate y = mx + b
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForSumOfSquaresRegression
     */
    public function testSumOfSquaresRegression(array $points, $SSreg)
    {
        $regression = new Linear($points);
        $this->assertEquals($SSreg, $regression->sumOfSquaresRegression());
    }

    public function dataProviderForSumOfSquaresRegression()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ], // y mean = 5; yhat = [1.82558139534879, 3.04651162790689, 5.48837209302309, 6.70930232558119, 7.93023255813929]
                25.63953488371927                      // 10.07693347755574 + 3.81611681990299 + 0.23850730124375 + 2.92171444023726 + 8.58626284477953
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForSumOfSquaresResidual
     */
    public function testSumOfSquareResidual(array $points, $SSres)
    {
        $regression = new Linear($points);
        $this->assertEquals($SSres, $regression->sumOfSquaresResidual());
    }

    public function dataProviderForSumOfSquaresResidual()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ], // yhat = [1.82558139534879, 3.04651162790689, 5.48837209302309, 6.70930232558119, 7.93023255813929]
                0.36046511627907 // 0.03042184964848 + 0.00216333153055 + 0.23850730124375 + 0.0845051379125 + 0.00486749594379
            ]
        ];
    }
}
