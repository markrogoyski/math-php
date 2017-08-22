<?php
namespace MathPHP\Tests\Number;

use MathPHP\Number\RationalNumber;
use MathPHP\Exception;

class RationalNumberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @testCase     __toString returns the proper string representation of a rational number
     * @dataProvider dataProviderForToString
     * @param        number $w
     * @param        number $n
     * @param        number $d
     * @param        string $string
     */
    public function testToString($w, $n, $d, string $string)
    {
        $number = new RationalNumber($w, $n, $d);
        $this->assertEquals($string, $number->__toString());
        $this->assertEquals($string, (string) $number);
    }
    
    public function dataProviderForToString(): array
    {
        return [
            [0, 0, 1, '0'],
            [1, 0, 1, '1'],
            [-1, 0, 1, '-1'],
            [0, 1, 1, '1'],
            [0, -1, 1, '-1'],
            [0, 1, -1, '-1'],
            [-5, -1, 2, '-5 ¹/₂'],
            [-5, 1, 2, '-4 ¹/₂'],
            [0, 1, 2, '¹/₂'],
            [0, -1, 2, '-¹/₂'],
        ];
    }
    
    /**
     * @testCase     __toFloat returns the correct floating point number
     * @dataProvider dataProviderForToFloat
     * @param        number $w
     * @param        number $n
     * @param        number $d
     * @param        float $float
     */
    public function testToFloat($w, $n, $d, $float)
    {
        $number = new RationalNumber($w, $n, $d);
        $this->assertEquals($float, $number->toFloat());
    }
    
    public function dataProviderForToFloat(): array
    {
        return [
            [0, 0, 1, 0],
            [1, 0, 1, 1],
            [-1, 0, 1, -1],
            [0, 1, 1, 1],
            [0, -1, 1, -1],
            [0, 1, -1, -1],
            [-5, -1, 2, -5.5],
            [-5, 1, 2, -4.5],
            [0, 1, 2, .5],
            [0, -1, 2, -.5],
        ];
    }
    
    /**
     * @testCase  normalization throws an Exception\BadDataException if the denominator is zero
     */
    public function testNormalizeException()
    {
        $this->expectException(Exception\BadDataException::class);
        $number = new RationalNumber(1, 1, 0);
    }

    /**
     * @testCase     Abs returns the correct number
     * @dataProvider dataProviderForAbs
     * @param        number $w
     * @param        number $n
     * @param        number $d
     * @param        array $result
     */
    public function testAbs($w, $n, $d, $result)
    {
        $number = new RationalNumber($w, $n, $d);
        $result_rn = new RationalNumber(...$result);
        $this->assertTrue($number->abs()->equals($result_rn));
    }
    
    public function dataProviderForAbs(): array
    {
        return [
            [0, 0, 1, [0, 0, 1]],
            [1, 0, 1, [1, 0, 1]],
            [-1, 0, 1, [1, 0, 1]],
            [0, 1, 1, [1, 0, 1]],
            [0, -1, 1, [1, 0, 1]],
            [0, 1, -1, [1, 0, 1]],
            [-5, -1, 2, [5, 1, 2]],
            [-5, 1, 2, [4, 1, 2]],
            [0, 1, 2, [0, 1, 2]],
            [0, -1, 2, [0, 1, 2]],
        ];
    }

    /**
     * @testCase     Add returns the correct number
     * @dataProvider dataProviderForAdd
     * @param        array $rn1
     * @param        array $rn2
     * @param        array $result
     */
    public function testAdd($rn1, $rn2, $result)
    {
        $rational_number_1 = new RationalNumber($rn1[0], $rn1[1], $rn1[2]);
        $rational_number_2 = new RationalNumber($rn2[0], $rn2[1], $rn2[2]);
        $result_rn = new RationalNumber(...$result);
        $this->assertTrue($rational_number_1->add($rational_number_2)->equals($result_rn));
    }
    
    public function dataProviderForAdd(): array
    {
        return [
            [[0, 0, 1], [0, 0, 1], [0, 0, 1]],
            [[1, 0, 1], [1, 0, 1], [2, 0, 1]],
            [[1, 0, 1], [-1, 0, 1], [0, 0, 1]],
            [[-5, -1, 2], [0, 1, 2], [-5, 0, 1]],
            [[15, 0, 1], [0, 3, 4], [15, 3, 4]],
        ];
    }

    /**
     * @testCase     Subtract returns the correct number
     * @dataProvider dataProviderForSubtract
     * @param        array $rn1
     * @param        array $rn2
     * @param        float $result
     */
    public function testSubtract($rn1, $rn2, $result)
    {
        $rational_number_1 = new RationalNumber($rn1[0], $rn1[1], $rn1[2]);
        $rational_number_2 = new RationalNumber($rn2[0], $rn2[1], $rn2[2]);
        $result_rn = new RationalNumber(...$result);
        $this->assertTrue($rational_number_1->subtract($rational_number_2)->equals($result_rn));
    }
    
    public function dataProviderForSubtract(): array
    {
        return [
            [[0, 0, 1], [0, 0, 1], [0, 0, 1]],
            [[1, 0, 1], [1, 0, 1], [0, 0, 1]],
            [[1, 0, 1], [-1, 0, 1], [2, 0, 1]],
            [[-5, -1, 2], [0, 1, 2], [-6, 0, 1]],
            [[15, 0, 1], [0, 3, 4], [14, 1, 4]],
        ];
    }

    /**
     * @testCase     Multiply returns the correct number
     * @dataProvider dataProviderForMultiply
     * @param        array $rn1
     * @param        array $rn2
     * @param        float $result
     */
    public function testMultiply($rn1, $rn2, $result)
    {
        $rational_number_1 = new RationalNumber($rn1[0], $rn1[1], $rn1[2]);
        $rational_number_2 = new RationalNumber($rn2[0], $rn2[1], $rn2[2]);
        $result_rn = new RationalNumber(...$result);
        $this->assertTrue($rational_number_1->multiply($rational_number_2)->equals($result_rn));
    }
    
    public function dataProviderForMultiply(): array
    {
        return [
            [[0, 0, 1], [0, 0, 1], [0, 0, 1]],
            [[1, 0, 1], [1, 0, 1], [1, 0, 1]],
            [[1, 0, 1], [-1, 0, 1], [-1, 0, 1]],
            [[-5, -1, 2], [0, 1, 2], [-2, -3, 4]],
            [[15, 0, 1], [0, 3, 4], [11, 1, 4]],
        ];
    }

    /**
     * @testCase     Divide returns the correct number
     * @dataProvider dataProviderForDivide
     * @param        array $rn1
     * @param        array $rn2
     * @param        float $result
     */
    public function testDivide($rn1, $rn2, $result)
    {
        $rational_number_1 = new RationalNumber($rn1[0], $rn1[1], $rn1[2]);
        $rational_number_2 = new RationalNumber($rn2[0], $rn2[1], $rn2[2]);
        $result_rn = new RationalNumber(...$result);
        $this->assertTrue($rational_number_1->divide($rational_number_2)->equals($result_rn));
    }
    
    public function dataProviderForDivide(): array
    {
        return [
            [[1, 0, 1], [1, 0, 1], [1, 0, 1]],
            [[1, 0, 1], [-1, 0, 1], [-1, 0, 1]],
            [[3, 4, 2], [3, 5, 2], [0, 10, 11]],
            [[-5, -1, 2], [0, 1, 2], [-11, 0, 1]],
            [[15, 0, 1], [0, 3, 4], [20, 0, 1]],
        ];
    }

    /**
     * @testCase     Add int returns the correct number
     * @dataProvider dataProviderForAddInt
     * @param        array $rn
     * @param        int $int
     * @param        float $result
     */
    public function testAddInt($rn, $int, $result)
    {
        $rational_number = new RationalNumber($rn[0], $rn[1], $rn[2]);
        $result_rn = new RationalNumber(...$result);
        $this->assertTrue($rational_number->add($int)->equals($result_rn));
    }
    
    public function dataProviderForAddInt(): array
    {
        return [
            [[1, 0, 1], 0, [1, 0, 1]],
            [[1, 0, 1], -1, [0, 0, 1]],
            [[3, 5, 2], 10, [15, 1, 2]],
            [[-5, -1, 2], -4, [-9, -1, 2]],
            [[15, 6, 13], -15, [0, 6, 13]],
        ];
    }

    /**
     * @testCase     Subtract int returns the correct number
     * @dataProvider dataProviderForSubtractInt
     * @param        array $rn
     * @param        int $int
     * @param        float $result
     */
    public function testSubtractInt($rn, $int, $result)
    {
        $rational_number = new RationalNumber($rn[0], $rn[1], $rn[2]);
        $result_rn = new RationalNumber(...$result);
        $this->assertTrue($rational_number->subtract($int)->equals($result_rn));
    }
    
    public function dataProviderForSubtractInt(): array
    {
        return [
            [[1, 0, 1], 0, [1, 0, 1]],
            [[1, 0, 1], -1, [2, 0, 1]],
            [[3, 5, 2], 10, [-4, -1, 2]],
            [[-5, -1, 2], -4, [-1, -1, 2]],
            [[15, 6, 13], -15, [30, 6, 13]],
        ];
    }

    /**
     * @testCase     Divide int returns the correct number
     * @dataProvider dataProviderForDivideInt
     * @param        array $rn
     * @param        int $int
     * @param        float $result
     */
    public function testDivideInt($rn, $int, $result)
    {
        $rational_number = new RationalNumber($rn[0], $rn[1], $rn[2]);
        $result_rn = new RationalNumber(...$result);
        $this->assertTrue($rational_number->divide($int)->equals($result_rn));
    }
    
    public function dataProviderForDivideInt(): array
    {
        return [
            [[1, 0, 1], 1, [1, 0, 1]],
            [[1, 0, 1], -1, [-1, 0, 1]],
            [[3, 5, 2], 10, [0, 11, 20]],
            [[-5, -1, 2], -4, [1, 3, 8]],
            [[15, 6, 13], -15, [-1, -2, 65]],
        ];
    }

    /**
     * @testCase  Adding a float throws an exception
     */
    public function testAddException()
    {
        $this->expectException(Exception\IncorrectTypeException::class);
        $number = new RationalNumber(1, 0, 1);
        $number->add(1.5);
    }

    /**
     * @testCase  Subtracting a float throws an exception
     */
    public function testSubtractException()
    {
        $this->expectException(Exception\IncorrectTypeException::class);
        $number = new RationalNumber(1, 0, 1);
        $number->subtract(1.5);
    }

    /**
     * @testCase  multiplying a float throws an exception
     */
    public function testMultiplyException()
    {
        $this->expectException(Exception\IncorrectTypeException::class);
        $number = new RationalNumber(1, 0, 1);
        $number->multiply(1.5);
    }

    /**
     * @testCase  Dividing a float throws an exception
     */
    public function testDivideException()
    {
        $this->expectException(Exception\IncorrectTypeException::class);
        $number = new RationalNumber(1, 0, 1);
        $number->divide(1.5);
    }
}
