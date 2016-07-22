<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Special;

class LogNormal extends Continuous
{
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
     * @param  number $x
     * @param  number $μ
     * @param  number $σ
     * @return number
     */
    public static function PDF($x, $μ, $σ)
    {
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
     * @param  number $x
     * @param  number $μ
     * @param  number $σ
     * @return number
     */
    public static function CDF($x, $μ, $σ)
    {
        $π          = \M_PI;
        $⟮ln x − μ⟯ = log($x) - $μ;
        $√2σ       = sqrt(2) * $σ;
        return 1/2 + 1/2 * Special::erf($⟮ln x − μ⟯ / $√2σ);
    }
}
