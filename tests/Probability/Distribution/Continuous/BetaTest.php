<?php
namespace Math\Probability\Distribution\Continuous;

class BetaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForPDF
     */
    public function testPDF($x, $α, $β, $pdf)
    {
        $this->assertEquals($pdf, Beta::PDF($x, $α, $β), '', 0.001);
    }

    public function dataProviderForPDF()
    {
        return [
            [0, 1, 1, 1],
            [0.01, 1, 1, 1],
            [0.15, 1, 1, 1],
            [0.81, 1, 1, 1],
            [0.99, 1, 1, 1],
            [1, 1, 1, 1],

            [0, 2, 3, 0],
            [0.01, 2, 3, 0.117612],
            [0.15, 2, 3, 1.3005],
            [0.81, 2, 3, 0.350892],
            [0.99, 2, 3, 0.001188],
            [1, 2, 3, 0],

            [0, 3, 2, 0],
            [0.01, 3, 2, 0.001188],
            [0.15, 3, 2, 0.2295],
            [0.81, 3, 2, 1.495908],
            [0.99, 3, 2, 0.117612],
            [1, 3, 2, 0],

            [0, 10, 50, 0],
            [0.01, 10, 50, 3.8395492E-7],
            [0.15, 10, 50, 8.404355],
            [0.81, 10, 50, 3.3949479E-26],
            [0.99, 10, 50, 5.739479E-87],
            [1, 10, 50, 0],
        ];
    }

    public function testPDFExceptionAlphaBetaLessThanEqualZero()
    {
        $this->setExpectedException('\Exception');
        list($x, $α, $β) = [4, 0, -3];
        Beta::PDF($x, $α, $β);
    }

    public function testPDFExceptionXOutOfBounds()
    {
        $this->setExpectedException('\Exception');
        list($x, $α, $β) = [4, 1, 1];
        Beta::PDF($x, $α, $β);
    }

    /**
     * @dataProvider dataProviderForCDF
     */
    public function testCDF($x, $α, $β, $cdf)
    {
        $this->assertEquals($cdf, Beta::CDF($x, $α, $β), '', 0.001);
    }

    public function dataProviderForCDF()
    {
        return [
            [0, 1, 1, 0],
            [0.01, 1, 1, 0.01],
            [0.15, 1, 1, 0.15],
            [0.81, 1, 1, 0.81],
            [0.99, 1, 1, 0.99],
            [1, 1, 1, 1],

            [0, 2, 3, 0],
            [0.01, 2, 3, 5.9203E-4],
            [0.15, 2, 3, 0.10951875],
            [0.81, 2, 3, 0.97647363],
            [0.99, 2, 3, 0.99999603],
            [1, 2, 3, 1],

            [0, 3, 2, 0],
            [0.01, 3, 2, 3.97E-6],
            [0.15, 3, 2, 0.01198125],
            [0.81, 3, 2, 0.83436237],
            [0.99, 3, 2, 0.99940797],
            [1, 3, 2, 1],

            [0, 10, 50, 0],
            [0.01, 10, 50, 4.0195552E-10],
            [0.15, 10, 50, 0.39087049],
            [0.81, 10, 50, 1],
            [0.99, 10, 50, 1],
            [1, 10, 50, 1],
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
