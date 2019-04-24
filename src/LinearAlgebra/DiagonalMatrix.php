<?php
namespace MathPHP\LinearAlgebra;

/**
 * Diagonal matrix
 * Elements along the main diagonal are the only non-zero elements (may also be zero).
 * The off-diagonal elements are all zero
 */
class DiagonalMatrix extends SquareMatrix
{
    /**
     * A Diagonal Matrix is constructed from a single-row array.
     * The elements of this array are placed on the diagonal of a
     * square matrix.
     */
    public function __construct(array $D)
    {
        $m = count($D);

        $A = [];
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $m; $j++) {
                if ($i == $j) {
                    $A[$i][$j] = $D[$i];
                } else {
                    $A[$i][$j] = 0;
                }
            }
        }

        parent::__construct($A);
    }

    /**
     * Diagonal matrix must be symmetric
     * @inheritDoc
     */
    public function isSymmetric(): bool
    {
        return true;
    }

    /**
     * Diagonal matrix must be lower triangular
     * @inheritDoc
     */
    public function isLowerTriangular(): bool
    {
        return true;
    }

    /**
     * Diagonal matrix must be upper triangular
     * @inheritDoc
     */
    public function isUpperTriangular(): bool
    {
        return true;
    }

    /**
     * Diagonal matrix must be triangular
     * @inheritDoc
     */
    public function isTriangular(): bool
    {
        return true;
    }

    /**
     * Diagonal matrix must be diagonal
     * @inheritDoc
     */
    public function isDiagonal(): bool
    {
        return true;
    }
}
