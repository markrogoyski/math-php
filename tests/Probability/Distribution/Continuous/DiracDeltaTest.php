<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\DiracDelta;

class DiracDeltaTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         pdf
     * @dataProvider dataProviderForPdf
     * @param        float $x
     * @param        float $pdf
     */
    public function testPdf(float $x, float $pdf)
    {
        $dirac = new DiracDelta();
        $this->assertEquals($pdf, $dirac->pdf($x), '', 0.00001);
    }

    /**
     * @return array [x, pdf]
     */
    public function dataProviderForPdf(): array
    {
        return [
            [-100, 0],
            [-12, 0],
            [-2, 0],
            [-1, 0],
            [-0.5, 0],
            [0, \INF],
            [0.5, 0],
            [1, 0],
            [2, 0],
            [12, 0],
            [100, 0],
        ];
    }
    /**
     * @testCase     cdf
     * @dataProvider dataProviderForCdf
     * @param        float $x
     * @param        int   $cdf
     */
    public function testCdf(float $x, int $cdf)
    {
        $dirac = new DiracDelta();
        $this->assertSame($cdf, $dirac->cdf($x));
    }

    /**
     * @return array [x, cdf]
     */
    public function dataProviderForCdf(): array
    {
        return [
            [-100, 0],
            [-12, 0],
            [-2, 0],
            [-1, 0],
            [-0.5, 0],
            [0, 1],
            [0.5, 1],
            [1, 1],
            [2, 1],
            [12, 1],
            [100, 1],
        ];
    }

    /**
     * @testCase inverse is always 0
     */
    public function testInverse()
    {
        $diracDelta = new DiracDelta();
        foreach (range(-10, 10, 0.5) as $p) {
            $this->assertEquals(0, $diracDelta->inverse($p));
        }
    }

    /**
     * @testCase rand is always 0
     */
    public function testRand()
    {
        $diracDelta = new DiracDelta();
        foreach (range(-10, 10, 0.5) as $_) {
            $this->assertEquals(0, $diracDelta->rand());
        }
    }

    /**
     * @testCase mean is always 0
     */
    public function testMean()
    {
        $diracDelta = new DiracDelta();
        foreach (range(-10, 10, 0.5) as $_) {
            $this->assertEquals(0, $diracDelta->mean());
        }
    }
}
