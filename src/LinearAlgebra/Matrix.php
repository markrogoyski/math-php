<?php

namespace MathPHP\LinearAlgebra;

use MathPHP\Functions\Map;
use MathPHP\Functions\Support;
use MathPHP\Exception;
use MathPHP\LinearAlgebra\Reduction;

/**
 * m x n Matrix
 */
class Matrix implements \ArrayAccess, \JsonSerializable
{
    /** @var int Number of rows */
    protected $m;

    /** @var int Number of columns */
    protected $n;

    /** @var array[] Matrix data as array of arrays */
    protected $A;

    /** @var MatrixCatalog */
    protected $catalog;

    /** @var float Error/zero tolerance */
    protected $ε;

    // Default error/zero tolerance
    const ε = 0.00000000001;

    /**
     * Constructor
     *
     * @param array[] $A of arrays $A m x n matrix
     *
     * @throws Exception\BadDataException if any rows have a different column count
     */
    public function __construct(array $A)
    {
        $this->A       = $A;
        $this->m       = count($A);
        $this->n       = $this->m > 0 ? count($A[0]) : 0;
        $this->ε       = self::ε;
        $this->catalog = new MatrixCatalog();

        $this->validateMatrixDimensions();
    }

    /**
     * Validate the matrix is entirely m x n
     *
     * @throws Exception\BadDataException
     */
    protected function validateMatrixDimensions()
    {
        foreach ($this->A as $i => $row) {
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
     * @return array[] of arrays
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
     * Get error / zero tolerance
     * @return float
     */
    public function getError(): float
    {
        return $this->ε;
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
        for ($i = 0; $i < min($this->m, $this->n); $i++) {
            $diagonal[] = $this->A[$i][$i];
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
                $superdiagonal[] = $this->A[$i][$i + 1];
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
                $subdiagonal[] = $this->A[$i][$i - 1];
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

    /***************************************************************************
     * SETTERS
     *  - setError
     **************************************************************************/

    /**
     * Set the error/zero tolerance for matrix values
     *  - Used to determine tolerance for equality
     *  - Used to determine if a value is zero
     *
     * @param float $ε
     */
    public function setError(float $ε)
    {
        $this->ε = $ε;
    }

    /***************************************************************************
     * MATRIX COMPARISONS
     *  - isEqual
     ***************************************************************************/

    /**
     * Is this matrix equal to some other matrix?
     *
     * @param Matrix $B
     *
     * @return bool
     */
    public function isEqual(Matrix $B): bool
    {
        $m = $this->m;
        $n = $this->n;
        $ε = $this->ε;

        // Same dimensions
        if ($m != $B->m || $n != $B->n) {
            return false;
        }

        // All elements are the same
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if (Support::isNotEqual($this->A[$i][$j], $B[$i][$j], $ε)) {
                    return false;
                }
            }
        }

        return true;
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
     *  - isIdempotent
     *  - isInvolutory
     *  - isSignature
     *  - isUpperBidiagonal
     *  - isLowerBidiagonal
     *  - isBidiagonal
     *  - isTridiagonal
     *  - isUpperHessenberg
     *  - isLowerHessenberg
     *  - isOrthogonal
     *  - isNormal
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
     * aᵢⱼ = aⱼᵢ
     *
     * Algorithm: Iterate on the upper triangular half and compare with corresponding
     * values on the lower triangular half. Skips the diagonal as it is symmetric with itself.
     *
     * @return bool true if symmetric; false otherwise.
     *
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     */
    public function isSymmetric(): bool
    {
        if (!$this->isSquare()) {
            return false;
        }

        for ($i = 0; $i < $this->m - 1; $i++) {
            for ($j = $i + 1; $j < $this->n; $j++) {
                if (Support::isNotEqual($this->A[$i][$j], $this->A[$j][$i], $this->ε)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Is the matrix skew-symmetric? (Antisymmetric matrix)
     * Does Aᵀ = −A
     * aᵢⱼ = -aⱼᵢ and main diagonal are all zeros
     *
     * Algorithm: Iterate on the upper triangular half and compare with corresponding
     * values on the lower triangular half. Skips the diagonal as it is symmetric with itself.
     *
     * @return bool true if skew-symmetric; false otherwise.
     *
     * @throws Exception\BadParameterException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     */
    public function isSkewSymmetric(): bool
    {
        if (!$this->isSquare()) {
            return false;
        }

        for ($i = 0; $i < $this->m - 1; $i++) {
            for ($j = $i + 1; $j < $this->n; $j++) {
                if (Support::isNotEqual($this->A[$i][$j], -$this->A[$j][$i], $this->ε)) {
                    return false;
                }
            }
        }
        foreach ($this->getDiagonalElements() as $diagonalElement) {
            if (Support::isNotZero($diagonalElement, $this->ε)) {
                return false;
            }
        }

        return true;
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
        $│A│ = $this->det();

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
        $│A│ = $this->det();

        if (Support::isNotZero($│A│, $this->ε)) {
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
        if (!$this->isSymmetric()) {
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
        if (!$this->isSymmetric()) {
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
        if (!$this->isSymmetric()) {
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
        if (!$this->isSymmetric()) {
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
            for ($j = $i + 1; $j < $n; $j++) {
                if (!Support::isZero($this->A[$i][$j])) {
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
                if (!Support::isZero($this->A[$i][$j])) {
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

            if (!$zero_row_ok) {
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
     * Is the matrix idempotent?
     * A matrix that equals itself when squared.
     * https://en.wikipedia.org/wiki/Idempotent_matrix
     *
     * @return boolean true if matrix is idempotent; false otherwise
     *
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     * @throws Exception\VectorException
     */
    public function isIdempotent(): bool
    {
        $A² = $this->multiply($this);
        return $this->isEqual($A²);
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

        return $A²->isEqual($I);
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
            for ($j = $i + 2; $j < $n; $j++) {
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
            for ($j = 0; $j < $i - 1; $j++) {
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
            for ($j = 0; $j < $i - 1; $j++) {
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
            for ($j = $i + 2; $j < $this->n; $j++) {
                if ($this->A[$i][$j] != 0) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Is the matrix orthogonal?
     *  - It is a square matrix
     *  - AAᵀ = AᵀA = I
     *
     * @return bool
     *
     * @throws Exception\MathException
     */
    public function isOrthogonal(): bool
    {
        if (!$this->isSquare()) {
            return false;
        }

        // AAᵀ = I
        $I   = MatrixFactory::identity($this->m);
        $Aᵀ  = $this->transpose();
        $AAᵀ = $this->multiply($Aᵀ);

        return $AAᵀ->isEqual($I);
    }

    /**
     * Is the matrix normal?
     *  - It is a square matrix
     *  - AAᵀ = AᵀA
     *
     * https://en.wikipedia.org/wiki/Normal_matrix
     * @return bool
     *
     * @throws Exception\MathException
     */
    public function isNormal(): bool
    {
        if (!$this->isSquare()) {
            return false;
        }

        // AAᵀ = AᵀA
        $Aᵀ  = $this->transpose();
        $AAᵀ = $this->multiply($Aᵀ);
        $AᵀA = $Aᵀ->multiply($this);

        return $AAᵀ->isEqual($AᵀA);
    }

    /**************************************************************************
     * MATRIX AUGMENTATION - Return a Matrix
     *  - augment
     *  - augmentIdentity
     *  - augmentBelow
     *  - augmentAbove
     *  - augmentLeft
     **************************************************************************/

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
     * Augment a matrix on the left
     * An augmented matrix is a matrix obtained by preprending the columns of two given matrices
     *
     *     [1, 2, 3]
     * A = [2, 3, 4]
     *     [3, 4, 5]
     *
     *     [4]
     * B = [5]
     *     [6]
     *
     *         [4 | 1, 2, 3]
     * (A|B) = [5 | 2, 3, 4]
     *         [6 | 3, 4, 5]
     *
     * @param  Matrix $B Matrix columns to add to matrix A
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if matrices do not have the same number of rows
     * @throws Exception\IncorrectTypeException
     */
    public function augmentLeft(Matrix $B): Matrix
    {
        if ($B->getM() !== $this->m) {
            throw new Exception\MatrixException('Matrices to augment do not have the same number of rows');
        }

        $m    = $this->m;
        $A    = $this->A;
        $B    = $B->getMatrix();
        $⟮B∣A⟯ = [];

        for ($i = 0; $i < $m; $i++) {
            $⟮B∣A⟯[$i] = array_merge($B[$i], $A[$i]);
        }

        return MatrixFactory::create($⟮B∣A⟯);
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
     * Augment a matrix from above
     * An augmented matrix is a matrix obtained by prepending the rows of two given matrices
     *
     *     [1, 2, 3]
     * A = [2, 3, 4]
     *     [3, 4, 5]
     *
     * B = [4, 5, 6]
     *
     *         [4, 5, 6]
     *         [1, 2, 3]
     * (A_B) = [2, 3, 4]
     *         [3, 4, 5]
     *
     * @param  Matrix $B Matrix rows to add to matrix A
     *
     * @return Matrix
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     */
    public function augmentAbove(Matrix $B): Matrix
    {
        if ($B->getN() !== $this->n) {
            throw new Exception\MatrixException('Matrices to augment do not have the same number of columns');
        }

        $⟮A∣B⟯ = array_merge($B->getMatrix(), $this->A);

        return MatrixFactory::create($⟮A∣B⟯);
    }

    /**************************************************************************
     * MATRIX ARITHMETIC OPERATIONS - Return a Matrix
     *  - add
     *  - directSum
     *  - kroneckerSum
     *  - subtract
     *  - multiply
     *  - scalarMultiply
     *  - scalarDivide
     *  - hadamardProduct
     *  - kroneckerProduct
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
     * Matrix multiplication - ikj algorithm
     * https://en.wikipedia.org/wiki/Matrix_multiplication
     *
     * ikj is an improvement on the classic ijk algorithm by simply changing the order of the loops.
     *
     * @param  Matrix|Vector $B Matrix or Vector to multiply
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

        // ikj algorithm
        $R = [];
        for ($i = 0; $i < $this->m; $i++) {
            $R[$i] = array_fill(0, $B->n, 0);
            for ($k = 0; $k < $this->n; $k++) {
                for ($j = 0; $j < $B->n; $j++) {
                    $R[$i][$j] += $this->A[$i][$k] * $B[$k][$j];
                }
            }
        }

        return MatrixFactory::create($R);
    }

    /**
     * Scalar matrix multiplication
     * https://en.wikipedia.org/wiki/Matrix_multiplication#Scalar_multiplication
     *
     * @param  float $λ
     *
     * @return Matrix
     *
     * @throws Exception\BadParameterException if λ is not a number
     * @throws Exception\IncorrectTypeException
     */
    public function scalarMultiply(float $λ): Matrix
    {
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
     * @param  float $λ
     *
     * @return Matrix
     *
     * @throws Exception\BadParameterException if λ is not a number
     * @throws Exception\BadParameterException if λ is 0
     * @throws Exception\IncorrectTypeException
     */
    public function scalarDivide(float $λ): Matrix
    {
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

    /**************************************************************************
     * MATRIX OPERATIONS - Return a Matrix
     *  - transpose
     *  - trace
     *  - map
     *  - diagonal
     *  - inverse
     *  - minorMatrix
     *  - cofactorMatrix
     *  - meanDeviation
     *  - covarianceMatrix
     *  - adjugate
     *  - submatrix
     *  - insert
     *  - householder
     **************************************************************************/

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
        if ($this->catalog->hasTranspose()) {
            return $this->catalog->getTranspose();
        }

        $Aᵀ = [];
        for ($i = 0; $i < $this->n; $i++) {
            $Aᵀ[$i] = $this->getColumn($i);
        }

        $this->catalog->addTranspose(MatrixFactory::create($Aᵀ));
        return $this->catalog->getTranspose();
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
     * @param  callable $func takes a matrix item as input
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
     * Inverse
     *
     * For a 1x1 matrix
     *  A   = [a]
     *  A⁻¹ = [1/a]
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
        if ($this->catalog->hasInverse()) {
            return $this->catalog->getInverse();
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
        $│A│ = $this->det();

         // 1x1 matrix: A⁻¹ = [1 / a]
        if ($m === 1) {
            $a   = $A[0][0];
            $A⁻¹ = MatrixFactory::create([[1 / $a]]);
            $this->catalog->addInverse($A⁻¹);
            return $A⁻¹;
        }

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
            $A⁻¹ = $R->scalarMultiply(1 / $│A│);

            $this->catalog->addInverse($A⁻¹);
            return $A⁻¹;
        }
        
        // nxn matrix 3x3 or larger
        $R   = $this->augmentIdentity()->rref();
        $A⁻¹ = [];

        for ($i = 0; $i < $n; $i++) {
            $A⁻¹[$i] = array_slice($R[$i], $n);
        }

        $A⁻¹ = MatrixFactory::create($A⁻¹);

        $this->catalog->addInverse($A⁻¹);
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
        $M = $this->rowMeans();

        /** @var Vector[] $B */
        $B = array_map(
            function (Vector $Xᵢ) use ($M) {
                return $Xᵢ->subtract($M);
            },
            $X
        );

        return MatrixFactory::createFromVectors($B);
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

    /**
     * Submatrix
     *
     * Return an arbitrary subset of a Matrix as a new Matrix.
     *
     * @param int $m₁ Starting row
     * @param int $n₁ Starting column
     * @param int $m₂ Ending row
     * @param int $n₂ Ending column
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException
     */
    public function submatrix(int $m₁, int $n₁, int $m₂, int $n₂): Matrix
    {
        if ($m₁ >= $this->m || $m₁ < 0 || $m₂ >= $this->m || $m₂ < 0) {
            throw new Exception\MatrixException('Specified Matrix row does not exist');
        }
        if ($n₁ >= $this->n || $n₁ < 0 || $n₂ >= $this->n || $n₂ < 0) {
            throw new Exception\MatrixException('Specified Matrix column does not exist');
        }
        if ($m₂ < $m₁) {
            throw new Exception\MatrixException('Ending row must be greater than beginning row');
        }
        if ($n₂ < $n₁) {
            throw new Exception\MatrixException('Ending column must be greater than the beginning column');
        }

        $A = [];
        for ($i = 0; $i <= $m₂ - $m₁; $i++) {
            for ($j = 0; $j <= $n₂ - $n₁; $j++) {
                $A[$i][$j] = $this->A[$i + $m₁][$j + $n₁];
            }
        }

        return MatrixFactory::create($A);
    }

    /**
     * Insert
     * Insert a smaller matrix within a larger matrix starting at a specified position
     *
     * @param Matrix $small the smaller matrix to embed
     * @param int $m Starting row
     * @param int $n Starting column
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException
     */
    public function insert(Matrix $small, int $m, int $n): Matrix
    {
        if ($small->getM() + $m > $this->m || $small->getN() + $n > $this->n) {
            throw new Exception\MatrixException('Inner matrix exceedes the bounds of the outer matrix');
        }

        $new_array = $this->A;
        for ($i = 0; $i < $small->getM(); $i++) {
            for ($j = 0; $j < $small->getN(); $j++) {
                $new_array[$i + $m][$j + $n] = $small[$i][$j];
            }
        }
        return MatrixFactory::create($new_array);
    }

    /**
     * Householder matrix transformation
     *
     * @return Matrix
     *
     * @throws Exception\MathException
     */
    public function householder(): Matrix
    {
        return Householder::transform($this);
    }

    /**************************************************************************
     * MATRIX VECTOR OPERATIONS - Return a Vector
     *  - vectorMultiply
     *  - rowSums
     *  - rowMeans
     *  - columnSums
     *  - columnMeans
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
     * Sums of each row, returned as a Vector
     *
     * @return Vector
     */
    public function rowSums(): Vector
    {
        $sums = array_map(
            function (array $row) {
                return array_sum($row);
            },
            $this->A
        );

        return new Vector($sums);
    }

    /**
     * Means of each row, returned as a Vector
     * https://en.wikipedia.org/wiki/Sample_mean_and_covariance
     *
     *     1
     * M = - (X₁ + X₂ + ⋯ + Xn)
     *     n
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
    public function rowMeans(): Vector
    {
        $n = $this->n;

        $means = array_map(
            function (array $row) use ($n) {
                return array_sum($row) / $n;
            },
            $this->A
        );

        return new Vector($means);
    }

    /**
     * Sums of each column, returned as a Vector
     *
     * @return Vector
     */
    public function columnSums(): Vector
    {
        $sums = [];
        for ($i = 0; $i < $this->n; $i++) {
            $sums[] = array_sum(array_column($this->A, $i));
        }

        return new Vector($sums);
    }

    /**
     * Means of each column, returned as a Vector
     * https://en.wikipedia.org/wiki/Sample_mean_and_covariance
     *
     *     1
     * M = - (X₁ + X₂ + ⋯ + Xn)
     *     m
     *
     * Example:
     *      [1  4 7 8]
     *  A = [2  2 8 4]
     *      [1 13 1 5]
     *
     *  Consider each row of observations as a row vector:
     *
     *   X₁ = [1  4 7 9]
     *   X₂ = [2  2 8 4]
     *   X₃ = [1 13 1 5]
     *
     *   1  /  1    4    7    9  \      1
     *   - |  +2   +2   +8   +4   |  =  - [4  19  16  18]  =  [1⅓, 6⅓, 5⅓, 5.⅔]
     *   3  \ +1  +13   +1   +5  /      3
     *
     * @return Vector
     */
    public function columnMeans(): Vector
    {
        $m = $this->m;
        $n = $this->n;

        $means = [];
        for ($i = 0; $i < $n; $i++) {
            $means[] = array_sum(array_column($this->A, $i)) / $m;
        }

        return new Vector($means);
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
                $ΣΣaᵢⱼ² += ($this->A[$i][$j]) ** 2;
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
        if ($this->catalog->hasDeterminant()) {
            return $this->catalog->getDeterminant();
        }

        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Not a square matrix (required for determinant)');
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
            $det = $R[0][0];
            $this->catalog->addDeterminant($det);
            return $det;
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

            $det = $ad - $bc;
            $this->catalog->addDeterminant($det);
            return $det;
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

            $det = $a * ($ei - $fh) - $b * ($di - $fg) + $c * ($dh - $eg);
            $this->catalog->addDeterminant($det);
            return $det;
        }

        /*
         * nxn matrix 4x4 or larger
         * Get row echelon form, then compute determinant of ref.
         * Then plug into formula with swaps.
         * │A│ = (-1)ⁿ │ref(A)│
         */
        $ref⟮A⟯ = $this->ref();
        $ⁿ     = $ref⟮A⟯->getRowSwaps();

        // Det(ref(A))
        $│ref⟮A⟯│ = 1;
        for ($i = 0; $i < $m; $i++) {
            $│ref⟮A⟯│ *= $ref⟮A⟯[$i][$i];
        }

        // │A│ = (-1)ⁿ │ref(A)│
        $det = (-1) ** $ⁿ * $│ref⟮A⟯│;
        $this->catalog->addDeterminant($det);
        return $det;
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
        $⟮−1⟯ⁱ⁺ʲ = (-1) ** ($mᵢ + $nⱼ);

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
                if (Support::isNotZero($rref[$i][$j], $this->ε)) {
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
     * @param int   $mᵢ Row to multiply
     * @param float $k Multiplier
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if row to multiply does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function rowMultiply(int $mᵢ, float $k): Matrix
    {
        if ($mᵢ >= $this->m) {
            throw new Exception\MatrixException('Row to multiply does not exist');
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
     * @param int   $mᵢ Row to multiply
     * @param float $k divisor
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if row to multiply does not exist
     * @throws Exception\BadParameterException if k is 0
     * @throws Exception\IncorrectTypeException
     */
    public function rowDivide(int $mᵢ, float $k): Matrix
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
     * @param int   $mᵢ Row to multiply * k to be added to row mⱼ
     * @param int   $mⱼ Row that will have row mⱼ * k added to it
     * @param float $k Multiplier
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if row to add does not exist
     * @throws Exception\BadParameterException if k is 0
     * @throws Exception\IncorrectTypeException
     */
    public function rowAdd(int $mᵢ, int $mⱼ, float $k): Matrix
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
     * @param int   $mᵢ Row to add k to
     * @param float $k scalar
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if row to add does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function rowAddScalar(int $mᵢ, float $k): Matrix
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
     * @param int   $mᵢ Row to multiply * k to be subtracted to row mⱼ
     * @param int   $mⱼ Row that will have row mⱼ * k subtracted to it
     * @param float $k Multiplier
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if row to subtract does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function rowSubtract(int $mᵢ, int $mⱼ, float $k): Matrix
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
     * @param int   $mᵢ Row to add k to
     * @param float $k scalar
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if row to subtract does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function rowSubtractScalar(int $mᵢ, float $k): Matrix
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
     * @param int   $nᵢ Column to multiply
     * @param float $k Multiplier
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if column to multiply does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function columnMultiply(int $nᵢ, float $k): Matrix
    {
        if ($nᵢ >= $this->n) {
            throw new Exception\MatrixException('Column to multiply does not exist');
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
     * @param int   $nᵢ Column to multiply * k to be added to column nⱼ
     * @param int   $nⱼ Column that will have column nⱼ * k added to it
     * @param float $k Multiplier
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if column to add does not exist
     * @throws Exception\BadParameterException if k is 0
     * @throws Exception\IncorrectTypeException
     */
    public function columnAdd(int $nᵢ, int $nⱼ, float $k): Matrix
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
     * MATRIX REDUCTIONS - Return a Matrix in a reduced form
     *  - ref (row echelon form)
     *  - rref (reduced row echelon form)
     **************************************************************************/

    /**
     * Row echelon form - REF
     *
     * @return Reduction\RowEchelonForm
     *
     * @throws Exception\BadDataException
     * @throws Exception\BadParameterException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     */
    public function ref(): Reduction\RowEchelonForm
    {
        if (!$this->catalog->hasRowEchelonForm()) {
            $this->catalog->addRowEchelonForm(Reduction\RowEchelonForm::reduce($this));
        }

        return $this->catalog->getRowEchelonForm();
    }

    /**
     * Reduced row echelon form (row canonical form) - RREF
     *
     * @return Reduction\ReducedRowEchelonForm
     *
     * @throws Exception\BadDataException
     * @throws Exception\BadParameterException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     */
    public function rref(): Reduction\ReducedRowEchelonForm
    {
        if (!$this->catalog->hasReducedRowEchelonForm()) {
            $ref = $this->ref();
            $this->catalog->addReducedRowEchelonForm($ref->rref());
        }

        return $this->catalog->getReducedRowEchelonForm();
    }

    /********************************************************************************
     * MATRIX DECOMPOSITIONS - Returns a Decomposition object that contains Matrices
     *  - LU decomposition
     *  - QR decomposition
     *  - Cholesky decomposition
     *  - Crout decomposition
     ********************************************************************************/

    /**
     * LU Decomposition (Doolittle decomposition) with pivoting via permutation matrix
     *
     * A = LU(P)
     *
     * L: Lower triangular matrix--all entries above the main diagonal are zero.
     *    The main diagonal will be all ones.
     * U: Upper tirangular matrix--all entries below the main diagonal are zero.
     * P: Permutation matrix--Identity matrix with possible rows interchanged.
     *
     * @return Decomposition\LU
     *
     * @throws Exception\MatrixException if matrix is not square
     * @throws Exception\IncorrectTypeException
     * @throws Exception\OutOfBoundsException
     * @throws Exception\VectorException
     */
    public function luDecomposition(): Decomposition\LU
    {
        if (!$this->catalog->hasLuDecomposition()) {
            $this->catalog->addLuDecomposition(Decomposition\LU::decompose($this));
        }

        return $this->catalog->getLuDecomposition();
    }

    /**
     * QR Decomposition using Householder reflections
     *
     * A = QR
     *
     * Q is an orthogonal matrix
     * R is an upper triangular matrix
     *
     * @return Decomposition\QR
     *
     * @throws Exception\MathException
     */
    public function qrDecomposition(): Decomposition\QR
    {
        if (!$this->catalog->hasQrDecomposition()) {
            $this->catalog->addQrDecomposition(Decomposition\QR::decompose($this));
        }

        return $this->catalog->getQrDecomposition();
    }

    /**
     * Cholesky decomposition
     *
     * A decomposition of a square, positive definitive matrix into the product of a lower triangular matrix and its transpose.
     *
     * A = LLᵀ
     *
     * L:  Lower triangular matrix
     * Lᵀ: Transpose of lower triangular matrix
     *
     * @return Decomposition\Cholesky
     *
     * @throws Exception\MatrixException if the matrix is not positive definite
     * @throws Exception\OutOfBoundsException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function choleskyDecomposition(): Decomposition\Cholesky
    {
        if (!$this->catalog->hasCholeskyDecomposition()) {
            $this->catalog->addCholeskyDecomposition(Decomposition\Cholesky::decompose($this));
        }

        return $this->catalog->getCholeskyDecomposition();
    }

    /**
     * Crout decomposition
     *
     * Decomposes a matrix into a lower triangular matrix (L), an upper triangular matrix (U).
     *
     * A = LU where L = LD
     * A = (LD)U
     *  - L = lower triangular matrix
     *  - D = diagonal matrix
     *  - U = normalised upper triangular matrix
     *
     * @return Decomposition\Crout
     *
     * @throws Exception\MatrixException if there is division by 0 because of a 0-value determinant
     * @throws Exception\OutOfBoundsException
     * @throws Exception\IncorrectTypeException
     */
    public function croutDecomposition(): Decomposition\Crout
    {
        if (!$this->catalog->hasCroutDecomposition()) {
            $this->catalog->addCroutDecomposition(Decomposition\Crout::decompose($this));
        }

        return $this->catalog->getCroutDecomposition();
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
     * @param Vector|array $b solution to Ax = b
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
        if ($this->catalog->hasInverse()) {
            return new Vector($this->catalog->getInverse()->multiply($b)->getColumn(0));
        }

        // If 2x2, just compute the inverse and solve: x = A⁻¹b
        if ($this->m === 2 && $this->n === 2) {
            $A⁻¹ = $this->inverse();
            return new Vector($A⁻¹->multiply($b)->getColumn(0));
        }

        // For 3x3 or higher, check if the RREF is already computed.
        // If so, just compute the inverse and solve: x = A⁻¹b
        if ($this->catalog->hasReducedRowEchelonForm()) {
            $A⁻¹ = $this->inverse();
            return new Vector($A⁻¹->multiply($b)->getColumn(0));
        }

        // No inverse or RREF pre-computed.
        // Use LU Decomposition.
        $lu = $this->luDecomposition();
        $L  = $lu->L;
        $U  = $lu->U;
        $P  = $lu->P;
        $m  = $this->m;

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
     * @throws Exception\MathException
     */
    public function eigenvalues(string $method = null): array
    {
        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Eigenvalues can only be calculated on square matrices');
        }
        if ($method === null) {
            if ($this->isTriangular()) {
                $diagonal = $this->getDiagonalElements();
                usort($diagonal, function ($a, $b) {
                    return abs($b) <=> abs($a);
                });
                return $diagonal;
            }
            if ($this->m < 5) {
                return Eigenvalue::closedFormPolynomialRootMethod($this);
            }
            if ($this->isSymmetric()) {
                return Eigenvalue::jacobiMethod($this);
            }
            throw new Exception\MatrixException("Eigenvalue cannot be calculated");
        } elseif (Eigenvalue::isAvailableMethod($method)) {
            return Eigenvalue::$method($this);
        }
        throw new Exception\MatrixException("$method is not a valid eigenvalue method");
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
     * @throws Exception\MathException
     */
    public function eigenvectors(string $method = null): Matrix
    {
        if ($method === null) {
            return Eigenvector::eigenvectors($this, $this->eigenvalues());
        }
        
        return Eigenvector::eigenvectors($this, $this->eigenvalues($method));
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

    /**
     * Get the type of objects that are stored in the matrix
     *
     * @return string The class of the objects
     */
    public function getObjectType(): string
    {
        return 'number';
    }
}
