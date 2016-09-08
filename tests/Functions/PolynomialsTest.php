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
}
