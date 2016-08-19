<?php
namespace Math\LinearAlgebra;

/**
 * Diagonal matrix
 * Elements along the main diagonal are the only non-zero elements (may also be zero).
 * The off-diagonal elements are all zero
 */
class DiagonalMatrix extends SquareMatrix
{
    /**
     * A Diagonal Matrix is constucted from a single-row array.
     * The elements of this array are placed on the diagonal of a
     * square matrix.
     */
    public function __construct(array $D)
    {
        $this->m = count($D);
        $this->n = $this->m;

        $A = [];
        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->m; $j++) {
                if ($i == $j) {
                    $A[$i][$j] = $D[$i];
                } else {
                    $A[$i][$j] = 0;
                }
            }
        }
        $this->A = $A;
    }

    /**
     * Diagonal matrix must be symmetric
     *
     * @return bool
     */
    public function isSymmetric(): bool
    {
        return true;
    }
}
