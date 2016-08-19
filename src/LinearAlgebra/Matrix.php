<?php
namespace Math\LinearAlgebra;

use Math\Functions\Map;

/**
 * m x n Matrix
 */
class Matrix implements \ArrayAccess, \JsonSerializable
{
    /**
     * Number of rows
     * @var int
     */
    protected $m;

    /**
     * Number of columns
     * @var int
     */
    protected $n;

    /**
     * Matrix
     * @var array of arrays
     */
    protected $A;

    /**
     * Reduced row echelon form
     * @var Matrix
     */
    protected $rref;

    /**
     * Determinant
     * @var number
     */
    protected $det;

    /**
     * Inverse
     */
    protected $A⁻¹;

    /**
     * Constructor
     * @param array of arrays $A m x n matrix
     */
    public function __construct(array $A)
    {
        $this->A = $A;
        $this->m = count($A);
        $this->n = $this->m > 0 ? count($A[0]) : 0;

        foreach ($A as $i => $row) {
            if (count($row) !== $this->n) {
                throw new \Exception("Row $i has a different column count: " . count($row) . "; was expecting {$this->n}.");
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
     */
    public function getRow(int $i): array
    {
        if ($i >= $this->m) {
            throw new \Exception("Row $i does not exist");
        }

        return $this->A[$i];
    }

    /**
     * Get single column from the matrix
     *
     * @param  int   $j column index (from 0 to n - 1)
     * @return array
     */
    public function getColumn(int $j): array
    {
        if ($j >= $this->n) {
            throw new \Exception("Column $j does not exist");
        }

        return array_column($this->A, $j);
    }

    /**
     * Get a specific value at row i, column j
     *
     * @param  int    $i row index
     * @param  int    $j column index
     * @return number
     */
    public function get(int $i, int $j)
    {
        if ($i >= $this->m) {
            throw new \Exception("Row $i does not exist");
        }
        if ($j >= $this->n) {
            throw new \Exception("Column $j does not exist");
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

    /**************************************************************************
     * MATRIX PROPERTIES
     *  - isSquare
     *  - isSymmetric
     **************************************************************************/

    /**
     * Is the matrix a square matrix?
     * Do rows m = columns n?
     *
     * @return bool
     */
    public function isSquare(): bool
    {
        return $this->m == $this->n;
    }

    /**
     * Is the matrix symmetric?
     * Does A = Aᵀ
     *
     * @return bool
     */
    public function isSymmetric(): bool
    {
        $A  = $this->A;
        $Aᵀ = $this->transpose()->getMatrix();

        return $A == $Aᵀ;
    }

    /**************************************************************************
     * MATRIX OPERATIONS - Return a Matrix
     *  - add
     *  - directSum
     *  - subtract
     *  - multiply
     *  - scalarMultiply
     *  - hadamardProduct
     *  - transpose
     *  - trace
     *  - map
     *  - diagonal
     *  - augment
     *  - augmentIdentity
     *  - inverse
     *  - minorMatrix
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
     */
    public function add(Matrix $B): Matrix
    {
        if ($B->getM() !== $this->m) {
            throw new \Exception('Matices have different number of rows');
        }
        if ($B->getN() !== $this->n) {
            throw new \Exception('Matices have different number of columns');
        }

        $R = [];

        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                $R[$i][$j] = $this->A[$i][$j] + $B[$i][$j];
            }
        }

        return new Matrix($R);
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

        return new Matrix($R);
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
     */
    public function subtract(Matrix $B): Matrix
    {
        if ($B->getM() !== $this->m) {
            throw new \Exception('Matices have different number of rows');
        }
        if ($B->getN() !== $this->n) {
            throw new \Exception('Matices have different number of columns');
        }

        $R = [];

        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                $R[$i][$j] = $this->A[$i][$j] - $B[$i][$j];
            }
        }
        return new Matrix($R);
    }

    /**
     * Matrix multiplication
     * https://en.wikipedia.org/wiki/Matrix_multiplication#Matrix_product_.28two_matrices.29
     *
     * @param  Matrix $B Matrix to multiply
     *
     * @return Matrix
     */
    public function multiply(Matrix $B): Matrix
    {
        if ($B->getM() !== $this->n) {
            throw new \Exception("Matrix dimensions do not match");
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

        return new Matrix($R);
    }

    /**
     * Scalar matrix multiplication
     * https://en.wikipedia.org/wiki/Matrix_multiplication#Scalar_multiplication
     *
     * @param  number $λ
     *
     * @return Matrix
     */
    public function scalarMultiply($λ): Matrix
    {
        if (!is_numeric($λ)) {
            throw new \Exception('Parameter not a number');
        }

        $R = [];

        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                $R[$i][$j] = $this->A[$i][$j] * $λ;
            }
        }

        return new Matrix($R);
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
     */
    public function hadamardProduct(Matrix $B): Matrix
    {
        if ($B->getM() !== $this->m || $B->getN() !== $this->n) {
            throw new \Exception('Matrices are not the same dimensions');
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

        return new Matrix($A∘B);
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
     */
    public function transpose()
    {
        $Aᵀ = [];
        for ($i = 0; $i < $this->n; $i++) {
            $Aᵀ[$i] = $this->getColumn($i);
        }

        return new Matrix($Aᵀ);
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
     */
    public function trace()
    {
        if (!$this->isSquare()) {
            throw new \Exception('trace only works on a square matrix');
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

        return new Matrix($R);
    }

    /**
     * Diagonal matrix
     * Retains the elements along the main diagonal.
     * All other off-diagonal elements are zeros.
     *
     * @return Matrix
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

        return new Matrix($R);
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
     */
    public function augment(Matrix $B): Matrix
    {
        if ($B->getM() !== $this->m) {
            throw new \Exception('Matrices to augment do not have the same number of rows');
        }

        $m    = $this->m;
        $A    = $this->A;
        $B    = $B->getMatrix();
        $⟮A∣B⟯ = [];

        for ($i = 0; $i < $m; $i++) {
            $⟮A∣B⟯[$i] = array_merge($A[$i], $B[$i]);
        }

        return new Matrix($⟮A∣B⟯);
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
     * @param  Matrix $B Matrix columns to add to matrix A
     *
     * @return Matrix
     */
    public function augmentIdentity(): Matrix
    {
        if (!$this->isSquare()) {
            throw new \Exception('Matrix is not square; cannot augment with the identity matrix');
        }

        return $this->augment(self::identity($this->getM()));
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
     */
    public function inverse(): Matrix
    {
        if (isset($this->A⁻¹)) {
            return $this->A⁻¹;
        }

        if (!$this->isSquare()) {
            throw new \Exception('Not a sqaure matrix (required for determinant)');
        }

        $│A│ = $this->det ?? $this->det();
        if ($│A│ == 0) {
            throw new \Exception('Singular matrix (determinant = 0); not invertible');
        }

        $m = $this->m;
        $n = $this->n;
        $A = $this->A;

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

            $R = new Matrix([
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

        $A⁻¹ = new Matrix($A⁻¹);

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
     */
    public function minorMatrix(int $mᵢ, int $nⱼ): Matrix
    {
        if (!$this->isSquare()) {
            throw new \Exception('Matrix is not square; cannot get minor Matrix of a non-square matrix');
        }
        if ($mᵢ >= $this->m || $mᵢ < 0) {
            throw new \Exception('Row to exclude for minor Matrix does not exist');
        }
        if ($nⱼ >= $this->n || $nⱼ < 0) {
            throw new \Exception('Column to exclude for minor Matrix does not exist');
        }

        return $this->rowExclude($mᵢ)->columnExclude($nⱼ);
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
     * Frobenius norm (Hilbert–Schmidt norm) (‖A‖F)
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
     * │A│ = (-1)ⁿ │rref(A)│ ∏1/k
     *
     *  where:
     *   │rref(A)│ = determinant of the reduced row echelon form of A
     *   ⁿ         = number of row swaps when computing RREF
     *   ∏1/k      = product of 1/k where k is the scaling factor divisor
     *
     * @return number
     */
    public function det()
    {
        if (isset($this->det)) {
            return $this->det;
        }

        if (!$this->isSquare()) {
            throw new \Exception('Not a sqaure matrix (required for determinant)');
        }

        $m = $this->m;
        $n = $this->n;
        $R = new Matrix($this->A);

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
         * Get row reduced echelon form, then compute determinant of rref.
         * Then plug into formula with swaps and product of scaling factor.
         * │A│ = (-1)ⁿ │rref(A)│ ∏1/k
         */
        $rref⟮A⟯ = $this->rref ?? $this->rref();
        $ⁿ      = $this->rref_swaps;
        $∏1／k  = $this->rref_∏scaling_factor;

        // Det(rref(A))
        $│rref⟮A⟯│ = 1;
        for ($i = 0; $i < $m; $i++) {
            $│rref⟮A⟯│ *= $rref⟮A⟯[$i][$i];
        }

        // │A│ = (-1)ⁿ │rref(A)│ ∏1/k
        $this->det = (-1)**$ⁿ * ($│rref⟮A⟯│ / $∏1／k);
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
     */
    public function minor(int $mᵢ, int $nⱼ)
    {
        if (!$this->isSquare()) {
            throw new \Exception('Matrix is not square; cannot get minor of a non-square matrix');
        }
        if ($mᵢ >= $this->m || $mᵢ < 0) {
            throw new \Exception('Row to exclude for minor does not exist');
        }
        if ($nⱼ >= $this->n || $nⱼ < 0) {
            throw new \Exception('Column to exclude for minor does not exist');
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
     */
    public function cofactor(int $mᵢ, int $nⱼ)
    {
        if (!$this->isSquare()) {
            throw new \Exception('Matrix is not square; cannot get cofactor of a non-square matrix');
        }
        if ($mᵢ >= $this->m || $mᵢ < 0) {
            throw new \Exception('Row to exclude for cofactor does not exist');
        }
        if ($nⱼ >= $this->n || $nⱼ < 0) {
            throw new \Exception('Column to exclude for cofactor does not exist');
        }

        $Mᵢⱼ    = $this->minor($mᵢ, $nⱼ);
        $⟮−1⟯ⁱ⁺ʲ = (-1)**($mᵢ + $nⱼ);

        return $⟮−1⟯ⁱ⁺ʲ * $Mᵢⱼ;
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
     */
    public function rowInterchange(int $mᵢ, int $mⱼ): Matrix
    {
        if ($mᵢ >= $this->m || $mⱼ >= $this->m) {
            throw new \Exception('Row to interchange does not exist');
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

        return new Matrix($R);
    }

    /**
     * Multiply a row by a factor k
     *
     * Each element of Row mᵢ will be multiplied by k
     *
     * @param int  $mᵢ Row to multiply
     * @param int  $k  Multiplier
     *
     * @return Matrix
     */
    public function rowMultiply(int $mᵢ, int $k): Matrix
    {
        if ($mᵢ >= $this->m) {
            throw new \Exception('Row to multiply does not exist');
        }
        if ($k == 0) {
            throw new \Exception('Multiplication factor k must not be 0');
        }

        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mᵢ][$j] *= $k;
        }

        return new Matrix($R);
    }

    /**
     * Divide a row by a divisor k
     *
     * Each element of Row mᵢ will be divided by k
     *
     * @param int  $mᵢ Row to multiply
     * @param int  $k  divisor
     *
     * @return Matrix
     */
    public function rowDivide(int $mᵢ, $k): Matrix
    {
        if ($mᵢ >= $this->m) {
            throw new \Exception('Row to multiply does not exist');
        }
        if ($k == 0) {
            throw new \Exception('Divisor k must not be 0');
        }

        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mᵢ][$j] /= $k;
        }

        return new Matrix($R);
    }

    /**
     * Add k times row mᵢ to row mⱼ
     *
     * @param int $mᵢ Row to multiply * k to be added to row mⱼ
     * @param int $mⱼ Row that will have row mⱼ * k added to it
     * @param int $k  Multiplier
     *
     * @return Matrix
     */
    public function rowAdd(int $mᵢ, int $mⱼ, int $k): Matrix
    {
        if ($mᵢ >= $this->m || $mⱼ >= $this->m) {
            throw new \Exception('Row to interchange does not exist');
        }
        if ($k == 0) {
            throw new \Exception('Multiplication factor k must not be 0');
        }

        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mⱼ][$j] += $R[$mᵢ][$j] * $k;
        }

        return new Matrix($R);
    }

    /**
     * Add a scalar k to each item of a row
     *
     * Each element of Row mᵢ will have k added to it
     *
     * @param int  $mᵢ Row to add k to
     * @param int  $k  scalar
     *
     * @return Matrix
     */
    public function rowAddScalar(int $mᵢ, $k): Matrix
    {
        if ($mᵢ >= $this->m) {
            throw new \Exception('Row to multiply does not exist');
        }

        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mᵢ][$j] += $k;
        }

        return new Matrix($R);
    }

    /**
     * Subtract k times row mᵢ to row mⱼ
     *
     * @param int $mᵢ Row to multiply * k to be subtracted to row mⱼ
     * @param int $mⱼ Row that will have row mⱼ * k subtracted to it
     * @param number $k  Multiplier
     *
     * @return Matrix
     */
    public function rowSubtract(int $mᵢ, int $mⱼ, $k): Matrix
    {
        if ($mᵢ >= $this->m || $mⱼ >= $this->m) {
            throw new \Exception('Row to interchange does not exist');
        }


        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mⱼ][$j] -= $R[$mᵢ][$j] * $k;
        }

        return new Matrix($R);
    }

    /**
     * Subtract a scalar k to each item of a row
     *
     * Each element of Row mᵢ will have k subtracted from it
     *
     * @param int  $mᵢ Row to add k to
     * @param int  $k  scalar
     *
     * @return Matrix
     */
    public function rowSubtractScalar(int $mᵢ, int $k): Matrix
    {
        if ($mᵢ >= $this->m) {
            throw new \Exception('Row to multiply does not exist');
        }

        $n = $this->n;
        $R = $this->A;

        for ($j = 0; $j < $n; $j++) {
            $R[$mᵢ][$j] -= $k;
        }

        return new Matrix($R);
    }

    /**
     * Exclude a row from the result matrix
     *
     * @param int $mᵢ Row to exclude
     *
     * @return Matrix with row mᵢ excluded
     */
    public function rowExclude(int $mᵢ): Matrix
    {
        if ($mᵢ >= $this->m || $mᵢ < 0) {
            throw new \Exception('Row to exclude does not exist');
        }

        $m = $this->m;
        $R = [];

        for ($i = 0; $i < $m; $i++) {
            if ($i === $mᵢ) {
                continue;
            }
            $R[$i] = $this->A[$i];
        }

        return new Matrix(array_values($R));
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
     */
    public function columnInterchange(int $nᵢ, int $nⱼ): Matrix
    {
        if ($nᵢ >= $this->n || $nⱼ >= $this->n) {
            throw new \Exception('Column to interchange does not exist');
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

        return new Matrix($R);
    }

    /**
     * Multiply a column by a factor k
     *
     * Each element of column nᵢ will be multiplied by k
     *
     * @param int  $nᵢ Column to multiply
     * @param int  $k  Multiplier
     *
     * @return Matrix
     */
    public function columnMultiply(int $nᵢ, int $k): Matrix
    {
        if ($nᵢ >= $this->n) {
            throw new \Exception('Column to multiply does not exist');
        }
        if ($k == 0) {
            throw new \Exception('Multiplication factor k must not be 0');
        }

        $m = $this->m;
        $R = $this->A;

        for ($i = 0; $i < $m; $i++) {
            $R[$i][$nᵢ] *= $k;
        }

        return new Matrix($R);
    }

    /**
     * Add k times column nᵢ to column nⱼ
     *
     * @param int $nᵢ Column to multiply * k to be added to column nⱼ
     * @param int $nⱼ Column that will have column nⱼ * k added to it
     * @param int $k  Multiplier
     *
     * @return Matrix
     */
    public function columnAdd(int $nᵢ, int $nⱼ, int $k): Matrix
    {
        if ($nᵢ >= $this->n || $nⱼ >= $this->n) {
            throw new \Exception('Column to interchange does not exist');
        }
        if ($k == 0) {
            throw new \Exception('Multiplication factor k must not be 0');
        }

        $m = $this->m;
        $R = $this->A;

        for ($i = 0; $i < $m; $i++) {
            $R[$i][$nⱼ] += $R[$i][$nᵢ] * $k;
        }

        return new Matrix($R);
    }

    /**
     * Exclude a column from the result matrix
     *
     * @param int $nᵢ Column to exclude
     *
     * @return Matrix with column nᵢ excluded
     */
    public function columnExclude(int $nᵢ): Matrix
    {
        if ($nᵢ >= $this->n || $nᵢ < 0) {
            throw new \Exception('Column to exclude does not exist');
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

        return new Matrix($R);
    }

    /**************************************************************************
     * MATRIX DECOMPOSITIONS - Return a Matrix (or array of Matrices)
     *  - rref
     *  - LUDecomposition
     **************************************************************************/

    /**
     * Ruduced row echelon form (row canonical form)
     *
     * Adapted from reference algorithm: https://rosettacode.org/wiki/Reduced_row_echelon_form
     *
     * Also computes number of swaps and product of scaling factor.
     * These are used for computing the determinant.
     *
     * @return Matrix in reduced row echelon form
     */
    public function rref(): Matrix
    {
        $m = $this->m;
        $n = $this->n;
        $R = new Matrix($this->A);

        $swaps           = 0;
        $∏scaling_factor = 1;

        $lead = 0;

        for ($r = 0; $r < $m; $r++) {
            if ($lead >= $n) {
                break;
            }

            $i = $r;
            while ($R[$i][$lead] == 0) {
                $i++;
                if ($i == $m) {
                    $i = $r;
                    $lead++;
                    if ($lead == $n) {
                        break 2; // done; break out of outer loop and return.
                    }
                }
            }

            // Swap rows i and r
            $R = $R->rowInterchange($i, $r);
            $swaps++;

            // Divide row $r by R[r][lead]
            $lv = $R[$r][$lead];
            $R  = $R->rowDivide($r, $lv);
            if ($lv != 0) {
                $∏scaling_factor *= 1 / $lv;
            }

            // Subtract row r * R[r][lead] from row i
            for ($i = 0; $i < $m; $i++) {
                if ($i != $r) {
                    $R  = $R->rowSubtract($r, $i, $R[$i][$lead]);
                }
            }
            $lead++;
        }

        $this->rref                 = $R;
        $this->rref_swaps           = $swaps;
        $this->rref_∏scaling_factor = $∏scaling_factor;

        return $R;
    }

    /**
     * LU Decomposition (Crout matrix decomposition) with permutation matrix
     *
     * A matrix has an LU-factorization if it can be expressed as the product of
     * a lower-triangular matrix L and an upper triangular matrix U:
     *   PA = LU
     *
     * Crout matrix decomposition is an LU decomposition which decomposes a matrix
     * into a lower triangular matrix (L), an upper triangular matrix (U) and,
     * although not always needed, a permutation matrix (P)
     *
     * https://en.wikipedia.org/wiki/LU_decomposition
     * https://en.wikipedia.org/wiki/Crout_matrix_decomposition
     *
     * L: Lower triangular matrix--all entries above the main diagonal are zero.
     *    The main diagonal will be all ones.
     * U: Upper tirangular matrix--all entries below the main diagonal are zero.
     * P: Permutation matrix--Identity matrix with possible rows interchanged.
     *
     * @return array [
     *   L: Lower triangular matrix
     *   U: Upper triangular matrix
     *   P: Permutation matrix
     *   A: Original square matrix
     * ]
     */
    public function LUDecomposition(): array
    {
        if (!$this->isSquare()) {
            throw new \Exception('LU decomposition only works on square matrices');
        }

        $n = $this->n;

        // Initialize L and U with all zeros
        $L = Matrix::zero($n, $n)->getMatrix();
        $U = Matrix::zero($n, $n)->getMatrix();

        // Create permutation matrix P and augmented A
        $P = $this->pivotize();
        $A = $P->multiply($this);

        for ($i = 0; $i < $n; $i++) {
            $L[$i][$i] = 1;
            for ($j = 0; $j <= $i; $j++) {
                $sum = 0;
                for ($k = 0; $k < $j; $k++) {
                    $sum += $U[$k][$i] * $L[$j][$k];
                }
                $U[$j][$i] = $A[$j][$i] - $sum;
            }
            for ($j = $i; $j < $n; $j++) {
                $sum = 0;
                for ($k = 0; $k < $i; $k++) {
                    $sum += $U[$k][$i] * $L[$j][$k];
                }
                $L[$j][$i] = ($U[$i][$i] == 0) ? \NAN : ($A[$j][$i] - $sum) / $U[$i][$i];
            }
        }

        $this->L = new Matrix($L);
        $this->U = new Matrix($U);
        $this->P = $P;

        return [
            'L' => $this->L,
            'U' => $this->U,
            'P' => $this->P,
            'A' => new Matrix($this->A),
        ];
    }

    /**
     * Helper function for LU decomposition
     *
     * @return Matrix
     */
    private function pivotize(): Matrix
    {
        $n = $this->n;
        $P = Matrix::identity($n);
        $A = $this->A;

        for ($i = 0; $i < $n; $i++) {
            $max = $A[$i][$i];
            $row = $i;

            for ($j = $i; $j < $n; $j++) {
                if ($A[$j][$i] > $max) {
                    $max = $A[$j][$i];
                    $row = $j;
                }
            }

            if ($i != $row) {
                $P = $P->rowInterchange($i, $row);
            }
        }

        return $P;
    }

    /**************************************************************************
     * STATIC METHODS - Return a Matrix
     *  - identity
     *  - zero
     *  - one
     **************************************************************************/

    /**
     * Identity matrix - n x n matrix with ones in the diaganol
     * Option to set the diaganol to any number.
     *
     * @param int    $n size of matrix
     * @param number $x (optional; default 1)
     *
     * @return Matrix
     */
    public static function identity(int $n, $x = 1): Matrix
    {
        if ($n < 0) {
            throw new \Exception('n must be ≥ 0');
        }
        $R = [];

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i][$j] = $i == $j ? $x : 0;
            }
        }

        return new Matrix($R);
    }

    /**
     * Zero matrix - m x n matrix with all elements being zeros
     *
     * @param int $m rows
     * @param int $n columns
     *
     * @return Matrix
     */
    public static function zero(int $m, int $n): Matrix
    {
        if ($m < 1 || $n < 1) {
            throw new \Exception('m and n must be > 0');
        }

        $R = [];

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i][$j] = 0;
            }
        }

        return new Matrix($R);
    }

    /**
     * Ones matrix - m x n matrix with all elements being ones
     *
     * @param int $m rows
     * @param int $n columns
     *
     * @return Matrix
     */
    public static function one(int $m, int $n): Matrix
    {
        if ($m < 1 || $n < 1) {
            throw new \Exception('m and n must be > 0');
        }

        $R = [];

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i][$j] = 1;
            }
        }

        return new Matrix($R);
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

    public function offsetExists($i): bool
    {
        return isset($this->A[$i]);
    }

    public function offsetGet($i)
    {
        return $this->A[$i];
    }

    public function offsetSet($i, $value)
    {
        throw new \Exception('Matrix class does not allow setting values');
    }

    public function offsetUnset($i)
    {
        throw new \Exception('Matrix class does not allow unsetting values');
    }

    /**************************************************************************
     * JsonSerializable INTERFACE
     **************************************************************************/

    public function jsonSerialize()
    {
        return $this->A;
    }
}
