<?php

namespace Math\Functions;

class PolynomialTest extends \PHPUnit_Framework_TestCase
{
    public function testString()
    {
        // p(x) = x² + 2x + 3

        $polynomial = new Polynomial([1, 2, 3]);
        $expected = " x² + 2x + 3";
        $actual = strval($polynomial);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dataProviderForEval
     */
    public function testEval(array $coefficients, $x, $expected)
    {
        $polynomial = new Polynomial($coefficients);
        $evaluated  = $polynomial($x);
        $this->assertEquals($expected, $evaluated);
    }

    public function dataProviderForEval()
    {
        return [
            [
                [1, 2, 3], // p(x) = x² + 2x + 3
                0, 3       // p(0) = 3
            ],
            [
                [1, 2, 3], // p(x) = x² + 2x + 3
                1, 6       // p(1) = 6
            ],
            [
                [1, 2, 3], // p(x) = x² + 2x + 3
                2, 11      // p(2) = 11
            ],
            [
                [1, 2, 3], // p(x) = x² + 2x + 3
                3, 18      // p(3) = 18
            ],
            [
                [1, 2, 3], // p(x) = x² + 2x + 3
                4, 27      // p(4) = 27
            ],
            [
                [1, 2, 3], // p(x) = x² + 2x + 3
                -1, 2     // p(-1) = 2
            ],
        ];
    }

    public function testDifferentiation()
    {
        // p(x)  = x² + 2x + 3
        // p'(x) = 2x + 2

        $polynomial = new Polynomial([1, 2, 3]);
        $expected   = new Polynomial([2, 2]);
        $actual     = $polynomial->differentiate();
        $this->assertEquals($expected, $actual);
    }

    public function testIntegration()
    {
        // p(x)  = x² + 2x + 3
        // p'(x) = (1/3)x³ + x² + 3x

        $polynomial = new Polynomial([1, 2, 3]);
        $expected = new Polynomial([1/3, 1, 3, 0]);
        $actual = $polynomial->integrate();
        $this->assertEquals($expected, $actual);
    }

    public function testFundamentalTheoremOfCalculus()
    {
        // p(x)  = x² + 2x + 3

        $polynomial = new Polynomial([1, 2, 3]);
        $integral   = $polynomial->integrate();
        $actual     = $integral->differentiate();
        $expected   = $polynomial;
        $this->assertEquals($expected, $actual);
    }
}
