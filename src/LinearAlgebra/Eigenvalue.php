<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;
use MathPHP\Functions\Polynomial;

class Eigenvalue
{
    const CLOSED_FORM_POLYNOMIAL_ROOT_METHOD = 'closedFormPolynomialRootMethod';

    const METHODS = [
        self::CLOSED_FORM_POLYNOMIAL_ROOT_METHOD,
    ];

    /**
     * Is the provided algorithm a valid eigenvalue method?
     *
     * @param  string  $method
     *
     * @return boolean true if a valid method; false otherwise
     */
    public static function isAvailableMethod(string $method): bool
    {
        return in_array($method, self::METHODS);
    }

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
    public static function closedFormPolynomialRootMethod(Matrix $A): array
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
        $λ_poly    = new Polynomial([1, 0], 'λ');
        $zero_poly = new Polynomial([0], 'λ');
        $λ_array   = [];
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $m; $j++) {
                $λ_array[$i][$j] = ($i == $j)
                    ? $λ_poly
                    : $zero_poly;
            }
        }
        $λ = MatrixFactory::create($λ_array);
        
        //Subtract Iλ from B
        $Bminusλ = $B->subtract($λ);

        // The Eigenvalues are the roots of the determinant of this matrix
        $det = $Bminusλ->det();
        
        // Calculate the roots of the determinant.
        $eigenvalues = $det->roots();
        return $eigenvalues;
    }
    
    /**
     * JK Method
     *
     * Calculate Eigenvalues of a symmetric matrix.
     *
     * @param Matrix $A
     *
     * @return array of eigenvalues
     *
     * @throws Exception\BadDataException if the matrix is not symmetric or is 1x1.
     */
    public static function JKMethod(Matrix $A): array
    {
        $originalA = $A;
        if (!$A->isSymmetric()) {
            throw new Exception\BadDataException('Matrix must be symmetric');
        }
        $m = $A->getM();
        if ($m < 2) {
            throw new Exception\BadDataException("Matrix must be 2x2 or larger");
        }

        $num_zero = 0;
        $iterationCount = 0;
        $ε = 1E-16;
        $Ematrix = [];
        while ($num_zero < $m * ($m - 1) / 2 && $iterationCount < 100) {
            for ($i = 0; $i < $m - 1; $i++) {
                for ($j = $i + 1; $j < $m; $j++) {
                    $num = 0;
                    $den = 0;

                    $x = $A->getColumn($i);
                    $y = $A->getColumn($j);
                    $num = 2 * array_sum(Multi::multiply($x, $y));
                    $den = array_sum(Multi::subtract(Single::square($x), Single::square($y)));

                    if (abs($num) > $ε || abs($den) > $ε) {
                        if (abs($num) <= abs($den)) {
                            $tan2θ = abs($num) / abs($den);
                            $cos2θ = 1 / sqrt(1 + $tan2θ ** 2);
                            $sin2θ = $tan2θ * $cos2θ;
                        } else {
                            $cot2θ = abs($den) / abs($num);
                            $sin2θ = 1 / sqrt(1 + $cot2θ ** 2);
                            $cos2θ = $cot2θ * $sin2θ;
                        }
                
                        $cosθ = sqrt((1 + $cos2θ) / 2);
                        $sinθ = $sin2θ / 2 / $cosθ;
                
                        $cosφ = $den > 0 ? $cosθ : $sinθ;
                        $sinφ = $den > 0 ? $sinθ : $cosθ;
                
                        $sinφ = Special::sgn($num) * $sinφ;

                        $x = $A->submatrix(0, $i, $m - 1, $i);
                        $y = $A->submatrix(0, $j, $m - 1, $j);
                        $submatrix = $x->augment($y);
                        $transformArray = [
                            [$cosφ, -1 * $sinφ],
                            [$sinφ, $cosφ],
                        ];

                        $transform = MatrixFactory::create($transformArray);
                        $newColumns = $submatrix->multiply($transform);
                        $A = self::replaceColumn($A, $newColumns->getColumn(0), $i);
                        $A = self::replaceColumn($A, $newColumns->getColumn(1), $j);
                    }
                }
            }

            if (abs($num) < $ε) {
                $num_zero++;
            } else {
                $num_zero = 0;
            }
            $iterationCount++;
        }
    
        if ($iterationCount == 100) {
             throw new Exception\BadDataException("Eigenvalues not found");
        }
        // $A is now a matrix of eigenvectors in each column
        $eigenvalues = [];
        for ($i = 0; $i < $m; $i++) {
            $eigenvalues[] = sqrt(array_sum(Single::square($A->getColumn($i))));
        }
        $λ³ = $A
            ->transpose()
            ->multiply($originalA)
            ->multiply($A)
            ->getDiagonalElements();
        // The PHP pow() function does not work on negative numbers so
        // we need to be more complicated.
        $absλ³ = Single::abs($λ³);
        $sgnλ = Multi::divide($λ³, $absλ³);
        $absλ = Single::pow($absλ³, 1/3);
        $λ = Multi::multiply($sgnλ, $absλ);
        
        return $λ;
    }

    /**
     * Replaces a column in a matrix with values from an array
     *
     * @param Matrix $matrix
     * @param array $array of new values
     * @param int $column
     *
     * @returns Matrix with new values in the specified column
     */
    private static function replaceColumn(Matrix $matrix, array $array, int $column): Matrix
    {
        $A = $matrix->getMatrix();
        $m = $matrix->getM();
        for ($i = 0; $i < $m; $i++) {
            $A[$i][$column] = $array[$i];
        }
        return MatrixFactory::create($A);
    }
}
