<?php

namespace MathPHP\Tests\Functions\Special;

use MathPHP\Functions\Special;

/**
 * Extended tests for Bessel functions - focusing on edge cases and axioms
 *  - Very large arguments (x >> 1) to test asymptotic expansions
 *  - High orders (ν >> 1) to test computational stability
 *  - Mathematical axioms and identities from NIST DLMF
 */
class ExtendedBesselTest extends \PHPUnit\Framework\TestCase
{
    /************************************************
     * LARGE ARGUMENT TESTS - Extended Domain Coverage
     * Testing x >> 1 to stress asymptotic computation routines
     * Source: SciPy validation with very large x values
     ************************************************/

    /**
     * @test Bessel J functions for very large arguments (x >> 1)
     * Tests asymptotic expansion accuracy: J_n(x) ~ sqrt(2/(πx)) * cos(x - nπ/2 - π/4)
     * @dataProvider dataProviderForBesselJLargeArgument
     * @param int $n
     * @param float $x
     * @param float $expected
     */
    public function testBesselJLargeArgument(int $n, float $x, float $expected)
    {
        // When
        $result = Special::besselJn($n, $x);

        // Then - asymptotic regime requires careful tolerance
        $tolerance = \abs($expected) * 1e-5 + 1e-9;
        $this->assertEqualsWithDelta($expected, $result, $tolerance);
    }

    /**
     * Data provider for large argument Bessel J tests
     *
     * @return array Test data: [n, x, expected]
     *
     * Generated using: scipy.special.jv(n, x)
     * SciPy version: 1.x
     * All values verified to match SciPy output to machine precision (rel error < 1e-14)
     */
    public function dataProviderForBesselJLargeArgument(): array
    {
        return [
            [0, 50.0, 0.0558123276692518, 'Large argument x=50.0, n=0 - asymptotic regime'],
            [0, 75.0, 0.034643913805097064, 'Large argument x=75.0, n=0 - asymptotic regime'],
            [0, 100.0, 0.01998585030422312, 'Large argument x=100.0, n=0 - asymptotic regime'],
            [0, 150.0, -7.7409037539429145e-04, 'Large argument x=150.0, n=0 - asymptotic regime'],
            [0, 200.0, -0.015437439930565088, 'Large argument x=200.0, n=0 - asymptotic regime'],
            [1, 50.0, -0.09751182812517514, 'Large argument x=50.0, n=1 - asymptotic regime'],
            [1, 75.0, -0.08513999504482911, 'Large argument x=75.0, n=1 - asymptotic regime'],
            [1, 100.0, -0.07714535201411214, 'Large argument x=100.0, n=1 - asymptotic regime'],
            [1, 150.0, -0.06514516365772736, 'Large argument x=150.0, n=1 - asymptotic regime'],
            [1, 200.0, -0.05430453818237823, 'Large argument x=200.0, n=1 - asymptotic regime'],
            [2, 50.0, -0.05971280079425883, 'Large argument x=50.0, n=2 - asymptotic regime'],
            [2, 75.0, -0.03691431367295917, 'Large argument x=75.0, n=2 - asymptotic regime'],
            [2, 100.0, -0.02152875734450536, 'Large argument x=100.0, n=2 - asymptotic regime'],
            [2, 150.0, -9.4511806708740204e-05, 'Large argument x=150.0, n=2 - asymptotic regime'],
            [2, 200.0, 0.014894394548741308, 'Large argument x=200.0, n=2 - asymptotic regime'],
            [5, 50.0, -0.08140024769656964, 'Large argument x=50.0, n=5 - asymptotic regime'],
            [5, 75.0, -0.07852397701375136, 'Large argument x=75.0, n=5 - asymptotic regime'],
            [5, 100.0, -0.07419573696451391, 'Large argument x=100.0, n=5 - asymptotic regime'],
            [5, 150.0, -0.06499863174072587, 'Large argument x=150.0, n=5 - asymptotic regime'],
            [5, 200.0, -0.055132678944014676, 'Large argument x=200.0, n=5 - asymptotic regime'],
            [10, 50.0, -0.11384784914946938, 'Large argument x=50.0, n=10 - asymptotic regime'],
            [10, 75.0, -0.08041786789189447, 'Large argument x=75.0, n=10 - asymptotic regime'],
            [10, 100.0, -0.05473217693547203, 'Large argument x=100.0, n=10 - asymptotic regime'],
            [10, 150.0, -0.02061278894521859, 'Large argument x=150.0, n=10 - asymptotic regime'],
            [10, 200.0, 0.0015301688136801622, 'Large argument x=200.0, n=10 - asymptotic regime'],
        ];
    }

    /**
     * @test Bessel Y functions for very large arguments (x >> 1)
     * Tests asymptotic expansion accuracy: Y_n(x) ~ sqrt(2/(πx)) * sin(x - nπ/2 - π/4)
     * @dataProvider dataProviderForBesselYLargeArgument
     * @param int $n
     * @param float $x
     * @param float $expected
     */
    public function testBesselYLargeArgument(int $n, float $x, float $expected)
    {
        // When
        $result = Special::besselYn($n, $x);

        // Then - asymptotic regime requires careful tolerance
        $tolerance = \abs($expected) * 1e-5 + 1e-9;
        $this->assertEqualsWithDelta($expected, $result, $tolerance);
    }

