<?php
namespace MathPHP\Tests\Functions;

use MathPHP\Functions\Arithmetic;

class ArithmeticTest extends \PHPUnit\Framework\TestCase
{
    public function testSum()
    {
        // f(x) = x⁴ + 8x³ -13x² -92x + 96
        $f = function ($x) {
            return $x**4 + 8 * $x**3 - 13 * $x**2 - 92 * $x + 96;
        };

        // g(x) = x³ - 12x² + 72x + 27
        $g = function ($x) {
            return $x**3 - 12 * $x**2 + 72 * $x + 27;
        };

        // Σ(x) = f(x) + g(x) = x⁴ + 9x³ -25x² -20x + 123
        $sum = Arithmetic::add($f, $g);

        // Σ(0) = 123
        $expected = 123;
        $x = $sum(0);
        $this->assertEquals($expected, $x);

        // Σ(5) = 1148
        $expected = 1148;
        $x = $sum(5);
        $this->assertEquals($expected, $x);

        // Σ(-5) = -902
        $expected = -902;
        $x = $sum(-5);
        $this->assertEquals($expected, $x);

        // Σ(100) = 108748123
        $expected = 108748123;
        $x = $sum(100);
        $this->assertEquals($expected, $x);

        // Σ(-100) = 90752123
        $expected = 90752123;
        $x = $sum(-100);
        $this->assertEquals($expected, $x);
    }

    public function testProduct()
    {
        // f(x) = x² + 8x - 12
        $f = function ($x) {
            return $x**2 + 8*$x - 12;
        };

        // g(x) = x - 9
        $g = function ($x) {
            return $x - 9;
        };

        // Π(x) = f(x) * g(x) = x³ - x² -84x + 108
        $product = Arithmetic::multiply($f, $g);

        // Π(0) = 108
        $expected = 108;
        $x = $product(0);
        $this->assertEquals($expected, $x);

        // Π(5) = -212
        $expected = -212;
        $x = $product(5);
        $this->assertEquals($expected, $x);

        // Π(-5) = 378
        $expected = 378;
        $x = $product(-5);
        $this->assertEquals($expected, $x);

        // Π(100) = 981708
        $expected = 981708;
        $x = $product(100);
        $this->assertEquals($expected, $x);

        // Π(-100) = -1001492
        $expected = -1001492;
        $x = $product(-100);
        $this->assertEquals($expected, $x);
    }

    public function testMultipleSums()
    {
        // f(x) = 8x³ - 13x² -92x + 96
        $f = function ($x) {
            return 8 * $x**3 - 13 * $x**2 - 92 * $x + 96;
        };

        $g = $f;
        $h = $f;
        $i = $f;
        $j = $f;

        // Σ(x) = f(x) + g(x) + h(x) + i(x) + j(x) = 5*f(x) = 40x³ - 65x² -460x + 480
        $sum = Arithmetic::add($f, $g, $h, $i, $j);

        // Σ(0) = 480
        $expected = 480;
        $x = $sum(0);
        $this->assertEquals($expected, $x);

        // Σ(5) = 1555
        $expected = 1555;
        $x = $sum(5);
        $this->assertEquals($expected, $x);

        // Σ(-5) = -3845
        $expected = -3845;
        $x = $sum(-5);
        $this->assertEquals($expected, $x);
    }

    public function testMultipleProducts()
    {
        // f(x) = x - 9
        $f = function ($x) {
            return $x - 9;
        };

        // g(x) = x + 2
        $g = function ($x) {
            return $x + 2;
        };

        // h(x) = x
        $h = function ($x) {
            return $x;
        };

        // Π(x) = f(x) * g(x) * h(x) = x³ - 7x² -18x
        $product = Arithmetic::multiply($f, $g, $h);

        // Π(0) = 0
        $expected = 0;
        $x = $product(0);
        $this->assertEquals($expected, $x);

        // Π(5) = -140
        $expected = -140;
        $x = $product(5);
        $this->assertEquals($expected, $x);

        // Π(-5) = -210
        $expected = -210;
        $x = $product(-5);
        $this->assertEquals($expected, $x);
    }

    public function testNestedArithmetic()
    {
        // f(x) = x - 9
        $f = function ($x) {
            return $x - 9;
        };

        // g(x) = x + 2
        $g = function ($x) {
            return $x + 2;
        };

        // h(x) = x
        $h = function ($x) {
            return $x;
        };

        // Π(x) = $f(x) * ( g(x) + h(x) ) = (x - 9) * (2x + 2) = 2x² - 16x - 18
        $product = Arithmetic::multiply($f, Arithmetic::add($g, $h));

        // Π(0) = -18
        $expected = -18;
        $x = $product(0);
        $this->assertEquals($expected, $x);

        // Π(5) = -48
        $expected = -48;
        $x = $product(5);
        $this->assertEquals($expected, $x);

        // Π(-5) = 112
        $expected = 112;
        $x = $product(-5);
        $this->assertEquals($expected, $x);
    }
}
