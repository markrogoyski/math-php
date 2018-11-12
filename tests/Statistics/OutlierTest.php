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
        $G = Significance::GrubbsStatistic($data);
        $this->assertEquals(2.4687, $G, '', 0.0001);
        $Gcrit = Significance::CriticalGrubbs(.05, count($data));
        $this->assertEquals(2.032, $Gcrit, '', 0.001);
    }
}
