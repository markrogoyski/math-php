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
}
