<?php

namespace MathPHP\LinearAlgebra\Decomposition;

use MathPHP\Arithmetic;
use MathPHP\Exception;
use MathPHP\Exception\MatrixException;
use MathPHP\LinearAlgebra\NumericMatrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;

/**
 * Singular value decomposition
 *
 * The generalization of the eigendecomposition of a square matrix to an m x n matrix
 * https://en.wikipedia.org/wiki/Singular_value_decomposition
 *
 * @property-read NumericMatrix $S m x n diagonal matrix
 * @property-read NumericMatrix $V n x n orthogonal matrix
 * @property-read NumericMatrix $U m x m orthogonal matrix
 * @property-read Vector        $D diagonal elements from S
 */
class SVD extends Decomposition
{
    /** @var NumericMatrix m x m orthogonal matrix  */
    private $U;

    /** @var NumericMatrix n x n orthogonal matrix  */
    private $V;

    /** @var NumericMatrix m x n diagonal matrix containing the singular values  */
    private $S;

    /** @var Vector diagonal elements from S that are the singular values  */
    private $D;

    /**
     * @param NumericMatrix $U Orthogonal matrix
     * @param NumericMatrix $S Rectangular Diagonal matrix
     * @param NumericMatrix $V Orthogonal matrix
     */
    private function __construct(NumericMatrix $U, NumericMatrix $S, NumericMatrix $V)
    {
        $this->U = $U;
        $this->S = $S;
        $this->V = $V;
        $this->D = new Vector($S->getDiagonalElements());
    }

    /**
     * Get U
     *
     * @return NumericMatrix
     */
    public function getU(): NumericMatrix
    {
        return $this->U;
    }

    /**
     * Get S
     *
     * @return NumericMatrix
     */
    public function getS(): NumericMatrix
    {
        return $this->S;
    }

    /**
     * Get V
     *
     * @return NumericMatrix
     */
    public function getV(): NumericMatrix
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

    /**
     * Generate the Singlue Value Decomposition of the matrix
     *
     * @param NumericMatrix $M
     *
     * @return SVD
     */
    public static function decompose(NumericMatrix $M): SVD
    {
        $Mᵀ  = $M->transpose();
        $MMᵀ = $M->multiply($Mᵀ);
        $MᵀM = $Mᵀ->multiply($M);

        // m x m orthoganol matrix
        $U = $MMᵀ->eigenvectors();

        // n x n orthoganol matrix
        $V = $MᵀM->eigenvectors();

        // A rectangular diagonal matrix
        $S = $U->transpose()->multiply($M)->multiply($V);

        // If S is non-diagonal, try permuting S to be diagonal
        if (!$S->isRectangularDiagonal()) {
            $P = self::diagonalizeColumnar($S);

            $S = $S->multiply($P);            // Permute columns of S
            $V = $P->inverse()->multiply($V); // Permute corresponding rows of V
        }

        $diag = $S->getDiagonalElements();

        // If there is a negative singular value, we need to adjust the signs of columns in U
        if (min($diag) < 0) {
            $sig = MatrixFactory::identity($U->getN())->getMatrix();
            foreach ($diag as $key => $value) {
                $sig[$key][$key] = $value >= 0 ? 1 : -1;
            }
            $signature = MatrixFactory::createNumeric($sig);
            $U = $U->multiply($signature);
            $S = $signature->multiply($S);
        }

        return new SVD($U, $S, $V);
    }

    /**
     * Returns a permutation matrix, P, such that the product of SP is diagonal
     * 
     * @param NumericMatrix $S the matrix to diagonalize
     * 
     * @return NumericMatrix a matrix, P, that will diagonalize S (by shuffling cols)
     */
    private static function diagonalizeColumnar(NumericMatrix $S): NumericMatrix
    {
        if ($S->isRectangularDiagonal()) {
            return MatrixFactory::identity($S->getN());
        }

        // Create an identity matrix to store permutations in
        $P = MatrixFactory::identity($S->getN())->asVectors();

        // Push all zero-columns to the right
        $cols = $S->asVectors();

        $zeroCols = [];

        foreach ($cols as $i => $colVector)
        {
            // Each column should contain 1 non-zero element
            $isZero = Arithmetic::almostEqual((float) $colVector->l2Norm(), 0);

            $zeroCols[$i] = $isZero ? 0 : 1;
        }

        arsort($zeroCols, SORT_NUMERIC);

        $zeroMap = array_keys($zeroCols);

        uksort($P, function ($left, $right) use ($zeroMap) {
            $leftPos = $zeroMap[$left];
            $rightPos = $zeroMap[$right];

            return $leftPos >= $rightPos;
        });

        // Only check the columns that contain diagonal entries
        $rowBound = $S->getM() - 1;
        $colBound = count($S->getDiagonalElements()) - 1;
        $cols = $S->submatrix(0,0, $rowBound, $colBound)->asVectors();

        $nonDiagonalValues = [];

        foreach ($cols as $i => $colVector)
        {
            // Each column should contain 1 non-zero element
            $j = self::isStandardBasisVector($colVector);

            if ($j === false) {
                throw new MatrixException("S Matrix in SVD is not orthogonal:\n" . (string) $S);
            }

            if ($i === $j) {
                continue;
            } else {
                $nonDiagonalValues[$i] = ['value' => $colVector[$j], 'row' => $j];
            }
        }

        // Now create a sort order
        $order = range(0, $S->getN() - 1);

        foreach ($nonDiagonalValues as $col => $elem)
        {
            $row = $elem['row'];
            $order[$row] = $col;
        }

        $map = array_flip($order);

        // Need to make column ($i of $nonDiagonalValues) = row ($j)
        // order = [1=>2, 2=>1, 3=>3]
        uksort($P, function ($left, $right) use ($map) {
            $leftPos = $map[$left];
            $rightPos = $map[$right];

            return $leftPos >= $rightPos;
        });

        // fromVectors treats the array as column vectors, so the matrix needs to be transposed
        return MatrixFactory::createFromVectors($P);
    }

    /**
     * Checks that a vector has a single non-zero entry
     * 
     * @param Vector $v
     * 
     * @return int|false The index of the non-zero entry or false if either:
     *      1. There are multiple non-zero entries
     *      2. The vector is a zero vector 
     */
    private static function isStandardBasisVector(Vector $v): int|false
    {
        if ($v->l2Norm() === 0) {
            return false;
        }

        // Vectors don't have negative indices
        $index = -1;
    
        foreach ($v->getVector() as $i => $component)
        {
            if (!Arithmetic::almostEqual($component, 0)) {
                if ($index === -1) {
                    $index = $i;
                } else { // If we already found a non-zero component, then return false
                    return false;
                }
            }
        }

        return $index;
    }

    /**
     * Get U, S, or V matrix, or D vector
     *
     * @param string $name
     *
     * @return NumericMatrix|Vector
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
