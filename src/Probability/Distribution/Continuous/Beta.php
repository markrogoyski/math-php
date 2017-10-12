<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Special;
use MathPHP\Functions\Support;

/**
 * Beta distribution
 * https://en.wikipedia.org/wiki/Beta_distribution
 */
class Beta extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * α ∈ (0,∞)
     * β ∈ (0,∞)
     * @var array
     */
    const PARAMETER_LIMITS = [
        'α' => '(0,∞)',
        'β' => '(0,∞)',
    ];

    /**
     * Distribution support bounds limits
     * x ∈ [0,1]
     * @var array
     */
    const SUPPORT_LIMITS = [
        'x' => '[0,1]',
    ];

    /** @var number Shape Parameter */
    protected $α;

    /** @var number Shape Parameter */
    protected $β;

    /**
     * Constructor
     *
     * @param number $α shape parameter α > 0
     * @param number $β shape parameter β > 0
     */
    public function __construct($α, $β)
    {
        parent::__construct($α, $β);
    }

    /**
     * Probability density function
     *
     *       xᵃ⁻¹(1 - x)ᵝ⁻¹
     * pdf = --------------
     *           B(α,β)
     *
     * @param float $x x ∈ (0,1)
     *
     * @return float
     */
    public function pdf(float $x)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $α = $this->α;
        $β = $this->β;

        $xᵃ⁻¹     = pow($x, $α - 1);
        $⟮1 − x⟯ᵝ⁻¹ = pow(1 - $x, $β - 1);
        $B⟮α、β⟯    = Special::beta($α, $β);
        return ($xᵃ⁻¹ * $⟮1 − x⟯ᵝ⁻¹) / $B⟮α、β⟯;
    }
    
    /**
     * Cumulative distribution function
     *
     * cdf = Iₓ(α,β)
     *
     * @param float $x x ∈ (0,1)
     *
     * @return float
     */
    public function cdf(float $x)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $α = $this->α;
        $β = $this->β;

        return Special::regularizedIncompleteBeta($x, $α, $β);
    }
    
    /**
     * Mean of the distribution
     *
     *       α
     * μ = -----
     *     α + β
     *
     * @return number
     */
    public function mean()
    {
        $α = $this->α;
        $β = $this->β;

        return $α / ($α + $β);
    }
}
