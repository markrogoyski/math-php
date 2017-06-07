<?php

namespace MathPHP\Tests\Number;

use MathPHP\Exception;
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
            [ // 1 as a 128 bit number (1x1)
                [1,0],
                '1',
            ],
            [ // -1 as a 128 bit number (1x128)
                [-1, -1],
                '11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
            ],
            [ // BigInt in binary
                "0b01010110101101001010100101010011010010101010100100100001010101010101101010101010101011010101010011001010101010101010101010101001",
                '1010110101101001010100101010011010010101010100100100001010101010101101010101010101011010101010011001010101010101010101010101001',
            ],
            
            [ //int in binary
                "0b1010",
                '1010',
            ],
        ];
    }

    /**
     * @testCase     binary representation is as expected
     * @dataProvider dataProviderForToInt
     * @param        mixed  $int
     */
    public function testToInt(int $int)
    {
        $A = new BigInt($int);
        $this->assertEquals($int, $A->toInt());
    }

    public function dataProviderForToInt()
    {
        return [
            [1],
            [-1],
            [0],
            [\PHP_INT_MAX],
            [\PHP_INT_MIN],
        ];
    }

    /**
     * @testCase     binary representation is as expected
     * @dataProvider dataProviderForToIntOutOfBounds
     * @param        mixed  $int
     */
    public function testToIntOutOfBounds(array $array)
    {
        $A = new BigInt($array);
        $this->expectException(Exception\OutOfBoundsException::class);
        $test = $A->toInt();
    }

    public function dataProviderForToIntOutOfBounds()
    {
        return [
            [[0, -1]],
            [[-2, -2]],
            [[-1, 0]],
            [[1, -1]],
            [[-1, 1]],
        ];
    }

    /**
     * @testCase     Test Constructor Exceptions
     * @dataProvider dataProviderForConstructorExceptions
     * @param        mixed  $value
     */
    public function testConstructorExceptions($value)
    {
        $this->expectException(Exception\BadParameterException::class);
        $A = new BigInt($value);
    }

    public function dataProviderForConstructorExceptions()
    {
        return [
            [ // String that does not start with '0b'
                "TEST",
            ],
            [ // String that is not only 1s and 0s after '0b'
                "0bTEST",
            ],
            [ // Too many bits
                "0b10101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010111010000000000",
            ],
            [ // array with too many items
                [1,2,3],
            ],
            [ // array with not enough items
                [1],
            ],
            [ // array where both are not integers
                [1, "test"],
            ],
        ];
    }

    /**
     * @testCase     Test Constructor for a Type Exception
     */
    public function testConstructorFloatExceptions()
    {
        $this->expectException(Exception\IncorrectTypeException::class);
        $A = new BigInt(2.5);
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
