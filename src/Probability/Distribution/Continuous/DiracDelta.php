<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Special;
use Math\Functions\Support;

/**
 * Dirac Delta Function
 * https://en.wikipedia.org/wiki/Dirac_delta_function
 */
class DiracDelta extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x  ∈ (-∞,∞)
     *
     * @var array
     */
    const LIMITS = [
        'x'  => '(-∞,∞)',
    ];
    /**
     * Probability density function
     *
     *
     *
     *          /‾
     *         |  +∞,   x = 0
     * δ(x) = <
     *         |  0,    x ≠ 0
     *          \_
     *
     *
     * @param number $x
     *
     * @return number probability
     */
    public static function PDF($x)
    {
        if ($x == 0) {
            return \INF;
        } else {
            return 0;
        }
    }
    /**
     * Cumulative distribution function
     * https://en.wikipedia.org/wiki/Heaviside_step_function
     *
     *  |\+∞
     *  |   δ(x) dx = 1
     * \|-∞
     *
     * @param number $x
     * @todo how to handle x = 0, depending on context, some say CDF=.5 @ x=0
     * @return number
     */
    public static function CDF($x)
    {
        if ($x >= 0) {
            return 1;
        }
        return 0;
    }
    
    /**
     * The inverse of the CDF function
     *
     */
    public static function inverse($p, ...$params)
    {
        return 0;
    }
}
