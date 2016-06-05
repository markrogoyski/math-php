<?php
namespace Math;

class AlgebraTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForGCD
     */
    public function testGCD(int $a, int $b, int $gcd)
    {
        $this->assertEquals($gcd, Algebra::gcd($a, $b));
    }

    public function dataProviderForGCD()
    {
        return [
            [0, 0, 0],
            [8, 0, 8],
            [0, 8, 8],
            [8, 12, 4],
            [12, 8, 4],
            [54, 24, 6],
            [24, 54, 6],
            [18, 84, 6],
            [84, 18, 6],
            [244, 343, 1],
            [343, 244, 1],
            [97, 577, 1],
            [577, 97, 1],
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