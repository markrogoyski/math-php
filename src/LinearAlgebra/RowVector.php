<?php

namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

/**
 * Row vector (row matrix)
 * 1 × n matrix consisting of a single row of n elements.
 *
 * x = [x₁ x₂ ⋯ xn]
 */
class RowVector extends NumericMatrix
{
    /**
     * Allows the creation of a RowVector (1 × n Matrix) from an array
     * instead of an array of arrays.
     *
     * @param array $N 1-dimensional array of vector values
     *
     * @throws Exception\BadDataException
     */
    public function __construct(array $N)
    {
        $this->validateRowVectorDimensions($N);

        $A = [$N];
        parent::__construct($A);
    }

    /**
     * Validate the matrix is provided as a one-dimensional array
     *
     * @param array $N
     *
     * @throws Exception\BadDataException
     */
    protected function validateRowVectorDimensions(array $N)
    {
        foreach ($N as $item) {
            if (\is_array($item)) {
                throw new Exception\BadDataException('Row vector data must be a one-dimensional array');
            }
        }
    }

    /**
     * Transpose
     * The transpose of a row vector is a column vector
     *
     *                 [x₁]
     * [x₁ x₂ ⋯ xn]ᵀ = [x₂]
     *                 [⋮ ]
     *                 [xn]
     *
     * @return ColumnVector
     *
     * @throws \MathPHP\Exception\MatrixException
     */
    public function transpose(): ColumnVector
    {
        return new ColumnVector($this->getRow(0));
    }
}