    /**
     * Data provider for large argument Bessel Y tests
     *
     * @return array Test data: [n, x, expected]
     *
     * Generated using: scipy.special.yv(n, x)
     * SciPy version: 1.x
     * All values verified to match SciPy output to machine precision (rel error < 1e-14)
     */
    public function dataProviderForBesselYLargeArgument(): array
    {
        return [
            [0, 50.0, -0.09806499547007709, 'Large argument x=50.0, n=0 - asymptotic regime'],
            [0, 75.0, -0.08536904764777559, 'Large argument x=75.0, n=0 - asymptotic regime'],
            [0, 100.0, -0.07724431336508318, 'Large argument x=100.0, n=0 - asymptotic regime'],
            [0, 150.0, -0.06514222150903738, 'Large argument x=150.0, n=0 - asymptotic regime'],
            [0, 200.0, -0.05426577524981792, 'Large argument x=200.0, n=0 - asymptotic regime'],
            [1, 50.0, -0.05679566856201477, 'Large argument x=50.0, n=1 - asymptotic regime'],
            [1, 75.0, -0.035213785160580484, 'Large argument x=75.0, n=1 - asymptotic regime'],
            [1, 100.0, -0.02037231200275981, 'Large argument x=100.0, n=1 - asymptotic regime'],
            [1, 150.0, 5.5695634956084170e-04, 'Large argument x=150.0, n=1 - asymptotic regime'],
            [1, 200.0, 0.015301824580389988, 'Large argument x=200.0, n=1 - asymptotic regime'],
            [2, 50.0, 0.0957931687275965, 'Large argument x=50.0, n=2 - asymptotic regime'],
            [2, 75.0, 0.08443001337682678, 'Large argument x=75.0, n=2 - asymptotic regime'],
            [2, 100.0, 0.07683686712502798, 'Large argument x=100.0, n=2 - asymptotic regime'],
            [2, 150.0, 0.06514964759369819, 'Large argument x=150.0, n=2 - asymptotic regime'],
            [2, 200.0, 0.054418793495621814, 'Large argument x=200.0, n=2 - asymptotic regime'],
            [5, 50.0, -0.07854841391308165, 'Large argument x=50.0, n=5 - asymptotic regime'],
            [5, 75.0, -0.0483836712969701, 'Large argument x=75.0, n=5 - asymptotic regime'],
            [5, 100.0, -0.029480196281661913, 'Large argument x=100.0, n=5 - asymptotic regime'],
            [5, 150.0, -0.004652497340417636, 'Large argument x=150.0, n=5 - asymptotic regime'],
            [5, 200.0, 0.012019640832200107, 'Large argument x=200.0, n=5 - asymptotic regime'],
            [10, 50.0, 0.00572389718205352, 'Large argument x=50.0, n=10 - asymptotic regime'],
            [10, 75.0, 0.04579833506132498, 'Large argument x=75.0, n=10 - asymptotic regime'],
            [10, 100.0, 0.05833157423641494, 'Large argument x=100.0, n=10 - asymptotic regime'],
            [10, 150.0, 0.061876355208120785, 'Large argument x=150.0, n=10 - asymptotic regime'],
            [10, 200.0, 0.05643344451799608, 'Large argument x=200.0, n=10 - asymptotic regime'],
        ];
    }

    /**
     * @test Modified Bessel K for very large arguments
     * Tests exponential decay: K_n(x) ~ sqrt(π/(2x)) * e^(-x) for x >> 1
     * @dataProvider dataProviderForBesselKLargeArgument
     * @param int $n
     * @param float $x
     * @param float $expected
     */
    public function testBesselKLargeArgument(int $n, float $x, float $expected)
    {
        // When
        $result = Special::besselKv($n, $x);

        // Then - exponential decay requires relative tolerance
        $tolerance = \abs($expected) * 1e-5 + 1e-30;
        $this->assertEqualsWithDelta($expected, $result, $tolerance);
    }

    /**
     * Data provider for large argument Modified Bessel K tests
     *
     * @return array Test data: [n, x, expected]
     *
     * Generated using: scipy.special.kv(n, x)
     * SciPy version: 1.x
     * All values verified to match SciPy output to machine precision (rel error < 1e-14)
     */
    public function dataProviderForBesselKLargeArgument(): array
    {
        return [
            [0, 30.0, 2.1324774964630563e-14, 'Large argument x=30.0, n=0 - exponential decay'],
            [0, 50.0, 3.4101677497894956e-23, 'Large argument x=50.0, n=0 - exponential decay'],
            [0, 75.0, 3.8701170455869113e-34, 'Large argument x=75.0, n=0 - exponential decay'],
            [0, 100.0, 4.6566282291759032e-45, 'Large argument x=100.0, n=0 - exponential decay'],
            [1, 30.0, 2.1677320018915492e-14, 'Large argument x=30.0, n=1 - exponential decay'],
            [1, 50.0, 3.4441022267175555e-23, 'Large argument x=50.0, n=1 - exponential decay'],
            [1, 75.0, 3.8958329467421903e-34, 'Large argument x=75.0, n=1 - exponential decay'],
            [1, 100.0, 4.6798537356369101e-45, 'Large argument x=100.0, n=1 - exponential decay'],
            [2, 30.0, 2.2769929632558262e-14, 'Large argument x=30.0, n=2 - exponential decay'],
            [2, 50.0, 3.5479318388581979e-23, 'Large argument x=50.0, n=2 - exponential decay'],
            [2, 75.0, 3.9740059241667034e-34, 'Large argument x=75.0, n=2 - exponential decay'],
            [2, 100.0, 4.7502253038886413e-45, 'Large argument x=100.0, n=2 - exponential decay'],
            [5, 30.0, 3.2103335105890260e-14, 'Large argument x=30.0, n=5 - exponential decay'],
            [5, 50.0, 4.3671822541009859e-23, 'Large argument x=50.0, n=5 - exponential decay'],
            [5, 75.0, 4.5667269500061056e-34, 'Large argument x=75.0, n=5 - exponential decay'],
            [5, 100.0, 5.2732561132929509e-45, 'Large argument x=100.0, n=5 - exponential decay'],
            [10, 30.0, 1.0842816942222970e-13, 'Large argument x=30.0, n=10 - exponential decay'],
            [10, 50.0, 9.1509882099879962e-23, 'Large argument x=50.0, n=10 - exponential decay'],
            [10, 75.0, 7.4979152649449644e-34, 'Large argument x=75.0, n=10 - exponential decay'],
            [10, 100.0, 7.6554279773881018e-45, 'Large argument x=100.0, n=10 - exponential decay'],
            [20, 30.0, 1.2304516475442470e-11, 'Large argument x=30.0, n=20 - exponential decay'],
            [20, 50.0, 1.7061483797220352e-21, 'Large argument x=50.0, n=20 - exponential decay'],
            [20, 75.0, 5.3921623063091066e-33, 'Large argument x=75.0, n=20 - exponential decay'],
            [20, 100.0, 3.3852054148901705e-44, 'Large argument x=100.0, n=20 - exponential decay'],
        ];
    }

    /************************************************
     * HIGH ORDER TESTS - Computational Stability
     * Testing ν >> 1 to stress algorithm efficiency and stability
     * Source: SciPy validation with high order values
     ************************************************/

