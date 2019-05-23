<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;
use MathPHP\Functions\Map\Multi;
use MathPHP\Functions\Map\Single;
use MathPHP\Functions\Polynomial;
use MathPHP\Functions\Special;

class Eigenvalue
{
    const CLOSED_FORM_POLYNOMIAL_ROOT_METHOD = 'closedFormPolynomialRootMethod';
    const JK_METHOD = 'JKMethod';

    const METHODS = [
        self::CLOSED_FORM_POLYNOMIAL_ROOT_METHOD,
        self::JK_METHOD,
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
     * @return float[] of eigenvalues
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
        /** @var ObjectSquareMatrix $λ */
        $λ = MatrixFactory::create($λ_array);

        /** @var ObjectSquareMatrix Subtract Iλ from B */
        $Bminusλ = $B->subtract($λ);

        /** @var Polynomial The Eigenvalues are the roots of the determinant of this matrix */
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
     * @param int $iter the maximum number of iterations
     *
     * @return array of eigenvalues
     *
     * @throws Exception\BadDataException if the matrix is not symmetric or is 1x1.
     */
    public static function JKMethod(Matrix $A, int $iter = 100): array
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
        $ε = 1E-14;
        while ($num_zero < $m * ($m - 1) / 2 && $iterationCount < $iter) {
            for ($i = 0; $i < $m - 1; $i++) {
                for ($j = $i + 1; $j < $m; $j++) {
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
                        $A = $A->replaceColumn($newColumns->getColumn(0), $i);
                        $A = $A->replaceColumn($newColumns->getColumn(1), $j);
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
    
        if ($iterationCount == $iter) {
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

        $λ = Single::root($λ³, 3);

        return $λ;
    }
}
