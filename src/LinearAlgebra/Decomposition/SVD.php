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
            ['sort'=>$sort, 'P'=>$P] = self::diagonalize($S);
            // Depending on the value of $sort, we either permute the rows or columns of $S 
            if ($sort === 'm') {
                $S = $P->multiply($S);            // Permute rows of S
                $U = $U->multiply($P->inverse()); // Permute corresponding columns of U
            } elseif ($sort === 'n') {
                $S = $S->multiply($P);            // Permute columns of S
                $V = $P->inverse()->multiply($V); // Permute corresponding rows of V
            }
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

        // Check the elements are in descending order
        if (!self::isDiagonalDescending($S)) {
            $P = self::sortDiagonal($S);
            /** @var NumericMatrix */
            $Pᵀ = $P->transpose();

            $S = $Pᵀ->multiply($S)->multiply($P);
            $U = $P->multiply($U)->multiply($Pᵀ);
        }

        return new SVD($U, $S, $V);
    }

    /**
     * Returns a permutation matrix, P, such that the product of SP is diagonal
     * 
     * @param NumericMatrix $S the matrix to diagonalize
     * 
     * @return array{'sort': string, 'P': NumericMatrix} a matrix, P, that will diagonalize S. Multiplication order defined by sort
     * If 'm', then pre-multiply
     * If 'n', then post-multiply
     */
    private static function diagonalize(NumericMatrix $S): array
    {
        if ($S->isRectangularDiagonal()) {
            return MatrixFactory::identity($S->getN());
        }

        $sort = '';
        $vecMethod = '';
        $max = 0;
        $min = 0;

        if ($S->getM() >= $S->getN()) {
            $sort = 'm'; // rows
            $vecMethod = 'asRowVectors';
            $max = $S->getM();
            $min = $S->getN();
        } else {
            $sort = 'n'; // columns
            $vecMethod = 'asVectors';
            $max = $S->getN();
            $min = $S->getM();
        }

        // Create an identity matrix to store permutations in
        $P = MatrixFactory::identity($max)->{$vecMethod}();

        // Push all zero-columns to the right
        $vectors = $S->{$vecMethod}();

        $zeroCols = [];

        foreach ($vectors as $i => $vector)
        {
            // Each column should contain 1 non-zero element
            $isZero = Arithmetic::almostEqual((float) $vector->l2Norm(), 0);

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
        $vectors = $S->submatrix(0,0, $min-1, $min-1)->{$vecMethod}();

        $nonDiagonalValues = [];

        /** @var Vector */
        foreach ($vectors as $i => $vector)
        {
            // Each column should contain 1 non-zero element
            $j = self::getStandardBasisIndex($vector);

            if ($j === -1) {
                throw new MatrixException("S Matrix in SVD is not orthogonal:\n" . (string) $S);
            }

            if ($i === $j) {
                continue;
            } else {
                $nonDiagonalValues[$i] = ['value' => $vector[$j], 'j' => $j];
            }
        }

        // Now create a sort order
        $order = range(0, $min - 1);

        foreach ($nonDiagonalValues as $i => $elem)
        {
            $entry = $elem['j'];
            $order[$entry] = $i;
        }

        $map = array_flip($order);

        // Need to make column ($i of $nonDiagonalValues) = row ($j)
        // order = [1=>2, 2=>1, 3=>3]
        uksort($P, function ($left, $right) use ($map) {
            $leftPos = isset($map[$left]) ? $map[$left] : INF; // sorts in ascending order, so just use inf
            $rightPos = isset($map[$right]) ? $map[$right] : INF;

            return $leftPos <=> $rightPos;
        });

        $P = MatrixFactory::createFromVectors($P);
        
        // fromVectors treats the array as column vectors, so the matrix might need to be transposed
        if ($sort === 'm') {
            $P = $P->transpose();
        }

        return ['sort'=>$sort, 'P' => $P];
    }

    /**
     * Checks that a vector has a single non-zero entry and returns its index
     * 
     * @param Vector $v
     * 
     * @return int The index of the non-zero entry or -1 if either:
     *      1. There are multiple non-zero entries
     *      2. The vector is a zero vector 
     */
    private static function getStandardBasisIndex(Vector $v): int
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
                } else { // If we already found a non-zero component, then return -1
                    return -1;
                }
            }
        }

        return $index;
    }

    /**
     * Returns a permutation matrix that sorts its diagonal values in descending order
     * 
     * @param NumericMatrix $S singular matrix
     * 
     * @return NumericMatrix a permutation matrix such that PᵀSP is diagonal
     */
    private static function sortDiagonal(NumericMatrix $S): NumericMatrix
    {
        // Get diagonal, pad it by columns, and sort it
        $diagonal = $S->getDiagonalElements();
        
        // Pad
        $padLength = $S->getN() - count($diagonal);

        $diagonal = array_merge($diagonal, array_fill(0, $padLength, 0)); // Pick 0 because the numbers should all be positive

        // arsort preserves the indices
        arsort($diagonal, SORT_NUMERIC);

        // ... so we can create a position map from the keys
        $map = array_keys($diagonal);

        $P = MatrixFactory::identity($S->getM())->asVectors();

        uksort($P, function ($left, $right) use ($map) {
            $leftPos = $map[$left];
            $rightPos = $map[$right];

            return $leftPos >= $rightPos;
        });

        return MatrixFactory::createFromVectors($P);
    }

    /**
     * Checks if the elements of a diagonal matrix are in descending order
     * 
     * @param NumericMatrix $S the matrix to check
     * 
     * @return bool
     */
    private static function isDiagonalDescending(NumericMatrix $S): bool
    {
        $diagonal = $S->getDiagonalElements();
        $sorted = array_values($diagonal); rsort($sorted, SORT_NUMERIC);

        return $diagonal === $sorted;
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
