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
     * x ∈ [0,1]
     * α ∈ (0,∞)
     * β ∈ (0,∞)
     * @var array
     */
    const LIMITS = [
        'x' => '[0,1]',
        'α' => '(0,∞)',
        'β' => '(0,∞)',
    ];
    
    /**
     * Probability density function
     *
     *       xᵃ⁻¹(1 - x)ᵝ⁻¹
     * pdf = --------------
     *           B(α,β)
     *
     * @param number $x x ∈ (0,1)
     *
     * @return float
     */
    public function pdf($x)
    {
        $limit['x'] = static::LIMITS['x'];
        Support::checkLimits($limit, ['x' => $x]);

        $xᵃ⁻¹     = pow($x, $this->α - 1);
        $⟮1 − x⟯ᵝ⁻¹ = pow(1 - $x, $this->β - 1);
        $B⟮α、β⟯    = Special::beta($this->α, $this->β);
        return ($xᵃ⁻¹ * $⟮1 − x⟯ᵝ⁻¹) / $B⟮α、β⟯;
    }
    
    /**
     * Cumulative distribution function
     *
     * cdf = Iₓ(α,β)
     *
     * @param number $x x ∈ (0,1)
     *
     * @return float
     */
    public function cdf($x)
    {
        $limit['x'] = static::LIMITS['x'];
        Support::checkLimits($limit, ['x' => $x]);

        return Special::regularizedIncompleteBeta($x, $this->α, $this->β);
    }
    
    /**
     * Mean of the distribution
     *
     *       α
     * μ = -----
     *     α + β
     *
     * @param number $α shape parameter α > 0
     * @param number $β shape parameter β > 0
     *
     * @return number
     */
    public function mean()
    {

        return $this->α / ($this->α + $this->β);
    }
}
