<?php
namespace Math\Sequence;

class BasicTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForArithmeticProgression
     */
    public function testArithmeticProgression(int $n, int $d, int $a₁, array $progression)
    {
        $this->assertEquals($progression, Basic::arithmeticProgression($n, $d, $a₁));
    }

    public function dataProviderForArithmeticProgression()
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
     * @dataProvider dataProviderForGeometricProgression
     */
    public function testGeometricProgression(int $n, $a, $r, array $progression)
    {
        $this->assertEquals($progression, Basic::geometricProgression($n, $a, $r));
    }

    public function dataProviderForGeometricProgression()
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

    public function testGeometricProgressionExceptionRIsZero()
    {
        $this->setExpectedException('\Exception');
        $r = 0;
        Basic::geometricProgression(10, 2, $r);
    }

    /**
     * @dataProvider dataProviderForSquareNumber
     */
    public function testSquareNumber(int $n, array $squares)
    {
        $this->assertEquals($squares, Basic::squareNumber($n));
    }

    public function dataProviderForSquareNumber()
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
     * @dataProvider dataProviderForCubicNumber
     */
    public function testCubicNumber(int $n, array $cubes)
    {
        $this->assertEquals($cubes, Basic::cubicNumber($n));
    }

    public function dataProviderForCubicNumber()
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
     * @dataProvider dataProviderForPowersOfTwo
     */
    public function testPowersOfTwo(int $n, array $powers)
    {
        $this->assertEquals($powers, Basic::powersOfTwo($n));
    }

    public function dataProviderForPowersOfTwo()
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
     * @dataProvider dataProviderForPowersOfTen
     */
    public function testPowersOfTen(int $n, array $powers)
    {
        $this->assertEquals($powers, Basic::powersOfTen($n));
    }

    public function dataProviderForPowersOfTen()
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
     * @dataProvider dataProviderForFactorial
     */
    public function testFactorial(int $n, array $powers)
    {
        $this->assertEquals($powers, Basic::factorial($n));
    }

    public function dataProviderForFactorial()
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
}
