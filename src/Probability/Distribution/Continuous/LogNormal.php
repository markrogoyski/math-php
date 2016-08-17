<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Special;
use Math\Functions\Support;

class LogNormal extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x ∈ (0,∞)
     * μ ∈ (-∞,∞)
     * σ ∈ (0,∞)
     * @var array
     */
    const LIMITS = [
        'x' => '(0,∞)',
        'μ' => '(-∞,∞)',
        'σ' => '(0,∞)',
    ];

    /**
     * Log normal distribution - probability density function
     *
     * https://en.wikipedia.org/wiki/Log-normal_distribution
     *
     *                 (ln x - μ)²
     *         1     - ----------
     * pdf = ----- ℯ       2σ²
     *       xσ√2π
     *
     * @param  number $x > 0
     * @param  number $μ location parameter
     * @param  number $σ scale parameter > 0
     * @return number
     */
    public static function PDF($x, $μ, $σ)
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'μ' => $μ, 'σ' => $σ]);

        $π          = \M_PI;
        $xσ√2π      = $x * $σ * sqrt(2 * $π);
        $⟮ln x − μ⟯² = pow(log($x) - $μ, 2);
        $σ²         = $σ**2;
        return (1 / $xσ√2π) * exp(-($⟮ln x − μ⟯² / (2 *$σ²)));
    }
    /**
     * Log normal distribution - cumulative distribution function
     *
     * https://en.wikipedia.org/wiki/Log-normal_distribution
     *
     *       1   1      / ln x - μ \
     * cdf = - + - erf |  --------  |
     *       2   2      \   √2σ     /
     *
     * @param  number $x > 0
     * @param  number $μ location parameter
     * @param  number $σ scale parameter > 0
     * @return number
     */
    public static function CDF($x, $μ, $σ)
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'μ' => $μ, 'σ' => $σ]);

        $π          = \M_PI;
        $⟮ln x − μ⟯ = log($x) - $μ;
        $√2σ       = sqrt(2) * $σ;
        return 1/2 + 1/2 * Special::erf($⟮ln x − μ⟯ / $√2σ);
    }
    
    /**
     * Mean of the distribution
     *
     * μ = exp(μ + σ²/2)
     *
     * @param  number $μ
     * @param  number $σ
     *
     * @return number
     */
    public static function mean($μ, $σ)
    {
        Support::checkLimits(self::LIMITS, ['μ' => $μ, 'σ' => $σ]);

        return exp($μ + ($σ**2 / 2));
    }
}
