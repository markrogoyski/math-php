<?php
namespace MathPHP\Tests\Functions;

use MathPHP\Functions\Polynomial;
use MathPHP\Exception;

class PolynomialTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForString
     */
    public function testString(array $coefficients, string $expected)
    {
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
     * @dataProvider dataProviderForVariable
     */
    public function testVariable(array $args, string $expected)
    {
        $coefficients = $args[0];
        $variable     = $args[1] ?? "x";
        $polynomial   = new Polynomial($coefficients, $variable);
        $string       = strval($polynomial);
        $this->assertEquals($expected, $string);
    }

    public function dataProviderForVariable()
    {
        return [
            [
                [[1, 2, 3]],       // p(x) = x² + 2x + 3
                'x² + 2x + 3',
            ],
            [
                [[2, 3, 4], "p"],       // p(p) = 2p² + 3p + 4
                '2p² + 3p + 4',
            ],
            [
                [[-1, -2, -3], "q"],       // p(q) = -q² - 2q - 3
                '-q² - 2q - 3',
            ],
            [
                [[-2, -3, -4], "a"],       // p(a) = -2a² - 3a - 4
                '-2a² - 3a - 4',
            ],
            [
                [[0, 2, 3], "a"],       // p(a) = 2a + 3
                '2a + 3',
            ],
            [
                [[1, 0, 3], "a"],       // p(a) = a² + 3
                'a² + 3',
            ],
            [
                [[1, 2, 0], "a"],       // p(a) = a² + 2a
                'a² + 2a',
            ],
            [
                [[0, 0, 3], "a"],       // p(a) = 3
                '3',
            ],
            [
                [[1, 0, 0], "a"],       // p(a) = a²
                'a²',
            ],
            [
                [[0, 2, 0], "a"],       // p(a) = 2a
                '2a',
            ],
            [
                [[0, -2, 3], "a"],       // p(a) = -2a + 3
                '-2a + 3',
            ],
            [
                [[-1, 0, 3], "a"],       // p(a) = -a² + 3
                '-a² + 3',
            ],
            [
                [[1, -2, 0], "a"],       // p(a) = a² - 2a
                'a² - 2a',
            ],
            [
                [[0, 0, -3], "a"],       // p(a) = -3
                '-3',
            ],
            [
                [[-1, 0, 0], "a"],       // p(a) = -a²
                '-a²',
            ],
            [
                [[0, -2, 0], "a"],       // p(a) = -2a
                '-2a',
            ],
            [
                [[0, 0, 0], "a"],       // p(a) = 0
                '0',
            ],
            [
                [[0, 0, 1], "a"],       // p(a) = 1
                '1',
            ],
            [
                [[0, 0, 5], "a"],       // p(a) = 5
                '5',
            ],
            [
                [[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], "a"],       // p(a) = a¹¹ + 2a¹⁰ + 3a⁹ + 4a⁸ + 5a⁷ + 6a⁶ + 7a⁵ + 8a⁴ + 9a³ + 10a² + 11a + 12
                'a¹¹ + 2a¹⁰ + 3a⁹ + 4a⁸ + 5a⁷ + 6a⁶ + 7a⁵ + 8a⁴ + 9a³ + 10a² + 11a + 12',
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
     * @dataProvider dataProviderForGetVariable
     */
    public function testGetVariable(array $args, string $expected)
    {
        $coefficients = $args[0];
        $variable     = $args[1] ?? "x";
        $polynomial   = new Polynomial($coefficients, $variable);
        $result       = $polynomial->getVariable();
        $this->assertEquals($expected, $result);
    }

    public function dataProviderForGetVariable()
    {
        return [
            [
                [[1, 2, 3]],       // p(x) = x² + 2x + 3
                'x',
            ],
            [
                [[2, 3, 4], "p"],       // p(p) = 2p² + 3p + 4
                'p',
            ],
            [
                [[-1, -2, -3], "m"],       // p(m) = -m² - 2m - 3
                'm',
            ],
            [
                [[-2, -3, -4], "a"],       // p(a) = -2a² - 3a - 4
                'a',
            ],
            [
                [[0, 2, 3], "Δ"],       // p(Δ) = 2Δ + 3
                'Δ',
            ],
            [
                [[1, 0, 3], "Γ"],       // p(Γ) = Γ² + 3
                'Γ',
            ],
            [
                [[1, 2, 0], "Ψ"],       // p(a) = Ψ² + 2Ψ
                'Ψ',
            ],
            [
                [[0, 0, 3], "μ"],       // p(μ) = 3
                'μ',
            ],
            [
                [[1, 0, 0], "ξ"],       // p(ξ) = ξ²
                'ξ',
            ],
            [
                [[0, 2, 0], "aₙ"],       // p(aₙ) = 2aₙ
                'aₙ',
            ],
            [
                [[0, -2, 3], "aⁿ"],       // p(aⁿ) = -2aⁿ + 3
                'aⁿ',
            ],
            [
                [[-1, 0, 3], "a₍ₘ₎₍ₙ₎"],       // p(a) = -a₍ₘ₎₍ₙ₎² + 3
                'a₍ₘ₎₍ₙ₎',
            ],
        ];
    }

    public function testSetVariable()
    {
        // Start with default variable: x
        $polynomial = new Polynomial([1, 1, 1, 1]);

        $expected = "x";
        $result   = $polynomial->getVariable();
        $this->assertEquals($expected, $result);

        $expected = "x³ + x² + x + 1";
        $result   = strval($polynomial);
        $this->assertEquals($expected, $result);

        // Switch variable to Φ
        $polynomial->setVariable("Φ");
        $expected = "Φ";
        $result   = $polynomial->getVariable();
        $this->assertEquals($expected, $result);

        $expected = "Φ³ + Φ² + Φ + 1";
        $result   = strval($polynomial);
        $this->assertEquals($expected, $result);

        // Switch variable back to x
        $polynomial->setVariable("x");
        $expected = "x";
        $result   = $polynomial->getVariable();
        $this->assertEquals($expected, $result);

        $expected = "x³ + x² + x + 1";
        $result   = strval($polynomial);
        $this->assertEquals($expected, $result);
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
                [2, 3, 1],       // f(x)      = 2x² + 3x + 1
                [1, 2, 3, 4, 4], // g(x)      = x⁴ + 2x³ + 3x² + 4x + 4
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
     * @dataProvider dataProviderForSubtraction
     */
    public function testSubtraction(array $polynomialA, array $polynomialB, array $expected_sum)
    {
        $polynomialA    = new Polynomial($polynomialA);
        $polynomialB    = new Polynomial($polynomialB);
        $expected       = new Polynomial($expected_sum);
        $sum            = $polynomialA->subtract($polynomialB);
        $this->assertEquals($expected, $sum);
    }

    public function dataProviderForSubtraction()
    {
        return [
            [
                [1, 2, 3],      // f(x)      = x² + 2x + 3
                [2, 3, 1],      // g(x)      = 2x² + 3x + 1
                [-1, -1, 2],    // f(x)-g(x) = -x² - x + 2
            ],
            [
                [1, 2, 3, 4, 4], // f(x)      = x⁴ + 2x³ + 3x² + 4x + 4
                [2, 3, 1],       // g(x)      = 2x² + 3x + 1
                [1, 2, 1, 1, 3], // f(x)-g(x) = x⁴ + 2x³ + x² + x + 3
            ],
            [
                [1, -8, 12, 3],  // f(x)      = x³ - 8x² + 12x + 3
                [1, -8, 12, 3],  // g(x)      = f(x)
                [0, 0, 0, 0],    // f(x)-g(x) = 0
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

    /**
     * @dataProvider dataProviderForScalerAddition
     */
    public function testScalerAddition(array $polynomialA, $scaler, array $expected_product)
    {
        $polynomialA    = new Polynomial($polynomialA);
        $expected       = new Polynomial($expected_product);
        $product        = $polynomialA->add($scaler);
        $this->assertEquals($expected, $product);
    }

    public function dataProviderForScalerAddition()
    {
        return [
            [
                [1, 2, 3],         // f(x)      = x² + 2x + 3
                2,
                [1, 2, 5],         // f(x)*c    = x² + 2x + 5
            ],
            [
                [1, 2, 3, 4, 4],      // f(x)      = x⁴ + 2x³ + 3x² + 4x + 4
                -2,
                [1, 2, 3, 4, 2],      // f(x)*c    = 1x⁴ + 2x³ + 3x² + 4x + 2
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForScalerSubtraction
     */
    public function testScalerSubtraction(array $polynomialA, $scaler, array $expected_product)
    {
        $polynomialA    = new Polynomial($polynomialA);
        $expected       = new Polynomial($expected_product);
        $product        = $polynomialA->subtract($scaler);
        $this->assertEquals($expected, $product);
    }

    public function dataProviderForScalerSubtraction()
    {
        return [
            [
                [1, 2, 3],         // f(x)      = x² + 2x + 3
                2,
                [1, 2, 1],         // f(x)*c    = x² + 2x + 1
            ],
            [
                [1, 2, 3, 4, 4],      // f(x)      = x⁴ + 2x³ + 3x² + 4x + 4
                -2,
                [1, 2, 3, 4, 6],      // f(x)*c    = 1x⁴ + 2x³ + 3x² + 4x + 6
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForScalerMultiplication
     */
    public function testScalerMultiplication(array $polynomialA, $scaler, array $expected_product)
    {
        $polynomialA    = new Polynomial($polynomialA);
        $expected       = new Polynomial($expected_product);
        $product        = $polynomialA->multiply($scaler);
        $this->assertEquals($expected, $product);
    }

    public function dataProviderForScalerMultiplication()
    {
        return [
            [
                [1, 2, 3],         // f(x)      = x² + 2x + 3
                2,
                [2, 4, 6],         // f(x)*c    = 2x² + 4x + 6
            ],
            [
                [1, 2, 3, 4, 4],           // f(x)      = x⁴ + 2x³ + 3x² + 4x + 4
                -2,
                [-2, -4, -6, -8, -8],      // f(x)*c    = -2x⁴ - 4x³ - 6x² - 8x - 8
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForRoots
     */
    public function testRoots(array $polynomialA, array $expected_roots)
    {
        $polynomialA = new Polynomial($polynomialA);
        $roots       = $polynomialA->roots();
        $this->assertEquals($expected_roots, $roots);
    }

    public function dataProviderForRoots()
    {
        return [
            [
                [1, -3],
                [3],
            ],
            [
                [1, -3, -4],
                [-1, 4],
            ],
            [
                [1, -6, 11, -6],
                [3, 1, 2],
            ],
            [
                [1, -10, 35, -50, 24],
                [4, 1, 3, 2],
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForRootsNAN
     */
    public function testRootsNAN(array $polynomialA)
    {
        $polynomialA = new Polynomial($polynomialA);
        $roots       = $polynomialA->roots();
        $this->assertNan($roots[0]);
    }

    public function dataProviderForRootsNAN(): array
    {
        return [
            [
                [1, -3, -4, 5, 5, 5],
            ],
        ];
    }

    /**
     * @testCase Polynomial constructor throws an IncorrectTypeException if the argument is not numeric or a Polynomial
     */
    public function testException()
    {
        $this->expectException(Exception\IncorrectTypeException::class);
        $string = 'This is a string!';
        $poly   = new Polynomial([1, 2]);
        $sum    = $poly->add($string);
    }

    /**
     * @testCase     checkNumericOrPolynomial returns a Polynomial for numeric and Polynomial inputs
     * @dataProvider dataProviderForCheckNumericOrPolynomial
     */
    public function testCheckNumericOrPolynomialNumericInput($input)
    {
        $method = new \ReflectionMethod(Polynomial::class, 'checkNumericOrPolynomial');
        $method->setAccessible(true);

        $polynomial = $method->invokeArgs(new Polynomial([1]), [$input]);
        $this->assertInstanceOf(Polynomial::class, $polynomial);
    }

    public function dataProviderForCheckNumericOrPolynomial(): array
    {
        return [
            [-1],
            [0],
            [1],
            [10],
            [2.45],
            ['3'],
            ['5.4'],
            [new Polynomial([4])],
            [new Polynomial([2, 3, 4])],

        ];
    }

    /**
     * @testCase checkNumericOrPolynomial throws an IncorrectTypeException if the input is not numeric or a Polynomial
     */
    public function testCheckNumericOrPolynomialException()
    {
        $method = new \ReflectionMethod(Polynomial::class, 'checkNumericOrPolynomial');
        $method->setAccessible(true);

        $this->expectException(Exception\IncorrectTypeException::class);
        $polynomial = $method->invokeArgs(new Polynomial([1]), ['not a number']);
    }

    /**
     * @testCase     negate returns a Polynomial with every coefficient negated
     * @dataProvider dataProviderForNegate
     * @param        array $polynomial
     * @param        array $expected_negated_polynomial
     */
    public function testNegate(array $polynomial, array $expected_negated_polynomial)
    {
        $polynomial = new Polynomial($polynomial);
        $expected   = new Polynomial($expected_negated_polynomial);
        $negated    = $polynomial->negate();
        $this->assertEquals($expected, $negated);
    }

    public function dataProviderForNegate(): array
    {
        return [
            [
                [],
                [],
            ],
            [
                [0],
                [0],
            ],
            [
                [1],
                [-1],
            ],
            [
                [-1],
                [1],
            ],
            [
                [1, 1],
                [-1, -1],
            ],
            [
                [-1, -1],
                [1, 1],
            ],
            [
                [1, -2, 3],
                [-1, 2, -3],
            ],
            [
                [5, 5, 5, -5, -5],
                [-5, -5, -5, 5, 5],
            ],
            [
                [23, 5, 65, 0, -4],
                [-23, -5, -65, 0, 4],
            ],
            [
                [-4, -3, 0, 0, 0],
                [4, 3, 0, 0, 0],
            ],
            [
                [-3, -4, 2, 1, 5, 5, 4, -3, 2],
                [3, 4, -2, -1, -5, -5, -4, 3, -2],
            ],
            [
                [1, 2, 3],
                [-1, -2, -3],
            ],
        ];
    }
}
