<?php
namespace Math\LinearAlgebra;

use Math\Functions\Map;

/**
 * 1 x n Vector
 */
class Vector implements \ArrayAccess
{

    /**
     * Number of elecments
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

    /**
     * ArrayAccess INTERFACE
     */

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
