<?php
namespace MathPHP\Tests\Sequence;

use MathPHP\Sequence\Basic;
use MathPHP\Exception;

class BasicTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     arithmeticProgression produces the expected sequence
     * @dataProvider dataProviderForArithmeticProgression
     */
    public function testArithmeticProgression(int $n, int $d, int $a₁, array $progression)
    {
        $this->assertEquals($progression, Basic::arithmeticProgression($n, $d, $a₁));
    }

    public function dataProviderForArithmeticProgression(): array
    {
        return [
            [-1, 2, 1, []],
            [0, 2, 1, []],
            [1, 2, 1, [1 => 1]],
            [10, 2, 0, [1 => 0, 2, 4, 6, 8, 10, 12, 14, 16, 18]],
            [10, 2, 1, [1 => 1, 3, 5, 7, 9, 11, 13, 15, 17, 19]],
            [10, 3, 0, [1 => 0, 3, 6, 9, 12, 15, 18, 21, 24, 27]],
            [10, 3, 1, [1 => 1, 4, 7, 10, 13, 16, 19, 22, 25, 28]],
        ];
    }

    /**
     * @testCase     geometricProgression produces the expected sequence
     * @dataProvider dataProviderForGeometricProgression
     */
    public function testGeometricProgression(int $n, $a, $r, array $progression)
    {
        $this->assertEquals($progression, Basic::geometricProgression($n, $a, $r));
    }

    public function dataProviderForGeometricProgression(): array
    {
        return [
            [-1, 2, 2, []],
            [0, 2, 2, []],
            [4, 2, 3, [2, 6, 18, 54]],
            [6, 1, -3, [1, -3, 9, -27, 81, -243]],
            [9, 1, 2, [1, 2, 4, 8, 16, 32, 64, 128, 256]],
            [6, 10, 3, [10, 30, 90, 270, 810, 2430]],
            [5, 4, 0.5, [4, 2, 1, 0.5, 0.25]],
        ];
    }

    /**
     * @testCase geometricProgression throws a BadParameterException when R is zero
     */
    public function testGeometricProgressionExceptionRIsZero()
    {
        $this->expectException(Exception\BadParameterException::class);
        $r = 0;
        Basic::geometricProgression(10, 2, $r);
    }

    /**
     * @testCase     squareNumber produces the expected sequence
     * @dataProvider dataProviderForSquareNumber
     */
    public function testSquareNumber(int $n, array $squares)
    {
        $this->assertEquals($squares, Basic::squareNumber($n));
    }

    public function dataProviderForSquareNumber(): array
    {
        return [
            [-1, []],
            [0, []],
            [1, [0]],
            [2, [0, 1]],
            [3, [0, 1, 4]],
            [20, [0,1,4,9,16,25,36,49,64,81,100,121,144,169,196,225,256,289,324,361]],
        ];
    }

    /**
     * @testCase     cubicNumber produces the expected sequence
     * @dataProvider dataProviderForCubicNumber
     */
    public function testCubicNumber(int $n, array $cubes)
    {
        $this->assertEquals($cubes, Basic::cubicNumber($n));
    }

    public function dataProviderForCubicNumber(): array
    {
        return [
            [-1, []],
            [0, []],
            [1, [0]],
            [2, [0, 1]],
            [3, [0, 1, 8]],
            [20, [0,1,8,27,64,125,216,343,512,729,1000,1331,1728,2197,2744,3375,4096,4913,5832,6859]],
        ];
    }

    /**
     * @testCase     powersOfTwo produces the expected sequence
     * @dataProvider dataProviderForPowersOfTwo
     */
    public function testPowersOfTwo(int $n, array $powers)
    {
        $this->assertEquals($powers, Basic::powersOfTwo($n));
    }

    public function dataProviderForPowersOfTwo(): array
    {
        return [
            [-1, []],
            [0, []],
            [1, [1]],
            [2, [1, 2]],
            [3, [1, 2, 4]],
            [20, [1,2,4,8,16,32,64,128,256,512,1024,2048,4096,8192,16384,32768,65536,131072,262144,524288]],
        ];
    }

    /**
     * @testCase     powersOfTen produces the expected sequence
     * @dataProvider dataProviderForPowersOfTen
     */
    public function testPowersOfTen(int $n, array $powers)
    {
        $this->assertEquals($powers, Basic::powersOfTen($n));
    }

    public function dataProviderForPowersOfTen(): array
    {
        return [
            [-1, []],
            [0, []],
            [1, [1]],
            [2, [1, 10]],
            [3, [1, 10, 100]],
            [10, [1,10,100,1000,10000,100000,1000000,10000000,100000000,1000000000]],
        ];
    }

    /**
     * @testCase     factorial produces the expected sequence
     * @dataProvider dataProviderForFactorial
     */
    public function testFactorial(int $n, array $powers)
    {
        $this->assertEquals($powers, Basic::factorial($n));
    }

    public function dataProviderForFactorial(): array
    {
        return [
            [-1, []],
            [0, []],
            [1, [1]],
            [2, [1, 1]],
            [3, [1, 1, 2]],
            [10, [1,1,2,6,24,120,720,5040,40320,362880]],
        ];
    }

    /**
     * @testCase     digitSum produces the expected sequence
     * @dataProvider dataProviderForDigitSum
     */
    public function testDigitSum(int $n, array $digit_sums)
    {
        $this->assertEquals($digit_sums, Basic::digitSum($n));
    }

    public function dataProviderForDigitSum(): array
    {
        return [
            [0, []],
            [1, [0]],
            [2, [0, 1]],
            [3, [0, 1, 2]],
            [4, [0, 1, 2, 3]],
            [5, [0, 1, 2, 3, 4]],
            [6, [0, 1, 2, 3, 4, 5]],
            [7, [0, 1, 2, 3, 4, 5, 6]],
            [8, [0, 1, 2, 3, 4, 5, 6, 7]],
            [9, [0, 1, 2, 3, 4, 5, 6, 7, 8]],
            [10, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]],
            [11, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1]],
            [12, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2]],
            [88, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 8, 9, 10, 11, 12, 13, 14, 15]],
        ];
    }

    /**
     * @testCase     digitalRoot produces the expected sequence
     * @dataProvider dataProviderForDigitalRoot
     */
    public function testDigitalRoot(int $n, array $digital_roots)
    {
        $this->assertEquals($digital_roots, Basic::digitalRoot($n));
    }

    public function dataProviderForDigitalRoot(): array
    {
        return [
            [0, []],
            [1, [0]],
            [2, [0, 1]],
            [3, [0, 1, 2]],
            [4, [0, 1, 2, 3]],
            [5, [0, 1, 2, 3, 4]],
            [6, [0, 1, 2, 3, 4, 5]],
            [7, [0, 1, 2, 3, 4, 5, 6]],
            [8, [0, 1, 2, 3, 4, 5, 6, 7]],
            [9, [0, 1, 2, 3, 4, 5, 6, 7, 8]],
            [10, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]],
            [11, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1]],
            [12, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2]],
            [105, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5, 6, 7, 8, 9, 1, 2, 3, 4, 5]],
        ];
    }
}
