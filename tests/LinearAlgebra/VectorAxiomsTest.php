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
    public function testMaxNormLessThtanEQualL1NormLessThanEqualSqrtNMaxNorm(array $V)
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
}
