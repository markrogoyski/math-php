<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Functions\Map;
use MathPHP\Functions\Support;
use MathPHP\Exception;

/**
 * m x n Matrix
 */
class Matrix implements \ArrayAccess, \JsonSerializable
{
    /** @var int Number of rows */
    protected $m;

    /** @var int Number of columns */
    protected $n;

    /** @var array Matrix array of arrays */
    protected $A;

    /** @var Matrix Row echelon form */
    protected $ref;

    /** @var Matrix Reduced row echelon form */
    protected $rref;

    /** @var int Number of row swaps when computing REF */
    protected $ref_swaps;

    /** @var number Determinant */
    protected $det;

    /** @var Matrix Inverse */
    protected $A⁻¹;

    /** @var Matrix Lower matrix in LUP decomposition */
    protected $L;

    /** @var Matrix Upper matrix in LUP decomposition */
    protected $U;

    /** @var Matrix Permutation matrix in LUP decomposition */
    protected $P;

    /**
     * Constructor
     * @param array $A of arrays $A m x n matrix
     *
     * @throws Exception\BadDataException if any rows have a different column count
     */
    public function __construct(array $A)
    {
        $this->A = $A;
        $this->m = count($A);
        $this->n = $this->m > 0 ? count($A[0]) : 0;

        foreach ($A as $i => $row) {
            if (count($row) !== $this->n) {
                throw new Exception\BadDataException("Row $i has a different column count: " . count($row) . "; was expecting {$this->n}.");
            }
        }
    }

    /**************************************************************************
     * BASIC MATRIX GETTERS
     *  - getMatrix
     *  - getM
     *  - getN
     *  - getRow
     *  - getColumn
     *  - get
     *  - getDiagonalElements
     *  - getSuperdiagonalElements
     *  - getSubdiagonalElements
     *  - asVectors
     **************************************************************************/

    /**
     * Get matrix
     * @return array of arrays
     */
    public function getMatrix(): array
    {
        return $this->A;
    }

    /**
     * Get row count (m)
     * @return int number of rows
     */
    public function getM(): int
    {
        return $this->m;
    }

    /**
     * Get column count (n)
     * @return int number of columns
     */
    public function getN(): int
    {
        return $this->n;
    }

    /**
     * Get single row from the matrix
     *
     * @param  int    $i row index (from 0 to m - 1)
     * @return array
     *
     * @throws Exception\MatrixException if row i does not exist
     */
    public function getRow(int $i): array
    {
        if ($i >= $this->m) {
            throw new Exception\MatrixException("Row $i does not exist");
        }

        return $this->A[$i];
    }

    /**
     * Get single column from the matrix
     *
     * @param  int   $j column index (from 0 to n - 1)
     * @return array
     *
     * @throws Exception\MatrixException if column j does not exist
     */
    public function getColumn(int $j): array
    {
        if ($j >= $this->n) {
            throw new Exception\MatrixException("Column $j does not exist");
        }

        return array_column($this->A, $j);
    }

    /**
     * Get a specific value at row i, column j
     *
     * @param  int    $i row index
     * @param  int    $j column index
     * @return number
     *
     * @throws Exception\MatrixException if row i or column j does not exist
     */
    public function get(int $i, int $j)
    {
        if ($i >= $this->m) {
            throw new Exception\MatrixException("Row $i does not exist");
        }
        if ($j >= $this->n) {
            throw new Exception\MatrixException("Column $j does not exist");
        }

        return $this->A[$i][$j];
    }

    /**
     * Returns the elements on the diagonal of a square matrix as an array
     *     [1 2 3]
     * A = [4 5 6]
     *     [7 8 9]
     *
     * getDiagonalElements($A) = [1, 5, 9]
     *
     * @return array
     */
    public function getDiagonalElements(): array
    {
        $diagonal = [];
        if ($this->isSquare()) {
            for ($i = 0; $i < $this->m; $i++) {
                $diagonal[] = $this->A[$i][$i];
            }
        }
        return $diagonal;
    }

    /**
     * Returns the elements on the superdiagonal of a square matrix as an array
     *     [1 2 3]
     * A = [4 5 6]
     *     [7 8 9]
     *
     * getSuperdiagonalElements($A) = [2, 6]
     *
     * http://mathworld.wolfram.com/Superdiagonal.html
     *
     * @return array
     */
    public function getSuperdiagonalElements(): array
    {
        $superdiagonal = [];
        if ($this->isSquare()) {
            for ($i = 0; $i < $this->m - 1; $i++) {
                $superdiagonal[] = $this->A[$i][$i+1];
            }
        }
        return $superdiagonal;
    }

    /**
     * Returns the elements on the subdiagonal of a square matrix as an array
     *     [1 2 3]
     * A = [4 5 6]
     *     [7 8 9]
     *
     * getSubdiagonalElements($A) = [4, 8]
     *
     * http://mathworld.wolfram.com/Subdiagonal.html
     *
     * @return array
     */
    public function getSubdiagonalElements(): array
    {
        $subdiagonal = [];
        if ($this->isSquare()) {
            for ($i = 1; $i < $this->m; $i++) {
                $subdiagonal[] = $this->A[$i][$i-1];
            }
        }
        return $subdiagonal;
    }

    /**
     * Returns an array of vectors from the columns of the matrix.
     * Each column of the matrix becomes a vector.
     *
     *     [1 2 3]
     * A = [4 5 6]
     *     [7 8 9]
     *
     *           [1] [2] [3]
     * Vectors = [4] [5] [6]
     *           [7] [8] [9]
     *
     * @return array of Vectors
     */
    public function asVectors(): array
    {
        $n       = $this->n;
        $vectors = [];

        for ($j = 0; $j < $n; $j++) {
            $vectors[] = new Vector(array_column($this->A, $j));
        }

        return $vectors;
    }

    /**************************************************************************
     * MATRIX PROPERTIES
     *  - isSquare
     *  - isSymmetric
     *  - isSingular
     *  - isNonsingular
     *  - isInvertible
     *  - isPositiveDefinite
     *  - isPositiveSemidefinite
     *  - isNegativeDefinite
     *  - isNegativeSemidefinite
     *  - isLowerTriangular
     *  - isUpperTriangular
     *  - isTriangular
     *  - isRef
     *  - isRref
     *  - isInvolutory
     *  - isSignature
     *  - isUpperBidiagonal
     *  - isLowerBidiagonal
     *  - isBidiagonal
     *  - isTridiagonal
     **************************************************************************/

    /**
     * Is the matrix a square matrix?
     * Do rows m = columns n?
     *
     * @return bool true if square; false otherwise.
     */
    public function isSquare(): bool
    {
        return $this->m === $this->n;
    }

    /**
     * Is the matrix symmetric?
     * Does A = Aᵀ
     *
     * @return bool true if symmetric; false otherwise.
     *
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     */
    public function isSymmetric(): bool
    {
        $A  = $this->A;
        $Aᵀ = $this->transpose()->getMatrix();

        return $A === $Aᵀ;
    }

    /**
     * Is the matrix skew-symmetric?
     * Does Aᵀ = −A
     *
     * @return bool true if skew-symmetric; false otherwise.
     *
     * @throws Exception\BadParameterException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     */
    public function isSkewSymmetric(): bool
    {
        $Aᵀ = $this->transpose()->getMatrix();
        $−A = $this->negate()->getMatrix();

        return $Aᵀ === $−A;
    }

