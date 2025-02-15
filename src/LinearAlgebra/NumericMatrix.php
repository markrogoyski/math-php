<?php

namespace MathPHP\LinearAlgebra;

use MathPHP\Arithmetic;
use MathPHP\Functions\Map;
use MathPHP\Functions\Support;
use MathPHP\Exception;
use MathPHP\LinearAlgebra\Reduction;

/**
 * m x n Matrix
 *
 * @extends Matrix<int|float>
 */
class NumericMatrix extends Matrix
{
    /** @var float Error/zero tolerance */
    protected $ε;

    // Default error/zero tolerance
    protected const ε = 0.00000000001;

    // Matrix data direction
    public const ROWS    = 'rows';
    public const COLUMNS = 'columns';

    // Matrix solve methods
    public const LU      = 'LU';
    public const QR      = 'QR';
    public const INVERSE = 'Inverse';
    public const RREF    = 'RREF';
    public const DEFAULT = 'Default';

    /**
     * Constructor
     *
     * @param array<array<int|float>> $A of arrays $A m x n matrix
     *
     * @throws Exception\BadDataException if any rows have a different column count
     */
    public function __construct(array $A)
    {
        $this->A       = $A;
        $this->m       = \count($A);
        $this->n       = $this->m > 0 ? \count($A[0]) : 0;
        $this->ε       = self::ε;
        $this->catalog = new MatrixCatalog();

        $this->validateMatrixDimensions();
    }

