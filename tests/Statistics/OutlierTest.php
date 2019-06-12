<?php
namespace MathPHP\Tests\Statistics;

use MathPHP\Exception;
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
    
    /**
     * @testCase     Tails must be 1 or 2
     */
    public function testGrubbsException()
    {
        $this->expectException(Exception\BadParameterException::class);
        $Gcrit = Outlier::CriticalGrubbs(.05, 10, 3);
    }
}
