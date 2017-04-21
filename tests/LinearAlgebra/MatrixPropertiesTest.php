<?php
namespace MathPHP\LinearAlgebra;

class MatrixPropertiesTest extends \PHPUnit_Framework_TestCase
{
    use \MathPHP\Tests\LinearAlgebra\MatrixDataProvider;

    /**
     * @testCase     isSquare returns true for square matrices.
     * @dataProvider dataProviderForSquareMatrix
     */
    public function testIsSquare(array $A)
    {
        $A = MatrixFactory::create($A);
        $this->assertTrue($A->isSquare());
    }

    /**
     * @testCase     isSquare returns false for nonsquare matrices.
     * @dataProvider dataProviderForNotSquareMatrix
     */
    public function testIsSquareFalseNonSquareMatrix(array $A)
    {
        $A = MatrixFactory::create($A);
        $this->assertFalse($A->isSquare());
    }

    /**
     * @testCase     isNotSquare returns true for nonsquare matrices.
     * @dataProvider dataProviderForNotSquareMatrix
     */
    public function testIsNotSquare(array $A)
    {
        $A = MatrixFactory::create($A);
        $this->assertFalse($A->isSquare());
    }

    /**
     * @testCase     isSymmetric returns true for symmetric matrices.
     * @dataProvider dataProviderForSymmetricMatrix
     */
    public function testIsSymmetric(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isSymmetric());
    }

    /**
     * @testCase     isSymmetric returns false for nonsymmetric matrices.
     * @dataProvider dataProviderForNotSymmetricMatrix
     */
    public function testIsNotSymmetric(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isSymmetric());
    }

    /**
     * @testCase     isSingular returns true for a singular matrix.
     * @dataProvider dataProviderForSingularMatrix
     */
    public function testIsSingular(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isSingular());
    }

    /**
     * @testCase     isSingular returns false for a nonsingular matrix.
     * @dataProvider dataProviderForNonsingularMatrix
     */
    public function testIsSingularFalseForNonsingularMatrix(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isSingular());
    }

    /**
     * @testCase     isNonsingular returns true for a nonsingular matrix.
     * @dataProvider dataProviderForNonsingularMatrix
     */
    public function testIsNonsingular(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isNonsingular());
    }

    /**
     * @testCase     isInvertible returns true for a invertible matrix.
     * @dataProvider dataProviderForNonsingularMatrix
     */
    public function testIsInvertible(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isInvertible());
    }

    /**
     * @testCase     isNonsingular returns false for a singular matrix.
     * @dataProvider dataProviderForSingularMatrix
     */
    public function testIsNonsingularFalseForSingularMatrix(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isNonsingular());
    }

    /**
     * @testCase     isInvertible returns false for a non-invertible matrix.
     * @dataProvider dataProviderForSingularMatrix
     */
    public function testIsInvertibleFalseForNonInvertibleMatrix(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isInvertible());
    }

    /**
     * @testCase     isPositiveDefinite returns true for a positive definite square matrix.
     * @dataProvider dataProviderForPositiveDefiniteMatrix
     * @param        array $A
     */
    public function testIsPositiveDefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isPositiveDefinite());
    }

    /**
     * @testCase     isPositiveDefinite returns false for a non positive definite square matrix.
     * @dataProvider dataProviderForNotPositiveDefiniteMatrix
     * @param        array $A
     */
    public function testIsNotPositiveDefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isPositiveDefinite());
    }

    /**
     * @testCase     isPositiveSemidefinite returns true for a positive definite square matrix.
     * @dataProvider dataProviderForPositiveSemidefiniteMatrix
     * @param        array $A
     */
    public function testIsPositiveSemidefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isPositiveSemidefinite());
    }

    /**
     * @testCase     isPositiveSemidefinite returns false for a non positive semidefinite square matrix.
     * @dataProvider dataProviderForNotPositiveSemidefiniteMatrix
     * @param        array $A
     */
    public function testIsNotPositiveSemiDefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isPositiveSemidefinite());
    }

    /**
     * @testCase     isNegativeDefinite returns true for a negative definite square matrix.
     * @dataProvider dataProviderForNegativeDefiniteMatrix
     * @param        array $A
     */
    public function testIsNegativeDefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isNegativeDefinite());
    }

    /**
     * @testCase     isNegativeDefinite returns false for a non negative definite square matrix.
     * @dataProvider dataProviderForNotNegativeDefiniteMatrix
     * @param        array $A
     */
    public function testIsNotNegativeDefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isNegativeDefinite());
    }

    /**
     * @testCase     isNegativeSemidefinite returns true for a negative semidefinite square matrix.
     * @dataProvider dataProviderForNegativeSemidefiniteMatrix
     * @param        array $A
     */
    public function testIsNegativeSemidefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isNegativeSemidefinite());
    }

    /**
     * @testCase     isNegativeSemidefinite returns false for a non negative semidefinite square matrix.
     * @dataProvider dataProviderForNotNegativeSemidefiniteMatrix
     * @param        array $A
     */
    public function testIsNotNegativeSemidefinite(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isNegativeSemidefinite());
    }

    /**
     * @testCase Non square matrix is not any definite.
     */
    public function testNonSquareMatrixIsNotAnyDefinite()
    {
        $A = new Matrix([
            [1, 2, 3],
            [2, 3, 4],
        ]);

        $this->assertFalse($A->isPositiveDefinite());
        $this->assertFalse($A->isPositiveSemidefinite());
        $this->assertFalse($A->isNegativeDefinite());
        $this->assertFalse($A->isNegativeSemidefinite());
    }

    /**
     * @testCase Non symmetric square matrix is not any definite.
     */
    public function testNonSymmetricSquareMatrixIsNotAnyDefinite()
    {
        $A = new Matrix([
            [1, 2, 3],
            [9, 8, 4],
            [6, 2, 5],
        ]);

        $this->assertFalse($A->isPositiveDefinite());
        $this->assertFalse($A->isPositiveSemidefinite());
        $this->assertFalse($A->isNegativeDefinite());
        $this->assertFalse($A->isNegativeSemidefinite());
    }

    /**
     * @testCase     isSquareAndSymmetric returns true for square symmetric matrices
     * @dataProvider dataProviderForPositiveDefiniteMatrix
     */
    public function testIsSquareAndSymmetric(array $A)
    {
        $A = MatrixFactory::create($A);

        $reflection_method = new \ReflectionMethod(Matrix::class, 'isSquareAndSymmetric');
        $reflection_method->setAccessible(true);

        $this->assertTrue($reflection_method->invoke($A));
    }

    /**
     * @testCase     isSquareAndSymmetric returns false for non square symmetric matrices
     * @dataProvider dataProviderForNotSquareAndSymmetricMatrix
     */
    public function testIsNotSquareAndSymmetric(array $A)
    {
        $A = MatrixFactory::create($A);

        $reflection_method = new \ReflectionMethod(Matrix::class, 'isSquareAndSymmetric');
        $reflection_method->setAccessible(true);

        $this->assertFalse($reflection_method->invoke($A));
    }

    /**
     * @testCase     isUpperTriangular returns true for an upper triangular matrix
     * @dataProvider dataProviderForUpperTriangularMatrix
     * @param        array $A
     */
    public function testIsUpperTriangular(array $U)
    {
        $U = MatrixFactory::create($U);

        $this->assertTrue($U->isUpperTriangular());
    }

    /**
     * @testCase     isUpperTriangular returns false for a non upper triangular matrix
     * @dataProvider dataProviderForNotTriangularMatrix
     * @param        array $A
     */
    public function testIsNotUpperTriangular(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isUpperTriangular());
    }

    /**
     * @testCase     isLowerTriangular returns true for an upper triangular matrix
     * @dataProvider dataProviderForLowerTriangularMatrix
     * @param        array $A
     */
    public function testIsLowerTriangular(array $L)
    {
        $L = MatrixFactory::create($L);

        $this->assertTrue($L->isLowerTriangular());
    }

    /**
     * @testCase     isLowerTriangular returns false for a non upper triangular matrix
     * @dataProvider dataProviderForNotTriangularMatrix
     * @param        array $A
     */
    public function testIsNotLowerTriangular(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isLowerTriangular());
    }

    /**
     * @testCase     isTriangular returns true for a lower triangular matrix
     * @dataProvider dataProviderForLowerTriangularMatrix
     * @param        array $L
     */
    public function testIsTriangularForLowerTriangular(array $L)
    {
        $L = MatrixFactory::create($L);

        $this->assertTrue($L->isTriangular());
    }

    /**
     * @testCase     isTriangular returns true for an upper triangular matrix
     * @dataProvider dataProviderForUpperTriangularMatrix
     * @param        array $A
     */
    public function testIsTriangularForUpperTriangular(array $U)
    {
        $U = MatrixFactory::create($U);

        $this->assertTrue($U->isTriangular());
    }

    /**
     * @testCase     isTriangular returns false for a non triangular matrix
     * @dataProvider dataProviderForNotTriangularMatrix
     * @param        array $A
     */
    public function testIsNotTriangular(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isTriangular());
    }

    /**
     * @testCase     isDiagonal returns true for a diagonal matrix
     * @dataProvider dataProviderForDiagonalMatrix
     * @param        array $A
     */
    public function testIsDiagonal(array $D)
    {
        $D = MatrixFactory::create($D);

        $this->assertTrue($D->isDiagonal());
    }

    /**
     * @testCase     isDiagonal returns false for a non diagonal matrix
     * @dataProvider dataProviderForNotDiagonalMatrix
     * @param        array $L
     */
    public function testIsDiagonalForLowerTriangular(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isDiagonal());
    }

    /**
     * @testCase     isRef returns true for a matrix in row echelon form
     * @dataProvider dataProviderForRefMatrix
     * @param        array $A
     */
    public function testIsRef(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isRef());
    }

    /**
     * @testCase     isRef returns false for a matrix not in row echelon form
     * @dataProvider dataProviderForNotRefMatrix
     * @param        array $A
     */
    public function testIsNotRef(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isRef());
    }

    /**
     * @testCase     isRef returns true for a matrix in row echelon form
     * @dataProvider dataProviderForRrefMatrix
     * @param        array $A
     */
    public function testIsRref(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isRref());
    }

    /**
     * @testCase     isRef returns false for a matrix not in row echelon form
     * @dataProvider dataProviderForNotRefMatrix
     * @param        array $A
     */
    public function testIsNotRref(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isRref());
    }

    /**
     * @testCase     isRef returns false for a ref matrix
     * @dataProvider dataProviderForNotRrefMatrix
     * @param        array $A
     */
    public function testIsNotRrefForRefMatrix(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertFalse($A->isRref());
    }

    /**
     * @testCase     isInvolutory returns true for a Involutory matrix
     * @dataProvider dataProviderForInvolutoryMatrix
     * @param        array $A
     */
    public function testIsInvolutory(array $A)
    {
        $A = MatrixFactory::create($A);

        $this->assertTrue($A->isInvolutory());
    }
}
