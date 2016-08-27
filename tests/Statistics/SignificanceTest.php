<?php
namespace Math\Statistics;

class SignificanceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForZScore
     */
    public function testZScore($μ, $σ, $M, $z)
    {
        $this->assertEquals($z, Significance::zScore($M, $μ, $σ), '', 0.001);
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
        $this->assertEquals($z, Significance::zScore($M, $μ, $σ, false), '', 0.01);
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
     * @dataProvider dataProviderForZTest
     */
    public function testZTest($Hₐ, $n, $H₀, $σ, array $ztest)
    {
        $this->assertEquals($ztest, Significance::zTest($Hₐ, $n, $H₀, $σ), '', 0.001);
    }

    // Test data created from these sites:
    //  - http://www.socscistatistics.com/tests/ztest_sample_mean/Default2.aspx
    //  - https://www.easycalculation.com/statistics/p-value-for-z-score.php
    public function dataProviderForZTest()
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
     * @dataProvider dataProviderForTTest
     */
    public function testTTest($Hₐ, $s, $n, $H₀, array $ttest)
    {
        $this->assertEquals($ttest, Significance::tTest($Hₐ, $s, $n, $H₀), '', 0.001);
    }

    public function dataProviderForTTest()
    {
        return [
            [130.1, 21.21, 100, 120, ['t' => 4.762, 'p1' => 0, 'p2' => 0]],
            [280, 50, 15, 300, ['t' => -1.549, 'p1' => 0.0718, 'p2' => 0.1437]],
            [130.5, 32.4, 30, 142.1, ['t' => -1.9609820, 'p1' => 0.02977385, 'p2' => 0.05954770]],
            [25.12, 2.91, 18, 24.64, ['t' => 0.69981702, 'p1' => 0.24675380, 'p2' => 0.4935]]
        ];
    }
}
