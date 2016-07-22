<?php
namespace Math\Probability\Distribution\Discrete;

class NegativeBinomial extends Discrete
{
    /**
     * Negative binomial distribution (Pascal distribution) - probability mass function
     * https://en.wikipedia.org/wiki/Negative_binomial_distribution
     *
     * b(x; r, P) = ₓ₋₁Cᵣ₋₁ Pʳ * (1 - P)ˣ⁻ʳ
     *
     * @param  int   $x number of trials required to produce r successes
     * @param  int   $r number of successful events
     * @param  float $P probability of success on an individual trial
     *
     * @return float
     */
    public static function PMF(int $x, int $r, float $P): float
    {
        if ($P < 0 || $P > 1) {
            throw new \Exception("Probability $P must be between 0 and 1.");
        }
     
        $ₓ₋₁Cᵣ₋₁   = Combinatorics::combinations($x - 1, $r - 1);
        $Pʳ        = pow($P, $r);
        $⟮1 − P⟯ˣ⁻ʳ = pow(1 - $P, $x - $r);
    
        return $ₓ₋₁Cᵣ₋₁ * $Pʳ * $⟮1 − P⟯ˣ⁻ʳ;
    }
}
