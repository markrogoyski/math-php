<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Support;

/**
 * Log-logistic distribution
 * Also known as the Fisk distribution.
 * https://en.wikipedia.org/wiki/Log-logistic_distribution
 */
class LogLogistic extends Continuous
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
     * x ∈ [0,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'x' => '[0,∞)',
    ];

     /** @var number Scale Parameter */
    protected $α;

    /** @var number Shape Parameter */
    protected $β;

    /**
     * Constructor
     *
     * @param number $α scale parameter α > 0
     * @param number $β shape parameter β > 0
     */
    public function __construct($α, $β)
    {
        parent::__construct($α, $β);
    }

    /**
     * Probability density function
     *
     *              (β/α)(x/α)ᵝ⁻¹
     * f(x; α, β) = -------------
     *              (1 + (x/α)ᵝ)²
     *
     * @param float $x (x > 0)
     *
     * @return number
     */

    public function pdf(float $x)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $α = $this->α;
        $β = $this->β;

        $⟮β／α⟯⟮x／α⟯ᵝ⁻¹  = ($β / $α) * pow($x / $α, $β - 1);
        $⟮1 ＋ ⟮x／α⟯ᵝ⟯² = pow(1 + ($x / $α)**$β, 2);
        return $⟮β／α⟯⟮x／α⟯ᵝ⁻¹ / $⟮1 ＋ ⟮x／α⟯ᵝ⟯²;
    }

    /**
     * Cumulative distribution function
     *
     *                   1
     * F(x; α, β) = -----------
     *              1 + (x/α)⁻ᵝ
     *
     * @param float $x (x > 0)
     *
     * @return number
     */
    public function cdf(float $x)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $α = $this->α;
        $β = $this->β;

        $⟮x／α⟯⁻ᵝ = pow($x / $α, -$β);
        return 1 / (1 + $⟮x／α⟯⁻ᵝ);
    }
    
    /**
     * Mean of the distribution
     *
     *      απ / β
     * μ = --------  if β > 1, else undefined
     *     sin(π/β)
     *
     * @return number
     */
    public function mean()
    {
        $α = $this->α;
        $β = $this->β;
        $π = \M_PI;

        if ($β > 1) {
            return (($α * $π) / $β) / sin($π / $β);
        }

        return \NAN;
    }
    
    /**
     * Inverse CDF (Quantile function)
     *
     *                 /   p   \ 1/β
     * F⁻¹(p;α,β) = α |  -----  |
     *                 \ 1 - p /
     *
     * @param float $p
     *
     * @return number
     */
    public function inverse(float $p)
    {
        Support::checkLimits(['p' => '[0,1]'], ['p' => $p]);

        $α = $this->α;
        $β = $this->β;
        
        return $α * ($p / (1 - $p))**(1/$β);
    }
}
