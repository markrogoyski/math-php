<?php

namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\Exception\VectorException;
use MathPHP\LinearAlgebra\Vector;
use PHPUnit\Framework\TestCase;

class VectorAngleTest extends TestCase
{
    /**
     * @test Angle between two vectors in degrees
     * @dataProvider dataProviderForDegAngle
     * @param array $A
     * @param array $B
     * @param float $expected
     */
    public function testGetDegAngle(array $A, array $B, $expected)
    {
        $A = new Vector($A);
        $B = new Vector($B);

        //When
        $angle1 = $A->cosineSimilarity($B, true);
        $angle2 = $B->cosineSimilarity($A, true);

        //Then
        $this->assertEquals($expected, $angle1);
        $this->assertEquals($expected, $angle2);
    }

    public function dataProviderForDegAngle(): array
    {
        return [
            [ [1, 2, 3], [3, 2, 1], rad2deg(acos(5/7)) ],
            [ [1, 0, 0], [0, 0, 1], rad2deg(acos(0)) ],
            [ [1, 0, 0], [1, 0, 0], rad2deg(acos(1)) ],
            [ [-1, 1, 0], [0, 1, -1], rad2deg(acos(1/2)) ],
            [ [23, 41, 33], [31,56,21], rad2deg(acos(1851*sqrt(2/7485431))) ],
        ];
    }

    /**
     * @test Angle between two vectors in radians
     * @dataProvider dataProviderForRadAngle
     */
    public function testGetRadAngle(array $A, array $B, $expected)
    {
        $A = new Vector($A);
        $B = new Vector($B);

        //When
        $angle1 = $A->cosineSimilarity($B);
        $angle2 = $B->cosineSimilarity($A);

        //Then
        $this->assertEquals($expected, $angle1);
        $this->assertEquals($expected, $angle2);
    }

    public function dataProviderForRadAngle(): array
    {
        return [
            [ [1, 2, 3], [3, 2, 1], acos(5/7) ],
            [ [1, 0, 0], [0, 0, 1], acos(0) ],
            [ [1, 0, 0], [1, 0, 0], acos(1) ],
            [ [-1, 1, 0], [0, 1, -1], acos(1/2) ],
            [ [23, 41, 33], [31,56,21], acos(1851*sqrt(2/7485431)) ],
        ];
    }

    /**
     * @dataProvider dataProviderForExceptionRadAngle
     */
    public function testExceptionRadAngle(array $A, array $B)
    {
        $this->expectException(VectorException::class);

        $A = new Vector($A);
        $B = new Vector($B);

        //When
        $A->cosineSimilarity($B);

        //Then
        //Exception
    }

    public function dataProviderForExceptionRadAngle() : array
    {
        return [
            [ [1, 2, 3], [0, 0, 0]],
            [ [0, 0, 0], [3, 2, 1]],
            [ [0, 0, 0], [0, 0, 0]]
        ];
    }
}
