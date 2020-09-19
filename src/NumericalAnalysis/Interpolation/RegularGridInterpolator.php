<?php

namespace MathPHP\NumericalAnalysis\Interpolation;

use MathPHP\Exception;
use MathPHP\Util;

/**
 * Interpolation on a regular grid in arbitrary dimensions
 *
 * The data must be defined on a regular grid;
 * the grid spacing however may be uneven.
 * Linear and nearest-neighbor interpolation are supported.
 * After setting up the interpolator object,
 * the interpolation method (linear or nearest) may be chosen at each evaluation.
 */
class RegularGridInterpolator
{
    const METHOD_LINEAR  = 'linear';
    const METHOD_NEAREST = 'nearest';

    /** @var string */
    private $method;

    /** @var array[] */
    private $grid;

    /** @var array */
    private $values;

    private function countdim($array)
    {
        if (is_array(reset($array))) {
            $return = $this->countdim(reset($array)) + 1;
        } else {
            $return = 1;
        }

        return $return;
    }

    /**
     * @param array  $points
     * @param array  $values
     * @param string $method
     *
     * @throws Exception\BadDataException
     */
    public function __construct(array $points, array $values, string $method = 'linear')
    {
        if (!\in_array($method, [self::METHOD_LINEAR, self::METHOD_NEAREST])) {
            throw new Exception\BadDataException("Method '{$method}' is not defined");
        }
        $this->method    = $method;
        $valuesDimension = $this->countdim($values);
        $pointsCount     = \count($points);

        if ($pointsCount > $valuesDimension) {
            throw new Exception\BadDataException(sprintf('There are %d point arrays, but values has %d ' . 'dimensions', $pointsCount, $valuesDimension));
        }

        $this->grid   = $points;
        $this->values = $values;
    }

    /**
     * @param array $points The points defining the regular grid in n dimensions.
     * @param array $values The data on the regular grid in n dimensions.
     * @param string $method The method of interpolation to perform. Supported are “linear” and “nearest”
     * @return static
     * @throws Exception\BadDataException
     */
    public static function interpolate(
        array $points,
        array $values,
        $method = self::METHOD_LINEAR
    ): callable {
        return new self($points, $values, $method);
    }

    /**
     * @param  array $xi
     * @param  string|null  $method
     * @return float
     * @throws Exception\BadDataException
     */
    public function __invoke(array $xi, string $method = null): float
    {
        $method = $method ?? $this->method;
        if (!\in_array($method, [self::METHOD_LINEAR, self::METHOD_NEAREST])) {
            throw new Exception\BadDataException("Method '{$method}' is not defined");
        }

        $gridDimension  = \count($this->grid);
        $pointDimension = \count($xi);
        if (\count($xi) != $gridDimension) {
            throw new Exception\BadDataException('The requested sample points xi have dimension ' . "{$pointDimension}, but this RegularGridInterpolator has " . "dimension {$gridDimension}");
        }

        list($indices, $normDistances) = $this->findIndices($xi);

        return $method === self::METHOD_LINEAR
            ? $this->evaluateLinear($indices, $normDistances)
            : $this->evaluateNearest($indices, $normDistances);
    }

    /**
     * @param $indices
     * @param $normDistances
     * @return float|int
     */
    private function evaluateLinear($indices, $normDistances)
    {
        foreach ($indices as $i) {
            $edges[] = [$i, $i + 1];
        }
        $edges[] = 1; // pass last argument (repeat)
        $edges = $this->product(...$edges); // create many to many links

        $values = 0.;
        foreach ($edges as $edge_indices) {
            $weight = 1.;
            foreach ($this->multipleIterator($edge_indices, $indices, $normDistances) as list($ei, $i, $yi)) {
                $weight *= ($ei == $i) ? 1 - $yi : $yi;
            }
            $values += ($this->flatCall($this->values, $edge_indices) * $weight);
        }

        return $values;
    }

    /**
     * @param $indices
     * @param $normDistances
     * @return array|mixed
     */
    private function evaluateNearest($indices, $normDistances)
    {
        $idxRes = [];
        foreach ($this->multipleIterator($indices, $normDistances) as list($i, $yi)) {
            $idxRes[] = $yi <= .5 ? $i : $i + 1;
        }

        return $this->flatCall($this->values, $idxRes);
    }

    /**
     * @param array $xi 1nd array ( search point = [x,y,z ....] )
     *
     * @return array[]
     */
    private function findIndices($xi): array
    {
        // find relevant edges between which xi are situated
        $indices = [];
        // compute distance to lower edge in unity units
        $normDistances = [];

        // iterate through dimensions x-y-z-...>
        foreach ($this->grid as $xInd => $grid) {
            $x = $xi[$xInd];
            // $grid - 1nd array, example all x values (or all y..)
            // $x float, search point: x or y or z...
            $i = Util\Search::sorted($grid, $x) - 1; // min match index

            $gridSize = \count($grid); //Column count
            if ($i < 0) {
                $i = 0;
            }
            if ($i > $gridSize - 2) {
                $i = $gridSize - 2;
            }

            $indices[] = $i;
            $lessValue = $grid[$i];
            $greaterValue = $grid[$i + 1];
            $normDistances[] = ($x - $lessValue) / ($greaterValue - $lessValue);
        }
        // $indices - indices in grid, for search point
        // $normDistances - norm distance, for search point
        return [$indices, $normDistances];
    }

    /**
     * Dynamically accessing multidimensional array value.
     *
     * @param array $data
     * @param array $keys
     * @return array|mixed
     */
    private function flatCall(array $data, array $keys)
    {
        $current = $data;
        foreach ($keys as $key) {
            $current = $current[$key];
        }

        return $current;
    }

    /**
     * Is used to find the cartesian product from the given iterator,
     * output is lexicographic ordered
     *
     * @param mixed ...$args
     *
     * @return \Generator
     */
    private function product(/*...$iterables[, $repeat]*/ ...$args)
    {
        $repeat = array_pop($args);
        $iterables = array_map([self::class, 'iter'], $args);

        $pools = array_merge(...array_fill(0, $repeat, $iterables));
        $result = [[]];

        foreach ($pools as $pool) {
            $result_inner = [];

            foreach ($result as $x) {
                foreach ($pool as $y) {
                    $result_inner[] = array_merge($x, [$y]);
                }
            }

            $result = $result_inner;
        }

        foreach ($result as $prod) {
            yield $prod;
        }
    }

    /**
     * @param $var
     * @return \ArrayIterator|\IteratorIterator
     */
    private static function iter($var)
    {
        switch (true) {
            case $var instanceof \Iterator:
                return $var;

            case $var instanceof \Traversable:
                return new \IteratorIterator($var);

            case \is_array($var):
                return new \ArrayIterator($var);

            default:
                $type = \gettype($var);
                throw new \InvalidArgumentException("'{$type}' type is not iterable");
        }
    }

    /**
     * Python port zip.
     *
     * @param mixed ...$iterables
     *
     * @return \MultipleIterator
     */
    private function multipleIterator(...$iterables): \MultipleIterator
    {
        $multipleIterator = new \MultipleIterator();
        foreach ($iterables as $iterable) {
            $multipleIterator->attachIterator(self::iter($iterable));
        }

        return $multipleIterator;
    }
}
