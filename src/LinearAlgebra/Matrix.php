<?php
namespace Math\LinearAlgebra;

/**
 * m x n Matrix
 */
class Matrix implements \ArrayAccess
{
    /**
     * Number of rows
     * @var int
     */
    private $m;

    /**
     * Number of rows
     * @var int
     */
    private $n;

    /**
     * Matrix
     * @var array of arrays
     */
    private $A;

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
            for($j = 0; $j < $this->n; $j++) {
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
            for($j = 0; $j < $this->n; $j++) {
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
            for ($j = 0; $j < $this->n; $i++) {
                $R[$i][$j] = $this->A[$i][$j] * $λ;
            }
        }

        return new Matrix($R);
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
     * Is the matrix a square matrix?
     * Do rows m = columns n?
     *
     * @return boolean
     */
    public function isSquare(): bool
    {
        return $this->m == $this->n;
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
    public function map(Callable $func): Matrix
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

    // Static methods
    
    /**
     * Identity matrix - n x n matrix with ones in the diaganol
     * Option to set the diaganol to any number.
     *
     * @param int    $n size of matrix
     * @param number $x (optional; default 1)
     *
     * @return Matrix
     */
    public static function identity(int $n, $x = 1)
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

    // ArrayAccess Interface

    public function offsetExists($i): boolean
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
}
