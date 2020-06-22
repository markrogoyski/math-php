<?php

namespace MathPHP\LinearAlgebra;

use MathPHP\Functions\Map;
use MathPHP\Exception;

/**
 * 1 x n Vector
 */
class Vector implements \Countable, \Iterator, \ArrayAccess, \JsonSerializable
{
    /** @var int Number of elements */
    private $n;

    /** @var array of numbers */
    private $A;

    /** @var int Iterator position */
    private $i;

    /**
     * Constructor
     *
     * @param array $A 1 x n vector
     *
     * @throws Exception\BadDataException if the Vector is empty
     */
    public function __construct(array $A)
    {
        $this->A = $A;
        $this->n = count($A);
        $this->i = 0;

        if ($this->n === 0) {
            throw new Exception\BadDataException('Vector cannot be empty');
        }
    }

    /**************************************************************************
     * BASIC VECTOR GETTERS
     *  - getVector
     *  - getN
     *  - get
     *  - asColumnMatrix
     *  - asRowMatrix
     **************************************************************************/

    /**
     * Get matrix
     *
     * @return array
     */
    public function getVector(): array
    {
        return $this->A;
    }

    /**
     * Get item count (n)
     *
     * @return int number of items
     */
    public function getN(): int
    {
        return $this->n;
    }

