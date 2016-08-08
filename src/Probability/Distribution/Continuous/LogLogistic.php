<?php
namespace Math\Probability\Distribution\Continuous;

/**
 * Log-logistic distribution
 * Also known as the Fisk distribution.
 * https://en.wikipedia.org/wiki/Log-logistic_distribution
 */
class LogLogistic extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x ∈ [0,∞)
     * α ∈ (0,∞)
     * β ∈ (0,∞)
     * @var array
     */
    const LIMITS = [
        'x' => '[0,∞)',
        'α' => '(0,∞)',
        'β' => '(0,∞)',
    ];

    /**
     * Probability density function
     *
     *              (β/α)(x/α)ᵝ⁻¹
     * f(x; α, β) = -------------
     *              (1 + (x/α)ᵝ)²
     *
     * @param number $α scale parameter (α > 0)
     * @param number $β shape parameter (β > 0)
     * @param number $x (x > 0)
     */
    public static function PDF($α, $β, $x)
    {
        self::checkLimits(self::LIMITS, ['x' => $x, 'α' => $α, 'β' => $β]);

        $⟮β／α⟯⟮x／α⟯ᵝ⁻¹  = ($β / $α) * pow($x / $α, $β - 1);
        $⟮1 ＋ ⟮x／α⟯ᵝ⟯² = pow(1 + ($x / $α)**$β, 2);
        return $⟮β／α⟯⟮x／α⟯ᵝ⁻¹ / $⟮1 ＋ ⟮x／α⟯ᵝ⟯²;
    }
    /**
     * Log-logistic distribution - cumulative distribution function
     * Also known as the Fisk distribution.
     * https://en.wikipedia.org/wiki/Log-logistic_distribution
     *
     *                   1
     * F(x; α, β) = -----------
     *              1 + (x/α)⁻ᵝ
     *
     * @param number $α scale parameter (α > 0)
     * @param number $β shape parameter (β > 0)
     * @param number $x (x > 0)
     */
    public static function CDF($α, $β, $x)
    {
        self::checkLimits(self::LIMITS, ['x' => $x, 'α' => $α, 'β' => $β]);

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
     * @param number $α scale parameter (α > 0)
     * @param number $β shape parameter (β > 0)
     *
     * @return number
     */
    public static function mean($α, $β)
    {
        self::checkLimits(self::LIMITS, ['α' => $α, 'β' => $β]);

        $π = \M_PI;

        if ($β > 1) {
            return (($α * $π) / $β) / sin($π / $β);
        }

        return null;
    }
}
