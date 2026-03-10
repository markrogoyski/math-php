<?php

namespace MathPHP\Tests\Polynomials;

use MathPHP\Polynomials\MonomialExponentGenerator;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class MonomialExponentGeneratorTest extends TestCase
{
    /**
     * @test         all returns all exponent tuples with total degree <= degree
     * @dataProvider dataProviderForAll
     * @param int $dimension
     * @param int $degree
     * @param bool $reverse
     * @param array $expected
     */
    public function testAll(int $dimension, int $degree, bool $reverse, array $expected): void
    {
        // When
        $result = MonomialExponentGenerator::all($dimension, $degree, $reverse);

        // Then
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array [dimension, degree, reverse, expected]
     */
    public function dataProviderForAll(): array
    {
        return [
            [
                1, 0, false,
                [[0]]
            ],
            [
                1, 1, false,
                [[0], [1]]
            ],
            [
                1, 2, false,
                [[0], [1], [2]]
            ],
            [
                2, 0, false,
                [[0, 0]]
            ],
            [
                2, 1, false,
                [
                    [0, 0],
                    [0, 1], [1, 0]
                ]
            ],
            [
                2, 2, false,
                [
                    [0, 0],
                    [0, 1], [1, 0],
                    [0, 2], [1, 1], [2, 0]
                ]
            ],
            [
                3, 1, false,
                [
                    [0, 0, 0],
                    [0, 0, 1], [0, 1, 0], [1, 0, 0]
                ]
            ],
            [
                3, 2, false,
                [
                    [0, 0, 0],
                    [0, 0, 1], [0, 1, 0], [1, 0, 0],
                    [0, 0, 2], [0, 1, 1], [0, 2, 0], [1, 0, 1], [1, 1, 0], [2, 0, 0],
                ]
            ],
            [
                1, 0, true,
                [[0]]
            ],
            [
                1, 1, true,
                [[0], [1]]
            ],
            [
                1, 2, true,
                [[0], [1], [2]]
            ],
            [
                2, 0, true,
                [[0, 0]]
            ],
            [
                2, 1, true,
                [
                    [0, 0],
                    [1, 0], [0, 1]
                ]
            ],
            [
                2, 2, true,
                [
                    [0, 0],
                    [1, 0], [0, 1],
                    [2, 0], [1, 1], [0, 2]
                ]
            ],
            [
                3, 1, true,
                [
                    [0, 0, 0],
                    [1, 0, 0], [0, 1, 0], [0, 0, 1]
                ]
            ],
            [
                3, 2, true,
                [
                    [0, 0, 0],
                    [1, 0, 0], [0, 1, 0], [0, 0, 1],
                    [2, 0, 0], [1, 1, 0], [1, 0, 1], [0, 2, 0], [0, 1, 1], [0, 0, 2],
                ]
            ],
        ];
    }

    /**
     * @test         iterate returns a generator over all exponent tuples
     * @dataProvider dataProviderForAll
     * @param int $dimension
     * @param int $degree
     * @param bool $reverse
     * @param array $expected
     */
    public function testIterate(int $dimension, int $degree, bool $reverse, array $expected): void
    {
        // When
        $generator = MonomialExponentGenerator::iterate($dimension, $degree, $reverse);
        $result = \iterator_to_array($generator, false);

        // Then
        $this->assertEquals($expected, $result);
    }

    /**
     * @test         iterate throws InvalidArgumentException when dimension < 1
     */
    public function testAllExceptionDimensionLessThanOne(): void
    {
        // Then
        $this->expectException(InvalidArgumentException::class);

        // When
        MonomialExponentGenerator::all(0, 0, false);
    }

    /**
     * @test         iterate throws InvalidArgumentException when degree < 0
     */
    public function testAllExceptionDegreeLessThanZero(): void
    {
        // Then
        $this->expectException(InvalidArgumentException::class);

        // When
        MonomialExponentGenerator::all(1, -1, false);
    }

    /**
     * @test         getNumberOfTerms returns the correct number of terms
     * @dataProvider dataProviderForAll
     * @param int $dimension
     * @param int $degree
     * @param bool $reverse
     * @param array $expected
     */
    public function testGetNumberOfTerms(int $dimension, int $degree, bool $reverse, array $expected): void
    {
        // Given
        $generator = new MonomialExponentGenerator();

        // When
        $numTerms = $generator->getNumberOfTerms($dimension, $degree);

        // Then
        $this->assertEquals(count($expected), $numTerms);
    }
}
