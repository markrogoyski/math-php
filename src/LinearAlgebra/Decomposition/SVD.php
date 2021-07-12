<?php
namespace MathPHP\LinearAlgebra\Decomposition;

use MathPHP\Exception;
use MathPHP\Functions\Map\Single;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;

/**
 * Singular value decomposition
 *
 * The generalization of the eigendecomposition of a square matrix to an m x n matrix
 * https://en.wikipedia.org/wiki/Singular_value_decomposition
 */
class SVD extends Decomposition
{
    /** @var Matrix m x m orthogonal matrix  */
    private $U;

    /** @var Matrix n x n orthogonal matrix  */
    private $V;

    /** @var Matrix m x n diagonal matrix  */
    private $S;

    /** @var Vector diagonal elements from $S  */
    private $D;

    /**
     * SVD constructor
     *
     * @param Matrix $U Orthogonal matrix
     * @param Matrix $S Rectangular Diagonal matrix
     * @param Matrix $V Orthogonal matrix
     */
    private function __construct(Matrix $U, Matrix $S, Matrix $V)
    {
        $this->U = $U;
        $this->S = $S;
        $this->V = $V;
        $this->D = new Vector($S->getDiagonalElements());
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

    /**
     * Get D
     *
     * @return Vector
     */
    public function getD(): Vector
    {
        return $this->D;
    }

    public static function decompose(Matrix $M): SVD
    {
        $Mt = $M->transpose();
        $MMt = $M->multiply($Mt);
        $MtM = $Mt->multiply($M);
        
        // m x m orthoganol matrix
        $U = $MMt->eigenvectors();
        
        // n x n orthoganol matrix
        $V = $MtM->eigenvectors();

        // A rectangular diagonal matrix
        $S = $U->transpose()->multiply($M)->multiply($V);

        $diag = $S->getDiagonalElements();

        // If there is a negative singular value, we need to adjust the signs of columns in U
        if (min($diag) < 0) {
            $sig = MatrixFactory::identity($U->getN())->getMatrix();
            foreach ($diag as $key => $value) {
                $sig[$key][$key] = $value >= 0 ? 1 : -1;
            }
            $signature = MatrixFactory::create($sig);
            $U = $U->multiply($signature);
            $S = $signature->multiply($S);
        }
        return new SVD($U, $S, $V);
    }

    /**
     * Get U, S, or V matrix
     *
     * @param string $name
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException
     */
    public function __get(string $name)
    {
        switch ($name) {
            case 'U':
            case 'S':
            case 'V':
            case 'D':
                return $this->$name;
            default:
                throw new Exception\MatrixException("SVD class does not have a gettable property: $name");
        }
    }
}
