<?php

namespace MathPHP\Tests\Probability\Distribution\Discrete;

use MathPHP\Probability\Distribution\Discrete\Zipf;

class PoissonTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         pmf
     * @dataProvider dataProviderForPmf
     * @param        int $x
     * @param        int $s
     * @param        float $N
     * @param        float $expectedPmf
     *
     * R code to replicate:
     * library(sads)
     * dzipf(x=x, N=N, s=s)
     */
    public function testPmf(int $x, int $s, float $N, float $expectedPmf)
    {
        // Given
        $zipf = new Zipf($s, $N);

        // When
        $pmf = $zipf->pmf($x);

        // Then
        $this->assertEqualsWithDelta($expectedPmf, $pmf, 0.001);
    }

    /**
     * @return array [x, s, N, pmf]
     */
    public function dataProviderForPmf(): array
    {
        return [
            [1, 3, 10, 0.8350508],
            [2, 3, 10, 0.1043813],
            [3, 3, 10, 0.03092781],
            [4, 3, 10, 0.01304767],
            [4, 2, 10, 0.04032862],
            [4, 1, 10, 0.08535429],
            [4, 1, 8, 0.09198423],
        ];
    }

    /**
     * @test         cdf
     * @dataProvider dataProviderForCdf
     * @param        int $k
     * @param        float $λ
     * @param        float $expectedCdf
     *
     * R code to replicate:
     * library(sads)
     * pzipf(q=x, N=N, s=s)
     */
    public function testCdf(int $x, int $s, float $N, float $expectedCdf)
    {
        // Given
        $zipf = new Zipf($s, $N);

        // When
        $cdf = $zipf->cdf($x);

        // Then
        $this->assertEqualsWithDelta($expectedCdf, $cdf, 0.001);
    }

    /**
     * @return array[x, s, N, cdf]
     */
    public function dataProviderForCdf(): array
    {
        return [
            [1, 3, 10, 0.8350508],
            [2, 3, 10, 0.9394321],
            [3, 3, 10, 0.9703599],
            [4, 3, 10, 0.9834076],
            [4, 2, 10, 0.9185964],
            [4, 1, 10, 0.7112857],
            [4, 1, 8, 0.7665353],
        ];
    }
}
