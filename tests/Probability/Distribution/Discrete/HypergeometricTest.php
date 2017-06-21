<?php
namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\Hypergeometric;
use MathPHP\Exception;

class HypergeometricTest extends \PHPUnit_Framework_TestCase
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
        $this->assertEquals($pmf, Hypergeometric::pmf($N, $K, $n, $k), '', 0.0000001);
    }

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
        ];
    }
}
