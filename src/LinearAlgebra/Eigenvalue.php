<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Algebra;
use MathPHP\Exception;
use MathPHP\Functions\Polynomial;
use MathPHP\Functions\Map\Single;
use MathPHP\Functions\Special;

class Eigenvalue
{
    /**
     * Produces the Eigenvalues for square 2x2 - 4x4 matricies
     *
     * Given a matrix
     *      [a b]
     * A =  [c d]
     *
     * Find all λ such that:
     *      |A-Iλ| = 0
     *
     * This is accomplished by finding the roots of the polyniomial that
     * is produced when computing the determinant of the matrix. The determinant
     * polynomial is calculated using polynomial arithmetic.
     *
     * @param Matrix $A
     *
     * @return array of eigenvalues
     *
     * @throws Exception\BadDataException if the matrix is not square
     * @throws Exception\BadDataException if the matrix is not 2x2, 3x3, or 4x4
     */
    public static function eigenvalue(Matrix $A): array
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

        // Create a diagonal Matrix of lambda (Iλ)
        $λ_poly = new Polynomial([1, 0], 'λ');
        $zero_poly = new Polynomial([0], 'λ');
        $λ_array =[];
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $m; $j++) {
                $λ_array[$i][$j] = $i == $j ? $λ_poly : $zero_poly;
            }
        }
        $λ = matrixFactory::create($λ_array);
        
        //Subtract Iλ from B
        $Bminusλ = $B->subtract($λ);

        // The Eigenvalues are the roots of the determinant of this matrix
        $det = $Bminusλ->det();
        
        // Calculate the roots of the determinant.
        $eigenvalues = $det->roots();
        return $eigenvalues;
    }

    /**
     * Calculate the Eigenvectors for a matrix
     *
     * Eigenvectors are vectors whos direction is unchaged after
     * the application of a transformation matrix.
     *
     * The results from this function are column unit vectors with the first
     * element being positive.
     *
     * @return Matrix of eigenvectors
     */
    public static function eigenvector(Matrix $A): Matrix
    {
        $eigenvalues = self::eigenvalue($A);
        $number = count($eigenvalues);
        $M = [];
        foreach ($eigenvalues as $eigenvalue) {
            $I = MatrixFactory::identity($number, $eigenvalue);
            $T = $A->subtract($I);
            $valid_minor = false;
            // Since by definition, the determinant of this matrix is zero, we cannot
            // solve the system of equations. Instead, we will find the unnecessary row
            // remove it, and set one of the variables to 1, and then solve for the
            // remaining variables.
            for ($i=0; $i<$number && !$valid_minor; $i++) {
                for ($j=0; $j<$number && !$valid_minor; $j++) {
                    $minor = $T->minorMatrix($i, $j);
                    if ($minor->isInvertible()) {
                        $valid_minor = true;
                        $solution = Single::multiply($T->rowExclude($i)->getColumn($j), -1);
                        $eigenvector = $minor->solve($solution)->getVector();
                        array_splice($eigenvector, $j, 0, 1);
                        // Last step is to scale the vector to be a unit vector.
                        $scale_factor = Special::sgn($eigenvector[0]) / sqrt(array_sum(Single::square($eigenvector)));
                        $eigenvector = Single::multiply($eigenvector, $scale_factor);
                        $M[] = $eigenvector;
                    }
                }
            }
        }
        $matrix = MatrixFactory::create($M);
        return $matrix->transpose();
    }
}
