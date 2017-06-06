<?php

namespace MathPHP\Tests\Number;

use MathPHP\Number\BigInt;

class BigIntTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @testCase     binary representation is as expected
     * @dataProvider dataProviderForDecBin
     * @param        mixed  $int
     * @param        string  $expected
     */
    public function testDecBin($int, string $expected)
    {
        $A = new BigInt($int);
        $this->assertEquals($expected, $A->decbin());
    }

    public function dataProviderForDecBin()
    {
        return [
            [ // 1 as a 128 bit number (1x1)
                1,
                '1',
            ],
            [ // -1 as a 128 bit number (1x128)
                -1,
                '11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
            ],
            [ // MAX_INT as a 128 bit number (1x63)
                \PHP_INT_MAX,
                '111111111111111111111111111111111111111111111111111111111111111',
            ],
            [
                "0b01010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101",
                '1010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101',
            ],
            [ // 1 as a 128 bit number (1x1)
                [0,1],
                '1',
            ],
            [ // -1 as a 128 bit number (1x128)
                [-1, -1],
                '11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
            ],
        ];
    }

    /**
     * @testCase     addition of two BigInts returns the expeced result
     * @dataProvider dataProviderForAddInt
     * @param        array  $int1
     * @param        array  $int2
     * @param        array  $expected
    public function testAddInt(int $int1, int $int2, int $e)
    {
        $A = new BigInt($int1);
        $B = new BigInt($int2);
        $sum = $A->add($B);
        $expected = new BigInt($e);
        $this->assertTrue($sum->equals($expected));
    }

    public function dataProviderForAddInt()
    {
        return [
            [ // 1 + 1 = 2
                1,
                1,
                2,
            ],
            [ // 123 + 234 = 357
                123,
                234,
                357,
            ],
            [ // 1 + -1 = 0
                1,
                -1,
                0,
            ],
            [ // -123 + -234 = -357
                -123
                -234,
                -357,
            ],
        ];
    }*/
}
