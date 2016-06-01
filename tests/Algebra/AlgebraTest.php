<?php
namespace Math;

class AlgebraTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForGCD
     */
    public function testGCD($a, $b, $gcd)
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
    public function testLCM($a, $b, $lcm)
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
}