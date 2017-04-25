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
     * r ∈ [0,∞)
     * p ∈ [0,1]
     * @var array
     */
    const LIMITS = [
        'n' => '[0,∞)',
        'r' => '[0,∞)',
        'p' => '[0,1]',
    ];

    /**
     * Probability mass function
     *
     * P(X = r) = nCr pʳ (1 - p)ⁿ⁻ʳ
     *
     * @param  int   $n number of events
     * @param  int   $r number of successful events
     * @param  float $p probability of success
     *
     * @return float
     */
    public static function pmf(int $n, int $r, float $p): float
    {
        Support::checkLimits(self::LIMITS, ['n' => $n, 'r' => $r, 'p' => $p]);

        $nCr       = Combinatorics::combinations($n, $r);
        $pʳ        = pow($p, $r);
        $⟮1 − p⟯ⁿ⁻ʳ = pow(1 - $p, $n - $r);

        return $nCr * $pʳ * $⟮1 − p⟯ⁿ⁻ʳ;
    }

    /**
     * Cumulative distribution function
     * Computes and sums the binomial distribution at each of the values in r.
     *
     * @param  int   $n number of events
     * @param  int   $r number of successful events
     * @param  float $P probability of success
     *
     * @return float
     */
    public static function cdf(int $n, int $r, float $p): float
    {
        Support::checkLimits(self::LIMITS, ['n' => $n, 'r' => $r, 'p' => $p]);

        $cumulative_probability = 0;
        for ($i = $r; $i >= 0; $i--) {
            $cumulative_probability += self::pmf($n, $i, $p);
        }
        return $cumulative_probability;
    }
}
