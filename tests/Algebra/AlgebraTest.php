<?php
namespace MathPHP\Tests\Algebra;

use MathPHP\Algebra;
use MathPHP\Number;

class AlgebraTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     gcd returns the greatest common divisor of two integers.
     * @dataProvider dataProviderForGcd
     */
    public function testGCD(int $a, int $b, int $gcd, int $_, int $__)
    {
        $this->assertEquals($gcd, Algebra::gcd($a, $b));
    }

    /**
     * @testCase     extendedGCD returns the extended greatest common divisor of two integers.
     * @dataProvider dataProviderForGcd
     */
    public function testExtendedGcd(int $a, int $b, int $gcd, int $alpha, int $beta)
    {
        $this->assertEquals([$gcd, $alpha, $beta], Algebra::extendedGcd($a, $b));
    }

    public function dataProviderForGcd()
    {
        return [
            [0, 0, 0, 0, 1],
            [8, 0, 8, 1, 0],
            [0, 8, 8, 0, 1],
            [8, 12, 4, -1, 1],
            [12, 8, 4, 1, -1],
            [54, 24, 6, 1, -2],
            [24, 54, 6, -2, 1],
            [18, 84, 6, 5, -1],
            [84, 18, 6, -1, 5],
            [244, 343, 1, 97, -69],
            [343, 244, 1, -69, 97],
            [97, 577, 1, 232, -39],
            [577, 97, 1, -39, 232],
            [40902, 24140, 34, 337, -571],
            [24140, 40902, 34, -571, 337],
            [1234, 54, 2, -7, 160],
            [54, 1234, 2, 160, -7],
        ];
    }

    /**
     * @testCase     lcm returns the least-common multiple of two integers.
     * @dataProvider dataProviderForLcm
     */
    public function testLCM(int $a, int $b, int $lcm)
    {
        $this->assertEquals($lcm, Algebra::lcm($a, $b));
    }

    public function dataProviderForLcm()
    {
        return [
            [0, 0, 0],
            [8, 0, 0],
            [0, 8, 0],
            [5, 2, 10],
            [2, 5, 10],
            [4, 6, 12],
            [6, 4, 12],
            [21, 6, 42],
            [6, 21, 42],
            [598, 352, 105248],
            [352, 598, 105248],
        ];
    }

    /**
     * @testCase     factors returns the expected factors of an integer.
     * @dataProvider dataProviderForFactors
     */
    public function testFactors(int $x, array $factors)
    {
        $this->assertEquals($factors, Algebra::factors($x));
    }

    public function dataProviderForFactors()
    {
        return [
            [ 0, [\INF] ],
            [ 12, [1, 2, 3, 4, 6, 12] ],
            [ 14, [1, 2, 7, 14] ],
            [ 30, [1, 2, 3, 5, 6, 10, 15, 30] ],
            [ 2248, [1, 2, 4, 8, 281, 562, 1124, 2248] ],
            [ 983928, [1, 2, 3, 4, 6, 8, 11, 12, 22, 24, 33, 44, 66, 88, 132, 264, 3727, 7454, 11181, 14908, 22362, 29816, 40997, 44724, 81994, 89448, 122991, 163988, 245982, 327976, 491964, 983928] ],
        ];
    }

    /**
     * @testCase     quadratic returns the expected roots.
     * @dataProvider dataProviderForQuadratic
     */
    public function testQuadratic($a, $b, $c, $quadratic)
    {
        $this->assertEquals($quadratic, Algebra::quadratic($a, $b, $c), '', 0.00000001);
    }

    /**
     * Many data examples from: http://www.themathpage.com/alg/quadratic-equations.htm
     */
    public function dataProviderForQuadratic()
    {
        return [
            [2, 4, -4, [-1 - sqrt(3), -1 + sqrt(3)]],
            [1, -3, -4, [-1, 4]],
            [1, 1, -4, [-2.56155281280883, 1.56155281280883]],
            [1, 0, -4, [-2, 2]],
            [6, 11, -35, [-7/2, 5/3]],
            [1, 0, -48, [-4 * sqrt(3), 4 * sqrt(3)]],
            [1, -7, 0, [0, 7]],
            [5, 6, 1, [-1, -0.2]],
            [1, 2, -8, [-4, 2]],
            [1, 2, -3, [-3, 1]],
            [1, -12, 36, [6, 6]],
            [2, 9, -5, [-5, 1/2]],
            [1, -3, 2, [1, 2]],
            [1, 7, 12, [-4, -3]],
            [1, 3, -10, [-5, 2]],
            [1, -1, -30, [-5, 6]],
            [2, 7, 3, [-3, -1/2]],
            [3, 1, -2, [-1, 2/3]],
            [1, 12, 36, [-6, -6]],
            [1, -2, 1, [1, 1]],
            [1, -5, 0, [0, 5]],
            [1, 1, 0, [-1, 0]],
            [3, 4, 0, [-4/3, 0]],
            [2, -1, 0, [0, 1/2]],
            [1, 0, -3, [-sqrt(3), sqrt(3)]],
            [1, 0, -25, [-5, 5]],
            [1, 0, -10, [-sqrt(10), sqrt(10)]],
            [1, -5, 6, [2, 3]],
            [1, -8, 12, [2, 6]],
            [3, 1, -10, [-2, 5/3]],
            [2, -1, 0, [0, 1/2]],
            [3, 5/2, -3, [-3/2, 2/3]],
            [5, 11/2, -3, [-3/2, 2/5]],
            [5, -11/3, -4, [-3/5, 4/3]],
            [1, 1, -20, [-5, 4]],
            [1, -3, -18, [-3, 6]],
            [2, -5, -3, [-1/2, 3]],
        ];
    }

    /**
     * @testCase     quadratic returns the expected root for edge case where a = 0 and formula is not quadratic.
     * @dataProvider dataProviderForQuadraticAIsZero
     */
    public function testQuadraticAIsZero($a, $b, $c, $quadratic)
    {
        $this->assertEquals($quadratic, Algebra::quadratic($a, $b, $c), '', 0.00000001);
    }

    public function dataProviderForQuadraticAIsZero()
    {
        return [
            [0, -5, -3, [-3/5]],
            [0, 5, -3, [3/5]],
            [0, 12, 6, [-1/2]],
            [0, 3, 7, [-7/3]],
        ];
    }

    /**
     * @testCase     quadratic returns array of [NAN, NAN] if the discriminant is negative.
     * @dataProvider dataProviderForQuadraticNegativeDiscriminant
     */
    public function testQuadraticNegativeDiscriminant($a, $b, $c)
    {
        $roots = Algebra::quadratic($a, $b, $c);
        $this->assertInternalType('array', $roots);
        $this->assertNotEmpty($roots);
        $this->assertEquals(2, count($roots));
        foreach ($roots as $root) {
            $this->assertTrue(is_nan($root));
        }
    }

    public function dataProviderForQuadraticNegativeDiscriminant()
    {
        return [
            [10, 1, 1, [\NAN, \NAN]],
            [3, 4, 20, [\NAN, \NAN]],
        ];
    }

    /**
     * @testCase     quadratic returns array of Complex Number objects if the discriminant is negative.
     * @dataProvider dataProviderForQuadraticNegativeDiscriminantComplex
     */
    public function testQuadraticNegativeDiscriminantComplex($a, $b, $c, $expected)
    {
        $complex0 = new Number\Complex($expected[0][0], $expected[0][1]);
        $complex1 = new Number\Complex($expected[1][0], $expected[1][1]);
        $roots = Algebra::quadratic($a, $b, $c, true);
        $this->assertInternalType('array', $roots);
        $this->assertInstanceOf(Number\Complex::class, $roots[0]);
        $this->assertInstanceOf(Number\Complex::class, $roots[1]);
        $this->assertNotEmpty($roots);
        $this->assertEquals(2, count($roots));
        $this->assertTrue($roots[0]->equals($complex0));
        $this->assertTrue($roots[1]->equals($complex1));
    }

    public function dataProviderForQuadraticNegativeDiscriminantComplex()
    {
        return [
            [10, 1, 1, [[-.05, -1 * sqrt(39) / 20], [-.05, sqrt(39) / 20]]],
            [3, 4, 20, [[-2 / 3, -1 * sqrt(14) * 2 / 3], [-2 / 3, sqrt(14) * 2 / 3]]],
        ];
    }

    /**
     * @testCase     discriminant returns the expected value.
     * @dataProvider dataProviderForDiscriminant
     */
    public function testDiscriminant($a, $b, $c, $discriminant)
    {
        $this->assertEquals($discriminant, Algebra::discriminant($a, $b, $c), '', 0.00000001);
    }

    public function dataProviderForDiscriminant()
    {
        return [
            [2, 4, -4, 48],
            [1, -3, -4, 25],
            [1, 1, -4, 17],
            [1, 0, -4, 16],
            [6, 11, -35, 961],
            [1, 0, -48, 192],
            [1, -7, 0, 49],
            [10, 1, 1, -39],
            [3, 4, 20, -224],
        ];
    }

    /**
     * @testCase     cubic returns the expected three real roots when D < 0 or D = 0.
     * @dataProvider dataProviderForCubic
     * @param        int $a
     * @param        int $b
     * @param        int $c
     * @param        int $d
     * @param        array $cubic expected roots
     */
    public function testCubic($a, $b, $c, $d, $cubic)
    {
        $this->assertEquals($cubic, Algebra::cubic($a, $b, $c, $d), '', 0.00000001);
    }

    /**
     * Calculator used to generate and validate examples: http://www.1728.org/cubic.htm
     * Some examples from: http://www.mash.dept.shef.ac.uk/Resources/web-cubicequations-john.pdf
     * Some examples from: https://trans4mind.com/personal_development/mathematics/polynomials/cardanoMethodExamples.htm
     * @return array
     */
    public function dataProviderForCubic(): array
    {
        return [
            // D < 0: Three real roots. Nice even numbers.
            [1, 0, 0, 0, [0, 0, 0]],
            [1, -6, 11, -6, [3, 1, 2]],
            [1, -5, -2, 24, [4, -2, 3]],
            [1, 0, -7, -6, [3, -2, -1]],
            [1, -4, -9, 36, [4, -3, 3]],
            [1, 3, -6, -8, [2, -4, -1]],
            [1, 2, -21, 18, [3, -6, 1]],
            [1, -7, 4, 12, [6, -1, 2]],
            [1, 9, 26, 24, [-2, -4, -3]],
            [1, 0, -19, -30, [5, -3, -2]],
            [1, 2, -25, -50, [5, -5, -2]],
            [1, 6, 11, 6, [-1, -3, -2]],
            [1, 4, 1, -6, [1, -3, -2]],
            [2, 9, 3, -4, [0.5, -4, -1]],
            [2, -4, -22, 24, [4, -3, 1]],
            [2, 3, -11, -6, [2, -3, -1/2]],
            [2, -9, 1, 12, [4, -1, 1.5]],
            [2, -3, -5, 6, [2, -1.5, 1]],
            [3, -1, -10, 8, [4/3, -2, 1]],
            [6, -5, -17, 6, [2, -1.5, 1/3]],
            [45, 24, -7, -2, [1/3, -2/3, -0.2]],
            [-1, -1, 22, 40, [5, -4, -2]],
            [-1, 0, 19, -30, [3, -5, 2]],
            [-1, 6, -5, -12, [4, -1, 3]],

            // D < 0: Three real roots. Floats.
            [1, 6, 3, -5, [0.66966384064222, -5.24655136455856, -1.42311247608366]],
            [1, 4, 1, -5, [0.9122291784844, -3.198691243516, -1.7135379349684]],
            [1, -4, -6, 5, [5, -1.61803398874989, 0.61803398874989]],
            [1, -3, -1, 1, [3.21431974337754, -0.67513087056665, 0.46081112718911]],
            [1, -2, -6, 4, [3.41421356237309, -2, 0.58578643762691]],
            [1, 1, -16, 0, [3.53112887414927, -4.53112887414927, 0]],
            [2, -3, -22, 24, [3.62221312679243, -3.16796177749228,  1.04574865069985]],
            [2, -2, -22, 24, [3.2488979294409, -3.35109344639606,  1.10219551695516]],
            [1000, -1254, -496, 191, [1.49979930548345, -0.50033136443491, 0.25453205895145]],

            // D = 0: All real roots--at least two are equal. Nice even numbers.
            [1, -5, 8, -4, [2, 1, 2]],
            [1, -3, 3, -1, [1, 1, 1]],
            [1, 3, 3, 1, [-1, -1, -1]],
            [1, 2, -20, 24, [2, -6, 2]],
            [64, -48, 12, -1, [0.25, 0.25, 0.25]],
        ];
    }

    /**
     * @testCase     cubic returns the expected roots when D > 0: one root is real, 2 are complex conjugates.
     * @dataProvider dataProviderForCubicOneRealRoot
     * @param        int $a
     * @param        int $b
     * @param        int $c
     * @param        int $d
     * @param        array $cubic expected roots
     */
    public function testCubicOneRealRoot($a, $b, $c, $d, $real_root)
    {
        list($z₁, $z₂, $z₃) = Algebra::cubic($a, $b, $c, $d);
        $this->assertEquals($real_root, $z₁, '', 0.00000001);
        $this->assertNan($z₂);
        $this->assertNan($z₃);
    }

    /**
     * Calculator used to generate and validate examples: http://www.1728.org/cubic.htm
     * Some examples from: http://www.mash.dept.shef.ac.uk/Resources/web-cubicequations-john.pdf
     * @return array
     */
    public function dataProviderForCubicOneRealRoot(): array
    {
        return [
            // D > 0: one root is real, 2 are complex conjugates.
            [1, 1, 1, -3, 0.9999999999999984],
            [1, -6, -6, -7, 7],
            [1, 1, 4, -8, 1.202981258316938],
            [1, 2, 3, -4, 0.7760454350285383],
            [1, -2.7, 4.5, -6, 1.9641774065933375],
            [1, 3, 3, -2, 0.4422495703074083],
            [1, 2, 10, -20, 1.3688081078213727],
            [1, 1, 10, -3, 0.28921621924406943],
            [2, -3, -4, -35, 3.5000000000000027],
            [2, -5, 23, -10, 0.4744277602198689],
            [2, -6, 7, -1, 0.1648776515186341],
            [2, 0, 4, 1, -0.24283973258548086],
        ];
    }

    /**
     * @testCase     cubic returns the expected roots when D > 0: one root is real, 2 are complex conjugates.
     * @dataProvider dataProviderForCubicOneRealRootWithComplex
     * @param        int $a
     * @param        int $b
     * @param        int $c
     * @param        int $d
     * @param        array $cubic expected roots
     */
    public function testCubicOneRealRootWithComplex($a, $b, $c, $d, $roots)
    {
        $real_root = $roots[0];
        $complex0 = new Number\Complex($roots[1]['r'], $roots[1]['i']);
        $complex1 = new Number\Complex($roots[2]['r'], $roots[2]['i']);
        list($z₁, $z₂, $z₃) = Algebra::cubic($a, $b, $c, $d, true);
        $this->assertEquals($real_root, $z₁, '', 0.00000001);
        $this->assertInstanceOf(Number\Complex::class, $z₂);
        $this->assertInstanceOf(Number\Complex::class, $z₃);
        $this->assertTrue($z₂->equals($complex0), "Expecting $complex0 but saw $z₂");
        $this->assertTrue($z₃->equals($complex1), "Expecting $complex1 but saw $z₃");
    }

    public function dataProviderForCubicOneRealRootWithComplex(): array
    {
        return [
            // D > 0: one root is real, 2 are complex conjugates.
            [1, 0, 1, 0, [0, ['r'=>0, 'i'=>-1], ['r'=>0, 'i'=>1]]],
            [1, -1, 1, -1, [1, ['r'=>0, 'i'=>-1], ['r'=>0, 'i'=>1]]],
            
        ];
    }

    /**
     * @testCase     cubic with a₃ coefficient of z³ of 0 is the same as quadratic.
     * @dataProvider dataProviderForQuadratic
     * @param        number $b
     * @param        number $c
     * @param        number $d
     * @param        array  $quadratic
     */
    public function testCubicCubeCoefficientZeroSameAsQuadratic($b, $c, $d, $quadratic)
    {
        $a = 0;
        $this->assertEquals($quadratic, Algebra::cubic($a, $b, $c, $d), '', 0.00000001);
    }

    /**
     * @dataProvider dataProviderForQuartic
     * @param        int $a
     * @param        int $b
     * @param        int $c
     * @param        int $d
     * @param        int $d
     * @param        array $quartic expected roots
     */
    public function testQuartic($a, $b, $c, $d, $e, $quartic)
    {
        $this->assertEquals($quartic, Algebra::quartic($a, $b, $c, $d, $e), '', 0.00000001);
    }

    /**
     * @return array
     */
    public function dataProviderForQuartic(): array
    {
        return [
            [3, 6, -123, -126, 1080, [5, -6, 3, -4]],
            [1, -10, 35, -50, 24, [4, 1, 3, 2]],
            [1, -4, 6, -4, 1, [1, 1, 1, 1]],
            
            // Actually a cubic
            [0, 1, -6, 11, -6, [3, 1, 2]],
            
            // Zero Root
            [1, -6, 11, -6, 0, [0, 3, 1, 2]],
            
            // Biquadratic
            [1, 0, -5, 0, 4, [2, -2, 1, -1]],

            // Depressed quartic is biquadratic
            [1, 12, 49, 78, 40, [-1, -5, -2, -4]],

            // Depressed quartic and has 4 real roots.
            [1, 0, -25, 60, -36, [-6, 1, 2, 3]],
         ];
    }

    /**
     * @dataProvider dataProviderForQuarticTwoComplex
     * @param        int $a
     * @param        int $b
     * @param        int $c
     * @param        int $d
     * @param        int $d
     * @param        int $e
     * @param        array $quartic expected roots
     */
    public function testQuarticTwoComplex($a, $b, $c, $d, $e, $quartic)
    {
        list($z₁, $z₂, $z₃, $z₄) = Algebra::quartic($a, $b, $c, $d, $e);
        $this->assertEquals($quartic[0], floatval($z₁), '', 0.00000001);
        $this->assertEquals($quartic[1], floatval($z₂), '', 0.00000001);
        $this->assertNan($z₃, '');
        $this->assertNan($z₄, '');
        
        list($z₁, $z₂, $z₃, $z₄) = Algebra::quartic($a, $b, $c, $d, $e, true);
        $complex0 = new Number\Complex($quartic[2]['r'], $quartic[2]['i']);
        $complex1 = new Number\Complex($quartic[3]['r'], $quartic[3]['i']);
        $this->assertEquals($quartic[0], floatval($z₁), '', 0.00000001);
        $this->assertEquals($quartic[1], floatval($z₂), '', 0.00000001);
        $this->assertTrue($z₃->equals($complex0), "Expecting $complex0 but saw $z₃, complex conjugate is $z₄");
        $this->assertTrue($z₄->equals($complex1), "Expecting $complex1 but saw $z₄, complex conjugate is $z₃");
    }

    /**
     * @return array
     */
    public function dataProviderForQuarticTwoComplex(): array
    {
        return [
            // Two Complex Roots
            [1, -5, 10, -10, 4, [1, 2, ['r'=>1, 'i'=>-1], ['r'=>1, 'i'=>1]]],

            // And is a depressed quartic. (sum of roots=0)
            [1, 0, 5/8, -5/8, -51/256, [-.25, .75, ['r'=>-.25, 'i' => -1], ['r'=>-.25, 'i'=>1]]],
            [1, 0, -5, 10, -6, [-3, 1, ['r'=>1, 'i'=>-1], ['r'=>1, 'i'=>1]]],
            
            // Biquadratic with two complex roots
            [1, 0, -5, 0, -36, [3, -3, ['r'=>0, 'i'=>2], ['r'=>0, 'i'=>-2]]],
         ];
    }

    /**
     * @dataProvider dataProviderForQuarticFourComplex
     * @param        int $a
     * @param        int $b
     * @param        int $c
     * @param        int $d
     * @param        int $d
     * @param        int $e
     * @param        array $quartic expected roots
     */
    public function testQuarticFourComplex($a, $b, $c, $d, $e, $quartic)
    {
        list($z₁, $z₂, $z₃, $z₄) = Algebra::quartic($a, $b, $c, $d, $e);
        $this->assertNan($z₁);
        $this->assertNan($z₂);
        $this->assertNan($z₃);
        $this->assertNan($z₄);
        
        list($z₁, $z₂, $z₃, $z₄) = Algebra::quartic($a, $b, $c, $d, $e, true);
        $complex0 = new Number\Complex($quartic[0]['r'], $quartic[0]['i']);
        $complex1 = new Number\Complex($quartic[1]['r'], $quartic[1]['i']);
        $complex2 = new Number\Complex($quartic[2]['r'], $quartic[2]['i']);
        $complex3 = new Number\Complex($quartic[3]['r'], $quartic[3]['i']);
        $this->assertTrue($z₁->equals($complex0), "Expecting $complex0 but saw $z₁");
        $this->assertTrue($z₂->equals($complex1), "Expecting $complex1 but saw $z₂");
        $this->assertTrue($z₃->equals($complex2), "Expecting $complex2 but saw $z₃");
        $this->assertTrue($z₄->equals($complex3), "Expecting $complex3 but saw $z₄");
    }

    /**
     * @return array
     */
    public function dataProviderForQuarticFourComplex(): array
    {
        return [
            // Four Complex Roots
            [1, -6, 18, -24, 16, [['r'=>1, 'i'=>-1], ['r'=>1, 'i'=>1], ['r'=>2, 'i'=>-2], ['r'=>2, 'i'=>2]]],
            [1, 0, -3, 12, 40, [['r'=>-2, 'i'=>-1], ['r'=>-2, 'i'=>1], ['r'=>2, 'i'=>-2], ['r'=>2, 'i'=>2]]],

            // Biquadratic with four complex roots
            [1, 0, 13, 0, 36, [['r'=>0, 'i'=>2], ['r'=>0, 'i'=>-2], ['r'=>0, 'i'=>3], ['r'=>0, 'i'=>-3]]],
         ];
    }
}
