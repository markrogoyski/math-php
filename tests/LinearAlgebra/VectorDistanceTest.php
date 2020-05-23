<?php

namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\Exception\VectorException;
use MathPHP\LinearAlgebra\Vector;
use PHPUnit\Framework\TestCase;

class VectorDistanceTest extends TestCase
{
    /**
     * @test Tests the exception when vectors don't have the same size
     * @dataProvider dataProviderForDifferentVectors
     */
    public function testDifferentVectors(array $A, array $B)
    {
        $this->expectException(VectorException::class);
        $A = new Vector($A);
        $B = new Vector($B);

        //When
        $A->minkowskiDistance($B, 2);

        //Then
        //Exception
    }

    public function dataProviderForDifferentVectors() : array
    {
        return [
            [ [1, 2, 3], [0, 0]],
            [ [0, 0, 0], [3, 2]],
            [ [0, 0], [0, 0, 0]],
            [ [3, 4], [4, 3, 2]],
            [ [1, 1], [1, 1, 1]],
        ];
    }

    /**
     * @test The manhatten distance
     * @dataProvider dataProviderForL1Distance
     */
    public function testGetL1Distance(array $A, array $B, $expection)
    {
        $A = new Vector($A);
        $B = new Vector($B);

        //When
        $distance1 = $A->l1Distance($B);
        $distance2 = $B->l1Distance($A);

        //Then
        $this->assertEquals($expection, $distance1);
        $this->assertEquals($expection, $distance2);
    }

    public function dataProviderForL1Distance() : array
    {
        return [
            [ [1, 2, 3], [0, 0, 0], 6],
            [ [0, 0, 0], [0, 0, 0], 0],
            [ [1, 1, 1], [1, 1, 1], 0],
            [ [56,26,83], [11,82,95], 113],
        ];
    }

    /**
     * @test The euclidean distance
     * @dataProvider dataProviderForL2Distance
     */
    public function testGetL2Distance(array $A, array $B, $expection)
    {
        $A = new Vector($A);
        $B = new Vector($B);

        //When
        $distance1 = $A->l2Distance($B);
        $distance2 = $B->l2Distance($A);

        //Then
        $this->assertEquals($expection, $distance1);
        $this->assertEquals($expection, $distance2);
    }

    public function dataProviderForL2Distance() : array
    {
        return [
            [ [1, 2, 3], [0, 0, 0], sqrt(14)],
            [ [0, 0, 0], [0, 0, 0], 0],
            [ [1, 1, 1], [1, 1, 1], 0],
            [ [56,26,83], [11,82,95], sqrt(5305)],
        ];
    }
}
