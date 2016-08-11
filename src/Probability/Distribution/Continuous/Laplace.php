<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Support;

class Laplace extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x ∈ (-∞,∞)
     * μ ∈ (-∞,∞)
     * b ∈ (0,∞)
     * @var array
     */
    const LIMITS = [
        'x' => '(-∞,∞)',
        'μ' => '(-∞,∞)',
        'b' => '(0,∞)',
    ];

    /**
     * Laplace distribution - probability density function
     *
     * https://en.wikipedia.org/wiki/Laplace_distribution
     *
     *            1      /  |x - μ| \
     * f(x|μ,b) = -- exp| - -------  |
     *            2b     \     b    /
     *
     * @param  number $x
     * @param  number $μ location parameter
     * @param  number $b scale parameter (diversity)  b > 0
     *
     * @return  float
     */
    public static function PDF($x, $μ, $b): float
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'μ' => $μ, 'b' => $b]);

        return (1 / (2 * $b)) * exp(-( abs($x - $μ)/$b ));
    }
    /**
     * Laplace distribution - cumulative distribution function
     * From -∞ to x (lower CDF)
     * https://en.wikipedia.org/wiki/Laplace_distribution
     *
     *        1     / x - μ \
     * F(x) = - exp|  ------ |       if x < μ
     *        2     \   b   /
     *
     *            1     /  x - μ \
     * F(x) = 1 - - exp| - ------ |  if x ≥ μ
     *            2     \    b   /
     *
     * @param  number $x
     * @param  number $μ location parameter
     * @param  number $b scale parameter (diversity)  b > 0
     *
     * @return  float
     */
    public static function CDF($x, $μ, $b): float
    {
        Support::checkLimits(self::LIMITS, ['x' => $x, 'μ' => $μ, 'b' => $b]);

        if ($x < $μ) {
            return (1/2) * exp(($x - $μ) / $b);
        }
        return 1 - (1/2) * exp(-($x - $μ) / $b);
    }
    
    /**
     * Mean of the distribution
     *
     * μ = μ
     *
     * @param  number $μ location parameter
     * @param  number $b scale parameter (diversity)  b > 0
     *
     * @return μ
     */
    public static function mean($μ, $b)
    {
        Support::checkLimits(self::LIMITS, ['μ' => $μ, 'b' => $b]);

        return $μ;
    }
}
