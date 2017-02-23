<?php
namespace MathPHP\Sequence;

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

    /**
     * @dataProvider dataProviderForLookAndSay
     */
    public function testLookAndSay(int $n, array $look_and_say)
    {
        $this->assertEquals($look_and_say, Advanced::lookAndSay($n));
    }

    public function dataProviderForLookAndSay()
    {
        return [
            [0,  []],
            [1,  [1 => '1']],
            [2,  [1 => '1', '11']],
            [3,  [1 => '1', '11', '21']],
            [4,  [1 => '1', '11', '21', '1211']],
            [5,  [1 => '1', '11', '21', '1211', '111221']],
            [6,  [1 => '1', '11', '21', '1211', '111221', '312211']],
            [7,  [1 => '1', '11', '21', '1211', '111221', '312211', '13112221']],
            [8,  [1 => '1', '11', '21', '1211', '111221', '312211', '13112221', '1113213211']],
            [9,  [1 => '1', '11', '21', '1211', '111221', '312211', '13112221', '1113213211', '31131211131221']],
            [10, [1 => '1', '11', '21', '1211', '111221', '312211', '13112221', '1113213211', '31131211131221', '13211311123113112211']],
            [11, [1 => '1', '11', '21', '1211', '111221', '312211', '13112221', '1113213211', '31131211131221', '13211311123113112211', '11131221133112132113212221']],
            [12, [1 => '1', '11', '21', '1211', '111221', '312211', '13112221', '1113213211', '31131211131221', '13211311123113112211', '11131221133112132113212221', '3113112221232112111312211312113211']],
            [13, [1 => '1', '11', '21', '1211', '111221', '312211', '13112221', '1113213211', '31131211131221', '13211311123113112211', '11131221133112132113212221', '3113112221232112111312211312113211', '1321132132111213122112311311222113111221131221']],
            [14, [1 => '1', '11', '21', '1211', '111221', '312211', '13112221', '1113213211', '31131211131221', '13211311123113112211', '11131221133112132113212221', '3113112221232112111312211312113211', '1321132132111213122112311311222113111221131221', '11131221131211131231121113112221121321132132211331222113112211']],
            [15, [1 => '1', '11', '21', '1211', '111221', '312211', '13112221', '1113213211', '31131211131221', '13211311123113112211', '11131221133112132113212221', '3113112221232112111312211312113211', '1321132132111213122112311311222113111221131221', '11131221131211131231121113112221121321132132211331222113112211', '311311222113111231131112132112311321322112111312211312111322212311322113212221']],
        ];
    }

    /**
     * @dataProvider dataProviderForLazyCaterers
     */
    public function testLazyCaterers(int $n, array $lazy_caterers)
    {
        $this->assertEquals($lazy_caterers, Advanced::lazyCaterers($n));
    }

    public function dataProviderForLazyCaterers()
    {
        return [
            [-1, []],
            [0, []],
            [1, [1]],
            [2, [1, 2]],
            [3, [1, 2, 4]],
            [4, [1, 2, 4, 7]],
            [5, [1, 2, 4, 7, 11]],
            [6, [1, 2, 4, 7, 11, 16]],
            [7, [1, 2, 4, 7, 11, 16, 22]],
            [8, [1, 2, 4, 7, 11, 16, 22, 29]],
            [9, [1, 2, 4, 7, 11, 16, 22, 29, 37]],
            [10, [1, 2, 4, 7, 11, 16, 22, 29, 37, 46]],
            [11, [1, 2, 4, 7, 11, 16, 22, 29, 37, 46, 56]],
            [53, [1, 2, 4, 7, 11, 16, 22, 29, 37, 46, 56, 67, 79, 92, 106, 121, 137, 154, 172, 191, 211, 232, 254, 277, 301, 326, 352, 379, 407, 436, 466, 497, 529, 562, 596, 631, 667, 704, 742, 781, 821, 862, 904, 947, 991, 1036, 1082, 1129, 1177, 1226, 1276, 1327, 1379]],
        ];
    }

    /**
     * @dataProvider dataProviderForMagicSquares
     */
    public function testMagicSquares(int $n, array $M)
    {
        $this->assertEquals($M, Advanced::magicSquares($n));
    }

    public function dataProviderForMagicSquares()
    {
        return [
            [-1, []],
            [0, []],
            [1, [0]],
            [2, [0, 1]],
            [3, [0, 1, 5]],
            [4, [0, 1, 5, 15]],
            [5, [0, 1, 5, 15, 34]],
            [6, [0, 1, 5, 15, 34, 65]],
            [7, [0, 1, 5, 15, 34, 65, 111]],
            [8, [0, 1, 5, 15, 34, 65, 111, 175]],
            [9, [0, 1, 5, 15, 34, 65, 111, 175, 260]],
            [10, [0, 1, 5, 15, 34, 65, 111, 175, 260, 369]],
            [11, [0, 1, 5, 15, 34, 65, 111, 175, 260, 369, 505]],
            [42, [0, 1, 5, 15, 34, 65, 111, 175, 260, 369, 505, 671, 870, 1105, 1379, 1695, 2056, 2465, 2925, 3439, 4010, 4641, 5335, 6095, 6924, 7825, 8801, 9855, 10990, 12209, 13515, 14911, 16400, 17985, 19669, 21455, 23346, 25345, 27455, 29679, 32020, 34481]],

        ];
    }

    /**
     * @dataProvider dataProviderForPerfectPowers
     */
    public function testPerfecetPowers(int $n, array $perfect_powers)
    {
        $this->assertEquals($perfect_powers, Advanced::perfectPowers($n));
    }

    public function dataProviderForPerfectPowers()
    {
        return [
            [-1, []],
            [0, []],
            [1, [4]],
            [2, [4, 8]],
            [10, [4, 8, 9, 16, 25, 27, 32, 36, 49, 64]],
            [53, [4, 8, 9, 16, 25, 27, 32, 36, 49, 64, 81, 100, 121, 125, 128, 144, 169, 196, 216, 225, 243, 256, 289, 324, 343, 361, 400, 441, 484, 512, 529, 576, 625, 676, 729, 784, 841, 900, 961, 1000, 1024, 1089, 1156, 1225, 1296, 1331, 1369, 1444, 1521, 1600, 1681, 1728, 1764]],
        ];
    }

    /**
     * @dataProvider dataProviderForNotPerfectPowers
     */
    public function testNotPerfecetPowers(int $n, array $not_perfect_powers)
    {
        $this->assertEquals($not_perfect_powers, Advanced::notPerfectPowers($n));
    }

    public function dataProviderForNotPerfectPowers()
    {
        return [
            [-1, []],
            [0, []],
            [1, [2]],
            [2, [2, 3]],
            [10, [2, 3, 5, 6, 7, 10, 11, 12, 13, 14]],
            [71, [2, 3, 5, 6, 7, 10, 11, 12, 13, 14, 15, 17, 18, 19, 20, 21, 22, 23, 24, 26, 28, 29, 30, 31, 33, 34, 35, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 82, 83]],
        ];
    }
}
