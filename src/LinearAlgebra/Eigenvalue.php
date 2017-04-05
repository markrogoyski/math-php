<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Algebra;
use MathPHP\Exception;

class Eigenvalue
{
    /**
     * Produces the Eigenvalues for a 2x2 or 3x3 matrix
     *
     * Given a matrix
     *      [a b]
     * A =  [c d]
     *
     * Find λ₁ and λ₂ such that the determinant of:
     *      [a-λ, b   ]
     *      [c,    d-λ] = 0
     *
     * or ad - λ(a+d) + λ² - cb = 0
     *
     * @param Matrix $A
     *
     * @return DiagonalMatrix of eigenvalues
     *
     * @throws Exception\BadDataException if the matrix is not square
     * @throws Exception\BadDataException if the matrix is not 2x2 or 3x3
     */
    public static function quadratic(Matrix $A): DiagonalMatrix
    {
        if (!$A->isSquare()) {
            throw new Exception\BadDataException('Matrix must be square');
        }

        $m = $A->getM();
        if ($m < 2 || $m > 3) {
            throw new Exception\BadDataException("Matrix must be 2x2 or 3x3. $m x $m given");
        }

        $A = $A->getMatrix();

        if ($m === 2) {
            $a = -1;
            $b = $A[0][0] + $A[1][1];
            $c = $A[1][0] * $A[0][1] - $A[0][0] * $A[1][1];
            $eigenvalues = Algebra::quadratic($a, $b, $c);
        } else {
            $a  = $A[0][0];
            $b  = $A[0][1];
            $c  = $A[0][2];
            $d  = $A[1][0];
            $e  = $A[1][1];
            $f  = $A[1][2];
            $g  = $A[2][0];
            $h  = $A[2][1];
            $i  = $A[2][2];
            $qa = -1;
            $qb = $a + $e + $i;
            $qc = $c * $g + $h * $f + $d * $b - $a * $e - $a * $i - $e * $i;
            $qd = $a * $e * $i + $b * $f * $g + $c * $d * $h - $g * $c * $e - $h * $f * $a - $d * $b * $i;
            $eigenvalues = Algebra::cubic($qa, $qb, $qc, $qd);
        }

        return MatrixFactory::create($eigenvalues);
    }
}
