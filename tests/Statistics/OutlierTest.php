<?php
namespace MathPHP\Tests\Statistics;

use MathPHP\Statistics\Outlier;

class OutlierTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @testCase     GrubbsTest
     */
    public function GrubbsTest()
    {
        $data = [199.31, 199.53, 200.19, 200.82, 201.92, 201.95, 202.18, 245.57];
        $G = Outlier::GrubbsStatistic($data, "upper");
        $this->assertEquals(2.4687, $G, '', 0.0001);
        $Gcrit = Outlier::CriticalGrubbs(.05, count($data), 1);
        $this->assertEquals(2.032, $Gcrit, '', 0.001);
    }
    
    public function TietjenMooreTest()
    {
        $data = [-1.40, -0.44, -0.30, -0.24, -0.22, -0.13, -0.05, 0.06, 0.10, 0.18, 0.20, 0.39, 0.48, 0.63, 1.01];
        $Ek = Outlier::TietjenMooreStatistic($data, 2, "two");
        $this->assertEquals(.292, $Ek, '', 0.001);
    }
}
