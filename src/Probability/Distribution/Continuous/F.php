<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Special;
use Math\Functions\Support;

/**
 * F-distribution
 * https://en.wikipedia.org/wiki/F-distribution
 */
class F extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x  ∈ [0,∞)
     * d₁ ∈ (0,∞)
     * d₂ ∈ (0,∞)
     * @var array
     */
    const LIMITS = [
        'x'  => '[0,∞)',
        'd₁' => '(0,∞)',
        'd₂' => '(0,∞)',
    ];

    /**
     * Probability density function
     *
     *      __________________
     *     / (d₁ x)ᵈ¹ d₂ᵈ²
     *    /  ----------------
     *   √   (d₁ x + d₂)ᵈ¹⁺ᵈ²
     *   ---------------------
     *           / d₁  d₂ \
     *      x B |  --, --  |
     *           \ 2   2  /
     *
     * @param number $x  percentile ≥ 0
     * @param int    $d₁ degree of freedom v1 > 0
     * @param int    $d₂ degree of freedom v2 > 0
     *
     * @todo how to handle x = 0
     *
     * @return number probability
     */
    public static function PDF($x, int $d₁, int $d₂)
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'd₁' => $d₁, 'd₂' => $d₂]);

        // Numerator
        $⟮d₁x⟯ᵈ¹d₂ᵈ²                = ($d₁ * $x)**$d₁ * $d₂**$d₂;
        $⟮d₁x＋d₂⟯ᵈ¹⁺ᵈ²             = ($d₁ * $x + $d₂)**($d₁ + $d₂);
        $√⟮d₁x⟯ᵈ¹d₂ᵈ²／⟮d₁x＋d₂⟯ᵈ¹⁺ᵈ² = sqrt($⟮d₁x⟯ᵈ¹d₂ᵈ² / $⟮d₁x＋d₂⟯ᵈ¹⁺ᵈ²);

        // Denominator
        $xB⟮d₁／2、d₂／2⟯ = $x * Special::beta($d₁ / 2, $d₂ / 2);

        return $√⟮d₁x⟯ᵈ¹d₂ᵈ²／⟮d₁x＋d₂⟯ᵈ¹⁺ᵈ² / $xB⟮d₁／2、d₂／2⟯;
    }

    /**
     * Cumulative distribution function
     *
     *          / d₁  d₂ \
     *  I      |  --, --  |
     *   ᵈ¹ˣ    \ 2   2  /
     *   ------
     *   ᵈ¹ˣ⁺ᵈ²
     *
     * Where I is the regularized incomplete beta function.
     *
     * @param number $x  percentile ≥ 0
     * @param int    $d₁ degree of freedom v1 > 0
     * @param int    $d₂ degree of freedom v2 > 0
     *
     * @return number
     */
    public static function CDF($x, int $d₁, int $d₂)
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'd₁' => $d₁, 'd₂' => $d₂]);

        $ᵈ¹ˣ／d₁x＋d₂ = ($d₁ * $x) / ($d₁ * $x + $d₂);

        return Special::regularizedIncompleteBeta($ᵈ¹ˣ／d₁x＋d₂, $d₁/2, $d₂/2);
    }
    
    /**
     * Mean of the distribution
     *
     *       d₂
     * μ = ------  for d₂ > 2
     *     d₂ - 2
     *
     * @param int $d₁ degree of freedom v1 > 0
     * @param int $d₂ degree of freedom v2 > 0
     *
     * @return number
     */
    public static function mean(int $d₁, int $d₂)
    {
        if ($d₂ > 2) {
            return $d₂ / ($d₂ - 2);
        }

        return \NAN;
    }
    
    /**
     * Median of the distribution
     *
     * Since the median does not have a closed-form solution we will use Newton's Method to find it.
     * We can not only use a simple self::inverse because if $d₂ <= 2, the inverse function uses the 
     * median as a starting point to find the inverse.
     */
    public static function median(int $d₁, int $d₂)
    {
        if ($d₂ > 2) {
            return self::inverse(.5, $d₁, $d₂);
        }
        
        $tolerance = .0000000001;
        $dif       = $tolerance + 1;
        $guess     = 1;
        while ($dif > $tolerance) {
            // load the guess into the arguments
            $params[0] = $guess;
            $y         = self::CDF($guess, $d₁, $d₂);
            
            // Since the CDF is the integral of the PDF, the PDF is the derivative of the CDF
            $slope = self::PDF($guess, $d₁, $d₂);
            $del_y = $target - $y;
            $guess = $del_y / $slope + $guess;
            $dif   = abs($del_y);
        }
        return $guess;
    }
    
    /**
     * Mode of the distribution
     *
     *      (d₁ - 2) * d₂
     * μ = ---------------  for d₁ > 2
     *      (d₂ + 2) * d₁
     *
     * @param int $d₁ degree of freedom v1 > 0
     * @param int $d₂ degree of freedom v2 > 0
     *
     * @return number
     */
    public static function mode(int $d₁, int $d₂)
    {
        if ($d₁ > 2) {
            return ($d₁ - 2) * $d₂ / ($d₂ + 2) / $d₁;
        }
        return \NAN;
    }
}
