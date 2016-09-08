<?php

namespace Math\Functions;

class PolynomialTest extends \PHPUnit_Framework_TestCase
{
    public function testString()
    {
        // p(x) = x² + 2x + 3

        $polynomial = new Polynomial([1, 2, 3]);
        $expected   = " x² + 2x + 3";
        $actual     = strval($polynomial);
        $this->assertEquals($expected, $actual);
    }

    public function testEval()
    {
        // p(x) = x² + 2x + 3
        // p(0) = 3

        $polynomial = new Polynomial([1, 2, 3]);
        $expected   = 3;
        $actual     = $polynomial(0);
        $this->assertEquals($expected, $actual);
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
