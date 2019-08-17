<?php

namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

/**
 * m x n Matrix
 */
abstract class MatrixBase implements \ArrayAccess, \JsonSerializable
{
    /** @var int Number of rows */
    protected $m;
    
    /** @var int Number of columns */
    protected $n;
    
    /** @var array Matrix array of arrays */
    protected $A;

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

    /**************************************************************************
     * MATRIX PROPERTIES
     *  - isSquare
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

    /**************************************************************************
     * MATRIX OPERATIONS - Return a Matrix
     *  - transpose
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
     * @return MatrixInterface
     *
     * @throws Exception\MatrixException
     * @throws Exception\IncorrectTypeException
     */
    public function transpose(): MatrixInterface
    {
        $Aᵀ = [];
        for ($i = 0; $i < $this->n; $i++) {
            $Aᵀ[$i] = $this->getColumn($i);
        }
        return MatrixFactory::create($Aᵀ);
    }

    /**************************************************************************
     * ROW OPERATIONS - Return a Matrix
     *  - rowInterchange
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
     * @return MatrixInterface with rows mᵢ and mⱼ interchanged
     *
     * @throws Exception\MatrixException if row to interchange does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function rowInterchange(int $mᵢ, int $mⱼ): MatrixInterface
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
     * Exclude a column from the result matrix
     *
     * @param int $nᵢ Column to exclude
     *
     * @return MatrixInterface with column nᵢ excluded
     *
     * @throws Exception\MatrixException if column to exclude does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function columnExclude(int $nᵢ): MatrixInterface
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

    /**
     * Exclude a row from the result matrix
     *
     * @param int $mᵢ Row to exclude
     *
     * @return MatrixInterface with row mᵢ excluded
     *
     * @throws Exception\MatrixException if row to exclude does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function rowExclude(int $mᵢ): MatrixInterface
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
     * @return MatrixInterface with columns nᵢ and nⱼ interchanged
     *
     * @throws Exception\MatrixException if column to interchange does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function columnInterchange(int $nᵢ, int $nⱼ): MatrixInterface
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

    /**************************************************************************
     * MATRIX OPERATIONS - Return a Matrix
     *  - directSum
     *  - augment
     *  - augmentBelow
     *  - augmentAbove
     *  - augmentLeft
     *  - submatrix
     **************************************************************************/

    /**
     * Direct sum of two matrices: A ⊕ B
     * The direct sum of any pair of matrices A of size m × n and B of size p × q
     * is a matrix of size (m + p) × (n + q)
     * https://en.wikipedia.org/wiki/Matrix_addition#Direct_sum
     *
     * @param  Matrix $B Matrix to add to this matrix
     *
     * @return MatrixInterface
     *
     * @throws Exception\IncorrectTypeException
     */
    public function directSum(MatrixInterface $B): MatrixInterface
    {
        if ($B->getObjectType() !== $this->getObjectType()) {
            throw new Exception\MatrixException('Matrices must be the same type');
        }
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
     * @return MatrixInterface
     *
     * @throws Exception\MatrixException if matrices do not have the same number of rows
     * @throws Exception\IncorrectTypeException
     */
    public function augmentLeft(MatrixInterface $B): MatrixInterface
    {
        if ($B->getM() !== $this->m) {
            throw new Exception\MatrixException('Matrices to augment do not have the same number of rows');
        }
        if ($B->getObjectType() !== $this->getObjectType()) {
            throw new Exception\MatrixException('Matrices must be the same type');
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
     * @return MatrixInterface
     *
     * @throws Exception\MatrixException if matrices do not have the same number of columns
     * @throws Exception\IncorrectTypeException
     */
    public function augmentBelow(MatrixInterface $B): MatrixInterface
    {
        if ($B->getN() !== $this->n) {
            throw new Exception\MatrixException('Matrices to augment do not have the same number of columns');
        }
        if ($B->getObjectType() !== $this->getObjectType()) {
            throw new Exception\MatrixException('Matrices must be the same type');
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
     * @return MatrixInterface
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     */
    public function augmentAbove(MatrixInterface $B): MatrixInterface
    {
        if ($B->getN() !== $this->n) {
            throw new Exception\MatrixException('Matrices to augment do not have the same number of columns');
        }
        if ($B->getObjectType() !== $this->getObjectType()) {
            throw new Exception\MatrixException('Matrices must be the same type');
        }

        $⟮A∣B⟯ = array_merge($B->getMatrix(), $this->A);

        return MatrixFactory::create($⟮A∣B⟯);
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
     * @return MatrixInterface
     *
     * @throws Exception\MatrixException if matrices do not have the same number of rows
     * @throws Exception\IncorrectTypeException
     */
    public function augment(MatrixInterface $B): MatrixInterface
    {
        if ($B->getM() !== $this->m) {
            throw new Exception\MatrixException('Matrices to augment do not have the same number of rows');
        }
        if ($B->getObjectType() !== $this->getObjectType()) {
            throw new Exception\MatrixException('Matrices must be the same type');
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
     * Submatrix
     *
     * Return an arbitrary subset of a Matrix as a new Matrix.
     *
     * @param int $m₁ Starting row
     * @param int $n₁ Starting column
     * @param int $m₂ Ending row
     * @param int $n₂ Ending column
     *
     * @return MatrixInterface
     *
     * @throws Exception\MatrixException
     */
    public function submatrix(int $m₁, int $n₁, int $m₂, int $n₂): MatrixInterface
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
    
    abstract public function getObjectType(): string;

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
}
