<?php

namespace MathPHP\Tests\Functions;

use MathPHP\Exception;
use MathPHP\Functions\BaseEncoderDecoder;
use MathPHP\Number\ArbitraryInteger;

class BaseEncoderDecoderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         toBase
     * @dataProvider dataProviderForTestToBase
     * @param        string $int
     * @param        int    $base
     * @param        string $expected
     * @throws       \Exception
     */
    public function testToBase(string $int, int $base, string $expected)
    {
        // Given
        $int = new ArbitraryInteger($int);

        // When
        $toBase = BaseEncoderDecoder::toBase($int, $base);

        // Theb
        $this->assertEquals($expected, $toBase);
    }

    public function dataProviderForTestToBase(): array
    {
        return [
           ['0xf', 16, 'f'],
           ['100', 256, \chr(100)],
           // Edge case: zero
           ['0', 2, '0'],
           ['0', 10, '0'],
           ['0', 16, '0'],
           ['0', 256, \chr(0)],
           // Base 2 (binary)
           ['1', 2, '1'],
           ['2', 2, '10'],
           ['7', 2, '111'],
           ['255', 2, '11111111'],
           // Base 8 (octal)
           ['8', 8, '10'],
           ['64', 8, '100'],
           ['255', 8, '377'],
           // Base 10 (decimal)
           ['1', 10, '1'],
           ['10', 10, '10'],
           ['999', 10, '999'],
           // Base 16 (hexadecimal)
           ['16', 16, '10'],
           ['255', 16, 'ff'],
           ['4096', 16, '1000'],
           ['65535', 16, 'ffff'],
           // Base 256 (single byte representation)
           ['1', 256, \chr(1)],
           ['255', 256, \chr(255)],
           ['256', 256, \chr(1) . \chr(0)],
           ['257', 256, \chr(1) . \chr(1)],
           // Large numbers
           ['1000000', 16, 'f4240'],
           ['1000000', 10, '1000000'],
        ];
    }

    /**
     * @test         creation with string representation
     * @dataProvider dataProviderForTestCreateArbitrary
     */
    public function testCreateArbitrary(string $int, int $base, string $expected)
    {
        // Given
        $int = BaseEncoderDecoder::createArbitraryInteger($int, $base);

        // When
        $stringRepresentation = (string) $int;

        // Then
        $this->assertEquals($expected, $stringRepresentation);
    }

    public function dataProviderForTestCreateArbitrary(): array
    {
        return [
            // Original test cases
            ['123', 10, '123'],
            ['7b', 16, '123'],
            [\chr(123), 256, '123'],
            // Edge case: zero
            ['0', 2, '0'],
            ['0', 10, '0'],
            ['0', 16, '0'],
            [\chr(0), 256, '0'],
            // Base 2 (binary)
            ['1', 2, '1'],
            ['10', 2, '2'],
            ['111', 2, '7'],
            ['11111111', 2, '255'],
            // Base 8 (octal)
            ['10', 8, '8'],
            ['100', 8, '64'],
            ['377', 8, '255'],
            // Base 10 (decimal)
            ['1', 10, '1'],
            ['10', 10, '10'],
            ['999', 10, '999'],
            ['1000', 10, '1000'],
            // Base 16 (hexadecimal)
            ['1', 16, '1'],
            ['a', 16, '10'],
            ['10', 16, '16'],
            ['ff', 16, '255'],
            ['1000', 16, '4096'],
            ['ffff', 16, '65535'],
            // Base 256
            [\chr(1), 256, '1'],
            [\chr(255), 256, '255'],
            [\chr(1) . \chr(0), 256, '256'],
            [\chr(1) . \chr(1), 256, '257'],
            [\chr(2) . \chr(0), 256, '512'],
            // Large numbers
            ['f4240', 16, '1000000'],
            ['1000000', 10, '1000000'],
        ];
    }

    /**
     * @test     toBase throws an exception when base>256
     * @throws   \Exception
     */
    public function testInvalidToBaseException()
    {
        // Given
        $base = 300;
        $int  = new ArbitraryInteger('123456');

        // Then
        $this->expectException(Exception\BadParameterException::class);

        // When
        $string = BaseEncoderDecoder::toBase($int, $base);
    }

    /**
     * @test   Function throws an exception when given an empty string
     * @throws \Exception
     */
    public function testEmptyStringException()
    {
        // Given
        $number = "";

        // Then
        $this->expectException(Exception\BadParameterException::class);

        // When
        $int = BaseEncoderDecoder::createArbitraryInteger($number, 10);
    }

    /**
     * @test   Function throws an exception when base>256
     * @throws \Exception
     */
    public function testInvalidBaseException()
    {
        // Given
        $base = 300;

        // Then
        $this->expectException(Exception\BadParameterException::class);

        // When
        $int = BaseEncoderDecoder::createArbitraryInteger('123456', $base);
    }

    /**
     * @test         Function throws an exception when base>256
     * @dataProvider dataProviderForTestInvalidCharInStringException
     * @param        string $value
     * @param        int    $base
     * @throws       \Exception
     */
    public function testInvalidCharInStringException(string $value, int $base)
    {
        // Then
        $this->expectException(Exception\BadParameterException::class);

        // When
        $int = BaseEncoderDecoder::createArbitraryInteger($value, $base);
    }

    public function dataProviderForTestInvalidCharInStringException(): array
    {
        return [
            ['12a', 10],
            ['0x12afg', 16],
        ];
    }

    /**
     * @test         Roundtrip conversion: toBase then createArbitraryInteger
     * @dataProvider dataProviderForTestRoundtripThenCreate
     * @param        string $int
     * @param        int    $base
     * @throws       \Exception
     */
    public function testRoundtripThenCreate(string $int, int $base)
    {
        // Given
        $original = new ArbitraryInteger($int);

        // When
        $encoded = BaseEncoderDecoder::toBase($original, $base);
        $decoded = BaseEncoderDecoder::createArbitraryInteger($encoded, $base);

        // Then
        $this->assertEquals($int, (string) $decoded);
    }

    public function dataProviderForTestRoundtripThenCreate(): array
    {
        return [
            ['0', 2],
            ['1', 2],
            ['255', 2],
            ['1000', 2],
            ['0', 8],
            ['64', 8],
            ['255', 8],
            ['0', 10],
            ['1', 10],
            ['123', 10],
            ['1000', 10],
            ['1000000', 10],
            ['0', 16],
            ['1', 16],
            ['255', 16],
            ['65535', 16],
            ['1000000', 16],
            ['0', 256],
            ['1', 256],
            ['255', 256],
            ['256', 256],
            ['65536', 256],
        ];
    }

    /**
     * @test         toBase with custom single-character alphabet offset
     * @dataProvider dataProviderForTestToBaseWithCustomAlphabet
     * @param        string $int
     * @param        int    $base
     * @param        string $alphabet
     * @param        string $expected
     * @throws       \Exception
     */
    public function testToBaseWithCustomAlphabet(string $int, int $base, string $alphabet, string $expected)
    {
        // Given
        $int = new ArbitraryInteger($int);

        // When
        $toBase = BaseEncoderDecoder::toBase($int, $base, $alphabet);

        // Then
        $this->assertEquals($expected, $toBase);
    }

    public function dataProviderForTestToBaseWithCustomAlphabet(): array
    {
        return [
            // Using '1' as offset for base 2 (instead of '0')
            ['0', 2, '1', '1'],
            ['1', 2, '1', '2'],
            ['2', 2, '1', '21'],
            ['7', 2, '1', '222'],
            // Using 'A' as offset for base 10
            ['0', 10, 'A', 'A'],
            ['1', 10, 'A', 'B'],
            ['9', 10, 'A', 'J'],
            ['10', 10, 'A', 'BA'],
        ];
    }

    /**
     * @test         createArbitraryInteger with custom single-character alphabet offset
     * @dataProvider dataProviderForTestCreateWithCustomAlphabet
     * @param        string $number
     * @param        int    $base
     * @param        string $offset
     * @param        string $expected
     * @throws       \Exception
     */
    public function testCreateWithCustomAlphabet(string $number, int $base, string $offset, string $expected)
    {
        // When
        $result = BaseEncoderDecoder::createArbitraryInteger($number, $base, $offset);

        // Then
        $this->assertEquals($expected, (string) $result);
    }

    public function dataProviderForTestCreateWithCustomAlphabet(): array
    {
        return [
            // Using '1' as offset for base 2
            ['1', 2, '1', '0'],
            ['2', 2, '1', '1'],
            ['21', 2, '1', '2'],
            ['222', 2, '1', '7'],
            // Using 'A' as offset for base 10
            ['A', 10, 'A', '0'],
            ['B', 10, 'A', '1'],
            ['J', 10, 'A', '9'],
            ['BA', 10, 'A', '10'],
        ];
    }

    /**
     * @test         toBase with multi-character alphabet
     * @dataProvider dataProviderForTestToBaseWithMultiCharAlphabet
     * @param        string $int
     * @param        int    $base
     * @param        string $alphabet
     * @param        string $expected
     * @throws       \Exception
     */
    public function testToBaseWithMultiCharAlphabet(string $int, int $base, string $alphabet, string $expected)
    {
        // Given
        $int = new ArbitraryInteger($int);

        // When
        $toBase = BaseEncoderDecoder::toBase($int, $base, $alphabet);

        // Then
        $this->assertEquals($expected, $toBase);
    }

    public function dataProviderForTestToBaseWithMultiCharAlphabet(): array
    {
        return [
            // Base 16 with custom alphabet
            ['0', 16, 'ABCDEFGHIJKLMNOP', 'A'],
            ['1', 16, 'ABCDEFGHIJKLMNOP', 'B'],
            ['15', 16, 'ABCDEFGHIJKLMNOP', 'P'],
            ['16', 16, 'ABCDEFGHIJKLMNOP', 'BA'],
            ['255', 16, 'ABCDEFGHIJKLMNOP', 'PP'],
            // Base 2 with custom alphabet
            ['0', 2, 'NO', 'N'],
            ['1', 2, 'NO', 'O'],
            ['2', 2, 'NO', 'ON'],
            ['7', 2, 'NO', 'OOO'],
        ];
    }

    /**
     * @test         createArbitraryInteger with multi-character alphabet
     * @dataProvider dataProviderForTestCreateWithMultiCharAlphabet
     * @param        string $number
     * @param        int    $base
     * @param        string $alphabet
     * @param        string $expected
     * @throws       \Exception
     */
    public function testCreateWithMultiCharAlphabet(string $number, int $base, string $alphabet, string $expected)
    {
        // When
        $result = BaseEncoderDecoder::createArbitraryInteger($number, $base, $alphabet);

        // Then
        $this->assertEquals($expected, (string) $result);
    }

    public function dataProviderForTestCreateWithMultiCharAlphabet(): array
    {
        return [
            // Base 16 with custom alphabet
            ['A', 16, 'ABCDEFGHIJKLMNOP', '0'],
            ['B', 16, 'ABCDEFGHIJKLMNOP', '1'],
            ['P', 16, 'ABCDEFGHIJKLMNOP', '15'],
            ['BA', 16, 'ABCDEFGHIJKLMNOP', '16'],
            ['PP', 16, 'ABCDEFGHIJKLMNOP', '255'],
            // Base 2 with custom alphabet
            ['N', 2, 'NO', '0'],
            ['O', 2, 'NO', '1'],
            ['ON', 2, 'NO', '2'],
            ['OOO', 2, 'NO', '7'],
        ];
    }

    /**
     * @test         Roundtrip with custom multi-char alphabet
     * @dataProvider dataProviderForTestRoundtripWithMultiCharAlphabet
     * @param        string $int
     * @param        int    $base
     * @param        string $alphabet
     * @throws       \Exception
     */
    public function testRoundtripWithMultiCharAlphabet(string $int, int $base, string $alphabet)
    {
        // Given
        $original = new ArbitraryInteger($int);

        // When
        $encoded = BaseEncoderDecoder::toBase($original, $base, $alphabet);
        $decoded = BaseEncoderDecoder::createArbitraryInteger($encoded, $base, $alphabet);

        // Then
        $this->assertEquals($int, (string) $decoded);
    }

    public function dataProviderForTestRoundtripWithMultiCharAlphabet(): array
    {
        return [
            ['0', 16, 'ABCDEFGHIJKLMNOP'],
            ['1', 16, 'ABCDEFGHIJKLMNOP'],
            ['255', 16, 'ABCDEFGHIJKLMNOP'],
            ['65535', 16, 'ABCDEFGHIJKLMNOP'],
            ['0', 2, 'NO'],
            ['1', 2, 'NO'],
            ['255', 2, 'NO'],
            ['1000', 2, 'NO'],
            ['0', 8, 'ABCDEFGH'],
            ['64', 8, 'ABCDEFGH'],
            ['255', 8, 'ABCDEFGH'],
        ];
    }

    /**
     * @test         Ensure default alphabet is used when not specified
     * @dataProvider dataProviderForTestDefaultAlphabet
     * @param        string $int
     * @param        int    $base
     * @param        string $expected
     * @throws       \Exception
     */
    public function testDefaultAlphabet(string $int, int $base, string $expected)
    {
        // Given
        $int = new ArbitraryInteger($int);

        // When - explicitly passing null for alphabet
        $result1 = BaseEncoderDecoder::toBase($int, $base);
        $result2 = BaseEncoderDecoder::toBase($int, $base, null);

        // Then - both should be equal
        $this->assertEquals($result1, $result2);
        $this->assertEquals($expected, $result1);
    }

    public function dataProviderForTestDefaultAlphabet(): array
    {
        return [
            ['255', 16, 'ff'],
            ['100', 256, \chr(100)],
        ];
    }

    /**
     * @test   Invalid character for given base in custom alphabet
     * @dataProvider dataProviderForTestInvalidCharWithCustomAlphabet
     * @throws \Exception
     */
    public function testInvalidCharWithCustomAlphabet(string $number, int $base, string $alphabet)
    {
        // Then
        $this->expectException(Exception\BadParameterException::class);

        // When
        BaseEncoderDecoder::createArbitraryInteger($number, $base, $alphabet);
    }

    public function dataProviderForTestInvalidCharWithCustomAlphabet(): array
    {
        return [
            // Character not in alphabet
            ['Z', 16, 'ABCDEFGHIJKLMNOP'],
            // Character beyond base range
            ['Q', 2, 'NO'],
            ['P', 8, 'ABCDEFGH'],
        ];
    }

    /**
     * @test         Verify single-digit conversions
     * @dataProvider dataProviderForTestSingleDigit
     * @param        int    $value
     * @param        int    $base
     * @throws       \Exception
     */
    public function testSingleDigit(int $value, int $base)
    {
        // Given
        $original = new ArbitraryInteger((string) $value);

        // When
        $encoded = BaseEncoderDecoder::toBase($original, $base);
        $decoded = BaseEncoderDecoder::createArbitraryInteger($encoded, $base);

        // Then
        $this->assertEquals((string) $value, (string) $decoded);
    }

    public function dataProviderForTestSingleDigit(): array
    {
        $data = [];
        // Test single digits for various bases
        for ($base = 2; $base <= 16; $base++) {
            for ($digit = 0; $digit < $base; $digit++) {
                $data[] = [$digit, $base];
            }
        }
        return $data;
    }
}
