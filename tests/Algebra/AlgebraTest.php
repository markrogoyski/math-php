<?php
namespace MathPHP;

class AlgebraTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForGCD
     */
    public function testGCD(int $a, int $b, int $gcd, int $_, int $__)
    {
        $this->assertEquals($gcd, Algebra::gcd($a, $b));
    }

    /**
     * @dataProvider dataProviderForGCD
     */
    public function testExtendedGCD(int $a, int $b, int $gcd, int $alpha, int $beta)
    {
        $this->assertEquals([$gcd, $alpha, $beta], Algebra::extendedGCD($a, $b));
    }

    public function dataProviderForGCD()
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
     * @dataProvider dataProviderForLCM
     */
    public function testLCM(int $a, int $b, int $lcm)
    {
        $this->assertEquals($lcm, Algebra::lcm($a, $b));
    }

    public function dataProviderForLCM()
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
        ];
    }
}
