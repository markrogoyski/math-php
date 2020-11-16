<?php

namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

trait SquareMatrixTrait
{

    /**************************************************************************
     * MATRIX OPERATIONS - Return a Matrix
     *  - inverse
     *  - minorMatrix
     *  - leadingPrincipleMinor
     **************************************************************************/

    /**
     * Inverse
     *
     * For a 1x1 matrix
     *  A   = [a]
     *  A⁻¹ = [1/a]
     *
     * For a 2x2 matrix:
     *      [a b]
     *  A = [c d]
     *
     *         1
     *  A⁻¹ = --- [d -b]
     *        │A│ [-c a]
     *
     * For a 3x3 matrix or larger:
     * Augment with identity matrix and calculate reduced row echelon form.
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if not a square matrix
     * @throws Exception\MatrixException if singular matrix
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     * @throws Exception\OutOfBoundsException
     */
    public function inverse(): Matrix
    {
        if ($this->catalog->hasInverse()) {
            return $this->catalog->getInverse();
        }

        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Not a sqaure matrix (required for determinant)');
        }
        if ($this->isSingular()) {
            throw new Exception\MatrixException('Singular matrix (determinant = 0); not invertible');
        }

        $m   = $this->m;
        $n   = $this->n;
        $A   = $this->A;
        $│A│ = $this->det();

         // 1x1 matrix: A⁻¹ = [1 / a]
        if ($m === 1) {
            $a   = $A[0][0];
            $A⁻¹ = MatrixFactory::create([[1 / $a]]);
            $this->catalog->addInverse($A⁻¹);
            return $A⁻¹;
        }

        /*
         * 2x2 matrix:
         *      [a b]
         *  A = [c d]
         *
         *        1
         * A⁻¹ = --- [d -b]
         *       │A│ [-c a]
         */
        if ($m === 2) {
            $a = $A[0][0];
            $b = $A[0][1];
            $c = $A[1][0];
            $d = $A[1][1];

            $R = MatrixFactory::create([
                [$d, -$b],
                [-$c, $a],
            ]);
            $A⁻¹ = $R->scalarMultiply(1 / $│A│);

            $this->catalog->addInverse($A⁻¹);
            return $A⁻¹;
        }

        // nxn matrix 3x3 or larger
        $R   = $this->augmentIdentity()->rref();
        $A⁻¹ = [];

        for ($i = 0; $i < $n; $i++) {
            $A⁻¹[$i] = array_slice($R[$i], $n);
        }

        $A⁻¹ = MatrixFactory::create($A⁻¹);

        $this->catalog->addInverse($A⁻¹);
        return $A⁻¹;
    }

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
     * @throws Exception\MatrixException if matrix is not square
     * @throws Exception\MatrixException if row to exclude for minor matrix does not exist
     * @throws Exception\MatrixException if column to exclude for minor matrix does not exist
     * @throws Exception\IncorrectTypeException
     */
    public function minorMatrix(int $mᵢ, int $nⱼ): Matrix
    {
        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Matrix is not square; cannot get minor Matrix of a non-square matrix');
        }
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
     * @throws Exception\MatrixException if matrix is not square
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
        if (!$this->isSquare()) {
            throw new Exception\MatrixException('Matrix is not square; cannot get leading principal minor Matrix of a non-square matrix');
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
