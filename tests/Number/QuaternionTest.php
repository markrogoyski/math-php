<?php

namespace MathPHP\Tests\Number;

use MathPHP\Number\Quaternion;
use MathPHP\Exception;
use MathPHP\Number\ObjectArithmetic;

class QuaternionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test Interfaces
     */
    public function testObjectArithmeticInterface()
    {
        // Given
        $c = new Quaternion(1, 2, 3, 4);

        // Then
        $this->assertInstanceOf(ObjectArithmetic::class, $c);
    }

    public function testZeroValue()
    {
        // Given
        $c = Quaternion::createZeroValue();

        // Then
        $this->assertEquals(0, $c->r);
        $this->assertEquals(0, $c->i);
        $this->assertEquals(0, $c->j);
        $this->assertEquals(0, $c->k);
    }

    /**
     * @test         __toString returns the proper string representation of a quaternion
     * @dataProvider dataProviderForToString
     * @param        number $r
     * @param        number $i
     * @param        number $j
     * @param        number $k
     * @param        string $expected
     */
    public function testToString($r, $i, $j, $k, string $expected)
    {
        // Given
        $c = new Quaternion($r, $i, $j, $k);

        // When
        $string = $c->__toString();

        // Then
        $this->assertEquals($expected, $string);
        $this->assertEquals($expected, (string) $c);
    }

    public function dataProviderForToString(): array
    {
        return [
            [0, 0, 0, 0, '0'],
            [1, 0, 0, 0, '1'],
            [-1, 0, 0, 0, '-1'],
            [0, 1, 0, 0, '1i'],
            [0, -1, 0, 0, '-1i'],
            [1, 0, 1, 0, '1 + 1j'],
            [1, 0, 0, 2, '1 + 2k'],
            [2, 0, -1, 0, '2 - 1j'],
            [2, 0, 0, -2, '2 - 2k'],
            [1, 0, -1, -1, '1 - 1j - 1k'],
            [1, 1, -2, 4, '1 + 1i - 2j + 4k'],
        ];
    }

    /**
     * @test __get returns r, i, j, and k
     */
    public function testGet()
    {
        // Given
        $r       = 1;
        $i       = 2;
        $j       = 3;
        $k       = 4;
        $c       = new Quaternion($r, $i, $j, $k);

        // Then
        $this->assertEquals($r, $c->r);
        $this->assertEquals($i, $c->i);
        $this->assertEquals($j, $c->j);
        $this->assertEquals($k, $c->k);
    }

    /**
     * @test __get throws an Exception\BadParameterException if a property other than r or i is attempted
     */
    public function testGetException()
    {
        // Given
        $r       = 1;
        $i       = 2;
        $j       = 3;
        $k       = 4;
        $c       = new Quaternion($r, $i, $j, $k);

        // Then
        $this->expectException(Exception\BadParameterException::class);

        // When
        $z = $c->z;
    }

    /**
     * @test         complexConjugate returns the expected Quaternion
     * @dataProvider dataProviderForComplexConjugate
     * @param        number $r
     * @param        number $i
     * @param        number $j
     * @param        number $k
     */
    public function testComplexConjugate($r, $i, $j, $k)
    {
        // Given
        $c = new Quaternion($r, $i , $j, $k);

        // When
        $cc = $c->complexConjugate();

        // Then
        $this->assertEquals($c->r, $cc->r);
        $this->assertEquals($c->i, -1 * $cc->i);
        $this->assertEquals($c->j, -1 * $cc->j);
        $this->assertEquals($c->k, -1 * $cc->k);
    }

    public function dataProviderForComplexConjugate(): array
    {
        return [
            [0, 0, 0, 0],
            [1, 0, 0, 0],
            [0, 1, 0, 0],
            [0, 1, 1, 1],
            [1, 1, -1, -1],
            [1, 2, 3, 4],
            [3, 7, 11, -13],
        ];
    }

    /**
     * @test         abs returns the expected value
     * @dataProvider dataProviderForAbs
     * @param        number $r
     * @param        number $i
     * @param        number $j
     * @param        number $k
     * @param        number $expected
     */
    public function testAbs($r, $i, $j, $k, $expected)
    {
        // Given
        $c = new Quaternion($r, $i , $j, $k);

        // When
        $abs = $c->abs();

        // Then
        $this->assertEquals($expected, $abs);
    }

    public function dataProviderForAbs(): array
    {
        return [
            [0, 0, 0, 0, 0],
            [1, 0, 0, 0, 1],
            [0, 1, 0, 0, 1],
            [0, 0, 1, 0, 1],
            [0, 0, 0, 1, 1],
            [1, 2, 3, 4, \sqrt(30)],
            [2, 1, \sqrt(5)],
            [2, 2, \sqrt(8)],
            [-1, 0, 0, 0, 1],
            [0, -1, 0, 0, 1],
            [-1, 2, -3, 4, \sqrt(30)],
        ];
    }
}