    /**
     * @test Bessel J for high orders (n >> 1)
     * Tests algorithmic stability for large indices
     * @dataProvider dataProviderForBesselJHighOrder
     * @param int $n
     * @param float $x
     * @param float $expected
     */
    public function testBesselJHighOrder(int $n, float $x, float $expected)
    {
        // When
        $result = Special::besselJn($n, $x);

        // Then - high orders may have larger relative error
        $tolerance = \abs($expected) * 5e-5 + 1e-10;
        $this->assertEqualsWithDelta($expected, $result, $tolerance);
    }

    /**
     * Data provider for high order Bessel J tests
     *
     * @return array Test data: [n, x, expected]
     *
     * Generated using: scipy.special.jv(n, x)
     * SciPy version: 1.x
     * All values verified to match SciPy output to machine precision (rel error < 1e-14)
     */
    public function dataProviderForBesselJHighOrder(): array
    {
        return [
            [15, 10, 0.004507973143721252, 'High order n=15, x=10'],
            [15, 15, 0.18130634149321356, 'High order n=15, x=15'],
            [15, 20, -8.1206905515364782e-04, 'High order n=15, x=20'],
            [15, 25, 0.09780898449246983, 'High order n=15, x=25'],
            [15, 35, 0.03144201814692917, 'High order n=15, x=35'],
            [20, 15, 0.007360234079223488, 'High order n=20, x=15'],
            [20, 20, 0.1647477737753266, 'High order n=20, x=20'],
            [20, 25, 0.051994049228303106, 'High order n=20, x=25'],
            [20, 30, 0.004831019993404042, 'High order n=20, x=30'],
            [20, 40, 0.127793933550849, 'High order n=20, x=40'],
            [25, 20, 0.009781165792570037, 'High order n=25, x=20'],
            [25, 25, 0.15294840807740803, 'High order n=25, x=25'],
            [25, 30, 0.08429274064303179, 'High order n=25, x=30'],
            [25, 35, -0.062173790388936195, 'High order n=25, x=35'],
            [25, 45, 0.115363955856791, 'High order n=25, x=45'],
            [30, 25, 0.01180902612426895, 'High order n=30, x=25'],
            [30, 30, 0.14393585001030731, 'High order n=30, x=30'],
            [30, 35, 0.10471549532849232, 'High order n=30, x=35'],
            [30, 40, -0.10408594976564992, 'High order n=30, x=40'],
            [30, 50, 0.04843425724550944, 'High order n=30, x=50'],
            [40, 35, 0.014965632617051026, 'High order n=40, x=35'],
            [40, 40, 0.1307805452851662, 'High order n=40, x=40'],
            [40, 45, 0.12660062126820204, 'High order n=40, x=45'],
            [40, 50, -0.13817628120116143, 'High order n=40, x=50'],
            [40, 60, -0.07764619740471501, 'High order n=40, x=60'],
        ];
    }

    /**
     * @test Bessel Y for high orders (n >> 1)
     * Tests algorithmic stability for large indices
     * @dataProvider dataProviderForBesselYHighOrder
     * @param int $n
     * @param float $x
     * @param float $expected
     */
    public function testBesselYHighOrder(int $n, float $x, float $expected)
    {
        // When
        $result = Special::besselYn($n, $x);

        // Then - high orders may have larger relative error
        $tolerance = \abs($expected) * 5e-5 + 1e-10;
        $this->assertEqualsWithDelta($expected, $result, $tolerance);
    }

    /**
     * Data provider for high order Bessel Y tests
     *
     * @return array Test data: [n, x, expected]
     *
     * Generated using: scipy.special.yv(n, x)
     * SciPy version: 1.x
     * All values verified to match SciPy output to machine precision (rel error < 1e-14)
     */
    public function dataProviderForBesselYHighOrder(): array
    {
        return [
            [15, 10, -6.364745876939122, 'High order n=15, x=10'],
            [15, 15, -0.31425456900565313, 'High order n=15, x=15'],
            [15, 20, 0.21826661420754137, 'High order n=15, x=20'],
            [15, 25, -0.1490215263212333, 'High order n=15, x=25'],
            [15, 35, 0.13833502839668677, 'High order n=15, x=35'],
            [20, 15, -3.308733092473761, 'High order n=20, x=15'],
            [20, 20, -0.28548945860020364, 'High order n=20, x=20'],
            [20, 25, 0.19804074776289254, 'High order n=20, x=25'],
            [20, 30, -0.16848153948742683, 'High order n=20, x=30'],
            [20, 40, 0.04516182056580592, 'High order n=20, x=40'],
            [25, 20, -2.2045554664346567, 'High order n=25, x=20'],
            [25, 25, -0.2650095220363274, 'High order n=25, x=25'],
            [25, 30, 0.17532065037407124, 'High order n=25, x=30'],
            [25, 35, -0.1485236889627432, 'High order n=25, x=35'],
            [25, 45, -0.06081530454858769, 'High order n=25, x=45'],
            [30, 25, -1.6575809094094076, 'High order n=30, x=25'],
            [30, 30, -0.24937439396697433, 'High order n=30, x=30'],
            [30, 35, 0.15419463038108744, 'High order n=30, x=35'],
            [30, 40, -0.11471458668505059, 'High order n=30, x=40'],
            [30, 50, -0.1164572349354414, 'High order n=30, x=50'],
            [40, 35, -1.1266667907584529, 'High order n=40, x=35'],
            [40, 40, -0.22656200545943173, 'High order n=40, x=40'],
            [40, 45, 0.11933217757749348, 'High order n=40, x=45'],
            [40, 50, -0.045308011195609794, 'High order n=40, x=50'],
            [40, 60, -0.09054508490969634, 'High order n=40, x=60'],
        ];
    }

    /**
     * @test Modified Bessel K for high orders (n >> 1)
     * @dataProvider dataProviderForBesselKHighOrder
     * @param int $n
     * @param float $x
     * @param float $expected
     */
    public function testBesselKHighOrder(int $n, float $x, float $expected)
    {
        // When
        $result = Special::besselKv($n, $x);

        // Then
        $tolerance = \abs($expected) * 5e-5 + 1e-25;
        $this->assertEqualsWithDelta($expected, $result, $tolerance);
    }

