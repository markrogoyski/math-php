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
    public $distribution_limits = [
        [ // x ∈ (0,1)
            'parameter' => 'x',
            'lower_endpoint' => '(',
            'lower_value' => 0,
            'upper_endpoint' => ')',
            'upper_value' => 1,
        ],
        [ // α > 0
            'parameter' => 'α',
            'lower_endpoint' => '(',
            'lower_value' => 0,
            'upper_endpoint' => ')',
            'upper_value' => INF,
        ],
        [ // β > 0
            'parameter' => 'β',
            'lower_endpoint' => '(',
            'lower_value' => 0,
            'upper_endpoint' => ')',
            'upper_value' => INF,
        ],
        
    
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
        self::check_limits($x, $α, $β);
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
        self::check_limits($x, $α, $β);

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
        // How do we use this for functions which do not use x.
        self::check_limits(.5, $α, $β);

        return $α / ($α + $β);
    }
}
