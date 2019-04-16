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
