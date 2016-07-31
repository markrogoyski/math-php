<?php
namespace Math\Statistics;

class CorrelationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataProviderForPopulationCovariance
     */
    public function testPopulationCovariance($X, $Y, $covariance)
    {
        $this->assertEquals($covariance, Correlation::populationCovariance($X, $Y), '', 0.01);
    }

    /**
     * Data provider for population covariance test
     * Data: [ X, Y, covariance ]
     */
    public function dataProviderForPopulationCovariance()
    {
        return [
            [ [ 1, 2, 3, 4 ], [ 2, 3, 4, 5 ], 1.25 ],
            [ [ 1, 2, 4, 7, 9, 10 ], [ 2, 3, 5, 8, 11, 12.5 ], 13.29167 ],
            [ [ 1, 3, 2, 5, 8, 7, 12, 2, 4], [ 8, 6, 9, 4, 3, 3, 2, 7, 7 ], -7.1728 ],
        ];
    }

    public function testPopulationCovarianceExceptionWhenXAndYHaveDifferentCounts()
    {
        $this->setExpectedException('\Exception');
        Correlation::populationCovariance([ 1, 2 ], [ 2, 3, 4 ]);
    }

    /**
     * @dataProvider dataProviderForSampleCovariance
     */
    public function testSampleCovariance($X, $Y, $covariance)
    {
        $this->assertEquals($covariance, Correlation::sampleCovariance($X, $Y), '', 0.01);
    }

    /**
     * Data provider for sample covariance test
     * Data: [ X, Y, covariance ]
     */
    public function dataProviderForSampleCovariance()
    {
        return [
            [ [ 1, 2, 3, 4 ], [ 2, 3, 4, 5 ], 1.66667 ],
            [ [ 1, 2, 4, 7, 9, 10 ], [ 2, 3, 5, 8, 11, 12.5 ], 15.95 ],
            [ [ 1, 3, 2, 5, 8, 7, 12, 2, 4], [ 8, 6, 9, 4, 3, 3, 2, 7, 7 ], -8.0694 ],
        ];
    }

    public function testSampleCovarianceExceptionWhenXAndYHaveDifferentCounts()
    {
        $this->setExpectedException('\Exception');
        Correlation::sampleCovariance([ 1, 2 ], [ 2, 3, 4 ]);
    }

    /**
     * @dataProvider dataProviderForPopulationCorrelationCoefficient
     */
    public function testPopulationCorrelationCoefficient(array $x, array $y, $pcc)
    {
        $this->assertEquals($pcc, Correlation::populationCorrelationCoefficient($x, $y), '', 0.0001);
    }

    /**
     * Data provider for population correlation coefficient test
     * Data: [ x, y, ppc ]
     */
    public function dataProviderForPopulationCorrelationCoefficient()
    {
        return [
            [ [ 1, 2, 4, 5, 8 ], [ 5, 20, 40, 80, 100 ], 0.96841 ],
            [ [ 1, 2, 4, 5, 8 ], [ 5, 20, 30, 50, 120 ], 0.96359 ],
        ];
    }

    /**
     * @dataProvider dataProviderForSampleCorrelationCoefficient
     */
    public function testSampleCorrelationCoefficient(array $x, array $y, $scc)
    {
        $this->assertEquals($scc, Correlation::sampleCorrelationCoefficient($x, $y), '', 0.0001);
    }

    /**
     * Data provider for sample correlation coefficient test
     * Data: [ x, y, ppc ]
     */
    public function dataProviderForSampleCorrelationCoefficient()
    {
        return [
            [ [ 1, 2, 4, 5, 8 ], [ 5, 20, 40, 80, 100 ], 0.9684 ],
            [ [ 1, 2, 4, 5, 8 ], [ 5, 20, 30, 50, 120 ], 0.9636 ],
        ];
    }

    /**
     * @dataProvider dataProviderForR2
     */
    public function testCoefficientOfDetermination(array $X, array $Y, $r2)
    {
        $this->assertEquals($r2, Correlation::coefficientOfDetermination($X, $Y), '', 0.001);
    }

    /**
     * @dataProvider dataProviderForR2
     */
    public function testR2(array $X, array $Y, $r2)
    {
        $this->assertEquals($r2, Correlation::r2($X, $Y), '', 0.001);
    }

    public function dataProviderForR2()
    {
        return [
            [
                [1,2,4,5,6],
                [2,3,5,7,8],
                0.986049
            ],
            [
                [4,9,10,14,4,7,12,22,1,3,8,11,5,6,10,11,16,13,13,10,],
                [390,580,650,730,410,530,600,790,350,400,590,640,450,520,690,690,770,700,730,640],
                0.87160896
            ],
        ];
    }
}
