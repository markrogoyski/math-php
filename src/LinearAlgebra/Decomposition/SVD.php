<?php
namespace MathPHP\LinearAlgebra\Decomposition;

use MathPHP\Functions\Map\Single;
use MathPHP\LinearAlgebra\Eigenvalue;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;

/**
 * Singular value decomposition
 *
 * The generalization of the eigendecomposition of a square matrix to an m x n matrix
 * https://en.wikipedia.org/wiki/Singular_value_decomposition
 */
class SVD
{
    /** @var Matrix m x m orthogonal matrix  */
    private $U;

    /** @var Matrix n x n orthogonal matrix  */
    private $V;

    /** @var Matrix m x n diagonal matrix  */
    private $S;
    
    /**
     * SVD constructor
     *
     * @param Matrix $U Orthogonal matrix
     * @param Matrix $S Diagonal matrix
     * @param Matrix $V Orthogonal matrix
     */
    private function __construct(Matrix $U, Matrix $S, Matrix $V)
    {
        $this->U = $U;
        $this->S = $S;
        $this->V = $V;
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
     * Get S
     *
     * @return Matrix
     */
    public function getS(): Matrix
    {
        return $this->S;
    }

    /**
     * Get V
     *
     * @return Matrix
     */
    public function getV(): Matrix
    {
        return $this->V;
    }

    public static function decompose(Matrix $M): array
    {
        $Mt = $m->transpose();
        $MMt = $M->multiply($Mt);
        $MtM = $Mt->multiply($M);
        
        // m x m orthoganol matrix
        $U = $MMt->eigenvectors(Eigenvalue::JACOBI_METHOD);
        
        // n x n orthoganol matrix
        $V = $MtM->eigenvectors(Eigenvalue::JACOBI_METHOD);
        
        // Diagonal matrix
        $S = MatrixFacotry::create(Single::sqrt($MMt->eigenvalues(Eigenvalue::JACOBI_METHOD)));
        
        return new SVD($U, $S, $V);
    }
}
