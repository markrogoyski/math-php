<?php
namespace Math\Probability;

class CombinatoricsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForFactorialPermutations
     */
    public function testFactorial($n, $factorial)
    {
        $this->assertEquals($factorial, Combinatorics::factorial($n));
    }

    public function testFactorialBoundsException()
    {
        $this->setExpectedException('\Exception');
        Combinatorics::factorial(-1);
    }

    /**
     * @dataProvider dataProviderForDoubleFactorial
     */
    public function testDoubleFactorial(int $n, $factorial)
    {
        $this->assertEquals($factorial, Combinatorics::doubleFactorial($n));
    }
    public function dataProviderForDoubleFactorial()
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
    
    public function testDoubleFactorialExceptionNLessThanZero()
    {
        $this->setExpectedException('\Exception');
        Combinatorics::doubleFactorial(-1);
    }
    
    /**
     * @dataProvider dataProviderForRisingFactorial
     */
    public function testRisingFactorial($x, int $n, $factorial)
    {
        $this->assertEquals($factorial, Combinatorics::risingFactorial($x, $n));
    }
    public function dataProviderForRisingFactorial()
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
    
    public function testRisingFactorialExceptionNLessThanZero()
    {
        $this->setExpectedException('\Exception');
        Combinatorics::risingFactorial(5, -1);
    }

    /**
     * @dataProvider dataProviderForFallingFactorial
     */
    public function testFallingFactorial($x, int $n, $factorial)
    {
        $this->assertEquals($factorial, Combinatorics::fallingFactorial($x, $n));
    }

    public function dataProviderForFallingFactorial()
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

    public function testFallingFactorialExceptionNLessThanZero()
    {
        $this->setExpectedException('\Exception');
        Combinatorics::fallingFactorial(5, -1);
    }

    /**
     * @dataProvider dataProviderForSubfactorial
     */
    public function testSubfactorial($n, $！n)
    {
        $this->assertEquals($！n, Combinatorics::subfactorial($n), '', 0.000000001);
    }

    public function dataProviderForSubfactorial()
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

    public function testSubactorialExceptionNLessThanZero()
    {
        $this->setExpectedException('\Exception');
        Combinatorics::subfactorial(-1);
    }

    /**
     * @dataProvider dataProviderForFactorialPermutations
     */
    public function testPermutations($n, $permutations)
    {
        $this->assertEquals($permutations, Combinatorics::permutations($n));
    }

    public function testPermutationsBoundsException()
    {
        $this->setExpectedException('\Exception');
        Combinatorics::permutations(-1);
    }

    /**
     * Data provider for factorial and permutations tests
     * Data: [ n, permutations ]
     */
    public function dataProviderForFactorialPermutations()
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
     * @dataProvider dataProviderForPermutationsChooseK
     */
    public function testPermutationsChooseK($n, $k, $nPk)
    {
        $this->assertEquals($nPk, Combinatorics::permutations($n, $k));
    }

    /**
     * Data provider for permutations choose k tests
     * Data: [ n, k, permutations ]
     */
    public function dataProviderForPermutationsChooseK()
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

    public function testPermutationsChooseKBoundsException()
    {
        $this->setExpectedException('\Exception');
        Combinatorics::permutations(-1, 3);
    }

    public function testPermutationsChooseKKGreaterThanNException()
    {
        $this->setExpectedException('\Exception');
        Combinatorics::permutations(3, 4);
    }

    /**
     * @dataProvider dataProviderForcombinations
     */
    public function testCombinations($n, $r, $combinations)
    {
        $this->assertEquals($combinations, Combinatorics::combinations($n, $r));
    }

    /**
     * Data provider for combinations tests
     * Data: [ n, r, combinations ]
     */
    public function dataProviderForCombinations()
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

    public function testCombinationsExceptionNLessThanZero()
    {
        $this->setExpectedException('\Exception');
        Combinatorics::combinations(-1, 2);
    }

    public function testCombinationsExceptionRLargerThanN()
    {
        $this->setExpectedException('\Exception');
        Combinatorics::combinations(1, 2);
    }

    /**
     * @dataProvider dataProviderForCombinationsWithRepetition
     */
    public function testCombinationsWithRepetition($n, $r, $combinations)
    {
        $this->assertEquals($combinations, Combinatorics::combinations($n, $r, Combinatorics::REPETITION));
    }

    public function testCombinationsWithRepetitionBoundsException()
    {
        $this->setExpectedException('\Exception');
        Combinatorics::combinations(-1, 3, Combinatorics::REPETITION);
    }

    public function testCombinationsRGreaterThanNException()
    {
        $this->setExpectedException('\Exception');
        Combinatorics::combinations(3, 4, Combinatorics::REPETITION);
    }

    /**
     * Data provider for combinations with repetition tests
     * Data: [ n, r, combinations ]
     */
    public function dataProviderForCombinationsWithRepetition()
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
     * @dataProvider dataProviderForCentralBinomialCoefficient
     */
    public function testCentralBinomialCoefficient($n, $！n)
    {
        $this->assertEquals($！n, Combinatorics::centralBinomialCoefficient($n), '', 0.000000001);
    }

    public function dataProviderForCentralBinomialCoefficient()
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

    public function testCentralBinomialCoefficientExceptionNLessThanZero()
    {
        $this->setExpectedException('\Exception');
        Combinatorics::centralBinomialCoefficient(-1);
    }

    /**
     * @dataProvider dataProviderForCatalanNumber
     */
    public function testCatalanNumber($n, $！n)
    {
        $this->assertEquals($！n, Combinatorics::catalanNumber($n), '', 0.000000001);
    }

    public function dataProviderForCatalanNumber()
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

    public function testCatalanNumberExceptionNLessThanZero()
    {
        $this->setExpectedException('\Exception');
        Combinatorics::catalanNumber(-1);
    }

    /**
     * @dataProvider dataProviderForMultinomialTheorem
     */
    public function testMultinomialTheorem(array $groups, $divisions)
    {
        $this->assertEquals($divisions, Combinatorics::multinomial($groups));
    }

    /**
     * Data provider for multinomial theorem tests
     * Data: [ n, groups, divisions ]
     */
    public function dataProviderForMultinomialTheorem()
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
     * @dataProvider dataProviderForLahNumber
     */
    public function testLahNumber($k, int $n, $lah)
    {
        $this->assertEquals($lah, Combinatorics::lahNumber($k, $n));
    }

    public function dataProviderForLahNumber()
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
     * @dataProvider dataProviderForLahNumberExceptionNOrKLessThanOne
     * @return [type] [description]
     */
    public function testLahNumberExceptionNOrKLessThanOne(int $n, int $k)
    {
        $this->setExpectedException('\Exception');
        Combinatorics::lahNumber($n, $k);
    }

    public function dataProviderForLahNumberExceptionNOrKLessThanOne()
    {
        return [
            [-1, 2],
            [2, -2],
            [-3, -3],
        ];
    }

    public function testLahNumberExceptionNLessThanK()
    {
        $this->setExpectedException('\Exception');

        $k = 4;
        $n = 2;
        Combinatorics::lahNumber($n, $k);
    }
}