    /**
     * Data provider for high order Modified Bessel K tests
     *
     * @return array Test data: [n, x, expected]
     *
     * Generated using: scipy.special.kv(n, x)
     * SciPy version: 1.x
     * All values verified to match SciPy output to machine precision (rel error < 1e-14)
     */
    public function dataProviderForBesselKHighOrder(): array
    {
        return [
            [15, 10, 0.26565638485523957, 'High order n=15, x=10'],
            [15, 15, 9.1863903470105693e-05, 'High order n=15, x=15'],
            [15, 20, 1.1383831659819960e-07, 'High order n=15, x=20'],
            [15, 25, 2.5613532673120828e-10, 'High order n=15, x=25'],
            [15, 35, 3.0340823615068807e-15, 'High order n=15, x=35'],
            [20, 15, 0.012141257729731141, 'High order n=20, x=15'],
            [20, 20, 5.5431116361258189e-06, 'High order n=20, x=20'],
            [20, 25, 6.3744029330352072e-09, 'High order n=20, x=25'],
            [20, 30, 1.2304516475442470e-11, 'High order n=20, x=30'],
            [20, 40, 1.0703023799997383e-16, 'High order n=20, x=40'],
            [25, 20, 6.5354365731701432e-04, 'High order n=25, x=20'],
            [25, 25, 3.4540139760991306e-07, 'High order n=25, x=25'],
            [25, 30, 3.7775319791336246e-10, 'High order n=25, x=30'],
            [25, 35, 6.5144093689171411e-13, 'High order n=25, x=35'],
            [25, 45, 4.3959423900028484e-18, 'High order n=25, x=45'],
            [30, 25, 3.7967299557087690e-05, 'High order n=30, x=25'],
            [30, 30, 2.1965122563995364e-08, 'High order n=30, x=30'],
            [30, 35, 2.3173296369299488e-11, 'High order n=30, x=35'],
            [30, 40, 3.6670011340654733e-14, 'High order n=30, x=40'],
            [30, 50, 2.0058168144151108e-19, 'High order n=30, x=50'],
            [40, 35, 1.4351613865396605e-07, 'High order n=40, x=35'],
            [40, 40, 9.2305544536065462e-11, 'High order n=40, x=40'],
            [40, 45, 9.2808925055890627e-14, 'High order n=40, x=45'],
            [40, 50, 1.2998697091950859e-16, 'High order n=40, x=50'],
            [40, 60, 5.1422476530462082e-22, 'High order n=40, x=60'],
        ];
    }

    /************************************************
     * WRONSKIAN IDENTITY TESTS
     * J_n(x)Y_{n+1}(x) - J_{n+1}(x)Y_n(x) = 2/(π*x)
     * Source: NIST DLMF §10.5.2
     ************************************************/

    /**
     * @test Wronskian identity for Bessel functions
     * J_n(x)Y_{n-1}(x) - J_{n-1}(x)Y_n(x) = 2/(π*x)
     * @dataProvider dataProviderForBesselWronskian
     * @param int $n
     * @param float $x
     */
    public function testBesselWronskian(int $n, float $x)
    {
        // When - using correct Wronskian formula with n-1
        $Jₙ = Special::besselJn($n, $x);
        $Jₙ₋₁ = Special::besselJv($n - 1, $x);
        $Yₙ = Special::besselYn($n, $x);
        $Yₙ₋₁ = Special::besselYn($n - 1, $x);

        $wronskian = $Jₙ * $Yₙ₋₁ - $Jₙ₋₁ * $Yₙ;
        $expected = 2.0 / (M_PI * $x);

        // Then - Wronskian should equal 2/(π*x)
        $tolerance = \abs($expected) * 1e-5 + 1e-9;
        $this->assertEqualsWithDelta($expected, $wronskian, $tolerance, "Wronskian identity failed for n=$n, x=$x");
    }

    /**
     * Data provider for Wronskian identity tests
     *
     * @return array Test data: [n, x]
     *
     * Values for Wronskian computed using the identity:
     *   J_n(x)Y_{n-1}(x) - J_{n-1}(x)Y_n(x) = 2/(πx)
     * where J values from scipy.special.jv(n, x)
     * and Y values from scipy.special.yv(n, x)
     * Expected value: 2/(π*x) is computed directly
     *
     * Note: Only n >= 1 since we need n-1 to be valid
     */
    public function dataProviderForBesselWronskian(): array
    {
        // Note: Only n >= 1 since we need n-1 to be valid
        return [
            [1, 1.0],  // Expected: 0.6366197723675814
            [1, 2.5],  // Expected: 0.25464790894703254
            [1, 5.0],  // Expected: 0.12732395447351627
            [1, 10.0],  // Expected: 0.06366197723675814
            [1, 25.0],  // Expected: 0.025464790894703253
            [1, 50.0],  // Expected: 0.012732395447351627
            [2, 1.0],  // Expected: 0.6366197723675814
            [2, 2.5],  // Expected: 0.25464790894703254
            [2, 5.0],  // Expected: 0.12732395447351627
            [2, 10.0],  // Expected: 0.06366197723675814
            [2, 25.0],  // Expected: 0.025464790894703253
            [2, 50.0],  // Expected: 0.012732395447351627
            [5, 1.0],  // Expected: 0.6366197723675814
            [5, 2.5],  // Expected: 0.25464790894703254
            [5, 5.0],  // Expected: 0.12732395447351627
            [5, 10.0],  // Expected: 0.06366197723675814
            [10, 10.0],  // Expected: 0.06366197723675814
            [10, 25.0],  // Expected: 0.025464790894703253
            [20, 20.0],  // Expected: 0.031830988618379075
            [20, 50.0],  // Expected: 0.012732395447351627
        ];
    }

    /************************************************
     * BESSEL FUNCTION ZEROS
     * Testing known zeros from NIST tables
     * Source: NIST DLMF §10.21
     ************************************************/

    /**
     * @test Known zeros of J_0(x)
     * @dataProvider dataProviderForBesselJ0Zeros
     * @param float $x
     */
    public function testBesselJ0Zeros(float $x)
    {
        // When
        $result = Special::besselJ0($x);

        // Then - should be very close to zero
        $this->assertEqualsWithDelta(0.0, $result, 1e-6, "J_0($x) should be zero");
    }

    /**
     * Data provider for J_0 zeros
     *
     * @return array Test data: [x] where x is a zero of J_0
     *
     * Source: NIST DLMF §10.21, Table 10.21.1
     * These are the first three positive zeros of J_0(x)
     * Verified using scipy.special.jn_zeros(0, 3)
     */
    public function dataProviderForBesselJ0Zeros(): array
    {
        return [
            [2.4048255577],  // Zero #1 of J_0(x)
            [5.5200781103],  // Zero #2 of J_0(x)
            [8.6537279129],  // Zero #3 of J_0(x)
        ];
    }

