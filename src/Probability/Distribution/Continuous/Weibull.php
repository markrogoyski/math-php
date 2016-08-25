<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Special;
use Math\Functions\Support;

class Weibull extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x ∈ [0,∞)
     * λ ∈ (0,∞)
     * k ∈ (0,∞)
     * p ∈ [0,1]
     * @var array
     */
    const LIMITS = [
        'x' => '[0,∞)',
        'λ' => '(0,∞)',
        'k' => '(0,∞)',
        'p' => '[0,1]',
    ];

    /**
     * Weibull distribution - probability density function
     *
     * https://en.wikipedia.org/wiki/Weibull_distribution
     *
     *        k  /x\ ᵏ⁻¹        ᵏ
     * f(x) = - | - |    ℯ⁻⁽x/λ⁾   for x ≥ 0
     *        λ  \λ/
     *
     * f(x) = 0                    for x < 0
     *
     * @param number $k shape parameter
     * @param number $λ scale parameter
     * @param number $x percentile (value to evaluate)
     * @return float
     */
    public static function PDF($x, $k, $λ)
    {
        Support::checkLimits(self::LIMITS, ['k' => $k, 'λ' => $λ]);

        if ($x < 0) {
            return 0;
        }
        $k／λ      = $k / $λ;
        $⟮x／λ⟯ᵏ⁻¹  = pow($x / $λ, $k - 1);
        $ℯ⁻⁽x／λ⁾ᵏ = exp(-pow($x / $λ, $k));
        return $k／λ * $⟮x／λ⟯ᵏ⁻¹ * $ℯ⁻⁽x／λ⁾ᵏ;
    }
    /**
     * Weibull distribution - cumulative distribution function
     * From 0 to x (lower CDF)
     * https://en.wikipedia.org/wiki/Weibull_distribution
     *
     * f(x) = 1 - ℯ⁻⁽x/λ⁾ for x ≥ 0
     * f(x) = 0           for x < 0
     *
     * @param number $k shape parameter
     * @param number $λ scale parameter
     * @param number $x percentile (value to evaluate)
     * @return float
     */
    public static function CDF($x, $k, $λ)
    {
        Support::checkLimits(self::LIMITS, ['k' => $k, 'λ' => $λ]);

        if ($x < 0) {
            return 0;
        }
        $ℯ⁻⁽x／λ⁾ᵏ = exp(-pow($x / $λ, $k));
        return 1 - $ℯ⁻⁽x／λ⁾ᵏ;
    }
    
    /**
     * Mean of the distribution
     *
     * μ = λΓ(1 + 1/k)
     *
     * @param number $k shape parameter
     * @param number $λ scale parameter
     *
     * @return number
     */
    public static function mean($k, $λ)
    {
        Support::checkLimits(self::LIMITS, ['k' => $k, 'λ' => $λ]);

        return $λ * Special::gamma(1 + 1 / $k);
    }
    
    /**
     * Inverse CDF (Quantile function)
     *
     * Q(p;k,λ) = λ(-ln(1 - p))¹/ᵏ
     *
     * @param number $p
     * @param number $k
     * @param number $λ
     *
     * @return number
     */
    public static function inverse($p, ...$params)
    {
        $k = $params[0];
        $λ = $params[1];
        Support::checkLimits(self::LIMITS, ['k' => $k, 'λ' => $λ, 'p' => $p]);

        return $λ * (-1 * log(1 - $p))**(1/$k);
    }
}
