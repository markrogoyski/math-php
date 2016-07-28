<?php
namespace Math\Functions\Map;

class SingleTest extends \PHPUnit_Framework_TestCase
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
}