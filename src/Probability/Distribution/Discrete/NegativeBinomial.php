<?php
namespace Math\Probability\Distribution\Discrete;

use Math\Probability\Combinatorics;
use Math\Functions\Special;
use Math\Functions\Support;

/**
 * Negative binomial distribution (Pascal distribution)
 * https://en.wikipedia.org/wiki/Negative_binomial_distribution
 */
class NegativeBinomial extends Discrete
{
    /**
     * Distribution parameter bounds limits
     * x ∈ [0,∞)
     * r ∈ [0,∞)
     * p ∈ [0,1]
     * @var array
     */
    const LIMITS = [
        'x' => '[0,∞)',
        'r' => '[0,∞)',
        'p' => '[0,1]',
    ];

    /**
     * Probability mass function
     *
     * b(x; r, p) = ₓ₋₁Cᵣ₋₁ pʳ * (1 - p)ˣ⁻ʳ
     *
     * @param  int   $x number of trials required to produce r successes
     * @param  int   $r number of successful events
     * @param  float $p probability of success on an individual trial
     *
     * @return float
     */
    public static function PMF(int $x, int $r, float $p): float
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'r' => $r, 'p' => $p]);
     
        $ₓ₋₁Cᵣ₋₁   = Combinatorics::combinations($x - 1, $r - 1);
        $pʳ        = pow($p, $r);
        $⟮1 − p⟯ˣ⁻ʳ = pow(1 - $p, $x - $r);
    
        return $ₓ₋₁Cᵣ₋₁ * $pʳ * $⟮1 − p⟯ˣ⁻ʳ;
    }
}
