<?php
namespace MathPHP\Tests\Probability;

use MathPHP\Probability\Combinatorics;
use MathPHP\Exception;

class CombinatoricsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testCase     factorial
     * @dataProvider dataProviderForFactorialPermutations
     * @param        int   $n
     * @param        float $factorial
     * @throws       \Exception
     */
    public function testFactorial(int $n, float $factorial)
    {
        $this->assertEquals($factorial, Combinatorics::factorial($n));
    }

    /**
     * @testCase factorial bounds exception
     * @throws   \Exception
     */
    public function testFactorialBoundsException()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Combinatorics::factorial(-1);
    }

    /**
     * @testCase     doubleFactorial
     * @dataProvider dataProviderForDoubleFactorial
     * @param        int   $n
     * @param        float $factorial
     * @throws       \Exception
     */
    public function testDoubleFactorial(int $n, float $factorial)
    {
        $this->assertEquals($factorial, Combinatorics::doubleFactorial($n));
    }

    /**
     * @return array [n, doubleFactorial]
     */
    public function dataProviderForDoubleFactorial(): array
    {
        return [
            [0, 1],
            [1, 1],
            [2, 2],
            [3, 3],
            [4, 8],
            [5, 15],
            [6, 48],
            [7, 105],
            [8, 384],
            [9, 945],
            [10, 3840],
            [11, 10395],
            [12, 46080],
            [13, 135135],
            [14, 645120],
        ];
    }

    /**
     * @testCase doubleFactorial n less than zero
     * @throws   \Exception
     */
    public function testDoubleFactorialExceptionNLessThanZero()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Combinatorics::doubleFactorial(-1);
    }

    /**
     * @testCase     risingFactorial
     * @dataProvider dataProviderForRisingFactorial
     * @param        int   $x
     * @param        int   $n
     * @param        float $factorial
     * @throws       \Exception
     */
    public function testRisingFactorial(int $x, int $n, float $factorial)
    {
        $this->assertEquals($factorial, Combinatorics::risingFactorial($x, $n));
    }

    /**
     * @return array [x, n, risingFactorial]
     */
    public function dataProviderForRisingFactorial(): array
    {
        return [
            [5, 0, 1],
            [5, 1, 5],
            [5, 2, 30],
            [5, 3, 210],
            [4, 4, 840],
            [3, 5, 2520],
            [2, 6, 5040],
        ];
    }

    /**
     * @testCase risingFactorial n less than zero
     * @throws   \Exception
     */
    public function testRisingFactorialExceptionNLessThanZero()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Combinatorics::risingFactorial(5, -1);
    }

    /**
     * @testCase     fallingFactorial
     * @dataProvider dataProviderForFallingFactorial
     * @param        int   $x
     * @param        int   $n
     * @param        float $factorial
     * @throws       \Exception
     */
    public function testFallingFactorial(int $x, int $n, float $factorial)
    {
        $this->assertEquals($factorial, Combinatorics::fallingFactorial($x, $n));
    }

    /**
     * @return array [x, n, fallingFactorial]
     */
    public function dataProviderForFallingFactorial(): array
    {
        return [
            [5, 0, 1],
            [5, 1, 5],
            [5, 2, 20],
            [5, 3, 60],
            [5, 4, 120],
            [5, 5, 120],
            [5, 6, 0],
            [4, 3, 24],
            [4, 4, 24],
            [4, 5, 0],
            [8, 5, 6720],
            [3, 5, 0],
            [2, 6, 0],
        ];
    }

    /**
     * @testCase fallingFactorial n less than zero
     * @throws   \Exception
     */
    public function testFallingFactorialExceptionNLessThanZero()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Combinatorics::fallingFactorial(5, -1);
    }

    /**
     * @testCase     subfactorial
     * @dataProvider dataProviderForSubfactorial
     * @param        int   $n
     * @param        float $！n
     * @throws       \Exception
     */
    public function testSubfactorial(int $n, float $！n)
    {
        $this->assertEquals($！n, Combinatorics::subfactorial($n), '', 0.000000001);
    }

    /**
     * @return array [n, ！n]
     */
    public function dataProviderForSubfactorial(): array
    {
        return [
            [0, 1],
            [1, 0],
            [2, 1],
            [3, 2],
            [4, 9],
            [5, 44],
            [6, 265],
            [7, 1854],
            [8, 14833],
            [9, 133496],
            [10, 1334961],
        ];
    }

    /**
     * @testCase subfactorial n less than zero
     * @throws   \Exception
     */
    public function testSubactorialExceptionNLessThanZero()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Combinatorics::subfactorial(-1);
    }

    /**
     * @testCase     permutations
     * @dataProvider dataProviderForFactorialPermutations
     * @param        int   $n
     * @param        float $permutations
     * @throws       \Exception
     */
    public function testPermutations(int $n, float $permutations)
    {
        $this->assertEquals($permutations, Combinatorics::permutations($n));
    }

    /**
     * @testCase permutations bounds exception
     * @throws   \Exception
     */
    public function testPermutationsBoundsException()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Combinatorics::permutations(-1);
    }

    /**
     * @return array [n, permutations]
     */
    public function dataProviderForFactorialPermutations(): array
    {
        return [
            [ 1,  1 ],
            [ 2,  2 ],
            [ 3,  6 ],
            [ 4,  24 ],
            [ 5,  120 ],
            [ 6,  720 ],
            [ 7,  5040 ],
            [ 8,  40320 ],
            [ 9,  362880 ],
            [ 10, 3628800 ],
            [ 11, 39916800 ],
            [ 12, 479001600 ],
            [ 13, 6227020800 ],
            [ 14, 87178291200 ],
            [ 15, 1307674368000 ],
            [ 16, 20922789888000 ],
            [ 17, 355687428096000 ],
            [ 18, 6402373705728000 ],
            [ 19, 121645100408832000 ],
            [ 20, 2432902008176640000 ],
        ];
    }

    /**
     * @testCase     permutations choose k
     * @dataProvider dataProviderForPermutationsChooseK
     * @param        int   $n
     * @param        int   $k
     * @param        float $nPk
     * @throws       \Exception
     */
    public function testPermutationsChooseK(int $n, int $k, float $nPk)
    {
        $this->assertEquals($nPk, Combinatorics::permutations($n, $k));
    }

    /**
     * @return array [n, k, permutations]
     */
    public function dataProviderForPermutationsChooseK(): array
    {
        return [
            [ 10,  0,       1 ],
            [ 10,  1,      10 ],
            [ 10,  2,      90 ],
            [ 10,  3,     720 ],
            [ 10,  4,    5040 ],
            [ 10,  5,   30240 ],
            [ 10,  6,  151200 ],
            [ 10,  7,  604800 ],
            [ 10,  8, 1814400 ],
            [ 10,  9, 3628800 ],
            [ 10, 10, 3628800 ],
            [  5,  3,      60 ],
            [  6,  4,     360 ],
            [ 16,  3,    3360 ],
            [ 20,  3,    6840 ],
        ];
    }

    /**
     * @testCase permutations choose k bounds exception
     * @throws   \Exception
     */
    public function testPermutationsChooseKBoundsException()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Combinatorics::permutations(-1, 3);
    }

    /**
     * @testCase permutations choose k - k greater than n exception
     * @throws   \Exception
     */
    public function testPermutationsChooseKKGreaterThanNException()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Combinatorics::permutations(3, 4);
    }

    /**
     * @testCase     combinations
     * @dataProvider dataProviderForcombinations
     * @param        int   $n
     * @param        int   $r
     * @param        float $combinations
     * @throws       \Exception
     */
    public function testCombinations(int $n, int $r, float $combinations)
    {
        $this->assertEquals($combinations, Combinatorics::combinations($n, $r));
    }

    /**
     * @return array [n, r, combinations]
     */
    public function dataProviderForCombinations(): array
    {
        return [
            [ 10,  0,    1 ],
            [ 10,  1,   10 ],
            [ 10,  2,   45 ],
            [ 10,  3,  120 ],
            [ 10,  4,  210 ],
            [ 10,  5,  252 ],
            [ 10,  6,  210 ],
            [ 10,  7,  120 ],
            [ 10,  8,   45 ],
            [ 10,  9,   10 ],
            [ 10, 10,    1 ],
            [  5,  3,   10 ],
            [  6,  4,   15 ],
            [ 16,  3,  560 ],
            [ 20,  3, 1140 ],
        ];
    }

    /**
     * @testCase combinations n less than zero
     * @throws   \Exception
     */
    public function testCombinationsExceptionNLessThanZero()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Combinatorics::combinations(-1, 2);
    }

    /**
     * @testCase combinations r larger than n
     * @throws   \Exception
     */
    public function testCombinationsExceptionRLargerThanN()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Combinatorics::combinations(1, 2);
    }

    /**
     * @testCase     combinations with repetition
     * @dataProvider dataProviderForCombinationsWithRepetition
     * @param        int    $n
     * @param        int    $r
     * @param        float $combinations
     * @throws       \Exception
     */
    public function testCombinationsWithRepetition(int $n, int $r, float $combinations)
    {
        $this->assertEquals($combinations, Combinatorics::combinations($n, $r, Combinatorics::REPETITION));
    }

    /**
     * @testCase combinations with repetition bounds exception
     * @throws   \Exception
     */
    public function testCombinationsWithRepetitionBoundsException()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Combinatorics::combinations(-1, 3, Combinatorics::REPETITION);
    }

    /**
     * @testCase combinations r greater than n
     * @throws   \Exception
     */
    public function testCombinationsRGreaterThanNException()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Combinatorics::combinations(3, 4, Combinatorics::REPETITION);
    }

    /**
     * @return array [n, r, combinations]
     */
    public function dataProviderForCombinationsWithRepetition(): array
    {
        return [
            [ 10,  0,     1 ],
            [ 10,  1,    10 ],
            [ 10,  2,    55 ],
            [ 10,  3,   220 ],
            [ 10,  4,   715 ],
            [ 10,  5,  2002 ],
            [ 10,  6,  5005 ],
            [ 10,  7, 11440 ],
            [ 10,  8, 24310 ],
            [ 10,  9, 48620 ],
            [ 10, 10, 92378 ],
            [ 5,   3,    35 ],
            [ 6,   4,   126 ],
            [ 16,  3,   816 ],
            [ 20,  3,  1540 ],
        ];
    }

    /**
     * @testCase     centralbinomialCoefficient
     * @dataProvider dataProviderForCentralBinomialCoefficient
     * @param        int   $n
     * @param        float $！n
     * @throws       \Exception
     */
    public function testCentralBinomialCoefficient(int $n, float $！n)
    {
        $this->assertEquals($！n, Combinatorics::centralBinomialCoefficient($n), '', 0.000000001);
    }

    /**
     * @return array [n, ！n]
     */
    public function dataProviderForCentralBinomialCoefficient(): array
    {
        return [
            [0, 1],
            [1, 2],
            [2, 6],
            [3, 20],
            [4, 70],
            [5, 252],
            [6, 924],
            [7, 3432],
            [8, 12870],
            [9, 48620],
            [10, 184756],
        ];
    }

    /**
     * @testCase centralBinomialCoefficient n less than zero
     * @throws   \Exception
     */
    public function testCentralBinomialCoefficientExceptionNLessThanZero()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Combinatorics::centralBinomialCoefficient(-1);
    }

    /**
     * @testCase     catalanNumber
     * @dataProvider dataProviderForCatalanNumber
     * @param        int   $n
     * @param        float $！n
     * @throws       \Exception
     */
    public function testCatalanNumber(int $n, float $！n)
    {
        $this->assertEquals($！n, Combinatorics::catalanNumber($n), '', 0.000000001);
    }

    /**
     * @return array [n, ！n]
     */
    public function dataProviderForCatalanNumber(): array
    {
        return [
            [0, 1],
            [1, 1],
            [2, 2],
            [3, 5],
            [4, 14],
            [5, 42],
            [6, 132],
            [7, 429],
            [8, 1430],
            [9, 4862],
            [10, 16796],
            [11, 58786],
        ];
    }

    /**
     * @testCase catalanNumber n less than zero
     * @throws   \Exception
     */
    public function testCatalanNumberExceptionNLessThanZero()
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Combinatorics::catalanNumber(-1);
    }

    /**
     * @testCase     multinomial
     * @dataProvider dataProviderForMultinomialTheorem
     * @param        array $groups
     * @param        int   $divisions
     * @throws       \Exception
     */
    public function testMultinomialTheorem(array $groups, int $divisions)
    {
        $this->assertEquals($divisions, Combinatorics::multinomial($groups));
    }

    /**
     * @return array [groups, divisions]
     */
    public function dataProviderForMultinomialTheorem(): array
    {
        return [
            [ [2, 0, 1], 3],
            [ [1, 1, 1], 6],
            [ [ 5, 2, 3 ], 2520 ],
            [ [ 5, 5 ],     252 ],
            [ [ 1, 4, 4, 2 ], 34650 ],
            [ [3, 4, 5, 8], 3491888400],
        ];
    }

    /**
     * @testCase     lahNumber
     * @dataProvider dataProviderForLahNumber
     * @param        int   $k
     * @param        int   $n
     * @param        float $lah
     * @throws       \Exception
     */
    public function testLahNumber(int $k, int $n, float $lah)
    {
        $this->assertEquals($lah, Combinatorics::lahNumber($k, $n));
    }

    /**
     * @return array [k, n, lah]
     */
    public function dataProviderForLahNumber(): array
    {
        return [
            [1, 1, 1],
            [2, 1, 2],
            [2, 2, 1],
            [3, 1, 6],
            [3, 2, 6],
            [3, 3, 1],
            [4, 1, 24],
            [4, 2, 36],
            [4, 3, 12],
            [4, 4, 1],
            [5, 1, 120],
            [5, 2, 240],
            [5, 3, 120],
            [5, 4, 20],
            [5, 5, 1],
            [6, 1, 720],
            [6, 2, 1800],
            [6, 3, 1200],
            [6, 4, 300],
            [6, 5, 30],
            [6, 6, 1],
            [12, 1, 479001600],
            [12, 2, 2634508800],
            [12, 3, 4390848000],
            [12, 4, 3293136000],
            [12, 5, 1317254400],
            [12, 6, 307359360],
            [12, 7, 43908480],
            [12, 8, 3920400],
            [12, 9, 217800],
            [12, 10, 7260],
            [12, 11, 132],
            [12, 12, 1],
        ];
    }

    /**
     * @testCase     lahNumber n or k less than one
     * @dataProvider dataProviderForLahNumberExceptionNOrKLessThanOne
     * @param        int $n
     * @param        int $k
     * @throws       \Exception
     */
    public function testLahNumberExceptionNOrKLessThanOne(int $n, int $k)
    {
        $this->expectException(Exception\OutOfBoundsException::class);
        Combinatorics::lahNumber($n, $k);
    }

    /**
     * @return array [n, k]
     */
    public function dataProviderForLahNumberExceptionNOrKLessThanOne(): array
    {
        return [
            [-1, 2],
            [2, -2],
            [-3, -3],
        ];
    }

    /**
     * @testCase lahNumber n less than k
     * @throws   \Exception
     */
    public function testLahNumberExceptionNLessThanK()
    {
        $this->expectException(Exception\OutOfBoundsException::class);

        $k = 4;
        $n = 2;
        Combinatorics::lahNumber($n, $k);
    }
}
