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
}
