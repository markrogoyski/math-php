<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Special;
use MathPHP\Functions\Support;

class Weibull extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * λ ∈ (0,∞)
     * k ∈ (0,∞)
     * @var array
     */
    const PARAMETER_LIMITS = [
        'k' => '(0,∞)',
        'λ' => '(0,∞)',
    ];

    /**
     * Distribution support bounds limits
     * x ∈ [0,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'x' => '(-∞,∞)',
    ];

    /** @var number Shape Parameter */
    protected $k;

    /** @var number Scale Parameter */
    protected $λ;

    /**
     * Constructor
     *
     * @param number $k shape parameter k > 0
     * @param number $λ scale parameter λ > 0
     */
    public function __construct($k, $λ)
    {
        parent::__construct($k, $λ);
    }

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
     * @param float $x percentile (value to evaluate)
     * @return float
     */
    public function pdf(float $x)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);
        if ($x < 0) {
            return 0;
        }

        $k = $this->k;
        $λ = $this->λ;

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
     * @param float $x percentile (value to evaluate)
     * @return float
     */
    public function cdf(float $x)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['x' => $x]);
        if ($x < 0) {
            return 0;
        }

        $k = $this->k;
        $λ = $this->λ;

        $ℯ⁻⁽x／λ⁾ᵏ = exp(-pow($x / $λ, $k));
        return 1 - $ℯ⁻⁽x／λ⁾ᵏ;
    }
    
    /**
     * Mean of the distribution
     *
     * μ = λΓ(1 + 1/k)
     *
     * @return number
     */
    public function mean()
    {
        $k = $this->k;
        $λ = $this->λ;

        return $λ * Special::gamma(1 + 1 / $k);
    }
    
    /**
     * Inverse CDF (Quantile function)
     *
     * Q(p;k,λ) = λ(-ln(1 - p))¹/ᵏ
     *
     * @param float $p
     *
     * @return number
     */
    public function inverse(float $p)
    {
        Support::checkLimits(['p' => '[0,1]'], ['p' => $p]);
        $k = $this->k;
        $λ = $this->λ;

        return $λ * (-1 * log(1 - $p))**(1/$k);
    }
}
