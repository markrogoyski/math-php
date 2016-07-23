<?php
namespace Math\Probability\Distribution\Discrete;

class Pascal extends Discrete
{
    /**
     * Pascal distribution (convenience method for negative binomial distribution)
     * Probability mass function
     * https://en.wikipedia.org/wiki/Negative_binomial_distribution
     *
     * b(x; r, P) = ₓ₋₁Cᵣ₋₁ pʳ * (1 - P)ˣ⁻ʳ
     *
     * @param  int   $x number of trials required to produce r successes
     * @param  int   $r number of successful events
     * @param  float $P probability of success on an individual trial
     *
     * @return float
     */
    public static function PMF(int $x, int $r, float $P): float
    {
        return NegativeBinomial::PMF($x, $r, $P);
    }
}
