<?php
namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\Hypergeometric;
use MathPHP\Exception;

class HypergeometricTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pmf returns expected probability
     * @dataProvider dataProviderForPmf
     * @param  int   $N population size
     * @param  int   $K number of success states in the population
     * @param  int   $n number of draws
     * @param  int   $k number of observed successes
     * @param  float $pmf
     */
    public function testPmf(int $N, int $K, int $n, int $k, float $pmf)
    {
        $hypergeometric = new Hypergeometric($N, $K, $n);
        $this->assertEquals($pmf, $hypergeometric->pmf($k), '', 0.0000001);
    }

    /**
     * Test data made with: http://stattrek.com/m/online-calculator/hypergeometric.aspx
     * @return array
     */
    public function dataProviderForPmf(): array
    {
        return [
            [50, 5, 10, 4, 0.00396458305801507],
            [50, 5, 10, 5, 0.000118937491740452],
            [100, 80, 50, 40, 0.196871217706549],
            [100, 80, 50, 35, 0.00889760379503624],
            [48, 6, 15, 2, 0.350128003786331],
            [48, 6, 15, 0, 0.0902552187538097],
            [48, 6, 15, 6, 0.000407855201543217],
            [100, 30, 20, 5, 0.19182559242904654583],
        ];
    }

    /**
     * @testCase     cdf returns expected probability
     * @dataProvider dataProviderForCdf
     * @param  int   $N population size
     * @param  int   $K number of success states in the population
     * @param  int   $n number of draws
     * @param  int   $k number of observed successes
     * @param  float $cdf
     */
    public function testCdf(int $N, int $K, int $n, int $k, float $cdf)
    {
        $hypergeometric = new Hypergeometric($N, $K, $n);
        $this->assertEquals($cdf, $hypergeometric->cdf($k), '', 0.0000001);
    }

    /**
     * Test data made with: http://stattrek.com/m/online-calculator/hypergeometric.aspx
     * @return array
     */
    public function dataProviderForCdf(): array
    {
        return [
            [50, 5, 10, 4, 0.000118937],
            [100, 80, 50, 40, 0.401564391],
            [100, 80, 50, 35, 0.988582509],
            [48, 6, 15, 2, 0.269510717],
            [48, 6, 15, 0, 0.909744781],
            [100, 30, 20, 5, 0.599011207],
        ];
    }

    /**
     * @testCase     mean returns expected average
     * @dataProvider dataProviderForMean
     * @param  int   $N population size
     * @param  int   $K number of success states in the population
     * @param  int   $n number of draws
     * @param  float $mean
     */
    public function testMean(int $N, int $K, int $n, float $mean)
    {
        $hypergeometric = new Hypergeometric($N, $K, $n);
        $this->assertEquals($mean, $hypergeometric->mean(), '', 0.0000001);
    }

    /**
     * Test data made with: http://keisan.casio.com/exec/system/1180573201
     * @return array
     */
    public function dataProviderForMean(): array
    {
        return [
            [50, 5, 10, 1],
            [50, 5, 10, 1],
            [100, 80, 50, 40],
            [100, 80, 50, 40],
            [48, 6, 15, 1.875],
            [48, 6, 15, 1.875],
            [48, 6, 15, 1.875],
            [100, 30, 20, 6],
        ];
    }
}
