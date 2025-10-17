<?php

namespace MathPHP\Tests\Functions\Special;

use MathPHP\Functions\Special;
use MathPHP\Exception;

/**
 * Tests for orthogonal polynomials
 * Test data generated from SciPy for validation
 */
class PolynomialTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test Legendre polynomial Pₙ(x)
     * @dataProvider dataProviderForLegendreP
     * @param int $n
     * @param float $x
     * @param float $expected
     */
    public function testLegendreP(int $n, float $x, float $expected)
    {
        // When
        $result = Special::legendreP($n, $x);

        // Then
        $this->assertEqualsWithDelta($expected, $result, 1e-10);
    }

    /**
     * Test data from SciPy scipy.special.eval_legendre()
     */
    public function dataProviderForLegendreP(): array
    {
        return [
            [0, -1.0, 1.0],
            [0, -0.5, 1.0],
            [0, 0.0, 1.0],
            [0, 0.5, 1.0],
            [0, 1.0, 1.0],
            [0, 2.0, 1.0],
            [0, 5.0, 1.0],
            [1, -1.0, -1.0],
            [1, -0.5, -0.5],
            [1, 0.0, 0.0],
            [1, 0.5, 0.5],
            [1, 1.0, 1.0],
            [1, 2.0, 2.0],
            [1, 5.0, 5.0],
            [2, -1.0, 1.0],
            [2, -0.5, -0.125],
            [2, 0.0, -0.5000000000000001],
            [2, 0.5, -0.125],
            [2, 1.0, 1.0],
            [2, 2.0, 5.5],
            [2, 5.0, 37.0],
            [3, -1.0, -1.0],
            [3, -0.5, 0.4375],
            [3, 0.0, 0.0],
            [3, 0.5, -0.43749999999999994],
            [3, 1.0, 1.0],
            [3, 2.0, 17.0],
            [3, 5.0, 305.0],
            [4, -1.0, 1.0],
            [4, -0.5, -0.2890625],
            [4, 0.0, 0.375],
            [4, 0.5, -0.28906249999999994],
            [4, 1.0, 1.0],
            [4, 2.0, 55.375],
            [4, 5.0, 2641.0],
            [5, -1.0, -1.0],
            [5, -0.5, -0.08984375],
            [5, 0.0, 0.0],
            [5, 0.5, 0.08984375],
            [5, 1.0, 1.0],
            [5, 2.0, 185.75],
            [5, 5.0, 23525.0],
            [10, -1.0, 0.9999999999999996],
            [10, -0.5, -0.1882286071777342],
            [10, 0.0, -0.24609375],
            [10, 0.5, -0.18822860717773435],
            [10, 1.0, 1.0],
            [10, 2.0, 96060.51953125],
            [10, 5.0, 1600472677.0],
        ];
    }

    /**
     * @test Chebyshev polynomial of the first kind Tₙ(x)
     * @dataProvider dataProviderForChebyshevT
     * @param int $n
     * @param float $x
     * @param float $expected
     */
    public function testChebyshevT(int $n, float $x, float $expected)
    {
        // When
        $result = Special::chebyshevT($n, $x);

        // Then
        $this->assertEqualsWithDelta($expected, $result, 1e-10);
    }

    /**
     * Test data from SciPy scipy.special.eval_chebyt()
     */
    public function dataProviderForChebyshevT(): array
    {
        return [
            [0, -1.0, 1.0],
            [0, -0.5, 1.0],
            [0, 0.0, 1.0],
            [0, 0.5, 1.0],
            [0, 1.0, 1.0],
            [1, -1.0, -1.0],
            [1, -0.5, -0.5],
            [1, 0.0, 0.0],
            [1, 0.5, 0.5],
            [1, 1.0, 1.0],
            [2, -1.0, 1.0],
            [2, -0.5, -0.5],
            [2, 0.0, -1.0],
            [2, 0.5, -0.5],
            [2, 1.0, 1.0],
            [3, -1.0, -1.0],
            [3, -0.5, 1.0],
            [3, 0.0, -0.0],
            [3, 0.5, -1.0],
            [3, 1.0, 1.0],
            [4, -1.0, 1.0],
            [4, -0.5, -0.5],
            [4, 0.0, 1.0],
            [4, 0.5, -0.5],
            [4, 1.0, 1.0],
            [5, -1.0, -1.0],
            [5, -0.5, -0.5],
            [5, 0.0, 0.0],
            [5, 0.5, 0.5],
            [5, 1.0, 1.0],
            [10, -1.0, 1.0],
            [10, -0.5, -0.5],
            [10, 0.0, -1.0],
            [10, 0.5, -0.5],
            [10, 1.0, 1.0],
        ];
    }

    /**
     * @test Chebyshev polynomial of the second kind Uₙ(x)
     * @dataProvider dataProviderForChebyshevU
     * @param int $n
     * @param float $x
     * @param float $expected
     */
    public function testChebyshevU(int $n, float $x, float $expected)
    {
        // When
        $result = Special::chebyshevU($n, $x);

        // Then
        $this->assertEqualsWithDelta($expected, $result, 1e-10);
    }

    /**
     * Test data from SciPy scipy.special.eval_chebyu()
     */
    public function dataProviderForChebyshevU(): array
    {
        return [
            [0, -1.0, 1.0],
            [0, -0.5, 1.0],
            [0, 0.0, 1.0],
            [0, 0.5, 1.0],
            [0, 1.0, 1.0],
            [1, -1.0, -2.0],
            [1, -0.5, -1.0],
            [1, 0.0, 0.0],
            [1, 0.5, 1.0],
            [1, 1.0, 2.0],
            [2, -1.0, 3.0],
            [2, -0.5, 0.0],
            [2, 0.0, -1.0],
            [2, 0.5, 0.0],
            [2, 1.0, 3.0],
            [3, -1.0, -4.0],
            [3, -0.5, 1.0],
            [3, 0.0, -0.0],
            [3, 0.5, -1.0],
            [3, 1.0, 4.0],
            [4, -1.0, 5.0],
            [4, -0.5, -1.0],
            [4, 0.0, 1.0],
            [4, 0.5, -1.0],
            [4, 1.0, 5.0],
            [5, -1.0, -6.0],
            [5, -0.5, 0.0],
            [5, 0.0, 0.0],
            [5, 0.5, 0.0],
            [5, 1.0, 6.0],
            [10, -1.0, 11.0],
            [10, -0.5, -1.0],
            [10, 0.0, -1.0],
            [10, 0.5, -1.0],
            [10, 1.0, 11.0],
        ];
    }

    /**
     * @test Hermite polynomial (physicist's version) Hₙ(x)
     * @dataProvider dataProviderForHermiteH
     * @param int $n
     * @param float $x
     * @param float $expected
     */
    public function testHermiteH(int $n, float $x, float $expected)
    {
        // When
        $result = Special::hermiteH($n, $x);

        // Then
        $this->assertEqualsWithDelta($expected, $result, abs($expected) * 1e-10 + 1e-10);
    }

    /**
     * Test data from SciPy scipy.special.eval_hermite()
     */
    public function dataProviderForHermiteH(): array
    {
        return [
            [0, -1.0, 1.0],
            [0, -0.5, 1.0],
            [0, 0.0, 1.0],
            [0, 0.5, 1.0],
            [0, 1.0, 1.0],
            [0, 2.0, 1.0],
            [0, 5.0, 1.0],
            [1, -1.0, -2.0000000000000004],
            [1, -0.5, -1.0000000000000002],
            [1, 0.0, 0.0],
            [1, 0.5, 1.0000000000000002],
            [1, 1.0, 2.0000000000000004],
            [1, 2.0, 4.000000000000001],
            [1, 5.0, 10.000000000000002],
            [2, -1.0, 2.0000000000000004],
            [2, -0.5, -0.9999999999999999],
            [2, 0.0, -2.0],
            [2, 0.5, -0.9999999999999999],
            [2, 1.0, 2.0000000000000004],
            [2, 2.0, 14.000000000000002],
            [2, 5.0, 98.00000000000001],
            [3, -1.0, 3.999999999999999],
            [3, -0.5, 5.000000000000001],
            [3, 0.0, -0.0],
            [3, 0.5, -5.000000000000001],
            [3, 1.0, -3.999999999999999],
            [3, 2.0, 40.000000000000014],
            [3, 5.0, 940.0000000000002],
            [4, -1.0, -20.0],
            [4, -0.5, 0.9999999999999983],
            [4, 0.0, 12.0],
            [4, 0.5, 0.9999999999999983],
            [4, 1.0, -20.0],
            [4, 2.0, 76.00000000000003],
            [4, 5.0, 8812.000000000002],
            [5, -1.0, 8.000000000000016],
            [5, -0.5, -41.0],
            [5, 0.0, 0.0],
            [5, 0.5, 41.0],
            [5, 1.0, -8.000000000000016],
            [5, 2.0, -15.999999999999924],
            [5, 5.0, 80600.00000000003],
            [10, -1.0, 8223.999999999987],
            [10, -0.5, 22591.000000000004],
            [10, 0.0, -30240.0],
            [10, 0.5, 22591.000000000004],
            [10, 1.0, 8223.999999999987],
            [10, 2.0, 200416.00000000017],
            [10, 5.0, 3275529760.0000024],
        ];
    }

    /**
     * @test Hermite polynomial (probabilist's version) Heₙ(x)
     * @dataProvider dataProviderForHermiteHe
     * @param int $n
     * @param float $x
     * @param float $expected
     */
    public function testHermiteHe(int $n, float $x, float $expected)
    {
        // When
        $result = Special::hermiteHe($n, $x);

        // Then
        $this->assertEqualsWithDelta($expected, $result, abs($expected) * 1e-10 + 1e-10);
    }

    /**
     * Test data from SciPy scipy.special.eval_hermitenorm()
     */
    public function dataProviderForHermiteHe(): array
    {
        return [
            [0, -1.0, 1.0],
            [0, -0.5, 1.0],
            [0, 0.0, 1.0],
            [0, 0.5, 1.0],
            [0, 1.0, 1.0],
            [0, 2.0, 1.0],
            [0, 5.0, 1.0],
            [1, -1.0, -1.0],
            [1, -0.5, -0.5],
            [1, 0.0, 0.0],
            [1, 0.5, 0.5],
            [1, 1.0, 1.0],
            [1, 2.0, 2.0],
            [1, 5.0, 5.0],
            [2, -1.0, 0.0],
            [2, -0.5, -0.75],
            [2, 0.0, -1.0],
            [2, 0.5, -0.75],
            [2, 1.0, 0.0],
            [2, 2.0, 3.0],
            [2, 5.0, 24.0],
            [3, -1.0, 2.0],
            [3, -0.5, 1.375],
            [3, 0.0, -0.0],
            [3, 0.5, -1.375],
            [3, 1.0, -2.0],
            [3, 2.0, 2.0],
            [3, 5.0, 110.0],
            [4, -1.0, -2.0],
            [4, -0.5, 1.5625],
            [4, 0.0, 3.0],
            [4, 0.5, 1.5625],
            [4, 1.0, -2.0],
            [4, 2.0, -5.0],
            [4, 5.0, 478.0],
            [5, -1.0, -6.0],
            [5, -0.5, -6.28125],
            [5, 0.0, 0.0],
            [5, 0.5, 6.28125],
            [5, 1.0, 6.0],
            [5, 2.0, -18.0],
            [5, 5.0, 1950.0],
            [10, -1.0, 1216.0],
            [10, -0.5, 49.0439453125],
            [10, 0.0, -945.0],
            [10, 0.5, 49.0439453125],
            [10, 1.0, 1216.0],
            [10, 2.0, -2621.0],
            [10, 5.0, 179680.0],
        ];
    }

    /**
     * @test Laguerre polynomial Lₙ(x)
     * @dataProvider dataProviderForLaguerreL
     * @param int $n
     * @param float $x
     * @param float $expected
     */
    public function testLaguerreL(int $n, float $x, float $expected)
    {
        // When
        $result = Special::laguerreL($n, $x);

        // Then
        $this->assertEqualsWithDelta($expected, $result, abs($expected) * 1e-10 + 1e-10);
    }

    /**
     * Test data from SciPy scipy.special.eval_laguerre()
     */
    public function dataProviderForLaguerreL(): array
    {
        return [
            [0, 0.0, 1.0],
            [0, 0.5, 1.0],
            [0, 1.0, 1.0],
            [0, 2.0, 1.0],
            [0, 5.0, 1.0],
            [1, 0.0, 1.0],
            [1, 0.5, 0.5],
            [1, 1.0, 0.0],
            [1, 2.0, -1.0],
            [1, 5.0, -4.0],
            [2, 0.0, 1.0],
            [2, 0.5, 0.125],
            [2, 1.0, -0.5],
            [2, 2.0, -1.0],
            [2, 5.0, 3.5],
            [3, 0.0, 1.0],
            [3, 0.5, -0.14583333333333331],
            [3, 1.0, -0.6666666666666666],
            [3, 2.0, -0.33333333333333337],
            [3, 5.0, 2.6666666666666665],
            [4, 0.0, 1.0],
            [4, 0.5, -0.33072916666666663],
            [4, 1.0, -0.625],
            [4, 2.0, 0.33333333333333337],
            [4, 5.0, -1.291666666666667],
            [5, 0.0, 1.0],
            [5, 0.5, -0.44557291666666665],
            [5, 1.0, -0.4666666666666667],
            [5, 2.0, 0.7333333333333334],
            [5, 5.0, -3.166666666666667],
            [10, 0.0, 1.0],
            [10, 0.5, -0.3893744141378521],
            [10, 1.0, 0.41894593253968254],
            [10, 2.0, -0.30906525573192234],
            [10, 5.0, 1.7562761794532622],
        ];
    }

    /************************************************
     * POLYNOMIAL RECURRENCE RELATION TESTS - NIST DLMF §18.9
     * Added: 2025-09-30
     * Sources: NIST DLMF, SciPy validation
     ************************************************/

    /**
     * @test Legendre polynomial three-term recurrence: (n+1)P_{n+1}(x) = (2n+1)xP_n(x) - nP_{n-1}(x)
     * @dataProvider dataProviderForLegendrePRecurrence
     * @param int $n
     * @param float $x
     */
    public function testLegendrePRecurrence(int $n, float $x)
    {
        // When
        $Pₙ₋₁ = Special::legendreP($n - 1, $x);
        $Pₙ = Special::legendreP($n, $x);
        $Pₙ₊₁ = Special::legendreP($n + 1, $x);

        // Recurrence: (n+1)P_{n+1}(x) = (2n+1)xP_n(x) - nP_{n-1}(x)
        $lhs = ($n + 1) * $Pₙ₊₁;
        $rhs = (2 * $n + 1) * $x * $Pₙ - $n * $Pₙ₋₁;

        // Then
        $this->assertEqualsWithDelta($rhs, $lhs, abs($rhs) * 1e-12 + 1e-14);
    }

    /**
     * Test data for Legendre recurrence
     * Source: NIST DLMF §18.9.2
     * @return array
     */
    public function dataProviderForLegendrePRecurrence(): array
    {
        return [
            [1, -0.5],
            [1, 0.0],
            [1, 0.5],
            [1, 0.8],
            [2, -0.5],
            [2, 0.0],
            [2, 0.5],
            [2, 0.8],
            [3, -0.5],
            [3, 0.0],
            [3, 0.5],
            [3, 0.8],
            [5, -0.5],
            [5, 0.0],
            [5, 0.5],
            [10, -0.5],
            [10, 0.5],
        ];
    }

    /**
     * @test Chebyshev T polynomial three-term recurrence: T_{n+1}(x) = 2xT_n(x) - T_{n-1}(x)
     * @dataProvider dataProviderForChebyshevTRecurrence
     * @param int $n
     * @param float $x
     */
    public function testChebyshevTRecurrence(int $n, float $x)
    {
        // When
        $Tₙ₋₁ = Special::chebyshevT($n - 1, $x);
        $Tₙ = Special::chebyshevT($n, $x);
        $Tₙ₊₁ = Special::chebyshevT($n + 1, $x);

        // Recurrence: T_{n+1}(x) = 2xT_n(x) - T_{n-1}(x)
        $lhs = $Tₙ₊₁;
        $rhs = 2 * $x * $Tₙ - $Tₙ₋₁;

        // Then
        $this->assertEqualsWithDelta($rhs, $lhs, abs($rhs) * 1e-12 + 1e-14);
    }

    /**
     * Test data for Chebyshev T recurrence
     * Source: NIST DLMF §18.9.2
     * @return array
     */
    public function dataProviderForChebyshevTRecurrence(): array
    {
        return [
            [1, -0.5],
            [1, 0.0],
            [1, 0.5],
            [1, 0.9],
            [2, -0.5],
            [2, 0.0],
            [2, 0.5],
            [2, 0.9],
            [3, -0.5],
            [3, 0.0],
            [3, 0.5],
            [5, -0.5],
            [5, 0.0],
            [5, 0.5],
        ];
    }

    /**
     * @test Chebyshev U polynomial three-term recurrence: U_{n+1}(x) = 2xU_n(x) - U_{n-1}(x)
     * @dataProvider dataProviderForChebyshevURecurrence
     * @param int $n
     * @param float $x
     */
    public function testChebyshevURecurrence(int $n, float $x)
    {
        // When
        $Uₙ₋₁ = Special::chebyshevU($n - 1, $x);
        $Uₙ = Special::chebyshevU($n, $x);
        $Uₙ₊₁ = Special::chebyshevU($n + 1, $x);

        // Recurrence: U_{n+1}(x) = 2xU_n(x) - U_{n-1}(x)
        $lhs = $Uₙ₊₁;
        $rhs = 2 * $x * $Uₙ - $Uₙ₋₁;

        // Then
        $this->assertEqualsWithDelta($rhs, $lhs, abs($rhs) * 1e-12 + 1e-14);
    }

    /**
     * Test data for Chebyshev U recurrence
     * Source: NIST DLMF §18.9.2
     * @return array
     */
    public function dataProviderForChebyshevURecurrence(): array
    {
        return [
            [1, -0.5],
            [1, 0.0],
            [1, 0.5],
            [2, -0.5],
            [2, 0.0],
            [2, 0.5],
            [3, -0.5],
            [3, 0.0],
            [3, 0.5],
        ];
    }

    /**
     * @test Hermite H polynomial three-term recurrence: H_{n+1}(x) = 2xH_n(x) - 2nH_{n-1}(x)
     * @dataProvider dataProviderForHermiteHRecurrence
     * @param int $n
     * @param float $x
     */
    public function testHermiteHRecurrence(int $n, float $x)
    {
        // When
        $Hₙ₋₁ = Special::hermiteH($n - 1, $x);
        $Hₙ = Special::hermiteH($n, $x);
        $Hₙ₊₁ = Special::hermiteH($n + 1, $x);

        // Recurrence: H_{n+1}(x) = 2xH_n(x) - 2nH_{n-1}(x)
        $lhs = $Hₙ₊₁;
        $rhs = 2 * $x * $Hₙ - 2 * $n * $Hₙ₋₁;

        // Then
        $this->assertEqualsWithDelta($rhs, $lhs, abs($rhs) * 1e-11 + 1e-12);
    }

    /**
     * Test data for Hermite H recurrence
     * Source: NIST DLMF §18.9.2
     * @return array
     */
    public function dataProviderForHermiteHRecurrence(): array
    {
        return [
            [1, -1.0],
            [1, 0.0],
            [1, 1.0],
            [1, 2.0],
            [2, -1.0],
            [2, 0.0],
            [2, 1.0],
            [2, 2.0],
            [3, -1.0],
            [3, 0.0],
            [3, 1.0],
            [5, -1.0],
            [5, 0.0],
            [5, 1.0],
        ];
    }

    /**
     * @test Laguerre polynomial three-term recurrence: (n+1)L_{n+1}(x) = (2n+1-x)L_n(x) - nL_{n-1}(x)
     * @dataProvider dataProviderForLaguerreLRecurrence
     * @param int $n
     * @param float $x
     */
    public function testLaguerreLRecurrence(int $n, float $x)
    {
        // When
        $Lₙ₋₁ = Special::laguerreL($n - 1, $x);
        $Lₙ = Special::laguerreL($n, $x);
        $Lₙ₊₁ = Special::laguerreL($n + 1, $x);

        // Recurrence: (n+1)L_{n+1}(x) = (2n+1-x)L_n(x) - nL_{n-1}(x)
        $lhs = ($n + 1) * $Lₙ₊₁;
        $rhs = (2 * $n + 1 - $x) * $Lₙ - $n * $Lₙ₋₁;

        // Then
        $this->assertEqualsWithDelta($rhs, $lhs, abs($rhs) * 1e-11 + 1e-12);
    }

    /**
     * Test data for Laguerre recurrence
     * Source: NIST DLMF §18.9.2
     * @return array
     */
    public function dataProviderForLaguerreLRecurrence(): array
    {
        return [
            [1, 0.0],
            [1, 0.5],
            [1, 1.0],
            [1, 2.0],
            [2, 0.0],
            [2, 0.5],
            [2, 1.0],
            [2, 2.0],
            [3, 0.0],
            [3, 0.5],
            [3, 1.0],
            [5, 0.0],
            [5, 0.5],
            [5, 1.0],
        ];
    }

    /************************************************
     * SPECIAL VALUE TESTS
     * Added: 2025-10-01
     ************************************************/

    /**
     * @test Legendre polynomials at x=1: P_n(1) = 1
     * @dataProvider dataProviderForLegendrePAtOne
     * @param int $n
     */
    public function testLegendrePAtOne(int $n)
    {
        $result = Special::legendreP($n, 1.0);
        $this->assertEqualsWithDelta(1.0, $result, 1e-14);
    }

    public function dataProviderForLegendrePAtOne(): array
    {
        return [[0], [1], [2], [3], [4], [5], [10], [20]];
    }

    /**
     * @test Legendre polynomials at x=-1: P_n(-1) = (-1)^n
     * @dataProvider dataProviderForLegendrePAtMinusOne
     * @param int $n
     */
    public function testLegendrePAtMinusOne(int $n)
    {
        $result = Special::legendreP($n, -1.0);
        $expected = ($n % 2 === 0) ? 1.0 : -1.0;
        $this->assertEqualsWithDelta($expected, $result, 1e-14);
    }

    public function dataProviderForLegendrePAtMinusOne(): array
    {
        return [[0], [1], [2], [3], [4], [5], [10], [20]];
    }

    /**
     * @test Chebyshev T at x=1: T_n(1) = 1
     * @dataProvider dataProviderForChebyshevTAtOne
     * @param int $n
     */
    public function testChebyshevTAtOne(int $n)
    {
        $result = Special::chebyshevT($n, 1.0);
        $this->assertEqualsWithDelta(1.0, $result, 1e-14);
    }

    public function dataProviderForChebyshevTAtOne(): array
    {
        return [[0], [1], [2], [3], [5], [10], [15]];
    }

    /**
     * @test Chebyshev T at x=-1: T_n(-1) = (-1)^n
     * @dataProvider dataProviderForChebyshevTAtMinusOne
     * @param int $n
     */
    public function testChebyshevTAtMinusOne(int $n)
    {
        $result = Special::chebyshevT($n, -1.0);
        $expected = ($n % 2 === 0) ? 1.0 : -1.0;
        $this->assertEqualsWithDelta($expected, $result, 1e-14);
    }

    public function dataProviderForChebyshevTAtMinusOne(): array
    {
        return [[0], [1], [2], [3], [5], [10], [15]];
    }

    /**
     * @test Hermite He at x=0: He_n(0) = 0 for odd n, nonzero for even n
     * @dataProvider dataProviderForHermiteHeAtZero
     * @param int $n
     * @param float $expected
     */
    public function testHermiteHeAtZero(int $n, float $expected)
    {
        $result = Special::hermiteHe($n, 0.0);
        $this->assertEqualsWithDelta($expected, $result, abs($expected) * 1e-12 + 1e-14);
    }

    public function dataProviderForHermiteHeAtZero(): array
    {
        return [
            [0, 1.0],
            [1, 0.0],
            [2, -1.0],
            [3, 0.0],
            [4, 3.0],
            [5, 0.0],
            [6, -15.0],
        ];
    }

    /**
     * @test Exception cases for orthogonal polynomials
     */

    /**
     * @test legendreP throws OutOfBoundsException when n is negative
     */
    public function testLegendrePExceptionNNegative()
    {
        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        Special::legendreP(-1, 0.5);
    }

    /**
     * @test chebyshevT throws OutOfBoundsException when n is negative
     */
    public function testChebyshevTExceptionNNegative()
    {
        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        Special::chebyshevT(-1, 0.5);
    }

    /**
     * @test chebyshevU throws OutOfBoundsException when n is negative
     */
    public function testChebyshevUExceptionNNegative()
    {
        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        Special::chebyshevU(-1, 0.5);
    }

    /**
     * @test hermiteH throws OutOfBoundsException when n is negative
     */
    public function testHermiteHExceptionNNegative()
    {
        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        Special::hermiteH(-1, 1.0);
    }

    /**
     * @test hermiteHe throws OutOfBoundsException when n is negative
     */
    public function testHermiteHeExceptionNNegative()
    {
        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        Special::hermiteHe(-1, 1.0);
    }

    /**
     * @test laguerreL throws OutOfBoundsException when n is negative
     */
    public function testLaguerreLExceptionNNegative()
    {
        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        Special::laguerreL(-1, 1.0);
    }
}
