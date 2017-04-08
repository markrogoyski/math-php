<?php
namespace MathPHP\Number;

class ComplexTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $complex = new Complex(1, 1);
        $string = $complex->__toString();
        $this->assertTrue(is_string($string));
        $this->assertEquals(
            "(1+1i)",
            $string
        );
    }
    /**
     * @dataProvider dataProviderForGetR
     */
    public function testGetR($r, $i)
    {
        $c = new Complex($r, $i);
        $this->assertEquals($r, $c->getR());
    }
    public function dataProviderForGetR()
    {
        return [
            [1, 2],
        ];
    }

    /**
     * @dataProvider dataProviderForGetI
     */
    public function testGetI($r, $i)
    {
        $c = new Complex($r, $i);
        $this->assertEquals($i, $c->getI());
    }
    public function dataProviderForGetI()
    {
        return [
            [1, 2],
        ];
    }
    
    /**
     * @dataProvider dataProviderForComplexConjugate
     */
    public function testComplexConjugate($r, $i)
    {
        $c = new Complex($r, $i);
        $cc = $c->complexConjugate();
        $this->assertEquals($c->getR(), $cc->getR());
        $this->assertEquals($c->getI(), -1 * $cc->getI());
    }
    public function dataProviderForComplexConjugate()
    {
        return [
            [1, 2],
        ];
    }

    /**
     * @dataProvider dataProviderForAbs
     */
    public function testAbs($r, $i, $expected)
    {
        $c = new Complex($r, $i);
        $this->assertEquals($expected, $c->abs());
    }
    public function dataProviderForAbs()
    {
        return [
            [1, 2, sqrt(5)],
        ];
    }

    /**
     * @dataProvider dataProviderForArg
     */
    public function testArg($r, $i, $expected)
    {
        $c = new Complex($r, $i);
        $this->assertEquals($expected, $c->arg());
    }
    public function dataProviderForArg()
    {
        return [
            [0, 1, pi() / 2],
            [0, -1, pi() / -2,],
        ];
    }

    /**
     * @dataProvider dataProviderForsqrt
     */
    public function testSqrt($complex_array, $expected_array)
    {
        $c = new Complex($complex_array[0], $complex_array[1]);
        $result = $c->sqrt();
        $this->assertEquals($expected_array[0], $result->getR());
        $this->assertEquals($expected_array[1], $result->getI());
    }
    public function dataProviderForSqrt()
    {
        return [
            [
                [8, -6],
                [3, -1],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForAdd
     */
    public function testAdd($complex_array1, $complex_array2, $expected_array)
    {
        $c1 = new Complex($complex_array1[0], $complex_array1[1]);
        $c2 = new Complex($complex_array2[0], $complex_array2[1]);
        $result = $c1->add($c2);
        $this->assertEquals($expected_array[0], $result->getR());
        $this->assertEquals($expected_array[1], $result->getI());
    }
    public function dataProviderForAdd()
    {
        return [
            [
                [3, 2],
                [4, -3],
                [7, -1],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForAddReal
     */
    public function testAddReal($complex_array, $real, $expected_array)
    {
        $c = new Complex($complex_array[0], $complex_array[1]);
        $result = $c->add($real);
        $this->assertEquals($expected_array[0], $result->getR());
        $this->assertEquals($expected_array[1], $result->getI());
    }
    public function dataProviderForAddReal()
    {
        return [
            [
                [3, 2],
                5,
                [8, 2],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForSubtract
     */
    public function testSubtract($complex_array1, $complex_array2, $expected_array)
    {
        $c1 = new Complex($complex_array1[0], $complex_array1[1]);
        $c2 = new Complex($complex_array2[0], $complex_array2[1]);
        $result = $c1->subtract($c2);
        $this->assertEquals($expected_array[0], $result->getR());
        $this->assertEquals($expected_array[1], $result->getI());
    }
    public function dataProviderForSubtract()
    {
        return [
            [
                [3, 2],
                [4, -3],
                [-1, 5],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForDivide
     */
    public function testDivide($complex_array1, $complex_array2, $expected_array)
    {
        $c1 = new Complex($complex_array1[0], $complex_array1[1]);
        $c2 = new Complex($complex_array2[0], $complex_array2[1]);
        $result = $c1->divide($c2);
        $this->assertEquals($expected_array[0], $result->getR());
        $this->assertEquals($expected_array[0], $result->r);
        $this->assertEquals($expected_array[1], $result->getI());
    }
    public function dataProviderForDivide()
    {
        return [
            [
                [3, 2],
                [4, -3],
                [.24, .68],
            ],
        ];
    }

    public function testComplexAddException()
    {
        $complex = new Complex(1, 1);
        $this->setExpectedException('MathPHP\Exception\IncorrectTypeException');
        $complex->add("string");
    }

    public function testComplexSubtractException()
    {
        $complex = new Complex(1, 1);
        $this->setExpectedException('MathPHP\Exception\IncorrectTypeException');
        $complex->subtract("string");
    }

    public function testComplexMultiplyException()
    {
        $complex = new Complex(1, 1);
        $this->setExpectedException('MathPHP\Exception\IncorrectTypeException');
        $complex->multiply("string");
    }

    public function testComplexDivideException()
    {
        $complex = new Complex(1, 1);
        $this->setExpectedException('MathPHP\Exception\IncorrectTypeException');
        $complex->divide("string");
    }

    public function testComplexGetException()
    {
        $complex = new Complex(1, 1);
        $this->setExpectedException('MathPHP\Exception\BadParameterException');
        $complex->p;
    }
}
