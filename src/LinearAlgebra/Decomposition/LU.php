<?php

namespace MathPHP\LinearAlgebra\Decomposition;

use MathPHP\Exception;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;

class LU
{
    /** @var Matrix Lower matrix in LUP decomposition */
    protected $L;

    /** @var Matrix Upper matrix in LUP decomposition */
    protected $U;

    /** @var Matrix Permutation matrix in LUP decomposition */
    protected $P;

    private function __construct(Matrix $L, Matrix $U, Matrix $P)
    {
        $this->L = $L;
        $this->U = $U;
        $this->P = $P;
    }

    /**
     * Get L
     *
     * @return Matrix
     */
    public function getL(): Matrix
    {
        return $this->L;
    }

    /**
     * Get U
     *
     * @return Matrix
     */
    public function getU(): Matrix
    {
        return $this->U;
    }

    /**
     * Get P
     *
     * @return Matrix
     */
    public function getP(): Matrix
    {
        return $this->P;
    }

    /**
     * LU Decomposition (Doolittle decomposition) with pivoting via permutation matrix
     *
     * A matrix has an LU-factorization if it can be expressed as the product of a
     * lower-triangular matrix L and an upper-triangular matrix U. If A is a nonsingular
     * matrix, then we can find a permutation matrix P so that PA will have an LU decomposition:
     *   PA = LU
     *
     * https://en.wikipedia.org/wiki/LU_decomposition
     * https://en.wikipedia.org/wiki/LU_decomposition#Doolittle_algorithm
     *
     * L: Lower triangular matrix--all entries above the main diagonal are zero.
     *    The main diagonal will be all ones.
     * U: Upper tirangular matrix--all entries below the main diagonal are zero.
     * P: Permutation matrix--Identity matrix with possible rows interchanged.
     *
     * Example:
     *      [1 3 5]
     *  A = [2 4 7]
     *      [1 1 0]
     *
     * Create permutation matrix P:
     *      [0 1 0]
     *  P = [1 0 0]
     *      [0 0 1]
     *
     * Pivot A to be PA:
     *       [0 1 0][1 3 5]   [2 4 7]
     *  PA = [1 0 0][2 4 7] = [1 3 5]
     *       [0 0 1][1 1 0]   [1 1 0]
     *
     * Calculate L and U
     *
     *     [1    0 0]      [2 4   7]
     * L = [0.5  1 0]  U = [0 1 1.5]
     *     [0.5 -1 1]      [0 0  -2]
     *
     * @return Matrix[] [
     *   L: Lower triangular matrix
     *   U: Upper triangular matrix
     *   P: Permutation matrix
     * ]
     *
     * @throws Exception\MatrixException if matrix is not square
     * @throws Exception\IncorrectTypeException
     * @throws Exception\OutOfBoundsException
     * @throws Exception\VectorException
     */
    public static function decompose(Matrix $A): LU
    {
        if (!$A->isSquare()) {
            throw new Exception\MatrixException('LU decomposition only works on square matrices');
        }

        $n = $A->getN();

        // Initialize L as diagonal ones matrix, and U as zero matrix
        $L = MatrixFactory::diagonal(array_fill(0, $n, 1))->getMatrix();
        $U = MatrixFactory::zero($n, $n)->getMatrix();

        // Create permutation matrix P and pivoted PA
        $P  = self::pivotize($A);
        $PA = $P->multiply($A);

        // Fill out L and U
        for ($i = 0; $i < $n; $i++) {
            // Calculate Uⱼᵢ
            for ($j = 0; $j <= $i; $j++) {
                $sum = 0;
                for ($k = 0; $k < $j; $k++) {
                    $sum += $U[$k][$i] * $L[$j][$k];
                }
                $U[$j][$i] = $PA[$j][$i] - $sum;
            }

            // Calculate Lⱼᵢ
            for ($j = $i; $j < $n; $j++) {
                $sum = 0;
                for ($k = 0; $k < $i; $k++) {
                    $sum += $U[$k][$i] * $L[$j][$k];
                }
                $L[$j][$i] = ($U[$i][$i] == 0) ? \NAN : ($PA[$j][$i] - $sum) / $U[$i][$i];
            }
        }

        // return the object
        return new LU(MatrixFactory::create($L), MatrixFactory::create($U), $P);
    }

    /**
     * Pivotize creates the permutation matrix P for the LU decomposition.
     * The permutation matrix is an identity matrix with rows possibly interchanged.
     *
     * The product PA results in a new matrix whose rows consist of the rows of A
     * but no rearranged in the order specified by the permutation matrix P.
     *
     * Example:
     *
     *     [α₁₁ α₁₂ α₁₃]
     * A = [α₂₁ α₂₂ α₂₃]
     *     [α₃₁ α₃₂ α₃₃]
     *
     *     [0 1 0]
     * P = [1 0 0]
     *     [0 0 1]
     *
     *      [α₂₁ α₂₂ α₂₃] \ rows
     * PA = [α₁₁ α₁₂ α₁₃] / interchanged
     *      [α₃₁ α₃₂ α₃₃]
     *
     * @return Matrix
     *
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException
     */
    protected static function pivotize(Matrix $A): Matrix
    {
        $n = $A->getN();
        $P = MatrixFactory::identity($n);

        // Set initial column max to diagonal element Aᵢᵢ
        for ($i = 0; $i < $n; $i++) {
            $max = $A[$i][$i];
            $row = $i;

            // Check for column element below Aᵢᵢ that is bigger
            for ($j = $i; $j < $n; $j++) {
                if ($A[$j][$i] > $max) {
                    $max = $A[$j][$i];
                    $row = $j;
                }
            }

            // Swap rows if a larger column element below Aᵢᵢ was found
            if ($i != $row) {
                $P = $P->rowInterchange($i, $row);
            }
        }
        return $P;
    }
}