    /**
     * Validate the matrix is entirely m x n
     *
     * @throws Exception\BadDataException
     */
    protected function validateMatrixDimensions(): void
    {
        foreach ($this->A as $i => $row) {
            if (\count($row) !== $this->n) {
                throw new Exception\BadDataException("Row $i has a different column count: " . \count($row) . "; was expecting {$this->n}.");
            }
        }
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

    /**************************************************************************
     * BASIC MATRIX GETTERS
     *  - getError
     **************************************************************************/

    /**
     * Get error / zero tolerance
     * @return float
     */
    public function getError(): float
    {
        return $this->ε;
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
     * @param float|null $ε
     */
    public function setError(?float $ε): void
    {
        if ($ε === null) {
            return;
        }
        $this->ε = $ε;
    }

    /***************************************************************************
     * MATRIX COMPARISONS
     *  - isEqual
     ***************************************************************************/

    /**
     * Is this matrix equal to some other matrix?
     *
     * @param NumericMatrix $B
     *
     * @return bool
     */
    public function isEqual(NumericMatrix $B): bool
    {
        if (!$this->isEqualSizeAndType($B)) {
            return false;
        }

        $m = $this->m;
        $n = $this->n;
        $ε = $this->ε;
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
     *  - isDiagonal
     *  - isRectangularDiagonal
     *  - isRef
     *  - isRref
     *  - isIdempotent
     *  - isNilpotent
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
     *  - isUnitary
     *  - isHermitian
     **************************************************************************/

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

        if (Support::isZero($│A│, $this->ε)) {
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
     * Is the retangular matrix diagonal?
     *  - All the entries below and above the main diagonal are zero
     *
     * https://en.wikipedia.org/wiki/Diagonal_matrix
     *
     * @return boolean true if rectangular diagonal
     */
    public function isRectangularDiagonal(): bool
    {
        $m = $this->m;
        $n = $this->n;
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i !== $j && !Support::isZero($this->A[$i][$j])) {
                    return false;
                }
            }
        }
        return true;
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
            $zero_row = \count(\array_filter(
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
            $entries = \array_filter($column);
            if (\count($entries) !== 1) {
                return false;
            }
            $entry = \array_shift($entries);
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
        if (!$this->isSquare()) {
            return false;
        }
        $A² = $this->multiply($this);
        return $this->isEqual($A²);
    }

    /**
     * Is the matrix nilpotent?
     *
     * A square MxM matrix is nilpotent if it becomes the
     * zero matrix when raised to some power k ≤ M.
     *
     * Nilpotent matrices will have a zero trace for all k
     * https://en.wikipedia.org/wiki/Nilpotent_matrix
     *
     * @return boolean true if matrix is nilpotent; false otherwise
     *
     * @throws Exception\MathException
     */
    public function isNilpotent(): bool
    {
        if (!$this->isSquare() || $this->trace() !== 0) {
            return false;
        }

        $m    = $this->getM();
        $zero = MatrixFactory::zero($m, $m);
        if ($this->isEqual($zero)) {
            return true;
        }

        $A         = $this;
        $nilpotent = false;

        for ($i = 1; $i < $m; $i++) {
            $A = $A->multiply($this);
            if ($A->isEqual($zero)) {
                $nilpotent = true;
                break;
            }
            if ($A->trace() !== 0) {
                break;
            }
        }

        return $nilpotent;
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
                    if (!\in_array($this->A[$i][$j], [-1, 1])) {
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
     *  - AAᴴ = AᴴA
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

        // AAᴴ = AᴴA
        $Aᴴ  = $this->conjugateTranspose();
        $AAᴴ = $this->multiply($Aᴴ);
        $AᴴA = $Aᴴ->multiply($this);

        return $AAᴴ->isEqual($AᴴA);
    }

    /**
     * Is the matrix unitary?
     *  - It is a square matrix
     *  - AAᴴ = AᴴA = I
     *
     * https://en.wikipedia.org/wiki/Unitary_matrix
     * @return bool
     *
     * @throws Exception\MathException
     */
    public function isUnitary(): bool
    {
        if (!$this->isSquare()) {
            return false;
        }

        // AAᴴ = AᴴA = I
        $Aᴴ  = $this->conjugateTranspose();
        $AAᴴ = $this->multiply($Aᴴ);
        $AᴴA = $Aᴴ->multiply($this);

        $I = MatrixFactory::identity($this->m);
        return $AAᴴ->isEqual($AᴴA) && $AAᴴ->isEqual($I);
    }

    /**
     * Is the matrix Hermitian?
     *  - It is a square matrix
     *  - A = Aᴴ
     *
     * https://en.wikipedia.org/wiki/Hermitian_matrix
     * @return bool
     *
     * @throws Exception\MathException
     */
    public function isHermitian(): bool
    {
        if (!$this->isSquare()) {
            return false;
        }

        // A = Aᴴ
        $Aᴴ  = $this->conjugateTranspose();

        return $this->isEqual($Aᴴ);
    }

    /**************************************************************************
     * MATRIX AUGMENTATION - Return a Matrix
     *  - augmentIdentity
     **************************************************************************/

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
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if matrix is not square
     * @throws Exception\IncorrectTypeException
     * @throws Exception\OutOfBoundsException
     */
    public function augmentIdentity(): NumericMatrix
    {
        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Matrix is not square; cannot augment with the identity matrix');
        }

        return $this->augment(MatrixFactory::identity($this->getM()));
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
     * @param NumericMatrix $B Matrix to add to this matrix
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if matrices have a different number of rows or columns
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     */
    public function add($B): NumericMatrix
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

        return MatrixFactory::createNumeric($R, $this->ε);
    }

    /**
     * Direct sum of two matrices: A ⊕ B
     * The direct sum of any pair of matrices A of size m × n and B of size p × q
     * is a matrix of size (m + p) × (n + q)
     * https://en.wikipedia.org/wiki/Matrix_addition#Direct_sum
     *
     * @param  NumericMatrix $B Matrix to add to this matrix
     *
     * @return NumericMatrix
     *
     * @throws Exception\IncorrectTypeException
     */
    public function directSum(NumericMatrix $B): NumericMatrix
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

        return MatrixFactory::createNumeric($R, $this->ε);
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
     * @param NumericMatrix $B Square matrix
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if either matrix is not a square matrix
     * @throws Exception\OutOfBoundsException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadDataException
     */
    public function kroneckerSum(NumericMatrix $B): NumericMatrix
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
     * @param NumericMatrix $B Matrix to subtract from this matrix
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if matrices have a different number of rows or columns
     * @throws Exception\IncorrectTypeException
     */
    public function subtract($B): NumericMatrix
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
        return MatrixFactory::createNumeric($R, $this->ε);
    }

    /**
     * Matrix multiplication - ijk algorithm using cache-oblivious algorithm optimization
     * https://en.wikipedia.org/wiki/Matrix_multiplication
     * https://en.wikipedia.org/wiki/Cache-oblivious_algorithm
     *
     * Gene H. Golub and Charles F. Van Loan (2013). "Matrix Computations 4th Edition" - The Johns Hopkins University Press
     *   - 1.1.10–15 Matrix-Matrix Multiplication
     *   - 1.5 Vectorization and Locality (1.5.4 Blocking for Data Reuse)
     * Park, Liuy, Prasanna, Raghavendra. "Efficient Matrix Multiplication Using Cache Conscious Data Layouts"
     *   (http://www.cs.technion.ac.il/~itai/Courses/Cache/matrix-mult.pdf)
     *
     * ijk is the classic matrix multiplication algorithm using triply nested loops.
     * Iterate the rows of A; iterate the columns of B; iterate the common dimension columns of A/rows of B.
     *
     * A ∈ ℝᵐˣʳ  B ∈ ℝʳˣⁿ  R ∈ ℝᵐˣ
     * for i = 1:m
     *   for j = 1:n
     *     for k - 1:r
     *       R(i,j) = R(i,j) + A(i,k)⋅B(k,j)
     *
     * Cache-oblivious matrix algorithms recognize the cost of thrashing data between memory to high-speed cache.
     * Matrices are implemented using PHP arrays, as rows of arrays, representing data from each column.
     * Transposing the matrix B and traversing it along its transposed rows rather than down columns will have fewer
     * operations to move values between internal memory hierarchies, leading to significant performance gains for
     * larger matrices on most computer hardware.
     *
     * Consider the standard way to think about matrix-matrix multiplication where each resultant matrix element
     * is computed as the dot product:
     *
     *     A        B                    R
     * [ 1  2 ] [ 5  6 ]     [ 1⋅5 + 2⋅7  1⋅6 + 2⋅8 ]
     * [ 3  4 ] [ 7  8 ]  =  [ 3⋅5 + 4⋅7  3⋅6 + 4⋅8 ]
     *
     * The element of R[0][0] traverses A by row and B by column: 1⋅5 + 2⋅7
     *    A       B                   R
     * [ → → ] [ ↓  ]       [ (1  2) ] [ (5)  6 ]
     * [     ] [ ↓  ]       [  3  4  ] [ (7)  8 ]
     *
     * To traverse B by column, a single element of each array is required. Considering that arrays are implemented
     * with contiguous memory allocations and moved into cache in blocks, it is highly probable to have fewer memory-
     * to-cache movement operations if we could also traverse B by row rather than by column.
     * Therefore, if we transpose B, we will traverse both A and B by row, which may lead to fewer operations to move
     * values between internal memory hierarchies.
     *
     * Then, the element of R[0][0] now traverses A by row and Bᵀ by row (which represents a column of B): 1⋅5 + 2⋅7
     *    A       B                  R
     * [ → → ] [ → → ]     [ (1  2) ] [ (5) (7) ]
     * [     ] [     ]     [  3  4  ] [  6   8  ]
     *
     * @param  NumericMatrix|Vector $B Matrix or Vector to multiply
     *
     * @return NumericMatrix
     *
     * @throws Exception\IncorrectTypeException if parameter B is not a Matrix or Vector
     * @throws Exception\MatrixException if matrix dimensions do not match
     * @throws Exception\MathException
     */
    public function multiply($B): NumericMatrix
    {
        if ((!$B instanceof NumericMatrix) && (!$B instanceof Vector)) {
            throw new Exception\IncorrectTypeException('Can only do matrix multiplication with a Matrix or Vector');
        }
        if ($B instanceof Vector) {
            $B = $B->asColumnMatrix();
        }
        if ($B->getM() !== $this->n) {
            throw new Exception\MatrixException("Matrix dimensions do not match");
        }

        $R = [];
        $Bᵀ = $B->transpose()->getMatrix();

        foreach ($this->A as $i => $Aʳᵒʷ⟦i⟧) {
            $R[$i] = \array_fill(0, $B->n, 0);
            foreach ($Bᵀ as $j => $Bᶜᵒˡ⟦j⟧) {
                foreach ($Aʳᵒʷ⟦i⟧ as $k => $A⟦i⟧⟦k⟧) {
                    $R[$i][$j] += $A⟦i⟧⟦k⟧ * $Bᶜᵒˡ⟦j⟧[$k];
                }
            }
        }

        return MatrixFactory::createNumeric($R, $this->ε);
    }

    /**
     * Scalar matrix multiplication
     * https://en.wikipedia.org/wiki/Matrix_multiplication#Scalar_multiplication
     *
     * @param  float $λ
     *
     * @return NumericMatrix
     *
     * @throws Exception\BadParameterException if λ is not a number
     * @throws Exception\IncorrectTypeException
     */
    public function scalarMultiply(float $λ): NumericMatrix
    {
        $R = [];

        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                $R[$i][$j] = $this->A[$i][$j] * $λ;
            }
        }

        return MatrixFactory::createNumeric($R, $this->ε);
    }

