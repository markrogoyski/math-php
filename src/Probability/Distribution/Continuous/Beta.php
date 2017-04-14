<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Special;
use MathPHP\Functions\Support;

/**
 * Beta distribution
 * https://en.wikipedia.org/wiki/Beta_distribution
 */
class Beta extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x ∈ [0,1]
     * α ∈ (0,∞)
     * β ∈ (0,∞)
     * @var array
     */
    const LIMITS = [
        'x' => '[0,1]',
        'α' => '(0,∞)',
        'β' => '(0,∞)',
    ];
    
    /**
     * Probability density function
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
        Support::checkLimits(self::LIMITS, ['x' => $x, 'α' => $α, 'β' => $β]);

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
        Support::checkLimits(self::LIMITS, ['x' => $x, 'α' => $α, 'β' => $β]);

        return Special::regularizedIncompleteBeta($x, $α, $β);
    }

    public static function mom_estimate(array $xs)
    {
    	$mean = sample_mean($xs);
    	$var = sample_variance($xs);
    	
    	//compute a and B
    	$a = ($mean)*($mean*(1-$mean)/$var-1);
    	$B = (1-$mean)*($mean*(1-$mean)/$var-1);
    	 
    	return array($a,$B);
    }

    public static function MLE(array $xs)
    {
    	return MLE_Newton($xs);
    }
    
    public static function MLE_Newton(array $xs)
    {
    	$params = mom_estimate($xs);
    	$delta = 1;
    	$delta_mult = 0.66; //slight overlap
    	$done = 0.0001;
    	
    	$best = avg_log_likelihood($xs, $params[0], $params[1]);
    	while( $delta > $done) {
    		//correct a
    		$a_up = $params[0]*exp($delta);
    		$a_dn = $params[0]*exp(-$delta);
    		$up = avg_log_likelihood($xs, $a_up, $params[1]);
    		$dn = avg_log_likelihood($xs, $a_dn, $params[1]);
    		if( $up > $best) {
    			$params[0] = $a_up;
    			$best = $up;
    		}
    		if( $dn > $best) {
    			$params[0] = $a_dn;
    			$best = $dn;
    		}
    		//correct b
    		$a_up = $params[1]*exp($delta);
    		$a_dn = $params[1]*exp(-$delta);
    		$up = avg_log_likelihood($xs, $params[0], $a_up);
    		$dn = avg_log_likelihood($xs, $params[0], $a_dn);
    		if( $up > $best) {
    			$params[1] = $a_up;
    			$best = $up;
    		}
    		if( $dn > $best) {
    			$params[1] = $a_dn;
    			$best = $dn;
    		}
			//zoom in
    		$delta *= $delta_mult;
    	}
    
    	return $params;
    }
    
    public static function avg_log_likelihood(array $xs, $α, $β)
    {
    	$sum = 0;
    	foreach($xs as $x) {
    		$sum += log(PDF($x,$α,$β),E);
    	}
    	return $sum / count($xs);
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
        Support::checkLimits(self::LIMITS, ['α' => $α, 'β' => $β]);

        return $α / ($α + $β);
    }
}
