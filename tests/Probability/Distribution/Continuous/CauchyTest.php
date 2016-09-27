<?php
namespace Math\Probability\Distribution\Continuous;

class CauchyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $x₀, $γ, $pdf)
    {
        $this->assertEquals($pdf, Cauchy::PDF($x, $x₀, $γ), '', 0.0001);
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
        $p = Cauchy::CDF($x, $x₀, $γ);
        $this->assertEquals($cdf, $p, '', 0.0001);
        $this->assertEquals($x, Cauchy::inverse($p, $x₀, $γ), '', 0.0001);
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
        $this->assertNan(Cauchy::mean(1, 1));
        $this->assertNan(Cauchy::mean(2, 3));
    }

    public function testMedian()
    {
        $x₀ = 5;
        $γ  = 3;
        $this->assertEquals($x₀, Cauchy::median($x₀, $γ));
    }

    public function testMode()
    {
        $x₀ = 5;
        $γ  = 3;
        $this->assertEquals($x₀, Cauchy::median($x₀, $γ));
    }
}
