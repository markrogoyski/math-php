<?php
namespace MathPHP\Probability\Distribution\Discrete;

use MathPHP\Functions\Support;

/**
 * Bernoulli distribution
 *
 * https://en.wikipedia.org/wiki/Bernoulli_distribution
 */
class Bernoulli extends Discrete
{
    /**
     * Distribution parameter bounds limits
     * p ∈ (0,1)
     * @var array
     */
    const PARAMETER_LIMITS = [
        'p' => '(0,1)',
    ];

    /**
     * Distribution support bounds limits
     * k ∈ [0,1]
     * p ∈ (0,1)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'k' => '[0,1]',
    ];

    /** @var number probability of success */
    protected $p;

    /**
     * Constructor
     *
     * @param float $p success probability  0 < p < 1
     */
    public function __construct(float $p)
    {
        parent::__construct($p);
    }

    /**
     * Probability mass function
     *
     * q = (1 - p)  for k = 0
     * q = p        for k = 1
     *
     * @param  int   $k number of successes  k ∈ {0, 1}
     *
     * @return  float
     */
    public function pmf(int $k): float
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['k' => $k]);

        if ($k === 0) {
            return 1 - $this->p;
        } else {
            return $this->p;
        }
    }
    /**
     * Cumulative distribution function
     *
     * 0      for k < 0
     * 1 - p  for 0 ≤ k < 1
     * 1      for k ≥ 1
     *
     * @param  int $k number of successes  k ∈ {0, 1}
     *
     * @return  float
     */
    public function cdf(int $k): float
    {
        if ($k < 0) {
            return 0;
        }
        if ($k < 1) {
            return 1 - $this->p;
        }
        return 1;
    }
}
