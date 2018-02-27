<?php
namespace MathPHP\Tests\Functions\Map;

use MathPHP\Functions\Map\Multi;
use MathPHP\Exception;

class MultiTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @dataProvider dataProviderForAddTwoArrays
     */
    public function testAddTwoArrays(array $xs, array $ys, array $sums)
    {
        $this->assertEquals($sums, Multi::add($xs, $ys));
    }

    public function dataProviderForAddTwoArrays()
    {
        return [
            [
                [1, 2, 3, 4],
                [2, 3, 4, 5],
                [3, 5, 7, 9],
            ],
            [
                [1, 2, 3, 4],
                [6, 6, 6, 6],
                [7, 8, 9, 10],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForAddMulti
     */
    public function testAddMulti(array $sums, array ...$arrays)
    {
        $this->assertEquals($sums, Multi::add(...$arrays));
    }

    public function dataProviderForAddMulti()
    {
        return [
            [
                [3, 5, 7, 9],
                [1, 2, 3, 4],
                [2, 3, 4, 5],
                
            ],
            [
                [7, 8, 9, 10],
                [1, 2, 3, 4],
                [6, 6, 6, 6],
                
            ],
            [
                [6, 7, 9, 10],
                [1, 2, 3, 4],
                [2, 2, 2, 2],
                [3, 3, 4, 4],
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForSubtractTwoArrays
     */
    public function testSubtractTwoArrays(array $xs, array $ys, array $differences)
    {
        $this->assertEquals($differences, Multi::subtract($xs, $ys));
    }

    public function dataProviderForSubtractTwoArrays()
    {
        return [
            [
                [1, 2, 3, 4],
                [2, 3, 4, 5],
                [-1, -1, -1, -1],
            ],
            [
                [1, 2, 3, 4],
                [6, 6, 6, 6],
                [-5, -4, -3, -2],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForSubtractMulti
     */
    public function testSubtractMulti(array $differences, array ...$arrays)
    {
        $this->assertEquals($differences, Multi::subtract(...$arrays));
    }

    public function dataProviderForSubtractMulti()
    {
        return [
            [
                [-1, -1, -1, -1],
                [1, 2, 3, 4],
                [2, 3, 4, 5],
                
            ],
            [
                [-5, -4, -3, -2],
                [1, 2, 3, 4],
                [6, 6, 6, 6],
                
            ],
            [
                [3, 3, 4, 4],
                [6, 7, 9, 10],
                [1, 2, 3, 4],
                [2, 2, 2, 2],
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForMultiplyTwoArrays
     */
    public function testMultiplyTwoArrays(array $xs, array $ys, array $products)
    {
        $this->assertEquals($products, Multi::multiply($xs, $ys));
    }

    public function dataProviderForMultiplyTwoArrays()
    {
        return [
            [
                [1, 2, 3, 4],
                [2, 3, 4, 5],
                [2, 6, 12, 20],
            ],
            [
                [1, 2, 3, 4],
                [6, 6, 6, 6],
                [6, 12, 18, 24],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForMultiplyMulti
     */
    public function testMultiplyMulti(array $differences, array ...$arrays)
    {
        $this->assertEquals($differences, Multi::multiply(...$arrays));
    }

    public function dataProviderForMultiplyMulti()
    {
        return [
            [
                [2, 6, 12, 20],
                [1, 2, 3, 4],
                [2, 3, 4, 5],
                
            ],
            [
                [6, 12, 18, 24],
                [1, 2, 3, 4],
                [6, 6, 6, 6],
                
            ],
            [
                [12, 28, 54, 80],
                [6, 7, 9, 10],
                [1, 2, 3, 4],
                [2, 2, 2, 2],
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForDivideTwoArrays
     */
    public function testDivideTwoArrays(array $xs, array $ys, array $quotients)
    {
        $this->assertEquals($quotients, Multi::divide($xs, $ys));
    }

    public function dataProviderForDivideTwoArrays()
    {
        return [
            [
                [5, 10, 15, 20],
                [5, 5, 5, 5],
                [1, 2, 3, 4],
            ],
            [
                [5, 10, 15, 20],
                [2.5, 20, 3, 4],
                [2, 0.5, 5, 5],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForDivideMulti
     */
    public function testDivideMulti(array $quotients, array ...$arrays)
    {
        $this->assertEquals($quotients, Multi::divide(...$arrays));
    }

    public function dataProviderForDivideMulti()
    {
        return [
            [
                [1, 2, 3, 4],
                [5, 10, 15, 20],
                [5, 5, 5, 5],
            ],
            [
                [2, 0.5, 5, 5],
                [5, 10, 15, 20],
                [2.5, 20, 3, 4],
            ],
            [
                [5, 20, 1, 8],
                [100, 80, 25, 64],
                [10, 2, 5, 2],
                [2, 2, 5, 4],
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForMaxTwoArrays
     */
    public function testMaxTwoArrays(array $xs, array $ys, array $maxes)
    {
        $this->assertEquals($maxes, Multi::max($xs, $ys));
    }

    public function dataProviderForMaxTwoArrays()
    {
        return [
            [
                [1, 5, 3, 6],
                [5, 5, 5, 5],
                [5, 5, 5, 6],
            ],
            [
                [5, 10, 15, 20],
                [2.5, 20, 3, 4],
                [5, 20, 15, 20],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForMaxMulti
     */
    public function testMaxMulti(array $maxes, array ...$arrays)
    {
        $this->assertEquals($maxes, Multi::max(...$arrays));
    }

    public function dataProviderForMaxMulti()
    {
        return [
            [
                [5, 10, 15, 20],
                [5, 10, 15, 20],
                [5, 5, 5, 5],
            ],
            [
                [5, 20, 15, 20],
                [5, 10, 15, 20],
                [2.5, 20, 3, 4],
            ],
            [
                [100, 80, 55, 664],
                [100, 80, 25, 64],
                [10, 2, 55, 2],
                [2, 2, 5, 664],
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForMin
     */
    public function testMin(array $xs, array $ys, array $maxes)
    {
        $this->assertEquals($maxes, Multi::min($xs, $ys));
    }

    public function dataProviderForMin()
    {
        return [
            [
                [1, 5, 3, 6],
                [5, 5, 5, 5],
                [1, 5, 3, 5],
            ],
            [
                [5, 10, 15, 20],
                [2.5, 20, 3, 4],
                [2.5, 10, 3, 4],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForMinMulti
     */
    public function testMinMulti(array $mins, array ...$arrays)
    {
        $this->assertEquals($mins, Multi::min(...$arrays));
    }

    public function dataProviderForMinMulti()
    {
        return [
            [
                [5, 5, 5, 5],
                [5, 10, 15, 20],
                [5, 5, 5, 5],
            ],
            [
                [2.5, 10, 3, 4],
                [5, 10, 15, 20],
                [2.5, 20, 3, 4],
            ],
            [
                [2, 2, 5, 2],
                [100, 80, 25, 64],
                [10, 2, 55, 2],
                [2, 2, 5, 664],
            ]
        ];
    }

    public function testCheckArrayLengthsException()
    {
        $xs = [1, 2, 3];
        $ys = [1, 2];

        $this->expectException(Exception\BadDataException::class);
        Multi::add($xs, $ys);
    }

    public function testCheckArrayLengthsExceptionOnlyOneArray()
    {
        $this->expectException(Exception\BadDataException::class);
        Multi::add([1,2]);
    }
}
