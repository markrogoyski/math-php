<?php
namespace Math\Probability\Distribution\Discrete;

use Math\Probability\Combinatorics;
use Math\Functions\Support;

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
     * Binomial distribution - probability mass function
     * https://en.wikipedia.org/wiki/Binomial_distribution
     *
     * P(X = r) = nCr pʳ (1 - p)ⁿ⁻ʳ
     *
     * @param  int   $n number of events
     * @param  int   $r number of successful events
     * @param  float $p probability of success
     *
     * @return float
     */
    public static function PMF(int $n, int $r, float $p): float
    {
        Support::checkLimits(self::LIMITS, ['n' => $n, 'r' => $r, 'p' => $p]);

        $nCr       = Combinatorics::combinations($n, $r);
        $pʳ        = pow($p, $r);
        $⟮1 − p⟯ⁿ⁻ʳ = pow(1 - $p, $n - $r);

        return $nCr * $pʳ * $⟮1 − p⟯ⁿ⁻ʳ;
    }

    /**
     * Binomial distribution - cumulative distribution function
     * Computes and sums the binomial distribution at each of the values in r.
     *
     * @param  int   $n number of events
     * @param  int   $r number of successful events
     * @param  float $P probability of success
     *
     * @return float
     */
    public static function CDF(int $n, int $r, float $p): float
    {
        Support::checkLimits(self::LIMITS, ['n' => $n, 'r' => $r, 'p' => $p]);

        $cumulative_probability = 0;
        for ($i = $r; $i >= 0; $i--) {
            $cumulative_probability += self::PMF($n, $i, $p);
        }
        return $cumulative_probability;
    }
}
