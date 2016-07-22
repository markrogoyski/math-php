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
}