    /**
     * Is the matrix singular?
     * A square matrix that does not have an inverse.
     * If the determinant is zero, then the matrix is singular.
     * http://mathworld.wolfram.com/SingularMatrix.html
     *
     * @return bool true if singular; false otherwise.
     *
     * @throws Exception\MatrixException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function isSingular(): bool
    {
        $│A│ = $this->det ?? $this->det();

        if ($│A│ == 0) {
            return true;
        }

        return false;
    }

    /**
     * Is the matrix nonsingular? (Regular matrix)
     * A square matrix that is not singular. It has an inverse.
     * If the determinant is nonzero, then the matrix is nonsingular.
     * http://mathworld.wolfram.com/NonsingularMatrix.html
     *
     * @return bool true if nonsingular; false otherwise.
     *
     * @throws Exception\MatrixException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function isNonsingular(): bool
    {
        $│A│ = $this->det ?? $this->det();

        if (Support::isNotZero($│A│)) {
            return true;
        }

        return false;
    }

    /**
     * Is the matrix invertible? (Regular nonsingular matrix)
     * Convenience method for isNonsingular.
     * https://en.wikipedia.org/wiki/Invertible_matrix
     * http://mathworld.wolfram.com/NonsingularMatrix.html
     *
     * @return bool true if invertible; false otherwise.
     *
     * @throws Exception\MatrixException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function isInvertible(): bool
    {
        return $this->isNonsingular();
    }

    /**
     * Is the matrix positive definite?
     *  - It is square and symmetric.
     *  - All principal minors have strictly positive determinants (> 0)
     *
     * Other facts:
     *  - All its eigenvalues are positive.
     *  - All its pivots are positive.
     *
     * https://en.wikipedia.org/wiki/Positive-definite_matrix
     * http://mathworld.wolfram.com/PositiveDefiniteMatrix.html
     * http://mat.gsia.cmu.edu/classes/QUANT/NOTES/chap1/node8.html
     * https://en.wikipedia.org/wiki/Sylvester%27s_criterion
     *
     * @return boolean true if positive definite; false otherwise
     *
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function isPositiveDefinite(): bool
    {
        if (!$this->isSquareAndSymmetric()) {
            return false;
        }

        for ($i = 1; $i <= $this->n; $i++) {
            if ($this->leadingPrincipalMinor($i)->det() <= 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Is the matrix positive semidefinite?
     *  - It is square and symmetric.
     *  - All principal minors have positive determinants (≥ 0)
     *
     * http://mathworld.wolfram.com/PositiveSemidefiniteMatrix.html
     *
     * @return boolean true if positive semidefinite; false otherwise
     *
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function isPositiveSemidefinite(): bool
    {
        if (!$this->isSquareAndSymmetric()) {
            return false;
        }

        for ($i = 1; $i <= $this->n; $i++) {
            if ($this->leadingPrincipalMinor($i)->det() < 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Is the matrix negative definite?
     *  - It is square and symmetric.
     *  - All principal minors have nonzero determinants and alternate in signs, starting with det(A₁) < 0
     *
     * http://mathworld.wolfram.com/NegativeDefiniteMatrix.html
     *
     * @return boolean true if negative definite; false otherwise
     *
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function isNegativeDefinite(): bool
    {
        if (!$this->isSquareAndSymmetric()) {
            return false;
        }

        for ($i = 1; $i <= $this->n; $i++) {
            switch ($i % 2) {
                case 1:
                    if ($this->leadingPrincipalMinor($i)->det() >= 0) {
                        return false;
                    }
                    break;
                case 0:
                    if ($this->leadingPrincipalMinor($i)->det() <= 0) {
                        return false;
                    }
                    break;
            }
        }

        return true;
    }

    /**
     * Is the matrix negative semidefinite?
     *  - It is square and symmetric.
     *  - All principal minors have determinants that alternate signs, starting with det(A₁) ≤ 0
     *
     * http://mathworld.wolfram.com/NegativeSemidefiniteMatrix.html
     *
     * @return boolean true if negative semidefinite; false otherwise
     *
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function isNegativeSemidefinite(): bool
    {
        if (!$this->isSquareAndSymmetric()) {
            return false;
        }

        for ($i = 1; $i <= $this->n; $i++) {
            switch ($i % 2) {
                case 1:
                    if ($this->leadingPrincipalMinor($i)->det() > 0) {
                        return false;
                    }
                    break;
                case 0:
                    if ($this->leadingPrincipalMinor($i)->det() < 0) {
                        return false;
                    }
                    break;
            }
        }

        return true;
    }

    /**
     * Is the matrix lower triangular?
     *  - It is a square matrix
     *  - All the entries above the main diagonal are zero
     *
     * https://en.wikipedia.org/wiki/Triangular_matrix
     *
     * @return boolean true if lower triangular; false otherwise
     */
    public function isLowerTriangular(): bool
    {
        if (!$this->isSquare()) {
            return false;
        }

        $m = $this->m;
        $n = $this->n;

        for ($i = 0; $i < $m; $i++) {
            for ($j = $i+1; $j < $n; $j++) {
                if ($this->A[$i][$j] != 0) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Is the matrix upper triangular?
     *  - It is a square matrix
     *  - All the entries below the main diagonal are zero
     *
     * https://en.wikipedia.org/wiki/Triangular_matrix
     *
     * @return boolean true if upper triangular; false otherwise
     */
    public function isUpperTriangular(): bool
    {
        if (!$this->isSquare()) {
            return false;
        }

        $m = $this->m;

        for ($i = 1; $i < $m; $i++) {
            for ($j = 0; $j < $i; $j++) {
                if ($this->A[$i][$j] != 0) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Is the matrix triangular?
     * The matrix is either lower or upper triangular
     *
     * https://en.wikipedia.org/wiki/Triangular_matrix
     *
     * @return boolean true if triangular; false otherwise
     */
    public function isTriangular(): bool
    {
        return ($this->isLowerTriangular() || $this->isUpperTriangular());
    }

    /**
     * Is the matrix diagonal?
     *  - It is a square matrix
     *  - All the entries above the main diagonal are zero
     *  - All the entries below the main diagonal are zero
     *
     * http://mathworld.wolfram.com/DiagonalMatrix.html
     *
     * @return boolean true if diagonal; false otherwise
     */
    public function isDiagonal(): bool
    {
        return ($this->isLowerTriangular() && $this->isUpperTriangular());
    }

    /**
     * Is the matrix in row echelon form?
     *  - All nonzero rows are above any rows of all zeroes
     *  - The leading coefficient of a nonzero row is always strictly to the right of the leading coefficient of the row above it.
     *
     * https://en.wikipedia.org/wiki/Row_echelon_form
     *
     * @return boolean true if matrix is in row echelon form; false otherwise
     */
    public function isRef(): bool
    {
        $m           = $this->m;
        $n           = $this->n;
        $zero_row_ok = true;

        // All nonzero rows are above any rows of all zeroes
        for ($i = $m - 1; $i >= 0; $i--) {
            $zero_row = count(array_filter(
                $this->A[$i],
                function ($x) {
                    return $x != 0;
                }
            )) === 0;

            if (!$zero_row) {
                $zero_row_ok = false;
                continue;
            }

            if ($zero_row && !$zero_row_ok) {
                return false;
            }
        }

        // Leading coefficients to the right of rows above it
        $lc = -1;
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($this->A[$i][$j] != 0) {
                    if ($j <= $lc) {
                        return false;
                    }
                    $lc = $j;
                    continue 2;
                }
            }
        }

        return true;
    }

    /**
     * Is the matrix in reduced row echelon form?
     *  - It is in row echelon form
     *  - Leading coefficients are 1
     *  - Leading coefficients are the only nonzero entry in its column
     *
     * https://en.wikipedia.org/wiki/Row_echelon_form
     *
     * @return boolean true if matrix is in reduced row echelon form; false otherwise
     *
     * @throws Exception\MatrixException
     */
    public function isRref(): bool
    {
        // Row echelon form
        if (!$this->isRef()) {
            return false;
        }

        $m   = $this->m;
        $n   = $this->n;
        $lcs = [];

        // Leading coefficients are 1
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($this->A[$i][$j] == 0) {
                    continue;
                }
                if ($this->A[$i][$j] != 1) {
                    return false;
                }
                $lcs[] = $j;
                continue 2;
            }
        }

        // Leading coefficients are the only nonzero entry in its column
        foreach ($lcs as $j) {
            $column  = $this->getColumn($j);
            $entries = array_filter($column);
            if (count($entries) !== 1) {
                return false;
            }
            $entry = array_shift($entries);
            if ($entry != 1) {
                return false;
            }
        }

        return true;
    }

    /**
     * Is the matrix involutory?
     * A matrix that is its own inverse. That is, multiplication by matrix A is an involution if and only if A² = I
     * https://en.wikipedia.org/wiki/Involutory_matrix
     *
     * @return boolean true if matrix is involutory; false otherwise
     *
     * @throws Exception\OutOfBoundsException
     * @throws Exception\MathException
     */
    public function isInvolutory(): bool
    {
        $I  = MatrixFactory::identity($this->m);
        $A² = $this->multiply($this);

        return $A²->getMatrix() == $I->getMatrix();
    }

    /**
     * Is the matrix a signature matrix?
     * A diagonal matrix whose diagonal elements are plus or minus 1.
     * https://en.wikipedia.org/wiki/Signature_matrix
     *
     *     | ±1  0  0 |
     * A = |  0 ±1  0 |
     *     |  0  0 ±1 |
     *
     * @return boolean true if matrix is a signature matrix; false otherwise
     */
    public function isSignature(): bool
    {
        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                if ($i == $j) {
                    if (!in_array($this->A[$i][$j], [-1, 1])) {
                        return false;
                    }
                } else {
                    if ($this->A[$i][$j] != 0) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Is the matrix upper bidiagonal?
     *  - It is a square matrix
     *  - Non-zero entries allowed along the main diagonal
     *  - Non-zero entries allowed along the diagonal above the main diagonal
     *  - All the other entries are zero
     *
     * https://en.wikipedia.org/wiki/Bidiagonal_matrix
     *
     * @return boolean true if upper bidiagonal; false otherwise
     */
    public function isUpperBidiagonal(): bool
    {
        if (!$this->isSquare() || !$this->isUpperTriangular()) {
            return false;
        }

        $m = $this->m;
        $n = $this->n;

        // Elements above upper diagonal are zero
        for ($i = 0; $i < $m; $i++) {
            for ($j = $i+2; $j < $n; $j++) {
                if ($this->A[$i][$j] != 0) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Is the matrix lower bidiagonal?
     *  - It is a square matrix
     *  - Non-zero entries allowed along the main diagonal
     *  - Non-zero entries allowed along the diagonal below the main diagonal
     *  - All the other entries are zero
     *
     * https://en.wikipedia.org/wiki/Bidiagonal_matrix
     *
     * @return boolean true if lower bidiagonal; false otherwise
     */
    public function isLowerBidiagonal(): bool
    {
        if (!$this->isSquare() || !$this->isLowerTriangular()) {
            return false;
        }

        // Elements below lower diagonal are non-zero
        for ($i = 2; $i < $this->m; $i++) {
            for ($j = 0; $j < $i-1; $j++) {
                if ($this->A[$i][$j] != 0) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Is the matrix bidiagonal?
     *  - It is a square matrix
     *  - Non-zero entries along the main diagonal
     *  - Non-zero entries along either the diagonal above or the diagonal below the main diagonal
     *  - All the other entries are zero
     *
     * https://en.wikipedia.org/wiki/Bidiagonal_matrix
     *
     * @return boolean true if bidiagonal; false otherwise
     */
    public function isBidiagonal(): bool
    {
        return ($this->isUpperBidiagonal() || $this->isLowerBidiagonal());
    }

    /**
     * Is the matrix tridiagonal?
     *  - It is a square matrix
     *  - Non-zero entries allowed along the main diagonal
     *  - Non-zero entries allowed along the diagonal above the main diagonal
     *  - Non-zero entries allowed along the diagonal below the main diagonal
     *  - All the other entries are zero
     *
     *  - Is both upper and lower Hessenberg
     *
     * https://en.wikipedia.org/wiki/Tridiagonal_matrix
     *
     * @return boolean true if tridiagonal; false otherwise
     */
    public function isTridiagonal(): bool
    {
        if (!$this->isSquare()) {
            return false;
        }

        if (!$this->isUpperHessenberg() || !$this->isLowerHessenberg()) {
            return false;
        }

        return true;
    }

    /**
     * Is the matrix upper Hessenberg?
     *  - It is a square matrix
     *  - Has zero entries below the first subdiagonal
     *
     * https://en.wikipedia.org/wiki/Hessenberg_matrix
     *
     * @return boolean true if upper Hessenberg; false otherwise
     */
    public function isUpperHessenberg(): bool
    {
        if (!$this->isSquare()) {
            return false;
        }

        // Elements below lower diagonal are zero
        for ($i = 2; $i < $this->m; $i++) {
            for ($j = 0; $j < $i-1; $j++) {
                if ($this->A[$i][$j] != 0) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Is the matrix lower Hessenberg?
     *  - It is a square matrix
     *  - Has zero entries above the first subdiagonal
     *
     * https://en.wikipedia.org/wiki/Hessenberg_matrix
     *
     * @return boolean true if lower Hessenberg; false otherwise
     */
    public function isLowerHessenberg(): bool
    {
        if (!$this->isSquare()) {
            return false;
        }

        // Elements above upper diagonal are zero
        for ($i = 0; $i < $this->m; $i++) {
            for ($j = $i+2; $j < $this->n; $j++) {
                if ($this->A[$i][$j] != 0) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Is the matrix square and symmetric
     *
     * @return boolean true if square and symmmetric; false otherwise
     *
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     */
    protected function isSquareAndSymmetric(): bool
    {
        return ($this->isSquare() && $this->isSymmetric());
    }

    /**************************************************************************
     * MATRIX OPERATIONS - Return a Matrix
     *  - add
     *  - directSum
     *  - kroneckerSum
     *  - subtract
     *  - multiply
     *  - scalarMultiply
     *  - scalarDivide
     *  - hadamardProduct
     *  - kroneckerProduct
     *  - transpose
     *  - trace
     *  - map
     *  - diagonal
     *  - augment
     *  - augmentIdentity
     *  - inverse
     *  - minorMatrix
     *  - cofactorMatrix
     *  - meanDeviation
     *  - covarianceMatrix
     *  - adjugate
     **************************************************************************/

    /**
     * Add two matrices - Entrywise sum
     * Adds each element of one matrix to the same element in the other matrix.
     * Returns a new matrix.
     * https://en.wikipedia.org/wiki/Matrix_addition#Entrywise_sum
     *
     * @param Matrix $B Matrix to add to this matrix
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if matrices have a different number of rows or columns
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     */
    public function add(Matrix $B): Matrix
    {
        if ($B->getM() !== $this->m) {
            throw new Exception\MatrixException('Matrices have different number of rows');
        }
        if ($B->getN() !== $this->n) {
            throw new Exception\MatrixException('Matrices have different number of columns');
        }

        $R = [];

        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                $R[$i][$j] = $this->A[$i][$j] + $B[$i][$j];
            }
        }

        return MatrixFactory::create($R);
    }

    /**
     * Direct sum of two matrices: A ⊕ B
     * The direct sum of any pair of matrices A of size m × n and B of size p × q
     * is a matrix of size (m + p) × (n + q)
     * https://en.wikipedia.org/wiki/Matrix_addition#Direct_sum
     *
     * @param  Matrix $B Matrix to add to this matrix
     *
     * @return Matrix
     *
     * @throws Exception\IncorrectTypeException
     */
    public function directSum(Matrix $B): Matrix
    {
        $m = $this->m + $B->getM();
        $n = $this->n + $B->getN();

        $R = [];

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i][$j] = 0;
            }
        }
        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                $R[$i][$j] = $this->A[$i][$j];
            }
        }

        $m = $B->getM();
        $n = $B->getN();
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i + $this->m][$j + $this->n] = $B[$i][$j];
            }
        }

        return MatrixFactory::create($R);
    }

    /**
     * Kronecker Sum (A⊕B)
     * A⊕B = A⊗Ib + I⊗aB
     * Where A and B are square matrices, Ia and Ib are identity matrixes,
     * and ⊗ is the Kronecker product.
     *
     * https://en.wikipedia.org/wiki/Matrix_addition#Kronecker_sum
     * http://mathworld.wolfram.com/KroneckerSum.html
     *
     * @param Matrix $B Square matrix
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if either matrix is not a square matrix
     * @throws Exception\OutOfBoundsException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadDataException
     */
    public function kroneckerSum(Matrix $B): Matrix
    {
        if (!$this->isSquare() || !$B->isSquare()) {
            throw new Exception\MatrixException('Matrices A and B must both be square for kroneckerSum');
        }

        $A  = $this;
        $m  = $B->getM();
        $n  = $this->n;

        $In = MatrixFactory::identity($n);
        $Im = MatrixFactory::identity($m);

        $A⊗Im = $A->kroneckerProduct($Im);
        $In⊗B = $In->kroneckerProduct($B);
        $A⊕B  = $A⊗Im->add($In⊗B);

        return $A⊕B;
    }

    /**
     * Subtract two matrices - Entrywise subtraction
     * Adds each element of one matrix to the same element in the other matrix.
     * Returns a new matrix.
     * https://en.wikipedia.org/wiki/Matrix_addition#Entrywise_sum
     *
     * @param Matrix $B Matrix to subtract from this matrix
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if matrices have a different number of rows or columns
     * @throws Exception\IncorrectTypeException
     */
    public function subtract(Matrix $B): Matrix
    {
        if ($B->getM() !== $this->m) {
            throw new Exception\MatrixException('Matrices have different number of rows');
        }
        if ($B->getN() !== $this->n) {
            throw new Exception\MatrixException('Matrices have different number of columns');
        }

        $R = [];

        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                $R[$i][$j] = $this->A[$i][$j] - $B[$i][$j];
            }
        }
        return MatrixFactory::create($R);
    }

    /**
     * Matrix multiplication
     * https://en.wikipedia.org/wiki/Matrix_multiplication#Matrix_product_.28two_matrices.29
     *
     * @param  Matrix/Vector $B Matrix or Vector to multiply
     *
     * @return Matrix
     *
     * @throws Exception\IncorrectTypeException if parameter B is not a Matrix or Vector
     * @throws Exception\MatrixException if matrix dimensions do not match
     * @throws Exception\VectorException
     */
    public function multiply($B): Matrix
    {
        if ((!$B instanceof Matrix) && (!$B instanceof Vector)) {
            throw new Exception\IncorrectTypeException('Can only do matrix multiplication with a Matrix or Vector');
        }
        if ($B instanceof Vector) {
            $B = $B->asColumnMatrix();
        }

        if ($B->getM() !== $this->n) {
            throw new Exception\MatrixException("Matrix dimensions do not match");
        }

        $n = $B->getN();
        $m = $this->m;
        $R = [];

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $VA        = new Vector($this->getRow($i));
                $VB        = new Vector($B->getColumn($j));
                $R[$i][$j] = $VA->dotProduct($VB);
            }
        }

        return MatrixFactory::create($R);
    }

    /**
     * Scalar matrix multiplication
     * https://en.wikipedia.org/wiki/Matrix_multiplication#Scalar_multiplication
     *
     * @param  number $λ
     *
     * @return Matrix
     *
     * @throws Exception\BadParameterException if λ is not a number
     * @throws Exception\IncorrectTypeException
     */
    public function scalarMultiply($λ): Matrix
    {
        if (!is_numeric($λ)) {
            throw new Exception\BadParameterException('Parameter λ is not a number');
        }

        $R = [];

        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                $R[$i][$j] = $this->A[$i][$j] * $λ;
            }
        }

        return MatrixFactory::create($R);
    }

