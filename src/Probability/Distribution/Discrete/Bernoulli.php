<?php
namespace Math\Probability\Distribution\Discrete;

class BBernoulli extends Discrete
{
    /**
     * Bernoulli distribution - probability mass function
     *
     * https://en.wikipedia.org/wiki/Bernoulli_distribution
     *
     * q = (1 - p)  for k = 0
     * q = p        for k = 1
     *
     * @param  int   $k number of successes  k ∈ {0, 1}
     * @param  float $p success probability  0 < p < 1
     *
     * @return  float
     */
    public static function PMF(int $k, float $p): float
    {
        if (!in_array($k, [0, 1])) {
            throw new \Exception('k must be 0 or 1');
        }
        if ($p <= 0 || $p >= 1) {
            throw new \Exception('p must be 0 < p < 1');
        }

        if ($k === 0) {
            return 1 - $p;
        } else {
            return $p;
        }
    }
    /**
     * Bernoulli distribution - cumulative distribution function
     *
     * https://en.wikipedia.org/wiki/Bernoulli_distribution
     *
     * 0      for k < 0
     * 1 - p  for 0 ≤ k < 1
     * 1      for k ≥ 1
     *
     * @param  int   $k number of successes  k ∈ {0, 1}
     * @param  float $p success probability  0 < p < 1
     *
     * @return  float
     */
    public static function CDF(int $k, float $p): float
    {
        if ($p <= 0 || $p > 1) {
            throw new \Exception('p must be 0 < p ≤ 1');
        }

        if ($k < 0) {
            return 0;
        }
        if ($k < 1) {
            return 1 - $p;
        }
        return 1;
    }
}
