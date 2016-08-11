<?php
namespace Math\Probability\Distribution\Discrete;

use Math\Probability\Combinatorics;
use Math\Functions\Support;

class NegativeBinomial extends Discrete
{
    /**
     * Distribution parameter bounds limits
     * x ∈ [0,∞)
     * r ∈ [0,∞)
     * P ∈ [0,1]
     * @var array
     */
    const LIMITS = [
        'x' => '[0,∞)',
        'r' => '[0,∞)',
        'P' => '[0,1]',
    ];

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
        Support::checkLimits(self::LIMITS, ['x' => $x, 'r' => $r, 'P' => $P]);
     
        $ₓ₋₁Cᵣ₋₁   = Combinatorics::combinations($x - 1, $r - 1);
        $Pʳ        = pow($P, $r);
        $⟮1 − P⟯ˣ⁻ʳ = pow(1 - $P, $x - $r);
    
        return $ₓ₋₁Cᵣ₋₁ * $Pʳ * $⟮1 − P⟯ˣ⁻ʳ;
    }
}
