<?php
namespace MathPHP\LinearAlgebra;

/**
 * Column vector (column matrix)
 * m × 1 matrix consisting of a single column of m elements.
 *
 *     [x₁]
 * x = [x₂]
 *     [⋮ ]
 *     [xm]
 */
class ColumnVector extends Matrix
{
    /**
     * Allows the creation of a ColumnVector (m × 1 Matrix) from an array
     * instead of an array of arrays.
     *
     * @param array $M 1-dimensional array of vector values
     */
    public function __construct(array $M)
    {
        $this->n = 1;
        $this->m = count($M);

        $A = [];
        foreach ($M as $value) {
            $A[] = [$value];
        }
        $this->A = $A;
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
