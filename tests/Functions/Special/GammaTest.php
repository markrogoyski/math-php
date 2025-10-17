<?php

namespace MathPHP\Tests\Functions\Special;

use MathPHP\Functions\Special;
use MathPHP\Exception;

class GammaTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         gamma returns the expected value
     * @dataProvider dataProviderForGamma
     * @param        $z
     * @param        $Γ
     * @throws       \Exception
     */
    public function testGamma($z, $Γ)
    {
        // When
        $gamma = Special::gamma($z);

        // Then
        $this->assertEqualsWithDelta($Γ, $gamma, \abs($Γ) * 1e-12 + 1e-14);
    }

    /**
     * Test data created with scipy.special.gamma
     * Test data created with R gamma(z) and online calculator https://keisan.casio.com/exec/system/1180573444
     * @return array (z, Γ)
     */
    public function dataProviderForGamma(): array
    {
        return [
            // scipy.special.gamma
            [-8.4, -3.447745221506681e-05],
            [-7.1, 0.001647824457026334],
            [-6.3, -0.0030542314729989],
            [-5.9, 0.017015074969831175],
            [-5.2, 0.031550202014925775],
            [-4.6, -0.05366459629507911],
            [-4.2, -0.16406105047761402],
            [-3.8, 0.2996321345028457],
            [-3.4, 0.32589116089216075],
            [-3.1, 1.49228976695897],
            [-2.7, -0.931082784838964],
            [-2.3, -1.4471073942559183],
            [-1.8, 3.188085911110281],
            [-1.5, 2.363271801207355],
            [-1.2, 4.850957140522098],
            [-0.9, -10.570564109631928],
            [-0.7, -4.273669982410842],
            [-0.5, -3.5449077018110318],
            [-0.3, -4.326851108825195],
            [-0.1, -10.686287021193193],
            [0.1, 9.513507698668732],
            [0.3, 2.991568987687591],
            [0.5, 1.7724538509055159],
            [0.7, 1.2980553326475581],
            [0.9, 1.0686287021193195],
            [1, 1.0],
            [1.2, 0.9181687423997608],
            [1.5, 0.8862269254527579],
            [1.8, 0.9313837709802426],
            [2, 1.0],
            [2.3, 1.1667119051981603],
            [2.7, 1.544685845850594],
            [3, 2.0],
            [3.4, 2.981206426810332],
            [3.8, 4.694174205740421],
            [4, 6.0],
            [4.2, 7.75668953579318],
            [4.9, 20.66738596185786],
            [5, 24.0],
            [5.5, 52.34277778455352],
            [6, 120.0],
            [6.3, 201.81327518474748],
            [7, 720.0],
            [7.1, 868.95685880064],
            [8, 5040.0],
            [8.4, 11405.887820016009],
            [9, 40320.0],
            [9.2, 62010.76389576469],
            [9.8, 231791.8799196757],
            [10, 362880.0],
            [-1.9, 5.563454794543114],
            [-1.99, 50.470831490356076],
            [-1.999, 500.4623295054461],
            [-1.9999, 5000.4614858369305],
            [-1.99999, 50000.46140120623],
            // R and online calculator
            [0.1, 9.51350769866873183629],
            [0.2, 4.5908437119988030532],
            [0.3, 2.99156898768759062831],
            [0.4, 2.21815954375768822306],
            [0.5, 1.772453850905516027298],
            [0.6, 1.489192248812817102394],
            [0.7, 1.29805533264755778568],
            [0.8, 1.16422971372530337364],
            [0.9, 1.0686287021193193549],
            [1,   1],
            [1.0, 1],
            [1.1, 0.951350769866873183629],
            [1.2, 0.91816874239976061064],
            [1.3, 0.89747069630627718849],
            [1.4, 0.88726381750307528922],
            [1.5, 0.88622692545275801365],
            [1.6, 0.89351534928769026144],
            [1.7, 0.90863873285329044998],
            [1.8, 0.9313837709802426989091],
            [1.9, 0.96176583190738741941],
            [2,   1],
            [2.0, 1],
            [3,   2],
            [3.0, 2],
            [4,   6],
            [4.0, 6],
            [5,   24],
            [5.0, 24],
            [6,   120],
            [6.0, 120],
            [7, 720],
            [8, 5040],
            [9, 40320],
            [10, 362880],
            [11, 3628800],
            [12, 39916800],
            [13, 479001600],
            [14, 6227020800],
            [15, 87178291200],
            [16, 1307674368000],
            [17, 20922789888000],
            [18, 355687428096000],
            [19, 6402373705728000],
            [20, 121645100408832000],
            [2.5, 1.32934038817913702047],
            [5.324, 39.54287866273389258523],
            [10.2, 570499.02784103598123],
            [-0.1, -10.686287021193193549],
            [-0.4, -3.72298062203204275599],
            [-1.1, 9.7148063829029032263],
            [-1.2, 4.8509571405220973902],
            [1E-207, 1E207],
            [2E-308, \INF],
            [-2E-309, -\INF],
            [-172.25, 0],
            [-168.0000000000001, -3.482118E-290],
            [-167.9999999999999, 3.482118E-290],
        ];
    }

    /**
     * @test gamma throws an NanException if gamma is undefined.
     * @dataProvider dataProviderUndefinedGamma
     */
    public function testGammaExceptionUndefined($x)
    {
        // Then
        $this->expectException(Exception\NanException::class);

        // When
        Special::gamma($x);
    }

    public function dataProviderUndefinedGamma()
    {
        return [
            [0],
            [0.0],
            [-1],
            [-2],
            [-2.0],
            [-3],
            [-4],
            [-10],
            [-20],
        ];
    }

    /**
     * @test         gamma of large values
     * @dataProvider dataProviderForGammaLargeValues
     * @param        $z
     * @param        $Γ
     * @throws       \Exception
     */
    public function testGammaLargeValues($z, $Γ)
    {
        // When
        $gamma = Special::gamma($z);

        // Then
        $this->assertEqualsWithDelta($Γ, $gamma, $Γ * 1e-12 + 1e-20);
    }

    /**
     * Test data created with high-precision online calculator: https://keisan.casio.com/exec/system/1180573444
     * @return array (z, Γ, ε)
     */
    public function dataProviderForGammaLargeValues(): array
    {
        return [
            [15, 87178291200],
            [20, 121645100408832000],
            [50, 6.082818640342675608723E+62],
            [100, 9.33262154439441526817E+155],
            [100.6, 1.477347552911177316693E+157],
            [171, 7.257415615307998967397E+306],
            [200, 3.943289336823952517762E+372],
        ];
    }

    /**
     * @test         gammaLanczos returns the expected value
     * @dataProvider dataProviderForGammaLanczos
     * @param        $z
     * @param        $Γ
     * @throws       \Exception
     */
    public function testGammaLanczos($z, $Γ)
    {
        // When
        $gammaLanczos = Special::gammaLanczos($z);

        // Then
        $this->assertEqualsWithDelta($Γ, $gammaLanczos, 0.001);
    }

    public function dataProviderForGammaLanczos(): array
    {
        return [
            [0.1, 9.51350769866873183629],
            [0.2, 4.5908437119988030532],
            [0.3, 2.99156898768759062831],
            [0.4, 2.21815954375768822306],
            [0.5, 1.772453850905516027298],
            [0.6, 1.489192248812817102394],
            [0.7, 1.29805533264755778568],
            [0.8, 1.16422971372530337364],
            [0.9, 1.0686287021193193549],
            [1,   1],
            [1.0, 1],
            [1.1, 0.951350769866873183629],
            [1.2, 0.91816874239976061064],
            [1.3, 0.89747069630627718849],
            [1.4, 0.88726381750307528922],
            [1.5, 0.88622692545275801365],
            [1.6, 0.89351534928769026144],
            [1.7, 0.90863873285329044998],
            [1.8, 0.9313837709802426989091],
            [1.9, 0.96176583190738741941],
            [2,   1],
            [2.0, 1],
            [3,   2],
            [3.0, 2],
            [4,   6],
            [4.0, 6],
            [5,   24],
            [5.0, 24],
            [6,   120],
            [6.0, 120],
            [2.5, 1.32934038817913702047],
            [5.324, 39.54287866273389258523],
            [10.2, 570499.02784103598123],
            [0, \INF],
            [0.0, \INF],
            [-1, -\INF],
            [-2, -\INF],
            [-2.0, -\INF],
            [-0.1, -10.686287021193193549],
            [-0.4, -3.72298062203204275599],
            [-1.1, 9.7148063829029032263],
            [-1.2, 4.8509571405220973902],
        ];
    }

    /**
     * @test gammaLanczos integer detection edge cases
     * @dataProvider dataProviderForGammaLanczosIntegerDetectionEdgeCases
     * @param float $x
     * @param float $expected
     */
    public function testGammaLanczosIntegerDetectionEdgeCases(float $x, float $expected)
    {
        // When
        $result = Special::gammaLanczos($x);

        // Then
        $this->assertEqualsWithDelta($expected, $result, abs($expected) * 1e-10 + 1e-14);
    }

    public function dataProviderForGammaLanczosIntegerDetectionEdgeCases(): array
    {
        return [
            // Near-integer values - should NOT use factorial path
            [2.000009, 1.00000380509237519e+00],
            [2.000001, 1.00000042278474699e+00],
            [3.000005, 2.00000922787451252e+00],
            [5.00001, 2.40003614712280857e+01],
        ];
    }

    /**
     * @test         gammaStirling returns the expected value
     * @dataProvider dataProviderForGammaStirling
     * @param        $n
     * @param        $Γ
     * @throws       \Exception
     */
    public function testGammaStirling($n, $Γ)
    {
        // When
        $gammaSterling = Special::gammaStirling($n);

        // Then
        $this->assertEqualsWithDelta($Γ, $gammaSterling, 0.01);
    }

    public function dataProviderForGammaStirling(): array
    {
        return [
            [ 1, 1 ],
            [ 1.0, 1 ],
            [ 2, 1 ],
            [ 3, 2 ],
            [ 4, 6 ],
            [ 5, 24 ],
            [ 6, 120 ],
            [ 1.1, 0.951350769866873183629 ],
            [ 1.2, 0.91816874239976061064 ],
            [ 1.5, 0.88622692545275801365 ],
            [ 2.5, 1.32934038817913702047 ],
            [ 5.324, 39.54287866273389258523 ],
            [ 10.2, 570499.02784103598123 ],
            [ 0, \INF ],
            [ -1, -\INF ],
            [ -2, -\INF ],
            [ -2.0, -\INF ],
        ];
    }

    /**
     * @test gammaStirling integer detection edge cases (Fix for Issue 1.1)
     * @dataProvider dataProviderForGammaStirlingIntegerDetectionEdgeCases
     * @param float $x
     * @param float $expected
     */
    public function testGammaStirlingIntegerDetectionEdgeCases(float $x, float $expected)
    {
        // When
        $result = Special::gammaStirling($x);

        // Then - Stirling approximation is less accurate, use looser tolerance
        $this->assertEqualsWithDelta($expected, $result, abs($expected) * 1e-3);
    }

    public function dataProviderForGammaStirlingIntegerDetectionEdgeCases(): array
    {
        return [
            // Near-integer values - should NOT use factorial path
            [2.000009, 1.00000380509237519e+00],
            [2.000001, 1.00000042278474699e+00],
            [3.000005, 2.00000922787451252e+00],
            [5.00001, 2.40003614712280857e+01],
        ];
    }

    /**
     * @test         logGamma returns the expected value
     * @dataProvider dataProviderForLogGamma
     * @param        $z
     * @param        $Γ
     * @throws       \Exception
     */
    public function testLogGamma($z, $Γ)
    {
        // When
        $log_gamma = Special::logGamma($z);

        // Then
        $this->assertEqualsWithDelta($Γ, $log_gamma, abs($Γ) * 0.00001);
    }

    /**
     * Test data produced with R function lgamma(x)
     * @return array
     */
    public function dataProviderForLogGamma(): array
    {
        return [
            [-2, \INF],
            [-1.9, 1.716219],
            [-1.5, 0.860047],
            [-1, \INF],
            [-0.1, 2.368961],
            [0, \INF],
            [0.1, 2.252713],
            [0.5, 0.5723649],
            [1, 0],
            [1.0, 0],
            [2, 0],
            [2.1, 0.04543774],
            [2.5, 0.2846829],
            [3, 0.6931472],
            [10, 12.80183],
            [100, 359.1342],
            [1000, 5905.22],
            [10000, 82099.72],
            [100000, 1051288],
            [5E-307, 705.2842],
            [2.6E305, \INF],
            [2E17, 7.767419e+18],
            [4934770, 71118994],
            [-.9, 2.358073],
            [-11.2, -16.31644474],
        ];
    }

    /**
     * @test logGamma returns NaNException appropriately
     */
    public function testLogGammaNan()
    {
        // Given
        $nan = acos(1.01);

        // Then
        $this->expectException(Exception\NanException::class);

        // When
        $nan = Special::logGamma($nan);
    }

     /**
     * @test gamma returns NaNException appropriately
     */
    public function testGammaNan()
    {
        // Given
        $nan = \NAN;

        // Then
        $this->expectException(Exception\NanException::class);

        // When
        $nan = Special::gamma($nan);
    }

    /**
     * @test logGammaCorr parameter must be greater than 10
     */
    public function testLogGammaCorrOutOfBounds()
    {
        // Given
        $x = 1;

        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        $correction = Special::logGammaCorr($x);
    }

    /**
     * @test         gamma returns exact values for known mathematical constants
     * @dataProvider dataProviderForGammaExactValues
     * @param        float $x
     * @param        float $expected
     * @throws       \Exception
     */
    public function testGammaExactValues(float $x, float $expected)
    {
        // When
        $gamma = Special::gamma($x);

        // Then
        // Using very tight tolerance for mathematically exact values
        $this->assertEqualsWithDelta($expected, $gamma, abs($expected) * 1e-14);
    }

    /**
     * Test data from NIST DLMF §5.4 and mpmath high-precision calculations
     * Source: https://dlmf.nist.gov/5.4
     * These are mathematically exact values computed with 50 decimal places precision
     * @return array (x, Γ(x))
     */
    public function dataProviderForGammaExactValues(): array
    {
        return [
            // NIST DLMF exact fractional values (§5.4)
            // Generated with: mpmath.gamma(x) with mp.dps=50
            [0.5, 1.77245385090551610e+00],         // Γ(1/2) = √π
            [1/3, 2.67893853470774790e+00],         // Γ(1/3)
            [2/3, 1.35411793942640046e+00],         // Γ(2/3)
            [0.25, 3.62560990822190821e+00],        // Γ(1/4)
            [0.75, 1.22541670246517764e+00],        // Γ(3/4)
            [1/6, 5.56631600178023511e+00],         // Γ(1/6)
            [5/6, 1.12878702990812596e+00],         // Γ(5/6)

            // Small positive values (high precision)
            [0.1, 9.51350769866873058e+00],
            [0.2, 4.59084371199880259e+00],
            [0.3, 2.99156898768759083e+00],
            [0.4, 2.21815954375768820e+00],
            [0.6, 1.48919224881281709e+00],
            [0.7, 1.29805533264755790e+00],
            [0.8, 1.16422971372530326e+00],
            [0.9, 1.06862870211931926e+00],

            // Negative non-integer values (reflection formula)
            [-0.5, -3.54490770181103221e+00],       // Γ(-1/2) = -2√π
            [-1.5, 2.36327180120735481e+00],        // Γ(-3/2) = 4√π/3
            [-2.5, -9.45308720482941900e-01],       // Γ(-5/2) = -8√π/15
            [-3.5, 2.70088205852269114e-01],        // Γ(-7/2) = 16√π/105
            [-0.1, -1.06862870211931931e+01],
            [-0.9, -1.05705641096319258e+01],
            [-1.1, 9.71480638290289455e+00],
            [-2.1, -4.62609827757280634e+00],
        ];
    }

    /**
     * @test         gamma functional equation Γ(z+1) = z·Γ(z)
     * @dataProvider dataProviderForGammaFunctionalEquation
     * @param        float $z
     * @throws       \Exception
     */
    public function testGammaFunctionalEquation(float $z)
    {
        // When
        $gamma_z = Special::gamma($z);
        $gamma_z_plus_1 = Special::gamma($z + 1);

        // Then
        // Γ(z+1) should equal z·Γ(z)
        $expected = $z * $gamma_z;
        $this->assertEqualsWithDelta($expected, $gamma_z_plus_1, abs($expected) * 1e-12);
    }

    /**
     * Test values for gamma functional equation
     * Source: Mathematical identity Γ(z+1) = z·Γ(z)
     * @return array
     */
    public function dataProviderForGammaFunctionalEquation(): array
    {
        return [
            [0.5],
            [1.5],
            [2.5],
            [3.7],
            [5.2],
            [10.3],
            [0.1],
            [0.9],
            [15.5],
        ];
    }

    /**
     * @test         gamma reflection formula Γ(z)Γ(1-z) = π/sin(πz)
     * @dataProvider dataProviderForGammaReflectionFormula
     * @param        float $z
     * @throws       \Exception
     */
    public function testGammaReflectionFormula(float $z)
    {
        // When
        $gamma_z = Special::gamma($z);
        $gamma_1_minus_z = Special::gamma(1 - $z);
        $∏ = $gamma_z * $gamma_1_minus_z;

        // Then Γ(z)Γ(1-z) should equal π/sin(πz)
        $expected = \M_PI / \sin(\M_PI * $z);
        $this->assertEqualsWithDelta($expected, $∏, abs($expected) * 1e-12);
    }

    /**
     * Test values for gamma reflection formula
     * Source: NIST DLMF §5.5(iii) Reflection Formula
     * https://dlmf.nist.gov/5.5#iii
     * @return array
     */
    public function dataProviderForGammaReflectionFormula(): array
    {
        return [
            [0.3],
            [0.7],
            [0.25],
            [0.75],
            [0.1],
            [0.9],
            [0.4],
            [0.6],
        ];
    }

    /**
     * @test         gamma duplication formula (Legendre)
     * @dataProvider dataProviderForGammaDuplicationFormula
     * @param        float $z
     * @throws       \Exception
     */
    public function testGammaDuplicationFormula(float $z)
    {
        // When
        $gamma_z = Special::gamma($z);
        $gamma_z_plus_half = Special::gamma($z + 0.5);
        $gamma_2z = Special::gamma(2 * $z);

        // Legendre duplication formula: Γ(z)Γ(z+1/2) = 2^(1-2z)·√π·Γ(2z)
        $lhs = $gamma_z * $gamma_z_plus_half;
        $rhs = \pow(2, 1 - 2*$z) * \sqrt(\M_PI) * $gamma_2z;

        // Then
        $this->assertEqualsWithDelta($rhs, $lhs, abs($rhs) * 1e-11);
    }

    /**
     * Test values for Legendre duplication formula
     * Source: NIST DLMF §5.5(ii) Multiplication Formulas
     * https://dlmf.nist.gov/5.5#ii
     * @return array
     */
    public function dataProviderForGammaDuplicationFormula(): array
    {
        return [
            [0.5],
            [1.0],
            [1.5],
            [2.0],
            [3.0],
            [5.0],
            [0.75],
            [2.25],
        ];
    }

    /**
     * @test Gamma function is log-convex (Bohr-Mollerup theorem)
     * Test: Γ(x)^2 ≤ Γ(x-ε)Γ(x+ε) for small ε
     * @dataProvider dataProviderForGammaLogConvexity
     * @param float $x
     */
    public function testGammaLogConvexity(float $x)
    {
        $epsilon = 0.1;

        // When
        $gamma_x = Special::gamma($x);
        $gamma_x_minus = Special::gamma($x - $epsilon);
        $gamma_x_plus = Special::gamma($x + $epsilon);

        // Log-convexity: log(Γ(x)) is convex
        // Equivalent to: Γ(x)^2 ≤ Γ(x-ε)Γ(x+ε)
        $lhs = $gamma_x * $gamma_x;
        $rhs = $gamma_x_minus * $gamma_x_plus;

        // Then (with small numerical tolerance)
        $this->assertLessThanOrEqual($rhs * 1.001, $lhs);
    }

    public function dataProviderForGammaLogConvexity(): array
    {
        return [
            [1.5],
            [2.0],
            [2.5],
            [3.0],
            [4.0],
            [5.0],
        ];
    }

    /**
     * @test Gamma function behavior near overflow
     * @dataProvider dataProviderForGammaNearOverflow
     * @param float $x
     */
    public function testGammaNearOverflow(float $x)
    {
        // When
        $result = Special::gamma($x);

        // Then - should either produce a finite value or INF (not NAN)
        $this->assertTrue(is_finite($result) || $result === INF, "Gamma($x) should be finite or INF, got: $result");

        // If finite, should be positive
        if (is_finite($result)) {
            $this->assertGreaterThan(0, $result);
        }
    }

    public function dataProviderForGammaNearOverflow(): array
    {
        // Γ(x) grows very rapidly for large x
        // PHP_FLOAT_MAX ≈ 1.8e308, Γ(171.624) ≈ 1.8e308 (overflow threshold)
        return [
            [50.0],   // Still computable
            [100.0],  // Still computable
            [150.0],  // Near overflow
            [170.0],  // Very near overflow
            [171.0],  // At/beyond overflow
        ];
    }

    /**
     * @test Cross-validation of gamma function against Wolfram Alpha
     * @dataProvider dataProviderForGammaWolfram
     * @param float $x
     * @param float $expected Wolfram Alpha high-precision result
     */
    public function testGammaCrossValidationWolfram(float $x, float $expected)
    {
        // When
        $result = Special::gamma($x);

        // Then - compare against Wolfram Alpha reference
        $this->assertEqualsWithDelta($expected, $result, abs($expected) * 1e-12 + 1e-14);
    }

    public function dataProviderForGammaWolfram(): array
    {
        // Reference values from Wolfram Alpha (20+ digit precision)
        return [
            [0.5, 1.7724538509055160273],      // Γ(1/2) = √π
            [1.5, 0.8862269254527580137],      // Γ(3/2) = √π/2
            [2.5, 1.3293403881791370205],      // Γ(5/2) = 3√π/4
            [3.7, 4.1706517837966040],
            [5.2, 32.5780960503313537],
            [10.0, 362880.0],                  // Γ(10) = 9!
        ];
    }

    /**
     * @test Integer detection edge cases
     * Tests that near-integer values are NOT treated as integers
     * @dataProvider dataProviderForGammaIntegerDetectionEdgeCases
     * @param float $x
     * @param float $expected
     */
    public function testGammaIntegerDetectionEdgeCases(float $x, float $expected)
    {
        // When
        $result = Special::gamma($x);

        // Then - use tight tolerance to ensure correct computation
        $this->assertEqualsWithDelta($expected, $result, abs($expected) * 1e-10 + 1e-14);
    }

    /**
     * Test data for integer detection edge cases
     * Generated using SciPy 2.3.3
     *
     * @return array
     */
    public function dataProviderForGammaIntegerDetectionEdgeCases(): array
    {
        return [
            // Near-integer positive values - should NOT use factorial path
            [2.000009, 1.00000380509237519e+00],
            [2.000001, 1.00000042278474699e+00],
            [1.999991, 9.99996194974343000e-01],
            [1.999999, 9.99999577216076574e-01],
            [3.000005, 2.00000922787451252e+00],
            [3.000001, 2.00000184556991689e+00],
            [2.999995, 1.99999077218781074e+00],
            [2.999999, 1.99999815443257600e+00],
            [5.00001, 2.40003614712280857e+01],
            [4.99999, 2.39996385347472305e+01],

            // Near-integer negative values (non-pole) - should use reflection formula
            [-0.5000001, -3.54490768887583130e+00],
            [-0.4999999, -3.54490771474654975e+00],
            [-1.5000001, 2.36327163503244453e+00],
            [-1.4999999, 2.36327196738249823e+00],
            [-2.5000001, -9.45308616200633667e-01],
            [-2.4999999, -9.45308824765352163e-01],

            // Exact integers for comparison - should use factorial for positive
            [1.0, 1.00000000000000000e+00],
            [2.0, 1.00000000000000000e+00],
            [3.0, 2.00000000000000000e+00],
            [4.0, 6.00000000000000000e+00],
            [5.0, 2.40000000000000000e+01],
        ];
    }

    /**
     * @test Near-pole negative integer values should compute without exceptions
     * @dataProvider dataProviderForGammaNearPoleValues
     * @param float $x
     */
    public function testGammaNearPoleValues(float $x)
    {
        // When
        $result = Special::gamma($x);

        // Then - should return a very large finite value or INF, NOT throw NanException
        $this->assertTrue(is_finite($result) || $result === INF || $result === -INF, "Gamma($x) near pole should compute (large value or INF), got: $result");
    }

    public function dataProviderForGammaNearPoleValues(): array
    {
        return [
            [-1.000001],     // Very close to pole at -1, but should compute
            [-1.000000001],  // Extremely close to pole at -1
            [-2.000001],     // Very close to pole at -2
            [-2.000000001],  // Extremely close to pole at -2
            [-3.000001],     // Very close to pole at -3
        ];
    }

    /**
     * @test stirlingError throws NanException when n < 0
     */
    public function testStirlingErrorExceptionNegative()
    {
        // Then
        $this->expectException(Exception\NanException::class);

        // When
        Special::stirlingError(-1);
    }
}
