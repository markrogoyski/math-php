<?php
namespace MathPHP\Tests\Functions\Map;

use MathPHP\Functions\Map\Single;

class SingleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForSquare
     */
    public function testSquare(array $xs, array $squares)
    {
        $this->assertEquals($squares, Single::square($xs));
    }

    public function dataProviderForSquare()
    {
        return [
            [
                [1, 2, 3, 4],
                [1, 4, 9, 16],
            ],
            [
                [7, 8, 9, 10],
                [49, 64, 81, 100],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForCube
     */
    public function testCube(array $xs, array $cubes)
    {
        $this->assertEquals($cubes, Single::cube($xs));
    }

    public function dataProviderForCube()
    {
        return [
            [
                [1, 2, 3, 4],
                [1, 8, 27, 64],
            ],
            [
                [7, 8, 9, 10],
                [343, 512, 729, 1000],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForPow
     */
    public function testPow(array $xs, $n, array $pows)
    {
        $this->assertEquals($pows, Single::pow($xs, $n));
    }

    public function dataProviderForPow()
    {
        return [
            [
                [1, 2, 3, 4], 5,
                [1, 32, 243, 1024],
            ],
            [
                [7, 8, 9, 10], 4,
                [2401, 4096, 6561, 10000],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForSqrt
     */
    public function testSqrt(array $xs, array $sqrts)
    {
        $this->assertEquals($sqrts, Single::sqrt($xs));
    }

    public function dataProviderForSqrt()
    {
        return [
            [
                [4, 9, 16, 25],
                [2, 3, 4, 5],
            ],
            [
                [64, 81, 100, 144],
                [8, 9, 10, 12],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForAbs
     */
    public function testAbs(array $xs, array $abs)
    {
        $this->assertEquals($abs, Single::abs($xs));
    }

    public function dataProviderForAbs()
    {
        return [
            [
                [1, 2, 3, 4],
                [1, 2, 3, 4],
            ],
            [
                [1, -2, 3, -4],
                [1, 2, 3, 4],
            ],
            [
                [-1, -2, -3, -4],
                [1, 2, 3, 4],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForAdd
     */
    public function testAdd(array $xs, $k, array $sums)
    {
        $this->assertEquals($sums, Single::add($xs, $k));
    }

    public function dataProviderForAdd()
    {
        return [
            [ [1, 2, 3, 4, 5], 4, [5, 6, 7, 8, 9] ],
            [ [5, 7, 23, 5, 2], 9.1, [14.1, 16.1, 32.1, 14.1, 11.1] ],
        ];
    }

    /**
     * @dataProvider dataProviderForSubtract
     */
    public function testSubtract(array $xs, $k, array $differences)
    {
        $this->assertEquals($differences, Single::subtract($xs, $k));
    }

    public function dataProviderForSubtract()
    {
        return [
            [ [1, 2, 3, 4, 5], 1, [0, 1, 2, 3, 4] ],
            [ [5, 7, 23, 5, 2], 3, [2, 4, 20, 2, -1] ],
        ];
    }

    /**
     * @dataProvider dataProviderForMultiply
     */
    public function testMultiply(array $xs, $k, array $products)
    {
        $this->assertEquals($products, Single::multiply($xs, $k));
    }

    public function dataProviderForMultiply()
    {
        return [
            [ [1, 2, 3, 4, 5], 4, [4, 8, 12, 16, 20] ],
            [ [5, 7, 23, 5, 2], 3, [15, 21, 69, 15, 6] ],
        ];
    }

    /**
     * @dataProvider dataProviderForDivide
     */
    public function testDivide(array $xs, $k, array $quotients)
    {
        $this->assertEquals($quotients, Single::divide($xs, $k));
    }

    public function dataProviderForDivide()
    {
        return [
            [ [1, 2, 3, 4, 5], 2, [0.5, 1, 1.5, 2, 2.5] ],
            [ [5, 10, 15, 20, 25], 5, [1, 2, 3, 4, 5] ],
        ];
    }

    /**
     * @dataProvider dataProviderForMax
     */
    public function testMax(array $xs, $value, array $maxes)
    {
        $this->assertEquals($maxes, Single::max($xs, $value));
    }

    public function dataProviderForMax()
    {
        return [
            [[1, 2, 3, 4, 5], 0, [1, 2, 3, 4, 5]],
            [[1, 2, 3, 4, 5], 1, [1, 2, 3, 4, 5]],
            [[1, 2, 3, 4, 5], 3, [3, 3, 3, 4, 5]],
            [[1, 2, 3, 4, 5], 6, [6, 6, 6, 6, 6]],
            [[1, 2, 3, 4, 5], 9, [9, 9, 9, 9, 9]],
            [[1, 2, 3, 4, 5], 3.4, [3.4, 3.4, 3.4, 4, 5]],
            [[1, 2, 3, 4, 5], 6.7, [6.7, 6.7, 6.7, 6.7, 6.7]],
        ];
    }

    /**
     * @dataProvider dataProviderForMin
     */
    public function testMin(array $xs, $value, array $mins)
    {
        $this->assertEquals($mins, Single::min($xs, $value));
    }

    public function dataProviderForMin()
    {
        return [
            [[1, 2, 3, 4, 5], 0, [0, 0, 0, 0, 0]],
            [[1, 2, 3, 4, 5], 1, [1, 1, 1, 1, 1]],
            [[1, 2, 3, 4, 5], 3, [1, 2, 3, 3, 3]],
            [[1, 2, 3, 4, 5], 6, [1, 2, 3, 4, 5]],
            [[1, 2, 3, 4, 5], 9, [1, 2, 3, 4, 5]],
            [[1, 2, 3, 4, 5], 3.4, [1, 2, 3, 3.4, 3.4]],
            [[1, 2, 3, 4, 5], 6.7, [1, 2, 3, 4, 5]],
        ];
    }
}
