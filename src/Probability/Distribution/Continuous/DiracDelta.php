<?php
namespace MathPHP\Probability\Distribution\Continuous;

use MathPHP\Functions\Special;
use MathPHP\Functions\Support;

/**
 * Dirac Delta Function
 * https://en.wikipedia.org/wiki/Dirac_delta_function
 */
class DiracDelta extends Continuous
{
    /**
     * Distribution support bounds limits
     * x  ∈ (-∞,∞)
     *
     * @var array
     */
    const SUPPORT_LIMITS = [
        'x'  => '(-∞,∞)',
    ];

    /**
     * Distribution parameter bounds limits
     *
     * @var array
     */
    const PARAMETER_LIMITS = [];

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Probability density function
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
    public static function pdf($x)
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
    public static function cdf($x)
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
    public function inverse($p)
    {
        return 0;
    }
}
