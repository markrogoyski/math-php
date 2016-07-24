<?php
namespace Math\LinearAlgebra;

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
     * Dot product (inner product)
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
            function($a, $b) {
                return $a * $b;
            },
            $this->A, $B->getVector()
        ));
    }

    /**
     * Inner product (convience method for dot product)
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

        return new Matrix($R);
    }

    // ArrayAccess Interface

    public function offsetExists($i): boolean
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