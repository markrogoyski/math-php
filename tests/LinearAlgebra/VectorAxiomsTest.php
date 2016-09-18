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
 *    - 0⋅A = A⋅0 = 0
 *  - Cross product
 *    - A x B = -(B x A)
 *  - Cross product / dot product
 *    - (A x B) ⋅ A = 0
 *    - (A x B) ⋅ B = 0
 *  - Outer product
 *    - A⨂B = ABᵀ
 *  - Scalar multiplication
 *    - (c + d)A = cA + dA
 *    - c(A + B) = cA + cB
 *    - 1A = A
 *    - 0A = 0
 *    - -1A = -A
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
     * Axiom: 0⋅A = A⋅0 = 0
     * Dot product of a vector and zero is zero.
     * @dataProvider dataProviderForDotProductZero
     */
    public function testDotProductZero(array $A, array $zero)
    {
        $A    = new Vector($A);
        $zero = new Vector($zero);

        $A⋅zero = $A->dotProduct($zero);
        $zero⋅A = $zero->dotProduct($A);

        $this->assertEquals(0, $A⋅zero);
        $this->assertEquals(0, $zero⋅A);
        $this->assertEquals($A⋅zero, $zero⋅A);
    }

    public function dataProviderForDotProductZero()
    {
        return [
            [
                [1],
                [0],
            ],
            [
                [1, 2],
                [0, 0],
            ],
            [
                [1, 2, 3],
                [0, 0, 0],
            ],
            [
                [5, 6, 7, 3, 4, 5, 6, 7, 8, 6, 5],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            ],
        ];
    }

    /**
     * Axiom: A x B = -(B x A)
     * Reverse order cross product results in a negative cross product
     * @dataProvider dataProviderForCrossProduct
     */
    public function testReverseCrossProduct(array $A, array $B)
    {
        $A = new Vector($A);
        $B = new Vector($B);

        $AxB = $A->crossProduct($B);
        $BxA = $B->crossProduct($A);

        $this->assertEquals($AxB[0], -$BxA[0]);
        $this->assertEquals($AxB[1], -$BxA[1]);
        $this->assertEquals($AxB[2], -$BxA[2]);
    }

    /**
     * Axiom: (A x B) ⋅ A = 0
     * Axiom: (A x B) ⋅ B = 0
     * Dot product of either vector with the cross product is always zero.
     * @dataProvider dataProviderForCrossProduct
     */
    public function testCrossProductInnerProductWithEitherVectorIsZero(array $A, array $B)
    {
        $A = new Vector($A);
        $B = new Vector($B);

        $AxB = $A->crossProduct($B);

        $this->assertEquals(0, $AxB->innerProduct($A));
        $this->assertEquals(0, $AxB->innerProduct($B));
    }

    public function dataProviderForCrossProduct()
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

    /**
     * Axiom: A⨂B = ABᵀ
     * Outer product is the same as matrix multiplication of A and transpose of B
     * @dataProvider dataProviderForOuterProduct
     */
    public function testOuterProductIsMatrixMultiplicationOfAAndBTranspose(array $A, array $B)
    {
        // Vector A⨂B
        $Av   = new Vector($A);
        $Bv   = new Vector($B);
        $A⨂B = $Av->outerProduct($Bv);

        // Matrix multiplication ABᵀ
        $Am = $Av->asColumnMatrix();
        $Bᵀ  = new Matrix([
            $Bv->getVector()
        ]);
        $ABᵀ = $Am->multiply($Bᵀ);

        $this->assertEquals($A⨂B, $ABᵀ);
    }

    public function dataProviderForOuterProduct()
    {
        return [
            [
                [2],
                [6],
            ],
            [
                [2, 5, 8],
                [6, 4, 9],
            ],
            [
                [3, 6, 3, 5, 8, 21],
                [12, 4, 5, 3, 21, 4],
            ],
        ];
    }

    /**
     * Axiom: (c + d)A = cA + dA
     * Additivity in the scalar
     * @dataProvider dataProviderForSingleVector
     */
    public function testAdditivityInTheScalarForScalarMultiplication(array $A)
    {
        $A = new Vector($A);
        $c = 2;
        $d = 9;

        $⟮c＋d⟯A  = $A->scalarMultiply($c + $d);
        $⟮cA＋dA⟯ = $A->scalarMultiply($c)->add($A->scalarMultiply($d));

        $this->assertEquals($⟮c＋d⟯A, $⟮cA＋dA⟯);
        $this->assertEquals($⟮c＋d⟯A->getVector(), $⟮cA＋dA⟯->getVector());
    }

    /**
     * Axiom: c(A + B) = cA + cB
     * Additivity in the vector
     * @dataProvider dataProviderForTwoVectors
     */
    public function testAdditivityInTheVectorForScalarMultiplication(array $A, array $B)
    {
        $A = new Vector($A);
        $B = new Vector($B);

        $c = 4;

        $c⟮A＋B⟯ = $A->add($B)->scalarMultiply($c);
        $⟮cA＋cB⟯ = $A->scalarMultiply($c)->add($B->scalarMultiply($c));

        $this->assertEquals($c⟮A＋B⟯, $⟮cA＋cB⟯);
        $this->assertEquals($c⟮A＋B⟯->getVector(), $⟮cA＋cB⟯->getVector());
    }

    /**
     * Axiom: 1A = A
     * Multiplying (scaling) by 1 does not change the vector
     * @dataProvider dataProviderForSingleVector
     */
    public function testScalarMultiplyOneIdentity(array $A)
    {
        $A = new Vector($A);
        $１A = $A->scalarMultiply(1);

        $this->assertEquals($A, $１A);
        $this->assertEquals($A->getVector(), $１A->getVector());
    }

    /**
     * Axiom: 0A = 0
     * Multiplying (scaling) by 0 gives the zero vector
     * @dataProvider dataProviderForSingleVector
     */
    public function testScalarMultiplyZeroIdentity(array $A)
    {
        $A    = new Vector($A);
        $０A  = $A->scalarMultiply(0);
        $zero = new Vector(array_fill(0, $A->getN(), 0)); 

        $this->assertEquals($zero, $０A);
        $this->assertEquals($zero->getVector(), $０A->getVector());
    }

    /**
     * Axiom: -1A = -A
     * Additive inverse
     * @dataProvider dataProviderForAdditiveInverse
     */
    public function testScalarMultiplyNegativeOneIdentity(array $A, array $R)
    {
        $A    = new Vector($A);
        $ーA  = $A->scalarMultiply(-1);
        $R    = new Vector($R);

        $this->assertEquals($R, $ーA);
        $this->assertEquals($R->getVector(), $ーA->getVector());
    }

    public function dataProviderForAdditiveInverse()
    {
        return [
            [
                [],
                [],
            ],
            [
                [2],
                [-2],
            ],
            [
                [0, 1, 2, 3, 4, 5, -6, -7, 8],
                [0, -1, -2, -3, -4, -5, 6, 7, -8],
            ],
        ];
    }
}
