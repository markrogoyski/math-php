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
            [
                '0b0' . str_repeat('1', 127),
                str_repeat('1', 127),
            ],
            [
                '0b' . str_repeat('1', 128),
                str_repeat('1', 128),
            ],
            [
                '0b' . str_repeat('1', 64),
                str_repeat('1', 64),
            ],
            [
                [1, 1],
                '1' . str_repeat('0', 63) . '1' ,
            ],
        ];
    }

    /**
     * @testCase     hexadecimal representation is as expected
     * @dataProvider dataProviderForDecHex
     * @param        mixed  $int
     * @param        string  $expected
     */
    public function testDecHex($int, string $expected)
    {
        $A = new BigInt($int);
        $this->assertEquals($expected, $A->dechex());
    }
    public function dataProviderForDecHex()
    {
        $BigInt = new BigInt(1);
        return [
            [ // 1 as a 128 bit number (1x1)
                1,
                '1',
            ],
            [ // -1 as a 128 bit number (1x128)
                -1,
                'ffffffffffffffffffffffffffffffff',
            ],
            [ // MAX_INT as a 128 bit number (1x63)
                '0b' . $BigInt::maxValue()->decbin(),
                '7fffffffffffffffffffffffffffffff',
            ],
            [ // BigInt in binary
                '0b' . $BigInt::minValue()->decbin(),
                '80000000000000000000000000000000',
            ],
            
            [ //int in binary
                "0b1010",
                'a',
            ],
            [ // -1 as a 128 bit number (1x128)
                [1, 1],
                '10000000000000001',
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
     */
    public function testAddInt(int $int1, int $int2, int $e)
    {
        $A = new BigInt($int1);
        $B = $int2;
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
                -123,
                -234,
                -357,
            ],
        ];
    }

    /**
     * @testCase     addition of two BigInts returns the expeced result
     * @dataProvider dataProviderForAddBigInt
     * @param        array  $int1
     * @param        array  $int2
     * @param        array  $expected
     */
    public function testAddBigInt($int1, $int2, $e)
    {
        $A = new BigInt($int1);
        $B = new BigInt($int2);
        $sum = $A->add($B);
        $expected = new BigInt($e);
        $this->assertEquals($expected->dechex(), $sum->dechex());
    }

    public function dataProviderForAddBigInt()
    {
        return [
            [
                [1, 1],
                [1, 1],
                [2, 2],
            ],
        ];
    }

    /**
     * @testCase     addition of two BigInts returns the expeced result
     * @dataProvider dataProviderForSubtractInt
     * @param        array  $int1
     * @param        array  $int2
     * @param        array  $expected
     */
    public function testSubtractInt(int $int1, int $int2, int $e)
    {
        $A = new BigInt($int1);
        $B = $int2;
        $sum = $A->subtract($B);
        $expected = new BigInt($e);
        $this->assertTrue($sum->equals($expected));
    }

    public function dataProviderForSubtractInt()
    {
        return [
            [ // 1 - -1 = 2
                1,
                -1,
                2,
            ],
            [ // 123 - 234 = -111
                123,
                234,
                -111,
            ],
            [ // 1 - 1 = 0
                1,
                1,
                0,
            ],
            [ // -123 - 234 = -357
                -123,
                234,
                -357,
            ],
        ];
    }

    /**
     * @testCase     Test out of Bounds
     */
    public function testAddOutOfBounds()
    {
        $A = new BigInt(1);
        $largest = $A::maxValue();
        $this->expectException(Exception\OutOfBoundsException::class);
        $sum = $largest->add($A);
    }

    public function testSubtractOutOfBounds()
    {
        $A = new BigInt(1);
        $largest = $A::minValue();
        $this->expectException(Exception\OutOfBoundsException::class);
        $sum = $largest->subtract($A);
    }
    
    /**
     * @testCase     Test bad input parameter
     */
    public function testAddBadParameter()
    {
        $A = new BigInt(1);
        $this->expectException(Exception\IncorrectTypeException::class);
        $sum = $A->add("TEST");
    }

    /**
     * @testCase     Subtraction of two BigInts returns the expeced result
     * @dataProvider dataProviderForSubtractBigInt
     * @param        array  $int1
     * @param        array  $int2
     * @param        array  $expected
     */
    public function testSubtractBigInt($int1, $int2, $e)
    {
        $A = new BigInt($int1);
        $B = new BigInt($int2);
        $sum = $A->subtract($B);
        $expected = new BigInt($e);
        $this->assertTrue($sum->equals($expected));
    }

    public function dataProviderForSubtractBigInt()
    {
        return [
            [
                [\PHP_INT_MAX, 1],
                [1, 1],
                [\PHP_INT_MAX - 1, 0],
            ],
        ];
    }

    /**
     * @testCase     addition of two BigInts returns the expeced result
     * @dataProvider dataProviderForMSB
     * @param        array  $int
     * @param        array  $e
     */
    public function testMSB($int, $e)
    {
        $A = new BigInt($int);
        $this->assertEquals($e, $A->MSB());
    }
    public function dataProviderForMSB()
    {
        return [
            [1, 0],
            [2, 1],
            [4, 2],
            [8, 3],
        ];
    }

    /**
     * @testCase     addition of two BigInts returns the expeced result
     * @dataProvider dataProviderForGetBit
     * @param        array  $int
     * @param        array  $e
     */
    public function testGetBit($int, $n, $e)
    {
        $A = new BigInt($int);
        $b = $A->getBit($n);
        $this->assertEquals($e, $b);
    }

    public function dataProviderForGetBit()
    {
        return [
            [5, 0, 1],
            [5, 1, 0],
            [5, 2, 1],
            [5, 63, 0],
            [\PHP_INT_MAX, 63, 0],
            [\PHP_INT_MIN, 63, 1],
        ];
    }

    /**
     * @testCase     addition of two BigInts returns the expeced result
     * @dataProvider dataProviderForMultiplyInt
     * @param        array  $int1
     * @param        array  $int2
     * @param        array  $expected
     */
    public function testMultiplyInt(int $int1, int $int2, int $e)
    {
        $A = new BigInt($int1);
        $B = $int2;
        $sum = $A->multiply($B);
        $expected = new BigInt($e);
        $this->assertEquals($expected->dechex(), $sum->dechex());
    }
    public function dataProviderForMultiplyInt()
    {
        return [
            [ // 1 * 1 = 1
                1,
                1,
                1,
            ],
            [
                5,
                5,
                25,
            ],
        ];
    }

    /**
     * @testCase     divsion of two BigInts returns the expeced result
     * @dataProvider dataProviderForDivideInt
     * @param        array  $int1
     * @param        array  $int2
     * @param        array  $e
     */
    public function testDivideInt(int $int1, int $int2, array $e)
    {
        $A = new BigInt($int1);
        $B = $int2;
        $results = $A->euclideanDivision($B);
        $expected_q = new BigInt($e[0]);
        $expected_r = new BigInt($e[1]);
        $this->assertEquals($expected_q->dechex(), $results['quotient']->dechex());
        $this->assertEquals($expected_r->dechex(), $results['remainder']->dechex());
    }
    public function dataProviderForDivideInt()
    {
        return [
            [ // 1 / 1 = 1
                1,
                1,
                [1, 0],
            ],
            [// 5 / 2 = 2r1
                5,
                2,
                [2, 1],
            ],
        ];
    }

    /**
     * @testCase     test power
     * @dataProvider dataProviderForPow
     * @param        array  $bigint
     * @param        array  $e
     */
    public function testPow($bigint, $p, $e)
    {
        $A = new BigInt($bigint);
        $B = $A->pow($p);
        $this->assertEquals($e, $B->dechex());
    }

    public function dataProviderForPow()
    {
        return [
            [1, 0, "1"],
            [1, 1, "1"],
            [1, 2, "1"],
            [2, 0, "1"],
            [2, 5, "20"],
            [10, 8, "2faf08"],
        ];
    }

    /**
     * @testCase     -1 times BigInt
     * @dataProvider dataProviderForNegate
     * @param        array  $bigint
     * @param        array  $e
     */
    public function testNegate($bigint, $e)
    {
        $A = new BigInt($bigint);
        $N = $A->negate();
        $expected = new BigInt($e);
        $this->assertTrue($N->equals($expected));
    }

    public function dataProviderForNegate()
    {
        return [
            [1, -1],
            [-1, 1],
            [0, 0],
            [1234567890, -1234567890],
            [[-1, \PHP_INT_MAX], [1, \PHP_INT_MIN]],
        ];
    }
    
    /**
     * @testCase     The smallest int cannot be represented as a positive value
     */
    public function testNegateException()
    {
        $A = new BigInt([0, \PHP_INT_MIN]);
        $this->expectException(Exception\OutOfBoundsException::class);
        $N = $A->negate();
    }

    /**
     * @testCase     test that the returned string is as it should be
     * @dataProvider dataProviderForToString
     * @param        array  $bigint
     * @param        array  $e
    */

    public function testToString($bigint, string $e)
    {
        //$A = new BigInt($bigint);
        //$this->assertEquals($e, $A->__toString());
    }

    public function dataProviderForToString()
    {
        return [
            [1, "1"],
            [-1, "-1"],
            [1234567, "1234567"],
            [1234567890, "1234567890"],
            [[-1, \PHP_INT_MAX], "160141188460469231687303715884105727"], // INT_MAX
            [[0, \PHP_INT_MIN], "-160141188460469231687303715884105728"], // INT_MIN
        ];
    }
}