    /**
     * Get a specific value at position i
     *
     * @param  int $i index
     *
     * @return number
     *
     * @throws Exception\VectorException
     */
    public function get(int $i)
    {
        if ($i >= $this->n) {
            throw new Exception\VectorException("Element $i does not exist");
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
     *
     * @throws Exception\MathException
     */
    public function asColumnMatrix()
    {
        $matrix = array_map(
            function ($element) {
                return [$element];
            },
            $this->A
        );

        return new Matrix($matrix);
    }

    /**
     * Get the vector as a 1xn row matrix
     *
     * Example:
     *  V = [1, 2, 3]
     *  R = [
     *   [1, 2, 3]
     *  ]
     *
     * @return Matrix
     *
     * @throws Exception\MathException
     */
    public function asRowMatrix()
    {
        return new Matrix([$this->A]);
    }

    /**************************************************************************
     * VECTOR NUMERIC OPERATIONS - Return a number
     *  - sum
     *  - length (magnitude)
     *  - dotProduct (innerProduct)
     *  - perpDotProduct
     *  - radAngle
     *  - defAngle
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
     * Vector length (magnitude)
     * Same as l2-norm
     *
     * @return number
     */
    public function length()
    {
        return $this->l2Norm();
    }

    /**
     * Dot product (inner product) (A⋅B)
     * https://en.wikipedia.org/wiki/Dot_product
     *
     * @param Vector $B
     *
     * @return number
     *
     * @throws Exception\VectorException
     */
    public function dotProduct(Vector $B)
    {
        if ($B->getN() !== $this->n) {
            throw new Exception\VectorException('Vectors have different number of items');
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
     * Perp dot product (A⊥⋅B)
     * A modification of the two-dimensional dot product in which A is
     * replaced by the perpendicular vector rotated 90º degrees.
     * http://mathworld.wolfram.com/PerpDotProduct.html
     *
     * @param Vector $B
     *
     * @return number
     *
     * @throws Exception\VectorException
     */
    public function perpDotProduct(Vector $B)
    {
        if ($this->n !== 2 || $B->getN() !== 2) {
            throw new Exception\VectorException('Cannot do perp dot product unless both vectors are two-dimensional');
        }

        $A⊥ = $this->perpendicular();

        return $A⊥->dotProduct($B);
    }

    /**
     * Calculates the angle between two vectors with
     *
     *           A⋅B
     * cos α = -------
     *         |A|⋅|B|
     *
     * @param Vector $B
     * @param bool $returnDegrees Determines whether the angle should be returned in degrees or in rad
     * @return float The angle between the vectors in radians
     * @throws Exception\VectorException
     */
    public function cosineSimilarity(Vector $B, bool $returnDegrees = false)
    {
        if (count(array_unique($this->A)) === 1 && end($this->A) === 0) {
            throw new Exception\VectorException('The this vector is the null vector');
        }
        elseif (count(array_unique($B->A)) === 1 && end($B->A) === 0) {
            throw new Exception\VectorException('The parameter vector is the null vector');
        }

        $A⋅B     = $this->dotProduct($B);
        $│A│⋅│B│ = $this->l2Norm() * $B->l2Norm();

        $result = acos($A⋅B / $│A│⋅│B│);

        if(!$returnDegrees)
            return $result;
        else
            return rad2deg($result);
    }

    /**
     * Calculates the taxicap geometry (sometimes manhatten distance) between the vectors
     * https://en.wikipedia.org/wiki/Taxicab_geometry
     *
     * @param Vector $B
     * @return float|int
     * @throws Exception\VectorException
     */
    public function l1Distance(Vector $B)
    {
        return self::minkowskiDistance($B, 1);
    }

    /**
     * Calculates the euclidean distance between the vectors
     * https://en.wikipedia.org/wiki/Euclidean_distance
     *
     * @param Vector $B
     * @return float|int The euclidean distance between the vectors
     * @throws Exception\VectorException
     */
    public function l2Distance(Vector $B)
    {
        return self::minkowskiDistance($B, 2);
    }

    /**
     * Calculates the minkowski distance between vectors with
     *
     * ( Σ|x_i - y_i|^p )^1/p
     *
     * @param Vector $B
     * @param int $p
     * @return float|int
     * @throws Exception\VectorException
     */
    public function minkowskiDistance(Vector $B, int $p)
    {
        if ($B->getN() !== $this->n) {
            throw new Exception\VectorException('The vectors have a different number of elements');
        }

        $sum = 0;
        for ($i = 0; $i < $this->n; $i++) {
            $sum += pow(abs($this->A[$i] - $B->A[$i]), $p);
        }

        return pow($sum, 1 / $p);
    }

    /**************************************************************************
     * VECTOR OPERATIONS - Return a Vector or Matrix
     *  - add
     *  - subtract
     *  - multiply
     *  - divide
     *  - scalarMultiply
     **************************************************************************/

    /**
     * Add (A + B)
     *
     * A = [a₁, a₂, a₃]
     * B = [b₁, b₂, b₃]
     * A + B = [a₁ + b₁, a₂ + b₂, a₃ + b₃]
     *
     * @param Vector $B
     *
     * @return Vector
     *
     * @throws Exception\VectorException
     * @throws Exception\BadDataException
     */
    public function add(Vector $B): Vector
    {
        if ($B->getN() !== $this->n) {
            throw new Exception\VectorException('Vectors must be the same length for addition');
        }

        $R = Map\Multi::add($this->A, $B->getVector());
        return new Vector($R);
    }

    /**
     * Subtract (A - B)
     *
     * A = [a₁, a₂, a₃]
     * B = [b₁, b₂, b₃]
     * A - B = [a₁ - b₁, a₂ - b₂, a₃ - b₃]
     *
     * @param Vector $B
     *
     * @return Vector
     *
     * @throws Exception\VectorException
     */
    public function subtract(Vector $B): Vector
    {
        if ($B->getN() !== $this->n) {
            throw new Exception\VectorException('Vectors must be the same length for subtraction');
        }

        $R = Map\Multi::subtract($this->A, $B->getVector());
        return new Vector($R);
    }

    /**
     * Multiply (A * B)
     *
     * A = [a₁, a₂, a₃]
     * B = [b₁, b₂, b₃]
     * A * B = [a₁ * b₁, a₂ * b₂, a₃ * b₃]
     *
     * @param Vector $B
     *
     * @return Vector
     *
     * @throws Exception\VectorException
     * @throws Exception\BadDataException
     */
    public function multiply(Vector $B): Vector
    {
        if ($B->getN() !== $this->n) {
            throw new Exception\VectorException('Vectors must be the same length for multiplication');
        }

        $R = Map\Multi::multiply($this->A, $B->getVector());
        return new Vector($R);
    }

    /**
     * Divide (A / B)
     *
     * A = [a₁, a₂, a₃]
     * B = [b₁, b₂, b₃]
     * A / B = [a₁ / b₁, a₂ / b₂, a₃ / b₃]
     *
     * @param Vector $B
     *
     * @return Vector
     *
     * @throws Exception\VectorException
     * @throws Exception\BadDataException
     */
    public function divide(Vector $B): Vector
    {
        if ($B->getN() !== $this->n) {
            throw new Exception\VectorException('Vectors must be the same length for division');
        }

        $R = Map\Multi::divide($this->A, $B->getVector());
        return new Vector($R);
    }

    /**
     * Scalar multiplication (scale)
     * kA = [k * a₁, k * a₂, k * a₃ ...]
     *
     * @param number $k Scale factor
     *
     * @return Vector
     */
    public function scalarMultiply($k)
    {
        return new Vector(Map\Single::multiply($this->A, $k));
    }

    /**
     * Scalar divide
     * kA = [k / a₁, k / a₂, k / a₃ ...]
     *
     * @param number $k Scale factor
     *
     * @return Vector
     */
    public function scalarDivide($k)
    {
        return new Vector(Map\Single::divide($this->A, $k));
    }

    /**************************************************************************
     * VECTOR ADVANCED OPERATIONS - Return a Vector or Matrix
     *  - outerProduct
     *  - directProduct (dyadic)
     *  - crossProduct
     *  - normalize
     *  - perpendicular
     *  - projection
     *  - kroneckerProduct
     **************************************************************************/

    /**
     * Outer product (A⨂B)
     * https://en.wikipedia.org/wiki/Outer_product
     * Same as direct product.
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
     * Direct product (dyadic)
     * https://en.wikipedia.org/wiki/Direct_product
     * http://mathworld.wolfram.com/VectorDirectProduct.html
     *
     *              [A₁]              [A₁B₁ A₁B₂ A₁B₃]
     * AB = A⨂Bᵀ = [A₂] [B₁ B₂ B₃] = [A₂B₁ A₂B₂ A₂B₃]
     *              [A₃]              [A₃B₁ A₃B₂ A₃B₃]
     *
     * Where ⨂ is the Kronecker product.
     *
     * @param Vector $B
     *
     * @return Matrix
     */
    public function directProduct(Vector $B): Matrix
    {
        $A  = $this->asColumnMatrix();
        $Bᵀ = $B->asRowMatrix();

        return $A->kroneckerProduct($Bᵀ);
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
     *
     * @throws Exception\VectorException
     */
    public function crossProduct(Vector $B): Vector
    {
        if ($B->getN() !== 3 || $this->n !== 3) {
            throw new Exception\VectorException('Vectors must have 3 items');
        }

        $s1 =   ($this->A[1] * $B[2]) - ($this->A[2] * $B[1]);
        $s2 = -(($this->A[0] * $B[2]) - ($this->A[2] * $B[0]));
        $s3 =   ($this->A[0] * $B[1]) - ($this->A[1] * $B[0]);

        return new Vector([$s1, $s2, $s3]);
    }

    /**
     * Normalize (Â)
     * The normalized vector Â is a vector in the same direction of A
     * but with a norm (length) of 1. It is a unit vector.
     * http://mathworld.wolfram.com/NormalizedVector.html
     *
     *      A
     * Â ≡ ---
     *     |A|
     *
     *  where |A| is the l²-norm (|A|₂)
     *
     * @return Vector
     */
    public function normalize(): Vector
    {
        $│A│ = $this->l2Norm();

        return $this->scalarDivide($│A│);
    }

    /**
     * Perpendicular (A⊥)
     * A vector perpendicular to A (A-perp) with the length that is rotated 90º
     * counter clockwise.
     *
     *     [a]       [-b]
     * A = [b]  A⊥ = [a]
     *
     * @return Vector
     *
     * @throws Exception\VectorException
     */
    public function perpendicular(): Vector
    {
        if ($this->n !== 2) {
            throw new Exception\VectorException('Perpendicular operation only makes sense for 2D vector. 3D and higher vectors have infinite perpendular vectors.');
        }

        $A⊥ = [-$this->A[1], $this->A[0]];

        return new Vector($A⊥);
    }

    /**
     * Projection of A onto B
     * https://en.wikipedia.org/wiki/Vector_projection#Vector_projection
     *
     *          A⋅B
     * projᵇA = --- B
     *          |B|²
     *
     * @param Vector $B
     *
     * @return Vector
     */
    public function projection(Vector $B): Vector
    {
        $A⋅B  = $this->dotProduct($B);
        $│B│² = ($B->l2Norm()) ** 2;

        return $B->scalarMultiply($A⋅B / $│B│²);
    }

    /**
     * Perpendicular of A on B
     * https://en.wikipedia.org/wiki/Vector_projection#Vector_projection
     *
     *          A⋅B⊥
     * perpᵇA = ---- B⊥
     *          |B|²
     *
     * @param Vector $B
     *
     * @return Vector
     */
    public function perp(Vector $B): Vector
    {
        $A⋅B⊥ = $B->perpDotProduct($this);
        $│B│² = ($B->l2Norm()) ** 2;
        $B⊥   = $B->perpendicular();

        return $B⊥->scalarMultiply($A⋅B⊥ / $│B│²);
    }

    /**
     * Kronecker product A⨂B
     * The kronecker product of two column vectors is a column vector.
     *
     * Example:  [1]    [3]   [3]
     *           [2] ⨂ [4] = [4]
     *                        [6]
     *                        [8]
     *
     * @param  Vector $B
     *
     * @return Vector
     */
    public function kroneckerProduct(Vector $B): Vector
    {
        $A = $this->asColumnMatrix();
        $B = $B->asColumnMatrix();

        $A⨂B = $A->kroneckerProduct($B);

        return new Vector($A⨂B->getColumn(0));
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
     * Used to normalize a vector.
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
     * @param number $p
     *
     * @return number
     */
    public function pNorm($p)
    {
        return array_sum(Map\Single::pow(Map\Single::abs($this->A), $p)) ** (1 / $p);
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
     * Countable INTERFACE
     **************************************************************************/

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->A);
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
        return isset($this->A[$i]);
    }

    /**
     * @param mixed $i
     * @return mixed
     */
    public function offsetGet($i)
    {
        return $this->A[$i];
    }

    /**
     * @param mixed $i
     * @param mixed $value
     * @throws Exception\VectorException
     */
    public function offsetSet($i, $value)
    {
        throw new Exception\VectorException('Vector class does not allow setting values');
    }

    /**
     * @param mixed $i
     * @throws Exception\VectorException
     */
    public function offsetUnset($i)
    {
        throw new Exception\VectorException('Vector class does not allow unsetting values');
    }

    /**************************************************************************
     * JsonSerializable INTERFACE
     **************************************************************************/

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->A;
    }

    /**************************************************************************
     * Iterator INTERFACE
     **************************************************************************/

    public function rewind()
    {
        $this->i = 0;
    }

    public function current()
    {
        return $this->A[$this->i];
    }

    public function key()
    {
        return $this->i;
    }

    public function next()
    {
        ++$this->i;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->A[$this->i]);
    }
}
