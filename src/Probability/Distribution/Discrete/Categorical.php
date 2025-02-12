<?php

namespace MathPHP\Probability\Distribution\Discrete;

use MathPHP\Exception;

/**
 * Categorical distribution
 *
 * https://en.wikipedia.org/wiki/Categorical_distribution
 *
 * @property-read int   $k             number of categories
 * @property-read array $probabilities probabilities of each category
 */
class Categorical extends Discrete
{
    public const PARAMETER_LIMITS = [];

    /** @var int number of categories */
    private $k;

    /**
     * @var array<int|string, int|float>
     * Probability of each category
     * If associative array, category names are keys.
     * Otherwise, category names are array indexes.
     */
    private $probabilities;

    /**
     * @var array<int|string, int|float>|null
     * Cached CDF when pmf sorted from most probable category
     * to least probable category.
     * This is only useful for repeated sampling using Categorical::rand()
     */
    private $sorted_cdf = null;

    /**
     * Distribution constructor
     *
     * @param  int                       $k             number of categories
     * @param  array<int|string, int|float> $probabilities of each category - If associative array, category names are keys.
     *                                                 Otherwise, category names are array indexes.
     *
     * @throws Exception\BadParameterException if k does not indicate at least one category
     * @throws Exception\BadDataException      if there are not k probabilities
     * @throws Exception\BadDataException      if the probabilities do not add up to 1
     */
    public function __construct(int $k, array $probabilities)
    {
        // Must have at least one category
        if ($k <= 0) {
            throw new Exception\BadParameterException("k (number of categories) must be > 0. Given $k");
        }

        // Must have k number of probabilities
        if (\count($probabilities) != $k) {
            throw new Exception\BadDataException("Must have $k probabilities. Given " . \count($probabilities));
        }

        // Probabilities must add up to 1
        if (\round(\array_sum($probabilities), 1) != 1) {
            throw new Exception\BadDataException('Probabilities do not add up to 1.');
        }

        $this->k             = $k;
        $this->probabilities = $probabilities;

        parent::__construct();
    }

    /**
     * Probability mass function
     *
     * pmf = p(x = i) = pᵢ
     *
     * @param  int|float $x category name/number
     *
     * @return float
     *
     * @throws Exception\BadDataException if x is not a valid category
     */
    public function pmf($x): float
    {
        if (!isset($this->probabilities[$x])) {
            throw new Exception\BadDataException("$x is not a valid category");
        }

        return $this->probabilities[$x];
    }

    /**
     * Mode of the distribution
     *
     * i such that pᵢ = \max(p₁, ... pk)
     *
     * @return mixed Category name/number. Only returns one category in case on multimodal scenario.
     */
    public function mode()
    {
        $category = null;
        $pmax     = 0;

        foreach ($this->probabilities as $i => $pᵢ) {
            if ($pᵢ > $pmax) {
                $pmax     = $pᵢ;
                $category = $i;
            }
        }

        return $category;
    }

    /**
     * Magic getter for k and probabilities
     *
     * @param  string $name
     *
     * @return int|array<mixed>
     *
     * @throws Exception\BadDataException if $name is not a valid parameter
     */
    public function __get(string $name)
    {
        switch ($name) {
            case 'k':
            case 'probabilities':
                return $this->{$name};

            default:
                throw new Exception\BadDataException("$name is not a valid gettable parameter");
        }
    }

    /**
     * Sample a random category and return its key
     *
     * @return int|string
     */
    public function rand()
    {
        // calculate sorted cdf or use cached array
        if (is_null($this->sorted_cdf)) {
            // sort probabilities in descending order
            $sorted_probabilities = $this->probabilities; // copy as arsort works in place
            arsort($sorted_probabilities, SORT_NUMERIC);

            // calculate cdf
            $cdf = [];
            $sum = 0.0;
            foreach ($sorted_probabilities as $category => $pᵢ) {
                $sum += $pᵢ;
                $cdf[$category] = $sum;
            }

            $this->sorted_cdf = $cdf;
        }

        $rand = \random_int(0, \PHP_INT_MAX) / \PHP_INT_MAX; // [0, 1]

        // find first element in sorted cdf that is larger than $rand
        // for large arrays, performance could be improved by using binary search instead
        // also possible with array_find_key in PHP >=8.4
        foreach ($this->sorted_cdf as $category => $v) {
            if ($v >= $rand) {
                return $category;
            }
        }

        // should only end up here if due to rounding errors the sum of probabilities
        // is less than 1.0 and the generated random value is larger than the sum
        // should be very unlikely, but possible
        return array_key_last($this->sorted_cdf);
    }
}