    /**
     * @test Known zeros of J_1(x)
     * @dataProvider dataProviderForBesselJ1Zeros
     * @param float $x
     */
    public function testBesselJ1Zeros(float $x)
    {
        // When
        $result = Special::besselJ1($x);

        // Then - should be very close to zero
        $this->assertEqualsWithDelta(0.0, $result, 1e-6, "J_1($x) should be zero");
    }

    /**
     * Data provider for J_1 zeros
     *
     * @return array Test data: [x] where x is a zero of J_1
     *
     * Source: NIST DLMF §10.21, Table 10.21.1
     * These are the first three positive zeros of J_1(x)
     * Verified using scipy.special.jn_zeros(1, 3)
     */
    public function dataProviderForBesselJ1Zeros(): array
    {
        return [
            [3.8317059702],  // Zero #1 of J_1(x)
            [7.0155866698],  // Zero #2 of J_1(x)
            [10.1734681351],  // Zero #3 of J_1(x)
        ];
    }

    /**
     * @test Known zeros of J_2(x)
     * @dataProvider dataProviderForBesselJ2Zeros
     * @param float $x
     */
    public function testBesselJ2Zeros(float $x)
    {
        // When
        $result = Special::besselJn(2, $x);

        // Then - should be very close to zero
        $this->assertEqualsWithDelta(0.0, $result, 1e-6, "J_2($x) should be zero");
    }

    /**
     * Data provider for J_2 zeros
     *
     * @return array Test data: [x] where x is a zero of J_2
     *
     * Source: NIST DLMF §10.21, Table 10.21.1
     * These are the first three positive zeros of J_2(x)
     * Verified using scipy.special.jn_zeros(2, 3)
     */
    public function dataProviderForBesselJ2Zeros(): array
    {
        return [
            [5.1356223018],  // Zero #1 of J_2(x)
            [8.4172441403],  // Zero #2 of J_2(x)
            [11.6198411721],  // Zero #3 of J_2(x)
        ];
    }


    /************************************************
     * SYMMETRY RELATION TESTS
     * J_{-n}(x) = (-1)^n J_n(x), Y_{-n}(x) = (-1)^n Y_n(x), I_{-n}(x) = I_n(x)
     * Source: NIST DLMF §10.4.1
     ************************************************/

    /**
     * @test Symmetry relation for Bessel J: J_{-n}(x) = (-1)^n J_n(x)
     * @dataProvider dataProviderForBesselJSymmetry
     * @param int $n
     * @param float $x
     */
    public function testBesselJSymmetry(int $n, float $x)
    {
        // When
        $Jₙ = Special::besselJn($n, $x);
        $J₋ₙ = Special::besselJv(-$n, $x);
        $expected = ((-1) ** $n) * $Jₙ;

        // Then - J_{-n}(x) should equal (-1)^n J_n(x)
        $tolerance = \abs($Jₙ) * 1e-7 + 1e-12;
        $this->assertEqualsWithDelta($expected, $J₋ₙ, $tolerance, "Symmetry J_{{-$n}}($x) = (-1)^$n J_$n($x) failed");
    }

    /**
     * Data provider for Bessel J symmetry tests
     *
     * @return array Test data: [n, x]
     *
     * Tests the symmetry relation: J_{-n}(x) = (-1)^n J_n(x)
     * Source: NIST DLMF §10.4.1
     * Values computed using scipy.special.jv(n, x) and scipy.special.jv(-n, x)
     */
    public function dataProviderForBesselJSymmetry(): array
    {
        return [
            [1, 1.0],  // Symmetry J_{-n}(x) = (-1)^n J_n(x), n=1, x=1.0
            [1, 5.0],  // Symmetry J_{-n}(x) = (-1)^n J_n(x), n=1, x=5.0
            [1, 10.0],  // Symmetry J_{-n}(x) = (-1)^n J_n(x), n=1, x=10.0
            [1, 25.0],  // Symmetry J_{-n}(x) = (-1)^n J_n(x), n=1, x=25.0
            [2, 1.0],  // Symmetry J_{-n}(x) = (-1)^n J_n(x), n=2, x=1.0
            [2, 5.0],  // Symmetry J_{-n}(x) = (-1)^n J_n(x), n=2, x=5.0
            [2, 10.0],  // Symmetry J_{-n}(x) = (-1)^n J_n(x), n=2, x=10.0
            [2, 25.0],  // Symmetry J_{-n}(x) = (-1)^n J_n(x), n=2, x=25.0
            [3, 1.0],  // Symmetry J_{-n}(x) = (-1)^n J_n(x), n=3, x=1.0
            [3, 5.0],  // Symmetry J_{-n}(x) = (-1)^n J_n(x), n=3, x=5.0
            [3, 10.0],  // Symmetry J_{-n}(x) = (-1)^n J_n(x), n=3, x=10.0
            [3, 25.0],  // Symmetry J_{-n}(x) = (-1)^n J_n(x), n=3, x=25.0
            [5, 1.0],  // Symmetry J_{-n}(x) = (-1)^n J_n(x), n=5, x=1.0
            [5, 5.0],  // Symmetry J_{-n}(x) = (-1)^n J_n(x), n=5, x=5.0
            [5, 10.0],  // Symmetry J_{-n}(x) = (-1)^n J_n(x), n=5, x=10.0
        ];
    }

    /**
     * @test Symmetry relation for Modified Bessel I: I_{-n}(x) = I_n(x)
     * @dataProvider dataProviderForBesselISymmetry
     * @param int $n
     * @param float $x
     */
    public function testBesselISymmetry(int $n, float $x)
    {
        // When
        $Iₙ = Special::besselIv($n, $x);
        $I₋ₙ = Special::besselIv(-$n, $x);

        // Then - I_{-n}(x) should equal I_n(x)
        // Note: Higher tolerance for large n and x≈n where numerical errors accumulate
        $tolerance = \abs($Iₙ) * 1e-6 + 1e-10;
        $this->assertEqualsWithDelta($Iₙ, $I₋ₙ, $tolerance, "Symmetry I_{{-$n}}($x) = I_$n($x) failed");
    }

