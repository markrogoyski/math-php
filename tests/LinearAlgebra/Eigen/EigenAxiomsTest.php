<?php

namespace MathPHP\Tests\LinearAlgebra\Eigen;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;
use MathPHP\Tests;

class EigenAxiomsTest extends \PHPUnit\Framework\TestCase
{
    use Tests\LinearAlgebra\Fixture\MatrixDataProvider;

    /**
     * Tests of Matrix eigenvector and eigenvalue axioms
     * These tests don't test specific functions,
     * but rather matrix axioms which in term make use of multiple functions.
     * If all the Matrix math is implemented properly, these tests should
     * all work out according to the axioms.
     *
     * Axioms tested:
     *  - Eigenvalues and eigenvectors
     *    - Av = λv (basic eigenvalue equation)
     *    - det(A - λI) = 0 (characteristic equation)
     *    - tr(A) = Σλᵢ (trace equals sum of eigenvalues)
     *    - det(A) = Πλᵢ (determinant equals product of eigenvalues)
     *    - Aⁿv = λⁿv (matrix power property)
     */

    /**************************************************************************
     * EIGENVALUE AND EIGENVECTOR AXIOMS
     **************************************************************************/

    /**
     * @test Axiom: Av = λv (basic eigenvalue equation)
     * For each eigenvalue λ and corresponding eigenvector v, the equation Av = λv must hold
     *
     * @dataProvider dataProviderForEigenvalueAxioms
     * @param        array $A
     * @throws       \Exception
     */
    public function testBasicEigenvalueEquation(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $eigenvalues  = $A->eigenvalues();
        $eigenvectors = $A->eigenvectors();

        // Then
        for ($i = 0; $i < \count($eigenvalues); $i++) {
            $λ = $eigenvalues[$i];
            $v = new Vector($eigenvectors->getColumn($i));

            $Av = $A->vectorMultiply($v);
            $λv = $v->scalarMultiply($λ);

            // Av should equal λv
            $this->assertEqualsWithDelta($Av->getVector(), $λv->getVector(), 1e-6);
        }
    }

    /**
     * @test Axiom: det(A - λI) = 0 (characteristic equation)
     * For each eigenvalue λ, the determinant of (A - λI) should be zero
     *
     * @dataProvider dataProviderForEigenvalueAxioms
     * @param        array $A
     * @throws       \Exception
     */
    public function testCharacteristicEquation(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);
        $I = MatrixFactory::identity($A->getM());

        // When
        $eigenvalues = $A->eigenvalues();

        // Then
        foreach ($eigenvalues as $λ) {
            $λI         = $I->scalarMultiply($λ);
            $A_minus_λI = $A->subtract($λI);
            $det        = $A_minus_λI->det();

            $this->assertEqualsWithDelta(0, $det, 1e-6);
        }
    }

    /**
     * @test Axiom: tr(A) = Σλᵢ (trace equals sum of eigenvalues)
     * The trace of a matrix equals the sum of its eigenvalues
     *
     * @dataProvider dataProviderForEigenvalueAxioms
     * @param        array $A
     * @throws       \Exception
     */
    public function testTraceEqualsSumOfEigenvalues(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $trace              = $A->trace();
        $eigenvalues        = $A->eigenvalues();
        $sum_of_eigenvalues = \array_sum($eigenvalues);

        // Then
        $this->assertEqualsWithDelta($trace, $sum_of_eigenvalues, 1e-6);
    }

    /**
     * @test Axiom: det(A) = Πλᵢ (determinant equals product of eigenvalues)
     * The determinant of a matrix equals the product of its eigenvalues
     *
     * @dataProvider dataProviderForEigenvalueAxioms
     * @param        array $A
     * @throws       \Exception
     */
    public function testDeterminantEqualsProductOfEigenvalues(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $det                    = $A->det();
        $eigenvalues            = $A->eigenvalues();
        $product_of_eigenvalues = \array_product($eigenvalues);

        // Then
        $this->assertEqualsWithDelta($det, $product_of_eigenvalues, 1e-6);
    }

    /**
     * @test Axiom: Aⁿv = λⁿv (matrix power property)
     * For eigenvalue λ and eigenvector v, raising the matrix to power n gives A^n v = λ^n v
     *
     * @dataProvider dataProviderForEigenvalueMatrixPower
     * @param        array $A
     * @param        int   $n
     * @throws       \Exception
     */
    public function testMatrixPowerProperty(array $A, int $n)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $eigenvalues  = $A->eigenvalues();
        $eigenvectors = $A->eigenvectors();

        // Calculate Aⁿ by repeated multiplication
        $An = MatrixFactory::identity($A->getM());
        for ($i = 0; $i < $n; $i++) {
            $An = $An->multiply($A);
        }

        // Then
        for ($i = 0; $i < \count($eigenvalues); $i++) {
            $λ = $eigenvalues[$i];
            $v = new Vector($eigenvectors->getColumn($i));

            $Aⁿv = $An->vectorMultiply($v);
            $λⁿv = $v->scalarMultiply(pow($λ, $n));

            // Aⁿv should equal λⁿv
            for ($j = 0; $j < $Aⁿv->getN(); $j++) {
                $this->assertEqualsWithDelta($λⁿv->get($j), $Aⁿv->get($j), 1e-5);
            }
        }
    }

    /**************************************************************************
     * DATA PROVIDERS FOR EIGENVALUE TESTS
     **************************************************************************/

    /**
     * Data provider for eigenvalue axiom tests with well-conditioned matrices
     * @return array
     */
    public function dataProviderForEigenvalueAxioms(): array
    {
        return [
            // 2x2 diagonal matrix (simple eigenvalues)
            [
                [
                    [2, 0],
                    [0, 3],
                ],
            ],
            // 2x2 symmetric matrix
            [
                [
                    [1, 2],
                    [2, 1],
                ],
            ],
            // 2x2 general matrix
            [
                [
                    [0, 1],
                    [-2, -3],
                ],
            ],
            // 3x3 diagonal matrix
            [
                [
                    [1, 0, 0],
                    [0, 2, 0],
                    [0, 0, 3],
                ],
            ],
            // 3x3 symmetric matrix
            [
                [
                    [2, -1, 0],
                    [-1, 2, -1],
                    [0, -1, 2],
                ],
            ],
            // 3x3 upper triangular matrix
            [
                [
                    [1, 2, 3],
                    [0, 4, 5],
                    [0, 0, 6],
                ],
            ],
            // 3x3 general matrix
            [
                [
                    [-2, -4, 2],
                    [-2, 1, 2],
                    [4, 2, 5],
                ],
            ],
        ];
    }

    /**
     * Data provider for matrix power eigenvalue tests
     * @return array
     */
    public function dataProviderForEigenvalueMatrixPower(): array
    {
        return [
            // 2x2 diagonal matrix with power 2
            [
                [
                    [2, 0],
                    [0, 3],
                ],
                2,
            ],
            // 2x2 diagonal matrix with power 3
            [
                [
                    [2, 0],
                    [0, 3],
                ],
                3,
            ],
            // 2x2 symmetric matrix with power 2
            [
                [
                    [1, 2],
                    [2, 1],
                ],
                2,
            ],
            // 3x3 diagonal matrix with power 2
            [
                [
                    [1, 0, 0],
                    [0, 2, 0],
                    [0, 0, 3],
                ],
                2,
            ],
            // 3x3 upper triangular matrix with power 2
            [
                [
                    [1, 1, 0],
                    [0, 2, 1],
                    [0, 0, 3],
                ],
                2,
            ],
        ];
    }
}
