<?php
namespace MathPHP\LinearAlgebra\Decomposition;

use MathPHP\Exception;
use MathPHP\Functions\Special;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;

/**
 * QR Decomposition using Householder reflections
 *
 * A = QR
 *
 * Q is an orthogonal matrix
 * R is an upper triangular matrix
 */
class QR
{
    /** @var Matrix orthogonal matrix  */
    private $Q;

    /** @var Matrix upper triangular matrix */
    private $R;

    /**
     * QR constructor
     *
     * @param Matrix $Q Orthogonal matrix
     * @param Matrix $R Upper triangular matrix
     */
    private function __construct(Matrix $Q, Matrix $R)
    {
        $this->Q = $Q;
        $this->R = $R;
    }

    /**
     * Get Q
     *
     * @return Matrix
     */
    public function getQ(): Matrix
    {
        return $this->Q;
    }

    /**
     * Get R
     *
     * @return Matrix
     */
    public function getR(): Matrix
    {
        return $this->R;
    }

    /**
     * Decompose a matrix into a QR Decomposition using Householder reflections
     * Factory method to create QR objects.
     *
     * A = QR
     *
     * Q is an orthogonal matrix
     * R is an upper triangular matrix
     *
     * Algorithm notes:
     *  If the source matrix is square or wider than it is tall, the final
     *  householder matrix will be the identity matrix with a -1 in the bottom
     *  corner. The effect of this final transformation would only change signs
     *  on existing matrices. Both R and Q will already be in appropriate forms
     *  in the next to the last step. We can skip the last transformation without
     *  affecting the validity of the results. Results indicate other software
     *  behaves similarly.
     *
     *  This is because on a 1x1 matrix uuᵀ = uᵀu, so I - [[2]] = [[-1]]
     *
     * @param Matrix $A source Matrix
     *
     * @return QR
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException
     * @throws Exception\VectorException
     */
    public static function decompose(Matrix $A): QR
    {
        $n  = $A->getN();  // columns
        $m  = $A->getM();  // rows
        $HA = $A;

        $numReflections = min($m - 1, $n);
        $FullI          = MatrixFactory::identity($m);
        $Q              = $FullI;

        for ($i = 0; $i < $numReflections; $i++) {
            // Remove the leftmost $i columns and upper $i rows
            $A = $HA->submatrix($i, $i, $m - 1, $n - 1);
            
            // Create the householder matrix
            $innerH = self::householderMatrix($A);
            
            // Embed the smaller matrix within a full rank Identity matrix
            $H  = $FullI->insert($innerH, $i, $i);
            $Q  = $Q->multiply($H);
            $HA = $H->multiply($HA);
        }

        $R = $HA;
        return new QR(
            $Q->submatrix(0, 0, $m - 1, min($m, $n) - 1),
            $R->submatrix(0, 0, min($m, $n) - 1, $n - 1)
        );
    }

    /**
     * Householder Matrix
     *
     * u = x ± αe   where α = ‖x‖ and sgn(α) = sgn(x)
     *
     *              uuᵀ
     * Q = I - 2 * -----
     *              uᵀu
     *
     * @param Matrix $A source Matrix
     *
     * @return Matrix
     *
     * @throws Exception\BadDataException
     * @throws Exception\BadParameterException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException
     * @throws Exception\VectorException
     */
    private static function householderMatrix(Matrix $A): Matrix
    {
        $m = $A->getM();
        $I = MatrixFactory::identity($m);
        
        // x is the leftmost column of A
        $x = $A->submatrix(0, 0, $m - 1, 0);
        
        // α is the square root of the sum of squares of x with the correct sign
        $α = Special::sgn($x[0][0]) * $x->frobeniusNorm();
        
        // e is the first column of I
        $e = $I->submatrix(0, 0, $m - 1, 0);
        
        // u = x ± αe
        $u   = $e->scalarMultiply($α)->add($x);
        $uᵀ  = $u->transpose();
        $uᵀu = $uᵀ->multiply($u)->get(0, 0);
        $uuᵀ = $u->multiply($uᵀ);
        if ($uᵀu == 0) {
            return $I;
        }
        
        // We scale $uuᵀ and subtract it from the identity matrix
        return $I->subtract($uuᵀ->scalarMultiply(2 / $uᵀu));
    }
}