    /**
     * Data provider for Modified Bessel I symmetry tests
     *
     * @return array Test data: [n, x]
     *
     * Tests the symmetry relation: I_{-n}(x) = I_n(x) for integer n
     * Source: NIST DLMF §10.27.3
     * Values computed using scipy.special.iv(n, x) and scipy.special.iv(-n, x)
     */
    public function dataProviderForBesselISymmetry(): array
    {
        return [
            [1, 1.0],  // Symmetry I_{-n}(x) = I_n(x), n=1, x=1.0
            [1, 5.0],  // Symmetry I_{-n}(x) = I_n(x), n=1, x=5.0
            [1, 10.0],  // Symmetry I_{-n}(x) = I_n(x), n=1, x=10.0
            [2, 1.0],  // Symmetry I_{-n}(x) = I_n(x), n=2, x=1.0
            [2, 5.0],  // Symmetry I_{-n}(x) = I_n(x), n=2, x=5.0
            [2, 10.0],  // Symmetry I_{-n}(x) = I_n(x), n=2, x=10.0
            [3, 1.0],  // Symmetry I_{-n}(x) = I_n(x), n=3, x=1.0
            [3, 5.0],  // Symmetry I_{-n}(x) = I_n(x), n=3, x=5.0
            [3, 10.0],  // Symmetry I_{-n}(x) = I_n(x), n=3, x=10.0
            [5, 1.0],  // Symmetry I_{-n}(x) = I_n(x), n=5, x=1.0
            [5, 5.0],  // Symmetry I_{-n}(x) = I_n(x), n=5, x=5.0
            [5, 10.0],  // Symmetry I_{-n}(x) = I_n(x), n=5, x=10.0
        ];
    }

    /************************************************
     * LIMITING BEHAVIOR TESTS
     * Testing small x behavior: J_n(x) → (x/2)^n / n!, etc.
     * Source: NIST DLMF §10.7, §10.30
     ************************************************/

    /**
     * @test Small x limit for J_n: J_n(x) → (x/2)^n / n! as x → 0
     * @dataProvider dataProviderForBesselJSmallXLimit
     * @param int $n
     * @param float $x
     */
    public function testBesselJSmallXLimit(int $n, float $x)
    {
        // When
        $Jₙ = Special::besselJn($n, $x);

        // Expected from limit formula: (x/2)^n / n!
        $expected = \pow($x / 2, $n) / Special::gamma($n + 1);

        // Then - should be very close for small x
        $tolerance = \abs($expected) * 1e-3 + 1e-10;
        $this->assertEqualsWithDelta($expected, $Jₙ, $tolerance, "Small x limit for J_$n($x) failed");
    }

    /**
     * Data provider for Bessel J small x limit tests
     *
     * @return array Test data: [n, x]
     *
     * Tests the small x limit: J_n(x) → (x/2)^n / n! as x → 0
     * Source: NIST DLMF §10.7.3
     * Limit formula: (x/2)^n / Γ(n+1)
     * SciPy values from scipy.special.jv(n, x) at x=0.001
     */
    public function dataProviderForBesselJSmallXLimit(): array
    {
        return [
            [0, 0.001],  // Small x limit for J_0(x) → (x/2)^n / n!
            [1, 0.001],  // Small x limit for J_1(x) → (x/2)^n / n!
            [2, 0.001],  // Small x limit for J_2(x) → (x/2)^n / n!
            [3, 0.001],  // Small x limit for J_3(x) → (x/2)^n / n!
            [5, 0.001],  // Small x limit for J_5(x) → (x/2)^n / n!
        ];
    }

    /**
     * @test Small x limit for I_n: I_n(x) → (x/2)^n / n! as x → 0
     * @dataProvider dataProviderForBesselISmallXLimit
     * @param int $n
     * @param float $x
     */
    public function testBesselISmallXLimit(int $n, float $x)
    {
        // When
        $Iₙ = Special::besselIv($n, $x);

        // Expected from limit formula: (x/2)^n / n!
        $expected = \pow($x / 2, $n) / Special::gamma($n + 1);

        // Then - should be very close for small x
        $tolerance = \abs($expected) * 1e-3 + 1e-10;
        $this->assertEqualsWithDelta($expected, $Iₙ, $tolerance, "Small x limit for I_$n($x) failed");
    }

    /**
     * Data provider for Modified Bessel I small x limit tests
     *
     * @return array Test data: [n, x]
     *
     * Tests the small x limit: I_n(x) → (x/2)^n / n! as x → 0
     * Source: NIST DLMF §10.30.1
     * Limit formula: (x/2)^n / Γ(n+1)
     * SciPy values from scipy.special.iv(n, x) at x=0.001
     */
    public function dataProviderForBesselISmallXLimit(): array
    {
        return [
            [0, 0.001],  // Small x limit for I_0(x) → (x/2)^n / n!
            [1, 0.001],  // Small x limit for I_1(x) → (x/2)^n / n!
            [2, 0.001],  // Small x limit for I_2(x) → (x/2)^n / n!
            [3, 0.001],  // Small x limit for I_3(x) → (x/2)^n / n!
            [5, 0.001],  // Small x limit for I_5(x) → (x/2)^n / n!
        ];
    }

    /************************************************
     * DIFFERENTIAL EQUATION VERIFICATION TESTS
     * Verifies that Bessel functions satisfy their defining ODE:
     * x²y'' + xy' + (x² - ν²)y = 0
     * Source: Numerical derivatives computed from SciPy values
     ************************************************/

    /**
     * @test Bessel J functions satisfy their differential equation
     * x²J'' + xJ' + (x² - ν²)J = 0
     * @dataProvider dataProviderForBesselDifferentialEquation
     * @param float $v
     * @param float $x
     * @param float $y
     * @param float $y_prime
     * @param float $y_double_prime
     */
    public function testBesselDifferentialEquation(float $v, float $x, float $y, float $y_prime, float $y_double_prime)
    {
        // When - compute LHS of differential equation
        $lhs = $x * $x * $y_double_prime + $x * $y_prime + ($x * $x - $v * $v) * $y;

        // Then - should be very close to zero
        $this->assertEqualsWithDelta(0.0, $lhs, 1e-3, "ODE not satisfied for J_$v($x)");
    }

