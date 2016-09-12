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
                [0, 0, 1],       // p(x) = 1
                '1',
            ],
            [
                [0, 0, 5],       // p(x) = 5
                '5',
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
     * @dataProvider dataProviderForGetDegree
     */
    public function testGetDegree(array $coefficients, int $expected)
    {
        $polynomial = new Polynomial($coefficients);
        $degree     = $polynomial->getDegree();
        $this->assertEquals($expected, $degree);
    }

    public function dataProviderForGetDegree()
    {
        return [
            [
                [1, 2, 3],       // p(x) = x² + 2x + 3
                2,
            ],
            [
                [2, 3, 4],       // p(x) = 2x² + 3x + 4
                2
            ],
            [
                [-1, -2, -3],       // p(x) = -x² - 2x - 3
                2
            ],
            [
                [-2, -3, -4],       // p(x) = -2x² - 3x - 4
                2
            ],
            [
                [0, 2, 3],       // p(x) = 2x + 3
                1
            ],
            [
                [1, 0, 3],       // p(x) = x² + 3
                2
            ],
            [
                [1, 2, 0],       // p(x) = x² + 2x
                2
            ],
            [
                [0, 0, 3],       // p(x) = 3
                0
            ],
            [
                [1, 0, 0],       // p(x) = x²
                2
            ],
            [
                [0, 2, 0],       // p(x) = 2x
                1
            ],
            [
                [0, -2, 3],       // p(x) = -2x + 3
                1
            ],
            [
                [-1, 0, 3],       // p(x) = -x² + 3
                2
            ],
            [
                [1, -2, 0],       // p(x) = x² - 2x
                2
            ],
            [
                [0, 0, -3],       // p(x) = -3
                0
            ],
            [
                [-1, 0, 0],       // p(x) = -x²
                2
            ],
            [
                [0, -2, 0],       // p(x) = -2x
                1
            ],
            [
                [0, 0, 0],       // p(x) = 0
                0
            ],
            [
                [0, 0, 1],       // p(x) = 1
                0
            ],
            [
                [0, 0, 5],       // p(x) = 5
                0
            ],
            [
                [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],       // p(x) = x¹¹ + 2x¹⁰ + 3x⁹ + 4x⁸ + 5x⁷ + 6x⁶ + 7x⁵ + 8x⁴ + 9x³ + 10x² + 11x + 12
                11
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForGetCoefficients
     */
    public function testGetCoefficients(array $coefficients, array $expected)
    {
        $polynomial   = new Polynomial($coefficients);
        $coefficients = $polynomial->getCoefficients();
        $this->assertEquals($expected, $coefficients);
    }
    
    public function dataProviderForGetCoefficients()
    {
        return [
            [
                [1, 2, 3],       // p(x) = x² + 2x + 3
                [1, 2, 3]
            ],
            [
                [2, 3, 4],       // p(x) = 2x² + 3x + 4
                [2, 3, 4]
            ],
            [
                [-1, -2, -3],       // p(x) = -x² - 2x - 3
                [-1, -2, -3]
            ],
            [
                [-2, -3, -4],       // p(x) = -2x² - 3x - 4
                [-2, -3, -4]
            ],
            [
                [0, 2, 3],       // p(x) = 2x + 3
                [2, 3]
            ],
            [
                [1, 0, 3],       // p(x) = x² + 3
                [1, 0, 3]
            ],
            [
                [1, 2, 0],       // p(x) = x² + 2x
                [1, 2, 0]
            ],
            [
                [0, 0, 3],       // p(x) = 3
                [3]
            ],
            [
                [1, 0, 0],       // p(x) = x²
                [1, 0, 0]
            ],
            [
                [0, 2, 0],       // p(x) = 2x
                [2, 0]
            ],
            [
                [0, -2, 3],       // p(x) = -2x + 3
                [-2, 3]
            ],
            [
                [-1, 0, 3],       // p(x) = -x² + 3
                [-1, 0, 3]
            ],
            [
                [1, -2, 0],       // p(x) = x² - 2x
                [1, -2, 0]
            ],
            [
                [0, 0, -3],       // p(x) = -3
                [-3]
            ],
            [
                [-1, 0, 0],       // p(x) = -x²
                [-1, 0, 0]
            ],
            [
                [0, -2, 0],       // p(x) = -2x
                [-2, 0]
            ],
            [
                [0, 0, 0],       // p(x) = 0
                [0]
            ],
            [
                [0, 0, 1],       // p(x) = 1
                [1]
            ],
            [
                [0, 0, 5],       // p(x) = 5
                [5]
            ],
            [
                [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],       // p(x) = x¹¹ + 2x¹⁰ + 3x⁹ + 4x⁸ + 5x⁷ + 6x⁶ + 7x⁵ + 8x⁴ + 9x³ + 10x² + 11x + 12
                [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
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
            [
                [5],    // p(x)  = 5
                [0]     // p'(x) = 0
            ],
            [
                [1],    // p(x)  = 1
                [0]     // p'(x) = 0
            ],
            [
                [0],    // p(x)  = 0
                [0]     // p'(x) = 0
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
                [0],    // f(x)  = 0
                [0, 0], // ∫f(x) = 0x
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

    /**
     * @dataProvider dataProviderForAddition
     */
    public function testAddition(array $polynomialA, array $polynomialB, array $expected_sum)
    {
        $polynomialA    = new Polynomial($polynomialA);
        $polynomialB    = new Polynomial($polynomialB);
        $expected       = new Polynomial($expected_sum);
        $sum            = $polynomialA->add($polynomialB);
        $this->assertEquals($expected, $sum);
    }

    public function dataProviderForAddition()
    {
        return [
            [
                [1, 2, 3],      // f(x)      = x² + 2x + 3
                [2, 3, 1],      // g(x)      = 2x² + 3x + 1
                [3, 5, 4],      // f(x)+g(x) = 3x² + 5x + 4
            ],
            [
                [1, 2, 3, 4, 4], // f(x)      = x⁴ + 2x³ + 3x² + 4x + 4
                [2, 3, 1],       // g(x)      = 2x² + 3x + 1
                [1, 2, 5, 7, 5], // f(x)+g(x) = x⁴ + 2x³ + 5x² + 7x + 5
            ],
            [
                [1, -8, 12, 3],  // f(x)      = x³ - 8x² + 12x + 3
                [1, -8, 12, 3],  // g(x)      = f(x)
                [2, -16, 24, 6], // f(x)+g(x) = 2x³ - 16x² + 24x + 6
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForMultiplication
     */
    public function testMultiplication(array $polynomialA, array $polynomialB, array $expected_product)
    {
        $polynomialA    = new Polynomial($polynomialA);
        $polynomialB    = new Polynomial($polynomialB);
        $expected       = new Polynomial($expected_product);
        $product        = $polynomialA->multiply($polynomialB);
        $this->assertEquals($expected, $product);
    }

    public function dataProviderForMultiplication()
    {
        return [
            [
                [1, 2, 3],         // f(x)      = x² + 2x + 3
                [2, 3, 1],         // g(x)      = 2x² + 3x + 1
                [2, 7, 13, 11, 3], // f(x)*g(x) = 2x⁴ + 7x³ + 13x² + 11x + 3
            ],
            [
                [1, 2, 3, 4, 4],           // f(x)      = x⁴ + 2x³ + 3x² + 4x + 4
                [2, 3, 1],                 // g(x)      = 2x² + 3x + 1
                [2, 7, 13, 19, 23, 16, 4], // f(x)*g(x) = 2x⁶ + 7x⁵ + 13x⁴ + 19x³ + 23x² + 16x + 4
            ],
            [
                [1, -8, 12, 3],                // f(x)      = x³ - 8x² + 12x + 3
                [1, -8, 12, 3],                // g(x)      = f(x)
                [1, -16, 88, -186, 96, 72, 9], // f(x)+g(x) = x⁶ - 16x⁵ + 88x⁴ - 186x³ + 96x² + 72x + 9
            ],
        ];
    }
}