    /**
     * Negate a matrix
     * −A = −1A
     *
     * @return NumericMatrix
     *
     * @throws Exception\BadParameterException
     * @throws Exception\IncorrectTypeException
     */
    public function negate(): NumericMatrix
    {
        return $this->scalarMultiply(-1);
    }

    /**
     * Scalar matrix division
     *
     * @param  float $λ
     *
     * @return NumericMatrix
     *
     * @throws Exception\BadParameterException if λ is not a number
     * @throws Exception\BadParameterException if λ is 0
     * @throws Exception\IncorrectTypeException
     */
    public function scalarDivide(float $λ): NumericMatrix
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

        return MatrixFactory::createNumeric($R, $this->ε);
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
     * @param NumericMatrix $B
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if matrices are not the same dimensions
     * @throws Exception\IncorrectTypeException
     */
    public function hadamardProduct(NumericMatrix $B): NumericMatrix
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

        return MatrixFactory::createNumeric($A∘B, $this->ε);
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
     * @param NumericMatrix $B
     *
     * @return NumericMatrix
     *
     * @throws Exception\BadDataException
     */
    public function kroneckerProduct(NumericMatrix $B): NumericMatrix
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
                $row[] = new NumericMatrix($R);
            }
            $arrays[] = $row;
        }

        // Augment each aᵢ₁ to aᵢn block
        $matrices = [];
        foreach ($arrays as $row) {
            /** @var NumericMatrix $initial_matrix */
            $initial_matrix = \array_shift($row);
            $matrices[] = \array_reduce(
                $row,
                function (NumericMatrix $augmented_matrix, NumericMatrix $matrix) {
                    return $augmented_matrix->augment($matrix);
                },
                $initial_matrix
            );
        }

        // Augment below each row block a₁ to am
        /** @var NumericMatrix $initial_matrix */
        $initial_matrix = \array_shift($matrices);
        $A⊗B            = \array_reduce(
            $matrices,
            function (NumericMatrix $augmented_matrix, NumericMatrix $matrix) {
                return $augmented_matrix->augmentBelow($matrix);
            },
            $initial_matrix
        );

        return $A⊗B;
    }

    /**************************************************************************
     * MATRIX OPERATIONS - Return a Matrix
     *  - diagonal
     *  - inverse
     *  - cofactorMatrix
     *  - meanDeviation
     *  - covarianceMatrix
     *  - adjugate
     *  - householder
     *  - upperHessenberg
     **************************************************************************/

    /**
     * Diagonal matrix
     * Retains the elements along the main diagonal.
     * All other off-diagonal elements are zeros.
     *
     * @return NumericMatrix
     *
     * @throws Exception\IncorrectTypeException
     */
    public function diagonal(): NumericMatrix
    {
        $m = $this->m;
        $n = $this->n;
        $R = [];

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i][$j] = ($i == $j) ? $this->A[$i][$j] : 0;
            }
        }

        return MatrixFactory::createNumeric($R, $this->ε);
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
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if not a square matrix
     * @throws Exception\MatrixException if singular matrix
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     * @throws Exception\OutOfBoundsException
     */
    public function inverse(): NumericMatrix
    {
        if ($this->catalog->hasInverse()) {
            /** @var NumericMatrix */
            return $this->catalog->getInverse();
        }

        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Not a square matrix (required for determinant)');
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
            $A⁻¹ = MatrixFactory::createNumeric([[1 / $a]], $this->ε);
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

            $R = MatrixFactory::createNumeric(
                [
                    [$d, -$b],
                    [-$c, $a],
                ],
                $this->ε
            );
            $A⁻¹ = $R->scalarMultiply(1 / $│A│);

            $this->catalog->addInverse($A⁻¹);
            return $A⁻¹;
        }

        // nxn matrix 3x3 or larger
        $R   = $this->augmentIdentity()->rref();
        $A⁻¹ = [];

        for ($i = 0; $i < $n; $i++) {
            $A⁻¹[$i] = \array_slice($R[$i], $n);
        }

        $A⁻¹ = MatrixFactory::createNumeric($A⁻¹, $this->ε);

        $this->catalog->addInverse($A⁻¹);
        return $A⁻¹;
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
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if matrix is not square
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function cofactorMatrix(): NumericMatrix
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

        return MatrixFactory::createNumeric($R, $this->ε);
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
     * @param string $direction Optional specification if to calculate along rows or columns
     *
     * @return NumericMatrix
     *
     * @throws Exception\BadParameterException if direction is not rows or columns
     */
    public function meanDeviation(string $direction = 'rows'): NumericMatrix
    {
        if (!\in_array($direction, [self::ROWS, self::COLUMNS])) {
            throw new Exception\BadParameterException("Direction must be rows or columns, got $direction");
        }

        return $direction === self::ROWS
            ? $this->meanDeviationOfRowVariables()
            : $this->meanDeviationOfColumnVariables();
    }

    /**
     * Mean deviation matrix
     * Matrix as an array of column vectors, where rows represent variables and columns represent samples.
     * Each column vector is subtracted by the sample mean.
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
     * @return NumericMatrix
     *
     * @throws Exception\IncorrectTypeException
     */
    public function meanDeviationOfRowVariables(): NumericMatrix
    {
        $X = $this->asVectors();
        $M = $this->rowMeans();

        /** @var Vector[] $B */
        $B = \array_map(
            function (Vector $Xᵢ) use ($M) {
                return $Xᵢ->subtract($M);
            },
            $X
        );

        return MatrixFactory::createFromVectors($B, $this->ε);
    }

    /**
     * Mean deviation matrix
     * Matrix as an array of row vectors, where columns represent variables and rows represent samples.
     * Each row vector is subtracted by the sample mean.
     *
     * Example:
     *      [1  4 7 8]      [5]
     *  A = [2  2 8 4]  M = [4]
     *      [1 13 1 5]      [5]
     *
     *  M = [4/3, 19/3, 16/3, 17/3]
     *
     *      |[1] - [4/3]  [4] - [19/3]  7 - [16/3]  [8] - [17/3]|
     *  B = |[2] - [4/3]  [2] - [19/3]  8 - [16/3]  [4] - [17/3]|
     *      |[1] - [4/3] [13] - [19/3]  1 - [16/3]  [5] - [17/3]|
     *
     *      [-1/3  -2.33   1.66  2.33]
     *  B = [2/3   -4.33   2.66 -1.66]
     *      [-1/3   6.66  -4.33  -2/3]
     *
     * @return NumericMatrix
     *
     * @throws Exception\IncorrectTypeException
     */
    public function meanDeviationOfColumnVariables(): NumericMatrix
    {
        $X = $this->asRowVectors();
        $M = $this->columnMeans();

        /** @var Vector[] $B */
        $B = \array_map(
            function (Vector $Xᵢ) use ($M) {
                return $Xᵢ->subtract($M);
            },
            $X
        );

        return MatrixFactory::createFromVectors($B, $this->ε)->transpose();
    }

    /**
     * Covariance matrix (variance-covariance matrix, sample covariance matrix)
     * https://en.wikipedia.org/wiki/Covariance_matrix
     * https://en.wikipedia.org/wiki/Sample_mean_and_covariance
     *
     * Example:
     *     [var₁  cov₁₂ cov₁₃]
     * S = [cov₁₂ var₂  cov₂₃]
     *     [cov₁₃ cov₂₃ var₃]
     *
     * @param string $direction Optional specification if to calculate along rows or columns
     *                          'rows' (default): rows represent variables and columns represent samples
     *                          'columns': columns represent variables and rows represent samples
     *
     * @return NumericMatrix
     *
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     * @throws Exception\BadParameterException
     * @throws Exception\VectorException
     */
    public function covarianceMatrix(string $direction = 'rows'): NumericMatrix
    {
        if (!\in_array($direction, [self::ROWS, self::COLUMNS])) {
            throw new Exception\BadParameterException("Direction must be rows or columns, got $direction");
        }

        $S = $direction === self::ROWS
            ? $this->covarianceMatrixOfRowVariables()
            : $this->covarianceMatrixOfColumnVariables();

        return $S;
    }

    /**
     * Covariance matrix (variance-covariance matrix, sample covariance matrix)
     * where rows represent variables and columns represent samples
     * https://en.wikipedia.org/wiki/Covariance_matrix
     * https://en.wikipedia.org/wiki/Sample_mean_and_covariance
     *
     *       1
     * S = ----- BBᵀ
     *     N - 1
     *
     *  where B is the mean-deviation form
     *
     * Uses mathematical convention where matrix columns represent observation vectors.
     * Follows formula and method found in Linear Algebra and Its Applications (Lay).
     *
     * @return NumericMatrix
     *
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     * @throws Exception\BadParameterException
     * @throws Exception\VectorException
     */
    protected function covarianceMatrixOfRowVariables(): NumericMatrix
    {
        $n  = $this->n;
        $B  = $this->meanDeviationOfRowVariables();
        $Bᵀ = $B->transpose();

        $S = $B->multiply($Bᵀ)->scalarMultiply((1 / ($n - 1)));

        return $S;
    }

    /**
     * Covariance matrix (variance-covariance matrix, sample covariance matrix)
     * where columns represent variables and rows represent samples
     * https://en.wikipedia.org/wiki/Covariance_matrix
     * https://en.wikipedia.org/wiki/Sample_mean_and_covariance
     *
     *       1
     * S = ----- BᵀB
     *     N - 1
     *
     *  where B is the mean-deviation form
     *
     * @return NumericMatrix
     *
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     * @throws Exception\BadParameterException
     * @throws Exception\VectorException
     */
    protected function covarianceMatrixOfColumnVariables(): NumericMatrix
    {
        $n  = $this->m;
        $B  = $this->meanDeviationOfColumnVariables();
        $Bᵀ = $B->transpose();

        $S = $Bᵀ->multiply($B)->scalarMultiply((1 / ($n - 1)));

        return $S;
    }

    /**
     * Adjugate matrix (adjoint, adjunct)
     * The transpose of its cofactor matrix.
     * https://en.wikipedia.org/wiki/Adjugate_matrix
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException is matrix is not square
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function adjugate(): NumericMatrix
    {
        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Matrix is not square; cannot get adjugate Matrix of a non-square matrix');
        }

        if ($this->n === 1) {
            return MatrixFactory::createNumeric([[1]], $this->ε);
        }

        $adj⟮A⟯ = $this->cofactorMatrix()->transpose();

        return $adj⟮A⟯;
    }

    /**
     * Householder matrix transformation
     *
     * @return NumericMatrix
     *
     * @throws Exception\MathException
     */
    public function householder(): NumericMatrix
    {
        return Householder::transform($this);
    }

    /**
     * Uses householder to convert the matrix to upper hessenberg form
     *
     * @return NumericMatrix
     *
     * @throws Exception\MathException
     */
    public function upperHessenberg(): NumericMatrix
    {
        $n = $this->getM();

        $hessenberg = $this;
        $identity = MatrixFactory::identity($n);

        for ($i = 0; $i < $n-2; $i++)
        {
            $slice = $this->submatrix($i+1, $i, $n-1, $i);
            $x = $slice->asVectors()[0];
            $sign = $x[0] >= 0 ? 1 : -1;
            $x1 = $sign * $x->l2norm();
            if (Arithmetic::almostEqual($x1, 0, $this->getError())) {
                continue;
            }
            $u = \array_fill(0, $n-$i-1, 0);
            $u[0] = $x1;
            $u = new Vector($u);
            
            $v = $u->subtract($x);
            $v = MatrixFactory::createFromVectors([$v]);
            $vt = $v->transpose();

            $vvt = $v->multiply($vt);
            $vtv = $vt->multiply($v)[0][0];
            $P = $vvt->scalarDivide($vtv);

            $H = MatrixFactory::identity($P->getM());
            $H = $H->subtract($P->scalarMultiply(2));

            // augment
            $offset = $n - $H->getM();
            $elems = $identity->getMatrix();
            for ($i = 0; $i < $H->getM(); $i++)
            {
                for ($j = 0; $j < $H->getN(); $j++)
                {
                    $elems[$i + $offset][$j + $offset] = $H[$i][$j];
                }
            }
            
            $H = MatrixFactory::create($elems);

            // Multiply
            $hessenberg = $H->multiply($hessenberg->multiply($H));
        }

        return $hessenberg;
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
        $n = \count($B);
        $m = $this->m;

        if ($n !== $this->n) {
            throw new Exception\MatrixException("Matrix and vector dimensions do not match");
        }

        $R = [];
        for ($i = 0; $i < $m; $i++) {
            $R[$i] = \array_sum(Map\Multi::multiply($this->getRow($i), $B));
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
        $sums = \array_map(
            function (array $row) {
                return \array_sum($row);
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

        $means = \array_map(
            function (array $row) use ($n) {
                return \array_sum($row) / $n;
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
            $sums[] = \array_sum(\array_column($this->A, $i));
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
            $means[] = \array_sum(\array_column($this->A, $i)) / $m;
        }

        return new Vector($means);
    }

    /**************************************************************************
     * MATRIX OPERATIONS - Return a value
     *  - trace
     *  - oneNorm
     *  - frobeniusNorm
     *  - infinityNorm
     *  - maxNorm
     *  - det
     *  - cofactor
     *  - rank
     **************************************************************************/

    /**
     * Trace
     * the trace of an n-by-n square matrix A is defined to be
     * the sum of the elements on the main diagonal
     * (the diagonal from the upper left to the lower right).
     * https://en.wikipedia.org/wiki/Trace_(linear_algebra)
     *
     * tr(A) = a₁₁ + a₂₂ + ... ann
     *
     * @return int|float
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
     * 1-norm (‖A‖₁)
     * Maximum absolute column sum of the matrix
     *
     * @return int|float
     */
    public function oneNorm()
    {
        $n = $this->n;
        $‖A‖₁ = \array_sum(Map\Single::abs(\array_column($this->A, 0)));

        for ($j = 1; $j < $n; $j++) {
            $‖A‖₁ = \max($‖A‖₁, \array_sum(Map\Single::abs(\array_column($this->A, $j))));
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
     * @return int|float
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

        return \sqrt($ΣΣaᵢⱼ²);
    }

    /**
     * Infinity norm (‖A‖∞)
     * Maximum absolute row sum of the matrix
     *
     * @return int|float
     */
    public function infinityNorm()
    {
        $m = $this->m;
        $‖A‖∞ = \array_sum(Map\Single::abs($this->A[0]));

        for ($i = 1; $i < $m; $i++) {
            $‖A‖∞ = \max($‖A‖∞, \array_sum(Map\Single::abs($this->A[$i])));
        }

        return $‖A‖∞;
    }

    /**
     * Max norm (‖A‖max)
     * Elementwise max
     *
     * @return int|float
     */
    public function maxNorm()
    {
        $m   = $this->m;
        $n   = $this->n;
        $max = \abs($this->A[0][0]);

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $max = \max($max, \abs($this->A[$i][$j]));
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
     * @return int|float
     *
     * @throws Exception\MatrixException if matrix is not square
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     */
    public function det()
    {
        if ($this->catalog->hasDeterminant()) {
            /** @var int|float */
            return $this->catalog->getDeterminant();
        }

        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Not a square matrix (required for determinant)');
        }

        $m = $this->m;

        /** @var NumericMatrix $R */
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
     * @return int|float
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
     *  - rowMultiply
     *  - rowDivide
     *  - rowAdd
     *  - rowAddScalar
     *  - rowAddVector
     *  - rowSubtract
     *  - rowSubtractScalar
     **************************************************************************/

    /**
     * Multiply a row by a factor k
     *
     * Each element of Row mᵢ will be multiplied by k
     *
     * @param int   $mᵢ Row to multiply
     * @param float $k Multiplier
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if row to multiply does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function rowMultiply(int $mᵢ, float $k): NumericMatrix
    {
        if ($mᵢ >= $this->m) {
            throw new Exception\MatrixException('Row to multiply does not exist');
        }

        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mᵢ][$j] *= $k;
        }

        return MatrixFactory::createNumeric($R, $this->ε);
    }

    /**
     * Divide a row by a divisor k
     *
     * Each element of Row mᵢ will be divided by k
     *
     * @param int   $mᵢ Row to multiply
     * @param float $k divisor
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if row to multiply does not exist
     * @throws Exception\BadParameterException if k is 0
     * @throws Exception\IncorrectTypeException
     */
    public function rowDivide(int $mᵢ, float $k): NumericMatrix
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

        return MatrixFactory::createNumeric($R, $this->ε);
    }

    /**
     * Add k times row mᵢ to row mⱼ
     *
     * @param int   $mᵢ Row to multiply * k to be added to row mⱼ
     * @param int   $mⱼ Row that will have row mⱼ * k added to it
     * @param float $k Multiplier
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if row to add does not exist
     * @throws Exception\BadParameterException if k is 0
     * @throws Exception\IncorrectTypeException
     */
    public function rowAdd(int $mᵢ, int $mⱼ, float $k): NumericMatrix
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

        return MatrixFactory::createNumeric($R, $this->ε);
    }

    /**
     * Add a scalar k to each item of a row
     *
     * Each element of Row mᵢ will have k added to it
     *
     * @param int   $mᵢ Row to add k to
     * @param float $k scalar
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if row to add does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function rowAddScalar(int $mᵢ, float $k): NumericMatrix
    {
        if ($mᵢ >= $this->m) {
            throw new Exception\MatrixException('Row to add does not exist');
        }

        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mᵢ][$j] += $k;
        }

        return MatrixFactory::createNumeric($R, $this->ε);
    }

    /**
     * Add components of vector V to row mᵢ
     *
     * @param int    $mᵢ Row to add vector $v to
     * @param Vector $V  Vector to add to row mᵢ
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if row to add does not exist
     * @throws Exception\BadParameterException if the vector has a different # of components to the # of columns
     * @throws Exception\IncorrectTypeException
     */
    public function rowAddVector(int $mᵢ, Vector $V): NumericMatrix
    {
        if ($mᵢ < 0 || $mᵢ >= $this->m) {
            throw new Exception\MatrixException("Row to add to ($mᵢ) does not exist");
        }
        if (count($V) !== $this->n) {
            throw new Exception\BadParameterException('Vector is not the same length as matrix columns');
        }

        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mᵢ][$j] += $V[$j];
        }

        return MatrixFactory::createNumeric($R, $this->ε);
    }

    /**
     * Subtract k times row mᵢ to row mⱼ
     *
     * @param int   $mᵢ Row to multiply * k to be subtracted to row mⱼ
     * @param int   $mⱼ Row that will have row mⱼ * k subtracted to it
     * @param float $k Multiplier
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if row to subtract does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function rowSubtract(int $mᵢ, int $mⱼ, float $k): NumericMatrix
    {
        if ($mᵢ >= $this->m || $mⱼ >= $this->m) {
            throw new Exception\MatrixException('Row to subtract does not exist');
        }

        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mⱼ][$j] -= $R[$mᵢ][$j] * $k;
        }

        return MatrixFactory::createNumeric($R, $this->ε);
    }

    /**
     * Subtract a scalar k to each item of a row
     *
     * Each element of Row mᵢ will have k subtracted from it
     *
     * @param int   $mᵢ Row to add k to
     * @param float $k scalar
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if row to subtract does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function rowSubtractScalar(int $mᵢ, float $k): NumericMatrix
    {
        if ($mᵢ >= $this->m) {
            throw new Exception\MatrixException('Row to subtract does not exist');
        }

        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mᵢ][$j] -= $k;
        }

        return MatrixFactory::createNumeric($R, $this->ε);
    }

    /**************************************************************************
     * COLUMN OPERATIONS - Return a Matrix
     *  - columnMultiply
     *  - columnAdd
     *  - columnAddVector
     **************************************************************************/

    /**
     * Multiply a column by a factor k
     *
     * Each element of column nᵢ will be multiplied by k
     *
     * @param int   $nᵢ Column to multiply
     * @param float $k Multiplier
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if column to multiply does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function columnMultiply(int $nᵢ, float $k): NumericMatrix
    {
        if ($nᵢ >= $this->n) {
            throw new Exception\MatrixException('Column to multiply does not exist');
        }

        $m = $this->m;
        $R = $this->A;

        for ($i = 0; $i < $m; $i++) {
            $R[$i][$nᵢ] *= $k;
        }

        return MatrixFactory::createNumeric($R, $this->ε);
    }

    /**
     * Add k times column nᵢ to column nⱼ
     *
     * @param int   $nᵢ Column to multiply * k to be added to column nⱼ
     * @param int   $nⱼ Column that will have column nⱼ * k added to it
     * @param float $k Multiplier
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if column to add does not exist
     * @throws Exception\BadParameterException if k is 0
     * @throws Exception\IncorrectTypeException
     */
    public function columnAdd(int $nᵢ, int $nⱼ, float $k): NumericMatrix
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

        return MatrixFactory::createNumeric($R, $this->ε);
    }

    /**
     * Add components of vector V to column nᵢ
     *
     * @param int    $nᵢ Column to add vector $v to
     * @param Vector $V  Vector to add to column nᵢ
     *
     * @return NumericMatrix
     *
     * @throws Exception\MatrixException if column to add does not exist
     * @throws Exception\BadParameterException if the vector has a different # of components to the # of rows
     * @throws Exception\IncorrectTypeException
     */
    public function columnAddVector(int $nᵢ, Vector $V): NumericMatrix
    {
        if ($nᵢ < 0 || $nᵢ >= $this->n) {
            throw new Exception\MatrixException("Column to add to ($nᵢ) does not exist");
        }
        if (count($V) !== $this->m) {
            throw new Exception\BadParameterException('Vector is not the same length as matrix rows');
        }

        $m = $this->m;
        $R = $this->A;

        for ($i = 0; $i < $m; $i++) {
            $R[$i][$nᵢ] += $V[$i];
        }

        return MatrixFactory::createNumeric($R, $this->ε);
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
     *  - SVD (Singular Value Decomposition)
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

    /**
     * Singular Value Decomposition (SVD)
     *
     * A = USVᵀ
     *
     * U is an orthogonal matrix
     * S is a diagonal matrix
     * V is an orthogonal matrix
     *
     * @return Decomposition\SVD
     *
     * @throws Exception\MathException
     */
    public function svd(): Decomposition\SVD
    {
        if (!$this->catalog->hasSVD()) {
            $this->catalog->addSVD(Decomposition\SVD::decompose($this));
        }

        return $this->catalog->getSVD();
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
     * @param Vector|array<int|float> $b solution to Ax = b
     * @param string                  $method (optional) Force a specific solve method - defaults to DEFAULT where various methods are tried
     *
     * @return Vector x
     *
     * @throws Exception\IncorrectTypeException if b is not a Vector or array
     * @throws Exception\MatrixException
     * @throws Exception\VectorException
     * @throws Exception\OutOfBoundsException
     * @throws Exception\BadParameterException
     */
    public function solve($b, string $method = self::DEFAULT): Vector
    {
        // Input must be a Vector or array.
        if (!($b instanceof Vector || \is_array($b))) {
            throw new Exception\IncorrectTypeException('b in Ax = b must be a Vector or array');
        }
        if (\is_array($b)) {
            $b = new Vector($b);
        }

        switch ($method) {
            case self::LU:
                $lu = $this->luDecomposition();
                return $lu->solve($b);

            case self::QR:
                $qr = $this->qrDecomposition();
                return $qr->solve($b);

            case self::INVERSE:
                $A⁻¹ = $this->inverse();
                return new Vector($A⁻¹->multiply($b)->getColumn(0));

            case self::RREF:
                return $this->solveRref($b);

            default:
                // If inverse is already calculated, solve: x = A⁻¹b
                if ($this->catalog->hasInverse()) {
                    /** @var NumericMatrix $inverse */
                    $inverse = $this->catalog->getInverse();
                    return new Vector($inverse->multiply($b)->getColumn(0));
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

                try {
                    $lu = $this->luDecomposition();
                    return $lu->solve($b);
                } catch (Exception\DivisionByZeroException $e) {
                    // Not solvable via LU decomposition
                }

                // LU failed, use QR Decomposition.
                try {
                    $qr = $this->qrDecomposition();
                    return $qr->solve($b);
                } catch (Exception\MatrixException $e) {
                    // Not solvable via QR decomposition
                }

                // Last resort, augment A with b (Ab) and solve RREF.
                // x is the rightmost column.
                return $this->solveRref($b);
        }
    }

    /**
     * Solve Ax = b using RREF
     *
     * As an augmented matrix Ab, the RREF has the x solution to Ax = b as the rightmost column.
     *
     * Edge case: If the matrix is singular, there may be one or more rows of zeros at the bottom. This leads to
     * the ones not being on the diagonal. In this case, the rightmost column will not have the values in the correct
     * order. To deal with this, we look at where the ones are and reorder the column vector.
     *
     * @param Vector $b
     * @return Vector
     */
    private function solveRref(Vector $b): Vector
    {
        $Ab   = $this->augment($b->asColumnMatrix());
        $rref = $Ab->rref();

        // Edge case if singular matrix
        if ($this->isSingular()) {
            $x = [];
            $i = 0;
            $j = 0;
            while ($i < $this->m && $j < $this->n) {
                if ($rref[$i][$j] == 1) {
                    $x[] = $rref[$i][$this->n];
                    $i++;
                    $j++;
                } else {
                    $x[] = 0;
                    $j++;
                }
            }
            return new Vector($x);
        }

        // Standard case - rightmost column is the solution
        return new Vector(\array_column($rref->getMatrix(), $rref->getN() - 1));
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
     * @return array<int|float> of eigenvalues
     *
     * @throws Exception\MatrixException if method is not a valid eigenvalue method
     * @throws Exception\MathException
     */
    public function eigenvalues(?string $method = null): array
    {
        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Eigenvalues can only be calculated on square matrices');
        }
        if ($method === null) {
            if ($this->isTriangular()) {
                $diagonal = $this->getDiagonalElements();
                \usort($diagonal, function ($a, $b) {
                    return \abs($b) <=> \abs($a);
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
     * @return NumericMatrix of eigenvectors
     *
     * @throws Exception\MatrixException if method is not a valid eigenvalue method
     * @throws Exception\MathException
     */
    public function eigenvectors(?string $method = null): NumericMatrix
    {
        if ($method === null) {
            return Eigenvector::eigenvectors($this, $this->eigenvalues());
        }

        return Eigenvector::eigenvectors($this, $this->eigenvalues($method));
    }

    /**************************************************************************
     * PHP MAGIC METHODS
     *  - __toString
     *  - __debugInfo
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
    public function __toString(): string
    {
        // @phpstan-ignore-next-line
        return \trim(\array_reduce(\array_map(
            function ($mᵢ) {
                return '[' . \implode(', ', $mᵢ) . ']';
            },
            $this->A
        ), function ($A, $mᵢ) {
            return $A . \PHP_EOL . $mᵢ;
        }));
    }

    /**
     * Debug info
     * Ex:
     *   [matrix] => 3x4
     *   [data] =>
     *     [1, 2, 3, 4]
     *     [2, 3, 4, 5]
     *     [3, 4, 5, 6]
     *   [ε] => 1.0E-11
     *
     * @return array{matrix: string, data: string, ε: float}
     */
    public function __debugInfo(): array
    {
        return [
            'matrix' => sprintf('%dx%d', $this->m, $this->n),
            'data'   => \PHP_EOL . (string) $this,
            'ε'      => $this->ε,
        ];
    }
}
