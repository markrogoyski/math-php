<?php

namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

/**
 * Square matrix
 * Number of rows = number of columns
 * 1x1, 2x2, 3x3, etc.
 */
class SquareMatrix extends Matrix
{
    use SquareMatrixTrait;

    /**
     * Constructor
     *
     * @param array $A
     *
     * @throws Exception\MathException
     */
    public function __construct(array $A)
    {
        parent::__construct($A);

        if ($this->m !== $this->n) {
            throw new Exception\MatrixException('Not a square matrix; row count and column count differ');
        }
    }

    /**
     * Square matrix must be square
     *
     * @return bool
     */
    public function isSquare(): bool
    {
        return true;
    }

    /**************************************************************************
     * MATRIX OPERATIONS - Return a Matrix
     *  - inverse
     **************************************************************************/

    /**
     * Inverse
     *
     * For a 1x1 matrix
     *  A   = [a]
     *  A⁻¹ = [1/a]
     *
     * For a 2x2 matrix:
     *      [a b]
     *  A = [c d]
     *
     *         1
     *  A⁻¹ = --- [d -b]
     *        │A│ [-c a]
     *
     * For a 3x3 matrix or larger:
     * Augment with identity matrix and calculate reduced row echelon form.
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException if singular matrix
     * @throws Exception\IncorrectTypeException
     * @throws Exception\BadParameterException
     * @throws Exception\OutOfBoundsException
     */
    public function inverse(): Matrix
    {
        if ($this->catalog->hasInverse()) {
            return $this->catalog->getInverse();
        }

        if ($this->isSingular()) {
            throw new Exception\MatrixException('Singular matrix (determinant = 0); not invertible');
        }

        $m   = $this->m;
        $n   = $this->n;
        $A   = $this->A;
        $│A│ = $this->det();

         // 1x1 matrix: A⁻¹ = [1 / a]
        if ($m === 1) {
            $a   = $A[0][0];
            $A⁻¹ = MatrixFactory::create([[1 / $a]]);
            $this->catalog->addInverse($A⁻¹);
            return $A⁻¹;
        }

        /*
         * 2x2 matrix:
         *      [a b]
         *  A = [c d]
         *
         *        1
         * A⁻¹ = --- [d -b]
         *       │A│ [-c a]
         */
        if ($m === 2) {
            $a = $A[0][0];
            $b = $A[0][1];
            $c = $A[1][0];
            $d = $A[1][1];

            $R = MatrixFactory::create([
                [$d, -$b],
                [-$c, $a],
            ]);
            $A⁻¹ = $R->scalarMultiply(1 / $│A│);

            $this->catalog->addInverse($A⁻¹);
            return $A⁻¹;
        }

        // nxn matrix 3x3 or larger
        $R   = $this->augmentIdentity()->rref();
        $A⁻¹ = [];

        for ($i = 0; $i < $n; $i++) {
            $A⁻¹[$i] = array_slice($R[$i], $n);
        }

        $A⁻¹ = MatrixFactory::create($A⁻¹);

        $this->catalog->addInverse($A⁻¹);
        return $A⁻¹;
    }
}
