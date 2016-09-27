<?php
namespace Math\LinearAlgebra;

/**
 * Tests of Matrix axioms
 * These tests don't test specific functions,
 * but rather matrix axioms which in term make use of multiple functions.
 * If all the Matrix math is implemented properly, these tests should
 * all work out according to the axioms.
 *
 * Axioms tested:
 *  - Addition
 *    - r(A + B) = rA + rB
 *  - Multiplication
 *    - (AB)C = A(BC)
 *    - A(B + C) = AB + BC
 *    - r(AB) = (rA)B = A(rB)
 *  - Identity
 *    - AI = A = IA
 *  - Inverse
 *    - AA⁻¹ = I = A⁻¹A
 *    - (A⁻¹)⁻¹ = A
 *    - (AB)⁻¹ = B⁻¹A⁻¹
 *  - Transpose
 *    - (Aᵀ)ᵀ = A
 *    - (A⁻¹)ᵀ = (Aᵀ)⁻¹
 *    - (rA)ᵀ = rAᵀ
 *    - (AB)ᵀ = BᵀAᵀ
 *    - (A + B)ᵀ = Aᵀ + Bᵀ
 *  - Trace
 *    - tr(A) = tr(Aᵀ)
 *    - tr(AB) = tr(BA)
 *  - Determinant
 *    - det(A) = det(Aᵀ)
 *    - det(AB) = det(A)det(B)
 *  - LU Decomposition (PA = LU)
 *    - PA = LU
 *    - A = P⁻¹LU
 *    - PPᵀ = I = PᵀP
 *    - (PA)⁻¹ = (LU)⁻¹ = U⁻¹L⁻¹
 *    - P⁻¹ = Pᵀ
 *  - System of linear equations (Ax = b)
 *    - Ax - b = 0
 *    - x = A⁻¹b
 *  - Symmetric matrix
 *    - A = Aᵀ
 *    - A⁻¹Aᵀ = I
 *  - Kronecker product
 *    - A ⊗ (B + C) = A ⊗ B + A ⊗ C
 *    - (A + B) ⊗ C = A ⊗ C + B ⊗ C
 *    - (A ⊗ B) ⊗ C = A ⊗ (B ⊗ C)
 *    - (kA) ⊗ B = A ⊗ (kB) = k(A ⊗ B)
 *    - (A ⊗ B)⁻¹ = A⁻¹ ⊗ B⁻¹
 *    - (A ⊗ B)ᵀ = Aᵀ ⊗ Bᵀ
 *    - det(A ⊗ B) = det(A)ᵐ det(B)ⁿ
 */
class MatrixAxiomsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Axiom: r(A + B) = rA + rB
     * Order of scalar multiplication does not matter.
     *
     * @dataProvider dataProviderForScalarMultiplicationOrderAddition
     */
    public function testScalarMultiplicationOrderAddition(array $A, array $B, int $r)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);

        // r(A + B)
        $A＋B = $A->add($B);
        $r⟮A＋B⟯ = $A＋B->scalarMultiply($r);

        // rA + rB
        $rA     = $A->scalarMultiply($r);
        $rB     = $B->scalarMultiply($r);
        $rA＋rB = $rA->add($rB);

        $this->assertEquals($r⟮A＋B⟯->getMatrix(), $rA＋rB->getMatrix());
    }

    public function dataProviderForScalarMultiplicationOrderAddition()
    {
        return [
            [
                [
                    [1, 5],
                    [4, 3],
                ],
                [
                    [5, 6],
                    [2, 1],
                ], 5
            ],
            [
                [
                    [3, 8, 5],
                    [3, 6, 1],
                    [9, 5, 8],
                ],
                [
                    [5, 3, 8],
                    [6, 4, 5],
                    [1, 8, 9],
                ], 4
            ],
            [
                [
                    [-4, -2, 9],
                    [3, 14, -6],
                    [3, 9, 9],
                ],
                [
                    [8, 7, 8],
                    [-5, 4, 1],
                    [3, 5, 1],
                ], 7
            ],
            [
                [
                    [4, 7, 7, 8],
                    [3, 6, 4, 1],
                    [-3, 6, 8, -3],
                    [3, 2, 1, -54],
                ],
                [
                    [3, 2, 6, 7],
                    [4, 3, -6, 2],
                    [12, 14, 14, -6],
                    [4, 6, 4, -42],
                ], -8
            ],
        ];
    }

    /**
     * Axiom: (AB)C = A(BC)
     * Matrix multiplication is associative
     *
     * @dataProvider dataProviderForMultiplicationIsAssociative
     */
    public function testMultiplicationIsAssociative(array $A, array $B, array $C)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);
        $C = MatrixFactory::create($C);

        $⟮AB⟯  = $A->multiply($B);
        $⟮AB⟯C = $⟮AB⟯->multiply($C);

        $⟮BC⟯  = $B->multiply($C);
        $A⟮BC⟯ = $A->multiply($⟮BC⟯);

        $this->assertEquals($⟮AB⟯C->getMatrix(), $A⟮BC⟯->getMatrix());
    }

    public function dataProviderForMultiplicationIsAssociative()
    {
        return [
            [
                [
                    [1, 5, 3],
                    [3, 6, 3],
                    [6, 7, 8],
                ],
                [
                    [6, 9, 9],
                    [3, 5, 1],
                    [3, 5, 12],
                ],
                [
                    [7, 4, 6],
                    [2, 3, 1],
                    [10, 12, 5],
                ],
            ],
            [
                [
                    [12, 21, 6],
                    [-3, 11, -6],
                    [3, 6, -3],
                ],
                [
                    [3, 7, 8],
                    [4, 4, 2],
                    [6, -4, 1],
                ],
                [
                    [1, -1, -5],
                    [6, 5, 19],
                    [3, 6, -2],
                ],
            ],
        ];
    }

    /**
     * Axiom: A(B + C) = AB + AC
     * Matrix multiplication is distributive
     *
     * @dataProvider dataProviderForMultiplicationIsDistributive
     */
    public function testMultiplicationIsDistributive(array $A, array $B, array $C)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);
        $C = MatrixFactory::create($C);

        // A(B + C)
        $⟮B＋C⟯  = $B->add($C);
        $A⟮B＋C⟯ = $A->multiply($⟮B＋C⟯);

        // AB + AC
        $AB     = $A->multiply($B);
        $AC     = $A->multiply($C);
        $AB＋AC = $AB->add($AC);

        $this->assertEquals($A⟮B＋C⟯->getMatrix(), $AB＋AC->getMatrix());
    }

    public function dataProviderForMultiplicationIsDistributive()
    {
        return [
            [
                [
                    [1, 2],
                    [0, -1],
                ],
                [
                    [0, -1],
                    [1, 1],
                ],
                [
                    [-2, 0],
                    [0, 1],
                ],
            ],
            [
                [
                    [1, 5, 3],
                    [3, 6, 3],
                    [6, 7, 8],
                ],
                [
                    [6, 9, 9],
                    [3, 5, 1],
                    [3, 5, 12],
                ],
                [
                    [7, 4, 6],
                    [2, 3, 1],
                    [10, 12, 5],
                ],
            ],
            [
                [
                    [12, 21, 6],
                    [-3, 11, -6],
                    [3, 6, -3],
                ],
                [
                    [3, 7, 8],
                    [4, 4, 2],
                    [6, -4, 1],
                ],
                [
                    [1, -1, -5],
                    [6, 5, 19],
                    [3, 6, -2],
                ],
            ],
        ];
    }

    /**
     * Axiom: r(AB) = (rA)B = A(rB)
     * Order of scalar multiplication does not matter.
     *
     * @dataProvider dataProviderForScalarMultiplicationOrder
     */
    public function testScalarMultiplcationOrder(array $A, array $B, int $r)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);

        // r(AB)
        $AB = $A->multiply($B);
        $r⟮AB⟯ = $AB->scalarMultiply($r);

        // (rA)B
        $rA = $A->scalarMultiply($r);
        $⟮rA⟯B = $rA->multiply($B);

        // A(rB)
        $rB = $B->scalarMultiply($r);
        $A⟮rB⟯ = $A->multiply($rB);

        $this->assertEquals($r⟮AB⟯->getMatrix(), $⟮rA⟯B->getMatrix());
        $this->assertEquals($⟮rA⟯B->getMatrix(), $A⟮rB⟯->getMatrix());
        $this->assertEquals($r⟮AB⟯->getMatrix(), $A⟮rB⟯->getMatrix());
    }

    public function dataProviderForScalarMultiplicationOrder()
    {
        return [
            [
                [
                    [1, 5],
                    [4, 3],
                ],
                [
                    [5, 6],
                    [2, 1],
                ], 5
            ],
            [
                [
                    [3, 8, 5],
                    [3, 6, 1],
                    [9, 5, 8],
                ],
                [
                    [5, 3, 8],
                    [6, 4, 5],
                    [1, 8, 9],
                ], 4
            ],
            [
                [
                    [-4, -2, 9],
                    [3, 14, -6],
                    [3, 9, 9],
                ],
                [
                    [8, 7, 8],
                    [-5, 4, 1],
                    [3, 5, 1],
                ], 7
            ],
            [
                [
                    [4, 7, 7, 8],
                    [3, 6, 4, 1],
                    [-3, 6, 8, -3],
                    [3, 2, 1, -54],
                ],
                [
                    [3, 2, 6, 7],
                    [4, 3, -6, 2],
                    [12, 14, 14, -6],
                    [4, 6, 4, -42],
                ], -8
            ],
        ];
    }

    /**
     * Axiom: AI = A = IA
     * Matrix multiplied with the identity matrix is the original matrix.
     *
     * @dataProvider dataProviderForMatrixTimesIdentityIsOriginalMatrix
     */
    public function testMatrixTimesIdentityIsOriginalMatrix(array $A)
    {
        $A  = MatrixFactory::create($A);
        $I  = MatrixFactory::identity($A->getN());
        $AI = $A->multiply($I);
        $IA = $I->multiply($A);

        $this->assertEquals($A->getMatrix(), $AI->getMatrix());
        $this->assertEquals($A->getMatrix(), $IA->getMatrix());
    }

    public function dataProviderForMatrixTimesIdentityIsOriginalMatrix()
    {
        return [
            [
                [
                    [1, 5],
                    [4, 3],
                ],
            ],
            [
                [
                    [5, 6],
                    [2, 1],
                ],
            ],
            [
                [
                    [3, 8, 5],
                    [3, 6, 1],
                    [9, 5, 8],
                ],
            ],
            [
                [
                    [5, 3, 8],
                    [6, 4, 5],
                    [1, 8, 9],
                ],
            ],
            [
                [
                    [-4, -2, 9],
                    [3, 14, -6],
                    [3, 9, 9],
                ],
            ],
            [
                [
                    [8, 7, 8],
                    [-5, 4, 1],
                    [3, 5, 1],
                ],
            ],
            [
                [
                    [4, 7, 7, 8],
                    [3, 6, 4, 1],
                    [-3, 6, 8, -3],
                    [3, 2, 1, -54],
                ],
            ],
            [
                [
                    [3, 2, 6, 7],
                    [4, 3, -6, 2],
                    [12, 14, 14, -6],
                    [4, 6, 4, -42],
                ],
            ],
        ];
    }

    /**
     * Axiom: AA⁻¹ = I = A⁻¹A
     * Matrix multiplied with its inverse is the identity matrix.
     *
     * @dataProvider dataProviderForInverse
     */
    public function testMatrixTimesInverseIsIdentity(array $A, array $A⁻¹)
    {
        $A    = MatrixFactory::create($A);
        $A⁻¹  = $A->inverse();
        $AA⁻¹ = $A->multiply($A⁻¹);
        $A⁻¹A = $A⁻¹->multiply($A);
        $I    = MatrixFactory::identity($A->getN());

        $this->assertEquals($I->getMatrix(), $AA⁻¹->getMatrix());
        $this->assertEquals($I->getMatrix(), $A⁻¹A->getMatrix());
        $this->assertEquals($AA⁻¹->getMatrix(), $A⁻¹A->getMatrix());
    }

    /**
     * Axiom: (A⁻¹)⁻¹ = A
     * Inverse of inverse is the original matrix.
     *
     * @dataProvider dataProviderForOneSquareMatrix
     */
    public function testInverseOfInverseIsOriginalMatrix(array $A)
    {
        $A       = MatrixFactory::create($A);
        $⟮A⁻¹⟯⁻¹  = $A->inverse()->inverse();

        $this->assertEquals($A->getMatrix(), $⟮A⁻¹⟯⁻¹->getMatrix());
    }

    public function dataProviderForInverse()
    {
        return [
            [
                [
                    [4, 7],
                    [2, 6],
                ],
                [
                    [0.6, -0.7],
                    [-0.2, 0.4],
                ],
            ],
            [
                [
                    [4, 3],
                    [3, 2],
                ],
                [
                    [-2, 3],
                    [3, -4],
                ],
            ],
            [
                [
                    [1, 2],
                    [3, 4],
                ],
                [
                    [-2, 1],
                    [3/2, -1/2],
                ],
            ],
            [
                [
                    [3, 3.5],
                    [3.2, 3.6],
                ],
                [
                    [-9, 8.75],
                    [8, -7.5],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [0, 4, 5],
                    [1, 0, 6],
                ],
                [
                    [12/11, -6/11, -1/11],
                    [5/22, 3/22, -5/22],
                    [-2/11, 1/11, 2/11],
                ],
            ],
            [
                [
                    [7, 2, 1],
                    [0, 3, -1],
                    [-3, 4, -2],
                ],
                [
                    [-2, 8, -5],
                    [3, -11, 7],
                    [9, -34, 21],
                ],
            ],
            [
                [
                    [3, 6, 6, 8],
                    [4, 5, 3, 2],
                    [2, 2, 2, 3],
                    [6, 8, 4, 2],
                ],
                [
                    [-0.333, 0.667, 0.667, -0.333],
                    [0.167, -2.333, 0.167, 1.417],
                    [0.167, 4.667, -1.833, -2.583],
                    [0.000, -2.000, 1.000, 1.000],
                ],
            ],
            [
                [
                    [4, 23, 6, 4, 7],
                    [3, 64, 23, 52, 2],
                    [65, 45, 3, 23, 1],
                    [2, 3, 4, 3, 9],
                    [53, 99, 54, 32, 105],
                ],
                [
                    [-0.142, 0.006, 0.003, -0.338, 0.038],
                    [0.172, -0.012, 0.010, 0.275, -0.035],
                    [-0.856, 0.082, -0.089, -2.344, 0.257],
                    [0.164, -0.001, 0.026, 0.683, -0.070],
                    [0.300, -0.033, 0.027, 0.909, -0.088],
                ],
            ],
        ];
    }

    /**
     * (AB)⁻¹ = B⁻¹A⁻¹
     * The inverse of a product is the reverse product of the inverses.
     *
     * @dataProvider dataProviderForInverseProductIsReverseProductOfInverses
     */
    public function testInverseProductIsReverseProductOfInverses(array $A, array $B)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);

        $⟮AB⟯⁻¹ = $A->multiply($B)->inverse();

        $B⁻¹ = $B->inverse();
        $A⁻¹ = $A->inverse();
        $B⁻¹A⁻¹ = $B⁻¹->multiply($A⁻¹);

        $this->assertEquals($⟮AB⟯⁻¹->getMatrix(), $B⁻¹A⁻¹->getMatrix());
    }
    
    public function dataProviderForInverseProductIsReverseProductOfInverses()
    {
        return [
            [
                [
                    [1, 5],
                    [4, 3],
                ],
                [
                    [5, 6],
                    [2, 1],
                ],
            ],
            [
                [
                    [3, 8, 5],
                    [3, 6, 1],
                    [9, 5, 8],
                ],
                [
                    [5, 3, 8],
                    [6, 4, 5],
                    [1, 8, 9],
                ],
            ],
            [
                [
                    [-4, -2, 9],
                    [3, 14, -6],
                    [3, 9, 9],
                ],
                [
                    [8, 7, 8],
                    [-5, 4, 1],
                    [3, 5, 1],
                ],
            ],
            [
                [
                    [4, 7, 7, 8],
                    [3, 6, 4, 1],
                    [-3, 6, 8, -3],
                    [3, 2, 1, -54],
                ],
                [
                    [3, 2, 6, 7],
                    [4, 3, -6, 2],
                    [12, 14, 14, -6],
                    [4, 6, 4, -42],
                ],
            ],
        ];
    }

    /**
     * (Aᵀ)ᵀ = A
     * The transpose of the transpose is the original matrix.
     *
     * @dataProvider dataProviderForOneSquareMatrix
     */
    public function testTransposeOfTransposeIsOriginalMatrix(array $A)
    {
        $A     = MatrixFactory::create($A);
        $⟮A⁻ᵀ⟯ᵀ = $A->transpose()->transpose();

        $this->assertEquals($⟮A⁻ᵀ⟯ᵀ->getMatrix(), $A->getMatrix());
    }

    /**
     * (A⁻¹)ᵀ = (Aᵀ)⁻¹
     * The transpose of the inverse is the inverse of the transpose.
     *
     * @dataProvider dataProviderForOneSquareMatrix
     */
    public function testTransposeOfInverseIsInverseOfTranspose(array $A)
    {
        $A     = MatrixFactory::create($A);
        $⟮A⁻¹⟯ᵀ = $A->inverse()->transpose();
        $⟮Aᵀ⟯⁻¹ = $A->transpose()->inverse();

        $this->assertEquals($⟮A⁻¹⟯ᵀ->getMatrix(), $⟮Aᵀ⟯⁻¹->getMatrix());
    }

    /**
     * (rA)ᵀ = rAᵀ
     * Scalar multiplication order does not matter for transpose
     *
     * @dataProvider dataProviderForOneSquareMatrix
     */
    public function testScalarMultiplicationOfTransposeOrder(array $A)
    {
        $A     = MatrixFactory::create($A);
        $r     = 4;

        $⟮rA⟯ᵀ = $A->scalarMultiply($r)->transpose();
        $rAᵀ  = $A->transpose()->scalarMultiply($r);

        $this->assertEquals($⟮rA⟯ᵀ->getMatrix(), $rAᵀ->getMatrix());
    }

    public function dataProviderForOneSquareMatrix()
    {
        return [
            [
                [
                    [1, 5],
                    [4, 3],
                ],
            ],
            [
                [
                    [5, 6],
                    [2, 1],
                ],
            ],
            [
                [
                    [3, 8, 5],
                    [3, 6, 1],
                    [9, 5, 8],
                ],
            ],
            [
                [
                    [5, 3, 8],
                    [6, 4, 5],
                    [1, 8, 9],
                ],
            ],
            [
                [
                    [-4, -2, 9],
                    [3, 14, -6],
                    [3, 9, 9],
                ],
            ],
            [
                [
                    [8, 7, 8],
                    [-5, 4, 1],
                    [3, 5, 1],
                ],
            ],
            [
                [
                    [4, 7, 7, 8],
                    [3, 6, 4, 1],
                    [-3, 6, 8, -3],
                    [3, 2, 1, -54],
                ],
            ],
            [
                [
                    [3, 2, 6, 7],
                    [4, 3, -6, 2],
                    [12, 14, 14, -6],
                    [4, 6, 4, -42],
                ],
            ],
        ];
    }

    /**
     * (AB)ᵀ = BᵀAᵀ
     * Transpose of a product of matrices equals the product of their transposes in reverse order.
     *
     * @dataProvider dataProviderForTwoSquareMatrices
     */
    public function testTransposeProductIsProductOfTranposesInReverseOrder(array $A, array $B)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);

        // (AB)ᵀ
        $⟮AB⟯ᵀ = $A->multiply($B)->transpose();

        // BᵀAᵀ
        $Bᵀ   = $B->transpose();
        $Aᵀ   = $A->transpose();
        $BᵀAᵀ = $Bᵀ->multiply($Aᵀ);

        $this->assertEquals($⟮AB⟯ᵀ->getMatrix(), $BᵀAᵀ->getMatrix());
    }

    /**
     * (A + B)ᵀ = Aᵀ + Bᵀ
     * Transpose of sum is the same as sum of transposes
     *
     * @dataProvider dataProviderForTwoSquareMatrices
     */
    public function testTransposeSumIsSameAsSumOfTransposes(array $A, array $B)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);

        // (A + B)ᵀ
        $⟮A＋B⟯ᵀ = $A->add($B)->transpose();

        // Aᵀ + Bᵀ
        $Aᵀ     = $A->transpose();
        $Bᵀ     = $B->transpose();
        $Aᵀ＋Bᵀ = $Aᵀ->add($Bᵀ);

        $this->assertEquals($⟮A＋B⟯ᵀ->getMatrix(), $Aᵀ＋Bᵀ->getMatrix());
    }

    public function dataProviderForTwoSquareMatrices()
    {
        return [
            [
                [
                    [1, 5],
                    [4, 3],
                ],
                [
                    [5, 6],
                    [2, 1],
                ],
            ],
            [
                [
                    [3, 8, 5],
                    [3, 6, 1],
                    [9, 5, 8],
                ],
                [
                    [5, 3, 8],
                    [6, 4, 5],
                    [1, 8, 9],
                ],
            ],
            [
                [
                    [-4, -2, 9],
                    [3, 14, -6],
                    [3, 9, 9],
                ],
                [
                    [8, 7, 8],
                    [-5, 4, 1],
                    [3, 5, 1],
                ],
            ],
            [
                [
                    [4, 7, 7, 8],
                    [3, 6, 4, 1],
                    [-3, 6, 8, -3],
                    [3, 2, 1, -54],
                ],
                [
                    [3, 2, 6, 7],
                    [4, 3, -6, 2],
                    [12, 14, 14, -6],
                    [4, 6, 4, -42],
                ],
            ],
        ];
    }

    /**
     * tr(A) = tr(Aᵀ)
     * Trace is the same as the trace of the transpose
     *
     * @dataProvider dataProviderForOneSquareMatrix
     */
    public function testTraceIsSameAsTraceOfTranspose(array $A)
    {
        $A  = MatrixFactory::create($A);
        $Aᵀ = $A->transpose();

        $tr⟮A⟯  = $A->trace();
        $tr⟮Aᵀ⟯ = $Aᵀ->trace();

        $this->assertEquals($tr⟮A⟯, $tr⟮Aᵀ⟯);
    }

    /**
     * tr(AB) = tr(BA)
     * Trace of product does not matter the order they were multiplied
     *
     * @dataProvider dataProviderForTwoSquareMatrices
     */
    public function testTraceOfProductIsSameRegardlessOfOrderMultiplied(array $A, array $B)
    {
        $A  = MatrixFactory::create($A);
        $B  = MatrixFactory::create($B);

        $tr⟮AB⟯ = $A->multiply($B)->trace();
        $tr⟮BA⟯ = $B->multiply($A)->trace();

        $this->assertEquals($tr⟮AB⟯, $tr⟮BA⟯);
    }

    /**
     * det(A) = det(Aᵀ)
     * Determinant of matrix is the same as determinant of transpose.
     *
     * @dataProvider dataProviderForOneSquareMatrix
     */
    public function testDeterminantSameAsDeterminantOfTranspose(array $A)
    {
        $A = MatrixFactory::create($A);

        $det⟮A⟯  = $A->det();
        $det⟮Aᵀ⟯ = $A->transpose()->det();

        $this->assertEquals($det⟮A⟯, $det⟮Aᵀ⟯);
    }

    /**
     * det(AB) = det(A)det(B)
     * Determinant of product of matrices is the same as the product of determinants.
     *
     * @dataProvider dataProviderForTwoSquareMatrices
     */
    public function testDeterminantProductSameAsProductOfDeterminants(array $A, array $B)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);

        // det(AB)
        $det⟮AB⟯  = $A->multiply($B)->det();

        // det(A)det(B)
        $det⟮A⟯ = $A->det();
        $det⟮B⟯ = $B->det();
        $det⟮A⟯det⟮B⟯ = $det⟮A⟯ * $det⟮B⟯;

        $this->assertEquals($det⟮AB⟯, $det⟮A⟯det⟮B⟯, '', 0.000001);
    }

    /**
     * PA = LU
     * Basic LU decomposition property that permutation matrix times the matrix is the product of the lower and upper decomposition matrices.
     * @dataProvider dataProviderForOneSquareMatrix
     */
    public function testLUDecompositionPAEqualsLU(array $A)
    {
        $A = MatrixFactory::create($A);

        $LUP = $A->LUDecomposition();

        $L   = $LUP['L'];
        $U   = $LUP['U'];
        $P   = $LUP['P'];

        $PA = $P->multiply($A);
        $LU = $L->multiply($U);
        $UL = $U->multiply($L);

        $this->assertEquals($PA->getMatrix(), $LU->getMatrix());
    }

    /**
     * A = P⁻¹LU
     * @dataProvider dataProviderForOneSquareMatrix
     */
    public function testLUDecompositionAEqualsPInverseLU(array $A)
    {
        $A = MatrixFactory::create($A);

        $LUP = $A->LUDecomposition();

        $L   = $LUP['L'];
        $U   = $LUP['U'];
        $P   = $LUP['P'];

        $P⁻¹LU = $P->inverse()->multiply($L)->multiply($U);

        $this->assertEquals($A->getMatrix(), $P⁻¹LU->getMatrix());
    }

    /**
     * PPᵀ = I = PᵀP
     * Permutation matrix of the LU decomposition times the transpose of the permutation matrix is the identity matrix.
     * @dataProvider dataProviderForOneSquareMatrix
     */
    public function testLUDecompositionPPTransposeEqualsIdentity(array $A)
    {
        $A = MatrixFactory::create($A);

        $LUP = $A->LUDecomposition();

        $P  = $LUP['P'];
        $Pᵀ = $P->transpose();

        $PPᵀ = $P->multiply($Pᵀ);
        $PᵀP = $Pᵀ->multiply($P);

        $I = MatrixFactory::identity($A->getM());

        $this->assertEquals($PPᵀ->getMatrix(), $I->getMatrix());
        $this->assertEquals($I->getMatrix(), $PᵀP->getMatrix());
        $this->assertEquals($PPᵀ->getMatrix(), $PᵀP->getMatrix());
    }

    /**
     * (PA)⁻¹ = (LU)⁻¹ = U⁻¹L⁻¹
     * Inverse of the LU decomposition equation is the inverse of the other side.
     * @dataProvider dataProviderForOneSquareMatrix
     */
    public function testInverseWithLUDecompositionInverse(array $A)
    {
        $A = MatrixFactory::create($A);

        $LUP = $A->LUDecomposition();
        $L   = $LUP['L'];
        $U   = $LUP['U'];
        $P   = $LUP['P'];

        $⟮PA⟯⁻¹    = $P->multiply($A)->inverse();
        $⟮LU⟯⁻¹ = $L->multiply($U)->inverse();

        $U⁻¹      = $U->inverse();
        $L⁻¹      = $L->inverse();
        $U⁻¹L⁻¹   = $U⁻¹->multiply($L⁻¹);

        $this->assertEquals($⟮PA⟯⁻¹->getMatrix(), $⟮LU⟯⁻¹->getMatrix());
        $this->assertEquals($⟮LU⟯⁻¹->getMatrix(), $U⁻¹L⁻¹->getMatrix());
        $this->assertEquals($⟮PA⟯⁻¹->getMatrix(), $U⁻¹L⁻¹->getMatrix());
    }

    /**
     * P⁻¹ = Pᵀ
     * Inverse of the permutation matrix equals the transpose of the permutation matrix
     * @dataProvider dataProviderForOneSquareMatrix
     */
    public function testPInverseEqualsPTranspose(array $A)
    {
        $A = MatrixFactory::create($A);

        $LUP = $A->LUDecomposition();
        $P   = $LUP['P'];
        $P⁻¹ = $P->inverse();
        $Pᵀ  = $P->transpose();

        $this->assertEquals($P⁻¹, $Pᵀ);
    }

    /**
     * Axiom: Ax - b = 0
     * Matrix multiplied with unknown x vector subtract solution b is 0
     *
     * @dataProvider dataProviderForSolve
     */
    public function testSolveEquationForZero(array $A, array $b, array $x, array $zeros)
    {
        $A = MatrixFactory::create($A);
        $x = new Vector($x);
        $b = (new Vector($b))->asColumnMatrix();
        $z = (new Vector($zeros))->asColumnMatrix();

        // Ax - b
        $R = $A->multiply($x)->subtract($b);

        $this->assertEquals($z, $R, '', 0.01);
    }

    /**
     * Axiom: x = A⁻¹b
     * Matrix multiplied with unknown x vector subtract solution b is 0
     *
     * @dataProvider dataProviderForSolve
     */
    public function testSolveInverseBEqualsX(array $A, array $b, array $x, array $zeros)
    {
        $A   = MatrixFactory::create($A);
        $A⁻¹ = $A->inverse();
        $x = (new Vector($x))->asColumnMatrix();
        $b = new Vector($b);

        // A⁻¹b
        $A⁻¹b = $A⁻¹->multiply($b);

        $this->assertEquals($x, $A⁻¹b, '', 0.001);
    }

    public function dataProviderForSolve()
    {
        return [
            [
                [
                    [3, 4],
                    [2, -1],
                ],
                [5, 7],
                [3, -1],
                [0, 0],
            ],
            [
                [
                    [3, 1],
                    [2, -1],
                ],
                [5, 0],
                [1, 2],
                [0, 0],
            ],
            [
                [
                    [3, 4],
                    [5, 3],
                ],
                [-2, 4],
                [2, -2],
                [0, 0],
            ],
            [
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
                [2, 3, -4],
                [2, 3, -4],
                [0, 0, 0],
            ],
            [
                [
                    [1, 1, -1],
                    [3, 1, 1],
                    [1, -1, 4],
                ],
                [1, 9, 8],
                [3, -1, 1],
                [0, 0, 0],
            ],
            [
                [
                    [2, 4, 1],
                    [4, -10, 2],
                    [1, 2, 4],
                ],
                [5, -8, 13],
                [-1, 1, 3],
                [0, 0, 0],
            ],
            [
                [
                    [1, 1, 1],
                    [0, 2, 5],
                    [2, 5, -1],
                ],
                [6, -4, 27],
                [5, 3, -2],
                [0, 0, 0],
            ],
            [
                [
                    [1, 2, 3],
                    [2, -1, 1],
                    [3, 0, -1],
                ],
                [9, 8, 3],
                [2, -1, 3],
                [0, 0, 0],
            ],
            [
                [
                    [2, 1, -3],
                    [4, -2, 1],
                    [3, 5, -2],
                ],
                [-4, 9, 5],
                [2, 1, 3],
                [0, 0, 0],
            ],
            [
                [
                    [4, 9, 0],
                    [8, 0, 6],
                    [0, 6, 6],
                ],
                [8, -1, -1],
                [1/2, 2/3, -5/6],
                [0, 0, 0],
            ],
            [
                [
                    [1, 1, 1],
                    [1, -2, 2],
                    [1, 2, -1],
                ],
                [0, 4, 2],
                [4, -2, -2],
                [0, 0, 0],
            ],
            [
                [
                    [3, 3, 4],
                    [3, 5, 9],
                    [5, 9, 17],
                ],
                [1, 2, 4],
                [1, -2, 1],
                [0, 0, 0],
            ],
            [
                [
                    [2, 1, 1],
                    [-1, 1, -1],
                    [1, 2, 3],
                ],
                [2, 3, -10],
                [3, 1, -5],
                [0, 0, 0],
            ],
            [
                [
                    [4, 2, -1, 3],
                    [3, -4, 2, 5],
                    [-2, 6, -5, -2],
                    [5, 1, 6, -3],
                ],
                [16.9, -14, 25, 9.4],
                [4.5, 1.6, -3.8, -2.7],
                [0, 0, 0, 0],
            ],
            [
                [
                    [4, 2, -1, 3],
                    [3, -4, 2, 5],
                    [-2, 6, -5, -2],
                    [5, 1, 6, -3],
                ],
                [-12, 34, 27, -19],
                [-101.485, 101.242, 115.727, 102.394],
                [0, 0, 0, 0],
            ],
            [
                [
                    [ 4,  1,  2,  -3],
                    [-3,  3, -1,   4],
                    [-1,  2,  5,   1],
                    [ 5,  4,  3,  -1],
                ],
                [-16, 20, -4, -10],
                [-1, 1, -2, 3],
                [0, 0, 0, 0],
            ],
            [
                [
                    [ 4,  1,  2,  -3,  5],
                    [-3,  3, -1,   4, -2],
                    [-1,  2,  5,   1,  3],
                    [ 5,  4,  3,  -1,  2],
                    [ 1, -2,  3,  -4,  5],
                ],
                [-16, 20, -4, -10,  3],
                [-15.354, 15.813, -1.770, -22.148, -6.660],
                [0, 0, 0, 0, 0],
            ],
            [
                [
                    [1, 1, -2, 1, 3, -1],
                    [2, -1, 1, 2, 1, -3],
                    [1, 3, -3, -1, 2, 1],
                    [5, 2, -1, -1, 2, 1],
                    [-3, -1, 2, 3, 1, 3],
                    [4, 3, 1, -6, -3, -2],
                ],
                [4, 20, -15, -3, 16, -27],
                [1, -2, 3, 4, 2, -1],
                [0, 0, 0, 0, 0, 0],
            ],
        ];
    }

    /**
     * Axiom: A = Aᵀ
     * Symmetric matrix is the same as its transpose
     * @dataProvider dataProviderForSymmetric
     */
    public function testSymmetricEqualsTranspose(array $A)
    {
        $A  = MatrixFactory::create($A);
        $Aᵀ = $A->transpose();

        $this->assertEquals($A, $Aᵀ);
        $this->assertEquals($A->getMatrix(), $Aᵀ->getMatrix());
    }

    /**
     * Axiom: A⁻¹Aᵀ = I
     * Symmetric matrix inverse times tranpose equals identity matrix
     * @dataProvider dataProviderForSymmetric
     */
    public function testSymmetricInverseTranposeEqualsIdentity(array $A)
    {
        $A   = MatrixFactory::create($A);
        $A⁻¹ = $A->inverse();
        $Aᵀ  = $A->transpose();

        $A⁻¹Aᵀ = $A⁻¹->multiply($Aᵀ);
        $I     = MatrixFactory::identity($A->getM());

        $this->assertEquals($I, $A⁻¹Aᵀ);
        $this->assertEquals($I->getMatrix(), $A⁻¹Aᵀ->getMatrix());
    }

    public function dataProviderForSymmetric()
    {
        return [
            [
                [
                    [1],
                ],
            ],
            [
                [
                    [1, 2],
                    [2, 1],
                ],
            ],
            [
                [
                    [4, 1],
                    [1, -2],
                ],
            ],
            [
                [
                    [4, -1],
                    [-1, 9],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [2, 6, 4],
                    [3, 4, 5],
                ],
            ],
            [
                [
                    [1, 7, 3],
                    [7, 4, -5],
                    [3, -5, 6],
                ],
            ],
            [
                [
                    [5, 6, 7],
                    [6, 3, 2],
                    [7, 2, 1],
                ],
            ],
            [
                [
                    [2, 7, 3],
                    [7, 9, 4],
                    [3, 4, 7],
                ],
            ],
            [
                [
                    [4, -1, -1, -1],
                    [-1, 4, -1, -1],
                    [-1, -1, 4, -1],
                    [-1, -1, -1, 4],
                ],
            ],
            [
                [
                    [1, 5, 6, 8],
                    [5, 2, 7, 9],
                    [6, 7, 3, 10],
                    [8, 9, 10, 4],
                ],
            ],
        ];
    }

    /**
     * Axiom: A ⊗ (B + C) = A ⊗ B + A ⊗ C
     * Kronecker product bilinearity
     * @dataProvider dataProviderForThreeMatrices
     */
    public function testKroneckerProductBilinearity1(array $A, array $B, array $C)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);
        $C = MatrixFactory::create($C);

        $A⊗⟮B＋C⟯  = $A->kroneckerProduct($B->add($C));
        $A⊗B＋A⊗C = $A->kroneckerProduct($B)->add($A->kroneckerProduct($C));

        $this->assertEquals($A⊗⟮B＋C⟯->getMatrix(), $A⊗B＋A⊗C->getMatrix());
    }

    /**
     * Axiom: (A + B) ⊗ C = A ⊗ C + B ⊗ C
     * Kronecker product bilinearity
     * @dataProvider dataProviderForThreeMatrices
     */
    public function testKroneckerProductBilinearity2(array $A, array $B, array $C)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);
        $C = MatrixFactory::create($C);

        $⟮A＋B⟯⊗C  = $A->add($B)->kroneckerProduct($C);
        $A⊗C＋B⊗C = $A->kroneckerProduct($C)->add($B->kroneckerProduct($C));

        $this->assertEquals($⟮A＋B⟯⊗C->getMatrix(), $A⊗C＋B⊗C->getMatrix());
    }

    /**
     * Axiom: (A ⊗ B) ⊗ C = A ⊗ (B ⊗ C)
     * Kronecker product associative
     * @dataProvider dataProviderForThreeMatrices
     */
    public function testKroneckerProductAssociativity(array $A, array $B, array $C)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);
        $C = MatrixFactory::create($C);

        $⟮A⊗B⟯⊗C = $A->kroneckerProduct($B)->kroneckerProduct($C);
        $A⊗⟮B⊗C⟯ = $A->kroneckerProduct($B->kroneckerProduct($C));

        $this->assertEquals($⟮A⊗B⟯⊗C->getMatrix(), $A⊗⟮B⊗C⟯->getMatrix());
    }

    public function dataProviderForThreeMatrices()
    {
        return [
            [
                [
                    [1],
                ],
                [
                    [2],
                ],
                [
                    [3],
                ],
            ],
            [
                [
                    [1, 5, 3],
                    [3, 6, 3],
                    [6, 7, 8],
                ],
                [
                    [6, 9, 9],
                    [3, 5, 1],
                    [3, 5, 12],
                ],
                [
                    [7, 9, 6],
                    [1, 9, 1],
                    [10, 12, 4],
                ],
            ],
            [
                [
                    [12, 21, 6],
                    [-3, 11, -6],
                    [3, 6, -3],
                ],
                [
                    [3, 7, 8],
                    [4, 4, 2],
                    [6, -4, 1],
                ],
                [
                    [-1, -1, -5],
                    [8, 15, 15],
                    [8, 6, -12],
                ],
            ],
            [
                [
                    [1, 2],
                    [0, -1],
                ],
                [
                    [0, -1],
                    [1, 1],
                ],
                [
                    [2, 8],
                    [2, 1],
                ],
            ],
            [
                [
                    [1, 5, 3],
                    [3, 6, 3],
                    [6, 7, 8],
                ],
                [
                    [6, 9, 9],
                    [3, 5, 1],
                    [3, 5, 12],
                ],
                [
                    [6, 4, 9],
                    [12, 3, -1],
                    [10, 2, 15],
                ],
            ],
            [
                [
                    [12, 21, 6],
                    [-3, 11, -6],
                    [3, 6, -3],
                ],
                [
                    [3, 7, 8],
                    [4, 4, 2],
                    [6, -4, 1],
                ],
                [
                    [1, 1, 5],
                    [3, 4, 9],
                    [3, 16, -2],
                ],
            ],
            [
                [
                    [1, 2, 3, 4, 5],
                    [2, 3, 4, 5, 6],
                    [4, 5, 6, 7, 8],
                    [6, 5, 4, 5, 7],
                ],
                [
                    [1, 2, 5, 5, 6],
                    [2, 3, 5, 5, 6],
                    [5, 4, 5, 5, 6],
                    [3, 2, 5, 5, 6],
                ],
                [
                    [5, 5, 7, 8, 9],
                    [4, 4, 7, 8, 9],
                    [7, 6, 7, 6, 7],
                    [9, 9, 9, 0, 0],
                ]
            ],
        ];
    }

    /**
     * Axiom: (kA) ⊗ B = A ⊗ (kB) = k(A ⊗ B)
     * Kronecker product scalar multiplication
     * @dataProvider dataProviderForTwoSquareMatrices
     */
    public function testKroneckerProductScalarMultiplication(array $A, array $B)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);
        $k = 5;

        $⟮kA⟯⊗B = $A->scalarMultiply($k)->kroneckerProduct($B);
        $A⊗⟮kB⟯ = $A->kroneckerProduct($B->scalarMultiply($k));
        $k⟮A⊗B⟯ = $A->kroneckerProduct($B)->scalarMultiply($k);

        $this->assertEquals($⟮kA⟯⊗B->getMatrix(), $A⊗⟮kB⟯->getMatrix());
        $this->assertEquals($⟮kA⟯⊗B->getMatrix(), $k⟮A⊗B⟯->getMatrix());
        $this->assertEquals($k⟮A⊗B⟯->getMatrix(), $A⊗⟮kB⟯->getMatrix());
    }

    /**
     * Axiom: (A ⊗ B)⁻¹ = A⁻¹ ⊗ B⁻¹
     * Inverse of Kronecker product
     * @dataProvider dataProviderForTwoSquareMatrices
     */
    public function testKroneckerProductInverse(array $A, array $B)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);

        $A⁻¹     = $A->inverse();
        $B⁻¹     = $B->inverse();
        $A⁻¹⊗B⁻¹ = $A⁻¹->kroneckerProduct($B⁻¹);
        $⟮A⊗B⟯⁻¹  = $A->kroneckerProduct($B)->inverse();

        $this->assertEquals($A⁻¹⊗B⁻¹->getMatrix(), $⟮A⊗B⟯⁻¹->getMatrix());
    }

    /**
     * Axiom: (A ⊗ B)ᵀ = Aᵀ ⊗ Bᵀ
     * Transpose of Kronecker product
     * @dataProvider dataProviderForTwoSquareMatrices
     */
    public function testKroneckerProductTranspose(array $A, array $B)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);

        $Aᵀ    = $A->transpose();
        $Bᵀ    = $B->transpose();
        $Aᵀ⊗Bᵀ = $Aᵀ->kroneckerProduct($Bᵀ);
        $⟮A⊗B⟯ᵀ = $A->kroneckerProduct($B)->transpose();

        $this->assertEquals($Aᵀ⊗Bᵀ->getMatrix(), $⟮A⊗B⟯ᵀ->getMatrix());
    }

    /**
     * Axiom: det(A ⊗ B) = det(A)ᵐ det(B)ⁿ
     * Determinant of Kronecker product - where A is nxn matrix, and b is nxn matrix
     * @dataProvider dataProviderForKroneckerProductDeterminant
     */
    public function testKroneckerProductDeterminant(array $A, array $B)
    {
        $A = MatrixFactory::create($A);
        $B = MatrixFactory::create($B);

        $det⟮A⟯ᵐ  = ($A->det())**$B->getM();
        $det⟮B⟯ⁿ  = ($B->det())**$A->getN();
        $det⟮A⊗B⟯ = $A->kroneckerProduct($B)->det();

        $this->assertEquals($det⟮A⊗B⟯, $det⟮A⟯ᵐ  * $det⟮B⟯ⁿ, '', 0.0001);
    }

    public function dataProviderForKroneckerProductDeterminant()
    {
        return [
            [
                [
                    [5],
                ],
                [
                    [4],
                ],
            ],
            [
                [
                    [5, 6],
                    [2, 4],
                ],
                [
                    [4, 9],
                    [3, 1],
                ],
            ],
            [
                [
                    [5, 6],
                    [-2, 4],
                ],
                [
                    [4, -9],
                    [3, 1],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [2, 4, 6],
                    [7, 6, 5],
                ],
                [
                    [2, 3, 4],
                    [3, 1, 6],
                    [4, 3, 3],
                ],
            ],
            [
                [
                    [1, -2, 3],
                    [2, -4, 6],
                    [7, -6, 5],
                ],
                [
                    [2, 3, 4],
                    [3, 1, 6],
                    [4, 3, 3],
                ],
            ],
            [
                [
                    [1, 2, 3, 4],
                    [2, 4, 6, 8],
                    [7, 6, 5, 4],
                    [1, 3, 5, 7],
                ],
                [
                    [2, 3, 4, 5],
                    [3, 1, 6, 3],
                    [4, 3, 3, 4],
                    [3, 3, 4, 1],
                ],
            ],
            [
                [
                    [-1, 2, 3, 4],
                    [2, 4, 6, 8],
                    [7, 6, 5, 4],
                    [1, 3, 5, 7],
                ],
                [
                    [2, 3, 4, 5],
                    [3, 1, 6, 3],
                    [4, 3, 3, -4],
                    [3, 3, 4, 1],
                ],
            ],
            [
                [
                    [1, 2, 3, 4, 5],
                    [2, 4, 6, 8, 10],
                    [7, 6, 5, 4, 5],
                    [1, 3, 5, 7, 9],
                    [1, 2, 3, 4, 5],
                ],
                [
                    [2, 3, 4, 5, 2],
                    [3, 1, 6, 3, 2],
                    [4, 3, 3, 4, 2],
                    [3, 3, 4, 1, 2],
                    [1, 1, 2, 2, 1],
                ],
            ],
        ];
    }
}
