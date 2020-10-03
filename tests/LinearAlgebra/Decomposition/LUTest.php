<?php

namespace MathPHP\Tests\LinearAlgebra\Decomposition;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\Exception;

class LUTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         LU decomposition
     * @dataProvider dataProviderForLUDecomposition
     * Unit test data created from online calculator: https://www.easycalculation.com/matrix/lu-decomposition-matrix.php
     * @param        array $A
     * @param        array $L
     * @param        array $U
     * @param        array $P
     * @throws       \Exception
     */
    public function testLUDecomposition(array $A, array $L, array $U, array $P)
    {
        // Given
        $A = MatrixFactory::create($A);
        $L = MatrixFactory::create($L);
        $U = MatrixFactory::create($U);
        $P = MatrixFactory::create($P);

        // When
        $LU = $A->luDecomposition();

        // Then
        $this->assertEquals($L, $LU->L, '', 0.001);
        $this->assertEquals($U, $LU->U, '', 0.001);
        $this->assertEquals($P, $LU->P, '', 0.001);

        // And
        $this->assertTrue($LU->L->isLowerTriangular());
        $this->assertTrue($LU->U->isUpperTriangular());
    }

    /**
     * @return array
     */
    public function dataProviderForLuDecomposition(): array
    {
        return [
            [
                [
                    [4, 3],
                    [6, 3],
                ],
                [
                    [1, 0],
                    [0.667, 1],
                ],
                [
                    [6, 3],
                    [0, 1],
                ],
                [
                    [0, 1],
                    [1, 0],
                ],
            ],
            [
                [
                    [1, 3, 5],
                    [2, 4, 7],
                    [1, 1, 0],
                ],
                [
                    [1, 0, 0],
                    [.5, 1, 0],
                    [.5, -1, 1],
                ],
                [
                    [2, 4, 7],
                    [0, 1, 1.5],
                    [0, 0, -2],
                ],
                [
                    [0, 1, 0],
                    [1, 0, 0],
                    [0, 0, 1],
                ]
            ],
            [
                [
                    [1, -2, 3],
                    [2, -5, 12],
                    [0, 2, -10],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0.5, 0.25, 1],
                ],
                [
                    [2, -5, 12],
                    [0, 2, -10],
                    [0, 0, -0.5],
                ],
                [
                    [0, 1, 0],
                    [0, 0, 1],
                    [1, 0, 0],
                ],
            ],
            [
                [
                    [4, 2, 3],
                    [-3, 1, 4],
                    [2, 4, 5],
                ],
                [
                    [1, 0, 0],
                    [0.5, 1, 0],
                    [-0.75, 0.833, 1],
                ],
                [
                    [4, 2, 3],
                    [0, 3, 3.5],
                    [0, 0, 3.333]
                ],
                [
                    [1, 0, 0],
                    [0, 0, 1],
                    [0, 1, 0],
                ],
            ],
            [
                [
                    [5, 4, 8, 9],
                    [9, 9, 9, 9],
                    [4, 5, 5, 7],
                    [1, 9, 8, 7],
                ],
                [
                    [1, 0, 0, 0],
                    [.556, 1, 0, 0],
                    [.111, -8, 1, 0],
                    [.444, -1, .129, 1],
                ],
                [
                    [9, 9, 9, 9],
                    [0, -1, 3, 4],
                    [0, 0, 31, 38],
                    [0, 0, 0, 2.097],
                ],
                [
                    [0, 1, 0, 0],
                    [1, 0, 0, 0],
                    [0, 0, 0, 1],
                    [0, 0, 1, 0],
                ],
            ],
            [
                [
                    [2, 1, 1, 0],
                    [4, 3, 3, 1],
                    [8, 7, 9, 5],
                    [6, 7, 9, 8],
                ],
                [
                    [1, 0, 0, 0],
                    [0.25, 1, 0, 0],
                    [0.5, 0.667, 1, 0],
                    [0.75, -2.333, 1, 1],
                ],
                [
                    [8, 7, 9, 5],
                    [0, -0.75, -1.25, -1.25],
                    [0, 0, -0.667, -0.667],
                    [0, 0, 0, 2],
                ],
                [
                    [0, 0, 1, 0],
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [11, 9, 24, 2],
                    [1, 5, 2, 6],
                    [3, 17, 18, 1],
                    [2, 5, 7, 1],
                ],
                [
                    [1, 0, 0, 0],
                    [.27273, 1, 0, 0],
                    [.09091, .28750, 1, 0],
                    [.18182, .23125, .00360, 1],
                ],
                [
                    [11, 9, 24, 2],
                    [0, 14.54545, 11.45455, 0.45455],
                    [0, 0, -3.47500, 5.68750],
                    [0, 0, 0, 0.51079],
                ],
                [
                    [1, 0, 0, 0],
                    [0, 0, 1, 0],
                    [0, 1, 0, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [5, 3, 8],
                    [6, 4, 5],
                    [1, 8, 9],
                ],
                [
                    [1, 0, 0],
                    [0.167, 1, 0],
                    [.833, -0.045, 1],
                ],
                [
                    [6, 4, 5],
                    [0, 7.333, 8.167],
                    [0, 0, 4.205]
                ],
                [
                    [0, 1, 0],
                    [0, 0, 1],
                    [1, 0, 0],
                ],
            ],
            [
                [
                    [3, 2, 6, 7],
                    [4, 3, -6, 2],
                    [12, 14, 14, -6],
                    [4, 6, 4, -42],
                ],
                [
                    [1, 0, 0, 0],
                    [0.25, 1, 0, 0],
                    [0.333, 1.111, 1, 0],
                    [0.333, -0.889, -0.116, 1],
                ],
                [
                    [12, 14, 14, -6],
                    [0, -1.5, 2.5, 8.5],
                    [0, 0, -13.444, -5.444],
                    [0, 0, 0, -33.074],
                ],
                [
                    [0, 0, 1, 0],
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [5, 3, 4, 1],
                    [5, 6, 4, 3],
                    [7, 6, 5, 3],
                    [2, 7, 4, 7],
                ],
                [
                    [1, 0, 0, 0],
                    [0.286, 1, 0, 0],
                    [0.714, -0.243, 1, 0],
                    [0.714, 0.324, -0.385, 1],
                ],
                [
                    [7, 6, 5, 3],
                    [0, 5.286, 2.571, 6.143],
                    [0, 0, 1.054, 0.351],
                    [0, 0, 0, -1],
                ],
                [
                    [0, 0, 1, 0],
                    [0, 0, 0, 1],
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                ],
            ],
        ];
    }

    /**
     * @test   LU decomposition exception for matrix not being square
     * @throws \Exception
     */
    public function testLUDecompositionExceptionNotSquare()
    {
        // Given
        $A = MatrixFactory::create([
            [1, 2, 3],
            [2, 3, 4],
        ]);

        // Then
        $this->expectException(Exception\MatrixException::class);

        // When
        $A->luDecomposition();
    }

    /**
     * @test   LU Decomposition invalid property
     * @throws \Exception
     */
    public function testLUDecompositionInvalidProperty()
    {
        // Given
        $A = MatrixFactory::create([
            [5, 3, 4, 1],
            [5, 6, 4, 3],
            [7, 6, 5, 3],
            [2, 7, 4, 7],
        ]);
        $LU = $A->luDecomposition();

        // Then
        $this->expectException(Exception\MathException::class);

        // When
        $doesNotExist = $LU->doesNotExist;
    }

    /**
     * @test   LU Decomposition ArrayAccess
     * @throws \Exception
     */
    public function testLUDecompositionArrayAccess()
    {
        // Given
        $A = MatrixFactory::create([
            [5, 3, 4, 1],
            [5, 6, 4, 3],
            [7, 6, 5, 3],
            [2, 7, 4, 7],
        ]);
        $LU = $A->luDecomposition();

        // When
        $L = $LU['L'];
        $U = $LU['U'];
        $P = $LU['P'];

        // Then
        $this->assertEquals($LU->L, $L);
        $this->assertEquals($LU->U, $U);
        $this->assertEquals($LU->P, $P);
    }

    /**
     * @test   LU Decomposition ArrayAccess invalid offset
     * @throws \Exception
     */
    public function testLUDecompositionArrayAccessInvalidOffset()
    {
        // Given
        $A = MatrixFactory::create([
            [5, 3, 4, 1],
            [5, 6, 4, 3],
            [7, 6, 5, 3],
            [2, 7, 4, 7],
        ]);
        $LU = $A->luDecomposition();

        // Then
        $this->assertFalse(isset($LU['doesNotExist']));
    }

    /**
     * @test   LU Decomposition ArrayAccess set disabled
     * @throws \Exception
     */
    public function testLUDecompositionArrayAccessSetDisabled()
    {
        // Given
        $A = MatrixFactory::create([
            [5, 3, 4, 1],
            [5, 6, 4, 3],
            [7, 6, 5, 3],
            [2, 7, 4, 7],
        ]);
        $LU = $A->luDecomposition();

        // Then
        $this->expectException(Exception\MatrixException::class);

        // When
        $LU['U'] = $A;
    }

    /**
     * @test   LU Decomposition ArrayAccess unset disabled
     * @throws \Exception
     */
    public function testLUDecompositionArrayAccessUnsetDisabled()
    {
        // Given
        $A = MatrixFactory::create([
            [5, 3, 4, 1],
            [5, 6, 4, 3],
            [7, 6, 5, 3],
            [2, 7, 4, 7],
        ]);
        $LU = $A->luDecomposition();

        // Then
        $this->expectException(Exception\MatrixException::class);

        // When
        unset($LU['U']);
    }

    /**
     * @test   LU Decomposition ArrayAccess Isset
     * @throws \Exception
     */
    public function testArrayAccessIsset()
    {
        // Given
        $A = MatrixFactory::create([
            [5, 3, 4, 1],
            [5, 6, 4, 3],
            [7, 6, 5, 3],
            [2, 7, 4, 7],
        ]);
        $LU = $A->luDecomposition();

        // When
        $L = isset($LU['L']);
        $U = isset($LU['U']);
        $P = isset($LU['P']);

        // Then
        $this->assertTrue($L);
        $this->assertTrue($U);
        $this->assertTrue($P);
    }
}
