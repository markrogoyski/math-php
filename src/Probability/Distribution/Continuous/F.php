<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Special;
use MathPHP\Functions\Support;

/**
 * F-distribution
 * https://en.wikipedia.org/wiki/F-distribution
 */
class F extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * d₁ ∈ (0,∞)
     * d₂ ∈ (0,∞)
     * @var array
     */
    const PARAMETER_LIMITS = [
        'd₁' => '(0,∞)',
        'd₂' => '(0,∞)',
    ];

    /**
     * Distribution Support bounds limits
     * x  ∈ [0,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'x'  => '[0,∞)',
    ];

    /** @var number Degree of Freedom Parameter */
    protected $d₁;

    /** @var number Degree of Freedom Parameter */
    protected $d₂;

    /**
     * Constructor
     *
     * @param number $d₁ degree of freedom parameter d₁ > 0
     * @param number $d₂ degree of freedom parameter d₂ > 0
     */
    public function __construct($d₁, $d₂)
    {
        parent::__construct($d₁, $d₂);
    }

    /**
     * Probability density function
     *
     *      __________________
     *     / (d₁ x)ᵈ¹ d₂ᵈ²
     *    /  ----------------
     *   √   (d₁ x + d₂)ᵈ¹⁺ᵈ²
     *   ---------------------
     *           / d₁  d₂ \
     *      x B |  --, --  |
     *           \ 2   2  /
     *
     * @param float $x  percentile ≥ 0
     *
     * @todo how to handle x = 0
     *
     * @return number probability
     */
    public function pdf(float $x)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $d₁ = $this->d₁;
        $d₂ = $this->d₂;

        // Numerator
        $⟮d₁x⟯ᵈ¹d₂ᵈ²                = ($d₁ * $x)**$d₁ * $d₂**$d₂;
        $⟮d₁x＋d₂⟯ᵈ¹⁺ᵈ²             = ($d₁ * $x + $d₂)**($d₁ + $d₂);
        $√⟮d₁x⟯ᵈ¹d₂ᵈ²／⟮d₁x＋d₂⟯ᵈ¹⁺ᵈ² = sqrt($⟮d₁x⟯ᵈ¹d₂ᵈ² / $⟮d₁x＋d₂⟯ᵈ¹⁺ᵈ²);

        // Denominator
        $xB⟮d₁／2、d₂／2⟯ = $x * Special::beta($d₁ / 2, $d₂ / 2);

        return $√⟮d₁x⟯ᵈ¹d₂ᵈ²／⟮d₁x＋d₂⟯ᵈ¹⁺ᵈ² / $xB⟮d₁／2、d₂／2⟯;
    }

    /**
     * Cumulative distribution function
     *
     *          / d₁  d₂ \
     *  I      |  --, --  |
     *   ᵈ¹ˣ    \ 2   2  /
     *   ------
     *   ᵈ¹ˣ⁺ᵈ²
     *
     * Where I is the regularized incomplete beta function.
     *
     * @param float $x  percentile ≥ 0
     *
     * @return number
     */
    public function cdf(float $x)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $d₁ = $this->d₁;
        $d₂ = $this->d₂;

        $ᵈ¹ˣ／d₁x＋d₂ = ($d₁ * $x) / ($d₁ * $x + $d₂);

        return Special::regularizedIncompleteBeta($ᵈ¹ˣ／d₁x＋d₂, $d₁/2, $d₂/2);
    }
    
    /**
     * Mean of the distribution
     *
     *       d₂
     * μ = ------  for d₂ > 2
     *     d₂ - 2
     *
     * @return number
     */
    public function mean()
    {
        $d₂ = $this->d₂;

        if ($d₂ > 2) {
            return $d₂ / ($d₂ - 2);
        }

        return \NAN;
    }
}
