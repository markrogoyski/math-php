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
}