    /**
     * Negate a matrix
     * −A = −1A
     *
     * @return Matrix
     *
     * @throws Exception\BadParameterException
     * @throws Exception\IncorrectTypeException
     */
    public function negate(): Matrix
    {
        return $this->scalarMultiply(-1);
    }

    /**
     * Scalar matrix division
     *
     * @param  number $λ
     *
     * @return Matrix
     *
     * @throws Exception\BadParameterException if λ is not a number
     * @throws Exception\BadParameterException if λ is 0
     * @throws Exception\IncorrectTypeException
     */
    public function scalarDivide($λ): Matrix
    {
        if (!is_numeric($λ)) {
            throw new Exception\BadParameterException('Parameter λ is not a number');
        }
        if ($λ == 0) {
            throw new Exception\BadParameterException('Parameter λ cannot equal 0');
        }

        $R = [];

        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                $R[$i][$j] = $this->A[$i][$j] / $λ;
            }
        }

        return MatrixFactory::create($R);
    }

    /**
     * Hadamard product (A∘B)
     * Also known as the Schur product, or the entrywise product
     *
     * A binary operation that takes two matrices of the same dimensions,
     * and produces another matrix where each element ij is the product of
     * elements ij of the original two matrices.
     * https://en.wikipedia.org/wiki/Hadamard_product_(matrices)
     *
     * (A∘B)ᵢⱼ = (A)ᵢⱼ(B)ᵢⱼ
     *
     * @param Matrix $B
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if matrices are not the same dimensions
     * @throws Exception\IncorrectTypeException
     */
    public function hadamardProduct(Matrix $B): Matrix
    {
        if ($B->getM() !== $this->m || $B->getN() !== $this->n) {
            throw new Exception\MatrixException('Matrices are not the same dimensions');
        }

        $m   = $this->m;
        $n   = $this->n;
        $A   = $this->A;
        $B   = $B->getMatrix();
        $A∘B = [];

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $A∘B[$i][$j] = $A[$i][$j] * $B[$i][$j];
            }
        }

        return MatrixFactory::create($A∘B);
    }

    /**
     * Kronecker product (A⊗B)
     *
     * If A is an m × n matrix and B is a p × q matrix,
     * then the Kronecker product A ⊗ B is the mp × nq block matrix:
     *
     *       [a₁₁b₁₁ a₁₁b₁₂ ⋯ a₁₁b₁q ⋯ ⋯ a₁nb₁₁ a₁nb₁₂ ⋯ a₁nb₁q]
     *       [a₁₁b₂₁ a₁₁b₂₂ ⋯ a₁₁b₂q ⋯ ⋯ a₁nb₂₁ a₁nb₂₂ ⋯ a₁nb₂q]
     *       [  ⋮       ⋮    ⋱  ⋮           ⋮      ⋮    ⋱   ⋮   ]
     *       [a₁₁bp₁ a₁₁bp₂ ⋯ a₁₁bpq ⋯ ⋯ a₁nbp₁ a₁nbp₂ ⋯ a₁nbpq]
     * A⊗B = [  ⋮       ⋮       ⋮     ⋱     ⋮      ⋮        ⋮   ]
     *       [  ⋮       ⋮       ⋮       ⋱   ⋮      ⋮        ⋮   ]
     *       [am₁b₁₁ am₁b₁₂ ⋯ am₁b₁q ⋯ ⋯ amnb₁₁ amnb₁₂ ⋯ amnb₁q]
     *       [am₁b₂₁ am₁b₂₂ ⋯ am₁b₂q ⋯ ⋯ amnb₂₁ amnb₂₂ ⋯ amnb₂q]
     *       [  ⋮       ⋮    ⋱  ⋮           ⋮      ⋮    ⋱   ⋮   ]
     *       [am₁bp₁ am₁bp₂ ⋯ am₁bpq ⋯ ⋯ amnbp₁ amnbp₂ ⋯ amnbpq]
     *
     * https://en.wikipedia.org/wiki/Kronecker_product
     *
     * @param Matrix $B
     *
     * @return Matrix
     *
     * @throws Exception\BadDataException
     */
    public function kroneckerProduct(Matrix $B): Matrix
    {
        // Compute each element of the block matrix
        $arrays = [];
        for ($m = 0; $m < $this->m; $m++) {
            $row = [];
            for ($n = 0; $n < $this->n; $n++) {
                $R = [];
                for ($p = 0; $p < $B->getM(); $p++) {
                    for ($q = 0; $q < $B->getN(); $q++) {
                        $R[$p][$q] = $this->A[$m][$n] * $B[$p][$q];
                    }
                }
                $row[] = new Matrix($R);
            }
            $arrays[] = $row;
        }

        // Augment each aᵢ₁ to aᵢn block
        $matrices = [];
        foreach ($arrays as $row) {
            $initial_matrix = array_shift($row);
            $matrices[] = array_reduce(
                $row,
                function (Matrix $augmented_matrix, Matrix $matrix) {
                    return $augmented_matrix->augment($matrix);
                },
                $initial_matrix
            );
        }

        // Augment below each row block a₁ to am
        $initial_matrix = array_shift($matrices);
        $A⊗B            = array_reduce(
            $matrices,
            function (Matrix $augmented_matrix, Matrix $matrix) {
                return $augmented_matrix->augmentBelow($matrix);
            },
            $initial_matrix
        );

        return $A⊗B;
    }

    /**
     * Transpose matrix
     *
     * The transpose of a matrix A is another matrix Aᵀ:
     *  - reflect A over its main diagonal (which runs from top-left to bottom-right) to obtain AT
     *  - write the rows of A as the columns of AT
     *  - write the columns of A as the rows of AT
     * Formally, the i th row, j th column element of Aᵀ is the j th row, i th column element of A.
     * If A is an m × n matrix then Aᵀ is an n × m matrix.
     * https://en.wikipedia.org/wiki/Transpose
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException
     * @throws Exception\IncorrectTypeException
     */
    public function transpose()
    {
        $Aᵀ = [];
        for ($i = 0; $i < $this->n; $i++) {
            $Aᵀ[$i] = $this->getColumn($i);
        }

        return MatrixFactory::create($Aᵀ);
    }

    /**
     * Trace
     * the trace of an n-by-n square matrix A is defined to be
     * the sum of the elements on the main diagonal
     * (the diagonal from the upper left to the lower right).
     * https://en.wikipedia.org/wiki/Trace_(linear_algebra)
     *
     * tr(A) = a₁₁ + a₂₂ + ... ann
     *
     * @return number
     *
     * @throws Exception\MatrixException if the matrix is not a square matrix
     */
    public function trace()
    {
        if (!$this->isSquare()) {
            throw new Exception\MatrixException('trace only works on a square matrix');
        }

        $m    = $this->m;
        $tr⟮A⟯ = 0;

        for ($i = 0; $i < $m; $i++) {
            $tr⟮A⟯ += $this->A[$i][$i];
        }

        return $tr⟮A⟯;
    }

    /**
     * Map a function over all elements of the Matrix
     *
     * @param  \Callable $func takes a matrix item as input
     *
     * @return Matrix
     *
     * @throws Exception\IncorrectTypeException
     */
    public function map(callable $func): Matrix
    {
        $m = $this->m;
        $n = $this->n;
        $R = [];

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i][$j] = $func($this->A[$i][$j]);
            }
        }

        return MatrixFactory::create($R);
    }

    /**
     * Diagonal matrix
     * Retains the elements along the main diagonal.
     * All other off-diagonal elements are zeros.
     *
     * @return Matrix
     *
     * @throws Exception\IncorrectTypeException
     */
    public function diagonal(): Matrix
    {
        $m = $this->m;
        $n = $this->n;
        $R = [];

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i][$j] = ($i == $j) ? $this->A[$i][$j] : 0;
            }
        }

        return MatrixFactory::create($R);
    }

    /**
     * Augment a matrix
     * An augmented matrix is a matrix obtained by appending the columns of two given matrices
     *
     *     [1, 2, 3]
     * A = [2, 3, 4]
     *     [3, 4, 5]
     *
     *     [4]
     * B = [5]
     *     [6]
     *
     *         [1, 2, 3 | 4]
     * (A|B) = [2, 3, 4 | 5]
     *         [3, 4, 5 | 6]
     *
     * @param  Matrix $B Matrix columns to add to matrix A
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if matrices do not have the same number of rows
     * @throws Exception\IncorrectTypeException
     */
    public function augment(Matrix $B): Matrix
    {
        if ($B->getM() !== $this->m) {
            throw new Exception\MatrixException('Matrices to augment do not have the same number of rows');
        }

        $m    = $this->m;
        $A    = $this->A;
        $B    = $B->getMatrix();
        $⟮A∣B⟯ = [];

        for ($i = 0; $i < $m; $i++) {
            $⟮A∣B⟯[$i] = array_merge($A[$i], $B[$i]);
        }

        return MatrixFactory::create($⟮A∣B⟯);
    }

    /**
     * Augment a matrix with its identity matrix
     *
     *     [1, 2, 3]
     * C = [2, 3, 4]
     *     [3, 4, 5]
     *
     *         [1, 2, 3 | 1, 0, 0]
     * (C|I) = [2, 3, 4 | 0, 1, 0]
     *         [3, 4, 5 | 0, 0, 1]
     *
     * C must be a square matrix
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if matrix is not square
     * @throws Exception\IncorrectTypeException
     * @throws Exception\OutOfBoundsException
     */
    public function augmentIdentity(): Matrix
    {
        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Matrix is not square; cannot augment with the identity matrix');
        }

        return $this->augment(MatrixFactory::identity($this->getM()));
    }

    /**
     * Augment a matrix from below
     * An augmented matrix is a matrix obtained by appending the rows of two given matrices
     *
     *     [1, 2, 3]
     * A = [2, 3, 4]
     *     [3, 4, 5]
     *
     * B = [4, 5, 6]
     *
     *         [1, 2, 3]
     * (A_B) = [2, 3, 4]
     *         [3, 4, 5]
     *         [4, 5, 6]
     *
     * @param  Matrix $B Matrix rows to add to matrix A
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if matrices do not have the same number of columns
     * @throws Exception\IncorrectTypeException
     */
    public function augmentBelow(Matrix $B): Matrix
    {
        if ($B->getN() !== $this->n) {
            throw new Exception\MatrixException('Matrices to augment do not have the same number of columns');
        }

        $⟮A∣B⟯ = array_merge($this->A, $B->getMatrix());

        return MatrixFactory::create($⟮A∣B⟯);
    }

    /**
     * Inverse
     *
     * For a 2x2 matrix:
     *      [a b]
     *  A = [c d]
     *
     *         1
     *  A⁻¹ = --- [d -b]
     *        │A│ [-c a]
     *
     * For a 3x3 matrix or larger:
     * Augment with identity matrix and calculate reduced row echelon form.
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if not a square matrix
     * @throws Exception\MatrixException if singular matrix
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     * @throws Exception\OutOfBoundsException
     */
    public function inverse(): Matrix
    {
        if (isset($this->A⁻¹)) {
            return $this->A⁻¹;
        }

        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Not a sqaure matrix (required for determinant)');
        }
        if ($this->isSingular()) {
            throw new Exception\MatrixException('Singular matrix (determinant = 0); not invertible');
        }

        $m   = $this->m;
        $n   = $this->n;
        $A   = $this->A;
        $│A│ = $this->det ?? $this->det();

        /*
         * 2x2 matrix:
         *      [a b]
         *  A = [c d]
         *
         *        1
         * A⁻¹ = --- [d -b]
         *       │A│ [-c a]
         */
        if ($m === 2) {
            $a = $A[0][0];
            $b = $A[0][1];
            $c = $A[1][0];
            $d = $A[1][1];

            $R = MatrixFactory::create([
                [$d, -$b],
                [-$c, $a],
            ]);
            $A⁻¹ = $R->scalarMultiply(1/$│A│);

            $this->A⁻¹ = $A⁻¹;
            return $A⁻¹;
        }

        /*
         * nxn matrix 3x3 or larger
         */
        $R   = $this->augmentIdentity()->rref();
        $A⁻¹ = [];

        for ($i = 0; $i < $n; $i++) {
            $A⁻¹[$i] = array_slice($R[$i], $n);
        }

        $A⁻¹ = MatrixFactory::create($A⁻¹);

        $this->A⁻¹ = $A⁻¹;
        return $A⁻¹;
    }

    /**
     * Minor matrix
     * Submatrix formed by deleting the iᵗʰ row and jᵗʰ column.
     * Used in computing the minor Mᵢⱼ.
     *
     * @param int $mᵢ Row to exclude
     * @param int $nⱼ Column to exclude
     *
     * @return Matrix with row mᵢ and column nⱼ removed
     *
     * @throws Exception\MatrixException if matrix is not square
     * @throws Exception\MatrixException if row to exclude for minor matrix does not exist
     * @throws Exception\MatrixException if column to exclude for minor matrix does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function minorMatrix(int $mᵢ, int $nⱼ): Matrix
    {
        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Matrix is not square; cannot get minor Matrix of a non-square matrix');
        }
        if ($mᵢ >= $this->m || $mᵢ < 0) {
            throw new Exception\MatrixException('Row to exclude for minor Matrix does not exist');
        }
        if ($nⱼ >= $this->n || $nⱼ < 0) {
            throw new Exception\MatrixException('Column to exclude for minor Matrix does not exist');
        }

        return $this->rowExclude($mᵢ)->columnExclude($nⱼ);
    }

    /**
     * Leading principal minor
     * The leading principal minor of A of order k is the minor of order k
     * obtained by deleting the last n − k rows and columns.
     *
     * Example:
     *
     *     [1 2 3]
     * A = [4 5 6]
     *     [7 8 9]
     *
     * 1st order (k = 1): [1]
     *
     *                    [1 2]
     * 2nd order (k = 2): [4 5]
     *
     *                    [1 2 3]
     * 3rd order (k = 3): [4 5 6]
     *                    [7 8 9]
     *
     * @param  int $k Order of the leading principal minor
     *
     * @return Matrix
     *
     * @throws Exception\OutOfBoundsException if k ≤ 0
     * @throws Exception\OutOfBoundsException if k > n
     * @throws Exception\MatrixException if matrix is not square
     * @throws Exception\IncorrectTypeException
     */
    public function leadingPrincipalMinor(int $k): Matrix
    {
        if ($k <= 0) {
            throw new Exception\OutOfBoundsException("k is ≤ 0: $k");
        }
        if ($k > $this->n) {
            throw new Exception\OutOfBoundsException("k ($k) leading principal minor is larger than size of Matrix: " . $this->n);
        }
        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Matrix is not square; cannot get leading principal minor Matrix of a non-square matrix');
        }

        $R = [];
        for ($i = 0; $i < $k; $i++) {
            for ($j = 0; $j < $k; $j++) {
                $R[$i][$j] = $this->A[$i][$j];
            }
        }

        return MatrixFactory::create($R);
    }

    /**
     * Cofactor matrix
     * A matrix where each element is a cofactor.
     *
     *     [A₀₀ A₀₁ A₀₂]
     * A = [A₁₀ A₁₁ A₁₂]
     *     [A₂₀ A₂₁ A₂₂]
     *
     *      [C₀₀ C₀₁ C₀₂]
     * CM = [C₁₀ C₁₁ C₁₂]
     *      [C₂₀ C₂₁ C₂₂]
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if matrix is not square
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function cofactorMatrix(): Matrix
    {
        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Matrix is not square; cannot get cofactor Matrix of a non-square matrix');
        }
        if ($this->n === 1) {
            throw new Exception\MatrixException('Matrix must be 2x2 or greater to compute cofactorMatrix');
        }

        $m = $this->m;
        $n = $this->n;
        $R = [];

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i][$j] = $this->cofactor($i, $j);
            }
        }

        return MatrixFactory::create($R);
    }

    /**
     * Mean deviation matrix
     * Matrix as an array of column vectors, each subtracted by the sample mean.
     *
     * Example:
     *      [1  4 7 8]      [5]
     *  A = [2  2 8 4]  M = [4]
     *      [1 13 1 5]      [5]
     *
     *      |[1] - [5]   [4]  - [5]   [7] - [5]   [8] - [5]|
     *  B = |[2] - [4]   [2]  - [4]   [8] - [4]   [4] - [4]|
     *      |[1] - [5]   [13] - [5]   [1] - [5]   [5] - [5]|
     *
     *      [-4 -1  2 3]
     *  B = [-2 -2  4 0]
     *      [-2  8 -4 0]
     *
     * @return Matrix
     *
     * @throws Exception\IncorrectTypeException
     */
    public function meanDeviation(): Matrix
    {
        $X = $this->asVectors();
        $M = $this->sampleMean();

        $B = array_map(
            function (Vector $Xᵢ) use ($M) {
                return $Xᵢ->subtract($M);
            },
            $X
        );

        return MatrixFactory::create($B);
    }

    /**
     * Covariance matrix (variance-covariance matrix, sample covariance matrix)
     * https://en.wikipedia.org/wiki/Covariance_matrix
     * https://en.wikipedia.org/wiki/Sample_mean_and_covariance
     *
     *       1
     * S = ----- BBᵀ
     *     N - 1
     *
     *  where B is the mean-deviation form
     *
     * Example:
     *     [var₁  cov₁₂ cov₁₃]
     * S = [cov₁₂ var₂  cov₂₃]
     *     [cov₁₃ cov₂₃ var₃]
     *
     * Uses mathematical convention where matrix columns represent observation vectors.
     * Follows formula and method found in Linear Algebra and Its Applications (Lay).
     *
     * @return Matrix
     *
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     * @throws Exception\BadParameterException
     * @throws Exception\VectorException
     */
    public function covarianceMatrix(): Matrix
    {
        $n  = $this->n;
        $B  = $this->meanDeviation();
        $Bᵀ = $B->transpose();

        $S = $B->multiply($Bᵀ)->scalarMultiply((1 / ($n - 1)));

        return $S;
    }

    /**
     * Adjugate matrix (adjoint, adjunct)
     * The transpose of its cofactor matrix.
     * https://en.wikipedia.org/wiki/Adjugate_matrix
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException is matrix is not square
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function adjugate(): Matrix
    {
        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Matrix is not square; cannot get adjugate Matrix of a non-square matrix');
        }

        if ($this->n === 1) {
            return MatrixFactory::create([[1]]);
        }

        $adj⟮A⟯ = $this->cofactorMatrix()->transpose();

        return $adj⟮A⟯;
    }

    /**************************************************************************
     * MATRIX OPERATIONS - Return a Vector
     *  - vectorMultiply
     *  - sampleMean
     **************************************************************************/

    /**
     * Matrix multiplication by a vector
     * m x n matrix multiplied by a 1 x n vector resulting in a new vector.
     * https://en.wikipedia.org/wiki/Matrix_multiplication#Square_matrix_and_column_vector
     *
     * @param  Vector $B Vector to multiply
     *
     * @return Vector
     *
     * @throws Exception\MatrixException if dimensions do not match
     */
    public function vectorMultiply(Vector $B): Vector
    {
        $B = $B->getVector();
        $n = count($B);
        $m = $this->m;

        if ($n !== $this->n) {
            throw new Exception\MatrixException("Matrix and vector dimensions do not match");
        }

        $R = [];
        for ($i = 0; $i < $m; $i++) {
            $R[$i] = array_sum(Map\Multi::multiply($this->getRow($i), $B));
        }

        return new Vector($R);
    }

    /**
     * Sample mean of multivariate matrix
     * https://en.wikipedia.org/wiki/Sample_mean_and_covariance
     *
     *     1
     * M = - (X₁ + X₂ + ⋯ + Xn)
     *     N
     *
     * Example:
     *      [1  4 7 8]
     *  A = [2  2 8 4]
     *      [1 13 1 5]
     *
     *  Consider each column of observations as a column vector:
     *        [1]       [4]        [7]       [8]
     *   X₁ = [2]  X₂ = [2]   X₃ = [8]  X₄ = [4]
     *        [1]       [13]       [1]       [5]
     *
     *    1  /[1]   [4]    [7]   [8]\     1 [20]   [5]
     *    - | [2] + [2]  + [8] + [4] |  = - [16] = [4]
     *    4  \[1]   [13]   [1]   [5]/     4 [20]   [5]
     *
     * @return Vector
     */
    public function sampleMean(): Vector
    {
        $m = $this->m;
        $n = $this->n;

        $M = array_reduce(
            $this->asVectors(),
            function (Vector $carryV, Vector $V) {
                return $carryV->add($V);
            },
            new Vector(array_fill(0, $m, 0))
        );

        return $M->scalarDivide($n);
    }

    /**************************************************************************
     * MATRIX OPERATIONS - Return a value
     *  - oneNorm
     *  - frobeniusNorm
     *  - infinityNorm
     *  - maxNorm
     *  - det
     *  - minor
     *  - cofactor
     *  - rank
     **************************************************************************/

    /**
     * 1-norm (‖A‖₁)
     * Maximum absolute column sum of the matrix
     *
     * @return number
     */
    public function oneNorm()
    {
        $n = $this->n;
        $‖A‖₁ = array_sum(Map\Single::abs(array_column($this->A, 0)));

        for ($j = 1; $j < $n; $j++) {
            $‖A‖₁ = max($‖A‖₁, array_sum(Map\Single::abs(array_column($this->A, $j))));
        }

        return $‖A‖₁;
    }

    /**
     * Frobenius norm (Hilbert–Schmidt norm, Euclidean norm) (‖A‖F)
     * Square root of the sum of the square of all elements.
     *
     * https://en.wikipedia.org/wiki/Matrix_norm#Frobenius_norm
     *
     *          _____________
     *         /ᵐ   ⁿ
     * ‖A‖F = √ Σ   Σ  |aᵢⱼ|²
     *         ᵢ₌₁ ᵢ₌₁
     *
     * @return number
     */
    public function frobeniusNorm()
    {
        $m      = $this->m;
        $n      = $this->n;
        $ΣΣaᵢⱼ² = 0;

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $ΣΣaᵢⱼ² += ($this->A[$i][$j])**2;
            }
        }

        return sqrt($ΣΣaᵢⱼ²);
    }

    /**
     * Infinity norm (‖A‖∞)
     * Maximum absolute row sum of the matrix
     *
     * @return number
     */
    public function infinityNorm()
    {
        $m = $this->m;
        $‖A‖∞ = array_sum(Map\Single::abs($this->A[0]));

        for ($i = 1; $i < $m; $i++) {
            $‖A‖∞ = max($‖A‖∞, array_sum(Map\Single::abs($this->A[$i])));
        }

        return $‖A‖∞;
    }

    /**
     * Max norm (‖A‖max)
     * Elementwise max
     *
     * @return number
     */
    public function maxNorm()
    {
        $m   = $this->m;
        $n   = $this->n;
        $max = abs($this->A[0][0]);

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $max = max($max, abs($this->A[$i][$j]));
            }
        }

        return $max;
    }

    /**
     * Determinant
     *
     * For a 1x1 matrix:
     *  A = [a]
     *
     * |A| = a
     *
     * For a 2x2 matrix:
     *      [a b]
     *  A = [c d]
     *
     * │A│ = ad - bc
     *
     * For a 3x3 matrix:
     *      [a b c]
     *  A = [d e f]
     *      [g h i]
     *
     * │A│ = a(ei - fh) - b(di - fg) + c(dh - eg)
     *
     * For 4x4 and larger matrices:
     *
     * │A│ = (-1)ⁿ │ref(A)│
     *
     *  where:
     *   │ref(A)│ = determinant of the row echelon form of A
     *   ⁿ        = number of row swaps when computing REF
     *
     * @return number
     *
     * @throws Exception\MatrixException if matrix is not square
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function det()
    {
        if (isset($this->det)) {
            return $this->det;
        }

        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Not a sqaure matrix (required for determinant)');
        }

        $m = $this->m;
        $R = MatrixFactory::create($this->A);

        /*
         * 1x1 matrix
         *  A = [a]
         *
         * |A| = a
         */
        if ($m === 1) {
            $this->det = $R[0][0];
            return $this->det;
        }

        /*
         * 2x2 matrix
         *      [a b]
         *  A = [c d]
         *
         * |A| = ad - bc
         */
        if ($m === 2) {
            $a = $R[0][0];
            $b = $R[0][1];
            $c = $R[1][0];
            $d = $R[1][1];

            $ad = $a * $d;
            $bc = $b * $c;

            $this->det = $ad - $bc;
            return $this->det;
        }

        /*
         * 3x3 matrix
         *      [a b c]
         *  A = [d e f]
         *      [g h i]
         *
         * |A| = a(ei - fh) - b(di - fg) + c(dh - eg)
         */
        if ($m === 3) {
            $a = $R[0][0];
            $b = $R[0][1];
            $c = $R[0][2];
            $d = $R[1][0];
            $e = $R[1][1];
            $f = $R[1][2];
            $g = $R[2][0];
            $h = $R[2][1];
            $i = $R[2][2];

            $ei = $e * $i;
            $fh = $f * $h;
            $di = $d * $i;
            $fg = $f * $g;
            $dh = $d * $h;
            $eg = $e * $g;

            $this->det = $a * ($ei - $fh) - $b * ($di - $fg) + $c * ($dh - $eg);
            return $this->det;
        }

        /*
         * nxn matrix 4x4 or larger
         * Get row echelon form, then compute determinant of ref.
         * Then plug into formula with swaps.
         * │A│ = (-1)ⁿ │ref(A)│
         */
        $ref⟮A⟯ = $this->ref ?? $this->ref();
        $ⁿ     = $this->ref_swaps;

        // Det(ref(A))
        $│ref⟮A⟯│ = 1;
        for ($i = 0; $i < $m; $i++) {
            $│ref⟮A⟯│ *= $ref⟮A⟯[$i][$i];
        }

        // │A│ = (-1)ⁿ │ref(A)│
        $this->det = (-1)**$ⁿ * $│ref⟮A⟯│;
        return $this->det;
    }

    /**
     * Minor (first minor)
     * The determinant of some smaller square matrix, cut down from A by removing one of its rows and columns.
     *
     *        [1 4  7]
     * If A = [3 0  5]
     *        [1 9 11]
     *
     *                [1 4 -]       [1 4]
     * Then M₁₂ = det [- - -] = det [1 9] = 13
     *                [1 9 -]
     *
     * https://en.wikipedia.org/wiki/Minor_(linear_algebra)
     *
     * @param int $mᵢ Row to exclude
     * @param int $nⱼ Column to exclude
     *
     * @return number
     *
     * @throws Exception\MatrixException if matrix is not square
     * @throws Exception\MatrixException if row to exclude for minor does not exist
     * @throws Exception\MatrixException if column to exclude for minor does not exist
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function minor(int $mᵢ, int $nⱼ)
    {
        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Matrix is not square; cannot get minor of a non-square matrix');
        }
        if ($mᵢ >= $this->m || $mᵢ < 0) {
            throw new Exception\MatrixException('Row to exclude for minor does not exist');
        }
        if ($nⱼ >= $this->n || $nⱼ < 0) {
            throw new Exception\MatrixException('Column to exclude for minor does not exist');
        }

        return $this->minorMatrix($mᵢ, $nⱼ)->det();
    }

    /**
     * Cofactor
     * Multiply the minor by (-1)ⁱ⁺ʲ.
     *
     * Cᵢⱼ = (-1)ⁱ⁺ʲMᵢⱼ
     *
     * Example:
     *        [1 4  7]
     * If A = [3 0  5]
     *        [1 9 11]
     *
     *                [1 4 -]       [1 4]
     * Then M₁₂ = det [- - -] = det [1 9] = 13
     *                [1 9 -]
     *
     * Therefore C₁₂ = (-1)¹⁺²(13) = -13
     *
     * https://en.wikipedia.org/wiki/Minor_(linear_algebra)
     *
     * @param int $mᵢ Row to exclude
     * @param int $nⱼ Column to exclude
     *
     * @return number
     *
     * @throws Exception\MatrixException if matrix is not square
     * @throws Exception\MatrixException if row to exclude for cofactor does not exist
     * @throws Exception\MatrixException if column to exclude for cofactor does not exist
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function cofactor(int $mᵢ, int $nⱼ)
    {
        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Matrix is not square; cannot get cofactor of a non-square matrix');
        }
        if ($mᵢ >= $this->m || $mᵢ < 0) {
            throw new Exception\MatrixException('Row to exclude for cofactor does not exist');
        }
        if ($nⱼ >= $this->n || $nⱼ < 0) {
            throw new Exception\MatrixException('Column to exclude for cofactor does not exist');
        }

        $Mᵢⱼ    = $this->minor($mᵢ, $nⱼ);
        $⟮−1⟯ⁱ⁺ʲ = (-1)**($mᵢ + $nⱼ);

        return $⟮−1⟯ⁱ⁺ʲ * $Mᵢⱼ;
    }

    /**
     * Rank of a matrix
     * Computed by counting number of pivots once in reduced row echelon form
     * https://en.wikipedia.org/wiki/Rank_(linear_algebra)
     *
     * @return int
     *
     * @throws Exception\BadParameterException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     */
    public function rank(): int
    {
        $rref   = $this->rref();
        $pivots = 0;

        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                if (Support::isNotZero($rref[$i][$j])) {
                    $pivots++;
                    continue 2;
                }
            }
        }

        return $pivots;
    }

    /**************************************************************************
     * ROW OPERATIONS - Return a Matrix
     *  - rowInterchange
     *  - rowMultiply
     *  - rowDivide
     *  - rowAdd
     *  - rowAddScalar
     *  - rowSubtract
     *  - rowSubtractScalar
     *  - rowExclude
     **************************************************************************/

    /**
     * Interchange two rows
     *
     * Row mᵢ changes to position mⱼ
     * Row mⱼ changes to position mᵢ
     *
     * @param int $mᵢ Row to swap into row position mⱼ
     * @param int $mⱼ Row to swap into row position mᵢ
     *
     * @return Matrix with rows mᵢ and mⱼ interchanged
     *
     * @throws Exception\MatrixException if row to interchange does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function rowInterchange(int $mᵢ, int $mⱼ): Matrix
    {
        if ($mᵢ >= $this->m || $mⱼ >= $this->m) {
            throw new Exception\MatrixException('Row to interchange does not exist');
        }

        $m = $this->m;
        $R = [];

        for ($i = 0; $i < $m; $i++) {
            switch ($i) {
                case $mᵢ:
                    $R[$i] = $this->A[$mⱼ];
                    break;
                case $mⱼ:
                    $R[$i] = $this->A[$mᵢ];
                    break;
                default:
                    $R[$i] = $this->A[$i];
            }
        }

        return MatrixFactory::create($R);
    }

    /**
     * Multiply a row by a factor k
     *
     * Each element of Row mᵢ will be multiplied by k
     *
     * @param int $mᵢ Row to multiply
     * @param int $k Multiplier
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if row to multiply does not exist
     * @throws Exception\BadParameterException if k is 0
     * @throws Exception\IncorrectTypeException
     */
    public function rowMultiply(int $mᵢ, int $k): Matrix
    {
        if ($mᵢ >= $this->m) {
            throw new Exception\MatrixException('Row to multiply does not exist');
        }
        if ($k == 0) {
            throw new Exception\BadParameterException('Multiplication factor k must not be 0');
        }

        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mᵢ][$j] *= $k;
        }

        return MatrixFactory::create($R);
    }

    /**
     * Divide a row by a divisor k
     *
     * Each element of Row mᵢ will be divided by k
     *
     * @param int $mᵢ Row to multiply
     * @param int $k divisor
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if row to multiply does not exist
     * @throws Exception\BadParameterException if k is 0
     * @throws Exception\IncorrectTypeException
     */
    public function rowDivide(int $mᵢ, $k): Matrix
    {
        if ($mᵢ >= $this->m) {
            throw new Exception\MatrixException('Row to multiply does not exist');
        }
        if ($k == 0) {
            throw new Exception\BadParameterException('Divisor k must not be 0');
        }

        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mᵢ][$j] /= $k;
        }

        return MatrixFactory::create($R);
    }

    /**
     * Add k times row mᵢ to row mⱼ
     *
     * @param int $mᵢ Row to multiply * k to be added to row mⱼ
     * @param int $mⱼ Row that will have row mⱼ * k added to it
     * @param number $k Multiplier
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if row to add does not exist
     * @throws Exception\BadParameterException if k is 0
     * @throws Exception\IncorrectTypeException
     */
    public function rowAdd(int $mᵢ, int $mⱼ, $k): Matrix
    {
        if ($mᵢ >= $this->m || $mⱼ >= $this->m) {
            throw new Exception\MatrixException('Row to add does not exist');
        }
        if ($k == 0) {
            throw new Exception\BadParameterException('Multiplication factor k must not be 0');
        }

        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mⱼ][$j] += $R[$mᵢ][$j] * $k;
        }

        return MatrixFactory::create($R);
    }

    /**
     * Add a scalar k to each item of a row
     *
     * Each element of Row mᵢ will have k added to it
     *
     * @param int $mᵢ Row to add k to
     * @param int $k scalar
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if row to add does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function rowAddScalar(int $mᵢ, $k): Matrix
    {
        if ($mᵢ >= $this->m) {
            throw new Exception\MatrixException('Row to add does not exist');
        }

        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mᵢ][$j] += $k;
        }

        return MatrixFactory::create($R);
    }

    /**
     * Subtract k times row mᵢ to row mⱼ
     *
     * @param int $mᵢ Row to multiply * k to be subtracted to row mⱼ
     * @param int $mⱼ Row that will have row mⱼ * k subtracted to it
     * @param number $k Multiplier
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if row to subtract does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function rowSubtract(int $mᵢ, int $mⱼ, $k): Matrix
    {
        if ($mᵢ >= $this->m || $mⱼ >= $this->m) {
            throw new Exception\MatrixException('Row to subtract does not exist');
        }


        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mⱼ][$j] -= $R[$mᵢ][$j] * $k;
        }

        return MatrixFactory::create($R);
    }

    /**
     * Subtract a scalar k to each item of a row
     *
     * Each element of Row mᵢ will have k subtracted from it
     *
     * @param int $mᵢ Row to add k to
     * @param int $k scalar
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if row to subtract does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function rowSubtractScalar(int $mᵢ, int $k): Matrix
    {
        if ($mᵢ >= $this->m) {
            throw new Exception\MatrixException('Row to subtract does not exist');
        }

        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mᵢ][$j] -= $k;
        }

        return MatrixFactory::create($R);
    }

    /**
     * Exclude a row from the result matrix
     *
     * @param int $mᵢ Row to exclude
     *
     * @return Matrix with row mᵢ excluded
     *
     * @throws Exception\MatrixException if row to exclude does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function rowExclude(int $mᵢ): Matrix
    {
        if ($mᵢ >= $this->m || $mᵢ < 0) {
            throw new Exception\MatrixException('Row to exclude does not exist');
        }

        $m = $this->m;
        $R = [];

        for ($i = 0; $i < $m; $i++) {
            if ($i === $mᵢ) {
                continue;
            }
            $R[$i] = $this->A[$i];
        }

        return MatrixFactory::create(array_values($R));
    }

    /**************************************************************************
     * COLUMN OPERATIONS - Return a Matrix
     *  - columnInterchange
     *  - columnMultiply
     *  - columnAdd
     *  - columnExclude
     **************************************************************************/

    /**
     * Interchange two columns
     *
     * Column nᵢ changes to position nⱼ
     * Column nⱼ changes to position nᵢ
     *
     * @param int $nᵢ Column to swap into column position nⱼ
     * @param int $nⱼ Column to swap into column position nᵢ
     *
     * @return Matrix with columns nᵢ and nⱼ interchanged
     *
     * @throws Exception\MatrixException if column to interchange does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function columnInterchange(int $nᵢ, int $nⱼ): Matrix
    {
        if ($nᵢ >= $this->n || $nⱼ >= $this->n) {
            throw new Exception\MatrixException('Column to interchange does not exist');
        }

        $m = $this->m;
        $n = $this->n;
        $R = [];

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                switch ($j) {
                    case $nᵢ:
                        $R[$i][$j] = $this->A[$i][$nⱼ];
                        break;
                    case $nⱼ:
                        $R[$i][$j] = $this->A[$i][$nᵢ];
                        break;
                    default:
                        $R[$i][$j] = $this->A[$i][$j];
                }
            }
        }

        return MatrixFactory::create($R);
    }

    /**
     * Multiply a column by a factor k
     *
     * Each element of column nᵢ will be multiplied by k
     *
     * @param int $nᵢ Column to multiply
     * @param int $k Multiplier
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if column to multiply does not exist
     * @throws Exception\BadParameterException if k is 0
     * @throws Exception\IncorrectTypeException
     */
    public function columnMultiply(int $nᵢ, int $k): Matrix
    {
        if ($nᵢ >= $this->n) {
            throw new Exception\MatrixException('Column to multiply does not exist');
        }
        if ($k == 0) {
            throw new Exception\BadParameterException('Multiplication factor k must not be 0');
        }

        $m = $this->m;
        $R = $this->A;

        for ($i = 0; $i < $m; $i++) {
            $R[$i][$nᵢ] *= $k;
        }

        return MatrixFactory::create($R);
    }

    /**
     * Add k times column nᵢ to column nⱼ
     *
     * @param int $nᵢ Column to multiply * k to be added to column nⱼ
     * @param int $nⱼ Column that will have column nⱼ * k added to it
     * @param int $k Multiplier
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if column to add does not exist
     * @throws Exception\BadParameterException if k is 0
     * @throws Exception\IncorrectTypeException
     */
    public function columnAdd(int $nᵢ, int $nⱼ, int $k): Matrix
    {
        if ($nᵢ >= $this->n || $nⱼ >= $this->n) {
            throw new Exception\MatrixException('Column to add does not exist');
        }
        if ($k == 0) {
            throw new Exception\BadParameterException('Multiplication factor k must not be 0');
        }

        $m = $this->m;
        $R = $this->A;

        for ($i = 0; $i < $m; $i++) {
            $R[$i][$nⱼ] += $R[$i][$nᵢ] * $k;
        }

        return MatrixFactory::create($R);
    }

    /**
     * Exclude a column from the result matrix
     *
     * @param int $nᵢ Column to exclude
     *
     * @return Matrix with column nᵢ excluded
     *
     * @throws Exception\MatrixException if column to exclude does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function columnExclude(int $nᵢ): Matrix
    {
        if ($nᵢ >= $this->n || $nᵢ < 0) {
            throw new Exception\MatrixException('Column to exclude does not exist');
        }

        $m = $this->m;
        $n = $this->n;
        $R = [];

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($j === $nᵢ) {
                    continue;
                }
                $R[$i][$j] = $this->A[$i][$j];
            }
        }

        // Reset column indexes
        for ($i = 0; $i < $m; $i++) {
            $R[$i] = array_values($R[$i]);
        }

        return MatrixFactory::create($R);
    }

    /**************************************************************************
     * MATRIX DECOMPOSITIONS - Return a Matrix (or array of Matrices)
     *  - ref (row echelon form)
     *  - rref (reduced row echelon form)
     *  - LU decomposition
     *  - Cholesky decomposition
     **************************************************************************/

    /**
     * Row echelon form
     *
     * First tries Guassian elimination.
     * If that fails (singular matrix), uses custom row reduction algorithm
     *
     * @return Matrix in row echelon form
     *
     * @throws Exception\BadParameterException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     */
    public function ref(): Matrix
    {
        if (isset($this->ref)) {
            return $this->ref;
        }

        try {
            $R = $this->gaussianElimination();
        } catch (Exception\SingularMatrixException $e) {
            $R = $this->rowReductionToEchelonForm();
        }

        $this->ref = MatrixFactory::create($R);
        return $this->ref;
    }

    /**
     * Gaussian elimination - row echelon form
     *
     * Algorithm
     *  for k = 1 ... min(m,n):
     *    Find the k-th pivot:
     *    i_max  := argmax (i = k ... m, abs(A[i, k]))
     *    if A[i_max, k] = 0
     *      error "Matrix is singular!"
     *    swap rows(k, i_max)
     *    Do for all rows below pivot:
     *    for i = k + 1 ... m:
     *      f := A[i, k] / A[k, k]
     *      Do for all remaining elements in current row:
     *      for j = k + 1 ... n:
     *        A[i, j]  := A[i, j] - A[k, j] * f
     *      Fill lower triangular matrix with zeros:
     *      A[i, k]  := 0
     *
     * https://en.wikipedia.org/wiki/Gaussian_elimination
     *
     * @return array - matrix in row echelon form
     *
     * @throws Exception\SingularMatrixException if the matrix is singular
     */
    protected function gaussianElimination(): array
    {
        $m     = $this->m;
        $n     = $this->n;
        $size  = min($m, $n);
        $R     = $this->A;
        $swaps = 0;

        for ($k = 0; $k < $size; $k++) {
            // Find column max
            $i_max = $k;
            for ($i = $k; $i < $m; $i++) {
                if (abs($R[$i][$k]) > abs($R[$i_max][$k])) {
                    $i_max = $i;
                }
            }

            if ($R[$i_max][$k] == 0) {
                throw new Exception\SingularMatrixException('Guassian elimination fails for singular matrices');
            }

            // Swap rows k and i_max (column max)
            if ($k != $i_max) {
                list($R[$k], $R[$i_max]) = [$R[$i_max], $R[$k]];
                $swaps++;
            }

            // Row operations
            for ($i = $k + 1; $i < $m; $i++) {
                $f = ($R[$k][$k] != 0) ? $R[$i][$k] / $R[$k][$k] : 1;
                for ($j = $k + 1; $j < $n; $j++) {
                    $R[$i][$j] = $R[$i][$j] - ($R[$k][$j] * $f);
                }
                $R[$i][$k] = 0;
            }
        }

        $this->ref_swaps = $swaps;
        return $R;
    }

    /**
     * Reduce a matrix to row echelon form using basic row operations
     * Custom MathPHP classic row reduction using basic matrix operations.
     *
     * Algorithm:
     *   (1) Find pivot
     *     (a) If pivot column is 0, look down the column to find a non-zero pivot and swap rows
     *     (b) If no non-zero pivot in the column, go to the next column of the same row and repeat (1)
     *   (2) Scale pivot row so pivot is 1 by using row division
     *   (3) Eliminate elements below pivot (make 0 using row addition of the pivot row * a scaling factor)
     *       so there are no non-zero elements in the pivot column in rows below the pivot
     *   (4) Repeat from 1 from the next row and column
     *
     *   (Extra) Keep track of number of row swaps (used for computing determinant)
     *
     * @return array - matrix in row echelon form
     *
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     * @throws Exception\BadParameterException
     */
    protected function rowReductionToEchelonForm(): array
    {
        $m    = $this->m;
        $n    = $this->n;
        $R    = MatrixFactory::create($this->A);

        // Starting conditions
        $row   = 0;
        $col   = 0;
        $swaps = 0;
        $ref   = false;

        while (!$ref) {
            // If pivot is 0, try to find a non-zero pivot in the column and swap rows
            if (Support::isZero($R[$row][$col])) {
                for ($j = $row + 1; $j < $m; $j++) {
                    if (Support::isNotZero($R[$j][$col])) {
                        $R = $R->rowInterchange($row, $j);
                        $swaps++;
                        break;
                    }
                }
            }

            // No non-zero pivot, go to next column of the same row
            if (Support::isZero($R[$row][$col])) {
                $col++;
                if ($row >= $m || $col >= $n) {
                    $ref = true;
                }
                continue;
            }

            // Scale pivot to 1
            $divisor = $R[$row][$col];
            $R = $R->rowDivide($row, $divisor);

            // Eliminate elements below pivot
            for ($j = $row + 1; $j < $m; $j++) {
                $factor = $R[$j][$col];
                if (Support::isNotZero($factor)) {
                    $R = $R->rowAdd($row, $j, -$factor);
                }
            }

            // Move on to next row and column
            $row++;
            $col++;

            // If no more rows or columns, ref achieved
            if ($row >= $m || $col >= $n) {
                $ref = true;
            }
        }

        $R = $R->getMatrix();

        // Floating point adjustment for zero values
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if (Support::isZero($R[$i][$j])) {
                    $R[$i][$j] = 0;
                }
            }
        }

        $this->ref_swaps = $swaps;
        return $R;
    }

    /**
     * Ruduced row echelon form (row canonical form)
     *
     * Algorithm:
     *   (1) Reduce to REF
     *   (2) Find pivot
     *     (b) If no non-zero pivot in the column, go to the next column of the same row and repeat (2)
     *   (2) Scale pivot row so pivot is 1 by using row division
     *   (3) Eliminate elements above pivot (make 0 using row addition of the pivot row * a scaling factor)
     *       so there are no non-zero elements in the pivot column in rows above the pivot
     *   (4) Repeat from 2 from the next row and column
     *
     * @return Matrix in reduced row echelon form
     *
     * @throws Exception\BadParameterException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     */
    public function rref(): Matrix
    {
        if (isset($this->rref)) {
            return $this->rref;
        }

        $m = $this->m;
        $n = $this->n;
        $R = $this->ref();

        // Starting conditions
        $row   = 0;
        $col   = 0;
        $rref = false;

        while (!$rref) {
            // No non-zero pivot, go to next column of the same row
            if (Support::isZero($R[$row][$col])) {
                $col++;
                if ($row >= $m || $col >= $n) {
                    $rref = true;
                }
                continue;
            }

            // Scale pivot to 1
            if ($R[$row][$col] != 1) {
                $divisor = $R[$row][$col];
                $R = $R->rowDivide($row, $divisor);
            }

            // Eliminate elements above pivot
            for ($j = $row - 1; $j >= 0; $j--) {
                $factor = $R[$j][$col];
                if (Support::isNotZero($factor)) {
                    $R = $R->rowAdd($row, $j, -$factor);
                }
            }

            // Move on to next row and column
            $row++;
            $col++;

            // If no more rows or columns, rref achieved
            if ($row >= $m || $col >= $n) {
                $rref = true;
            }
        }

        $R = $R->getMatrix();

        // Floating point adjustment for zero values
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if (Support::isZero($R[$i][$j])) {
                    $R[$i][$j] = 0;
                }
            }
        }

        $this->rref = MatrixFactory::create($R);
        return $this->rref;
    }

    /**
     * LU Decomposition (Doolittle decomposition) with pivoting via permutation matrix
     *
     * A matrix has an LU-factorization if it can be expressed as the product of a
     * lower-triangular matrix L and an upper-triangular matrix U. If A is a nonsingular
     * matrix, then we can find a permutation matrix P so that PA will have an LU decomposition:
     *   PA = LU
     *
     * https://en.wikipedia.org/wiki/LU_decomposition
     * https://en.wikipedia.org/wiki/LU_decomposition#Doolittle_algorithm
     *
     * L: Lower triangular matrix--all entries above the main diagonal are zero.
     *    The main diagonal will be all ones.
     * U: Upper tirangular matrix--all entries below the main diagonal are zero.
     * P: Permutation matrix--Identity matrix with possible rows interchanged.
     *
     * Example:
     *      [1 3 5]
     *  A = [2 4 7]
     *      [1 1 0]
     *
     * Create permutation matrix P:
     *      [0 1 0]
     *  P = [1 0 1]
     *      [0 0 1]
     *
     * Pivot A to be PA:
     *       [0 1 0][1 3 5]   [2 4 7]
     *  PA = [1 0 1][2 4 7] = [1 3 5]
     *       [0 0 1][1 1 0]   [1 1 0]
     *
     * Calculate L and U
     *
     *     [1    0 0]      [2 4   7]
     * L = [0.5  1 0]  U = [0 1 1.5]
     *     [0.5 -1 1]      [0 0  -2]
     *
     * @return array [
     *   L: Lower triangular matrix
     *   U: Upper triangular matrix
     *   P: Permutation matrix
     * ]
     *
     * @throws Exception\MatrixException if matrix is not square
     * @throws Exception\IncorrectTypeException
     * @throws Exception\OutOfBoundsException
     * @throws Exception\VectorException
     */
    public function luDecomposition(): array
    {
        if (!$this->isSquare()) {
            throw new Exception\MatrixException('LU decomposition only works on square matrices');
        }

        $n = $this->n;

        // Initialize L as diagonal ones matrix, and U as zero matrix
        $L = (new DiagonalMatrix(array_fill(0, $n, 1)))->getMatrix();
        $U = MatrixFactory::zero($n, $n)->getMatrix();

        // Create permutation matrix P and pivoted PA
        $P  = $this->pivotize();
        $PA = $P->multiply($this);

        // Fill out L and U
        for ($i = 0; $i < $n; $i++) {
            // Calculate Uⱼᵢ
            for ($j = 0; $j <= $i; $j++) {
                $sum = 0;
                for ($k = 0; $k < $j; $k++) {
                    $sum += $U[$k][$i] * $L[$j][$k];
                }
                $U[$j][$i] = $PA[$j][$i] - $sum;
            }

            // Calculate Lⱼᵢ
            for ($j = $i; $j < $n; $j++) {
                $sum = 0;
                for ($k = 0; $k < $i; $k++) {
                    $sum += $U[$k][$i] * $L[$j][$k];
                }
                $L[$j][$i] = ($U[$i][$i] == 0) ? \NAN : ($PA[$j][$i] - $sum) / $U[$i][$i];
            }
        }

        // Assemble return array items: [L, U, P, A]
        $this->L = MatrixFactory::create($L);
        $this->U = MatrixFactory::create($U);
        $this->P = $P;

        return [
            'L' => $this->L,
            'U' => $this->U,
            'P' => $this->P,
        ];
    }

    /**
     * Pivotize creates the permutation matrix P for the LU decomposition.
     * The permutation matrix is an identity matrix with rows possibly interchanged.
     *
     * The product PA results in a new matrix whose rows consist of the rows of A
     * but no rearranged in the order specified by the permutation matrix P.
     *
     * Example:
     *
     *     [α₁₁ α₁₂ α₁₃]
     * A = [α₂₁ α₂₂ α₂₃]
     *     [α₃₁ α₃₂ α₃₃]
     *
     *     [0 1 0]
     * P = [1 0 0]
     *     [0 0 1]
     *
     *      [α₂₁ α₂₂ α₂₃] \ rows
     * PA = [α₁₁ α₁₂ α₁₃] / interchanged
     *      [α₃₁ α₃₂ α₃₃]
     *
     * @return Matrix
     *
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException
     */
    protected function pivotize(): Matrix
    {
        $n = $this->n;
        $P = MatrixFactory::identity($n);
        $A = $this->A;

        // Set initial column max to diagonal element Aᵢᵢ
        for ($i = 0; $i < $n; $i++) {
            $max = $A[$i][$i];
            $row = $i;

            // Check for column element below Aᵢᵢ that is bigger
            for ($j = $i; $j < $n; $j++) {
                if ($A[$j][$i] > $max) {
                    $max = $A[$j][$i];
                    $row = $j;
                }
            }

            // Swap rows if a larger column element below Aᵢᵢ was found
            if ($i != $row) {
                $P = $P->rowInterchange($i, $row);
            }
        }

        return $P;
    }

    /**
     * Cholesky decomposition
     * A decomposition of a square, positive definitive matrix
     * into the product of a lower triangular matrix and its transpose.
     *
     * https://en.wikipedia.org/wiki/Cholesky_decomposition
     *
     * A = LLᵀ
     *
     *     [a₁₁ a₁₂ a₁₃]
     * A = [a₂₁ a₂₂ a₂₃]
     *     [a₃₁ a₃₂ a₃₃]
     *
     *     [l₁₁  0   0 ] [l₁₁ l₁₂ l₁₃]
     * A = [l₂₁ l₂₂  0 ] [ 0  l₂₂ l₂₃] ≡ LLᵀ
     *     [l₃₁ l₃₂ l₃₃] [ 0   0  l₃₃]
     *
     * Diagonal elements
     *          ____________
     *         /     ᵢ₋₁
     * lᵢᵢ =  / aᵢᵢ - ∑l²ᵢₓ
     *       √       ˣ⁼¹
     *
     * Elements below diagonal
     *
     *        1   /      ᵢ₋₁     \
     * lⱼᵢ = --- |  aⱼᵢ - ∑lⱼₓlᵢₓ |
     *       lᵢᵢ  \      ˣ⁼¹     /
     *
     * @return Matrix Lower triangular matrix L of A = LLᵀ
     *
     * @throws Exception\MatrixException if the matrix is not positive definite
     * @throws Exception\OutOfBoundsException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function choleskyDecomposition(): Matrix
    {
        if (!$this->isPositiveDefinite()) {
            throw new Exception\MatrixException('Matrix must be positive definite for Cholesky decomposition');
        }

        $m = $this->m;
        $L = MatrixFactory::zero($m, $m)->getMatrix();

        for ($j = 0; $j < $m; $j++) {
            for ($i = 0; $i < ($j+1); $i++) {
                $∑lⱼₓlᵢₓ = 0;
                for ($x = 0; $x < $i; $x++) {
                    $∑lⱼₓlᵢₓ += $L[$j][$x] * $L[$i][$x];
                }
                $L[$j][$i] = ($j === $i)
                    ? sqrt($this->A[$j][$j] - $∑lⱼₓlᵢₓ)
                    : (1 / $L[$i][$i] * ($this->A[$j][$i] - $∑lⱼₓlᵢₓ));
            }
        }

        return MatrixFactory::create($L);
    }

    /**
     * Crout decomposition
     * An LU decomposition which decomposes a matrix into a lower triangular matrix (L), an upper triangular matrix (U).
     * https://en.wikipedia.org/wiki/Crout_matrix_decomposition
     *
     * A = LU where L = LD
     * A = (LD)U
     *  - L = lower triangular matrix
     *  - D = diagonal matrix
     *  - U = normalised upper triangular matrix
     *
     * @return array [
     *   L: Lower triangular/diagonal matrix
     *   U: Normalised upper triangular matrix
     * ]
     *
     * @throws Exception\MatrixException if there is division by 0 because of a 0-value determinant
     * @throws Exception\OutOfBoundsException
     * @throws Exception\IncorrectTypeException
     */
    public function croutDecomposition(): array
    {
        $m   = $this->m;
        $n   = $this->n;
        $A   = $this->A;
        $U   = MatrixFactory::identity($n)->getMatrix();
        $L   = MatrixFactory::zero($m, $n)->getMatrix();

        for ($j = 0; $j < $n; $j++) {
            for ($i = $j; $i < $n; $i++) {
                $sum = 0;
                for ($k = 0; $k < $j; $k++) {
                    $sum = $sum + $L[$i][$k] * $U[$k][$j];
                }
                $L[$i][$j] = $A[$i][$j] - $sum;
            }

            for ($i = $j; $i < $n; $i++) {
                $sum = 0;
                for ($k = 0; $k < $j; $k++) {
                    $sum = $sum + $L[$j][$k] * $U[$k][$i];
                }
                if ($L[$j][$j] == 0) {
                    throw new Exception\MatrixException('Cannot do Crout decomposition. det(L) close to 0 - Cannot divide by 0');
                }
                $U[$j][$i] = ($A[$j][$i] - $sum) / $L[$j][$j];
            }
        }

        return [
            'L' => MatrixFactory::create($L),
            'U' => MatrixFactory::create($U),
        ];
    }

    /**************************************************************************
     * SOLVE LINEAR SYSTEM OF EQUATIONS
     * - solve
     **************************************************************************/

    /**
     * Solve linear system of equations
     * Ax = b
     *  where:
     *   A: Matrix
     *   x: unknown to solve for
     *   b: solution to linear system of equations (input to function)
     *
     * If A is nxn invertible matrix,
     * and the inverse is already computed:
     *  x = A⁻¹b
     *
     * If 2x2, just take the inverse and solve:
     *  x = A⁻¹b
     *
     * If 3x3 or higher, check if the RREF is already computed,
     * and if so, then just take the inverse and solve:
     *   x = A⁻¹b
     *
     * Otherwise, it is more efficient to decompose and then solve.
     * Use LU Decomposition and solve Ax = b.
     *
     * LU Decomposition:
     *  - Equation to solve: Ax = b
     *  - LU Decomposition produces: PA = LU
     *  - Substitute: LUx = Pb, or Pb = LUx
     *  - Can rewrite as Pb = L(Ux)
     *  - Can say y = Ux
     *  - Then can rewrite as Pb = Ly
     *  - Solve for y (we know Pb and L)
     *  - Solve for x in y = Ux once we know y
     *
     * Solving triangular systems Ly = Pb and Ux = y
     *  - Solve for Ly = Pb using forward substitution
     *
     *         1   /    ᵢ₋₁      \
     *   yᵢ = --- | bᵢ - ∑ Lᵢⱼyⱼ |
     *        Lᵢᵢ  \    ʲ⁼¹      /
     *
     *  - Solve for Ux = y using back substitution
     *
     *         1   /     m       \
     *   xᵢ = --- | yᵢ - ∑ Uᵢⱼxⱼ |
     *        Uᵢᵢ  \   ʲ⁼ⁱ⁺¹     /
     *
     * @param Vector/array $b solution to Ax = b
     *
     * @return Vector x
     *
     * @throws Exception\IncorrectTypeException if b is not a Vector or array
     * @throws Exception\MatrixException
     * @throws Exception\VectorException
     * @throws Exception\OutOfBoundsException
     * @throws Exception\BadParameterException
     */
    public function solve($b)
    {
        // Input must be a Vector or array.
        if (!($b instanceof Vector || is_array($b))) {
            throw new Exception\IncorrectTypeException('b in Ax = b must be a Vector or array');
        }
        if (is_array($b)) {
            $b = new Vector($b);
        }

        // If inverse is already calculated, solve: x = A⁻¹b
        if (isset($this->A⁻¹)) {
            return new Vector($this->A⁻¹->multiply($b)->getColumn(0));
        }

        // If 2x2, just compute the inverse and solve: x = A⁻¹b
        if ($this->m === 2 && $this->n === 2) {
            $this->inverse();
            return new Vector($this->A⁻¹->multiply($b)->getColumn(0));
        }

        // For 3x3 or higher, check if the RREF is already computed.
        // If so, just compute the inverse and solve: x = A⁻¹b
        if (isset($this->rref)) {
            $this->inverse();
            return new Vector($this->A⁻¹->multiply($b)->getColumn(0));
        }

        // No inverse or RREF pre-computed.
        // Use LU Decomposition.
        $this->luDecomposition();
        $L = $this->L;
        $U = $this->U;
        $P = $this->P;
        $m = $this->m;

        // Pivot solution vector b with permutation matrix: Pb
        $Pb = $P->multiply($b);

        /* Solve for Ly = Pb using forward substitution
         *         1   /    ᵢ₋₁      \
         *   yᵢ = --- | bᵢ - ∑ Lᵢⱼyⱼ |
         *        Lᵢᵢ  \    ʲ⁼¹      /
         */
        $y    = [];
        $y[0] = $Pb[0][0] / $L[0][0];
        for ($i = 1; $i < $m; $i++) {
            $sum = 0;
            for ($j = 0; $j <= $i - 1; $j++) {
                $sum += $L[$i][$j] * $y[$j];
            }
            $y[$i] = ($Pb[$i][0] - $sum) / $L[$i][$i];
        }

        /* Solve for Ux = y using back substitution
         *         1   /     m       \
         *   xᵢ = --- | yᵢ - ∑ Uᵢⱼxⱼ |
         *        Uᵢᵢ  \   ʲ⁼ⁱ⁺¹     /
         */
        $x         = [];
        $x[$m - 1] = $y[$m - 1] / $U[$m - 1][$m - 1];
        for ($i = $m - 2; $i >= 0; $i--) {
            $sum = 0;
            for ($j = $i + 1; $j < $m; $j++) {
                $sum += $U[$i][$j] * $x[$j];
            }
            $x[$i] = ($y[$i] - $sum) / $U[$i][$i];
        }

        // Return unknown xs as Vector
        return new Vector(array_reverse($x));
    }

    /**************************************************************************
     * EIGEN METHODS
     * - eigenvalues
     * - eigenvectors
     **************************************************************************/

    /**
     * Eigenvalues of the matrix.
     * Various eigenvalue algorithms (methods) are available.
     * Use the $method parameter to control the algorithm used.
     *
     * @param string $method Algorithm used to compute the eigenvalues
     *
     * @return array of eigenvalues
     *
     * @throws Exception\MatrixException if method is not a valid eigenvalue method
     */
    public function eigenvalues(string $method = Eigenvalue::CLOSED_FORM_POLYNOMIAL_ROOT_METHOD): array
    {
        if (!Eigenvalue::isAvailableMethod($method)) {
            throw new Exception\MatrixException("$method is not a valid eigenvalue method");
        }

        return Eigenvalue::$method($this);
    }

    /**
     * Eigenvectors of the matrix.
     * Eigenvector computation function takes in an array of eigenvalues as input.
     * Various eigenvalue algorithms (methods) are availbale.
     * Use the $method parameter to control the algorithm used.
     *
     * @param string $method Algorithm used to compute the eigenvalues
     *
     * @return Matrix of eigenvectors
     *
     * @throws Exception\MatrixException if method is not a valid eigenvalue method
     * @throws Exception\BadDataException
     */
    public function eigenvectors(string $method = Eigenvalue::CLOSED_FORM_POLYNOMIAL_ROOT_METHOD): Matrix
    {
        if (!Eigenvalue::isAvailableMethod($method)) {
            throw new Exception\MatrixException("$method is not a valid eigenvalue method");
        }

        return Eigenvector::eigenvectors($this, Eigenvalue::$method($this));
    }

    /**************************************************************************
     * PHP MAGIC METHODS
     *  - __toString
     **************************************************************************/

    /**
     * Print the matrix as a string
     * Format is as a matrix, not as the underlying array structure.
     * Ex:
     *  [1, 2, 3]
     *  [2, 3, 4]
     *  [3, 4, 5]
     *
     * @return string
     */
    public function __toString()
    {
        return trim(array_reduce(array_map(
            function ($mᵢ) {
                return '[' . implode(', ', $mᵢ) . ']';
            },
            $this->A
        ), function ($A, $mᵢ) {
            return $A . \PHP_EOL . $mᵢ;
        }));
    }

    /**************************************************************************
     * ArrayAccess INTERFACE
     **************************************************************************/

    /**
     * @param mixed $i
     * @return bool
     */
    public function offsetExists($i): bool
    {
        return isset($this->A[$i]);
    }

    /**
     * @param mixed $i
     * @return mixed
     */
    public function offsetGet($i)
    {
        return $this->A[$i];
    }

    /**
     * @param  mixed $i
     * @param  mixed $value
     * @throws Exception\MatrixException
     */
    public function offsetSet($i, $value)
    {
        throw new Exception\MatrixException('Matrix class does not allow setting values');
    }

    /**
     * @param  mixed $i
     * @throws Exception\MatrixException
     */
    public function offsetUnset($i)
    {
        throw new Exception\MatrixException('Matrix class does not allow unsetting values');
    }

    /**************************************************************************
     * JsonSerializable INTERFACE
     **************************************************************************/

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->A;
    }

    public function getObjectType()
    {
    }
}
