<?php

namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\Exception\BadDataException;
use MathPHP\LinearAlgebra\Vector;
use PHPUnit\Framework\TestCase;

class VectorAngleTest extends TestCase
{
    /**
     * @test         Angle between two vectors in degrees
     * @dataProvider dataProviderForDegAngle
     * @param        array $A
     * @param        array $B
     * @param        float $expected
     */
    public function testGetDegAngle(array $A, array $B, float $expected)
    {
        // Given
        $A = new Vector($A);
        $B = new Vector($B);

        // When
        $angle1 = $A->cosineSimilarity($B, true);
        $angle2 = $B->cosineSimilarity($A, true);

        // Then
        $this->assertEquals($expected, $angle1, '', 00000000001);
        $this->assertEquals($expected, $angle2, '', 00000000001);
    }

    /**
     * Test data created with online calculator: https://www.emathhelp.net/calculators/linear-algebra/angle-between-two-vectors-calculator
     * @return array
     */
    public function dataProviderForDegAngle(): array
    {
        return [
            [
                [1, 2, 3],
                [3, 2, 1],
                rad2deg(acos(5 / 7)),
            ],
            [
                [1, 2, 3],
                [3, 2, 1],
                44.415308597193,
            ],
            [
                [1, 0, 0],
                [0, 0, 1],
                rad2deg(acos(0)),
            ],
            [
                [1, 0, 0],
                [0, 0, 1],
                90,
            ],
            [
                [1, 0, 0],
                [1, 0, 0],
                rad2deg(acos(1)),
            ],
            [
                [1, 0, 0],
                [1, 0, 0],
                0
            ],
            [
                [-1, 1, 0],
                [0, 1, -1],
                rad2deg(acos(1 / 2)),
            ],
            [
                [-1, 1, 0],
                [0, 1, -1],
                60,
            ],
            [
                [23, 41, 33],
                [31, 56, 21],
                rad2deg(acos(1851 * sqrt(2 / 7485431))),
            ],
            [
                [23, 41, 33],
                [31, 56, 21],
                16.9062176097913,
            ],
        ];
    }

    /**
     * @test         Angle between two vectors in radians
     * @dataProvider dataProviderForRadAngle
     * @param        array $A
     * @param        array $B
     * @param        float $expected
     */
    public function testGetRadAngle(array $A, array $B, float $expected)
    {
        // Given
        $A = new Vector($A);
        $B = new Vector($B);

        // When
        $angle1 = $A->cosineSimilarity($B);
        $angle2 = $B->cosineSimilarity($A);

        // Then
        $this->assertEquals($expected, $angle1, '', 00000000001);
        $this->assertEquals($expected, $angle2, '', 00000000001);
    }

    /**
     * Test data created with online calculator: https://www.emathhelp.net/calculators/linear-algebra/angle-between-two-vectors-calculator
     * @return array
     */
    public function dataProviderForRadAngle(): array
    {
        return [
            [
                [1, 2, 3],
                [3, 2, 1],
                acos(5 / 7),
            ],
            [
                [1, 2, 3],
                [3, 2, 1],
                0.775193373310361,
            ],
            [
                [1, 0, 0],
                [0, 0, 1],
                acos(0),
            ],
            [
                [1, 0, 0],
                [0, 0, 1],
                1.5707963267949,
            ],
            [
                [1, 0, 0],
                [1, 0, 0],
                acos(1),
            ],
            [
                [1, 0, 0],
                [1, 0, 0],
                0,
            ],
            [
                [-1, 1, 0],
                [0, 1, -1],
                acos(1 / 2),
            ],
            [
                [-1, 1, 0],
                [0, 1, -1],
                1.0471975511966,
            ],
            [
                [23, 41, 33],
                [31, 56, 21],
                acos(1851 * sqrt(2/7485431)),
            ],
            [
                [23, 41, 33],
                [31, 56, 21],
                0.295069161349504,
            ],
        ];
    }

    /**
     * @test         angle between vectors exception for null vector
     * @dataProvider dataProviderForExceptionRadAngle
     * @param        array $A
     * @param        array $B
     */
    public function testExceptionRadAngle(array $A, array $B)
    {
        // Given
        $A = new Vector($A);
        $B = new Vector($B);

        // Then
        $this->expectException(BadDataException::class);

        // When
        $A->cosineSimilarity($B);
    }

    /**
     * @return array
     */
    public function dataProviderForExceptionRadAngle(): array
    {
        return [
            [
                [1, 2, 3],
                [0, 0, 0],
            ],
            [
                [0, 0, 0],
                [3, 2, 1],
            ],
            [
                [0, 0, 0],
                [0, 0, 0],
            ]
        ];
    }
}
