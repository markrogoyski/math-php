<?php

namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

trait SquareMatrixTrait
{

    /**************************************************************************
     * MATRIX OPERATIONS - Return a Matrix
     *  - minorMatrix
     *  - leadingPrincipleMinor
     **************************************************************************/

    /**
     * Minor matrix
     * Submatrix formed by deleting the iᵗʰ row and jᵗʰ column.
     * Used in computing the minor Mᵢⱼ.
     *
     * @param int $mᵢ Row to exclude
     * @param int $nⱼ Column to exclude
     *
     * @return Matrix with row mᵢ and column nⱼ removed
     *
     * @throws Exception\MatrixException if row to exclude for minor matrix does not exist
     * @throws Exception\MatrixException if column to exclude for minor matrix does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function minorMatrix(int $mᵢ, int $nⱼ): Matrix
    {
        if ($mᵢ >= $this->m || $mᵢ < 0) {
            throw new Exception\MatrixException('Row to exclude for minor Matrix does not exist');
        }
        if ($nⱼ >= $this->n || $nⱼ < 0) {
            throw new Exception\MatrixException('Column to exclude for minor Matrix does not exist');
        }

        return $this->rowExclude($mᵢ)->columnExclude($nⱼ);
    }

    /**
     * Leading principal minor
     * The leading principal minor of A of order k is the minor of order k
     * obtained by deleting the last n − k rows and columns.
     *
     * Example:
     *
     *     [1 2 3]
     * A = [4 5 6]
     *     [7 8 9]
     *
     * 1st order (k = 1): [1]
     *
     *                    [1 2]
     * 2nd order (k = 2): [4 5]
     *
     *                    [1 2 3]
     * 3rd order (k = 3): [4 5 6]
     *                    [7 8 9]
     *
     * @param  int $k Order of the leading principal minor
     *
     * @return Matrix
     *
     * @throws Exception\OutOfBoundsException if k ≤ 0
     * @throws Exception\OutOfBoundsException if k > n
     * @throws Exception\IncorrectTypeException
     */
    public function leadingPrincipalMinor(int $k): Matrix
    {
        if ($k <= 0) {
            throw new Exception\OutOfBoundsException("k is ≤ 0: $k");
        }
        if ($k > $this->n) {
            throw new Exception\OutOfBoundsException("k ($k) leading principal minor is larger than size of Matrix: " . $this->n);
        }

        $R = [];
        for ($i = 0; $i < $k; $i++) {
            for ($j = 0; $j < $k; $j++) {
                $R[$i][$j] = $this->A[$i][$j];
            }
        }

        return MatrixFactory::create($R);
    }
}
