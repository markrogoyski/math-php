<?php

namespace MathPHP\Tests\Functions\Special;

use MathPHP\Functions\Special;
use MathPHP\Exception;

/**
 * Argument Range Coverage Tests (P1.2)
 *
 * Tests all algorithmic regions (small arg, asymptotic, transition) to ensure
 * proper behavior across the entire domain of each special function:
 *
 * - Subnormal/extreme small values
 * - Very large arguments (asymptotic formulas)
 * - Transition regions between algorithms
 * - Boundary behavior near critical points
 *
 * Test data generated from mpmath (50 decimal places) via:
 * python scripts/test_generators/argument_range_tests.py
 */
class ArgumentRangeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test Gamma function across full argument range
     * @dataProvider dataProviderForGammaRange
     * @param string $region        Region identifier (small, large, integer, half_integer)
     * @param float  $x             Argument
     * @param float  $expected      Expected value
     * @param float  $tolerance     Tolerance for comparison
     * @param string $note          Test case description
     */
    public function testGammaRange(
        string $region,
        float $x,
        float $expected,
        float $tolerance,
        string $note
    ) {
        // When
        $result = Special::gamma($x);

        // Then
        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note in region '$region'"
        );
    }

    /**
     * @test Bessel J function in asymptotic region
     * @dataProvider dataProviderForBesselJAsymptotic
     * @param int    $n             Order
     * @param float  $x             Argument
     * @param float  $expected      Expected value
     * @param float  $tolerance     Tolerance for comparison
     * @param string $note          Test case description
     */
    public function testBesselJAsymptotic(
        int $n,
        float $x,
        float $expected,
        float $tolerance,
        string $note
    ) {
        // When
        $result = Special::besselJn($n, $x);

        // Then
        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note"
        );
    }

    /**
     * @test Bessel Y function in asymptotic region
     * @dataProvider dataProviderForBesselYAsymptotic
     * @param int    $n             Order
     * @param float  $x             Argument
     * @param float  $expected      Expected value
     * @param float  $tolerance     Tolerance for comparison
     * @param string $note          Test case description
     */
    public function testBesselYAsymptotic(
        int $n,
        float $x,
        float $expected,
        float $tolerance,
        string $note
    ) {
        // When
        $result = Special::besselYn($n, $x);

        // Then
        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note"
        );
    }

    /**
     * @test Modified Bessel I function in asymptotic region
     * @dataProvider dataProviderForBesselIAsymptotic
     * @param int    $n             Order
     * @param float  $x             Argument
     * @param float  $expected      Expected value
     * @param float  $tolerance     Tolerance for comparison
     * @param string $note          Test case description
     */
    public function testBesselIAsymptotic(
        int $n,
        float $x,
        float $expected,
        float $tolerance,
        string $note
    ) {
        // When
        $result = Special::besselIv($n, $x);

        // Then
        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note"
        );
    }

    /**
     * @test Modified Bessel K function in asymptotic region
     * @dataProvider dataProviderForBesselKAsymptotic
     * @param int    $n             Order
     * @param float  $x             Argument
     * @param float  $expected      Expected value
     * @param float  $tolerance     Tolerance for comparison
     * @param string $note          Test case description
     */
    public function testBesselKAsymptotic(
        int $n,
        float $x,
        float $expected,
        float $tolerance,
        string $note
    ) {
        // When
        $result = Special::besselKv($n, $x);

        // Then
        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note"
        );
    }

    /**
     * @test Incomplete Beta function near boundaries (x=0 and x=1)
     * @dataProvider dataProviderForIncompleteBetaBoundary
     * @param string $region        Region (near_zero, near_one, mid_range)
     * @param float  $x             Argument (0 < x < 1)
     * @param float  $a             Parameter a > 0
     * @param float  $b             Parameter b > 0
     * @param float  $expected      Expected value
     * @param float  $tolerance     Tolerance for comparison
     * @param string $note          Test case description
     */
    public function testIncompleteBetaBoundary(
        string $region,
        float $x,
        float $a,
        float $b,
        float $expected,
        float $tolerance,
        string $note
    ) {
        // When
        $result = Special::regularizedIncompleteBeta($x, $a, $b);

        // Then
        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note in region '$region'"
        );
    }

    /**
     * @test Error function (erf) across full range
     * @dataProvider dataProviderForErfBoundary
     * @param string $region        Region (small, large, negative)
     * @param float  $x             Argument
     * @param float  $expected      Expected value
     * @param float  $tolerance     Tolerance for comparison
     * @param string $note          Test case description
     */
    public function testErfBoundary(
        string $region,
        float $x,
        float $expected,
        float $tolerance,
        string $note
    ) {
        // When
        $result = Special::erf($x);

        // Then
        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note"
        );
    }

    /**
     * @test Complementary error function (erfc) across full range
     * @dataProvider dataProviderForErfcBoundary
     * @param string $region        Region (small, large)
     * @param float  $x             Argument
     * @param float  $expected      Expected value
     * @param float  $tolerance     Tolerance for comparison
     * @param string $note          Test case description
     */
    public function testErfcBoundary(
        string $region,
        float $x,
        float $expected,
        float $tolerance,
        string $note
    ) {
        // When
        $result = Special::erfc($x);

        // Then
        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note"
        );
    }

    /**
     * @test Lower incomplete gamma P(s,x) near boundaries
     * @dataProvider dataProviderForLowerIncompleteGamma
     * @param string $region        Region (small_x, transition)
     * @param float  $s             Parameter s > 0
     * @param float  $x             Argument x ≥ 0
     * @param float  $expected      Expected value
     * @param float  $tolerance     Tolerance for comparison
     * @param string $note          Test case description
     */
    public function testLowerIncompleteGamma(
        string $region,
        float $s,
        float $x,
        float $expected,
        float $tolerance,
        string $note
    ) {
        // When
        $result = Special::regularizedLowerIncompleteGamma($s, $x);

        // Then
        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note"
        );
    }

    /**
     * @test Upper incomplete gamma Q(s,x) = 1 - P(s,x) in upper tail
     * @dataProvider dataProviderForUpperIncompleteGamma
     * @param string $region        Region (large_x)
     * @param float  $s             Parameter s > 0
     * @param float  $x             Argument x ≥ 0
     * @param float  $expected      Expected value
     * @param float  $tolerance     Tolerance for comparison
     * @param string $note          Test case description
     */
    public function testUpperIncompleteGamma(
        string $region,
        float $s,
        float $x,
        float $expected,
        float $tolerance,
        string $note
    ) {
        // When
        // Upper incomplete gamma: Q(s,x) = 1 - P(s,x)
        $p = Special::regularizedLowerIncompleteGamma($s, $x);
        $result = 1.0 - $p;

        // Then
        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note"
        );
    }

    /**
     * @test Verify argument range coverage is comprehensive
     *
     * This meta-test checks that we have adequate coverage across
     * all critical regions for each function type.
     */
    public function testArgumentRangeCoverage()
    {
        // Count tests
        $total = count($this->dataProviderForGammaRange()) +
                count($this->dataProviderForBesselJAsymptotic()) +
                count($this->dataProviderForBesselYAsymptotic()) +
                count($this->dataProviderForBesselIAsymptotic()) +
                count($this->dataProviderForBesselKAsymptotic()) +
                count($this->dataProviderForIncompleteBetaBoundary()) +
                count($this->dataProviderForErfBoundary()) +
                count($this->dataProviderForErfcBoundary()) +
                count($this->dataProviderForLowerIncompleteGamma()) +
                count($this->dataProviderForUpperIncompleteGamma());

        $this->assertGreaterThanOrEqual(
            300,
            $total,
            'Should have at least 300 total argument range tests'
        );
    }

