<?php
namespace MathPHP\Probability\Distribution\Discrete;

/**
 * Pascal distribution (convenience class for negative binomial distribution)
 * https://en.wikipedia.org/wiki/Negative_binomial_distribution
 */
class Pascal extends Discrete
{
    /**
     * Probability mass function
     *
     * b(x; r, P) = ₓ₋₁Cᵣ₋₁ pʳ * (1 - P)ˣ⁻ʳ
     *
     * @param  int   $x number of trials required to produce r successes
     * @param  int   $r number of successful events
     * @param  float $P probability of success on an individual trial
     *
     * @return float
     */
    public static function pmf(int $x, int $r, float $P): float
    {
        return NegativeBinomial::pmf($x, $r, $P);
    }
}
