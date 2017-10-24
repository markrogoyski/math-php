<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Special;
use MathPHP\Functions\Support;

/**
 * Gamma distribution
 * https://en.wikipedia.org/wiki/Gamma_distribution
 */
class Gamma extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * k ∈ (0,∞)
     * θ ∈ (0,∞)
     * @var array
     */
    const PARAMETER_LIMITS = [
        'k' => '(0,∞)',
        'θ' => '(0,∞)',
    ];

    /**
     * Distribution suport bounds limits
     * x ∈ (0,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'x' => '(0,∞)',
    ];

    /** @var number shape parameter k > 0 */
    protected $k;

    /** @var number shape parameter θ > 0 */
    protected $θ;

    /**
     * Constructor
     *
     * @param number $k shape parameter k > 0
     * @param number $θ scale parameter θ > 0
     */
    public function __construct($k, $θ)
    {
        parent::__construct($k, $θ);
    }

    /**
     * Probability density function
     *
     *                     ₓ
     *          1         ⁻-
     * pdf = ------ xᵏ⁻¹ e θ
     *       Γ(k)θᵏ
     *
     * @param float $x percentile      x > 0
     *
     * @return float
     */
    public function pdf(float $x): float
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $k = $this->k;
        $θ = $this->θ;

        $Γ⟮k⟯   = Special::Γ($k);
        $θᵏ    = $θ**$k;
        $Γ⟮k⟯θᵏ = $Γ⟮k⟯ * $θᵏ;

        $xᵏ⁻¹ = $x**($k - 1);
        $e    = \M_E**(-$x / $θ);

        return ($xᵏ⁻¹ * $e) / $Γ⟮k⟯θᵏ;
    }

    /**
     * Cumulative distribution function
     *
     *         1      /   x \
     * cdf = ----- γ | k, -  |
     *       Γ(k)     \   θ /
     *
     * @param float $x percentile      x > 0
     *
     * @return float
     */
    public function cdf(float $x): float
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $k = $this->k;
        $θ = $this->θ;

        $Γ⟮k⟯ = Special::Γ($k);
        $γ   = Special::γ($k, $x / $θ);

        return $γ / $Γ⟮k⟯;
    }

    /**
     * Mean of the distribution
     *
     * μ = k θ
     *
     * @return number
     */
    public function mean()
    {
        return $this->k * $this->θ;
    }
}
