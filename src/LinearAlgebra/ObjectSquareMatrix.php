<?php
namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;
use MathPHP\Number\ObjectArithmetic;

/**
 * ObjectSquareMatrix
 *
 * The objectSquareMatrix extends Matrix functions to a matrix of objects.
 * The object must implement the MatrixArithmetic interface to prove
 * compatibility. It extends the SquareMatrix in order to use Matrix::minor().
 */
class ObjectSquareMatrix extends SquareMatrix
{
    /**
     * The type of object that is being stored in this Matrix
     * @var string
     */
    protected $object_type;

    /**
     * Constructor
     *
     * The constuctor follows performs all the same checks as the parent, but also checks that
     * all of the elements in the arry are of the same data type.
     *
     * @param array[] of arrays $A m x n matrix
     *
     * @throws Exception\BadDataException if any rows have a different column count
     * @throws Exception\IncorrectTypeException if all elements are not the same class
     * @throws Exception\IncorrectTypeException if The class does not implement the ObjectArithmetic interface
     * @throws Exception\MathException
     */
    public function __construct(array $A)
    {
        parent::__construct($A);

        if ($A[0][0] instanceof ObjectArithmetic) {
            $this->object_type = get_class($A[0][0]);
        } else {
            throw new Exception\IncorrectTypeException("The object must implement the interface.");
        }
        foreach ($A as $i => $row) {
            foreach ($row as $object) {
                if (get_class($object) != $this->object_type) {
                    throw new Exception\IncorrectTypeException("All elements in the matrix must be of the same type.");
                }
            }
        }
    }

    /**
     * Get the type of objects that are stored in the matrix
     *
     * @return string The class of the objects
     */
    public function getObjectType()
    {
        return $this->object_type;
    }

    /**
     * Check that the matricies are the same size and of the same type
     *
     * @throws Exception\MatrixException if matrices have a different number of rows or columns
     * @throws Exception\IncorrectTypeException if the two matricies are not the same class
     */
    private function checkEqualSizes(Matrix $B)
    {
        if ($B->getM() !== $this->m || $B->getN() !== $this->n) {
            throw new Exception\MatrixException('Matrices are different sizes');
        }
        if ($B->getObjectType() !== $this->object_type) {
            throw new Exception\IncorrectTypeException('Matrices must contain the same object types');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function add(Matrix $B): Matrix
    {
        $this->checkEqualSizes($B);
        $R = [];
        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                $R[$i][$j] = $this->A[$i][$j]->add($B[$i][$j]);
            }
        }
        return MatrixFactory::create($R);
    }

    /**
     * {@inheritDoc}
     */
    public function subtract(Matrix $B): Matrix
    {
        $this->checkEqualSizes($B);
        $R = [];
        for ($i = 0; $i < $this->m; $i++) {
            for ($j = 0; $j < $this->n; $j++) {
                $R[$i][$j] = $this->A[$i][$j]->subtract($B[$i][$j]);
            }
        }
        return MatrixFactory::create($R);
    }

    /**
     * Determinant
     *
     * This implementation is simpler than that of the parent. Instead of
     * reducing the matrix, which requires division, we calculate the cofactors
     * for the first row of the matrix, perform element-wise multiplication, and
     * add the results of that row.
     *
     * This implementation also uses the same algorithm for 2x2 matricies. Adding
     * a special case may quicken code execution.
     *
     * @return ObjectArithmetic
     */
    public function det()
    {
        if (isset($this->det)) {
            return $this->det;
        }
        $m = $this->m;
        $n = $this->n;
        $R = MatrixFactory::create($this->A);
        /*
         * 1x1 matrix
         *  A = [a]
         *
         * |A| = a
         */
        if ($m === 1) {
            $this->det = $R[0][0];
            return $this->det;
        } else {
            // Calculate the cofactors of the top row of the matrix
            $row_of_cofactors = [];
            for ($i = 0; $i < $m; $i++) {
                $row_of_cofactors[$i] = $R->cofactor(0, $i);
            }

            // Since we don't know what the data type is, we can't initialze $det
            // to zero without a special initialize() or zero() method.
            $initialize = true;
            $det = $R[0][0]->multiply($row_of_cofactors[0]);
            foreach ($row_of_cofactors as $key => $value) {
                if ($initialize) {
                    // We skip the first element since it was used to initialize.
                    $initialize = false;
                } else {
                    // $det += element * cofactor
                    $det = $det->add($R[0][$key]->multiply($value));
                }
            }
            $this->det = $det;
            return $this->det;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function cofactor(int $mᵢ, int $nⱼ)
    {
        // All necessary exceptions are thrown in Matrix::minor
        $Mᵢⱼ    = $this->minor($mᵢ, $nⱼ);
        $⟮−1⟯ⁱ⁺ʲ = (-1)**($mᵢ + $nⱼ);
        return $Mᵢⱼ->multiply($⟮−1⟯ⁱ⁺ʲ);
    }
}
