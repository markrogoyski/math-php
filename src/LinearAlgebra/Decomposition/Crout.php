<?php
namespace MathPHP\LinearAlgebra\Decomposition;

use MathPHP\Exception;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;

/**
 * Crout decomposition
 * An LU decomposition which decomposes a matrix into a lower triangular matrix (L), an upper triangular matrix (U).
 * https://en.wikipedia.org/wiki/Crout_matrix_decomposition
 *
 * A = LU where L = LD
 * A = (LD)U
 *  - L = lower triangular matrix
 *  - D = diagonal matrix
 *  - U = normalised upper triangular matrix
 *
 * @property-read Matrix $L Lower triangular matrix LD
 * @property-read Matrix $U Normalized upper triangular matrix
 */
class Crout implements \ArrayAccess
{
    /** @var Matrix Lower triangular matrix LD */
    private $L;

    /** @var Matrix Normalized upper triangular matrix */
    private $U;

    /**
     * Crout constructor
     *
     * @param Matrix $L Lower triangular matrix LD
     * @param Matrix $U Normalized upper triangular matrix
     */
    private function __construct(Matrix $L, Matrix $U)
    {
        $this->L = $L;
        $this->U = $U;
    }

    /**
     * @return Matrix
     */
    public function getL(): Matrix
    {
        return $this->L;
    }

    /**
     * @return Matrix
     */
    public function getU(): Matrix
    {
        return $this->U;
    }

    /**
     * Decompose a matrix into Crout decomposition
     * Factory method to create Crout decomposition
     *
     * @param Matrix $A
     *
     * @return Crout
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MathException
     * @throws Exception\MatrixException if there is division by 0 because of a 0-value determinant
     * @throws Exception\OutOfBoundsException
     */
    public static function decompose(Matrix $A): Crout
    {
        $m   = $A->getM();
        $n   = $A->getN();
        $A   = $A->getMatrix();
        $U   = MatrixFactory::identity($n)->getMatrix();
        $L   = MatrixFactory::zero($m, $n)->getMatrix();

        for ($j = 0; $j < $n; $j++) {
            for ($i = $j; $i < $n; $i++) {
                $sum = 0;
                for ($k = 0; $k < $j; $k++) {
                    $sum = $sum + $L[$i][$k] * $U[$k][$j];
                }
                $L[$i][$j] = $A[$i][$j] - $sum;
            }

            for ($i = $j; $i < $n; $i++) {
                $sum = 0;
                for ($k = 0; $k < $j; $k++) {
                    $sum = $sum + $L[$j][$k] * $U[$k][$i];
                }
                if ($L[$j][$j] == 0) {
                    throw new Exception\MatrixException('Cannot do Crout decomposition. det(L) close to 0 - Cannot divide by 0');
                }
                $U[$j][$i] = ($A[$j][$i] - $sum) / $L[$j][$j];
            }
        }

        $L = MatrixFactory::create($L);
        $U = MatrixFactory::create($U);

        return new Crout($L, $U);
    }

    /**
     * Get L, or Lᵀ matrix
     *
     * @param string $name
     *
     * @return Matrix
     *
     * @throws Exception\MatrixException
     */
    public function __get(string $name): Matrix
    {
        switch ($name) {
            case 'L':
            case 'U':
                return $this->$name;

            default:
                throw new Exception\MatrixException("Crout class does not have a gettable property: $name");
        }
    }

    /**************************************************************************
     * ArrayAccess INTERFACE
     **************************************************************************/

    /**
     * @param mixed $i
     * @return bool
     */
    public function offsetExists($i): bool
    {
        switch ($i) {
            case 'L':
            case 'U':
                return true;

            default:
                return false;
        }
    }

    /**
     * @param mixed $i
     * @return mixed
     */
    public function offsetGet($i)
    {
        return $this->$i;
    }

    /**
     * @param  mixed $i
     * @param  mixed $value
     * @throws Exception\MatrixException
     */
    public function offsetSet($i, $value)
    {
        throw new Exception\MatrixException('Crout class does not allow setting values');
    }

    /**
     * @param  mixed $i
     * @throws Exception\MatrixException
     */
    public function offsetUnset($i)
    {
        throw new Exception\MatrixException('Crout class does not allow unsetting values');
    }
}
