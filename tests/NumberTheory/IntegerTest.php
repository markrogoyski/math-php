<?php
namespace MathPHP\NumberTheory;

class IntegerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @testCase     isPerfectPower returns true if n is a perfect prime.
     * @dataProvider dataProviderForIsPerfectPower
     * @param        int $n
     */
    public function testIsPerfectPower(int $n)
    {
        $this->assertTrue(Integer::isPerfectPower($n));
    }

    /**
     * A001597 Perfect powers: m^k where m > 0 and k >= 2.
     * https://oeis.org/A001597
     * @return array
     */
    public function dataProviderForIsPerfectPower(): array
    {
        return [
            [4],
            [8],
            [9],
            [16],
            [16],
            [25],
            [27],
            [32],
            [36],
            [49],
            [64],
            [64],
            [64],
            [81],
            [81],
            [100],
            [121],
            [125],
            [128],
            [144],
            [169],
            [196],
            [216],
            [225],
            [243],
            [256],
            [256],
            [256],
            [289],
            [324],
            [343],
            [361],
            [400],
            [441],
            [484],
            [512],
            [512],
            [529],
            [576],
            [625],
            [625],
            [676],
            [729],
            [729],
            [729],
            [784],
            [841],
            [900],
            [961],
            [1000],
            [1024],
            [1024],
            [1024],
            [1089],
        ];
    }

    /**
     * @testCase     isPerfectPower returns false if n is not a perfect prime.
     * @dataProvider dataProviderForIsNotPerfectPower
     * @param        int $n
     */
    public function testIsNotPerfectPower(int $n)
    {
        $this->assertFalse(Integer::isPerfectPower($n));
    }

    /**
     * A007916 Numbers that are not perfect powers.
     * https://oeis.org/A007916
     * @return array
     */
    public function dataProviderForIsNotPerfectPower(): array
    {
        return [
            [2],
            [3],
            [5],
            [6],
            [7],
            [10],
            [11],
            [12],
            [13],
            [14],
            [15],
            [17],
            [18],
            [19],
            [20],
            [21],
            [22],
            [23],
            [24],
            [26],
            [28],
            [29],
            [30],
            [31],
            [33],
            [34],
            [35],
            [37],
            [38],
            [39],
            [40],
            [41],
            [42],
            [43],
            [44],
            [45],
            [46],
            [47],
            [48],
            [50],
            [51],
            [52],
            [53],
            [54],
            [55],
            [56],
            [57],
            [58],
            [59],
            [60],
            [61],
            [62],
            [63],
            [65],
            [66],
            [67],
            [68],
            [69],
            [70],
            [71],
            [72],
            [73],
            [74],
            [75],
            [76],
            [77],
            [78],
            [79],
            [80],
            [82],
            [83],
        ];
    }

    /**
     * @testCase     perfectPower returns m and k for n such that mᵏ = n if n is a perfect power.
     * @dataProvider dataProviderForPerfectPower
     * @param        int $n
     * @param        int $m
     * @param        int $k
     */
    public function testPerfectPower(int $n, int $expected_m, int $expected_k)
    {
        list($m, $k) = Integer::perfectPower($n);
        $this->assertEquals($expected_m, $m);
        $this->assertEquals($expected_k, $k);
    }

    /**
     * Perfect powers: m^k where m > 0 and k >= 2.
     * @return array
     */
    public function dataProviderForPerfectPower(): array
    {
        return [
            [4, 2, 2],
            [8, 2, 3],
            [9, 3, 2],
            [16, 2, 4],
            [25, 5, 2],
            [27, 3, 3],
            [32, 2, 5],
            [36, 6, 2],
            [49, 7, 2],
            [64, 2, 6],
            [81, 3, 4],
            [100, 10, 2],
            [121, 11, 2],
            [125, 5, 3],
            [128, 2, 7],
            [144, 12, 2],
            [169, 13, 2],
            [196, 14, 2],
            [216, 6, 3],
            [225, 15, 2],
            [243, 3, 5],
            [256, 2, 8],
            [1000, 10, 3],
            [1024, 2, 10],
        ];
    }

    /**
     * @testCase     perfectPower returns a non-empty array comtaining numeric m and k both > 1 if n is a perfect power.
     * @dataProvider dataProviderForIsPerfectPower
     * @param        int $n
     */
    public function testPerfectPowerArray(int $n)
    {
        $perfect_power = Integer::perfectPower($n);
        $this->assertNotEmpty($perfect_power);

        $m = array_shift($perfect_power);
        $k = array_shift($perfect_power);
        $this->assertTrue(is_numeric($m));
        $this->assertTrue(is_numeric($k));
        $this->assertGreaterThan(1, $m);
        $this->assertGreaterThan(1, $k);
    }

    /**
     * @testCase     perfectPower returns an empty array if n is not a perfect power.
     * @dataProvider dataProviderForIsNotPerfectPower
     * @param        int $n
     */
    public function testEmptyPerfectPower(int $n)
    {
        $this->assertEmpty(Integer::perfectPower($n));
    }
}
