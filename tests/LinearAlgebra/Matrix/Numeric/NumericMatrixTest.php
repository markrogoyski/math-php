<?php

namespace MathPHP\Tests\LinearAlgebra\Matrix\Numeric;

use MathPHP\LinearAlgebra\NumericMatrix;

class NumericMatrixTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test Object type of numeric matrix
     */
    public function testGetObjectType()
    {
        // Given
        $A = new NumericMatrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        // When
        $objectType = $A->getObjectType();

        // Then
        $this->assertSame('number', $objectType);
    }

    /**
     * @test string representation
     */
    public function testGetStringRepresentation()
    {
        // Given
        $A = new NumericMatrix([
            [1, 2, 3],
            [2, 3, 4],
            [3, 4, 5],
        ]);

        // And
        $expected = "[1, 2, 3]\n[2, 3, 4]\n[3, 4, 5]";

        // When
        $stringRepresentation = (string) $A;

        // Then
        $this->assertEquals($expected, $stringRepresentation);
    }
}
