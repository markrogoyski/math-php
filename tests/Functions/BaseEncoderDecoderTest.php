<?php

namespace MathPHP\Tests\Functions;

use MathPHP\Exception;
use MathPHP\Functions\BaseEncoderDecoder;
use MathPHP\Number\ArbitraryInteger;

class BaseEncoderDecoderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @dataProvider dataProviderForTestToBase
     */
    public function testToBase(string $int, int $base, string $expected)
    {
        $int = new ArbitraryInteger($int);
        $this->assertEquals($expected, BaseEncoderDecoder::toBase($int, $base));
    }

    public function dataProviderForTestToBase()
    {
        return [
           ['0xf', 16, 'f'],
           ['100', 256, chr(100)],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderForTestCreateArbitrary
     */
    public function testCreateArbitrary(string $int, int $base, string $expected)
    {
        $int = BaseEncoderDecoder::createArbitraryInteger($int, $base);
        $this->assertEquals($expected, (string) $int);
    }

    public function dataProviderForTestCreateArbitrary()
    {
        return [
            ['123', 10, '123'],
            ['7b', 16, '123'],
            [chr(123), 256, '123'],
        ];
    }

    /**
     * @test     toBase throws an exception when base>256
     * @throws   BadParameterException
     */
    public function testInvalidToBaseException()
    {
        // Given
        $base = 300;
        $int =  new ArbitraryInteger('123456');
        // Then
        $this->expectException(Exception\BadParameterException::class);
        // When
        $string =  BaseEncoderDecoder::toBase($int, $base);
    }

    /**
     * @test     Function throws an exception when given an empty string
     * @throws   \Exception
     */
    public function testEmptyStringException()
    {
        // Given
        $number = "";

        // Then
        $this->expectException(Exception\BadParameterException::class);

        // When
        $int =  BaseEncoderDecoder::createArbitraryInteger($number, 10);
    }

    /**
     * @test     Function throws an exception when base>256
     * @throws   \Exception
     */
    public function testInvalidBaseException()
    {
        // Given
        $base = 300;

        // Then
        $this->expectException(Exception\BadParameterException::class);

        // When
        $int =  BaseEncoderDecoder::createArbitraryInteger('123456', $base);
    }
}
