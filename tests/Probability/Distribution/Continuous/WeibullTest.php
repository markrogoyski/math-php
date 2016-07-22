<?php
namespace Math\Probability\Distribution\Continuous;

class WeibullTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($k, $λ, $x, $pdf)
    {
        $this->assertEquals($pdf, Weibull::PDF($k, $λ, $x), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [ 1, 1, 1, 0.3678794411714424 ],
            [ 1, 2, 1, 0.3032653298563167 ],
            [ 2, 1, 1, 0.735758882342884643191 ],
            [ 4, 5, 3, 0.15179559655966815 ],
            [ 5, 5, 3, 0.11990416322625333 ],
            [ 34, 45, 33, 0.000027114527139041827 ],
            [ 1, 1, 0, 1 ],
            [ 2, 2, 0, 0 ],
            [ 1, 1, -1, 0 ],
            [ 2, 2, -0.1, 0 ],
        ];
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($k, $λ, $x, $cdf)
    {
        $this->assertEquals($cdf, Weibull::CDF($k, $λ, $x), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [ 1, 1, 1, 0.6321205588285577 ],
            [ 1, 2, 1, 0.3934693402873666 ],
            [ 2, 1, 1, 0.6321205588285577 ],
            [ 4, 5, 3, 0.12155326065006866 ],
            [ 5, 5, 3, 0.07481355535298351 ],
            [ 34, 45, 33, 0.00002631738735214828 ],
            [ 1, 1, 0, 0 ],
            [ 2, 2, 0, 0 ],
            [ 1, 1, -1, 0 ],
            [ 2, 2, -0.1, 0 ],
        ];
    }
}
