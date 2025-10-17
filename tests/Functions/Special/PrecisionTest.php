<?php

namespace MathPHP\Tests\Functions\Special;

use MathPHP\Functions\Special;
use MathPHP\Exception;

/**
 * Precision Degradation Tests (P1.1)
 *
 * Tests numerical precision of recurrence relations and iterative algorithms
 * to ensure stability and accuracy. These tests validate that:
 *
 * - Miller's backward recurrence maintains acceptable precision for Bessel functions
 * - Recurrence relations for polynomials don't accumulate excessive errors
 * - Modified Bessel functions remain stable near underflow/overflow
 *
 * Test data generated from mpmath (50 decimal places) via:
 * python scripts/test_generators/precision_tests.py
 */
class PrecisionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test Bessel J_n precision with Miller's backward recurrence
     * @dataProvider dataProviderForBesselJPrecision
     * @param int    $n                Order of Bessel function
     * @param float  $x                Argument
     * @param float  $expected         Expected value (high precision)
     * @param int    $min_sig_digits   Minimum required significant digits
     * @param string $note             Test case description
     */
    public function testBesselJPrecision(
        int $n,
        float $x,
        float $expected,
        int $min_sig_digits,
        string $note
    ) {
        // When
        $result = Special::besselJn($n, $x);

        // Then - tolerance based on required significant digits
        // For very small expected values, use absolute tolerance
        if (abs($expected) < 1e-15) {
            $tolerance = 1e-15;
        } else {
            $tolerance = abs($expected) * pow(10, -$min_sig_digits);
        }

        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note - Expected $min_sig_digits significant digits"
        );
    }

    /**
     * @test Bessel Y_n precision with recurrence relations
     * @dataProvider dataProviderForBesselYPrecision
     * @param int    $n                Order of Bessel function
     * @param float  $x                Argument
     * @param float  $expected         Expected value (high precision)
     * @param int    $min_sig_digits   Minimum required significant digits
     * @param string $note             Test case description
     */
    public function testBesselYPrecision(
        int $n,
        float $x,
        float $expected,
        int $min_sig_digits,
        string $note
    ) {
        // When
        $result = Special::besselYn($n, $x);

        // Then
        if (abs($expected) < 1e-15) {
            $tolerance = 1e-15;
        } else {
            $tolerance = abs($expected) * pow(10, -$min_sig_digits);
        }

        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note"
        );
    }

    /**
     * @test Modified Bessel I_n precision
     * @dataProvider dataProviderForBesselIPrecision
     * @param int    $n                Order
     * @param float  $x                Argument
     * @param float  $expected         Expected value
     * @param int    $min_sig_digits   Minimum required significant digits
     * @param string $note             Test case description
     */
    public function testBesselIPrecision(
        int $n,
        float $x,
        float $expected,
        int $min_sig_digits,
        string $note
    ) {
        // When
        $result = Special::besselIv($n, $x);

        // Then
        if (abs($expected) < 1e-15) {
            $tolerance = 1e-15;
        } else {
            $tolerance = abs($expected) * pow(10, -$min_sig_digits);
        }

        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note"
        );
    }

    /**
     * @test Modified Bessel K_n precision (Temme's algorithm)
     * @dataProvider dataProviderForBesselKPrecision
     * @param int    $n                Order
     * @param float  $x                Argument
     * @param float  $expected         Expected value
     * @param int    $min_sig_digits   Minimum required significant digits
     * @param string $note             Test case description
     */
    public function testBesselKPrecision(
        int $n,
        float $x,
        float $expected,
        int $min_sig_digits,
        string $note
    ) {
        // When
        $result = Special::besselKv($n, $x);

        // Then
        if (abs($expected) < 1e-15) {
            $tolerance = 1e-15;
        } else {
            $tolerance = abs($expected) * pow(10, -$min_sig_digits);
        }

        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note"
        );
    }

    /**
     * @test Legendre polynomial P_n(x) recurrence precision
     * @dataProvider dataProviderForLegendrePrecision
     * @param int    $n                Degree
     * @param float  $x                Argument
     * @param float  $expected         Expected value
     * @param int    $min_sig_digits   Minimum required significant digits
     * @param string $note             Test case description
     */
    public function testLegendrePrecision(
        int $n,
        float $x,
        float $expected,
        int $min_sig_digits,
        string $note
    ) {
        // When
        $result = Special::legendreP($n, $x);

        // Then
        if (abs($expected) < 1e-15) {
            $tolerance = 1e-15;
        } else {
            $tolerance = abs($expected) * pow(10, -$min_sig_digits);
        }

        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note"
        );
    }

    /**
     * @test Chebyshev T_n(x) polynomial recurrence precision
     * @dataProvider dataProviderForChebyshevTPrecision
     * @param int    $n                Degree
     * @param float  $x                Argument
     * @param float  $expected         Expected value
     * @param int    $min_sig_digits   Minimum required significant digits
     * @param string $note             Test case description
     */
    public function testChebyshevTPrecision(
        int $n,
        float $x,
        float $expected,
        int $min_sig_digits,
        string $note
    ) {
        // When
        $result = Special::chebyshevT($n, $x);

        // Then
        if (abs($expected) < 1e-15) {
            $tolerance = 1e-15;
        } else {
            $tolerance = abs($expected) * pow(10, -$min_sig_digits);
        }

        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note"
        );
    }

    /**
     * @test Hermite polynomial H_n(x) recurrence precision
     * @dataProvider dataProviderForHermitePrecision
     * @param int    $n                Degree
     * @param float  $x                Argument
     * @param float  $expected         Expected value
     * @param int    $min_sig_digits   Minimum required significant digits
     * @param string $note             Test case description
     */
    public function testHermitePrecision(
        int $n,
        float $x,
        float $expected,
        int $min_sig_digits,
        string $note
    ) {
        // When
        $result = Special::hermiteH($n, $x);

        // Then
        if (abs($expected) < 1e-15) {
            $tolerance = 1e-15;
        } else {
            $tolerance = abs($expected) * pow(10, -$min_sig_digits);
        }

        $this->assertEqualsWithDelta(
            $expected,
            $result,
            $tolerance,
            "Failed: $note"
        );
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForBesselJPrecision(): array
    {
        return [
            [20, 5.00000000000000000e+00, 2.77033005212894164e-11, 8, 'Miller backward recurrence n=20, x=5.0'],
            [20, 1.00000000000000000e+01, 1.15133692478133978e-05, 8, 'Miller backward recurrence n=20, x=10.0'],
            [20, 1.50000000000000000e+01, 7.36023407922348563e-03, 8, 'Miller backward recurrence n=20, x=15.0'],
            [20, 2.00000000000000000e+01, 1.64747773775326539e-01, 8, 'Miller backward recurrence n=20, x=20.0'],
            [30, 5.00000000000000000e+00, 2.67117727825079892e-21, 8, 'Miller backward recurrence n=30, x=5.0'],
            [30, 1.00000000000000000e+01, 1.55109607825746702e-12, 8, 'Miller backward recurrence n=30, x=10.0'],
            [30, 1.50000000000000000e+01, 1.03747102010787185e-07, 8, 'Miller backward recurrence n=30, x=15.0'],
            [30, 2.00000000000000000e+01, 1.24015363603543268e-04, 8, 'Miller backward recurrence n=30, x=20.0'],
            [40, 5.00000000000000000e+00, 8.70224161738881778e-33, 6, 'Miller backward recurrence n=40, x=5.0'],
            [40, 1.00000000000000000e+01, 6.03089531234690670e-21, 6, 'Miller backward recurrence n=40, x=10.0'],
            [40, 1.50000000000000000e+01, 3.05353523048900689e-14, 6, 'Miller backward recurrence n=40, x=15.0'],
            [40, 2.00000000000000000e+01, 9.90238941374468657e-10, 6, 'Miller backward recurrence n=40, x=20.0'],
            [50, 5.00000000000000000e+00, 2.29424761595254022e-45, 6, 'Miller backward recurrence n=50, x=5.0'],
            [50, 1.00000000000000000e+01, 1.78451360787159534e-30, 6, 'Miller backward recurrence n=50, x=10.0'],
            [50, 1.50000000000000000e+01, 6.10605194953387523e-22, 6, 'Miller backward recurrence n=50, x=15.0'],
            [50, 2.00000000000000000e+01, 4.45103928470068130e-16, 6, 'Miller backward recurrence n=50, x=20.0'],
            [0, 2.38077730211881500e+00, 1.25462245853593616e-02, 6, 'Near zero #1 for J_0'],
            [0, 5.46487732918344715e+00, -1.88678428089306197e-02, 6, 'Near zero #2 for J_0'],
            [1, 3.79338891050543747e+00, 1.55067028982734199e-02, 6, 'Near zero #1 for J_1'],
            [1, 6.94543080311746230e+00, -2.11438641601949390e-02, 6, 'Near zero #2 for J_1'],
            [2, 5.08426607882227621e+00, 1.75253847735609737e-02, 6, 'Near zero #1 for J_2'],
            [2, 8.33307169899586597e+00, -2.29323619629837570e-02, 6, 'Near zero #2 for J_2'],
            [3, 6.31636027696474400e+00, 1.91160354197679183e-02, 6, 'Near zero #1 for J_3'],
            [3, 9.66341289868185349e+00, -2.44330447839857454e-02, 6, 'Near zero #2 for J_3'],
            [4, 7.51245901015876605e+00, 2.04527244536269436e-02, 6, 'Near zero #1 for J_4'],
            [4, 1.09540623936161730e+01, -2.57398980334926850e-02, 6, 'Near zero #2 for J_4'],
            [5, 8.68376897780035328e+00, 2.16179524643777994e-02, 6, 'Near zero #1 for J_5'],
            [5, 1.22152181554922752e+01, -2.69055522229722024e-02, 6, 'Near zero #2 for J_5'],
            [6, 9.83674842897550938e+00, 2.26581179205866783e-02, 6, 'Near zero #1 for J_6'],
            [6, 1.34533972688358041e+01, -2.79628164393847106e-02, 6, 'Near zero #2 for J_6'],
            [7, 1.09755063190526325e+01, 2.36022270199108194e-02, 6, 'Near zero #1 for J_7'],
            [7, 1.46730560397430398e+01, -2.89336978286549272e-02, 6, 'Near zero #2 for J_7'],
            [8, 1.21028413413646074e+01, 2.44697739960133276e-02, 6, 'Near zero #1 for J_8'],
            [8, 1.58773964489788320e+01, -2.98337671841895115e-02, 6, 'Near zero #2 for J_8'],
            [9, 1.32207574726609778e+01, 2.52745705950752353e-02, 6, 'Near zero #1 for J_9'],
            [9, 1.70688081786642378e+01, -3.06744987353610270e-02, 6, 'Near zero #2 for J_9'],
            [10, 8.00000000000000000e+00, 6.07670267742511580e-02, 7, 'Transition region x≈n: J_10(8.0)'],
            [10, 1.00000000000000000e+01, 2.07486106633358869e-01, 7, 'Transition region x≈n: J_10(10.0)'],
            [10, 1.20000000000000000e+01, 3.00476035271269315e-01, 7, 'Transition region x≈n: J_10(12.0)'],
            [20, 1.60000000000000000e+01, 1.73287462275919964e-02, 7, 'Transition region x≈n: J_20(16.0)'],
            [20, 2.00000000000000000e+01, 1.64747773775326539e-01, 7, 'Transition region x≈n: J_20(20.0)'],
            [20, 2.40000000000000000e+01, 1.61912651664495283e-01, 7, 'Transition region x≈n: J_20(24.0)'],
            [30, 2.40000000000000000e+01, 5.62568084269514032e-03, 7, 'Transition region x≈n: J_30(24.0)'],
            [30, 3.00000000000000000e+01, 1.43935850010307204e-01, 7, 'Transition region x≈n: J_30(30.0)'],
            [30, 3.60000000000000000e+01, 9.79712262555703858e-03, 7, 'Transition region x≈n: J_30(36.0)'],
            [40, 3.20000000000000000e+01, 1.92897125559317231e-03, 7, 'Transition region x≈n: J_40(32.0)'],
            [40, 4.00000000000000000e+01, 1.30780545285166722e-01, 7, 'Transition region x≈n: J_40(40.0)'],
            [40, 4.80000000000000000e+01, -1.02712672336094685e-01, 7, 'Transition region x≈n: J_40(48.0)'],
        ];
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForBesselYPrecision(): array
    {
        return [
            [5, 1.00000000000000000e+00, -2.60405866625812223e+02, 7, 'Bessel Y_5(1.0) recurrence'],
            [5, 2.00000000000000000e+00, -9.93598912848197457e+00, 7, 'Bessel Y_5(2.0) recurrence'],
            [5, 5.00000000000000000e+00, -4.53694822491101879e-01, 7, 'Bessel Y_5(5.0) recurrence'],
            [5, 1.00000000000000000e+01, 1.35403047689362316e-01, 7, 'Bessel Y_5(10.0) recurrence'],
            [5, 2.00000000000000000e+01, -1.00035767889532431e-01, 7, 'Bessel Y_5(20.0) recurrence'],
            [10, 1.00000000000000000e+00, -1.21618014278689191e+08, 7, 'Bessel Y_10(1.0) recurrence'],
            [10, 2.00000000000000000e+00, -1.29184542208039289e+05, 7, 'Bessel Y_10(2.0) recurrence'],
            [10, 5.00000000000000000e+00, -2.51291100956100983e+01, 7, 'Bessel Y_10(5.0) recurrence'],
            [10, 1.00000000000000000e+01, -3.59814152183402736e-01, 7, 'Bessel Y_10(10.0) recurrence'],
            [10, 2.00000000000000000e+01, -4.38946535156583967e-02, 7, 'Bessel Y_10(20.0) recurrence'],
            [15, 1.00000000000000000e+00, -9.25697327575220750e+14, 6, 'Bessel Y_15(1.0) recurrence'],
            [15, 2.00000000000000000e+00, -2.98102364652545280e+10, 6, 'Bessel Y_15(2.0) recurrence'],
            [15, 5.00000000000000000e+00, -4.69404956379457508e+04, 6, 'Bessel Y_15(5.0) recurrence'],
            [15, 1.00000000000000000e+01, -6.36474587693912941e+00, 6, 'Bessel Y_15(10.0) recurrence'],
            [15, 2.00000000000000000e+01, 2.18266614207541315e-01, 6, 'Bessel Y_15(20.0) recurrence'],
            [20, 1.00000000000000000e+00, -4.11397031483550487e+22, 6, 'Bessel Y_20(1.0) recurrence'],
            [20, 2.00000000000000000e+00, -4.08165138899836640e+16, 6, 'Bessel Y_20(2.0) recurrence'],
            [20, 5.00000000000000000e+00, -5.93396529691432118e+08, 6, 'Bessel Y_20(5.0) recurrence'],
            [20, 1.00000000000000000e+01, -1.59748384826962592e+03, 6, 'Bessel Y_20(10.0) recurrence'],
            [20, 2.00000000000000000e+01, -2.85489458600203472e-01, 6, 'Bessel Y_20(20.0) recurrence'],
            [0, 1.00000000000000002e-02, -3.00545563708364583e+00, 5, 'Y_0 near singularity at x=0.01'],
            [0, 1.00000000000000006e-01, -1.53423865135036674e+00, 7, 'Y_0 near singularity at x=0.1'],
            [0, 5.00000000000000000e-01, -4.44518733506706565e-01, 7, 'Y_0 near singularity at x=0.5'],
            [0, 1.00000000000000000e+00, 8.82569642156769557e-02, 7, 'Y_0 near singularity at x=1.0'],
            [1, 1.00000000000000002e-02, -6.36785962820606528e+01, 5, 'Y_1 near singularity at x=0.01'],
            [1, 1.00000000000000006e-01, -6.45895109470202655e+00, 7, 'Y_1 near singularity at x=0.1'],
            [1, 5.00000000000000000e-01, -1.47147239267024310e+00, 7, 'Y_1 near singularity at x=0.5'],
            [1, 1.00000000000000000e+00, -7.81212821300288685e-01, 7, 'Y_1 near singularity at x=1.0'],
            [2, 1.00000000000000002e-02, -1.27327138007750473e+04, 5, 'Y_2 near singularity at x=0.01'],
            [2, 1.00000000000000006e-01, -1.27644783242690153e+02, 7, 'Y_2 near singularity at x=0.1'],
            [2, 5.00000000000000000e-01, -5.44137083717426595e+00, 7, 'Y_2 near singularity at x=0.5'],
            [2, 1.00000000000000000e+00, -1.65068260681625434e+00, 7, 'Y_2 near singularity at x=1.0'],
            [3, 1.00000000000000002e-02, -5.09302184171373677e+06, 5, 'Y_3 near singularity at x=0.01'],
            [3, 1.00000000000000006e-01, -5.09933237861290399e+03, 7, 'Y_3 near singularity at x=0.1'],
            [3, 5.00000000000000000e-01, -4.20594943047238843e+01, 7, 'Y_3 near singularity at x=0.5'],
            [3, 1.00000000000000000e+00, -5.82151760596472911e+00, 7, 'Y_3 near singularity at x=1.0'],
            [4, 1.00000000000000002e-02, -3.05580037231444120e+09, 5, 'Y_4 near singularity at x=0.01'],
            [4, 1.00000000000000006e-01, -3.05832297933531518e+05, 7, 'Y_4 near singularity at x=0.1'],
            [4, 5.00000000000000000e-01, -4.99272560819512307e+02, 7, 'Y_4 near singularity at x=0.5'],
            [4, 1.00000000000000000e+00, -3.32784230289721208e+01, 7, 'Y_4 near singularity at x=1.0'],
        ];
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForBesselIPrecision(): array
    {
        return [
            [5, 5.00000000000000000e-01, 8.22317131310926462e-06, 7, 'Modified Bessel I_5(0.5)'],
            [5, 1.00000000000000000e+00, 2.71463155956971891e-04, 7, 'Modified Bessel I_5(1.0)'],
            [5, 2.00000000000000000e+00, 9.82567932313170231e-03, 7, 'Modified Bessel I_5(2.0)'],
            [5, 5.00000000000000000e+00, 2.15797454732254668e+00, 5, 'Modified Bessel I_5(5.0)'],
            [10, 5.00000000000000000e-01, 2.64304192588127937e-13, 7, 'Modified Bessel I_10(0.5)'],
            [10, 1.00000000000000000e+00, 2.75294803983687372e-10, 7, 'Modified Bessel I_10(1.0)'],
            [10, 2.00000000000000000e+00, 3.01696387935068448e-07, 7, 'Modified Bessel I_10(2.0)'],
            [10, 5.00000000000000000e+00, 4.58004441917605164e-03, 5, 'Modified Bessel I_10(5.0)'],
            [15, 5.00000000000000000e-01, 7.14984763416345279e-22, 7, 'Modified Bessel I_15(0.5)'],
            [15, 1.00000000000000000e+00, 2.37046305128074811e-17, 7, 'Modified Bessel I_15(1.0)'],
            [15, 2.00000000000000000e+00, 8.13943253123737947e-13, 7, 'Modified Bessel I_15(2.0)'],
            [15, 5.00000000000000000e+00, 1.04797767541791883e-06, 5, 'Modified Bessel I_15(5.0)'],
            [20, 5.00000000000000000e-01, 3.74945384807901944e-31, 7, 'Modified Bessel I_20(0.5)'],
            [20, 1.00000000000000000e+00, 3.96683598581902017e-25, 7, 'Modified Bessel I_20(1.0)'],
            [20, 2.00000000000000000e+00, 4.31056057610954842e-19, 7, 'Modified Bessel I_20(2.0)'],
            [20, 5.00000000000000000e+00, 5.02423935797180593e-11, 5, 'Modified Bessel I_20(5.0)'],
        ];
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForBesselKPrecision(): array
    {
        return [
            [5, 5.00000000000000000e-01, 1.20979794760963941e+04, 7, 'Modified Bessel K_5(0.5) - Temme algorithm'],
            [5, 1.00000000000000000e+00, 3.60960589601240713e+02, 7, 'Modified Bessel K_5(1.0) - Temme algorithm'],
            [5, 2.00000000000000000e+00, 9.43104910059646784e+00, 7, 'Modified Bessel K_5(2.0) - Temme algorithm'],
            [5, 5.00000000000000000e+00, 3.27062737120318581e-02, 7, 'Modified Bessel K_5(5.0) - Temme algorithm'],
            [5, 1.00000000000000000e+01, 5.75418499853122813e-05, 7, 'Modified Bessel K_5(10.0) - Temme algorithm'],
            [10, 5.00000000000000000e-01, 1.88937569319900269e+11, 7, 'Modified Bessel K_10(0.5) - Temme algorithm'],
            [10, 1.00000000000000000e+00, 1.80713289901029468e+08, 7, 'Modified Bessel K_10(1.0) - Temme algorithm'],
            [10, 2.00000000000000000e+00, 1.62482403979559138e+05, 7, 'Modified Bessel K_10(2.0) - Temme algorithm'],
            [10, 5.00000000000000000e+00, 9.75856282917781037e+00, 7, 'Modified Bessel K_10(5.0) - Temme algorithm'],
            [10, 1.00000000000000000e+01, 1.61425530039067001e-03, 7, 'Modified Bessel K_10(10.0) - Temme algorithm'],
            [15, 5.00000000000000000e-01, 4.65950459559723909e+19, 5, 'Modified Bessel K_15(0.5) - Temme algorithm'],
            [15, 1.00000000000000000e+00, 1.40306680115503900e+15, 7, 'Modified Bessel K_15(1.0) - Temme algorithm'],
            [15, 2.00000000000000000e+00, 4.05921333155087662e+10, 7, 'Modified Bessel K_15(2.0) - Temme algorithm'],
            [15, 5.00000000000000000e+00, 3.01697663006732037e+04, 7, 'Modified Bessel K_15(5.0) - Temme algorithm'],
            [15, 1.00000000000000000e+01, 2.65656384855239680e-01, 7, 'Modified Bessel K_15(10.0) - Temme algorithm'],
            [20, 5.00000000000000000e-01, 6.66554987441715582e+28, 5, 'Modified Bessel K_20(0.5) - Temme algorithm'],
            [20, 1.00000000000000000e+00, 6.29436936042453517e+22, 7, 'Modified Bessel K_20(1.0) - Temme algorithm'],
            [20, 2.00000000000000000e+00, 5.77085685270024080e+16, 7, 'Modified Bessel K_20(2.0) - Temme algorithm'],
            [20, 5.00000000000000000e+00, 4.82700052062148452e+08, 7, 'Modified Bessel K_20(5.0) - Temme algorithm'],
            [20, 1.00000000000000000e+01, 1.78744278207705491e+02, 7, 'Modified Bessel K_20(10.0) - Temme algorithm'],
        ];
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForLegendrePrecision(): array
    {
        return [
            [10, -9.00000000000000022e-01, -2.63145617855859548e-01, 8, 'P_10(-0.9) three-term recurrence'],
            [10, -5.00000000000000000e-01, -1.88228607177734375e-01, 8, 'P_10(-0.5) three-term recurrence'],
            [10, 0.00000000000000000e+00, -2.46093750000000000e-01, 8, 'P_10(0.0) three-term recurrence'],
            [10, 5.00000000000000000e-01, -1.88228607177734375e-01, 8, 'P_10(0.5) three-term recurrence'],
            [10, 9.00000000000000022e-01, -2.63145617855859548e-01, 8, 'P_10(0.9) three-term recurrence'],
            [20, -9.00000000000000022e-01, -1.49308235309848464e-01, 8, 'P_20(-0.9) three-term recurrence'],
            [20, -5.00000000000000000e-01, -4.83583810673735570e-02, 8, 'P_20(-0.5) three-term recurrence'],
            [20, 0.00000000000000000e+00, 1.76197052001953125e-01, 8, 'P_20(0.0) three-term recurrence'],
            [20, 5.00000000000000000e-01, -4.83583810673735570e-02, 8, 'P_20(0.5) three-term recurrence'],
            [20, 9.00000000000000022e-01, -1.49308235309848464e-01, 8, 'P_20(0.9) three-term recurrence'],
            [30, -9.00000000000000022e-01, 2.01812397959063416e-01, 8, 'P_30(-0.9) three-term recurrence'],
            [30, -5.00000000000000000e-01, 1.49848814900610805e-01, 8, 'P_30(-0.5) three-term recurrence'],
            [30, 0.00000000000000000e+00, -1.44464448094367981e-01, 8, 'P_30(0.0) three-term recurrence'],
            [30, 5.00000000000000000e-01, 1.49848814900610805e-01, 8, 'P_30(0.5) three-term recurrence'],
            [30, 9.00000000000000022e-01, 2.01812397959063416e-01, 8, 'P_30(0.9) three-term recurrence'],
            [40, -9.00000000000000022e-01, 3.69874184218762156e-02, 8, 'P_40(-0.9) three-term recurrence'],
            [40, -5.00000000000000000e-01, -9.54294352326154754e-02, 8, 'P_40(-0.5) three-term recurrence'],
            [40, 0.00000000000000000e+00, 1.25370687619579257e-01, 8, 'P_40(0.0) three-term recurrence'],
            [40, 5.00000000000000000e-01, -9.54294352326154754e-02, 8, 'P_40(0.5) three-term recurrence'],
            [40, 9.00000000000000022e-01, 3.69874184218762156e-02, 8, 'P_40(0.9) three-term recurrence'],
            [50, -9.00000000000000022e-01, -1.70037659943836794e-01, 8, 'P_50(-0.9) three-term recurrence'],
            [50, -5.00000000000000000e-01, -3.10590992396098213e-02, 8, 'P_50(-0.5) three-term recurrence'],
            [50, 0.00000000000000000e+00, -1.12275172659217048e-01, 8, 'P_50(0.0) three-term recurrence'],
            [50, 5.00000000000000000e-01, -3.10590992396098213e-02, 8, 'P_50(0.5) three-term recurrence'],
            [50, 9.00000000000000022e-01, -1.70037659943836794e-01, 8, 'P_50(0.9) three-term recurrence'],
        ];
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForChebyshevTPrecision(): array
    {
        return [
            [10, -9.00000000000000022e-01, -2.00747468800000495e-01, 10, 'T_10(-0.9) stable recurrence'],
            [10, -5.00000000000000000e-01, -5.00000000000000000e-01, 10, 'T_10(-0.5) stable recurrence'],
            [10, 0.00000000000000000e+00, -1.00000000000000000e+00, 10, 'T_10(0.0) stable recurrence'],
            [10, 5.00000000000000000e-01, -5.00000000000000000e-01, 10, 'T_10(0.5) stable recurrence'],
            [10, 9.00000000000000022e-01, -2.00747468800000495e-01, 10, 'T_10(0.9) stable recurrence'],
            [20, -9.00000000000000022e-01, -9.19400907540785628e-01, 10, 'T_20(-0.9) stable recurrence'],
            [20, -5.00000000000000000e-01, -5.00000000000000000e-01, 10, 'T_20(-0.5) stable recurrence'],
            [20, 0.00000000000000000e+00, 1.00000000000000000e+00, 10, 'T_20(0.0) stable recurrence'],
            [20, 5.00000000000000000e-01, -5.00000000000000000e-01, 10, 'T_20(0.5) stable recurrence'],
            [20, 9.00000000000000022e-01, -9.19400907540785628e-01, 10, 'T_20(0.9) stable recurrence'],
            [30, -9.00000000000000022e-01, 5.69882278802472553e-01, 10, 'T_30(-0.9) stable recurrence'],
            [30, -5.00000000000000000e-01, 1.00000000000000000e+00, 10, 'T_30(-0.5) stable recurrence'],
            [30, 0.00000000000000000e+00, -1.00000000000000000e+00, 10, 'T_30(0.0) stable recurrence'],
            [30, 5.00000000000000000e-01, 1.00000000000000000e+00, 10, 'T_30(0.5) stable recurrence'],
            [30, 9.00000000000000022e-01, 5.69882278802472553e-01, 10, 'T_30(0.9) stable recurrence'],
            [40, -9.00000000000000022e-01, 6.90596057573640620e-01, 10, 'T_40(-0.9) stable recurrence'],
            [40, -5.00000000000000000e-01, -5.00000000000000000e-01, 10, 'T_40(-0.5) stable recurrence'],
            [40, 0.00000000000000000e+00, 1.00000000000000000e+00, 10, 'T_40(0.0) stable recurrence'],
            [40, 5.00000000000000000e-01, -5.00000000000000000e-01, 10, 'T_40(0.5) stable recurrence'],
            [40, 9.00000000000000022e-01, 6.90596057573640620e-01, 10, 'T_40(0.9) stable recurrence'],
            [50, -9.00000000000000022e-01, -8.47153099844808022e-01, 10, 'T_50(-0.9) stable recurrence'],
            [50, -5.00000000000000000e-01, -5.00000000000000000e-01, 10, 'T_50(-0.5) stable recurrence'],
            [50, 0.00000000000000000e+00, -1.00000000000000000e+00, 10, 'T_50(0.0) stable recurrence'],
            [50, 5.00000000000000000e-01, -5.00000000000000000e-01, 10, 'T_50(0.5) stable recurrence'],
            [50, 9.00000000000000022e-01, -8.47153099844808022e-01, 10, 'T_50(0.9) stable recurrence'],
        ];
    }

    /**
     * Test data from mpmath (50 decimal places)
     */
    public function dataProviderForHermitePrecision(): array
    {
        return [
            [5, -3.00000000000000000e+00, -3.81600000000000000e+03, 8, 'H_5(-3.0) recurrence with growth'],
            [5, -1.00000000000000000e+00, 8.00000000000000000e+00, 8, 'H_5(-1.0) recurrence with growth'],
            [5, 0.00000000000000000e+00, 0.00000000000000000e+00, 8, 'H_5(0.0) recurrence with growth'],
            [5, 1.00000000000000000e+00, -8.00000000000000000e+00, 8, 'H_5(1.0) recurrence with growth'],
            [5, 3.00000000000000000e+00, 3.81600000000000000e+03, 8, 'H_5(3.0) recurrence with growth'],
            [10, -3.00000000000000000e+00, -3.09398400000000000e+06, 8, 'H_10(-3.0) recurrence with growth'],
            [10, -1.00000000000000000e+00, 8.22400000000000000e+03, 8, 'H_10(-1.0) recurrence with growth'],
            [10, 0.00000000000000000e+00, -3.02400000000000000e+04, 8, 'H_10(0.0) recurrence with growth'],
            [10, 1.00000000000000000e+00, 8.22400000000000000e+03, 8, 'H_10(1.0) recurrence with growth'],
            [10, 3.00000000000000000e+00, -3.09398400000000000e+06, 8, 'H_10(3.0) recurrence with growth'],
            [15, -3.00000000000000000e+00, -1.40571417600000000e+09, 8, 'H_15(-3.0) recurrence with growth'],
            [15, -1.00000000000000000e+00, -1.04891648000000000e+08, 8, 'H_15(-1.0) recurrence with growth'],
            [15, 0.00000000000000000e+00, 0.00000000000000000e+00, 8, 'H_15(0.0) recurrence with growth'],
            [15, 1.00000000000000000e+00, 1.04891648000000000e+08, 8, 'H_15(1.0) recurrence with growth'],
            [15, 3.00000000000000000e+00, 1.40571417600000000e+09, 8, 'H_15(3.0) recurrence with growth'],
            [20, -3.00000000000000000e+00, 5.99902813992960000e+13, 6, 'H_20(-3.0) recurrence with growth'],
            [20, -1.00000000000000000e+00, 1.10721447833600000e+12, 8, 'H_20(-1.0) recurrence with growth'],
            [20, 0.00000000000000000e+00, 6.70442572800000000e+11, 8, 'H_20(0.0) recurrence with growth'],
            [20, 1.00000000000000000e+00, 1.10721447833600000e+12, 8, 'H_20(1.0) recurrence with growth'],
            [20, 3.00000000000000000e+00, 5.99902813992960000e+13, 6, 'H_20(3.0) recurrence with growth'],
        ];
    }
}
