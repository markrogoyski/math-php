<?php

namespace MathPHP\Tests\Util;

use MathPHP\Exception;
use MathPHP\Util\Search;

class SearchTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         sorted
     * @dataProvider dataProviderForSearchSorted
     * @param        array $haystack
     * @param        float $needle
     * @param        int   $expected
     */
    public function testSearchSorted(array $haystack, float $needle, int $expected)
    {
        // When
        $insertionPoint = Search::sorted($haystack, $needle);

        // Then
        $this->assertSame($expected, $insertionPoint);
    }

    /**
     * Test data created with Python NumPy searchsorted
     * @return array (haystack, needle, expected)
     */
    public function dataProviderForSearchSorted(): array
    {
        return [
            [[1, 2, 3, 4, 5], -100, 0],
            [[1, 2, 3, 4, 5], -1, 0],
            [[1, 2, 3, 4, 5], 0, 0],
            [[1, 2, 3, 4, 5], 1, 0],
            [[1, 2, 3, 4, 5], 2, 1],
            [[1, 2, 3, 4, 5], 3, 2],
            [[1, 2, 3, 4, 5], 4, 3],
            [[1, 2, 3, 4, 5], 5, 4],
            [[1, 2, 3, 4, 5], 6, 5],
            [[1, 2, 3, 4, 5], 7, 5],
            [[1, 2, 3, 4, 5], 100, 5],

            [[-8, -5, -1, 3, 6, 10], -10, 0],
            [[-8, -5, -1, 3, 6, 10], -9, 0],
            [[-8, -5, -1, 3, 6, 10], -8, 0],
            [[-8, -5, -1, 3, 6, 10], -7, 1],
            [[-8, -5, -1, 3, 6, 10], -6, 1],
            [[-8, -5, -1, 3, 6, 10], -5, 1],
            [[-8, -5, -1, 3, 6, 10], -4, 2],
            [[-8, -5, -1, 3, 6, 10], -3, 2],
            [[-8, -5, -1, 3, 6, 10], -2, 2],
            [[-8, -5, -1, 3, 6, 10], -1, 2],
            [[-8, -5, -1, 3, 6, 10], 0, 3],
            [[-8, -5, -1, 3, 6, 10], 1, 3],
            [[-8, -5, -1, 3, 6, 10], 2, 3],
            [[-8, -5, -1, 3, 6, 10], 3, 3],
            [[-8, -5, -1, 3, 6, 10], 4, 4],
            [[-8, -5, -1, 3, 6, 10], 5, 4],
            [[-8, -5, -1, 3, 6, 10], 6, 4],
            [[-8, -5, -1, 3, 6, 10], 7, 5],
            [[-8, -5, -1, 3, 6, 10], 8, 5],
            [[-8, -5, -1, 3, 6, 10], 9, 5],
            [[-8, -5, -1, 3, 6, 10], 10, 5],
            [[-8, -5, -1, 3, 6, 10], 11, 6],
            [[-8, -5, -1, 3, 6, 10], 12, 6],
            [[-8, -5, -1, 3, 6, 10], 100, 6],

            [[1.1, 2.2, 3.3, 4.4, 5.5], -1.4, 0],
            [[1.1, 2.2, 3.3, 4.4, 5.5], -0.1, 0],
            [[1.1, 2.2, 3.3, 4.4, 5.5], 0, 0],
            [[1.1, 2.2, 3.3, 4.4, 5.5], 1.045, 0],
            [[1.1, 2.2, 3.3, 4.4, 5.5], 1.2, 1],
            [[1.1, 2.2, 3.3, 4.4, 5.5], 2, 1],
            [[1.1, 2.2, 3.3, 4.4, 5.5], 2.5, 2],
            [[1.1, 2.2, 3.3, 4.4, 5.5], 3.2, 2],
            [[1.1, 2.2, 3.3, 4.4, 5.5], 3.8, 3],
            [[1.1, 2.2, 3.3, 4.4, 5.5], 4.3, 3],
            [[1.1, 2.2, 3.3, 4.4, 5.5], 4.5, 4],
            [[1.1, 2.2, 3.3, 4.4, 5.5], 5.4, 4],
            [[1.1, 2.2, 3.3, 4.4, 5.5], 5.55, 5],
            [[1.1, 2.2, 3.3, 4.4, 5.5], 20.9, 5],

            [[4, 4.14285714, 4.28571429, 4.42857143, 4.57142857, 4.71428571, 4.85714286, 5, 5.14285714, 5.28571429, 5.42857143, 5.57142857, 5.71428571, 5.85714286, 6, 6.14285714, 6.28571429, 6.42857143, 6.57142857, 6.71428571, 6.85714286, 7], 6.2, 16],
        ];
    }

    /**
     * @test         argMax
     * @dataProvider dataProviderForArgMax
     * @param        array $values
     * @param        int   $expected
     */
    public function testArgMax(array $values, int $expected)
    {
        // When
        $indexOfMax = Search::argMax($values);

        // Then
        $this->assertSame($expected, $indexOfMax);
    }

    /**
     * Test data created with Python NumPy argmax
     * @return array
     */
    public function dataProviderForArgMax(): array
    {
        return [
            [[-100], 0],
            [[-1], 0],
            [[0], 0],
            [[1], 0],
            [[2], 0],
            [[100], 0],

            [[-100.43], 0],
            [[-1.3], 0],
            [[0.0], 0],
            [[0.0000003829], 0],
            [[1.5], 0],
            [[2.2], 0],
            [[100.4089], 0],

            [[0, 1], 1],
            [[1, 2], 1],
            [[3, 6], 1],
            [[94, 95], 1],
            [[9384935, 900980398049], 1],

            [[0.0, 0.1], 1],
            [[0.00004, 0.00005], 1],
            [[39.34, 39.35], 1],
            [[-4, -3], 1],
            [[-0.00001, 0], 1],

            [[-1, -1], 0],
            [[0, 0], 0],
            [[1, 1], 0],
            [[1.7, 1.7], 0],
            [[34535, 34535], 0],

            [[5, 1, 2, 3, 4], 0],
            [[1, 5, 2, 3, 4], 1],
            [[1, 2, 5, 3, 4], 2],
            [[1, 2, 3, 5, 4], 3],
            [[1, 2, 3, 4, 5], 4],

            [[5, 1, 2, 3, 5], 0],
            [[1, 5, 2, 5, 4], 1],
            [[1, 5, 5, 3, 4], 1],
            [[1, 2, 5, 5, 4], 2],
            [[1, 2, 3, 5, 5], 3],

            [[1.1, 1.2, 1.3, 1.4, 1.5], 4],
            [[0, 1, 2, 3, \NAN], 4],
            [[0, 1, 2, \NAN, 3], 3],
            [[0, 1, \NAN, 2, 3], 2],
            [[0, \NAN, 1, 2, 3], 1],
            [[\NAN, 0, 1, 2, 3], 0],
            [[\NAN, 0, \NAN, 1, 2, 3], 0],

            [
                [
                    [new \DateTimeImmutable('1979-01-01 00:00:00')],
                    [new \DateTimeImmutable('1989-01-01 00:00:00')],
                    [new \DateTimeImmutable('1999-01-01 00:00:00')],
                    [new \DateTimeImmutable('2009-01-01 00:00:00')],
                    [new \DateTimeImmutable('2019-01-01 00:00:00')],
                ],
                4
            ],
            [
                [
                    [new \DateTimeImmutable('1979-01-01 00:00:00')],
                    [new \DateTimeImmutable('1989-01-01 00:00:00')],
                    [new \DateTimeImmutable('1999-01-01 00:00:00')],
                    [new \DateTimeImmutable('2019-01-01 00:00:00')],
                    [new \DateTimeImmutable('2009-01-01 00:00:00')],
                ],
                3
            ],
            [
                [
                    [new \DateTimeImmutable('1979-01-01 00:00:00')],
                    [new \DateTimeImmutable('1989-01-01 00:00:00')],
                    [new \DateTimeImmutable('2019-01-01 00:00:00')],
                    [new \DateTimeImmutable('1999-01-01 00:00:00')],
                    [new \DateTimeImmutable('2009-01-01 00:00:00')],
                ],
                2
            ],
            [
                [
                    [new \DateTimeImmutable('1979-01-01 00:00:00')],
                    [new \DateTimeImmutable('2019-01-01 00:00:00')],
                    [new \DateTimeImmutable('1989-01-01 00:00:00')],
                    [new \DateTimeImmutable('1999-01-01 00:00:00')],
                    [new \DateTimeImmutable('2009-01-01 00:00:00')],
                ],
                1
            ],
            [
                [
                    [new \DateTimeImmutable('2019-01-01 00:00:00')],
                    [new \DateTimeImmutable('1979-01-01 00:00:00')],
                    [new \DateTimeImmutable('1989-01-01 00:00:00')],
                    [new \DateTimeImmutable('1999-01-01 00:00:00')],
                    [new \DateTimeImmutable('2009-01-01 00:00:00')],
                ],
                0
            ],

            [[false, false, false, false, true], 4],
            [[false, false, false, true, false], 3],
            [[true, false, false, false, false], 0],
            [[true, false, true, false, false], 0],
        ];
    }

    /**
     * @test argMax error when the input array is empty
     */
    public function testArgMaxErrorOnEmptyArray()
    {
        // Given
        $values = [];

        // Then
        $this->expectException(Exception\BadDataException::class);

        // When
        $index = Search::argMax($values);
    }
}
