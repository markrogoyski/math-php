<?php
namespace Math\Probability\Distribution\Discrete;

use Math\Probability\Combinatorics;

class Binomial extends Discrete
{
    /**
     * Binomial distribution - probability mass function
     * https://en.wikipedia.org/wiki/Binomial_distribution
     *
     * P(X = r) = nCr pʳ (1 - p)ⁿ⁻ʳ
     *
     * @param  int   $n number of events
     * @param  int   $r number of successful events
     * @param  float $P probability of success
     *
     * @return float
     */
    public static function PMF(int $n, int $r, float $p): float
    {
        if ($p < 0 || $p > 1) {
            throw new \Exception("Probability $p must be between 0 and 1.");
        }

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
        $cumulative_probability = 0;
        for ($i = $r; $i >= 0; $i--) {
            $cumulative_probability += self::PMF($n, $i, $p);
        }
        return $cumulative_probability;
    }
}
