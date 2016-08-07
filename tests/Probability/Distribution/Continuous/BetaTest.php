<?php
namespace Math\Probability\Distribution\Continuous;

class BetaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($α, $β, $x, $pdf)
    {
        $this->assertEquals($pdf, Beta::PDF($α, $β, $x), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [1, 1, 0, 1],
            [1, 1, 0.01, 1],
            [1, 1, 0.15, 1],
            [1, 1, 0.81, 1],
            [1, 1, 0.99, 1],
            [1, 1, 1, 1],

            [2, 3, 0, 0],
            [2, 3, 0.01, 0.117612],
            [2, 3, 0.15, 1.3005],
            [2, 3, 0.81, 0.350892],
            [2, 3, 0.99, 0.001188],
            [2, 3, 1, 0],

            [3, 2, 0, 0],
            [3, 2, 0.01, 0.001188],
            [3, 2, 0.15, 0.2295],
            [3, 2, 0.81, 1.495908],
            [3, 2, 0.99, 0.117612],
            [3, 2, 1, 0],

            [10, 50, 0, 0],
            [10, 50, 0.01, 3.8395492E-7],
            [10, 50, 0.15, 8.404355],
            [10, 50, 0.81, 3.3949479E-26],
            [10, 50, 0.99, 5.739479E-87],
            [10, 50, 1, 0],
        ];
    }

    public function testPDFExceptionAlphaBetaLessThanEqualZero()
    {
        $this->setExpectedException('\Exception');
        list($α, $β, $x) = [0, -3, 4];
        Beta::PDF($α, $β, $x);
    }

    public function testPDFExceptionXOutOfBounds()
    {
        $this->setExpectedException('\Exception');
        list($α, $β, $x) = [1, 1, 4];
        Beta::PDF($α, $β, $x);
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($α, $β, $x, $cdf)
    {
        $this->assertEquals($cdf, Beta::CDF($α, $β, $x), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [1, 1, 0, 0],
            [1, 1, 0.01, 0.01],
            [1, 1, 0.15, 0.15],
            [1, 1, 0.81, 0.81],
            [1, 1, 0.99, 0.99],
            [1, 1, 1, 1],

            [2, 3, 0, 0],
            [2, 3, 0.01, 5.9203E-4],
            [2, 3, 0.15, 0.10951875],
            [2, 3, 0.81, 0.97647363],
            [2, 3, 0.99, 0.99999603],
            [2, 3, 1, 1],

            [3, 2, 0, 0],
            [3, 2, 0.01, 3.97E-6],
            [3, 2, 0.15, 0.01198125],
            [3, 2, 0.81, 0.83436237],
            [3, 2, 0.99, 0.99940797],
            [3, 2, 1, 1],

            [10, 50, 0, 0],
            [10, 50, 0.01, 4.0195552E-10],
            [10, 50, 0.15, 0.39087049],
            [10, 50, 0.81, 1],
            [10, 50, 0.99, 1],
            [10, 50, 1, 1],
        ];
    }

    /**
     * @dataProvider dataProviderForMean
     */
    public function testMean($α, $β, $μ)
    {
        $this->assertEquals($μ, Beta::mean($α, $β), '', 0.0001);
    }

    public function dataProviderForMean()
    {
        return [
            [1, 1, 0.5],
            [1, 2, 0.3333],
            [2, 1, 0.6666],
        ];
    }
}
