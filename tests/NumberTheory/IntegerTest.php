<?php
namespace MathPHP\Tests\NumberTheory;

use MathPHP\NumberTheory\Integer;
use MathPHP\Exception;

class IntegerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     isPerfectNumber
     * @dataProvider dataProviderForPerfectNumbers
     * @param        int $n
     */
    public function testIsPerfectNumber(int $n)
    {
        // When
        $isPerfectNumber = Integer::isPerfectNumber($n);

        // Then
        $this->assertTrue($isPerfectNumber);
    }

    /**
     * @see    https://oeis.org/A000396
     * @return array
     */
    public function dataProviderForPerfectNumbers(): array
    {
        return [
            [6],
            [28],
            [496],
            [8128],
            [33550336],
            [8589869056],
            [137438691328],
        ];
    }

    /**
     * @testCase     isPerfectNumber is not a perfect number
     * @dataProvider dataProviderForNonPerfectNumbers
     * @param        int $n
     */
    public function testIsNotPerfectNumber(int $n)
    {
        // When
        $isPerfectNumber = Integer::isPerfectNumber($n);

        // Then
        $this->assertFalse($isPerfectNumber);
    }

    /**
     * @return array
     */
    public function dataProviderForNonPerfectNumbers(): array
    {
        return [
            [-1],
            [0],
            [1],
            [2],
            [3],
            [4],
            [5],
            [7],
            [8],
            [9],
            [10],
            [26],
            [498],
            [8124],
            [23550336],
            [2589869056],
            [133438691328],
        ];
    }

    /**
     * @testCase     isPerfectPower returns true if n is a perfect prime.
     * @dataProvider dataProviderForIsPerfectPower
     * @param        int $n
     */
    public function testIsPerfectPower(int $n)
    {
        // When
        $isPerfectPower = Integer::isPerfectPower($n);

        // Then
        $this->assertTrue($isPerfectPower);
    }

    /**
     * A001597 Perfect powers: m^k where m > 0 and k >= 2.
     * @see    https://oeis.org/A001597
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
        // When
        $isPerfectPower = Integer::isPerfectPower($n);

        // Then
        $this->assertFalse($isPerfectPower);
    }

    /**
     * A007916 Numbers that are not perfect powers.
     * @see    https://oeis.org/A007916
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
     * @param        int $expected_m
     * @param        int $expected_k
     */
    public function testPerfectPower(int $n, int $expected_m, int $expected_k)
    {
        // When
        list($m, $k) = Integer::perfectPower($n);

        // Then
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
        // When
        $perfect_power = Integer::perfectPower($n);

        // Then
        $this->assertNotEmpty($perfect_power);

        // And
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
        // When
        $empty = Integer::perfectPower($n);

        // Then
        $this->assertEmpty($empty);
    }

    /**
     * @testCase     primeFactorization returns an array of the prime factors of an integer n.
     * @dataProvider dataProviderForPrimeFactorization
     * @param        int   $n
     * @param        array $expected_actors
     * @throws       \Exception
     */
    public function testPrimeFactorization(int $n, array $expected_actors)
    {
        // When
        $factors = Integer::primeFactorization($n);

        // Then
        $this->assertEquals($expected_actors, $factors);
    }

    /**
     * @return array
     */
    public function dataProviderForPrimeFactorization(): array
    {
        return [
            [2, [2]],
            [3, [3]],
            [4, [2, 2]],
            [5, [5]],
            [6, [2, 3]],
            [7, [7]],
            [8, [2, 2, 2]],
            [9, [3, 3]],
            [10, [2, 5]],
            [11, [11]],
            [12, [2, 2, 3]],
            [13, [13]],
            [14, [2, 7]],
            [15, [3, 5]],
            [16, [2, 2, 2, 2]],
            [17, [17]],
            [18, [2, 3, 3]],
            [19, [19]],
            [20, [2, 2, 5]],
            [48, [2, 2, 2, 2, 3]],
            [99, [3, 3, 11]],
            [100, [2, 2, 5, 5]],
            [101, [101]],
            [111, [3, 37]],
            [147, [3, 7, 7]],
            [200, [2, 2, 2, 5, 5]],
            [5555, [5, 11, 101]],
            [8463, [3, 7, 13, 31]],
            [12345, [3, 5, 823]],
            [45123, [3, 13, 13, 89]],
            [99999, [3, 3, 41, 271]],
            [5465432, [2, 2, 2, 7, 17, 5741]],
            [25794349, [7, 619, 5953]],
            [87534987, [3, 23, 1268623]],
            [123456789, [3, 3, 3607, 3803]],
            [8654893156, [2, 2, 7, 13, 157, 269, 563]],
            [2*3*5*7, [2, 3, 5, 7]],
            [2*2*2*5*7*7*7*13, [2, 2, 2, 5, 7, 7, 7, 13]],
        ];
    }

    /**
     * @testCase     primeFactorization throws an OutOfBoundsException if n is < 2.
     * @dataProvider dataProviderForPrimeFactorizationOutOfBoundsException
     * @param        int $n
     * @throws       \Exception
     */
    public function testPrimeFactorizationOutOfBoundsException(int $n)
    {
        // Then
        $this->expectException(Exception\OutOfBoundsException::class);

        // When
        Integer::primeFactorization($n);
    }

    /**
     * @return array
     */
    public function dataProviderForPrimeFactorizationOutOfBoundsException(): array
    {
        return [
            [1],
            [0],
            [-1],
            [-2],
            [-100],
            [-98352299832],
        ];
    }

    /**
     * @testCase     coprime returns true if a and b are coprime
     * @dataProvider dataProviderForCoprime
     * @param        int $a
     * @param        int $b
     */
    public function testCoprime(int $a, int $b)
    {
        // When
        $coprime = Integer::coprime($a, $b);

        // Then
        $this->assertTrue($coprime);
    }

    /**
     * @return array
     */
    public function dataProviderForCoprime(): array
    {
        return [
            [1, 0],
            [-1, 1],
            [1, 2],
            [1, 3],
            [1, 4],
            [1, 5],
            [1, 6],
            [1, 7],
            [1, 8],
            [1, 9],
            [1, 10],
            [1, 20],
            [1, 30],
            [1, 100],
            [2, 3],
            [2, 5],
            [2, 7],
            [2, 9],
            [2, 11],
            [2, 13],
            [2, 15],
            [2, 17],
            [2, 19],
            [2, 21],
            [2, 23],
            [2, 25],
            [2, 27],
            [2, 29],
            [3, 4],
            [3, 5],
            [3, 7],
            [3, 8],
            [3, 10],
            [3, 11],
            [3, 13],
            [3, 14],
            [3, 16],
            [4, 3],
            [4, 5],
            [4, 7],
            [4, 17],
            [4, 21],
            [4, 35],
            [5, 6],
            [5, 7],
            [5, 8],
            [5, 9],
            [5, 11],
            [5, 12],
            [5, 13],
            [5, 14],
            [5, 16],
            [5, 27],
            [6, 7],
            [6, 11],
            [6, 13],
            [6, 17],
            [6, 29],
            [6, 23],
            [6, 25],
            [6, 29],
            [19, 20],
            [20, 21],
            [23, 24],
            [23, 25],
            [27, 16],
            [28, 29],
            [29, 30],
        ];
    }

    /**
     * @testCase     coprime returns false if a and b are not coprime
     * @dataProvider dataProviderForNotCoprime
     * @param        int $a
     * @param        int $b
     */
    public function testNotCoprime(int $a, int $b)
    {
        // When
        $coprime = Integer::coprime($a, $b);

        // Then
        $this->assertFalse($coprime);
    }

    /**
     * @return array
     */
    public function dataProviderForNotCoprime(): array
    {
        return [
            [2, 4],
            [2, 6],
            [2, 8],
            [2, 10],
            [2, 12],
            [2, 14],
            [2, 16],
            [2, 18],
            [2, 20],
            [2, 22],
            [2, 24],
            [2, 26],
            [2, 28],
            [2, 30],
            [3, 6],
            [3, 9],
            [3, 12],
            [3, 15],
            [4, 8],
            [4, 12],
            [4, 20],
            [4, 22],
            [4, 24],
            [4, 30],
            [5, 10],
            [5, 15],
            [5, 20],
            [5, 25],
            [5, 30],
            [5, 50],
            [5, 100],
            [5, 200],
            [5, 225],
            [5, 555],
            [6, 12],
            [6, 14],
            [6, 16],
            [6, 18],
            [6, 26],
            [6, 28],
            [6, 30],
            [6, 32],
            [12, 21],
            [18, 20],
            [20, 22],
            [21, 24],
        ];
    }

    /**
     * @testCase isOdd returns true for an odd number
     */
    public function testIsOdd()
    {
        foreach (range(-11, 101, 2) as $x) {
            // When
            $isOdd = Integer::isOdd($x);

            // Then
            $this->assertTrue($isOdd);
        }
    }

    /**
     * @testCase isOdd returns false for an even number
     */
    public function testIsNotOdd()
    {
        foreach (range(-10, 100, 2) as $x) {
            // When
            $isOdd = Integer::isOdd($x);

            // Then
            $this->assertFalse($isOdd);
        }
    }

    /**
     * @testCase isEven returns true for an even number
     */
    public function testIsEven()
    {
        foreach (range(-10, 100, 2) as $x) {
            // When
            $isEven = Integer::isEven($x);

            // Then
            $this->assertTrue($isEven);
        }
    }

    /**
     * @testCase isEven returns false for an odd number
     */
    public function testIsNotEven()
    {
        foreach (range(-11, 101, 2) as $x) {
            // When
            $isEven = Integer::isEven($x);

            // Then
            $this->assertFalse($isEven);
        }
    }
}
