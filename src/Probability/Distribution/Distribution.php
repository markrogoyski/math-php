<?php
namespace MathPHP\Probability\Distribution;

abstract class Distribution
{

	public static function sample_mean(array $xs)
	{
		//compute mean
		$mean = 0;
		foreach($xs as $x) {
			$mean += $x;
		}
		$mean /= count($xs);
		return $mean;
	}
	
	public static function sample_variance(array $xs)
	{
		$mean = sample_mean($xs);
		//compute variance
		$var = 0;
		foreach($xs as $x) {
			$s = $x-$mean;
			$var += $s*$s;
		}
		$var /= count($xs)-1;
		return $var;
	}
}
