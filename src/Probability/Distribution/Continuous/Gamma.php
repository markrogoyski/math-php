<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Special;
use MathPHP\Functions\Support;

/**
 * Gamma distribution
 * https://en.wikipedia.org/wiki/Gamma_distribution
 */
class Gamma extends Continuous
{

    /**
     * Distribution parameter bounds limits
     * x ∈ (0,∞)
     * o ∈ (0,∞)
     * k ∈ (0,∞)
     *
     * @var array
     */
    const LIMITS = [
        'x' => '(0,∞)',
        'o' => '(0,∞)',
        'k' => '(0,∞)'
    ];

    public static function PDF($x, $o, $k)
    {
        Support::checkLimits(self::LIMITS, [
            'x' => $x,
            'o' => $o,
            'k' => $k
        ]);
        
        $normalizing_constant = 1.0 / (pow($o, $k) * Special::gamma($k));
        return $normalizing_constant * pow($x, $k - 1) * exp(- $x / $o);
    }

    public static function CDF($x, $o, $k)
    {
        Support::checkLimits(self::LIMITS, [
            'x' => $x,
            'o' => $o,
            'k' => $k
        ]);
        
        return Special::lowerIncompleteGamma($k, $x / $o) / Special::gamma($k);
    }

    public static function avg_log_likelihood(array $xs, $α, $β)
    {
        $sum = 0;
        foreach ($xs as $x) {
            $sum += log(PDF($x, $α, $β));
        }
        return $sum / count($xs);
    }
}
