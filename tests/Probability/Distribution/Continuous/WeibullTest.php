<?php
namespace Math\Probability\Distribution\Continuous;

class WeibullTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $k, $λ, $pdf)
    {
        $this->assertEquals($pdf, Weibull::PDF($x, $k, $λ), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [ 1, 1, 1, 0.3678794411714424 ],
            [ 1, 1, 2, 0.3032653298563167 ],
            [ 1, 2, 1, 0.735758882342884643191 ],
            [ 3, 4, 5, 0.15179559655966815 ],
            [ 3, 5, 5, 0.11990416322625333 ],
            [ 33, 34, 45, 0.000027114527139041827 ],
            [ 0, 1, 1, 1 ],
            [ 0, 2, 2, 0 ],
            [ -1, 1, 1, 0 ],
            [ -0.1, 2, 2, 0 ],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($x, $k, $λ, $cdf)
    {
        $p = Weibull::CDF($x, $k, $λ);
        $this->assertEquals($cdf, $p, '', 0.001);
        if ($x >= 0) {
            $this->assertEquals($x, Weibull::inverse($p, $k, $λ), '', 0.001);
        }
    }

    public function dataProviderForCDF()
    {
        return [
            [ 1, 1, 1, 0.6321205588285577 ],
            [ 1, 1, 2, 0.3934693402873666 ],
            [ 1, 2, 1, 0.6321205588285577 ],
            [ 3, 4, 5, 0.12155326065006866 ],
            [ 3, 5, 5, 0.07481355535298351 ],
            [ 33, 34, 45, 0.00002631738735214828 ],
            [ 0, 1, 1, 0 ],
            [ 0, 2, 2, 0 ],
            [ -1, 1, 1, 0 ],
            [ -0.1, 2, 2, 0 ],
        ];
    }

    /**
     * @dataProvider dataProviderForMean
     */
    public function testMean($k, $λ, $μ)
    {
        $this->assertEquals($μ, Weibull::mean($k, $λ), '', 0.0001);
    }

    public function dataProviderForMean()
    {
        return [
            [1, 1, 1],
            [1, 2, 2],
            [2, 1, 0.88622692545275801365],
            [2, 2, 1.77245386],
        ];
    }
}
