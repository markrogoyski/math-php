<?php

namespace MathPHP\Tests\Number;

use MathPHP\Number\BigInt;

class BigIntTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @testCase     binary representation is as expected
     * @dataProvider dataProviderForDecBin
     * @param        array  $int
     * @param        array  $expected
     */
    public function testDecBin(int $int, string $expected)
    {
        $A = new BigInt($int);
        $this->assertEquals($expected, $A->decbin());
    }

    public function dataProviderForDecBin()
    {
        return [
            [ // 1 as a 128 bit number (0x127 . 1x1)
                1,
                '00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000001',
            ],
            [ // -1 as a 128 bit number (1x128)
                -1,
                '11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
            ],
            [ // MAX_INT as a 128 bit number (0x65 . 1x63)
                \PHP_INT_MAX,
                '00000000000000000000000000000000000000000000000000000000000000000111111111111111111111111111111111111111111111111111111111111111',
            ],
        ];
    }

    /**
     * @testCase     addition of two BigInts returns the expeced result
     * @dataProvider dataProviderForAdd
     * @param        array  $bigint1
     * @param        array  $bigint2
     * @param        array  $expected
    public function testAdd(array $bigint1, array $bigint2, array $e)
    {
        $A = new BigInt($bigint1[0], $bigint1[1]);
        $B = new BigInt($bigint2[0], $bigint2[1]);
        $C = $A->add($B);
        $expected = new BigInt($e[0], $e[1]);
        $this->assertTrue($C->equals($expected));
    }

    public function dataProviderForAdd()
    {
        return [
            [ // 1 + 1 = 2
                [1, 0],
                [1, 0],
                [2, 0],
            ],
            [ // 100 + 200 = 300
                [100, 0],
                [200, 0],
                [300, 0],
            ],
            [ // Max int plus one
                [\PHP_INT_MAX, 0],
                [1, 0],
                [0, 1],
            ],
            [ // Max int times two
                [\PHP_INT_MAX, 0],
                [\PHP_INT_MAX, 0],
                [\PHP_INT_MAX - 1, 1],
            ],
        ];
    }
    */
}
