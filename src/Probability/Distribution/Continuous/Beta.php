<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Special;

class Beta extends Continuous
{
    
    /**
     * The limits of each of the distribution's parameters using ISO 31-11 notation. 
     * 
     * 
     * (a,b) = a <  x <  b
     * [a,b) = a <= x <  b
     * (a,b] = a <  x <= b
     * [a,b] = a <= x <= b
     */ 
    protected static $distribution_limits = [
        'x' => '[0,1]',
        'α' => '(0,∞)',
        'β' => '(0,∞)',
    ];   
    
    /**
     * Beta distribution - probability density function
     * https://en.wikipedia.org/wiki/Beta_distribution
     *
     *       xᵃ⁻¹(1 - x)ᵝ⁻¹
     * pdf = --------------
     *           B(α,β)
     *
     * @param number $α shape parameter α > 0
     * @param number $β shape parameter β > 0
     * @param number $x x ∈ (0,1)
     *
     * @return float
     */
    public static function PDF($x, $α, $β)
    {
        self::check_limits(self::$distribution_limits, [ 'x' => $x, 'α' => $α, β => $β]);
        $xᵃ⁻¹     = pow($x, $α - 1);
        $⟮1 − x⟯ᵝ⁻¹ = pow(1 - $x, $β - 1);
        $B⟮α、β⟯    = Special::beta($α, $β);
        return ($xᵃ⁻¹ * $⟮1 − x⟯ᵝ⁻¹) / $B⟮α、β⟯;
    }
    
    /**
     * Cumulative distribution function
     *
     * cdf = Iₓ(α,β)
     *
     * @param number $α shape parameter α > 0
     * @param number $β shape parameter β > 0
     * @param number $x x ∈ (0,1)
     *
     * @return float
     */
    public static function CDF($x, $α, $β)
    {
        self::check_limits(self::$distribution_limits, [ 'x' => $x, 'α' => $α, β => $β]);

        return Special::regularizedIncompleteBeta($x, $α, $β);
    }
    
    /**
     * Mean of the distribution
     *
     *       α
     * μ = -----
     *     α + β
     *
     * @param number $α shape parameter α > 0
     * @param number $β shape parameter β > 0
     *
     * @return number
     */
    public static function mean($α, $β)
    {
        self::check_limits(self::$distribution_limits, [ 'α' => $α, β => $β]);

        return $α / ($α + $β);
    }
}
