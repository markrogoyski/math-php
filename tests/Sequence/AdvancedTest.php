<?php
namespace Math\Sequence;

class AdvancedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForFibonacci
     */
    public function testFibonacci(int $n, array $fibonacci)
    {
        $this->assertEquals($fibonacci, Advanced::fibonacci($n));
    }

    public function dataProviderForFibonacci()
    {
        return [
            [-1, []],
            [0, []],
            [1, [0]],
            [2, [0, 1]],
            [3, [0, 1, 1]],
            [4, [0, 1, 1, 2]],
            [5, [0, 1, 1, 2, 3]],
            [6, [0, 1, 1, 2, 3, 5]],
            [13, [0,1,1,2,3,5,8,13,21,34,55,89,144]],
        ];
    }

    /**
     * @dataProvider dataProviderForLucasNumber
     */
    public function testLucasNumber(int $n, array $lucas)
    {
        $this->assertEquals($lucas, Advanced::lucasNumber($n));
    }

    public function dataProviderForLucasNumber()
    {
        return [
            [-1, []],
            [0, []],
            [1, [2]],
            [2, [2, 1]],
            [3, [2, 1, 3]],
            [4, [2, 1, 3, 4]],
            [5, [2, 1, 3, 4, 7]],
            [6, [2, 1, 3, 4, 7, 11]],
            [11, [2,1,3,4,7,11,18,29,47,76,123]],
        ];
    }

    /**
     * @dataProvider dataProviderForPellNumber
     */
    public function testPellNumber(int $n, array $pell)
    {
        $this->assertEquals($pell, Advanced::pellNumber($n));
    }

    public function dataProviderForPellNumber()
    {
        return [
            [-1, []],
            [0, []],
            [1, [0]],
            [2, [0, 1]],
            [3, [0, 1, 2]],
            [4, [0, 1, 2, 5]],
            [5, [0, 1, 2, 5, 12]],
            [6, [0, 1, 2, 5, 12, 29]],
            [13, [0,1,2,5,12,29,70,169,408,985,2378,5741,13860]],
        ];
    }

    /**
     * @dataProvider dataProviderForTriangularNumber
     */
    public function testTriangularNumber(int $n, array $triangular)
    {
        $this->assertEquals($triangular, Advanced::triangularNumber($n));
    }

    public function dataProviderForTriangularNumber()
    {
        return [
            [-1, []],
            [0, []],
            [1, [1 => 1]],
            [2, [1 => 1, 3]],
            [3, [1 => 1, 3, 6]],
            [4, [1 => 1, 3, 6, 10]],
            [5, [1 => 1, 3, 6, 10, 15]],
            [6, [1 => 1, 3, 6, 10, 15, 21]],
            [28, [1 => 1,3,6,10,15,21,28,36,45,55,66,78,91,105,120,136,153,171,190,210,231,253,276,300,325,351,378,406]],
        ];
    }

    /**
     * @dataProvider dataProviderForPentagonalNumber
     */
    public function testPentagonalNumber(int $n, array $pentagonal)
    {
        $this->assertEquals($pentagonal, Advanced::pentagonalNumber($n));
    }

    public function dataProviderForPentagonalNumber()
    {
        return [
            [-1, []],
            [0, []],
            [1, [1 => 1]],
            [2, [1 => 1, 5]],
            [3, [1 => 1, 5, 12]],
            [4, [1 => 1, 5, 12, 22]],
            [5, [1 => 1, 5, 12, 22, 35]],
            [6, [1 => 1, 5, 12, 22, 35, 51]],
            [26, [1 => 1,5,12,22,35,51,70,92,117,145,176,210,247,287,330,376,425,477,532,590,651,715,782,852,925,1001]],
        ];
    }

    /**
     * @dataProvider dataProviderForHexagonalNumber
     */
    public function testHexagonalNumber(int $n, array $hexagonal)
    {
        $this->assertEquals($hexagonal, Advanced::hexagonalNumber($n));
    }

    public function dataProviderForHexagonalNumber()
    {
        return [
            [-1, []],
            [0, []],
            [1, [1 => 1]],
            [2, [1 => 1, 6]],
            [3, [1 => 1, 6, 15]],
            [4, [1 => 1, 6, 15, 28]],
            [5, [1 => 1, 6, 15, 28, 45]],
            [6, [1 => 1, 6, 15, 28, 45, 66]],
            [22, [1 => 1,6,15,28,45,66,91,120,153,190,231,276,325,378,435,496,561,630,703,780,861,946]],
        ];
    }

    /**
     * @dataProvider dataProviderForHeptagonalNumber
     */
    public function testHeptagonalNumber(int $n, array $heptagonal)
    {
        $this->assertEquals($heptagonal, Advanced::heptagonalNumber($n));
    }

    public function dataProviderForHeptagonalNumber()
    {
        return [
            [-1, []],
            [0, []],
            [1, [1 => 1]],
            [2, [1 => 1, 7]],
            [3, [1 => 1, 7, 18]],
            [4, [1 => 1, 7, 18, 34]],
            [5, [1 => 1, 7, 18, 34, 55]],
            [6, [1 => 1, 7, 18, 34, 55, 81]],
            [27, [1 => 1,7,18,34,55,81,112,148,189,235,286,342,403,469,540,616,697,783,874,970,1071,1177,1288,1404,1525,1651,1782]],
        ];
    }
}
