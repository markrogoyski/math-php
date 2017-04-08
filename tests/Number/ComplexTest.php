<?php
namespace MathPHP\Number;

use MathPHP\Exception;

class ComplexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @testCase     __toString returns the proper string representation of a complex number
     * @dataProvider dataProviderForToString
     * @param        number $r
     * @param        number $i
     * @param        string $string
     */
    public function testToString($r, $i, string $string)
    {
        $complex = new Complex($r, $i);
        $this->assertEquals($string, $complex->__toString());
        $this->assertEquals($string, (string) $complex);
    }

    public function dataProviderForToString(): array
    {
        return [
            [0, 0, '0'],
            [1, 0, '1'],
            [-1, 0, '-1'],
            [0, 1, '1i'],
            [0, -1, '-1i'],
            [1, 1, '1 + 1i'],
            [1, 2, '1 + 2i'],
            [2, 1, '2 + 1i'],
            [2, 2, '2 + 2i'],
            [1, -1, '1 - 1i'],
            [1, -2, '1 - 2i'],
            [2, -1, '2 - 1i'],
            [2, -2, '2 - 2i'],
            [-1, 1, '-1 + 1i'],
            [-1, 2, '-1 + 2i'],
            [-2, 1, '-2 + 1i'],
            [-2, 2, '-2 + 2i'],
            [-1, -1, '-1 - 1i'],
            [-1, -2, '-1 - 2i'],
            [-2, -1, '-2 - 1i'],
            [-2, -2, '-2 - 2i'],
        ];
    }

    /**
     * @testCase __get returns r and i
     */
    public function testGet()
    {
        $r       = 1;
        $i       = 2;
        $complex = new Complex($r, $i);

        $this->assertEquals($r, $complex->r);
        $this->assertEquals($i, $complex->i);
    }

    /**
     * @testCase __get throws an Exception\BadParameterException if a property other than r or i is attempted
     */
    public function testGetException()
    {
        $r       = 1;
        $i       = 2;
        $complex = new Complex($r, $i);

        $this->setExpectedException(Exception\BadParameterException::class);
        $z = $complex->z;
    }
    
    /**
     * @testCase     complexConjugate returns the expected Complex number
     * @dataProvider dataProviderForComplexConjugate
     * @param        number $r
     * @param        number $i
     */
    public function testComplexConjugate($r, $i)
    {
        $c  = new Complex($r, $i);
        $cc = $c->complexConjugate();

        $this->assertEquals($c->r, $cc->r);
        $this->assertEquals($c->i, -1 * $cc->i);
    }

    public function dataProviderForComplexConjugate(): array
    {
        return [
            [0, 0],
            [1, 0],
            [0, 1],
            [1, 1],
            [1, 2],
            [3, 7],
        ];
    }

    /**
     * @testCase     abs returns the expected value
     * @dataProvider dataProviderForAbs
     * @param        number $r
     * @param        number $i
     * @param        number $expected
     */
    public function testAbs($r, $i, $expected)
    {
        $c = new Complex($r, $i);
        $this->assertEquals($expected, $c->abs());
    }

    public function dataProviderForAbs(): array
    {
        return [
            [0, 0, 0],
            [1, 0, 1],
            [0, 1, 1],
            [1, 2, sqrt(5)],
            [2, 1, sqrt(5)],
            [2, 2, sqrt(8)],
            [-1, 0, 1],
            [0, -1, 1],
            [-1, 2, sqrt(5)],
            [2, -1, sqrt(5)],
            [-2, -2, sqrt(8)],
        ];
    }

    /**
     * @testCase     arg returns the expected value
     * @dataProvider dataProviderForArg
     * @param        number $r
     * @param        number $i
     * @param        number $expected
     */
    public function testArg($r, $i, $expected)
    {
        $c = new Complex($r, $i);
        $this->assertEquals($expected, $c->arg());
    }

    public function dataProviderForArg(): array
    {
        return [
            [0, 1, \M_PI / 2],
            [0, -1, \M_PI / -2],
        ];
    }

    /**
     * @testCase     sqrt returns the expected positive Complex root
     * @dataProvider dataProviderForsqrt
     * @param        number $r
     * @param        number $i
     * @param        number $expected_r
     * @param        number $expected_i
     */
    public function testSqrt($r, $i, $expected_r, $expected_i)
    {
        $c    = new Complex($r, $i);
        $sqrt = $c->sqrt();
        $this->assertEquals($expected_r, $sqrt->r, '', 0.00001);
        $this->assertEquals($expected_i, $sqrt->i, '', 0.00001);
    }

    public function dataProviderForSqrt(): array
    {
        return [
            [8, -6, 3, -1],
            [9, 4, sqrt((9 + sqrt(97))/2), 2 * (sqrt(2/(9 + sqrt(97))))],
            [-4, -6, 1.2671, -2.3676],
        ];
    }

    /**
     * @testCase     add of two complex numbers returns the expected complex number
     * @dataProvider dataProviderForAdd
     * @param        array  $complex1
     * @param        array  $complex2
     * @param        array  $expected
     */
    public function testAdd(array $complex1, array $complex2, array $expected)
    {
        $c1     = new Complex($complex1['r'], $complex1['i']);
        $c2     = new Complex($complex2['r'], $complex2['i']);
        $result = $c1->add($c2);

        $this->assertEquals($expected['r'], $result->r);
        $this->assertEquals($expected['i'], $result->i);
    }

    public function dataProviderForAdd(): array
    {
        return [
            [
                ['r' => 3, 'i' => 2],
                ['r' => 4, 'i' => -3],
                ['r' => 7, 'i' => -1],
            ],
            [
                ['r' => 0, 'i' => 0],
                ['r' => 4, 'i' => -3],
                ['r' => 4, 'i' => -3],
            ],
            [
                ['r' => -3, 'i' => -2],
                ['r' => 4, 'i' => 3],
                ['r' => 1, 'i' => 1],
            ],
            [
                ['r' => 7, 'i' => 6],
                ['r' => 4, 'i' => 4],
                ['r' => 11, 'i' => 10],
            ],
        ];
    }

    /**
     * @testCase     add of real numbers returns the expected complex number
     * @dataProvider dataProviderForAddReal
     */
    public function testAddReal($complex, $real, $expected)
    {
        $c      = new Complex($complex['r'], $complex['i']);
        $result = $c->add($real);

        $this->assertEquals($expected['r'], $result->r);
        $this->assertEquals($expected['i'], $result->i);
    }

    public function dataProviderForAddReal()
    {
        return [
            [
                ['r' => 3, 'i' => 2],
                5,
                ['r' => 8, 'i' => 2],
            ],
            [
                ['r' => 0, 'i' => 0],
                5,
                ['r' => 5, 'i' => 0],
            ],
            [
                ['r' => 3, 'i' => 2],
                -2,
                ['r' => 1, 'i' => 2],
            ],
        ];
    }

    /**
     * @testCase     subtract of two complex numbers returns the expected complex number
     * @dataProvider dataProviderForSubtract
     * @param        array  $complex1
     * @param        array  $complex2
     * @param        array  $expected
     */
    public function testSubtract(array $complex1, array $complex2, array $expected)
    {
        $c1     = new Complex($complex1['r'], $complex1['i']);
        $c2     = new Complex($complex2['r'], $complex2['i']);
        $result = $c1->subtract($c2);

        $this->assertEquals($expected['r'], $result->r);
        $this->assertEquals($expected['i'], $result->i);
    }

    public function dataProviderForSubtract(): array
    {
        return [
            [
                ['r' => 3, 'i' => 2],
                ['r' => 4, 'i' => -3],
                ['r' => -1, 'i' => 5],
            ],
            [
                ['r' => 3, 'i' => 2],
                ['r' => 4, 'i' => -3],
                ['r' => -1, 'i' => 5],
            ],
            [
                ['r' => 0, 'i' => 0],
                ['r' => 4, 'i' => -3],
                ['r' => -4, 'i' => 3],
            ],
            [
                ['r' => -3, 'i' => -2],
                ['r' => 4, 'i' => 3],
                ['r' => -7, 'i' => -5],
            ],
            [
                ['r' => 7, 'i' => 6],
                ['r' => 4, 'i' => 4],
                ['r' => 3, 'i' => 2],
            ],
        ];
    }


    /**
     * @testCase     subtract of real numbers returns the expected complex number
     * @dataProvider dataProviderForSubtractReal
     */
    public function testSubtractReal($complex, $real, $expected)
    {
        $c      = new Complex($complex['r'], $complex['i']);
        $result = $c->subtract($real);

        $this->assertEquals($expected['r'], $result->r);
        $this->assertEquals($expected['i'], $result->i);
    }

    public function dataProviderForSubtractReal()
    {
        return [
            [
                ['r' => 3, 'i' => 2],
                5,
                ['r' => -2, 'i' => 2],
            ],
            [
                ['r' => 0, 'i' => 0],
                5,
                ['r' => -5, 'i' => 0],
            ],
            [
                ['r' => 3, 'i' => 2],
                -2,
                ['r' => 5, 'i' => 2],
            ],
        ];
    }

    /**
     * @testCase     multiply of two complex numbers returns the expected complex number
     * @dataProvider dataProviderForMultiply
     * @param        array  $complex1
     * @param        array  $complex2
     * @param        array  $expected
     */
    public function testMultiply(array $complex1, array $complex2, array $expected)
    {
        $c1     = new Complex($complex1['r'], $complex1['i']);
        $c2     = new Complex($complex2['r'], $complex2['i']);
        $result = $c1->multiply($c2);

        $this->assertEquals($expected['r'], $result->r);
        $this->assertEquals($expected['i'], $result->i);
    }

    public function dataProviderForMultiply(): array
    {
        return [
            [
                ['r' => 3, 'i' => 2],
                ['r' => 1, 'i' => 4],
                ['r' => -5, 'i' => 14],
            ],
            [
                ['r' => 3, 'i' => 13],
                ['r' => 7, 'i' => 17],
                ['r' => -200, 'i' => 142],
            ],
            [
                ['r' => 6, 'i' => 8],
                ['r' => 4, 'i' => -9],
                ['r' => 96, 'i' => -22],
            ],
            [
                ['r' => -56, 'i' => 3],
                ['r' => -84, 'i' => -4],
                ['r' => 4716, 'i' => -28],
            ],
        ];
    }

    /**
     * @testCase     multiply of real numbers returns the expected complex number
     * @dataProvider dataProviderForMultiplyReal
     */
    public function testMultiplyReal($complex, $real, $expected)
    {
        $c      = new Complex($complex['r'], $complex['i']);
        $result = $c->multiply($real);

        $this->assertEquals($expected['r'], $result->r);
        $this->assertEquals($expected['i'], $result->i);
    }

    public function dataProviderForMultiplyReal()
    {
        return [
            [
                ['r' => 3, 'i' => 1],
                2,
                ['r' => 6, 'i' => 2],
            ],
            [
                ['r' => 30, 'i' => 13],
                2,
                ['r' => 60, 'i' => 26],
            ],
        ];
    }

    /**
     * @testCase     divide of two complex numbers returns the expected complex number
     * @dataProvider dataProviderForDivide
     * @param        array  $complex1
     * @param        array  $complex2
     * @param        array  $expected
     */
    public function testDivide(array $complex1, array $complex2, array $expected)
    {
        $c1     = new Complex($complex1['r'], $complex1['i']);
        $c2     = new Complex($complex2['r'], $complex2['i']);
        $result = $c1->divide($c2);

        $this->assertEquals($expected['r'], $result->r);
        $this->assertEquals($expected['i'], $result->i);
    }

    public function dataProviderForDivide(): array
    {
        return [
            [
                ['r' => 3, 'i' => 2],
                ['r' => 4, 'i' => -3],
                ['r' => 0.24, 'i' => 0.68],
            ],
            [
                ['r' => 5, 'i' => 5],
                ['r' => 6, 'i' => 2],
                ['r' => 1, 'i' => 1/2],
            ],
            [
                ['r' => 6, 'i' => 2],
                ['r' => 7, 'i' => -7],
                ['r' => 2/7, 'i' => 4/7],
            ],
            [
                ['r' => -56, 'i' => 3],
                ['r' => -84, 'i' => -4],
                ['r' => 69/104, 'i' => -7/104],
            ],
        ];
    }

    /**
     * @testCase     divide of real numbers returns the expected complex number
     * @dataProvider dataProviderForDivideReal
     */
    public function testDivideReal($complex, $real, $expected)
    {
        $c      = new Complex($complex['r'], $complex['i']);
        $result = $c->divide($real);

        $this->assertEquals($expected['r'], $result->r);
        $this->assertEquals($expected['i'], $result->i);
    }

    public function dataProviderForDivideReal()
    {
        return [
            [
                ['r' => 4, 'i' => 1],
                2,
                ['r' => 2, 'i' => 1/2],
            ],
            [
                ['r' => 60, 'i' => 9],
                3,
                ['r' => 20, 'i' => 3],
            ],
        ];
    }

    /**
     * @testCase add throws an Exception\IncorrectTypeException when the argument is not a number or complex number
     * @return [type] [description]
     */
    public function testComplexAddException()
    {
        $complex = new Complex(1, 1);
        $this->setExpectedException(Exception\IncorrectTypeException::class);
        $complex->add("string");
    }

    /**
     * @testCase subtract throws an Exception\IncorrectTypeException when the argument is not a number or complex number
     * @return [type] [description]
     */
    public function testComplexSubtractException()
    {
        $complex = new Complex(1, 1);
        $this->setExpectedException(Exception\IncorrectTypeException::class);
        $complex->subtract("string");
    }

    /**
     * @testCase multiply throws an Exception\IncorrectTypeException when the argument is not a number or complex number
     * @return [type] [description]
     */
    public function testComplexMultiplyException()
    {
        $complex = new Complex(1, 1);
        $this->setExpectedException(Exception\IncorrectTypeException::class);
        $complex->multiply("string");
    }

    /**
     * @testCase divide throws an Exception\IncorrectTypeException when the argument is not a number or complex number
     * @return [type] [description]
     */
    public function testComplexDivideException()
    {
        $complex = new Complex(1, 1);
        $this->setExpectedException(Exception\IncorrectTypeException::class);
        $complex->divide("string");
    }

    /**
     * @testCase     inverse returns the expected complex number
     * @dataProvider dataProviderForInverse
     * @param        number $r
     * @param        number $i
     * @param        number $expected_r
     * @param        number $expected_i
     */
    public function testInverse($r, $i, $expected_r, $expected_i)
    {
        $c       = new Complex($r, $i);
        $inverse = $c->inv();

        $this->assertEquals($expected_r, $inverse->r);
        $this->assertEquals($expected_i, $inverse->i);
    }

    public function dataProviderForInverse(): array
    {
        return [

            [1, 0, 1, 0],
            [0, 1, 0, -1],
            [1, 1, 1/2, -1/2],
            [4, 6, 1/13, -3/26],
            [-4, 6, -1/13, -3/26],
            [4, -6, 1/13, 3/26],
            [-4, -6, -1/13, 3/26],
        ];
    }

    /**
     * @testCase inverse throws an Exception\BadDataException when value is 0 + 0i
     */
    public function testInverseException()
    {
        $complex = new Complex(0, 0);
        $this->setExpectedException(Exception\BadDataException::class);
        $complex->inv();
    }

    /**
     * @testCase     equals returns true if the complex numbers are the same
     * @dataProvider dataProviderForComplexNumbers
     * @param        number $r
     * @param        number $i
     */
    public function testEqualsTrue($r, $i)
    {
        $c1 = new Complex($r, $i);
        $c2 = new Complex($r, $i);

        $this->assertTrue($c1->Equals($c2));
    }

    /**
     * @testCase     equals returns false if the complex numbers are different
     * @dataProvider dataProviderForComplexNumbers
     * @param        number $r
     * @param        number $i
     */
    public function testEqualsFalse($r, $i)
    {
        $c1 = new Complex($r, $i);
        $c2 = new Complex($r + 1, $i - 1);

        $this->assertFalse($c1->Equals($c2));
    }

    public function dataProviderForComplexNumbers(): array
    {
        return [
            [0, 0],
            [1, 0],
            [0, 1],
            [1, 1],
            [1, 2],
            [2, 1],
            [2, 2],
            [3, 4],
            [5, 3],
            [-1, 0],
            [0, 1],
            [-1, 1],
            [-1, 2],
            [-2, 1],
            [-2, 2],
            [-3, 4],
            [-5, 3],
            [1, 0],
            [0, -1],
            [1, -1],
            [1, -2],
            [2, -1],
            [2, -2],
            [3, -4],
            [5, -3],
            [-1, 0],
            [0, -1],
            [-1, -1],
            [-1, -2],
            [-2, -1],
            [-2, -2],
            [-3, -4],
            [-5, -3],
        ];
    }
}
