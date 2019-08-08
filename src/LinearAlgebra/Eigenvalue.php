<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;
use MathPHP\Functions\Polynomial;
use MathPHP\Functions\Support;

class Eigenvalue
{
    const CLOSED_FORM_POLYNOMIAL_ROOT_METHOD = 'closedFormPolynomialRootMethod';
    const JACOBI_METHOD = 'jacobiMethod';

    const METHODS = [
        self::CLOSED_FORM_POLYNOMIAL_ROOT_METHOD,
        self::JACOBI_METHOD,
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
     * Find eigenvalues by the Jacobi method
     *
     * https://en.wikipedia.org/wiki/Jacobi_eigenvalue_algorithm
     *
     * @param Matrix $A
     *
     * @return float[] of eigenvalues
     *
     * @throws Exception\BadDataException if the matrix is not symmetric
     * @throws Exception\BadDataException if the matrix is 1x1
     */
    public static function jacobiMethod(Matrix $A): array
    {
        if (!$A->isSymmetric()) {
            throw new Exception\BadDataException('Matrix must be symmetric');
        }

        $m = $A->getM();
        if ($m < 2) {
            throw new Exception\BadDataException("Matrix must be 2x2 or larger");
        }
        $D = $A;
        $S = MatrixFactory::identity($m);
        while (!self::isNearlyDiagonal($D)) {
            // Find the largest off-diagonal element in $D
            $pivot = ['value' => 0, 'i' => 0, 'j'=> 0];
            for ($i = 0; $i < $m - 1; $i++) {
                for ($j = $i + 1; $j < $m; $j++) {
                    if (abs($D[$i][$j]) > abs($pivot['value'])) {
                        $pivot['value'] = $D[$i][$j];
                        $pivot['i'] = $i;
                        $pivot['j'] = $j;
                    }
                }
            }
            $i = $pivot['i'];
            $j = $pivot['j'];
            if ($D[$i][$i] == $D[$j][$j]) {
                $angle = ($D[$i][$i] <=> 0) * \M_PI / 4;
            } else {
                $angle = atan(2 * $D[$i][$j] / ($D[$i][$i] - $D[$j][$j])) / 2;
            }
            $G = self::givensMatrix($i, $j, $angle, $m);
            $D = $G->transpose()->multiply($D)->multiply($G);
            $S = $S->multiply($G);
        }
        $eigenvalues = $D->getDiagonalElements();
        usort($eigenvalues, function ($a, $b) {
            return abs($b) <=> abs($a);
        });
        return $eigenvalues;
    }

    /**
    * Construct a givens matrix
    *
    *               [  1 ⋯ 0 ⋯ 0 ⋯ 0 ]
    *               [  ⋮ ⋱ ⋮    ⋮    ⋮  ]
    *               [  0 ⋯ c ⋯-s ⋯ 0 ]
    * G (𝒾,𝒿,θ) =    [  ⋮   ⋮  ⋱ ⋮    ⋮ ]
    *               [  0 ⋯ s ⋯ c ⋯ 0 ]
    *               [  ⋮    ⋮    ⋮  ⋱ ⋮ ]
    *               [  0 ⋯ 0 ⋯ 0 ⋯ 1 ]
    *
    * https://en.wikipedia.org/wiki/Givens_rotation
    *
    * @param int $i The row in G in which the top of the roatation lies
    * @param int $j The column in G in which the left of the roatation lies
    * @param float $angle The angle to use in the trigonometric functions
    * @param int $m The total number of rows in G
    *
    * @return Matrix
    */
    private static function givensMatrix(int $i, int $j, float $angle, int $m) : Matrix
    {
        $G = Matrixfactory::identity($m)->getMatrix();
        $G[$i][$i] = cos($angle);
        $G[$j][$j] = cos($angle);
        $G[$i][$j] = - 1 * sin($angle);
        $G[$j][$i] = sin($angle);
        return MatrixFactory::create($G);
    }

    /**
     * True if all off-diagonal elements are very close to zero
     *
     * @param Matrix $A
     *
     * @return bool
     */
    private static function isNearlyDiagonal(Matrix $A): bool
    {
        if (!$A->isSquare()) {
            return false;
        }
        $m = $A->getM();
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $m; $j++) {
                if ($i !== $j && !Support::isZero($A[$i][$j])) {
                    return false;
                }
            }
        }
        return true;
    }
}
