<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Algebra;
use MathPHP\Exception;
use MathPHP\Functions\Polynomial;

class Eigenvalue
{
    /**
     * Produces the Eigenvalues for a 2x2, 3x3, or 4x4 matrix
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
     * @throws Exception\BadDataException if the matrix is not 2x2, 3x3, or 4x4
     */
    public static function quadratic(Matrix $A): DiagonalMatrix
    {
        if (!$A->isSquare()) {
            throw new Exception\BadDataException('Matrix must be square');
        }

        $m = $A->getM();
        if ($m < 2 || $m > 4) {
            throw new Exception\BadDataException("Matrix must be 2x2, 3x3, or 4x4. $m x $m given");
        }
        
        // Convert the numerical matrix into an ObjectMatrix
        $B_array = [];
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $m; $j++) {
                $B_array[$i][$j] = new Polynomial([$A[$i][$j]], 'λ');
            }
        }
        $B = MatrixFactory::create($B_array);

        // Create a diagonal Matrix of lambda and subtract it from B
        $λ_poly = new Polynomial([1, 0], 'λ');
        $λ = matrixFactory::create(array_fill(0, $m, $λ_poly));
        $Bminusλ = $B->subtract($λ);

        // The Eigenvalues are the roots of the determinant of this matrix
        $det = $Bminusλ->det();
        
        // Calculate the roots of the determinant and convert into a diagonal matrix
        $eigenvalues = MatrixFactory::create($det->roots());
        return $eigenvalues;
    }
}
