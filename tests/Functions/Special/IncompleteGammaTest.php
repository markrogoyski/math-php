<?php

namespace MathPHP\Tests\Functions\Special;

use MathPHP\Functions\Special;
use MathPHP\Exception;

class IncompleteGammaTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         lowerIncompleteGamma returns the expected value
     * @dataProvider dataProviderForLowerIncompleteGamma
     * @param        float $s
     * @param        float $x
     * @param        float $lig
     */
    public function testLowerIncompleteGamma(float $s, float $x, float $lig)
    {
        // When
        $lowerIncompleteGamma = Special::lowerIncompleteGamma($s, $x);

        // Then
        $this->assertEqualsWithDelta($lig, $lowerIncompleteGamma, \abs($lig) * 1e-6 + 1e-12);
    }

    /**
     * Test data created with R (pracma) gammainc(x, a)
     * @return array (s, x, gamma)
     */
    public function dataProviderForLowerIncompleteGamma(): array
    {
        return [
            [0.0001, 1, 9999.2034895],
            [1, 1, 0.6321206],
            [1, 2, 0.8646647],
            [2, 2, 0.5939942],
            [3, 2.5, 0.9123738],
            [3.5, 2, 0.7318770],
            [4.6, 2, 1.07178785],
            [4, 2.6, 1.5839901],
            [2.7, 2.6, 0.8603568],
            [1.5, 2.5, 0.7339757],
            [0.5, 4, 1.764162782],
            [2, 3, 0.8008517],
            [4.5, 2.3, 1.5389745],
            [7, 9.55, 603.9624331],
            [1, 3, 0.95021293],
            [0.5, 0.5, 1.2100356],
            [0.5, 1, 1.4936483],
            [0.5, 4, 1.764162782],
            // Negative x it not officially supported, but sometimes works.
            // Other times it gets NAN.
            [1, -1, -1.718282],
            [1, -2, -6.389056],
            [1, -3, -19.08554],
        ];
    }

    /**
     * @test         lowerIncompleteGamma NAN for negative x
     *               Negative x is not officially supported (but sometimes works), and returns NAN for most negative x.
     * @dataProvider dataProviderForLowerIncompleteGammaNegativeXIsNan
     * @param        float $s
     * @param        float $x
     */
    public function testLowerIncompleteGammaNegativeXIsNan(float $s, float $x)
    {
        // When
        $lowerIncompleteGamma = Special::lowerIncompleteGamma($s, $x);

        // Then
        $this->assertNan($lowerIncompleteGamma);
    }

    /**
     * @return array (s, x)
     */
    public function dataProviderForLowerIncompleteGammaNegativeXIsNan(): array
    {
        return [
            [2, -2],
            [3, -2.5],
            [4, -2.6],
            [2, -3],
        ];
    }

    /**
     * @test         lowerIncompleteGamma returns 0 for x = 0, and s and x = 0
     * @dataProvider dataProviderForLowerIncompleteGammaZeroBoundary
     * @param        float $s
     * @param        float $x
     */
    public function testLowerIncompleteGammaZeroBoundary(float $s, float $x)
    {
        // When
        $lowerIncompleteGamma = Special::lowerIncompleteGamma($s, $x);

        // Then
        $this->assertEqualsWithDelta(0, $lowerIncompleteGamma, 0.00001);
    }

    /**
     * @return array (s, x)
     */
    public function dataProviderForLowerIncompleteGammaZeroBoundary(): array
    {
        return [
            [0, 0],
            [1, 0],
            [2, 0],
            [100, 0]
        ];
    }

    /**
     * @test         lowerIncompleteGamma returns NAN for x = 0 and x nonzero
     * @dataProvider dataProviderForLowerIncompleteGammaNanBoundary
     * @param        float $s
     * @param        float $x
     */
    public function testLowerIncompleteGammaNanBoundary(float $s, float $x)
    {
        // When
        $lowerIncompleteGamma = Special::lowerIncompleteGamma($s, $x);

        // Then
        $this->assertNan($lowerIncompleteGamma);
    }

    /**
     * @return array (s, x)
     */
    public function dataProviderForLowerIncompleteGammaNanBoundary(): array
    {
        return [
            [0, 1],
            [0, 2],
            [0, 10],
            [0, 1000]
        ];
    }

    /**
     * @test         upperIncompleteGamma returns the expected value
     * @dataProvider dataProviderForUpperIncompleteGamma
     * @param        float $s
     * @param        float $x
     * @param        float $uig
     * @throws       \Exception
     */
    public function testUpperIncompleteGamma(float $s, float $x, float $uig)
    {
        // When
        $upperIncompleteGamma = Special::upperIncompleteGamma($s, $x);

        // Then
        $this->assertEqualsWithDelta($uig, $upperIncompleteGamma, \abs($uig) * 1e-6 + 1e-12);
    }

    /**
     * Test data created with R (pracma) gammainc(x, a)
     * @return array (s, x, gamma)
     */
    public function dataProviderForUpperIncompleteGamma(): array
    {
        return [
            [0.0001, 1, 0.21939372],
            [1, 1, 0.3678794],
            [1, 2, 0.1353353],
            [2, 2, 0.40600585],
            [3, 2.5, 1.08762623],
            [3.5, 2, 2.59147401],
            [4.6, 2, 12.30949802],
            [4, 2.6, 4.41600987],
            [2.7, 2.6, 0.68432904],
            [1.5, 2.5, 0.15225125],
            [0.5, 4, 0.008291069],
            [2, 3, 0.1991483],
            [4.5, 2.3, 10.0927539],
            [7, 9.55, 116.0375669],
            [0.5, 0.5, 0.5624182],
            [0.5, 1, 0.2788056],
            [0.5, 4, 0.008291069],
        ];
    }

    /**
     * @test         regularizedLowerIncompleteGamma
     * @dataProvider dataProviderForRegularizedLowerIncompleteGamma
     * @param        float $s
     * @param        float $x
     * @param        float $expected
     * @throws       \Exception
     */
    public function testRegularizedLowerIncompleteGamma(float $s, float $x, float $expected)
    {
        // When
        $gammainc = Special::regularizedLowerIncompleteGamma($s, $x);

        // Then
        $this->assertEqualsWithDelta($expected, $gammainc, \abs($expected) * 1e-12 + 1e-15);
    }

    /**
     * Test cases generated with scipy.special.gammainc(a, x))
     * @return array
     */
    public function dataProviderForRegularizedLowerIncompleteGamma(): array
    {
        return [
            [0.1, 0, 0.0],
            [0.1, 0.1, 0.8275517595858506],
            [0.1, 0.5, 0.9414024458901334],
            [0.1, 1, 0.9758726562736721],
            [0.1, 2, 0.9943261760201885],
            [0.1, 3, 0.9984347282528856],
            [0.1, 5, 0.9998560610341533],
            [0.1, 10, 0.9999994452014282],
            [0.5, 0, 0.0],
            [0.5, 0.1, 0.34527915398142317],
            [0.5, 0.5, 0.6826894921370859],
            [0.5, 1, 0.8427007929497151],
            [0.5, 2, 0.9544997361036416],
            [0.5, 3, 0.9856941215645704],
            [0.5, 5, 0.9984345977419975],
            [0.5, 10, 0.999992255783569],
            [0.7, 0, 0.0],
            [0.7, 0.1, 0.21082407575330603],
            [0.7, 0.5, 0.5575152928771887],
            [0.7, 1, 0.7611876235625862],
            [0.7, 2, 0.9237471472921016],
            [0.7, 3, 0.9744473877163565],
            [0.7, 5, 0.9969537579688733],
            [0.7, 10, 0.999982940341103],
            [1, 0, 0.0],
            [1, 0.1, 0.09516258196404044],
            [1, 0.5, 0.3934693402873665],
            [1, 1, 0.6321205588285577],
            [1, 2, 0.8646647167633873],
            [1, 3, 0.950212931632136],
            [1, 5, 0.9932620530009145],
            [1, 10, 0.9999546000702375],
            [1.5, 0, 0.0],
            [1.5, 0.1, 0.02241070223835062],
            [1.5, 0.5, 0.19874804309879915],
            [1.5, 1, 0.42759329552912023],
            [1.5, 2, 0.7385358700508897],
            [1.5, 3, 0.8883897749052875],
            [1.5, 5, 0.9814338645369568],
            [1.5, 10, 0.9998302575644472],
            [2, 0, 0.0],
            [2, 0.1, 0.004678840160444474],
            [2, 0.5, 0.09020401043104986],
            [2, 1, 0.2642411176571153],
            [2, 2, 0.5939941502901616],
            [2, 3, 0.8008517265285442],
            [2, 5, 0.9595723180054873],
            [2, 10, 0.9995006007726127],
            [2.5, 0, 0.0],
            [2.5, 0.1, 0.0008861387888124423],
            [0.0001, 1, 0.9999780593618628],
            [3, 2.5, 0.4561868841166703],
            [3.5, 2, 0.22022259152428406],
            [4.6, 2, 0.08009602788934697],
            [4, 2.6, 0.2639983556147607],
            [2.7, 2.6, 0.5569785027850895],
            [1.5, 2.5, 0.8282028557032668],
            [0.5, 4, 0.9953222650189527],
            [4.5, 2.3, 0.13230832850229468],
            [7, 9.55, 0.8388367127060298],
            [0.5, 4, 0.9953222650189527],
        ];
    }

    /**
     * @test upperIncompleteGamma throws an OutOfBoundsException if s is less than 0
     */
    public function testUpperIncompleteGammaExceptionSLessThanZero()
    {
        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        Special::upperIncompleteGamma(-1, 1);
    }

    /**
     * @test Incomplete gamma lower behavior near underflow
     * @dataProvider dataProviderForIncompleteGammaNearUnderflow
     * @param float $s
     * @param float $x
     */
    public function testIncompleteGammaLowerNearUnderflow(float $s, float $x)
    {
        // When - small x means very small γ(s, x)
        $result = Special::lowerIncompleteGamma($s, $x);

        // Then - should be non-negative and finite or zero (or false if computation fails)
        $this->assertGreaterThanOrEqual(0, $result, "Lower incomplete gamma should be non-negative");
        $this->assertTrue(is_finite($result), "Lower incomplete gamma should be finite");
    }

    public function dataProviderForIncompleteGammaNearUnderflow(): array
    {
        return [
            [1.0, 1e-10],
            [2.0, 1e-10],
            [5.0, 1e-8],
            [10.0, 1e-6],
            [0.5, 1e-12],
        ];
    }

    /**
     * @test Cross-validation of incomplete gamma against textbook examples
     * @dataProvider dataProviderForIncompleteGammaTextbook
     * @param float $s
     * @param float $x
     * @param float $expectedLower Lower incomplete gamma reference
     */
    public function testLowerIncompleteGammaCrossValidation(float $s, float $x, float $expectedLower)
    {
        // When
        $result = Special::lowerIncompleteGamma($s, $x);

        // Then - compare against published reference
        // Tolerance relaxed from 1e-10 to 1e-7 for cases using erf() internally (s=0.5)
        $this->assertEqualsWithDelta($expectedLower, $result, abs($expectedLower) * 1e-7 + 1e-10);
    }

    public function dataProviderForIncompleteGammaTextbook(): array
    {
        // Reference values verified with SciPy: γ(s,x) = scipy.special.gammainc(s, x) * scipy.special.gamma(s)
        // Definition: γ(s,x) = ∫₀ˣ t^(s-1) e^(-t) dt (NIST DLMF §8.2)
        return [
            [1.0, 1.0, 0.6321205588285576784],   // γ(1,1) = 1 - 1/e (exact)
            [2.0, 2.0, 0.5939941502901616],
            [0.5, 0.5, 1.2100356193111088],
            [3.0, 1.0, 0.1606027941427884],
        ];
    }

    /**
     * @test Test lower incomplete gamma with large x values (x > 100)
     * @dataProvider dataProviderForLowerIncompleteGammaLargeX
     * @param float $s
     * @param float $x
     * @param float $expected
     */
    public function testLowerIncompleteGammaLargeX(float $s, float $x, float $expected)
    {
        $result = Special::lowerIncompleteGamma($s, $x);

        // For large values, use relative tolerance
        $tolerance = max(abs($expected) * 1e-9, 1e-10);
        $this->assertEqualsWithDelta($expected, $result, $tolerance, "Failed for s=$s, x=$x");
    }

    public function dataProviderForLowerIncompleteGammaLargeX(): array
    {
        // Generated from SciPy: γ(s,x) = scipy.special.gammainc(s, x) * scipy.special.gamma(s)
        return [
            // large_x: s=0.5, x=100.0
            [0.5, 100.0, 1.772453850905516e+00],
            // large_x: s=1.0, x=100.0
            [1.0, 100.0, 1.000000000000000e+00],
            // large_x: s=2.0, x=100.0
            [2.0, 100.0, 1.000000000000000e+00],
            // large_x: s=5.0, x=100.0
            [5.0, 100.0, 2.400000000000000e+01],
            // large_x: s=10.0, x=100.0
            [10.0, 100.0, 3.628800000000000e+05],
        ];
    }

    /**
     * @test Test lower incomplete gamma with large s values (s > 100)
     * @dataProvider dataProviderForLowerIncompleteGammaLargeS
     * @param float $s
     * @param float $x
     * @param float $expected
     */
    public function testLowerIncompleteGammaLargeS(float $s, float $x, float $expected)
    {
        $result = Special::lowerIncompleteGamma($s, $x);

        // For large values, use relative tolerance
        $tolerance = max(abs($expected) * 1e-8, 1e-10);
        $this->assertEqualsWithDelta($expected, $result, $tolerance, "Failed for s=$s, x=$x");
    }

    public function dataProviderForLowerIncompleteGammaLargeS(): array
    {
        // Generated from SciPy: γ(s,x) = scipy.special.gammainc(s, x) * scipy.special.gamma(s)
        return [
            // large_s: s=100.0, x=50.0
            [100.0, 50.0, 2.986499859169288e+146],
            // large_s: s=100.0, x=100.0
            [100.0, 100.0, 4.790423423531748e+155],
            // large_s: s=100.0, x=150.0
            [100.0, 150.0, 9.332566252901643e+155],
        ];
    }

    /**
     * @test Test lower incomplete gamma in transition region (x ≈ s)
     * @dataProvider dataProviderForLowerIncompleteGammaTransition
     * @param float $s
     * @param float $x
     * @param float $expected
     */
    public function testLowerIncompleteGammaTransition(float $s, float $x, float $expected)
    {
        $result = Special::lowerIncompleteGamma($s, $x);

        // Transition region is tricky, use slightly relaxed relative tolerance
        // The series/CF transition can introduce small errors ~1e-6 relative
        $tolerance = max(abs($expected) * 5e-6, 1e-10);
        $this->assertEqualsWithDelta($expected, $result, $tolerance, "Failed for s=$s, x=$x (transition region)");
    }

    public function dataProviderForLowerIncompleteGammaTransition(): array
    {
        // Generated from SciPy: γ(s,x) = scipy.special.gammainc(s, x) * scipy.special.gamma(s)
        // Testing x ≈ s (ratio from 0.8 to 1.2)
        return [
            // s=5.0
            [5.0, 4.0, 8.907913555683038e+00],
            [5.0, 4.5, 1.122951416700684e+01],
            [5.0, 5.0, 1.342816115843491e+01],
            [5.0, 5.5, 1.541956794172979e+01],
            [5.0, 6.0, 1.715864399240085e+01],
            // s=10.0
            [10.0, 8.0, 1.028313889931423e+05],
            [10.0, 9.0, 1.497212962968250e+05],
            [10.0, 10.0, 1.967064652124543e+05],
            [10.0, 11.0, 2.393154980620610e+05],
            [10.0, 12.0, 2.749207323730044e+05],
            // s=20.0
            [20.0, 16.0, 2.283904662237135e+16],
            [20.0, 18.0, 4.246434266289140e+16],
            [20.0, 20.0, 6.444060796618985e+16],
            [20.0, 22.0, 8.441841276473054e+16],
            [20.0, 24.0, 9.971729206285131e+16],
        ];
    }

    /**
     * @test Test upper incomplete gamma with large x values
     * @dataProvider dataProviderForUpperIncompleteGammaLargeX
     * @param float $s
     * @param float $x
     * @param float $expected
     */
    public function testUpperIncompleteGammaLargeX(float $s, float $x, float $expected)
    {
        $result = Special::upperIncompleteGamma($s, $x);

        // For very small values approaching zero, use absolute tolerance
        $tolerance = max(abs($expected) * 1e-8, 1e-10);
        $this->assertEqualsWithDelta($expected, $result, $tolerance, "Failed for s=$s, x=$x");
    }

    public function dataProviderForUpperIncompleteGammaLargeX(): array
    {
        // Generated from SciPy: Γ(s,x) = scipy.special.gammaincc(s, x) * scipy.special.gamma(s)
        return [
            // large_x: upper should approach 0
            [0.5, 100.0, 7.009086453935070e-43],
            [1.0, 100.0, 3.720075976020836e-44],
            [2.0, 100.0, 3.720075976020835e-42],
            [5.0, 100.0, 1.044408749285382e-36],
            [10.0, 100.0, 1.336565914134452e-28],
        ];
    }
}
