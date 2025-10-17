<?php

namespace MathPHP\Tests\Functions\Special;

use MathPHP\Functions\Special;
use MathPHP\Exception;

class BetaTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         beta returns the expected value
     * @dataProvider dataProviderForBeta
     * @param        float $x
     * @param        float $y
     * @param        float $expected
     * @throws       \Exception
     */
    public function testBeta(float $x, float $y, float $expected)
    {
        // When
        $beta = Special::beta($x, $y);
        $β    = Special::β($y, $x);

        // Then
        $this->assertEqualsWithDelta($expected, $beta, \abs($beta) * 1e-6 + 1e-12);
        $this->assertEqualsWithDelta($expected, $β, \abs($beta) * 1e-6 + 1e-12);
    }

    /**
     * Test data created with scipy.special.beta(a, b), R beta (x, y)
     * and online calculator https://keisan.casio.com/exec/system/1180573394
     * @return array (x, y, β)
     */
    public function dataProviderForBeta(): array
    {
        return [
            [1, 1, 1.0],
            [1, 2, 0.5],
            [1, 3, 0.3333333333333333],
            [1, 4, 0.25],
            [1, 5, 0.2],
            [1, 6, 0.16666666666666669],
            [1, 7, 0.14285714285714285],
            [1, 8, 0.125],
            [1, 9, 0.11111111111111112],
            [1, 10, 0.09999999999999999],
            [1, 11, 0.09090909090909091],
            [1, 20, 0.05],
            [2, 0, \INF],
            [2, 1, 0.5],
            [2, 2, 0.16666666666666666],
            [2, 3, 0.08333333333333333],
            [2, 4, 0.05],
            [2, 5, 0.03333333333333333],
            [2, 6, 0.023809523809523808],
            [2, 7, 0.017857142857142856],
            [2, 8, 0.01388888888888889],
            [2, 9, 0.01111111111111111],
            [2, 10, 0.009090909090909092],
            [2, 11, 0.007575757575757576],
            [2, 20, 0.002380952380952381],
            [3, 0, \INF],
            [3, 1, 0.3333333333333333],
            [3, 2, 0.08333333333333333],
            [3, 3, 0.03333333333333333],
            [3, 4, 0.016666666666666666],
            [3, 5, 0.009523809523809525],
            [3, 6, 0.005952380952380952],
            [3, 7, 0.003968253968253969],
            [3, 8, 0.0027777777777777775],
            [3, 9, 0.00202020202020202],
            [3, 10, 0.0015151515151515152],
            [3, 11, 0.0011655011655011655],
            [3, 20, 0.00021645021645021648],
            [1.5, 0.2, 4.4776093743471685],
            [0.2, 1.5, 4.4776093743471685],
            [0.1, 0.9, 10.16640738463052],
            [0.9, 0.1, 10.16640738463052],
            [3, 9, 0.00202020202020202],
            [9, 3, 0.00202020202020202],
            [10, 10, 1.0825088224469029e-06],
            [20, 20, 7.254444551924845e-13],
            [\INF, 2, 0.0],
            [2, \INF, 0.0],
            [1.5, 170.5, 0.0003971962],  // Issue #429
            [5.65, 3.43, 0.0042964669653093365],
            [6.24, 6.67, 0.00018626244199887866],
        ];
    }

    /**
     * @testCase     logBeta returns the expected value
     * @dataProvider dataProviderForLogBeta
     */
    public function testLogBeta($x, $y, float $log_beta)
    {
        $this->assertEqualsWithDelta($log_beta, Special::logBeta($x, $y), \abs($log_beta * 1e-14 + 1e-16));
    }

    /**
     * Test data generated with scipy.special.betaln(a, b))
     * @return array
     */
    public function dataProviderForLogBeta(): array
    {
        return [
            [1.5, 0, \INF],
            [0, 1.5, \INF],
            [0, 0, \INF],
            [1, 1, 0],
            [1, 2, -0.6931471805599453],
            [2, 1, -0.6931471805599453],
            [2, 2, -1.791759469228055],
            [.9, .1, 2.319088891468949],
            [20, 20, -27.95199188624447],
            [1, \INF, -\INF],
            [1, 11, -2.3978952727983707],
            [5E-307, 5, 705.28418563673792],
            [94907000, 11, -186.9480762562983],
            [6.24, 6.67, -8.588353900257262],
        ];
    }

    /**
     * @test beta returns NaNException appropriately
     */
    public function testBetaNan()
    {
        // Given
        $nan = \NAN;

        // Then
        $this->expectException(Exception\NanException::class);

        // When
        $beta = Special::beta($nan, 2);
    }

    /**
     * @test logBeta returns NaNException appropriately
     */
    public function testLogBetaNan()
    {
        // Given
        $nan = \NAN;

        // Then
        $this->expectException(Exception\NanException::class);

        // When
        $lbeta = Special::logBeta($nan, 2);
    }

    /**
     * @test logBeta parameters must be greater than 0
     */
    public function testLogBetaOutOfBounds()
    {
        // Given
        $p = -1;

        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        $lbeta = Special::logBeta($p, 2);
    }

    /**
     * @test beta parameters must be greater than 0
     */
    public function testBetaOutOfBounds()
    {
        // Given
        $p = -1;

        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        $beta = Special::beta($p, 2);
    }

    /**
     * @test         multivariateBeta returns the expected value
     * @dataProvider dataProviderForBeta
     * @param        float $x
     * @param        float $y
     * @param        float $expected
     * @throws       \Exception
     */
    public function testMultivariateBeta(float $x, float $y, float $expected)
    {
        // When
        $beta = Special::multivariateBeta([$x, $y]);

        // Then
        $this->assertEqualsWithDelta($expected, $beta, 0.0000001);
    }

    /**
     * @test         incompleteBeta returns the expected value
     * @dataProvider dataProviderForIncompleteBeta
     * @param        float $x
     * @param        float $a
     * @param        float $b
     * @param        float $ib
     * @throws       \Exception
     */
    public function testIncompleteBeta(float $x, float $a, float $b, float $ib)
    {
        // When
        $incompleteBeta = Special::incompleteBeta($x, $a, $b);

        // Then
        $this->assertEqualsWithDelta($ib, $incompleteBeta, \abs($ib) * 1e-10 + 1e-12);
    }

    public function dataProviderForIncompleteBeta(): array
    {
        return [
            [0.1, 1, 3, 0.09033333333333333333333],
            [0.4, 1, 3, 0.2613333333333333333333],
            [0.9, 1, 3, 0.333],
            [0.4, 1, 2, 0.32],
            [0.4, 1, 4, 0.2176],
            [0.4, 2, 4, 0.033152],
            [0.4, 4, 4, 0.002069942857142857142857],
            [0.7, 6, 10, 3.31784042288233100233E-5],
            [0.7, 7, 10, 1.23984824870524912587E-5],
            [0.44, 3, 8.4, 0.0022050133154863808046],
            [0.44, 3.5, 8.5, 0.00101547937082370450368],
            [0.3, 2.5, 4.5, 0.00873072257563537808667],
            [0.5, 1, 2, 0.375],
            [0.2, 3.4, 2.3, 9.94015483378346364195E-4],
            [0.8, 3.4, 2.3, 0.040438859297104036187],
            [0.45, 12.45, 3.49, 1.016239733540625974803E-6],
            [0.294, 0.23, 2.11, 3.082589637435583044388],
            [0.993, 0.23, 2.11, 3.48298583651202868119],
            [0.76, 4, 0.3, 0.1692673319857469933301],
            [0.55, 2, 2.5, 0.0774145505281552534703],
            [0.55, 2.5, 2, 0.05448257245698492387678],
            [0.55, 3.5, 2, 0.0201727956188770976315],
            [0.73, 3.5, 5, 0.00553077297647439276549],
        ];
    }

    /**
     * @test         regularizedIncompleteBeta returns the expected value
     * @dataProvider dataProviderForRegularizedIncompleteBeta
     * @param        float $x
     * @param        float $a
     * @param        float $b
     * @param        float $rib
     * @throws       \Exception
     */
    public function testRegularizedIncompleteBeta(float $x, float $a, float $b, float $rib)
    {
        // When
        $regularizedIncompleteBeta = Special::regularizedIncompleteBeta($x, $a, $b);

        // Then
        $this->assertEqualsWithDelta($rib, $regularizedIncompleteBeta, \abs($rib) * 1e-5 + 1e-12);
    }

    /**
     * Test data created with Python scipy.special.betainc(a, b, x) and online calculator https://keisan.casio.com/exec/system/1180573396
     * @return array (x, a, b, beta)
     */
    public function dataProviderForRegularizedIncompleteBeta(): array
    {
        return [
            [0.4, 1, 2, 0.64],
            [0.4, 1, 4, 0.87040],
            [0.4, 2, 4, 0.663040],
            [0.4, 4, 4, 0.2897920],
            [0.7, 6, 10, 0.99634748],
            [0.7, 7, 10, 0.99287048],
            [0.44, 3, 8.4, 0.90536083],
            [0.44, 3.5, 8.5, 0.86907356],
            [0.3, 2.5, 4.5, 0.40653902],
            [0.5, 1, 2, 0.750],
            [0.2, 3.4, 2.3, 0.02072722],
            [0.8, 3.4, 2.3, 0.84323132],
            [0.45, 12.45, 3.49, 0.00283809],
            [0.294, 0.23, 2.11, 0.88503883],
            [0.993, 0.23, 2.11, 0.99999612],
            [0.76, 4, 0.3, 0.08350803],
            [0.55, 2, 2.5, 0.67737732],
            [0.55, 2.5, 2, 0.47672251],
            [0.55, 3.5, 2, 0.31772153],
            [0.73, 3.5, 5, 0.97317839],
            [0, 1, 1, 0],
            [0.1, 1, 1, 0.1],
            [0.2, 1, 1, 0.2],
            [0.3, 1, 1, 0.3],
            [0.4, 1, 1, 0.4],
            [0.5, 1, 1, 0.5],
            [0.6, 1, 1, 0.6],
            [0.7, 1, 1, 0.7],
            [0.8, 1, 1, 0.8],
            [0.9, 1, 1, 0.9],
            [1, 1, 1, 1],
            [0, 2, 2, 0],
            [0.1, 2, 2, 0.028],
            [0.2, 2, 2, 0.104],
            [0.3, 2, 2, 0.216],
            [0.4, 2, 2, 0.352],
            [0.5, 2, 2, 0.5],
            [0.6, 2, 2, 0.648],
            [0.7, 2, 2, 0.784],
            [0.8, 2, 2, 0.896],
            [0.9, 2, 2, 0.972],
            [1, 2, 2, 1],
            // SciPy examples - https://docs.scipy.org/doc/scipy/reference/generated/scipy.special.betainc.html#scipy.special.betainc
            [1, 0.2, 3.5, 1],
            [0.5, 1.4, 3.1, 0.8148904036225296],
            [0.4, 2.2, 3.1, 0.4933963880761945],
            [1, 1, 1, 1],
            [0.5, 2, 3, 0.6875],
            [0.3, 3, 4, 0.2556899999999999],
            // Issue #429
            [0.0041461509490402, 0.5, 170.5, 0.7657225092554762],
            [0.0041461509490402, 1.5, 170.5, 0.29887797299851921],
            // Issue #458
            [0.47241392386467, 0.5, 55.5, 0.99999999999999996],
            [0.47241392386467, 1.5, 55.5, 0.99999999999999773],
            // Reference implementation tests computed using SciPy - https://github.com/codeplea/incbeta/blob/master/test.c
            [0.1, 10, 10, 3.929882327128003e-06],
            [0.3, 10, 10, 0.03255335688130092],
            [0.5, 10, 10, 0.5],
            [0.7, 10, 10, 0.967446643118699],
            [1, 10, 10, 1.0],
            [0.5, 15, 10, 0.1537281274795532],
            [0.6, 15, 10, 0.4890801931489529],
            [0.5, 10, 15, 0.8462718725204468],
            [0.6, 10, 15, 0.978341948670397],
            [0.4, 20, 20, 0.10205863128940816],
            [0.4, 40, 40, 0.03581030716079576],
            [0.7, 40, 40, 0.9999016649962936],
        ];
    }

    /**
     * @test regularizedIncompleteBeta throws an OutOfBoundsException if a is less than 0
     */
    public function testRegularizedIncompleteBetaExceptionALessThanZero()
    {
        // Given
        $a = -1;

        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        Special::regularizedIncompleteBeta(0.4, $a, 4);
    }

    /**
     * @test regularizedIncompleteBeta throws an OutOfBoundsException if x is out of bounds
     */
    public function testRegularizedIncompleteBetaExceptionXOutOfBounds()
    {
        // Given
        $x = -1;

        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        Special::regularizedIncompleteBeta($x, 4, 4);
    }

    /**
     * @test Github Issue 393 Bug - regularizedIncompleteBeta
     * Reference expected values:
     *   Python scipy.special.betainc(a, b, x)
     *     >>> scipy.special.betainc(274.40782656165, 0.5, 0.99993441100298)
     *     0.8495884744315958
     *   Online calculator https://keisan.casio.com/exec/system/1180573396
     *     0.8495884744316587246283
     */
    public function testIssue393BugInRegularizedIncompleteBeta()
    {
        // Given
        $x = 0.99993441100298;
        $a = 274.40782656165;
        $b = 0.5;

        // And
        $expected = 0.8495884744315958;

        // When
        $betainc = Special::regularizedIncompleteBeta($x, $a, $b);

        // Then
        $this->assertEqualsWithDelta($expected, $betainc, 0.00001);
    }

    /**
     * @test         beta function symmetry β(a,b) = β(b,a)
     * @dataProvider dataProviderForBetaSymmetry
     * @param        float $a
     * @param        float $b
     * @throws       \Exception
     */
    public function testBetaSymmetry(float $a, float $b)
    {
        // When
        $beta_ab = Special::beta($a, $b);
        $beta_ba = Special::beta($b, $a);

        // Then
        // β(a,b) should equal β(b,a)
        $this->assertEqualsWithDelta($beta_ab, $beta_ba, abs($beta_ab) * 1e-14);
    }

    /**
     * Test values for beta symmetry
     * Source: Mathematical property of beta function
     * @return array
     */
    public function dataProviderForBetaSymmetry(): array
    {
        // Beta function symmetry: β(a,b) = β(b,a)
        return [
            [2, 3],
            [5, 7],
            [0.5, 1.5],
            [10, 20],
            [1.7, 3.2],
            [0.1, 0.9],
            [50, 100],
        ];
    }

    /**
     * @test         beta function relationship to gamma: β(a,b) = Γ(a)Γ(b)/Γ(a+b)
     * @dataProvider dataProviderForBetaGammaRelationship
     * @param        float $a
     * @param        float $b
     * @throws       \Exception
     */
    public function testBetaGammaRelationship(float $a, float $b)
    {
        // When
        $beta = Special::beta($a, $b);

        // Calculate using gamma functions
        $gamma_a = Special::gamma($a);
        $gamma_b = Special::gamma($b);
        $gamma_a_plus_b = Special::gamma($a + $b);
        $beta_from_gamma = ($gamma_a * $gamma_b) / $gamma_a_plus_b;

        // Then β(a,b) should equal Γ(a)Γ(b)/Γ(a+b)
        $this->assertEqualsWithDelta($beta, $beta_from_gamma, abs($beta) * 1e-12);
    }

    /**
     * Test values for beta-gamma relationship
     * Source: NIST DLMF §5.12 Beta Function
     * https://dlmf.nist.gov/5.12
     * @return array
     */
    public function dataProviderForBetaGammaRelationship(): array
    {
        return [
            [1, 1],
            [2, 3],
            [0.5, 0.5],
            [5, 10],
            [1.5, 2.5],
            [0.25, 0.75],
            [3.7, 4.2],
        ];
    }

    /**
     * @test Regularized incomplete beta symmetry: I_x(a,b) + I_{1-x}(b,a) = 1
     * @dataProvider dataProviderForRegularizedIncompleteBetaComplement
     * @param float $x
     * @param float $a
     * @param float $b
     */
    public function testRegularizedIncompleteBetaComplement(float $x, float $a, float $b)
    {
        // When
        $I_x_ab = Special::regularizedIncompleteBeta($x, $a, $b);
        $I_1mx_ba = Special::regularizedIncompleteBeta(1 - $x, $b, $a);

        // Complement relationship: I_x(a,b) + I_{1-x}(b,a) = 1
        $∑ = $I_x_ab + $I_1mx_ba;

        // Then
        $this->assertEqualsWithDelta(1.0, $∑, 1e-10);
    }

    public function dataProviderForRegularizedIncompleteBetaComplement(): array
    {
        return [
            [0.3, 2.0, 3.0],
            [0.5, 1.0, 1.0],
            [0.4, 2.5, 3.5],
            [0.7, 3.0, 2.0],
            [0.2, 5.0, 2.0],
            [0.8, 2.0, 5.0],
        ];
    }

    /**
     * @test Incomplete beta is monotonically increasing in x
     * @dataProvider dataProviderForIncompleteBetaMonotonicity
     * @param float $x1
     * @param float $x2
     * @param float $a
     * @param float $b
     */
    public function testRegularizedIncompleteBetaMonotonicity(float $x1, float $x2, float $a, float $b)
    {
        // Ensure x1 < x2
        if ($x1 >= $x2) {
            $this->fail("Test requires x1 < x2");
        }

        // When
        $I_x1 = Special::regularizedIncompleteBeta($x1, $a, $b);
        $I_x2 = Special::regularizedIncompleteBeta($x2, $a, $b);

        // Then: I_x is increasing in x, so I(x1) < I(x2)
        $this->assertTrue($I_x1 < $I_x2);
    }

    public function dataProviderForIncompleteBetaMonotonicity(): array
    {
        return [
            [0.1, 0.3, 2.0, 3.0],
            [0.3, 0.5, 2.0, 3.0],
            [0.5, 0.7, 2.0, 3.0],
            [0.2, 0.6, 1.0, 1.0],
            [0.1, 0.5, 3.5, 2.5],
            [0.4, 0.8, 5.0, 3.0],
        ];
    }

    /**
     * @test Cross-validation of beta function against Boost Math test data
     * @dataProvider dataProviderForBetaBoost
     * @param float $a
     * @param float $b
     * @param float $expected Boost reference value
     */
    public function testBetaCrossValidationBoost(float $a, float $b, float $expected)
    {
        // When
        $result = Special::beta($a, $b);

        // Then - compare against Boost Math Toolkit reference
        $this->assertEqualsWithDelta($expected, $result, abs($expected) * 1e-12 + 1e-14);
    }

    public function dataProviderForBetaBoost(): array
    {
        // Reference values verified with SciPy scipy.special.beta
        // β(a,b) = Γ(a)Γ(b)/Γ(a+b)
        return [
            [1.0, 1.0, 1.0],                   // β(1,1) = 1
            [2.0, 3.0, 0.083333333333333333],  // β(2,3) = 1/12
            [0.5, 0.5, 3.1415926535897932],    // β(1/2,1/2) = π
            [5.5, 2.3, 1.8084206313912280e-02],
            [10.0, 5.0, 9.9900099900099900e-05],
        ];
    }
}
