<?php
namespace MathPHP\Tests\Probability\Distribution\Continuous;

use MathPHP\Probability\Distribution\Continuous\Cauchy;

class CauchyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $x₀, $γ, $pdf)
    {
        $cauchy = new Cauchy($x₀, $γ);
        $this->assertEquals($pdf, $cauchy->pdf($x), '', 0.0001);
    }

    public function dataProviderForPDF()
    {
        return [
            [1, 0, 1, 0.1591549430918953357689],
            [0, 1, 1, 0.1591549430918953357689],
            [1, 1, 1, 0.3183098861837906715378],
            [2, 3, 4, 0.07489644380795074624418],
            [4, 3, 2, 0.1273239544735162686151],
            [5, 5, 5, 0.06366197723675813430755],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($x, $x₀, $γ, $cdf)
    {
        $cauchy = new Cauchy($x₀, $γ);
        $p = $cauchy->cdf($x);
        $this->assertEquals($cdf, $p, '', 0.0001);
        $this->assertEquals($x, $cauchy->inverse($p), '', 0.0001);
    }

    public function dataProviderForCDF()
    {
        return [
            [1, 0, 1, 0.75],
            [0, 1, 1, 0.25],
            [1, 1, 1, 0.5],
            [2, 3, 4, 0.4220208696226306745395],
            [4, 3, 2, 0.6475836176504332741754],
            [5, 5, 5, 0.5],
        ];
    }

    public function testMean()
    {
        $cauchy1 = new Cauchy(1, 1);
        $cauchy2 = new Cauchy(2, 3);

        $this->assertNan($cauchy1->mean());
        $this->assertNan($cauchy2->mean());
    }

    public function testMedian()
    {
        $x₀ = 5;
        $γ  = 3;
        
        $cauchy = new Cauchy($x₀, $γ);
        $this->assertEquals($x₀, $cauchy->median());
    }

    public function testMode()
    {
        $x₀ = 5;
        $γ  = 3;
        
        $cauchy = new Cauchy($x₀, $γ);
        $this->assertEquals($x₀, $cauchy->mode($x₀, $γ));
    }
}
