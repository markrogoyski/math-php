<?php

namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

/**
 * Column vector (column matrix)
 * m × 1 matrix consisting of a single column of m elements.
 *
 *     [x₁]
 * x = [x₂]
 *     [⋮ ]
 *     [xm]
 */
class ColumnVector extends NumericMatrix
{
    /**
     * Allows the creation of a ColumnVector (m × 1 Matrix) from an array
     * instead of an array of arrays.
     *
     * @param array $M 1-dimensional array of vector values
     */
    public function __construct(array $M)
    {
        $this->validateColumnVectorDimensions($M);

        $A = [];
        foreach ($M as $value) {
            $A[] = [$value];
        }

        parent::__construct($A);
    }

    /**
     * Validate the matrix is provided as a one-dimensional array
     *
     * @param array $N
     *
     * @throws Exception\BadDataException
     */
    protected function validateColumnVectorDimensions(array $M)
    {
        foreach ($M as $item) {
            if (\is_array($item)) {
                throw new Exception\BadDataException('Row vector data must be a one-dimensional array');
            }
        }
    }

    /**
     * Transpose
     * The transpose of a column vector is a row vector
     *
     * [x₁]ᵀ
     * [x₂]  = [x₁ x₂ ⋯ xm]
     * [⋮ ]
     * [xm]
     *
     * @return RowVector
     *
     * @throws \MathPHP\Exception\MatrixException
     */
    public function transpose(): RowVector
    {
        return new RowVector($this->getColumn(0));
    }
}
