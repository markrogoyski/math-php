<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Special;
use MathPHP\Functions\Support;

/**
 * Student's t-distribution
 * https://en.wikipedia.org/wiki/Student%27s_t-distribution
 */
class StudentT extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * ν ∈ (0,∞)
     * @var array
     */
    const PARAMETER_LIMITS = [
        'ν' => '(0,∞)',
    ];

    /**
     * Distribution support bounds limits
     * t ∈ (-∞,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        't' => '(-∞,∞)',
    ];

    /** @var number Degrees of Freedom Parameter */
    protected $ν;

    /**
     * Constructor
     *
     * @param number $ν degrees of freedom ν > 0
     */
    public function __construct($ν)
    {
        parent::__construct($ν);
    }

    /**
     * Probability density function
     *
     *     / ν + 1 \
     *  Γ |  -----  |
     *     \   2   /    /    x² \ ⁻⁽ᵛ⁺¹⁾/²
     *  -------------  | 1 + --  |
     *   __    / ν \    \    ν  /
     *  √νπ Γ |  -  |
     *         \ 2 /
     *
     *
     * @param float $t t score
     *
     * @return number
     */
    public function pdf(float $t)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['t' => $t]);

        $ν = $this->ν;
        $π = \M_PI;

        // Numerator
        $Γ⟮⟮ν＋1⟯∕2⟯ = Special::gamma(($ν + 1) / 2);
        $⟮1＋t²∕ν⟯ = 1 + ($t**2 / $ν);
        $−⟮ν＋1⟯∕2 = -($ν + 1) / 2;

        // Denominator
        $√⟮νπ⟯  = sqrt($ν * $π);
        $Γ⟮ν∕2⟯ = Special::gamma($ν / 2);
        
        return ($Γ⟮⟮ν＋1⟯∕2⟯ * $⟮1＋t²∕ν⟯**$−⟮ν＋1⟯∕2) / ($√⟮νπ⟯ * $Γ⟮ν∕2⟯);
    }
    
    /**
     * Cumulative distribution function
     * Calculate the cumulative t value up to a point, left tail.
     *
     * cdf = 1 - ½Iₓ₍t₎(ν/2, ½)
     *
     *                 ν
     *  where x(t) = ------
     *               t² + ν
     *
     *        Iₓ₍t₎(ν/2, ½) is the regularized incomplete beta function
     *
     * @param float $t t score
     *
     * @return number
     */
    public function cdf(float $t)
    {
        Support::checkLimits(self::SUPPORT_LIMITS, ['t' => $t]);

        $ν = $this->ν;
        if ($t == 0) {
            return .5;
        }

        $x⟮t⟯  = $ν / ($t**2 + $ν);
        $ν／2 = $ν / 2;
        $½    = .5;
        $Iₓ   = Special::regularizedIncompleteBeta($x⟮t⟯, $ν／2, $½);

        if ($t < 0) {
            return $½ * $Iₓ;
        }

        // $t ≥ 0
        return 1 - $½ * $Iₓ;
    }

    /**
     * Inverse 2 tails
     * Find t such that the area greater than t and the area beneath -t is p.
     *
     * @param number $p Proportion of area
     *
     * @return number t-score
     */
    public function inverse2Tails($p)
    {
        Support::checkLimits(['p'  => '[0,1]'], ['p' => $p]);

        return $this->inverse(1 - $p / 2);
    }
    
    /**
     * Mean of the distribution
     *
     * μ = 0 if ν > 1
     * otherwise undefined
     *
     * @return number
     */
    public function mean()
    {
        if ($this->ν > 1) {
            return 0;
        }

        return \NAN;
    }
    
    /**
     * Median of the distribution
     *
     * μ = 0
     *
     * @return number
     */
    public function median()
    {
        return 0;
    }
}
