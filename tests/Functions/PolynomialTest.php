<?php

namespace Math\Functions;

class PolynomialTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForString
     */
    public function testString(array $coefficients, string $expected)
    {
        // p(x) = x² + 2x + 3

        $polynomial = new Polynomial($coefficients);
        $string     = strval($polynomial);
        $this->assertEquals($expected, $string);
    }

    public function dataProviderForString()
    {
        return [
            [
                [1, 2, 3],       // p(x) = x² + 2x + 3
                'x² + 2x + 3',
            ],
            [
                [2, 3, 4],       // p(x) = 2x² + 3x + 4
                '2x² + 3x + 4',
            ],
            [
                [-1, -2, -3],       // p(x) = -x² - 2x - 3
                '-x² - 2x - 3',
            ],
            [
                [-2, -3, -4],       // p(x) = -2x² - 3x - 4
                '-2x² - 3x - 4',
            ],
            [
                [0, 2, 3],       // p(x) = 2x + 3
                '2x + 3',
            ],
            [
                [1, 0, 3],       // p(x) = x² + 3
                'x² + 3',
            ],
            [
                [1, 2, 0],       // p(x) = x² + 2x
                'x² + 2x',
            ],
            [
                [0, 0, 3],       // p(x) = 3
                '3',
            ],
            [
                [1, 0, 0],       // p(x) = x²
                'x²',
            ],
            [
                [0, 2, 0],       // p(x) = 2x
                '2x',
            ],

            [
                [0, -2, 3],       // p(x) = -2x + 3
                '-2x + 3',
            ],
            [
                [-1, 0, 3],       // p(x) = -x² + 3
                '-x² + 3',
            ],
            [
                [1, -2, 0],       // p(x) = x² - 2x
                'x² - 2x',
            ],
            [
                [0, 0, -3],       // p(x) = -3
                '-3',
            ],
            [
                [-1, 0, 0],       // p(x) = -x²
                '-x²',
            ],
            [
                [0, -2, 0],       // p(x) = -2x
                '-2x',
            ],
            [
                [0, 0, 0],       // p(x) = 0
                '0',
            ],
            [
                [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],       // p(x) = x¹¹ + 2x¹⁰ + 3x⁹ + 4x⁸ + 5x⁷ + 6x⁶ + 7x⁵ + 8x⁴ + 9x³ + 10x² + 11x + 12
                'x¹¹ + 2x¹⁰ + 3x⁹ + 4x⁸ + 5x⁷ + 6x⁶ + 7x⁵ + 8x⁴ + 9x³ + 10x² + 11x + 12',
            ],
        ];
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
                -1, 2      // p(-1) = 2
            ],
            [
                [0, 0, 0], // p(x) = 0
                5, 0       // p(5) = 0
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForDifferentiate
     */
    public function testDifferentiation(array $polynomial, array $expected)
    {
        $polynomial = new Polynomial($polynomial);
        $expected   = new Polynomial($expected);
        $derivative = $polynomial->differentiate();
        $this->assertEquals($expected, $derivative);
    }

    public function dataProviderForDifferentiate()
    {
        return [
            [
                [1, 2, 3], // p(x)  = x² + 2x + 3
                [2, 2]     // p'(x) = 2x + 2
            ],
            [
                [2, 3, 4], // p(x)  = 2x² + 3x + 4
                [4, 3]     // p'(x) = 4x + 3
            ],
            [
                [1, 0], // p(x)  = x
                [1]     // p'(x) = 1
            ],
            [
                [5, 0], // p(x)  = 5x
                [5]     // p'(x) = 5
            ],
            [
                [1, 0, 0], // p(x)  = x²
                [2, 0]     // p'(x) = 2x
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForIntegrate
     */
    public function testIntegration(array $polynomial, array $expected_integral)
    {
        $polynomial = new Polynomial($polynomial);
        $expected   = new Polynomial($expected_integral);
        $integral   = $polynomial->integrate();
        $this->assertEquals($expected, $integral);
    }

    public function dataProviderForIntegrate()
    {
        return [
            [
                [1, 2, 3],      // f(x)  = x² + 2x + 3
                [1/3, 1, 3, 0], // ∫f(x) = (1/3)x³ + x² + 3x
            ],
            [
                [5],    // f(x)  = 5
                [5, 0], // ∫f(x) = 5x
            ],
            [
                [1, 0],      // f(x)  = x
                [1/2, 0, 0], // ∫f(x) = (1/2)²
            ],
        ];
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
