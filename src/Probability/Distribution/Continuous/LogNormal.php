<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Special;
use MathPHP\Functions\Support;

class LogNormal extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * μ ∈ (-∞,∞)
     * σ ∈ (0,∞)
     * @var array
     */
    const PARAMETER_LIMITS = [
        'μ' => '(-∞,∞)',
        'σ' => '(0,∞)',
    ];

    /**
     * Distribution support bounds limits
     * x ∈ (0,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'x' => '(0,∞)',
    ];

     /** @var number location parameter */
    protected $μ;

     /** @var float scale parameter > 0 */
    protected $σ;

    /**
     * Constructor
     *
     * @param  number $μ location parameter
     * @param  number $λ scale parameter > 0
     */
    public function __construct($μ, $λ)
    {
        parent::__construct($μ, $λ);
    }

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
     * @param  float $x > 0
     *
     * @return number
     */
    public function pdf(float $x)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $μ = $this->μ;
        $σ = $this->σ;
        $π = \M_PI;

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
     * @param  float $x > 0
     *
     * @return number
     */
    public function cdf(float $x)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);

        $μ = $this->μ;
        $σ = $this->σ;

        $⟮ln x − μ⟯ = log($x) - $μ;
        $√2σ       = sqrt(2) * $σ;

        return 1/2 + 1/2 * Special::erf($⟮ln x − μ⟯ / $√2σ);
    }
    
    /**
     * Mean of the distribution
     *
     * μ = exp(μ + σ²/2)
     *
     * @return number
     */
    public function mean()
    {
        $μ = $this->μ;
        $σ = $this->σ;

        return exp($μ + ($σ**2 / 2));
    }
}
