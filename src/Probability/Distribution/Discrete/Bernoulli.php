<?php
namespace Math\Probability\Distribution\Discrete;

use Math\Functions\Support;

class Bernoulli extends Discrete
{
    /**
     * Distribution parameter bounds limits
     * k ∈ [0,1]
     * p ∈ (0,1)
     * @var array
     */
    const LIMITS = [
        'k' => '[0,1]',
        'p' => '(0,1)',
    ];

    /**
     * Bernoulli distribution - probability mass function
     *
     * https://en.wikipedia.org/wiki/Bernoulli_distribution
     *
     * q = (1 - p)  for k = 0
     * q = p        for k = 1
     *
     * @param  int   $k number of successes  k ∈ {0, 1} (int type hint with checkLimit enforces this)
     * @param  float $p success probability  0 < p < 1
     *
     * @return  float
     */
    public static function PMF(int $k, float $p): float
    {
        Support::checkLimits(self::LIMITS, ['k' => $k, 'p' => $p]);

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
        Support::checkLimits(self::LIMITS, ['p' => $p]);

        if ($k < 0) {
            return 0;
        }
        if ($k < 1) {
            return 1 - $p;
        }
        return 1;
    }
}
