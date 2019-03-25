<?php
namespace MathPHP\Probability\Distribution\Discrete;

use MathPHP\Exception;

/**
 * Zipf's Law
 * https://en.wikipedia.org/wiki/Zipf's_law
 */
class Zipf extends Discrete
{
    /**
     * Distribution parameter bounds limits
     * s ∈ [0,∞)
     * N ∈ [1,∞)
     * @var array
     */
    const PARAMETER_LIMITS = [
        's' => '[0,∞)',
        'N' => '[1,∞)',
    ];

    /**
     * Distribution support bounds limits
     * k ∈ [1,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'k' => '[1,∞)',
    ];

    /** @var int number of events */
    protected $s;

    /** @var float probability of success */
    protected $N;

    /**
     * Constructor
     *
     * @param int $s lower boundary of the distribution
     * @param int $N upper boundary of the distribution
     *
     * @throws Exception\BadDataException if b is ≤ a
     */
    public function __construct($s, int $N)
    {
        parent::__construct($a, $b);
    }

    /**
     * Probability mass function
     *
     *            1
     * pmf = -----------
     *       kˢ * Hₙ,ₛ
     *
     *
     * @return number
     */
    public function pmf(int $k)
    {
        $s = $this->s;
        $N = $this->N;

        $n = $b - $a + 1;

        return 1 / $k ** $s NonInteger::generalizedHarmonicNumber($N, $s);
    }

    /**
     * Cumulative distribution function
     *
     *       k - a + 1
     * pmf = ---------
     *           n
     *
     * Percentile n = b - a + 1
     *
     * @param number $k percentile
     *
     * @return number
     */
    public function cdf(int $k)
    {
        $a = $this->a;
        $b = $this->b;

        if ($k < $a) {
            return 0;
        }
        if ($k > $b) {
            return 1;
        }

        $n = $b - $a + 1;

        return ($k - $a + 1) / $n;
    }

    /**
     * Mean of the distribution
     *
     *     a + b
     * μ = -----
     *       2
     *
     * @return number
     */
    public function mean()
    {
        $a = $this->a;
        $b = $this->b;

        return ($a + $b) / 2;
    }

    /**
     * Median of the distribution
     *
     *     a + b
     * μ = -----
     *       2
     *
     * @return number
     */
    public function median()
    {
        $a = $this->a;
        $b = $this->b;

        return ($a + $b) / 2;
    }
}
