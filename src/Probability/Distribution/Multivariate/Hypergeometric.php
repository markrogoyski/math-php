<?php

namespace MathPHP\Probability\Distribution\Multivariate;

use MathPHP\Probability\Combinatorics;
use MathPHP\Exception;

/**
 * Multinomial distribution (multivariate)
 *
 * https://en.wikipedia.org/wiki/Multinomial_distribution
 */
class Hypergeometric
{
    /** @var array */
    protected $quantities;

    /**
     * Multinomial constructor
     *
     * @param   array $quantities
     *
     * @throws Exception\BadDataException if the probabilities do not add up to 1
     */
    public function __construct(array $quantities)
    {
        foreach ($quantities as $value) {
            if (!is_int($value)) {
                throw new Exception\BadDataException('Values must be integers.');
            }
            $this->quantities[] = $value;
        }
    }

    /**
     * Probability mass function
     *
     * @param  array $picks
     *
     * @return float
     *
     * @throws Exception\BadDataException if the number of frequencies does not match the number of probabilities
     */
    public function pmf(array $picks): float
    {
        // Must have a probability for each frequency
        if (count($picks) !== count($this->quantities)) {
            throw new Exception\BadDataException('Number of quantities does not match number of picks.');
        }
        foreach ($picks as $pick) {
            if (!is_int($pick)) {
                throw new Exception\BadDataException("Picks must be integers. $pick is not an int.");
            }
        }

        /** @var int $n */
        $n   = array_sum($picks);
        $total = array_sum($this->quantities);
        $product = 1;

        foreach ($picks as $i => $pick) {
            $product *= Combinatorics::combinations($this->quantities[$i], $pick);
        }

        return $product / Combinatorics::combinations($total, $n);
    }
}
