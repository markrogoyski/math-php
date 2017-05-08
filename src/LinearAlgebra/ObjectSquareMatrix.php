<?php

namespace MathPHP\LinearAlgebra;

use MathPHP\Exception;

/**
 * ObjectSquareMatrix
 *
 * The objectMatrix extends Matrix functions to a matrix of objects.
 * The object must have add(), subtract(), and multiply() methods to be
 * compatible.
 */
class ObjectSquareMatrix extends SquareMatrix
{
    /**
     * Check that the matricies are the same size
     *
     * @throws MatrixException if matrices have a different number of rows or columns
     */
    private function checkEqualSizes(Matrix $B)
    {
        if ($B->getM() !== $this->m) {
            throw new \MathPHP\Exception\MatrixException('Matrices are different sizes');
        }
    }

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
     * For a 1x1 matrix:
     *  A = [a]
     *
     * |A| = a
     *
     * For larger matrices:
     *
     * We sum the product of each element and cofactor of that element,
     * using the top row of the matrix.
     *
     * @return object
     *
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
                    $initialize = false;
                } else {
                    $det = $det->add($R[0][$key]->multiply($value));
                }
            }
            $this->det = $det;
            return $this->det;
        }
    }

    public function cofactor(int $mᵢ, int $nⱼ)
    {
        // All necessary exceptions are thrown in Matrix::minor
        $Mᵢⱼ    = $this->minor($mᵢ, $nⱼ);
        $⟮−1⟯ⁱ⁺ʲ = (-1)**($mᵢ + $nⱼ);
        return $Mᵢⱼ->multiply($⟮−1⟯ⁱ⁺ʲ);
    }
}
