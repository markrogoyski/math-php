<?php
namespace Math\LinearAlgebra;

/**
 * Tests of Vector axioms
 * These tests don't test specific functions,
 * but rather matrix axioms which in term make use of multiple functions.
 * If all the Vector math is implemented properly, these tests should
 * all work out according to the axioms.
 *
 * Axioms tested:
 *  - Norms
 *    - |x|₂ ≤ |x|₁ ≤ √n |x|₂
 *    - |x|∞ ≤ |x|₂ ≤ √n |x|∞
 *    - |x|∞ ≤ |x|₁ ≤ √n |x|∞
 *  - Dot product
 *    - A⋅B = B⋅A
 *  - Cross product / dot product
 *    - (A x B) ⋅ A = 0
 *    - (A x B) ⋅ B = 0
 */
class VectorAxiomsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Axiom: |x|₂ ≤ |x|₁ ≤ √n |x|₂
     * l²-norm is less than equal to l₁-norm which is less than equal to sqrt n * l²-norm.
     *
     * @dataProvider dataProviderForSingleVector
     */
    public function testL2NormLessThanL1NormLessThanSqrtNL2Norm(array $V)
    {
        $V = new Vector($V);
        $n = $V->getN();

        $l₁norm   = $V->l1Norm();
        $l²norm   = $V->l2Norm();
        $√nl²norm = $n * $l²norm;

        $this->assertLessThanOrEqual($l₁norm, $l²norm);
        $this->assertLessThanOrEqual($√nl²norm, $l₁norm);
        $this->assertLessThanOrEqual($√nl²norm, $l²norm);
    }

    /**
     * Axiom: |x|∞ ≤ |x|₂ ≤ √n |x|∞
     * Max norm is less than equal to l₂-norm which is less than equal to sqrt n * max norm.
     *
     * @dataProvider dataProviderForSingleVector
     */
    public function testMaxNormLessThtanEQualL2NormLessThanEqualSqrtNMaxNorm(array $V)
    {
        $V = new Vector($V);
        $n = $V->getN();

        $max_norm    = $V->maxNorm();
        $l²norm      = $V->l2Norm();
        $√n_max_norm = $n * $max_norm;

        $this->assertLessThanOrEqual($l²norm, $max_norm);
        $this->assertLessThanOrEqual($√n_max_norm, $l²norm);
        $this->assertLessThanOrEqual($√n_max_norm, $max_norm);
    }

    /**
     * Axiom: |x|∞ ≤ |x|₁ ≤ √n |x|∞
     * Max norm is less than equal to l₁-norm which is less than equal to sqrt n * max norm.
     *
     * @dataProvider dataProviderForSingleVector
     */
    public function testMaxNormLessThanEqualL1NormLessThanEqualSqrtNMaxNorm(array $V)
    {
        $V = new Vector($V);
        $n = $V->getN();

        $max_norm    = $V->maxNorm();
        $l₁norm      = $V->l1Norm();
        $√n_max_norm = $n * $max_norm;

        $this->assertLessThanOrEqual($l₁norm, $max_norm);
        $this->assertLessThanOrEqual($√n_max_norm, $l₁norm);
        $this->assertLessThanOrEqual($√n_max_norm, $max_norm);
    }

    public function dataProviderForSingleVector()
    {
        return [
            [ [0] ],
            [ [1] ],
            [ [1, 2] ],
            [ [1, 2, 3, 4, 5] ],
            [ [5, 2, 7, 4, 2, 7, 4] ],
            [ [-4, 6, 3, 7, -4, 5, -8, -11, 5, 0, 5, -2] ],
            [ [1, 0, 3, 5, 3, 0, 0, 9, 0] ],
            [ [34, 100, 4, 532, 6, 43, 78, 32, 853, 23, 532, 327 ] ],
        ];
    }

    /**
     * Axiom: A⋅B = B⋅A
     * Dot product is commutative
     * @dataProvider dataProviderForTwoVectors
     */
    public function testDotProductCommutative(array $A, array $B)
    {
        $A = new Vector($A);
        $B = new Vector($B);

        $A⋅B = $A->dotProduct($B);
        $B⋅A = $B->dotProduct($A);

        $this->assertEquals($A⋅B, $B⋅A);
    }

    public function dataProviderForTwoVectors()
    {
        return [
            [
                [1],
                [1],
            ],
            [
                [1, 2],
                [2, 3],
            ],
            [
                [1, 2, 3, 4, 5],
                [4, 5, 6, 7, 8],
            ],
            [
                [3, -5, 2, -12, 4, 9, -4],
                [-9, 4, 5, 6, -11, 2, -4],
            ],
            [
                [1, 0, 3],
                [0, 1 ,9],
            ],
        ];
    }

    /**
     * Axiom: (A x B) ⋅ A = 0
     * Axiom: (A x B) ⋅ B = 0
     * Dot product of either vector with the cross product is always zero.
     * @dataProvider dataProviderForCrossProdcutMultiplyZero
     */
    public function testCrossProductInnerProductWithEitherVectorIsZero(array $A, array $B)
    {
        $A = new Vector($A);
        $B = new Vector($B);

        $AxB = $A->crossProduct($B);

        $this->assertEquals(0, $AxB->innerProduct($A));
        $this->assertEquals(0, $AxB->innerProduct($B));
    }

    public function dataProviderForCrossProdcutMultiplyZero()
    {
        return [
            [
                [1, 2, 3],
                [4, 5, 6],
            ],
            [
                [1, 2, 3],
                [4, -5, 6],
            ],
            [
                [-1, 2, -3],
                [4,-5,6],
            ],
            [
                [0,0,0],
                [0,0,0],
            ],
            [
                [4, 5, 6],
                [7, 8, 9],
            ],
            [
                [4, 9, 3],
                [12, 11, 4],
            ],
            [
                [-4, 9, 3],
                [12, 11, 4],
            ],
            [
                [4, -9, 3],
                [12, 11, 4],
            ],
            [
                [4, 9, -3],
                [12, 11, 4],
            ],
            [
                [4, 9, 3],
                [-12, 11, 4],
            ],
            [
                [4, 9, 3],
                [12, -11, 4],
            ],
            [
                [4, 9, 3],
                [12, 11, -4],
            ],
        ];
    }
}
