<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Special;
use MathPHP\Functions\Support;

/**
 * Dirichlet distribution
 * https://en.wikipedia.org/wiki/Dirichlet_distribution
 */

class Dirichlet extends Continuous
{
	public static function PDF(array $xs, array $as)
	{
		$top = 0;
		$bottom = 0;
		foreach($as as $a) {
			$top += log(Special::gamma($a));
			$bottom += $a;
		}
		
		$normalizing_constant =  Special::gamma($bottom)/exp($top);
		
		
		$m = 1;
		for( $i = 0; $i < count($xs); $i++) {
			$m *= pow($xs[$i],$as[$i]-1);
		}
		return $normalizing_constant*$m;
	}

	public static function avg_log_likelihood(array $xss, $as)
	{
		$sum = 0;
		foreach($xss as $xs) {
			$sum += log(PDF($xs,$as));
		}
		return $sum / count($xss);
	}

}
