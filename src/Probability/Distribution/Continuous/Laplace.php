<?php
namespace Math\Probability\Distribution\Continuous;

class Laplace extends Continuous
{
    /**
     * Laplace distribution - probability density function
     *
     * https://en.wikipedia.org/wiki/Laplace_distribution
     *
     *            1      /  |x - μ| \
     * f(x|μ,b) = -- exp| - -------  |
     *            2b     \     b    /
     *
     * @param  number $μ location parameter
     * @param  number $b scale parameter (diversity)  b > 0
     * @param  number $x
     *
     * @return  float
     */
    public static function PDF($μ, $b, $x): float
    {
        if ($b <= 0) {
            throw new \Exception('b must be > 0');
        }
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
     * @param  number $μ location parameter
     * @param  number $b scale parameter (diversity)  b > 0
     * @param  number $x
     *
     * @return  float
     */
    public static function CDF($μ, $b, $x): float
    {
        if ($b <= 0) {
            throw new \Exception('b must be > 0');
        }
        if ($x < $μ) {
            return (1/2) * exp(($x - $μ) / $b);
        }
        return 1 - (1/2) * exp(-($x - $μ) / $b);
    }
    
     /**
     * Returns the mean of the distribution
     */
    public static function getMean($μ, $b)
    {
        return $μ;
    }
}