/**
 * AUTO-GENERATED DATA PROVIDERS
 *
 * Generated by: scripts/test_generators/argument_range_tests.py
 * Reference: mpmath 1.3.0, SciPy 1.11+
 * Precision: 50 decimal places
 *
 * DO NOT EDIT MANUALLY - Regenerate using:
 *   python scripts/test_generators/argument_range_tests.py
 */

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForGammaRange(): array
    {
        return [
            ['small', 1.00000000000000004e-10, 9.99999999942278481e+09, 1.00000000000000000e-05, 'Gamma at x=10^-10'],
            ['small', 1.00000000000000002e-08, 9.99999994227843434e+07, 1.00000000000000002e-08, 'Gamma at x=10^-8'],
            ['small', 9.99999999999999955e-07, 9.99999422785324161e+05, 1.00000000000000002e-08, 'Gamma at x=10^-6'],
            ['small', 1.00000000000000005e-04, 9.99942288323162393e+03, 1.00000000000000002e-08, 'Gamma at x=10^-4'],
            ['small', 1.00000000000000002e-02, 9.94325851191506018e+01, 1.00000000000000002e-08, 'Gamma at x=10^-2'],
            ['large', 2.00000000000000000e+01, 1.21645100408832000e+17, 1.00000000000000002e-08, 'Gamma at large x=20.0 (Stirling)'],
            ['large', 5.00000000000000000e+01, 6.08281864034267522e+62, 6.08281864034267522e+48, 'Gamma at large x=50.0 (Stirling)'],
            ['large', 1.00000000000000000e+02, 9.33262154439441533e+155, 9.33262154439441533e+141, 'Gamma at large x=100.0 (Stirling)'],
            ['large', 1.50000000000000000e+02, 3.80892263763056979e+260, 3.80892263763056979e+246, 'Gamma at large x=150.0 (Stirling)'],
            ['large', 1.70000000000000000e+02, 4.26906800900470511e+304, 4.00000000000000000e+291, 'Gamma at large x=170.0 (Stirling)'],
            ['integer', 1.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000004e-10, 'Gamma(1) = 0!'],
            ['half_integer', 1.50000000000000000e+00, 8.86226925452758052e-01, 1.00000000000000004e-10, 'Gamma(1+1/2)'],
            ['integer', 2.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000004e-10, 'Gamma(2) = 1!'],
            ['half_integer', 2.50000000000000000e+00, 1.32934038817913702e+00, 1.00000000000000004e-10, 'Gamma(2+1/2)'],
            ['integer', 3.00000000000000000e+00, 2.00000000000000000e+00, 1.00000000000000004e-10, 'Gamma(3) = 2!'],
            ['half_integer', 3.50000000000000000e+00, 3.32335097044784256e+00, 1.00000000000000004e-10, 'Gamma(3+1/2)'],
            ['integer', 4.00000000000000000e+00, 6.00000000000000000e+00, 1.00000000000000004e-10, 'Gamma(4) = 3!'],
            ['half_integer', 4.50000000000000000e+00, 1.16317283965674481e+01, 1.00000000000000004e-10, 'Gamma(4+1/2)'],
            ['integer', 5.00000000000000000e+00, 2.40000000000000000e+01, 1.00000000000000004e-10, 'Gamma(5) = 4!'],
            ['half_integer', 5.50000000000000000e+00, 5.23427777845535189e+01, 1.00000000000000004e-10, 'Gamma(5+1/2)'],
        ];
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForBesselJAsymptotic(): array
    {
        return [
            [0, 5.00000000000000000e+01, 5.58123276692518155e-02, 1.00000000000000002e-08, 'J_0(50.0) asymptotic'],
            [0, 1.00000000000000000e+02, 1.99858503042231218e-02, 1.00000000000000002e-08, 'J_0(100.0) asymptotic'],
            [0, 2.00000000000000000e+02, -1.54374399305650910e-02, 1.00000000000000002e-08, 'J_0(200.0) asymptotic'],
            [1, 5.00000000000000000e+01, -9.75118281251751429e-02, 1.00000000000000002e-08, 'J_1(50.0) asymptotic'],
            [1, 1.00000000000000000e+02, -7.71453520141121563e-02, 1.00000000000000002e-08, 'J_1(100.0) asymptotic'],
            [1, 2.00000000000000000e+02, -5.43045381823782231e-02, 1.00000000000000002e-08, 'J_1(200.0) asymptotic'],
            [2, 5.00000000000000000e+01, -5.97128007942588218e-02, 1.00000000000000002e-08, 'J_2(50.0) asymptotic'],
            [2, 1.00000000000000000e+02, -2.15287573445053643e-02, 1.00000000000000002e-08, 'J_2(100.0) asymptotic'],
            [2, 2.00000000000000000e+02, 1.48943945487413094e-02, 1.00000000000000002e-08, 'J_2(200.0) asymptotic'],
            [5, 5.00000000000000000e+01, -8.14002476965696442e-02, 1.00000000000000002e-08, 'J_5(50.0) asymptotic'],
            [5, 1.00000000000000000e+02, -7.41957369645139253e-02, 1.00000000000000002e-08, 'J_5(100.0) asymptotic'],
            [5, 2.00000000000000000e+02, -5.51326789440146764e-02, 1.00000000000000002e-08, 'J_5(200.0) asymptotic'],
            [10, 5.00000000000000000e+01, -1.13847849149469382e-01, 1.00000000000000002e-08, 'J_10(50.0) asymptotic'],
            [10, 1.00000000000000000e+02, -5.47321769354720128e-02, 1.00000000000000002e-08, 'J_10(100.0) asymptotic'],
            [10, 2.00000000000000000e+02, 1.53016881368016420e-03, 1.00000000000000002e-08, 'J_10(200.0) asymptotic'],
            [15, 5.00000000000000000e+01, -1.08225598975114551e-01, 1.00000000000000002e-08, 'J_15(50.0) asymptotic'],
            [15, 1.00000000000000000e+02, 1.51981212239273231e-02, 1.00000000000000002e-08, 'J_15(100.0) asymptotic'],
            [15, 2.00000000000000000e+02, 5.42098929424377068e-02, 1.00000000000000002e-08, 'J_15(200.0) asymptotic'],
        ];
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForBesselYAsymptotic(): array
    {
        return [
            [0, 5.00000000000000000e+01, -9.80649954700770765e-02, 9.99999999999999955e-08, 'Y_0(50.0) asymptotic'],
            [0, 1.00000000000000000e+02, -7.72443133650831532e-02, 9.99999999999999955e-08, 'Y_0(100.0) asymptotic'],
            [1, 5.00000000000000000e+01, -5.67956685620147686e-02, 9.99999999999999955e-08, 'Y_1(50.0) asymptotic'],
            [1, 1.00000000000000000e+02, -2.03723120027597925e-02, 9.99999999999999955e-08, 'Y_1(100.0) asymptotic'],
            [2, 5.00000000000000000e+01, 9.57931687275964949e-02, 9.99999999999999955e-08, 'Y_2(50.0) asymptotic'],
            [2, 1.00000000000000000e+02, 7.68368671250279495e-02, 9.99999999999999955e-08, 'Y_2(100.0) asymptotic'],
            [5, 5.00000000000000000e+01, -7.85484139130816494e-02, 9.99999999999999955e-08, 'Y_5(50.0) asymptotic'],
            [5, 1.00000000000000000e+02, -2.94801962816618954e-02, 9.99999999999999955e-08, 'Y_5(100.0) asymptotic'],
            [10, 5.00000000000000000e+01, 5.72389718205351325e-03, 9.99999999999999955e-08, 'Y_10(50.0) asymptotic'],
            [10, 1.00000000000000000e+02, 5.83315742364149264e-02, 9.99999999999999955e-08, 'Y_10(100.0) asymptotic'],
        ];
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForBesselIAsymptotic(): array
    {
        return [
            [0, 2.00000000000000000e+01, 4.35582825595535338e+07, 9.99999999999999955e-07, 'I_0(20.0) asymptotic'],
            [0, 5.00000000000000000e+01, 2.93255378384933618e+20, 9.99999999999999955e-07, 'I_0(50.0) asymptotic'],
            [1, 2.00000000000000000e+01, 4.24549733851277679e+07, 9.99999999999999955e-07, 'I_1(20.0) asymptotic'],
            [1, 5.00000000000000000e+01, 2.90307859010355692e+20, 2.90307859010355692e+05, 'I_1(50.0) asymptotic'],
            [2, 2.00000000000000000e+01, 3.93127852210407555e+07, 9.99999999999999955e-07, 'I_2(20.0) asymptotic'],
            [2, 5.00000000000000000e+01, 2.81643064024519410e+20, 2.81643064024519410e+05, 'I_2(50.0) asymptotic'],
            [5, 2.00000000000000000e+01, 2.30183922134136707e+07, 9.99999999999999955e-07, 'I_5(20.0) asymptotic'],
            [5, 5.00000000000000000e+01, 2.27854830791128187e+20, 2.27854830791128187e+05, 'I_5(50.0) asymptotic'],
        ];
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForBesselKAsymptotic(): array
    {
        return [
            [0, 2.00000000000000000e+01, 5.74123781533652479e-10, 1.00000000000000002e-08, 'K_0(20.0) asymptotic'],
            [0, 5.00000000000000000e+01, 3.41016774978949556e-23, 1.00000000000000002e-08, 'K_0(50.0) asymptotic'],
            [1, 2.00000000000000000e+01, 5.88305796955703838e-10, 1.00000000000000002e-08, 'K_1(20.0) asymptotic'],
            [1, 5.00000000000000000e+01, 3.44410222671755546e-23, 1.00000000000000002e-08, 'K_1(50.0) asymptotic'],
            [2, 2.00000000000000000e+01, 6.32954361229222811e-10, 1.00000000000000002e-08, 'K_2(20.0) asymptotic'],
            [2, 5.00000000000000000e+01, 3.54793183885819785e-23, 1.00000000000000002e-08, 'K_2(50.0) asymptotic'],
            [5, 2.00000000000000000e+01, 1.05386601399742332e-09, 1.00000000000000002e-08, 'K_5(20.0) asymptotic'],
            [5, 5.00000000000000000e+01, 4.36718225410098649e-23, 1.00000000000000002e-08, 'K_5(50.0) asymptotic'],
        ];
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForIncompleteBetaBoundary(): array
    {
        return [
            ['near_zero', 1.00000000000000004e-10, 5.00000000000000000e-01, 5.00000000000000000e-01, 6.36619772378191689e-06, 1.00000000000000004e-10, 'I_{1e-10}(0.5,0.5) near zero'],
            ['near_zero', 1.00000000000000002e-08, 5.00000000000000000e-01, 5.00000000000000000e-01, 6.36619773428614256e-05, 1.00000000000000004e-10, 'I_{1e-08}(0.5,0.5) near zero'],
            ['near_zero', 9.99999999999999955e-07, 5.00000000000000000e-01, 5.00000000000000000e-01, 6.36619878470924419e-04, 1.00000000000000004e-10, 'I_{1e-06}(0.5,0.5) near zero'],
            ['near_zero', 1.00000000000000005e-04, 5.00000000000000000e-01, 5.00000000000000000e-01, 6.36630383174614095e-03, 1.00000000000000004e-10, 'I_{0.0001}(0.5,0.5) near zero'],
            ['near_zero', 1.00000000000000002e-02, 5.00000000000000000e-01, 5.00000000000000000e-01, 6.37685608585198543e-02, 1.00000000000000004e-10, 'I_{0.01}(0.5,0.5) near zero'],
            ['near_zero', 1.00000000000000004e-10, 5.00000000000000000e-01, 1.00000000000000000e+00, 1.00000000000000008e-05, 1.00000000000000004e-10, 'I_{1e-10}(0.5,1.0) near zero'],
            ['near_zero', 1.00000000000000002e-08, 5.00000000000000000e-01, 1.00000000000000000e+00, 1.00000000000000005e-04, 1.00000000000000004e-10, 'I_{1e-08}(0.5,1.0) near zero'],
            ['near_zero', 9.99999999999999955e-07, 5.00000000000000000e-01, 1.00000000000000000e+00, 1.00000000000000002e-03, 1.00000000000000004e-10, 'I_{1e-06}(0.5,1.0) near zero'],
            ['near_zero', 1.00000000000000005e-04, 5.00000000000000000e-01, 1.00000000000000000e+00, 1.00000000000000002e-02, 1.00000000000000004e-10, 'I_{0.0001}(0.5,1.0) near zero'],
            ['near_zero', 1.00000000000000002e-02, 5.00000000000000000e-01, 1.00000000000000000e+00, 1.00000000000000006e-01, 1.00000000000000004e-10, 'I_{0.01}(0.5,1.0) near zero'],
            ['near_zero', 1.00000000000000004e-10, 5.00000000000000000e-01, 2.00000000000000000e+00, 1.49999999995000002e-05, 1.00000000000000004e-10, 'I_{1e-10}(0.5,2.0) near zero'],
            ['near_zero', 1.00000000000000002e-08, 5.00000000000000000e-01, 2.00000000000000000e+00, 1.49999999499999989e-04, 1.00000000000000004e-10, 'I_{1e-08}(0.5,2.0) near zero'],
            ['near_zero', 9.99999999999999955e-07, 5.00000000000000000e-01, 2.00000000000000000e+00, 1.49999949999999986e-03, 1.00000000000000004e-10, 'I_{1e-06}(0.5,2.0) near zero'],
            ['near_zero', 1.00000000000000005e-04, 5.00000000000000000e-01, 2.00000000000000000e+00, 1.49995000000000007e-02, 1.00000000000000004e-10, 'I_{0.0001}(0.5,2.0) near zero'],
            ['near_zero', 1.00000000000000002e-02, 5.00000000000000000e-01, 2.00000000000000000e+00, 1.49499999999999994e-01, 1.00000000000000004e-10, 'I_{0.01}(0.5,2.0) near zero'],
            ['near_zero', 1.00000000000000004e-10, 5.00000000000000000e-01, 5.00000000000000000e+00, 2.46093749967187504e-05, 1.00000000000000004e-10, 'I_{1e-10}(0.5,5.0) near zero'],
            ['near_zero', 1.00000000000000002e-08, 5.00000000000000000e-01, 5.00000000000000000e+00, 2.46093746718750056e-04, 1.00000000000000004e-10, 'I_{1e-08}(0.5,5.0) near zero'],
            ['near_zero', 9.99999999999999955e-07, 5.00000000000000000e-01, 5.00000000000000000e+00, 2.46093421875295294e-03, 1.00000000000000004e-10, 'I_{1e-06}(0.5,5.0) near zero'],
            ['near_zero', 1.00000000000000005e-04, 5.00000000000000000e-01, 5.00000000000000000e+00, 2.46060940452984371e-02, 1.00000000000000004e-10, 'I_{0.0001}(0.5,5.0) near zero'],
            ['near_zero', 1.00000000000000002e-02, 5.00000000000000000e-01, 5.00000000000000000e+00, 2.42841890898437496e-01, 1.00000000000000004e-10, 'I_{0.01}(0.5,5.0) near zero'],
            ['near_zero', 1.00000000000000004e-10, 1.00000000000000000e+00, 5.00000000000000000e-01, 5.00000000012500010e-11, 1.00000000000000004e-10, 'I_{1e-10}(1.0,0.5) near zero'],
            ['near_zero', 1.00000000000000002e-08, 1.00000000000000000e+00, 5.00000000000000000e-01, 5.00000001250000032e-09, 1.00000000000000004e-10, 'I_{1e-08}(1.0,0.5) near zero'],
            ['near_zero', 9.99999999999999955e-07, 1.00000000000000000e+00, 5.00000000000000000e-01, 5.00000125000062476e-07, 1.00000000000000004e-10, 'I_{1e-06}(1.0,0.5) near zero'],
            ['near_zero', 1.00000000000000005e-04, 1.00000000000000000e+00, 5.00000000000000000e-01, 5.00012500625039114e-05, 1.00000000000000004e-10, 'I_{0.0001}(1.0,0.5) near zero'],
            ['near_zero', 1.00000000000000002e-02, 1.00000000000000000e+00, 5.00000000000000000e-01, 5.01256289338004521e-03, 1.00000000000000004e-10, 'I_{0.01}(1.0,0.5) near zero'],
            ['near_zero', 1.00000000000000004e-10, 1.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000004e-10, 1.00000000000000004e-10, 'I_{1e-10}(1.0,1.0) near zero'],
            ['near_zero', 1.00000000000000002e-08, 1.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000002e-08, 1.00000000000000004e-10, 'I_{1e-08}(1.0,1.0) near zero'],
            ['near_zero', 9.99999999999999955e-07, 1.00000000000000000e+00, 1.00000000000000000e+00, 9.99999999999999955e-07, 1.00000000000000004e-10, 'I_{1e-06}(1.0,1.0) near zero'],
            ['near_zero', 1.00000000000000005e-04, 1.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000005e-04, 1.00000000000000004e-10, 'I_{0.0001}(1.0,1.0) near zero'],
            ['near_zero', 1.00000000000000002e-02, 1.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000002e-02, 1.00000000000000004e-10, 'I_{0.01}(1.0,1.0) near zero'],
            ['near_zero', 1.00000000000000004e-10, 1.00000000000000000e+00, 2.00000000000000000e+00, 1.99999999990000014e-10, 1.00000000000000004e-10, 'I_{1e-10}(1.0,2.0) near zero'],
            ['near_zero', 1.00000000000000002e-08, 1.00000000000000000e+00, 2.00000000000000000e+00, 1.99999999000000020e-08, 1.00000000000000004e-10, 'I_{1e-08}(1.0,2.0) near zero'],
            ['near_zero', 9.99999999999999955e-07, 1.00000000000000000e+00, 2.00000000000000000e+00, 1.99999900000000009e-06, 1.00000000000000004e-10, 'I_{1e-06}(1.0,2.0) near zero'],
            ['near_zero', 1.00000000000000005e-04, 1.00000000000000000e+00, 2.00000000000000000e+00, 1.99990000000000015e-04, 1.00000000000000004e-10, 'I_{0.0001}(1.0,2.0) near zero'],
            ['near_zero', 1.00000000000000002e-02, 1.00000000000000000e+00, 2.00000000000000000e+00, 1.99000000000000010e-02, 1.00000000000000004e-10, 'I_{0.01}(1.0,2.0) near zero'],
            ['near_zero', 1.00000000000000004e-10, 1.00000000000000000e+00, 5.00000000000000000e+00, 4.99999999899999996e-10, 1.00000000000000004e-10, 'I_{1e-10}(1.0,5.0) near zero'],
            ['near_zero', 1.00000000000000002e-08, 1.00000000000000000e+00, 5.00000000000000000e+00, 4.99999990000000140e-08, 1.00000000000000004e-10, 'I_{1e-08}(1.0,5.0) near zero'],
            ['near_zero', 9.99999999999999955e-07, 1.00000000000000000e+00, 5.00000000000000000e+00, 4.99999000000999978e-06, 1.00000000000000004e-10, 'I_{1e-06}(1.0,5.0) near zero'],
            ['near_zero', 1.00000000000000005e-04, 1.00000000000000000e+00, 5.00000000000000000e+00, 4.99900009999499988e-04, 1.00000000000000004e-10, 'I_{0.0001}(1.0,5.0) near zero'],
            ['near_zero', 1.00000000000000002e-02, 1.00000000000000000e+00, 5.00000000000000000e+00, 4.90099500999999979e-02, 1.00000000000000004e-10, 'I_{0.01}(1.0,5.0) near zero'],
            ['near_zero', 1.00000000000000004e-10, 2.00000000000000000e+00, 5.00000000000000000e-01, 3.75000000012499998e-21, 1.00000000000000004e-10, 'I_{1e-10}(2.0,0.5) near zero'],
            ['near_zero', 1.00000000000000002e-08, 2.00000000000000000e+00, 5.00000000000000000e-01, 3.75000001250000017e-17, 1.00000000000000004e-10, 'I_{1e-08}(2.0,0.5) near zero'],
            ['near_zero', 9.99999999999999955e-07, 2.00000000000000000e+00, 5.00000000000000000e-01, 3.75000125000070267e-13, 1.00000000000000004e-10, 'I_{1e-06}(2.0,0.5) near zero'],
            ['near_zero', 1.00000000000000005e-04, 2.00000000000000000e+00, 5.00000000000000000e-01, 3.75012500703171909e-09, 1.00000000000000004e-10, 'I_{0.0001}(2.0,0.5) near zero'],
            ['near_zero', 1.00000000000000002e-02, 2.00000000000000000e+00, 5.00000000000000000e-01, 3.76257078469454932e-05, 1.00000000000000004e-10, 'I_{0.01}(2.0,0.5) near zero'],
            ['near_zero', 1.00000000000000004e-10, 2.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000010e-20, 1.00000000000000004e-10, 'I_{1e-10}(2.0,1.0) near zero'],
            ['near_zero', 1.00000000000000002e-08, 2.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000010e-16, 1.00000000000000004e-10, 'I_{1e-08}(2.0,1.0) near zero'],
            ['near_zero', 9.99999999999999955e-07, 2.00000000000000000e+00, 1.00000000000000000e+00, 9.99999999999999980e-13, 1.00000000000000004e-10, 'I_{1e-06}(2.0,1.0) near zero'],
            ['near_zero', 1.00000000000000005e-04, 2.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000002e-08, 1.00000000000000004e-10, 'I_{0.0001}(2.0,1.0) near zero'],
            ['near_zero', 1.00000000000000002e-02, 2.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000005e-04, 1.00000000000000004e-10, 'I_{0.01}(2.0,1.0) near zero'],
            ['near_zero', 1.00000000000000004e-10, 2.00000000000000000e+00, 2.00000000000000000e+00, 2.99999999980000029e-20, 1.00000000000000004e-10, 'I_{1e-10}(2.0,2.0) near zero'],
            ['near_zero', 1.00000000000000002e-08, 2.00000000000000000e+00, 2.00000000000000000e+00, 2.99999998000000004e-16, 1.00000000000000004e-10, 'I_{1e-08}(2.0,2.0) near zero'],
            ['near_zero', 9.99999999999999955e-07, 2.00000000000000000e+00, 2.00000000000000000e+00, 2.99999799999999979e-12, 1.00000000000000004e-10, 'I_{1e-06}(2.0,2.0) near zero'],
            ['near_zero', 1.00000000000000005e-04, 2.00000000000000000e+00, 2.00000000000000000e+00, 2.99980000000000017e-08, 1.00000000000000004e-10, 'I_{0.0001}(2.0,2.0) near zero'],
            ['near_zero', 1.00000000000000002e-02, 2.00000000000000000e+00, 2.00000000000000000e+00, 2.98000000000000034e-04, 1.00000000000000004e-10, 'I_{0.01}(2.0,2.0) near zero'],
            ['near_zero', 1.00000000000000004e-10, 2.00000000000000000e+00, 5.00000000000000000e+00, 1.49999999960000020e-19, 1.00000000000000004e-10, 'I_{1e-10}(2.0,5.0) near zero'],
            ['near_zero', 1.00000000000000002e-08, 2.00000000000000000e+00, 5.00000000000000000e+00, 1.49999996000000052e-15, 1.00000000000000004e-10, 'I_{1e-08}(2.0,5.0) near zero'],
            ['near_zero', 9.99999999999999955e-07, 2.00000000000000000e+00, 5.00000000000000000e+00, 1.49999600000450000e-11, 1.00000000000000004e-10, 'I_{1e-06}(2.0,5.0) near zero'],
            ['near_zero', 1.00000000000000005e-04, 2.00000000000000000e+00, 5.00000000000000000e+00, 1.49960004499760016e-07, 1.00000000000000004e-10, 'I_{0.0001}(2.0,5.0) near zero'],
            ['near_zero', 1.00000000000000002e-02, 2.00000000000000000e+00, 5.00000000000000000e+00, 1.46044760500000010e-03, 1.00000000000000004e-10, 'I_{0.01}(2.0,5.0) near zero'],
            ['near_zero', 1.00000000000000004e-10, 5.00000000000000000e+00, 5.00000000000000000e-01, 2.46093750010253953e-51, 1.00000000000000004e-10, 'I_{1e-10}(5.0,0.5) near zero'],
            ['near_zero', 1.00000000000000002e-08, 5.00000000000000000e+00, 5.00000000000000000e-01, 2.46093751025390682e-41, 1.00000000000000004e-10, 'I_{1e-08}(5.0,0.5) near zero'],
            ['near_zero', 9.99999999999999955e-07, 5.00000000000000000e+00, 5.00000000000000000e-01, 2.46093852539128366e-31, 1.00000000000000004e-10, 'I_{1e-06}(5.0,0.5) near zero'],
            ['near_zero', 1.00000000000000005e-04, 5.00000000000000000e+00, 5.00000000000000000e-01, 2.46104004565477806e-21, 1.00000000000000004e-10, 'I_{0.0001}(5.0,0.5) near zero'],
            ['near_zero', 1.00000000000000002e-02, 5.00000000000000000e+00, 5.00000000000000000e-01, 2.47125780863954475e-11, 1.00000000000000004e-10, 'I_{0.01}(5.0,0.5) near zero'],
            ['near_zero', 1.00000000000000004e-10, 5.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000013e-50, 1.00000000000000004e-10, 'I_{1e-10}(5.0,1.0) near zero'],
            ['near_zero', 1.00000000000000002e-08, 5.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000013e-40, 1.00000000000000004e-10, 'I_{1e-08}(5.0,1.0) near zero'],
            ['near_zero', 9.99999999999999955e-07, 5.00000000000000000e+00, 1.00000000000000000e+00, 9.99999999999999733e-31, 1.00000000000000004e-10, 'I_{1e-06}(5.0,1.0) near zero'],
            ['near_zero', 1.00000000000000005e-04, 5.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000025e-20, 1.00000000000000004e-10, 'I_{0.0001}(5.0,1.0) near zero'],
            ['near_zero', 1.00000000000000002e-02, 5.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000017e-10, 1.00000000000000004e-10, 'I_{0.01}(5.0,1.0) near zero'],
            ['near_zero', 1.00000000000000004e-10, 5.00000000000000000e+00, 2.00000000000000000e+00, 5.99999999950000119e-50, 1.00000000000000004e-10, 'I_{1e-10}(5.0,2.0) near zero'],
            ['near_zero', 1.00000000000000002e-08, 5.00000000000000000e+00, 2.00000000000000000e+00, 5.99999995000000091e-40, 1.00000000000000004e-10, 'I_{1e-08}(5.0,2.0) near zero'],
            ['near_zero', 9.99999999999999955e-07, 5.00000000000000000e+00, 2.00000000000000000e+00, 5.99999499999999877e-30, 1.00000000000000004e-10, 'I_{1e-06}(5.0,2.0) near zero'],
            ['near_zero', 1.00000000000000005e-04, 5.00000000000000000e+00, 2.00000000000000000e+00, 5.99950000000000152e-20, 1.00000000000000004e-10, 'I_{0.0001}(5.0,2.0) near zero'],
            ['near_zero', 1.00000000000000002e-02, 5.00000000000000000e+00, 2.00000000000000000e+00, 5.95000000000000013e-10, 1.00000000000000004e-10, 'I_{0.01}(5.0,2.0) near zero'],
            ['near_zero', 1.00000000000000004e-10, 5.00000000000000000e+00, 5.00000000000000000e+00, 1.25999999958000018e-48, 1.00000000000000004e-10, 'I_{1e-10}(5.0,5.0) near zero'],
            ['near_zero', 1.00000000000000002e-08, 5.00000000000000000e+00, 5.00000000000000000e+00, 1.25999995800000075e-38, 1.00000000000000004e-10, 'I_{1e-08}(5.0,5.0) near zero'],
            ['near_zero', 9.99999999999999955e-07, 5.00000000000000000e+00, 5.00000000000000000e+00, 1.25999580000539976e-28, 1.00000000000000004e-10, 'I_{1e-06}(5.0,5.0) near zero'],
            ['near_zero', 1.00000000000000005e-04, 5.00000000000000000e+00, 5.00000000000000000e+00, 1.25958005399685042e-18, 1.00000000000000004e-10, 'I_{0.0001}(5.0,5.0) near zero'],
            ['near_zero', 1.00000000000000002e-02, 5.00000000000000000e+00, 5.00000000000000000e+00, 1.21853685700000018e-08, 1.00000000000000004e-10, 'I_{0.01}(5.0,5.0) near zero'],
            ['near_one', 9.89999999999999991e-01, 5.00000000000000000e-01, 5.00000000000000000e-01, 9.36231439141480104e-01, 1.00000000000000004e-10, 'I_{0.99}(0.5,0.5) near one'],
            ['near_one', 9.98999999999999999e-01, 5.00000000000000000e-01, 5.00000000000000000e-01, 9.79864958366622463e-01, 1.00000000000000004e-10, 'I_{0.999}(0.5,0.5) near one'],
            ['near_one', 9.99900000000000011e-01, 5.00000000000000000e-01, 5.00000000000000000e-01, 9.93633696168254255e-01, 1.00000000000000004e-10, 'I_{0.9999}(0.5,0.5) near one'],
            ['near_one', 9.99999989999999950e-01, 5.00000000000000000e-01, 5.00000000000000000e-01, 9.99936338022497151e-01, 1.00000000000000004e-10, 'I_{0.99999999}(0.5,0.5) near one'],
            ['near_one', 9.89999999999999991e-01, 5.00000000000000000e-01, 1.00000000000000000e+00, 9.94987437106619965e-01, 1.00000000000000004e-10, 'I_{0.99}(0.5,1.0) near one'],
            ['near_one', 9.98999999999999999e-01, 5.00000000000000000e-01, 1.00000000000000000e+00, 9.99499874937460953e-01, 1.00000000000000004e-10, 'I_{0.999}(0.5,1.0) near one'],
            ['near_one', 9.99900000000000011e-01, 5.00000000000000000e-01, 1.00000000000000000e+00, 9.99949998749937508e-01, 1.00000000000000004e-10, 'I_{0.9999}(0.5,1.0) near one'],
            ['near_one', 9.99999989999999950e-01, 5.00000000000000000e-01, 1.00000000000000000e+00, 9.99999994999999919e-01, 1.00000000000000004e-10, 'I_{0.99999999}(0.5,1.0) near one'],
            ['near_one', 9.89999999999999991e-01, 5.00000000000000000e-01, 2.00000000000000000e+00, 9.99962374292153067e-01, 1.00000000000000004e-10, 'I_{0.99}(0.5,2.0) near one'],
            ['near_one', 9.98999999999999999e-01, 5.00000000000000000e-01, 2.00000000000000000e+00, 9.99999624874929660e-01, 1.00000000000000004e-10, 'I_{0.999}(0.5,2.0) near one'],
            ['near_one', 9.99900000000000011e-01, 5.00000000000000000e-01, 2.00000000000000000e+00, 9.99999996249875012e-01, 1.00000000000000004e-10, 'I_{0.9999}(0.5,2.0) near one'],
            ['near_one', 9.99999989999999950e-01, 5.00000000000000000e-01, 2.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000004e-10, 'I_{0.99999999}(0.5,2.0) near one'],
            ['near_one', 9.89999999999999991e-01, 5.00000000000000000e-01, 5.00000000000000000e+00, 9.99999999975287435e-01, 1.00000000000000004e-10, 'I_{0.99}(0.5,5.0) near one'],
            ['near_one', 9.98999999999999999e-01, 5.00000000000000000e-01, 5.00000000000000000e+00, 9.99999999999999778e-01, 1.00000000000000004e-10, 'I_{0.999}(0.5,5.0) near one'],
            ['near_one', 9.99900000000000011e-01, 5.00000000000000000e-01, 5.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000004e-10, 'I_{0.9999}(0.5,5.0) near one'],
            ['near_one', 9.99999989999999950e-01, 5.00000000000000000e-01, 5.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000004e-10, 'I_{0.99999999}(0.5,5.0) near one'],
            ['near_one', 9.89999999999999991e-01, 1.00000000000000000e+00, 5.00000000000000000e-01, 8.99999999999999911e-01, 1.00000000000000004e-10, 'I_{0.99}(1.0,0.5) near one'],
            ['near_one', 9.98999999999999999e-01, 1.00000000000000000e+00, 5.00000000000000000e-01, 9.68377223398316223e-01, 1.00000000000000004e-10, 'I_{0.999}(1.0,0.5) near one'],
            ['near_one', 9.99900000000000011e-01, 1.00000000000000000e+00, 5.00000000000000000e-01, 9.90000000000000546e-01, 1.00000000000000004e-10, 'I_{0.9999}(1.0,0.5) near one'],
            ['near_one', 9.99999989999999950e-01, 1.00000000000000000e+00, 5.00000000000000000e-01, 9.99899999999748768e-01, 1.00000000000000004e-10, 'I_{0.99999999}(1.0,0.5) near one'],
            ['near_one', 9.89999999999999991e-01, 1.00000000000000000e+00, 1.00000000000000000e+00, 9.89999999999999991e-01, 1.00000000000000004e-10, 'I_{0.99}(1.0,1.0) near one'],
            ['near_one', 9.98999999999999999e-01, 1.00000000000000000e+00, 1.00000000000000000e+00, 9.98999999999999999e-01, 1.00000000000000004e-10, 'I_{0.999}(1.0,1.0) near one'],
            ['near_one', 9.99900000000000011e-01, 1.00000000000000000e+00, 1.00000000000000000e+00, 9.99900000000000011e-01, 1.00000000000000004e-10, 'I_{0.9999}(1.0,1.0) near one'],
            ['near_one', 9.99999989999999950e-01, 1.00000000000000000e+00, 1.00000000000000000e+00, 9.99999989999999950e-01, 1.00000000000000004e-10, 'I_{0.99999999}(1.0,1.0) near one'],
            ['near_one', 9.89999999999999991e-01, 1.00000000000000000e+00, 2.00000000000000000e+00, 9.99900000000000011e-01, 1.00000000000000004e-10, 'I_{0.99}(1.0,2.0) near one'],
            ['near_one', 9.98999999999999999e-01, 1.00000000000000000e+00, 2.00000000000000000e+00, 9.99998999999999971e-01, 1.00000000000000004e-10, 'I_{0.999}(1.0,2.0) near one'],
            ['near_one', 9.99900000000000011e-01, 1.00000000000000000e+00, 2.00000000000000000e+00, 9.99999989999999950e-01, 1.00000000000000004e-10, 'I_{0.9999}(1.0,2.0) near one'],
            ['near_one', 9.99999989999999950e-01, 1.00000000000000000e+00, 2.00000000000000000e+00, 9.99999999999999889e-01, 1.00000000000000004e-10, 'I_{0.99999999}(1.0,2.0) near one'],
            ['near_one', 9.89999999999999991e-01, 1.00000000000000000e+00, 5.00000000000000000e+00, 9.99999999899999992e-01, 1.00000000000000004e-10, 'I_{0.99}(1.0,5.0) near one'],
            ['near_one', 9.98999999999999999e-01, 1.00000000000000000e+00, 5.00000000000000000e+00, 9.99999999999999001e-01, 1.00000000000000004e-10, 'I_{0.999}(1.0,5.0) near one'],
            ['near_one', 9.99900000000000011e-01, 1.00000000000000000e+00, 5.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000004e-10, 'I_{0.9999}(1.0,5.0) near one'],
            ['near_one', 9.99999989999999950e-01, 1.00000000000000000e+00, 5.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000004e-10, 'I_{0.99999999}(1.0,5.0) near one'],
            ['near_one', 9.89999999999999991e-01, 2.00000000000000000e+00, 5.00000000000000000e-01, 8.50499999999999923e-01, 1.00000000000000004e-10, 'I_{0.99}(2.0,0.5) near one'],
            ['near_one', 9.98999999999999999e-01, 2.00000000000000000e+00, 5.00000000000000000e-01, 9.52581646485775146e-01, 1.00000000000000004e-10, 'I_{0.999}(2.0,0.5) near one'],
            ['near_one', 9.99900000000000011e-01, 2.00000000000000000e+00, 5.00000000000000000e-01, 9.85000500000000834e-01, 1.00000000000000004e-10, 'I_{0.9999}(2.0,0.5) near one'],
            ['near_one', 9.99999989999999950e-01, 2.00000000000000000e+00, 5.00000000000000000e-01, 9.99850000000123140e-01, 1.00000000000000004e-10, 'I_{0.99999999}(2.0,0.5) near one'],
            ['near_one', 9.89999999999999991e-01, 2.00000000000000000e+00, 1.00000000000000000e+00, 9.80099999999999971e-01, 1.00000000000000004e-10, 'I_{0.99}(2.0,1.0) near one'],
            ['near_one', 9.98999999999999999e-01, 2.00000000000000000e+00, 1.00000000000000000e+00, 9.98001000000000027e-01, 1.00000000000000004e-10, 'I_{0.999}(2.0,1.0) near one'],
            ['near_one', 9.99900000000000011e-01, 2.00000000000000000e+00, 1.00000000000000000e+00, 9.99800010000000072e-01, 1.00000000000000004e-10, 'I_{0.9999}(2.0,1.0) near one'],
            ['near_one', 9.99999989999999950e-01, 2.00000000000000000e+00, 1.00000000000000000e+00, 9.99999980000000011e-01, 1.00000000000000004e-10, 'I_{0.99999999}(2.0,1.0) near one'],
            ['near_one', 9.89999999999999991e-01, 2.00000000000000000e+00, 2.00000000000000000e+00, 9.99701999999999980e-01, 1.00000000000000004e-10, 'I_{0.99}(2.0,2.0) near one'],
            ['near_one', 9.98999999999999999e-01, 2.00000000000000000e+00, 2.00000000000000000e+00, 9.99997001999999968e-01, 1.00000000000000004e-10, 'I_{0.999}(2.0,2.0) near one'],
            ['near_one', 9.99900000000000011e-01, 2.00000000000000000e+00, 2.00000000000000000e+00, 9.99999970002000027e-01, 1.00000000000000004e-10, 'I_{0.9999}(2.0,2.0) near one'],
            ['near_one', 9.99999989999999950e-01, 2.00000000000000000e+00, 2.00000000000000000e+00, 9.99999999999999667e-01, 1.00000000000000004e-10, 'I_{0.99999999}(2.0,2.0) near one'],
            ['near_one', 9.89999999999999991e-01, 2.00000000000000000e+00, 5.00000000000000000e+00, 9.99999999404999951e-01, 1.00000000000000004e-10, 'I_{0.99}(2.0,5.0) near one'],
            ['near_one', 9.98999999999999999e-01, 2.00000000000000000e+00, 5.00000000000000000e+00, 9.99999999999994005e-01, 1.00000000000000004e-10, 'I_{0.999}(2.0,5.0) near one'],
            ['near_one', 9.99900000000000011e-01, 2.00000000000000000e+00, 5.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000004e-10, 'I_{0.9999}(2.0,5.0) near one'],
            ['near_one', 9.99999989999999950e-01, 2.00000000000000000e+00, 5.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000004e-10, 'I_{0.99999999}(2.0,5.0) near one'],
            ['near_one', 9.89999999999999991e-01, 5.00000000000000000e+00, 5.00000000000000000e-01, 7.57158109101562449e-01, 1.00000000000000004e-10, 'I_{0.99}(5.0,0.5) near one'],
            ['near_one', 9.98999999999999999e-01, 5.00000000000000000e+00, 5.00000000000000000e-01, 9.22281992100966730e-01, 1.00000000000000004e-10, 'I_{0.999}(5.0,0.5) near one'],
            ['near_one', 9.99900000000000011e-01, 5.00000000000000000e+00, 5.00000000000000000e-01, 9.75393905954702878e-01, 1.00000000000000004e-10, 'I_{0.9999}(5.0,0.5) near one'],
            ['near_one', 9.99999989999999950e-01, 5.00000000000000000e+00, 5.00000000000000000e-01, 9.99753906252662916e-01, 1.00000000000000004e-10, 'I_{0.99999999}(5.0,0.5) near one'],
            ['near_one', 9.89999999999999991e-01, 5.00000000000000000e+00, 1.00000000000000000e+00, 9.50990049899999912e-01, 1.00000000000000004e-10, 'I_{0.99}(5.0,1.0) near one'],
            ['near_one', 9.98999999999999999e-01, 5.00000000000000000e+00, 1.00000000000000000e+00, 9.95009990004999012e-01, 1.00000000000000004e-10, 'I_{0.999}(5.0,1.0) near one'],
            ['near_one', 9.99900000000000011e-01, 5.00000000000000000e+00, 1.00000000000000000e+00, 9.99500099990000557e-01, 1.00000000000000004e-10, 'I_{0.9999}(5.0,1.0) near one'],
            ['near_one', 9.99999989999999950e-01, 5.00000000000000000e+00, 1.00000000000000000e+00, 9.99999950000000748e-01, 1.00000000000000004e-10, 'I_{0.99999999}(5.0,1.0) near one'],
            ['near_one', 9.89999999999999991e-01, 5.00000000000000000e+00, 2.00000000000000000e+00, 9.98539552395000030e-01, 1.00000000000000004e-10, 'I_{0.99}(5.0,2.0) near one'],
            ['near_one', 9.98999999999999999e-01, 5.00000000000000000e+00, 2.00000000000000000e+00, 9.99985039955023969e-01, 1.00000000000000004e-10, 'I_{0.999}(5.0,2.0) near one'],
            ['near_one', 9.99900000000000011e-01, 5.00000000000000000e+00, 2.00000000000000000e+00, 9.99999850039995475e-01, 1.00000000000000004e-10, 'I_{0.9999}(5.0,2.0) near one'],
            ['near_one', 9.99999989999999950e-01, 5.00000000000000000e+00, 2.00000000000000000e+00, 9.99999999999998446e-01, 1.00000000000000004e-10, 'I_{0.99999999}(5.0,2.0) near one'],
            ['near_one', 9.89999999999999991e-01, 5.00000000000000000e+00, 5.00000000000000000e+00, 9.99999987814631397e-01, 1.00000000000000004e-10, 'I_{0.99}(5.0,5.0) near one'],
            ['near_one', 9.98999999999999999e-01, 5.00000000000000000e+00, 5.00000000000000000e+00, 9.99999999999874434e-01, 1.00000000000000004e-10, 'I_{0.999}(5.0,5.0) near one'],
            ['near_one', 9.99900000000000011e-01, 5.00000000000000000e+00, 5.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000004e-10, 'I_{0.9999}(5.0,5.0) near one'],
            ['near_one', 9.99999989999999950e-01, 5.00000000000000000e+00, 5.00000000000000000e+00, 1.00000000000000000e+00, 1.00000000000000004e-10, 'I_{0.99999999}(5.0,5.0) near one'],
            ['mid_range', 2.50000000000000000e-01, 5.00000000000000000e-01, 5.00000000000000000e-01, 3.33333333333333315e-01, 9.99999999999999980e-13, 'I_{0.25}(0.5,0.5) mid-range'],
            ['mid_range', 5.00000000000000000e-01, 5.00000000000000000e-01, 5.00000000000000000e-01, 5.00000000000000000e-01, 9.99999999999999980e-13, 'I_{0.5}(0.5,0.5) mid-range'],
            ['mid_range', 7.50000000000000000e-01, 5.00000000000000000e-01, 5.00000000000000000e-01, 6.66666666666666630e-01, 9.99999999999999980e-13, 'I_{0.75}(0.5,0.5) mid-range'],
            ['mid_range', 2.50000000000000000e-01, 5.00000000000000000e-01, 1.00000000000000000e+00, 5.00000000000000000e-01, 9.99999999999999980e-13, 'I_{0.25}(0.5,1.0) mid-range'],
            ['mid_range', 5.00000000000000000e-01, 5.00000000000000000e-01, 1.00000000000000000e+00, 7.07106781186547573e-01, 9.99999999999999980e-13, 'I_{0.5}(0.5,1.0) mid-range'],
            ['mid_range', 7.50000000000000000e-01, 5.00000000000000000e-01, 1.00000000000000000e+00, 8.66025403784438597e-01, 9.99999999999999980e-13, 'I_{0.75}(0.5,1.0) mid-range'],
            ['mid_range', 2.50000000000000000e-01, 5.00000000000000000e-01, 2.00000000000000000e+00, 6.87500000000000000e-01, 9.99999999999999980e-13, 'I_{0.25}(0.5,2.0) mid-range'],
            ['mid_range', 5.00000000000000000e-01, 5.00000000000000000e-01, 2.00000000000000000e+00, 8.83883476483184438e-01, 9.99999999999999980e-13, 'I_{0.5}(0.5,2.0) mid-range'],
            ['mid_range', 7.50000000000000000e-01, 5.00000000000000000e-01, 2.00000000000000000e+00, 9.74278579257493504e-01, 9.99999999999999980e-13, 'I_{0.75}(0.5,2.0) mid-range'],
            ['mid_range', 2.50000000000000000e-01, 1.00000000000000000e+00, 5.00000000000000000e-01, 1.33974596215561348e-01, 9.99999999999999980e-13, 'I_{0.25}(1.0,0.5) mid-range'],
            ['mid_range', 5.00000000000000000e-01, 1.00000000000000000e+00, 5.00000000000000000e-01, 2.92893218813452483e-01, 9.99999999999999980e-13, 'I_{0.5}(1.0,0.5) mid-range'],
            ['mid_range', 7.50000000000000000e-01, 1.00000000000000000e+00, 5.00000000000000000e-01, 5.00000000000000000e-01, 9.99999999999999980e-13, 'I_{0.75}(1.0,0.5) mid-range'],
            ['mid_range', 2.50000000000000000e-01, 1.00000000000000000e+00, 1.00000000000000000e+00, 2.50000000000000000e-01, 9.99999999999999980e-13, 'I_{0.25}(1.0,1.0) mid-range'],
            ['mid_range', 5.00000000000000000e-01, 1.00000000000000000e+00, 1.00000000000000000e+00, 5.00000000000000000e-01, 9.99999999999999980e-13, 'I_{0.5}(1.0,1.0) mid-range'],
            ['mid_range', 7.50000000000000000e-01, 1.00000000000000000e+00, 1.00000000000000000e+00, 7.50000000000000000e-01, 9.99999999999999980e-13, 'I_{0.75}(1.0,1.0) mid-range'],
            ['mid_range', 2.50000000000000000e-01, 1.00000000000000000e+00, 2.00000000000000000e+00, 4.37500000000000000e-01, 9.99999999999999980e-13, 'I_{0.25}(1.0,2.0) mid-range'],
            ['mid_range', 5.00000000000000000e-01, 1.00000000000000000e+00, 2.00000000000000000e+00, 7.50000000000000000e-01, 9.99999999999999980e-13, 'I_{0.5}(1.0,2.0) mid-range'],
            ['mid_range', 7.50000000000000000e-01, 1.00000000000000000e+00, 2.00000000000000000e+00, 9.37500000000000000e-01, 9.99999999999999980e-13, 'I_{0.75}(1.0,2.0) mid-range'],
            ['mid_range', 2.50000000000000000e-01, 2.00000000000000000e+00, 5.00000000000000000e-01, 2.57214207425065233e-02, 9.99999999999999980e-13, 'I_{0.25}(2.0,0.5) mid-range'],
            ['mid_range', 5.00000000000000000e-01, 2.00000000000000000e+00, 5.00000000000000000e-01, 1.16116523516815590e-01, 9.99999999999999980e-13, 'I_{0.5}(2.0,0.5) mid-range'],
            ['mid_range', 7.50000000000000000e-01, 2.00000000000000000e+00, 5.00000000000000000e-01, 3.12500000000000000e-01, 9.99999999999999980e-13, 'I_{0.75}(2.0,0.5) mid-range'],
            ['mid_range', 2.50000000000000000e-01, 2.00000000000000000e+00, 1.00000000000000000e+00, 6.25000000000000000e-02, 9.99999999999999980e-13, 'I_{0.25}(2.0,1.0) mid-range'],
            ['mid_range', 5.00000000000000000e-01, 2.00000000000000000e+00, 1.00000000000000000e+00, 2.50000000000000000e-01, 9.99999999999999980e-13, 'I_{0.5}(2.0,1.0) mid-range'],
            ['mid_range', 7.50000000000000000e-01, 2.00000000000000000e+00, 1.00000000000000000e+00, 5.62500000000000000e-01, 9.99999999999999980e-13, 'I_{0.75}(2.0,1.0) mid-range'],
            ['mid_range', 2.50000000000000000e-01, 2.00000000000000000e+00, 2.00000000000000000e+00, 1.56250000000000000e-01, 9.99999999999999980e-13, 'I_{0.25}(2.0,2.0) mid-range'],
            ['mid_range', 5.00000000000000000e-01, 2.00000000000000000e+00, 2.00000000000000000e+00, 5.00000000000000000e-01, 9.99999999999999980e-13, 'I_{0.5}(2.0,2.0) mid-range'],
            ['mid_range', 7.50000000000000000e-01, 2.00000000000000000e+00, 2.00000000000000000e+00, 8.43750000000000000e-01, 9.99999999999999980e-13, 'I_{0.75}(2.0,2.0) mid-range'],
        ];
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForErfBoundary(): array
    {
        return [
            ['small', 1.00000000000000004e-10, 1.12837916709551258e-10, 9.99999999999999980e-13, 'erf(1e-10) small argument'],
            ['small', 1.00000000000000002e-08, 1.12837916709551262e-08, 9.99999999999999980e-13, 'erf(1e-08) small argument'],
            ['small', 9.99999999999999955e-07, 1.12837916709513640e-06, 9.99999999999999980e-13, 'erf(1e-06) small argument'],
            ['small', 1.00000000000000005e-04, 1.12837916333424873e-04, 9.99999999999999980e-13, 'erf(0.0001) small argument'],
            ['small', 1.00000000000000002e-02, 1.12834155558496178e-02, 9.99999999999999980e-13, 'erf(0.01) small argument'],
            ['large', 5.00000000000000000e+00, 9.99999999998462563e-01, 1.00000000000000004e-10, 'erf(5.0) → 1'],
            ['large', 1.00000000000000000e+01, 1.00000000000000000e+00, 1.00000000000000004e-10, 'erf(10.0) → 1'],
            ['large', 1.50000000000000000e+01, 1.00000000000000000e+00, 1.00000000000000004e-10, 'erf(15.0) → 1'],
            ['large', 2.00000000000000000e+01, 1.00000000000000000e+00, 1.00000000000000004e-10, 'erf(20.0) → 1'],
            ['large', 2.50000000000000000e+01, 1.00000000000000000e+00, 1.00000000000000004e-10, 'erf(25.0) → 1'],
            ['negative', -5.00000000000000000e+00, -9.99999999998462563e-01, 9.99999999999999955e-08, 'erf(-5.0) = -erf(5.0)'],
            ['negative', -2.00000000000000000e+00, -9.95322265018952712e-01, 9.99999999999999955e-08, 'erf(-2.0) = -erf(2.0)'],
            ['negative', -1.00000000000000000e+00, -8.42700792949714894e-01, 9.99999999999999955e-08, 'erf(-1.0) = -erf(1.0)'],
            ['negative', -5.00000000000000000e-01, -5.20499877813046519e-01, 9.99999999999999955e-08, 'erf(-0.5) = -erf(0.5)'],
        ];
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForErfcBoundary(): array
    {
        return [
            ['small', 1.00000000000000004e-10, 9.99999999887162039e-01, 9.99999999999999980e-13, 'erfc(1e-10) small argument'],
            ['small', 1.00000000000000002e-08, 9.99999988716208321e-01, 9.99999999999999980e-13, 'erfc(1e-08) small argument'],
            ['small', 9.99999999999999955e-07, 9.99998871620832896e-01, 9.99999999999999980e-13, 'erfc(1e-06) small argument'],
            ['small', 1.00000000000000005e-04, 9.99887162083666570e-01, 9.99999999999999980e-13, 'erfc(0.0001) small argument'],
            ['small', 1.00000000000000002e-02, 9.88716584444150337e-01, 9.99999999999999980e-13, 'erfc(0.01) small argument'],
            ['large', 5.00000000000000000e+00, 1.53745979442803494e-12, 1.00000000000000008e-15, 'erfc(5.0) → 0 (asymptotic)'],
            ['large', 1.00000000000000000e+01, 2.08848758376254488e-45, 1.00000000000000008e-15, 'erfc(10.0) → 0 (asymptotic)'],
            ['large', 1.50000000000000000e+01, 7.21299417245120682e-100, 1.00000000000000008e-15, 'erfc(15.0) → 0 (asymptotic)'],
            ['large', 2.00000000000000000e+01, 5.39586561160790118e-176, 1.00000000000000008e-15, 'erfc(20.0) → 0 (asymptotic)'],
            ['large', 2.50000000000000000e+01, 8.30017257119652279e-274, 1.00000000000000008e-15, 'erfc(25.0) → 0 (asymptotic)'],
        ];
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForLowerIncompleteGamma(): array
    {
        return [
            ['small_x', 5.00000000000000000e-01, 1.00000000000000002e-08, 1.12837916333424873e-04, 9.99999999999999955e-08, 'P(0.5,1e-08) near zero'],
            ['small_x', 5.00000000000000000e-01, 9.99999999999999955e-07, 1.12837879096923627e-03, 9.99999999999999955e-08, 'P(0.5,1e-06) near zero'],
            ['small_x', 5.00000000000000000e-01, 1.00000000000000005e-04, 1.12834155558496178e-02, 9.99999999999999955e-08, 'P(0.5,0.0001) near zero'],
            ['small_x', 5.00000000000000000e-01, 1.00000000000000002e-02, 1.12462916018284897e-01, 9.99999999999999955e-08, 'P(0.5,0.01) near zero'],
            ['small_x', 5.00000000000000000e-01, 1.00000000000000006e-01, 3.45279153981423004e-01, 9.99999999999999955e-08, 'P(0.5,0.1) near zero'],
            ['small_x', 1.00000000000000000e+00, 1.00000000000000002e-08, 9.99999995000000102e-09, 1.00000000000000006e-09, 'P(1.0,1e-08) near zero'],
            ['small_x', 1.00000000000000000e+00, 9.99999999999999955e-07, 9.99999500000166701e-07, 1.00000000000000006e-09, 'P(1.0,1e-06) near zero'],
            ['small_x', 1.00000000000000000e+00, 1.00000000000000005e-04, 9.99950001666625082e-05, 1.00000000000000006e-09, 'P(1.0,0.0001) near zero'],
            ['small_x', 1.00000000000000000e+00, 1.00000000000000002e-02, 9.95016625083194710e-03, 1.00000000000000006e-09, 'P(1.0,0.01) near zero'],
            ['small_x', 1.00000000000000000e+00, 1.00000000000000006e-01, 9.51625819640404269e-02, 1.00000000000000006e-09, 'P(1.0,0.1) near zero'],
            ['small_x', 2.00000000000000000e+00, 1.00000000000000002e-08, 4.99999996666666694e-17, 1.00000000000000006e-09, 'P(2.0,1e-08) near zero'],
            ['small_x', 2.00000000000000000e+00, 9.99999999999999955e-07, 4.99999666666791571e-13, 1.00000000000000006e-09, 'P(2.0,1e-06) near zero'],
            ['small_x', 2.00000000000000000e+00, 1.00000000000000005e-04, 4.99966667916633353e-09, 1.00000000000000006e-09, 'P(2.0,0.0001) near zero'],
            ['small_x', 2.00000000000000000e+00, 1.00000000000000002e-02, 4.96679133402658894e-05, 1.00000000000000006e-09, 'P(2.0,0.01) near zero'],
            ['small_x', 2.00000000000000000e+00, 1.00000000000000006e-01, 4.67884016044447029e-03, 1.00000000000000006e-09, 'P(2.0,0.1) near zero'],
            ['small_x', 5.00000000000000000e+00, 1.00000000000000002e-08, 8.33333326388888937e-43, 1.00000000000000006e-09, 'P(5.0,1e-08) near zero'],
            ['small_x', 5.00000000000000000e+00, 9.99999999999999955e-07, 8.33332638889186272e-33, 1.00000000000000006e-09, 'P(5.0,1e-06) near zero'],
            ['small_x', 5.00000000000000000e+00, 1.00000000000000005e-04, 8.33263891864992710e-23, 1.00000000000000006e-09, 'P(5.0,0.0001) near zero'],
            ['small_x', 5.00000000000000000e+00, 1.00000000000000002e-02, 8.26418564180649862e-13, 1.00000000000000006e-09, 'P(5.0,0.01) near zero'],
            ['small_x', 5.00000000000000000e+00, 1.00000000000000006e-01, 7.66780168618931066e-08, 1.00000000000000006e-09, 'P(5.0,0.1) near zero'],
            ['small_x', 1.00000000000000000e+01, 1.00000000000000002e-08, 2.75573189734648131e-87, 1.00000000000000006e-09, 'P(10.0,1e-08) near zero'],
            ['small_x', 1.00000000000000000e+01, 9.99999999999999955e-07, 2.75572941718889739e-67, 1.00000000000000006e-09, 'P(10.0,1e-06) near zero'],
            ['small_x', 1.00000000000000000e+01, 1.00000000000000005e-04, 2.75548141279659909e-47, 1.00000000000000006e-09, 'P(10.0,0.0001) near zero'],
            ['small_x', 1.00000000000000000e+01, 1.00000000000000002e-02, 2.73079428369624661e-27, 1.00000000000000006e-09, 'P(10.0,0.01) near zero'],
            ['small_x', 1.00000000000000000e+01, 1.00000000000000006e-01, 2.51634780677031630e-17, 1.00000000000000006e-09, 'P(10.0,0.1) near zero'],
            ['transition', 1.00000000000000000e+00, 5.00000000000000000e-01, 3.93469340287366576e-01, 9.99999999999999939e-12, 'P(1.0,0.5) x≈s transition'],
            ['transition', 1.00000000000000000e+00, 1.00000000000000000e+00, 6.32120558828557666e-01, 9.99999999999999939e-12, 'P(1.0,1.0) x≈s transition'],
            ['transition', 1.00000000000000000e+00, 2.00000000000000000e+00, 8.64664716763387298e-01, 9.99999999999999939e-12, 'P(1.0,2.0) x≈s transition'],
            ['transition', 2.00000000000000000e+00, 1.00000000000000000e+00, 2.64241117657115332e-01, 9.99999999999999939e-12, 'P(2.0,1.0) x≈s transition'],
            ['transition', 2.00000000000000000e+00, 2.00000000000000000e+00, 5.93994150290161893e-01, 9.99999999999999939e-12, 'P(2.0,2.0) x≈s transition'],
            ['transition', 2.00000000000000000e+00, 4.00000000000000000e+00, 9.08421805556329121e-01, 9.99999999999999939e-12, 'P(2.0,4.0) x≈s transition'],
            ['transition', 5.00000000000000000e+00, 2.50000000000000000e+00, 1.08821981085848757e-01, 9.99999999999999939e-12, 'P(5.0,2.5) x≈s transition'],
            ['transition', 5.00000000000000000e+00, 5.00000000000000000e+00, 5.59506714934787541e-01, 9.99999999999999939e-12, 'P(5.0,5.0) x≈s transition'],
            ['transition', 5.00000000000000000e+00, 1.00000000000000000e+01, 9.70747311923038980e-01, 9.99999999999999939e-12, 'P(5.0,10.0) x≈s transition'],
            ['transition', 1.00000000000000000e+01, 5.00000000000000000e+00, 3.18280573062048114e-02, 9.99999999999999939e-12, 'P(10.0,5.0) x≈s transition'],
            ['transition', 1.00000000000000000e+01, 1.00000000000000000e+01, 5.42070285528147844e-01, 9.99999999999999939e-12, 'P(10.0,10.0) x≈s transition'],
            ['transition', 1.00000000000000000e+01, 2.00000000000000000e+01, 9.95004587691692421e-01, 9.99999999999999939e-12, 'P(10.0,20.0) x≈s transition'],
        ];
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForUpperIncompleteGamma(): array
    {
        return [
            ['large_x', 5.00000000000000000e-01, 1.00000000000000000e+01, 7.74421643104408415e-06, 9.99999999999999955e-08, 'Q(0.5,10.0) upper tail'],
            ['large_x', 5.00000000000000000e-01, 5.00000000000000000e+01, 1.52397060483210509e-23, 9.99999999999999955e-08, 'Q(0.5,50.0) upper tail'],
            ['large_x', 5.00000000000000000e-01, 1.00000000000000000e+02, 2.08848758376254488e-45, 9.99999999999999955e-08, 'Q(0.5,100.0) upper tail'],
            ['large_x', 1.00000000000000000e+00, 1.00000000000000000e+01, 4.53999297624848542e-05, 1.00000000000000006e-09, 'Q(1.0,10.0) upper tail'],
            ['large_x', 1.00000000000000000e+00, 5.00000000000000000e+01, 1.92874984796391782e-22, 9.99999999999999955e-08, 'Q(1.0,50.0) upper tail'],
            ['large_x', 1.00000000000000000e+00, 1.00000000000000000e+02, 3.72007597602083612e-44, 9.99999999999999955e-08, 'Q(1.0,100.0) upper tail'],
            ['large_x', 2.00000000000000000e+00, 1.00000000000000000e+01, 4.99399227387333335e-04, 1.00000000000000006e-09, 'Q(2.0,10.0) upper tail'],
            ['large_x', 2.00000000000000000e+00, 5.00000000000000000e+01, 9.83662422461598058e-21, 9.99999999999999955e-08, 'Q(2.0,50.0) upper tail'],
            ['large_x', 2.00000000000000000e+00, 1.00000000000000000e+02, 3.75727673578104424e-42, 9.99999999999999955e-08, 'Q(2.0,100.0) upper tail'],
            ['large_x', 5.00000000000000000e+00, 1.00000000000000000e+01, 2.92526880769610718e-02, 1.00000000000000006e-09, 'Q(5.0,10.0) upper tail'],
            ['large_x', 5.00000000000000000e+00, 5.00000000000000000e+01, 5.44970198292052954e-17, 9.99999999999999955e-08, 'Q(5.0,50.0) upper tail'],
            ['large_x', 5.00000000000000000e+00, 1.00000000000000000e+02, 1.61393053369773050e-37, 9.99999999999999955e-08, 'Q(5.0,100.0) upper tail'],
        ];
    }
}
