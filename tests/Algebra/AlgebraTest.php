<?php
namespace Math;

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
}