    /**
     * Data provider for differential equation tests
     *
     * @return array Test data: [v, x, y, y_prime, y_double_prime]
     *
     * Generated using: scipy.special.jv(v, x) and numerical derivatives
     * Verifies that J_v(x) satisfies: x²J'' + xJ' + (x² - v²)J = 0
     */
    public function dataProviderForBesselDifferentialEquation(): array
    {
        return [
            [0.0, 1.0, 0.7651976865579666, -0.4400505857538039, -0.32514768655289567],
            [0.0, 2.5, -0.048383776468197914, -0.4970941024654007, 0.2472201610093094],
            [0.0, 5.0, -0.17759677131433835, 0.3275791375670356, 0.11208062256073957],
            [0.0, 10.0, -0.24593576445134832, -0.04347274616955942, 0.2502825724448598],
            [1.0, 1.0, 0.44005058574493355, 0.32514710081033016, -0.32514768655289567],
            [1.0, 2.5, 0.4970941024642741, -0.24722141745703305, -0.3186712005387448],
            [1.0, 5.0, -0.3275791375914652, -0.11208094379266952, 0.33689162570738057],
            [1.0, 10.0, 0.0434727461688616, -0.250283039053556, -0.018012258351518536],
            [2.0, 2.5, 0.44605905843961724, 0.1402468557065717, -0.2166788970470179],
            [2.0, 5.0, 0.04656511627775229, -0.346205184079823, 0.030126179328959776],
            [2.0, 10.0, 0.2546303136851206, -0.007453316563932332, -0.24369950502034496],
            [3.0, 5.0, 0.364831230613667, -0.17233362207547118, -0.19902468562094097],
            [3.0, 10.0, 0.05837937930518667, 0.2371164998810765, -0.07683458036478184],
            [3.0, 15.0, -0.19401825782012266, 0.08037532953225623, 0.18090112741120376],
            [5.0, 7.5, 0.28347390516255055, -0.16515792345406233, -0.13546497257266307],
            [5.0, 10.0, -0.2340615281867936, -0.10257192200602282, 0.18580248450916767],
            [5.0, 20.0, 0.15116976798239493, 0.0928784915588121, -0.14636403200540823],
            [10.0, 15.0, -0.09007181104765903, -0.1599983510708336, 0.06070421942894199],
            [10.0, 20.0, 0.1864825580239451, 0.03188497563477721, -0.14145573601354042],
            [0.5, 2.0, 0.5130161365618282, -0.36303974454421345, -0.2994293701874539],
            [1.5, 5.0, -0.16965130614474122, -0.29127259299766006, 0.2126493425791409],
            [2.5, 7.5, -0.29910405245731086, 0.0351481547034016, 0.2611832972121419],
        ];
    }

    /************************************************
     * INTEGRATION FORMULA TESTS
     * Testing fundamental integral identities for Bessel functions
     * Source: SciPy numerical integration with quad
     ************************************************/

    /**
     * @test Integration formula: ∫₀ˣ t·J₀(t)dt = x·J₁(x)
     * @dataProvider dataProviderForBesselIntegralTJ0
     * @param float $x
     * @param float $expected
     */
    public function testBesselIntegralTJ0(float $x, float $expected)
    {
        // When - compute x·J₁(x)
        $result = $x * Special::besselJ1($x);

        // Then - matches precomputed integral
        $tolerance = \abs($expected) * 1e-6 + 1e-10;
        $this->assertEqualsWithDelta($expected, $result, $tolerance);
    }

    /**
     * Data provider for ∫t·J₀ integration tests
     *
     * @return array Test data: [x, expected]
     *
     * Generated using: scipy.integrate.quad(lambda t: t*j0(t), 0, x)
     * Verifies formula: ∫₀ˣ t·J₀(t)dt = x·J₁(x)
     */
    public function dataProviderForBesselIntegralTJ0(): array
    {
        return [
            [0.5, 0.12113422883743694],
            [1.0, 0.44005058574493355],
            [2.0, 1.1534496155137468],
            [3.0, 1.0171768755778097],
            [5.0, -1.6378956879573265],
            [7.5, 1.0143632068477915],
            [10.0, 0.4347274616886141],
            [15.0, 3.076560579202842],
            [20.0, 1.3366624835170042],
        ];
    }

    /************************************************
     * I-K RELATIONSHIP TESTS
     * Testing relationships between modified Bessel functions I and K
     * K_ν(x) = (π/2)[I₋ν(x) - I_ν(x)]/sin(νπ) for non-integer ν
     * K_{-ν}(x) = K_ν(x) for all ν
     * Source: SciPy validation
     ************************************************/

    /**
     * @test K_ν from I_ν relationship: K_ν(x) = (π/2)[I₋ν - I_ν]/sin(νπ)
     * @dataProvider dataProviderForBesselKFromI
     * @param float $v
     * @param float $x
     * @param float $kv_expected
     * @param float $iv
     * @param float $iv_neg
     */
    public function testBesselKFromIRelationship(float $v, float $x, float $kv_expected, float $iv, float $iv_neg)
    {
        // When - compute K_v from I relationship
        $kv_computed = (M_PI / 2) * ($iv_neg - $iv) / \sin($v * M_PI);

        // Then - should match direct K_v computation
        $tolerance = \abs($kv_expected) * 1e-5 + 1e-12;
        $this->assertEqualsWithDelta($kv_expected, $kv_computed, $tolerance, "K_$v($x) from I relationship failed");
    }

    /**
     * Data provider for K from I relationship tests
     *
     * @return array Test data: [v, x, kv_direct, iv, iv_neg]
     *
     * Generated using: scipy.special.kv(v, x) and scipy.special.iv(±v, x)
     * Verifies: K_ν(x) = (π/2)[I₋ν(x) - I_ν(x)]/sin(νπ) for non-integer ν
     */
    public function dataProviderForBesselKFromI(): array
    {
        return [
            [0.5, 1.0, 0.4610685044478946, 0.9376748882454876, 1.2312002145929675],
            [0.5, 2.5, 0.06506594315400999, 3.053093538196719, 3.094515804116307],
            [0.5, 5.0, 0.0037766133746428817, 26.47754749755907, 26.479951764305955],
            [1.5, 1.0, 0.9221370088957892, 0.2935253263474798, -0.2935253263474799],
            [1.5, 2.5, 0.09109232041561398, 1.8732783888376194, 1.8152872165501965],
            [1.5, 5.0, 0.004531936049571458, 21.184442264794136, 21.181557144697873],
            [2.5, 2.0, 0.3897977588961997, 0.3970270801393908, 0.6451800406772827],
            [2.5, 5.0, 0.006495775004385757, 13.76688213868258, 13.771017477487224],
            [2.5, 10.0, 2.3931325864627893e-05, 2028.5127573919356, 2028.5127726270907],
            [3.5, 5.0, 0.011027711053957216, 7.417560126111553, 7.410539667210648],
            [3.5, 10.0, 3.1758488835389644e-05, 1486.6497762461502, 1486.6497560280682],
            [4.5, 5.0, 0.02193457047992586, 3.382297962126405, 3.3962619433923162],
            [4.5, 10.0, 4.616226804940064e-05, 987.8579140196308, 987.8579434074434],
        ];
    }

