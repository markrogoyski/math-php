<?php
namespace Math\Statistics;

class SignificanceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForZScore
     */
    public function testZScore($μ, $σ, $M, $z)
    {
        $this->assertEquals($z, Significance::zScore($M, $μ, $σ, Significance::Z_TABLE_VALUE), '', 0.001);
    }

    public function dataProviderForZScore()
    {
        return [
            [1, 1, 1, 0],
            [1, 1, 2, 1],
            [4, 0.5, 5.5, 3.0],
            [4, 0.5, 3, -2.0],
            [3.6, 0.4, 3.3, -0.75],
            [943, 36.8, 1032.44, 2.43],
            [943, 36.8, 803.2, -3.80],
        ];
    }

    /**
     * @dataProvider dataProviderForZScoreRaw
     */
    public function testZScoreRaw($μ, $σ, $M, $z)
    {
        $this->assertEquals($z, Significance::zScore($M, $μ, $σ, Significance::Z_RAW_VALUE), '', 0.01);
    }

    public function dataProviderForZScoreRaw()
    {
        return [
            [1, 1, 1, 0],
            [1, 1, 2, 1],
            [4, 0.5, 5.5, 3.0],
            [4, 0.5, 3, -2.0],
            [3.6, 0.4, 3.3, -0.75],
            [943, 36.8, 1032.44, 2.43],
            [943, 36.8, 803.2, -3.80434783],
        ];
    }

    /**
     * @dataProvider dataProviderForSEM
     */
    public function testSEM($σ, int $n, $sem)
    {
        $this->assertEquals($sem, Significance::sem($σ, $n), '', 0.0001);
    }

    public function dataProviderForSEM()
    {
        return [
            [5, 100, 0.5],
            [6, 200, 0.4242640687119],
            [5, 35, 0.8451542547285],
        ];
    }

    /**
     * @dataProvider dataProviderForZTestOneSample
     */
    public function testZTestOneSample($Hₐ, $n, $H₀, $σ, array $ztest)
    {
        $this->assertEquals($ztest, Significance::zTest($Hₐ, $n, $H₀, $σ), '', 0.001);
    }

    // Test data created from these sites:
    //  - http://www.socscistatistics.com/tests/ztest_sample_mean/Default2.aspx
    //  - https://www.easycalculation.com/statistics/p-value-for-z-score.php
    public function dataProviderForZTestOneSample()
    {
        return [
            [96, 55, 100, 12, ['z' => -2.4720661623652, 'p1' => 0.00676, 'p2' => 0.013436]],
            [83, 40, 80, 5, ['z' => 3.79473, 'p1' => 0.0001, 'p2' => 0.0001]],
            [20, 200, 19.2, 6, ['z' => 1.88562, 'p1' => 0.02938, 'p2' => 0.0593]],
            [22.875, 35, 19.5, 5, ['z' => 3.99335, 'p1' => 0.0001, 'p2' => 0.0001]],
            [112, 30, 100, 15, ['z' => 4.38178, 'p1' => 0.0000, 'p2' => 0.0000]],
            [18.9, 200, 21, 5, ['z' => -5.9397, 'p1' => 0.0000, 'p2' => 0.0000]],
            [6.7, 29, 5, 7.1, ['z' => 1.28941, 'p1' => 0.0986, 'p2' => 0.1973]],
            [80.94, 25, 85, 11.6, ['z' => -1.75, 'p1' => 0.0401, 'p2' => 0.080118]],
        ];
    }

    /**
     * @dataProvider dataProviderFortTestTwoSample
     */
    public function testtTestTwoSample($μ₁, $μ₂, $n₁, $n₂, $σ₁, $σ₂, $Δ, array $ztest)
    {
        $this->assertEquals($ztest, Significance::tTestTwoSample($μ₁, $μ₂, $n₁, $n₂, $σ₁, $σ₂, $Δ), '', 0.001);
    }

    /**
     * Used for calculating t and p values for test data:
     * http://www.usablestats.com/calcs/2samplet&summary=1
     * http://www.socscistatistics.com/pvalues/tdistribution.aspx
     */
    public function dataProviderForTTestTwoSample()
    {
        return [
            [3100, 2750, 75, 75, 420, 425, 0, ['t' => 5.0728, 'p1' => 0.0000, 'p2' => 0.0000]],
            [20.23, 18.68, 7, 5, 2.74, 1.64, 0, ['t' => 1.2214, 'p1' => 0.124974, 'p2' => 0.249948]], // example data: https://www.isixsigma.com/tools-templates/hypothesis-testing/making-sense-two-sample-t-test/
            [42.14, 43.23, 10, 10, 0.683, 0.750, 0, ['t' => -3.3978, 'p1' => 0.001604, 'p2' => 0.003207]], // example data: https://onlinecourses.science.psu.edu/stat500/node/50
            [2.840, 2.9808, 17, 13, 0.520, 0.3093, 0, ['t' => -0.9233, 'p1' => 0.181947, 'p2' => 0.363893]], // example data: https://onlinecourses.science.psu.edu/stat500/node/50
        ];
    }

    /**
     * @dataProvider dataProviderForTScore
     */
    public function testTScore($Hₐ, $s, $n, $H₀, $t)
    {
        $this->assertEquals($t, Significance::tScore($Hₐ, $s, $n, $H₀), '', 0.001);
    }

    public function dataProviderForTScore()
    {
        return [
            [130.1, 21.21, 100, 120, 4.762],
            [280, 50, 15, 300, -1.549],
        ];
    }

    /**
     * @dataProvider dataProviderForTTestOneSample
     */
    public function testTTest($Hₐ, $s, $n, $H₀, array $ttest)
    {
        $this->assertEquals($ttest, Significance::tTestOneSample($Hₐ, $s, $n, $H₀), '', 0.001);
    }

    public function dataProviderForTTestOneSample()
    {
        return [
            [130.1, 21.21, 100, 120, ['t' => 4.762, 'p1' => 0, 'p2' => 0]],
            [280, 50, 15, 300, ['t' => -1.549, 'p1' => 0.0718, 'p2' => 0.1437]],
            [130.5, 32.4, 30, 142.1, ['t' => -1.9609820, 'p1' => 0.02977385, 'p2' => 0.05954770]],
            [25.12, 2.91, 18, 24.64, ['t' => 0.69981702, 'p1' => 0.24675380, 'p2' => 0.4935]]
        ];
    }

    /**
     * @dataProvider dataProviderForChiSquaredTest
     */
    public function testChiSquaredTest(array $observed, array $expected, $χ², $p)
    {
        $chi = Significance::chiSquaredTest($observed, $expected);

        $this->assertEquals($χ², $chi['chi-square'], '', 0.0001);
        $this->assertEquals($p, $chi['p'], '', 0.0001);
    }

    public function dataProviderForChiSquaredTest()
    {
        return [
            // Example data from Statistics (Freedman, Pisani, Purves)
            [
                [4, 6, 17, 16, 8, 9],
                [10, 10, 10, 10, 10, 10],
                14.2, 0.014388,
            ],
            [
                [5, 7, 17, 16, 8, 7],
                [10, 10, 10, 10, 10, 10],
                13.2, 0.0216,
            ],
            [
                [9, 11, 10, 8, 12, 10],
                [10, 10, 10, 10, 10, 10],
                1.0, 0.962566,
            ],
            [
                [90, 110, 100, 80, 120, 100],
                [100, 100, 100, 100, 100, 100],
                10.0, 0.075235,
            ],
            [
                [10287, 10056, 9708, 10080, 9935, 9934],
                [10000, 10000, 10000, 10000, 10000, 10000],
                18.575, 0.0023,
            ],
        ];
    }

    public function testChiSquaredTestExceptionCountsDiffer()
    {
        $observed = [1, 2, 3, 4];
        $expected = [1, 2, 3];

        $this->setExpectedException('\Exception');
        Significance::chiSquaredTest($observed, $expected);
    }
}
