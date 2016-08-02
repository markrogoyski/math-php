<?php
namespace Math\Statistics;

class CorrelationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataProviderForPopulationCovariance
     */
    public function testCovariancePopluation($X, $Y, $covariance)
    {
        $this->assertEquals($covariance, Correlation::covariance($X, $Y, true), '', 0.01);
    }

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
    public function testCovarianceSample($X, $Y, $covariance)
    {
        $this->assertEquals($covariance, Correlation::covariance($X, $Y), '', 0.01);
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
    public function testRPopulation(array $x, array $y, $pcc)
    {
        $this->assertEquals($pcc, Correlation::r($x, $y, true), '', 0.0001);
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
    public function testRSample(array $x, array $y, $scc)
    {
        $this->assertEquals($scc, Correlation::r($x, $y), '', 0.0001);
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

    /**
     * @dataProvider dataProviderForKendallsTau
     */
    public function testKendallsTau(array $X, array $Y, $τ)
    {
        $this->assertEquals($τ, Correlation::kendallsTau($X, $Y), '', 0.001);
    }

    public function dataProviderForKendallsTau()
    {
        return [
            // No ties for tau-a
            [
                [1, 2, 5, 3, 4],
                [1, 4, 2, 3, 5],
                0.2,
            ],

            [
                [2, 4, 7, 9],
                [4, 8, 7, 9],
                0.666667,
            ],
            [
                [5, 4, 3, 2],
                [4, 5, 6, 7],
                -1,
            ],
            [
                [85, 98, 90, 83, 57, 63, 77, 99, 80, 96, 69],
                [85, 95, 80, 75, 70, 65, 73, 93, 79, 88, 74],
                0.818,
            ],

            // Ties for tau-b
            [
                [4, 5, 5, 6, 5, 8],
                [4, 6, 7, 8, 7, 8],
                0.880705,
            ],
            [
                [12, 14, 14, 17, 19, 19, 19, 19, 19, 20, 21, 21, 21, 21, 21, 22, 23, 24, 24, 24, 26, 26, 27],
                [11, 4, 4, 2, 0, 0, 0, 0, 0, 0, 4, 0, 4, 0, 0, 0, 0, 4, 0, 0, 0, 0, 0],
                -0.376201540231705,
            ],
            [
                [0.7, 0.8, 0.8, 0.8, 1.2, 1.3, 1.6, 1.8, 1.9, 2.4, 2.5, 2.7, 2.9, 2.9, 3.9, 5.8, 6.5, 7.9, 9.1],
                [300, 211, 227, 297, 199, 285, 207, 167, 266, 191, 211, 172, 131, 220, 167, 115, 86, 107, 71],
                -0.696
            ],
        ];
    }

    public function testKendallsTauExceptionDifferentLengthArrays()
    {
        $X = [1, 2, 3];
        $Y = [2, 3, 4, 5];

        $this->setExpectedException('\Exception');
        Correlation::kendallsTau($X, $Y);
    }

    /**
     * @dataProvider dataProviderForSpearmansRho
     */
    public function testSpearmansRho(array $X, array $Y, $ρ)
    {
        $this->assertEquals($ρ, Correlation::spearmansRho($X, $Y), '', 0.001);
    }

    public function dataProviderForSpearmansRho()
    {
        return [
            [
                [56, 75, 45, 71, 62, 64, 58, 80, 76, 61],
                [66, 70, 40, 60, 65, 56, 59, 77, 67, 63],
                0.6727
            ],
            [
                [1, 2, 3, 4, 5],
                [2, 3, 4, 4, 6],
                0.975
            ],
            [
                [4, 10, 3, 1, 9, 2, 6, 7, 8, 5],
                [5, 8, 6, 2, 10, 3, 9, 4, 7, 1],
                0.6848
            ],
            [
                [13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25],
                [26, 25, 18, 33, 70, 55, 50, 49, 70, 80, 76, 74, 73],
                0.8583
            ],
            [
                [1, 5, 2, 5, 2],
                [2, 2, 3, 1, 3],
                -0.325
            ]
        ];
    }

    public function testSpearmansRhoExceptionDifferentLengthArrays()
    {
        $X = [1, 2, 3];
        $Y = [2, 3, 4, 5];

        $this->setExpectedException('\Exception');
        Correlation::spearmansRho($X, $Y);
    }

    public function testDescribe()
    {
        $X = [1, 2, 3, 4, 5];
        $Y = [2, 3, 4, 4, 6];
        $stats = Correlation::describe($X, $Y);

        $this->assertTrue(is_array($stats));
        $this->assertArrayHasKey('cov', $stats);
        $this->assertArrayHasKey('r', $stats);
        $this->assertArrayHasKey('R2', $stats);
        $this->assertArrayHasKey('tau', $stats);
        $this->assertArrayHasKey('rho', $stats);
        $this->assertTrue(is_numeric($stats['cov']));
        $this->assertTrue(is_numeric($stats['r']));
        $this->assertTrue(is_numeric($stats['R2']));
        $this->assertTrue(is_numeric($stats['tau']));
        $this->assertTrue(is_numeric($stats['rho']));
    }
}
