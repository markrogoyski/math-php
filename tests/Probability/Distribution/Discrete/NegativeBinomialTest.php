<?php
namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\NegativeBinomial;

class NegativeBinomialTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     pmf
     * @dataProvider dataProviderForPMF
     * @param        int   $r
     * @param        float $p
     * @param        int   $x
     * @param        float $expectedPmf
     * @throws       \Exception
     */
    public function testPMF(int $r, float $p, int $x, float $expectedPmf)
    {
        // Given
        $negativeBinomial = new NegativeBinomial($r, $p);

        // When
        $pmf = $negativeBinomial->pmf($x);

        // Then
        $this->assertEquals($expectedPmf, $pmf, '', 0.001);
    }

    /**
     * @return array [r, p, x, pmf]
     * Data generated with R stats dnbinom(x, r, p)
     */
    public function dataProviderForPMF(): array
    {
        return [
            [1, 0.5, 2, 0.125],
            [1, 0.4, 2, 0.144],
            [2, 0.5, 3, 0.125],
            [2, 0.3, 3, 0.12348],
            [4, 0.95, 2, 0.02036266],
            [7, 0.6, 4, 0.1504936],
            [1, 0.2, 3, 0.1024],
            [1, 0.2, 7, 0.04194304],
            [40, 0.35, 65, 0.02448896],
        ];
    }
}