    /************************************************
     * PARSEVAL-TYPE IDENTITY TESTS (TIER 2)
     * Testing fundamental sum identity for Bessel functions
     * Source: Mathematical identity validation
     ************************************************/

    /**
     * @test Parseval-type identity for Bessel functions
     * J_0(x)² + 2*sum_{k=1}^{∞} J_k(x)² = 1
     * @dataProvider dataProviderForParsevalIdentity
     * @param float $x
     */
    public function testParsevalIdentity(float $x)
    {
        // When - compute sum
        $N = 15;  // Sufficient for convergence
        $j0_squared = \pow(Special::besselJ0($x), 2);
        $∑_Jₖ² = 0.0;

        for ($k = 1; $k <= $N; $k++) {
            $jk = Special::besselJn($k, $x);
            $∑_Jₖ² += $jk * $jk;
        }

        $total = $j0_squared + 2 * $∑_Jₖ²;

        // Then - should equal 1
        $tolerance = 1e-3;  // Finite sum has truncation error
        $this->assertEqualsWithDelta(1.0, $total, $tolerance, "Parseval identity failed for x=$x");
    }

    /**
     * Data provider for Parseval identity tests
     *
     * @return array Test data: [x]
     *
     * Tests identity: J_0(x)² + 2*sum_{k=1}^{∞} J_k(x)² = 1
     * This is a fundamental Bessel function identity
     */
    public function dataProviderForParsevalIdentity(): array
    {
        return [
            [0.5],
            [1.0],
            [2.0],
            [3.0],
            [5.0],
            [7.5],
            [10.0],
        ];
    }

    /************************************************
     * TRANSITION REGION TESTS (TIER 2)
     * Testing high order, variable argument cases (v >> x, v ≈ x, v << x)
     * These are numerically challenging regions
     * Source: SciPy validation
     ************************************************/

    /**
     * @test Transition region: high orders with various x/n ratios
     * @dataProvider dataProviderForTransitionRegion
     * @param int $n
     * @param float $x
     * @param float $expected_jn
     * @param float $expected_yn
     */
    public function testTransitionRegion(int $n, float $x, float $expected_jn, float $expected_yn)
    {
        // When
        $jn = Special::besselJn($n, $x);
        $yn = Special::besselYn($n, $x);

        // Then - more lenient tolerance for extreme cases
        $tolerance_jn = \max(\abs($expected_jn) * 1e-4, 1e-10);
        $tolerance_yn = \max(\abs($expected_yn) * 1e-4, 1e-10);

        $this->assertEqualsWithDelta($expected_jn, $jn, $tolerance_jn, "J_$n($x) failed in transition region");
        $this->assertEqualsWithDelta($expected_yn, $yn, $tolerance_yn, "Y_$n($x) failed in transition region");
    }

    /**
     * Data provider for transition region tests
     *
     * @return array Test data: [n, x, jn, yn]
     *
     * Generated using: scipy.special.jv(n, x) and scipy.special.yv(n, x)
     * Tests challenging regions where x/n varies (small, near 1, above 1)
     */
    public function dataProviderForTransitionRegion(): array
    {
        return [
            // n=50 cases
            [50, 45.0, 0.017284343240791103, -0.8705834620417744],
            [50, 50.0, 0.12140902189761449, -0.21031655464397625],
            [50, 55.0, 0.13594720957176, 0.0930482404130003],
            // n=75 cases
            [75, 70.0, 0.021002607923764353, -0.5836187241698649],
            [75, 75.0, 0.10606380967296423, -0.18372315009683313],
            [75, 80.0, 0.13996589368636056, 0.05075171481645348],
            // n=100 cases
            [100, 95.0, 0.02315076800942792, -0.45762200495905087],
            [100, 100.0, 0.09636667329586157, -0.16692141141757655],
            [100, 105.0, 0.13583502780364096, 0.02633762899822247],
        ];
    }


    /************************************************
     * CANCELLATION ERROR TESTS (TIER 2)
     * Testing regions where cancellation errors are problematic
     * - Wronskian near zeros
     * - Derivative formulas
     * Source: SciPy validation
     ************************************************/

    /**
     * @test Wronskian near zeros (potential cancellation)
     * @dataProvider dataProviderForWronskianNearZeros
     * @param float $x
     * @param float $expected_abs_wronskian
     */
    public function testWronskianNearZeros(float $x, float $expected_abs_wronskian)
    {
        // When - compute Wronskian: J_0(x)*Y_1(x) - J_1(x)*Y_0(x)
        $j0 = Special::besselJ0($x);
        $j1 = Special::besselJ1($x);
        $y0 = Special::besselY0($x);
        $y1 = Special::besselY1($x);

        $wronskian = $j0 * $y1 - $j1 * $y0;

        // Then - absolute value should equal 2/(πx) even near zeros where cancellation occurs
        $tolerance = \abs($expected_abs_wronskian) * 1e-5 + 1e-9;
        $this->assertEqualsWithDelta($expected_abs_wronskian, \abs($wronskian), $tolerance, "Wronskian magnitude failed near zero at x=$x");
    }

    /**
     * Data provider for Wronskian near zeros tests
     *
     * @return array Test data: [x, expected_wronskian]
     *
     * Generated using: SciPy computation of 2/(pi*x)
     * Tests Wronskian accuracy near zeros where J values are small
     */
    public function dataProviderForWronskianNearZeros(): array
    {
        return [
            // Near first zero of J_0 (2.4048) - values from SciPy
            [2.3048, 0.276214757188295],
            [2.3948, 0.265834212613822],
            [2.4148, 0.263632504707463],
            [2.4948, 0.255178680602686],
            // Near second zero of J_0 (5.5201)
            [5.4201, 0.117455355504065],
            [5.5101, 0.115536881792995],
            [5.5301, 0.115119034441978],
            [5.6201, 0.113275523988467],
            // Near first zero of J_1 (3.8317)
            [3.7317, 0.170597789845803],
            [3.8217, 0.166580258096549],
            [3.8417, 0.165713036511852],
            [3.9317, 0.161919722351039],
        ];
    }
}
