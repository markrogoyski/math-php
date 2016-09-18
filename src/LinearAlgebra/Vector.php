<?php
namespace Math\LinearAlgebra;

use Math\Functions\Map;

/**
 * 1 x n Vector
 */
class Vector implements \ArrayAccess
{
    /**
     * Number of elements
     * @var int
     */
    private $n;

    /**
     * Vector
     * @var array
     */
    private $A;

    /**
     * Constructor
     * @param array $A 1 x n vector
     */
    public function __construct(array $A)
    {
        $this->A = $A;
        $this->n = count($A);
    }

    /**************************************************************************
     * BASIC VECTOR GETTERS
     *  - getVector
     *  - getN
     *  - get
     *  - asColumnMatrix
     **************************************************************************/

    /**
     * Get matrix
     * @return array of arrays
     */
    public function getVector(): array
    {
        return $this->A;
    }

    /**
     * Get item count (n)
     * @return int number of items
     */
    public function getN(): int
    {
        return $this->n;
    }

    /**
     * Get a specific value at position i
     *
     * @param  int    $i index
     * @return number
     */
    public function get(int $i)
    {
        if ($i >= $this->n) {
            throw new \Exception("Element $i does not exist");
        }

        return $this->A[$i];
    }

    /**
     * Get the vector as an nx1 column matrix
     *
     * Example:
     *  V = [1, 2, 3]
     *
     *      [1]
     *  R = [2]
     *      [3]
     *
     * @return Matrix
     */
    public function asColumnMatrix()
    {
        $matrix = [];
        foreach ($this->A as $element) {
            $matrix[] = [$element];
        }

        return new Matrix($matrix);
    }

    /**************************************************************************
     * VECTOR OPERATIONS - Return a number
     *  - sum
     *  - dotProduct (innerProduct)
     **************************************************************************/

    /**
     * Sum of all elements
     *
     * @return number
     */
    public function sum()
    {
        return array_sum($this->A);
    }

    /**
     * Dot product (inner product) (A⋅B)
     * https://en.wikipedia.org/wiki/Dot_product
     *
     * @param Vector $B
     *
     * @return number
     */
    public function dotProduct(Vector $B)
    {
        if ($B->getN() !== $this->n) {
            throw new \Exception('Vectors have different number of items');
        }

        return array_sum(array_map(
            function ($a, $b) {
                return $a * $b;
            },
            $this->A,
            $B->getVector()
        ));
    }

    /**
     * Inner product (convience method for dot product) (A⋅B)
     *
     * @param Vector $B
     *
     * @return number
     */
    public function innerProduct(Vector $B)
    {
        return $this->dotProduct($B);
    }

    /**************************************************************************
     * VECTOR OPERATIONS - Return a Vector or Matrix
     *  - outerProduct
     *  - crossProduct
     **************************************************************************/

    /**
     * Outer product
     * https://en.wikipedia.org/wiki/Outer_product
     *
     * @param Vector $B
     *
     * @return Matrix
     */
    public function outerProduct(Vector $B): Matrix
    {
        $m = $this->n;
        $n = $B->getN();
        $R = [];

        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $R[$i][$j] = $this->A[$i] * $B[$j];
            }
        }

        return MatrixFactory::create($R);
    }

    /**
     * Cross product (AxB)
     * https://en.wikipedia.org/wiki/Cross_product
     *
     *         | i  j  k  |
     * A x B = | a₀ a₁ a₂ | = |a₁ a₂|  - |a₀ a₂|  + |a₀ a₁|
     *         | b₀ b₁ b₂ |   |b₁ b₂|i   |b₀ b₂|j   |b₀ b₁|k
     *
     *       = (a₁b₂ - b₁a₂) - (a₀b₂ - b₀a₂) + (a₀b₁ - b₀a₁)
     *
     * @param Vector $B
     *
     * @return Vector
     */
    public function crossProduct(Vector $B)
    {
        if ($B->getN() !== 3 || $this->n !== 3) {
            throw new \Exception('Vectors must have 3 items');
        }

        $s1 =   ($this->A[1] * $B[2]) - ($this->A[2] * $B[1]);
        $s2 = -(($this->A[0] * $B[2]) - ($this->A[2] * $B[0]));
        $s3 =   ($this->A[0] * $B[1]) - ($this->A[1] * $B[0]);

        return new Vector([$s1, $s2, $s3]);
    }

    /**************************************************************************
     * VECTOR NORMS
     *  - l1Norm
     *  - l2Norm
     *  - pNorm
     *  - maxNorm
     **************************************************************************/

    /**
     * l₁-norm (|x|₁)
     * Also known as Taxicab norm or Manhattan norm
     *
     * https://en.wikipedia.org/wiki/Norm_(mathematics)#Taxicab_norm_or_Manhattan_norm
     *
     * |x|₁ = ∑|xᵢ|
     *
     * @return number
     */
    public function l1Norm()
    {
        return array_sum(Map\Single::abs($this->A));
    }

    /**
     * l²-norm (|x|₂)
     * Also known as Euclidean norm, Euclidean length, L² distance, ℓ² distance
     *
     * http://mathworld.wolfram.com/L2-Norm.html
     * https://en.wikipedia.org/wiki/Norm_(mathematics)#Euclidean_norm
     *         ______
     * |x|₂ = √∑|xᵢ|²
     *
     * @return number
     */
    public function l2Norm()
    {
        return sqrt(array_sum(Map\Single::square($this->A)));
    }

    /**
     * p-norm (|x|p)
     * Also known as lp norm
     *
     * https://en.wikipedia.org/wiki/Norm_(mathematics)#p-norm
     *
     * |x|p = (∑|xᵢ|ᵖ)¹/ᵖ
     *
     * @return number
     */
    public function pNorm($p)
    {
        return array_sum(Map\Single::pow($this->A, $p))**(1/$p);
    }

    /**
     * Max norm (infinity norm) (|x|∞)
     *
     * |x|∞ = max |x|
     *
     * @return number
     */
    public function maxNorm()
    {
        return max(Map\Single::abs($this->A));
    }

    /**************************************************************************
     * PHP MAGIC METHODS
     *  - __toString
     **************************************************************************/

    /**
     * Print the vector as a string
     * Ex:
     *  [1, 2, 3]
     *
     * @return string
     */
    public function __toString()
    {
        return '[' . implode(', ', $this->A) . ']';
    }

    /**************************************************************************
     * ArrayAccess INTERFACE
     **************************************************************************/

    public function offsetExists($i): bool
    {
        return isset($this->A[$i]);
    }

    public function offsetGet($i)
    {
        return $this->A[$i];
    }

    public function offsetSet($i, $value)
    {
        throw new \Exception('Vector class does not allow setting values');
    }

    public function offsetUnset($i)
    {
        throw new \Exception('Vector class does not allow unsetting values');
    }
}
