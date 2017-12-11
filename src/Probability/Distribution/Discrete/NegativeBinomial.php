<?php
namespace MathPHP\Probability\Distribution\Discrete;

use MathPHP\Probability\Combinatorics;
use MathPHP\Functions\Special;
use MathPHP\Functions\Support;

/**
 * Negative binomial distribution (Pascal distribution)
 * https://en.wikipedia.org/wiki/Negative_binomial_distribution
 */
class NegativeBinomial extends Discrete
{
    /**
     * Distribution parameter bounds limits
     * r ∈ [0,∞)
     * p ∈ [0,1]
     * @var array
     */
    const PARAMETER_LIMITS = [
        'r' => '[0,∞)',
        'p' => '[0,1]',
    ];

    /**
     * Distribution support bounds limits
     * x ∈ [0,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'x' => '[0,∞)',
    ];

    /** @var int number of successful events */
    protected $r;

    /** @var float probability of success on an individual trial */
    protected $p;

    /**
     * Constructor
     *
     * @param  int   $r number of successful events
     * @param  float $p probability of success on an individual trial
     */
    public function __construct(int $r, float $p)
    {
        parent::__construct($r, $p);
    }

    /**
     * Probability mass function
     *
     * b(x; r, p) = ₓ₋₁Cᵣ₋₁ pʳ * (1 - p)ˣ⁻ʳ
     *
     * @param  int   $x number of trials required to produce r successes
     *
     * @return float
     */
    public function pmf(int $x): float
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $r = $this->r;
        $p = $this->p;
     
        $ₓ₋₁Cᵣ₋₁   = Combinatorics::combinations($x - 1, $r - 1);
        $pʳ        = pow($p, $r);
        $⟮1 − p⟯ˣ⁻ʳ = pow(1 - $p, $x - $r);
    
        return $ₓ₋₁Cᵣ₋₁ * $pʳ * $⟮1 − p⟯ˣ⁻ʳ;
    }
}
