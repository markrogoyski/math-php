<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

/**
 * Square matrix
 * Number of rows = number of columns
 * 1x1, 2x2, 3x3, etc.
 */
class SquareMatrix extends Matrix
{
    /**
     * Constructor
     *
     * @param array $A
     *
     * @throws Exception\MathException
     */
    public function __construct(array $A)
    {
        $this->A = $A;
        $this->m = count($A);
        $this->n = $this->m > 0 ? count($A[0]) : 0;

        if ($this->m !== $this->n) {
            throw new Exception\MatrixException('Not a square matrix; row count and column count differ');
        }
    }

    /**
     * Square matrix must be square
     *
     * @return bool
     */
    public function isSquare(): bool
    {
        return true;
    }
}
