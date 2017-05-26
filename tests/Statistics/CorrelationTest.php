<?php
namespace MathPHP\Tests\Statistics;

use MathPHP\Statistics\Correlation;
use MathPHP\Exception;
use MathPHP\LinearAlgebra\MatrixFactory;

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
        $this->expectException(Exception\BadDataException::class);
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
        $this->expectException(Exception\BadDataException::class);
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

        $this->expectException(Exception\BadDataException::class);
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

        $this->expectException(Exception\BadDataException::class);
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
        $this->assertArrayHasKey('r2', $stats);
        $this->assertArrayHasKey('tau', $stats);
        $this->assertArrayHasKey('rho', $stats);
        $this->assertTrue(is_numeric($stats['cov']));
        $this->assertTrue(is_numeric($stats['r']));
        $this->assertTrue(is_numeric($stats['r2']));
        $this->assertTrue(is_numeric($stats['tau']));
        $this->assertTrue(is_numeric($stats['rho']));
    }

    /**
     * @dataProvider dataProviderForEllipse
     */
    public function testEllipse(array $data, $sd, array $results)
    {
        $calc = Correlation::ConfidenceEllipse(array_column($data, 0), array_column($data, 1), $sd);
        $this->assertEquals($results, $calc, '', 0.0001);
    }

    public function dataProviderForEllipse()
    {
        return [
            [ // Test1
                [
                    [1.00787, 1.09905],
                    [1.23724, 0.98834],
                    [1.02175, 0.67245],
                    [0.88458, 0.36003],
                    [0.66582, 1.22097],
                    [1.24408, 0.59735],
                    [1.03421, 0.88595],
                    [1.66279, 0.84183],
                ],
                1,
                [
                    [1.47449429236742, 0.555004169940273],
                    [1.54091626950741, 0.797745563446301],
                    [1.43693412988479, 1.05404701259189],
                    [1.20226551661247, 1.22601007516927],
                    [0.926545863867666, 1.24795070608341],
                    [0.71509070763258, 1.11148833005973],
                    [0.648668730492593, 0.868746936553699],
                    [0.752650870115211, 0.612445487408114],
                    [0.987319483387533, 0.440482424830733],
                    [1.26303913613233, 0.418541793916591],
                    [1.47449429236742, 0.555004169940273],
                ],
            ],
        ];
    }
}
