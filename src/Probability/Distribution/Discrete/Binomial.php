<?php
namespace MathPHP\Probability\Distribution\Discrete;

use MathPHP\Probability\Combinatorics;
use MathPHP\Functions\Support;

/**
 * Binomial distribution - probability mass function
 *
 * https://en.wikipedia.org/wiki/Binomial_distribution
 */
class Binomial extends Discrete
{
    /**
     * Distribution parameter bounds limits
     * n ∈ [0,∞)
     * p ∈ [0,1]
     * @var array
     */
    const PARAMETER_LIMITS = [
        'n' => '[0,∞)',
        'p' => '[0,1]',
    ];

    /**
     * Distribution support bounds limits
     * r ∈ [0,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'r' => '[0,∞)',
    ];

    /** @var int number of events */
    protected $n;

    /** @var float probability of success */
    protected $p;

    /**
     * Constructor
     *
     * @param int $n number of events n >= 0
     * @param float $p probability of success 0 <= p <= 1
     */
    public function __construct(int $n, float $p)
    {
        parent::__construct($n, $p);
    }

    /**
     * Probability mass function
     *
     * P(X = r) = nCr pʳ (1 - p)ⁿ⁻ʳ
     *
     * @param  int   $r number of successful events
     *
     * @return float
     */
    public function pmf(int $r): float
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['r' => $r]);

        $n = $this->n;
        $p = $this->p;
        $nCr       = Combinatorics::combinations($n, $r);
        $pʳ        = pow($p, $r);
        $⟮1 − p⟯ⁿ⁻ʳ = pow(1 - $p, $n - $r);

        return $nCr * $pʳ * $⟮1 − p⟯ⁿ⁻ʳ;
    }

    /**
     * Cumulative distribution function
     * Computes and sums the binomial distribution at each of the values in r.
     *
     * @param  int   $r number of successful events
     *
     * @return float
     */
    public function cdf(int $r): float
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['r' => $r]);

        $cumulative_probability = 0;
        for ($i = $r; $i >= 0; $i--) {
            $cumulative_probability += $this->pmf($i);
        }
        return $cumulative_probability;
    }
}
