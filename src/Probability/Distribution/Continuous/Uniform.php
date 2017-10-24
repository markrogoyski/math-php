<?php
namespace MathPHP\Probability\Distribution\Continuous;

class Uniform extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * a ∈ (-∞,∞)
     * b ∈ (-∞,∞)
     * @var array
     */
    const PARAMETER_LIMITS = [
        'a' => '(-∞,∞)',
        'b' => '(-∞,∞)',
    ];

    /**
     * Distribution support bounds limits
     * x ∈ (-∞,∞)
     * @var array
     */
    const SUPPORT_LIMITS = [
        'x' => '(-∞,∞)',
    ];
    
    /** @var number Lower Bound Parameter */
    protected $a;

    /** @var number Upper Bound Parameter */
    protected $b;

    /**
     * Constructor
     *
     * @param number $a lower bound parameter
     * @param number $b upper bound parameter
     */
    public function __construct($a, $b)
    {
        parent::__construct($a, $b);
    }

    /**
     * Continuous uniform distribution - probability desnsity function
     * https://en.wikipedia.org/wiki/Uniform_distribution_(continuous)
     *
     *         1
     * pdf = -----  for a ≤ x ≤ b
     *       b - a
     *
     * pdf = 0      for x < a, x > b
     *
     * @param float $x percentile
     *
     * @return number
     */
    public function pdf(float $x)
    {
        $a = $this->a;
        $b = $this->b;
        if ($x < $a || $x > $b) {
            return 0;
        }
        return 1 / ($b - $a);
    }
    
    /**
     * Continuous uniform distribution - cumulative distribution function
     * https://en.wikipedia.org/wiki/Uniform_distribution_(continuous)
     *
     * cdf = 0      for x < a
     *
     *       x - a
     * cdf = -----  for a ≤ x < b
     *       b - a
     *
     * cdf = 1      x ≥ b
     *
     * @param float $x percentile
     *
     * @return number
     */
    public function cdf(float $x)
    {
        $a = $this->a;
        $b = $this->b;
        if ($x < $a) {
            return 0;
        }
        if ($x >= $b) {
            return 1;
        }
        return ($x - $a) / ($b - $a);
    }
    
    /**
     * Mean of the distribution
     *
     *     a + b
     * μ = -----
     *       2
     *
     *
     * @return number
     */
    public function mean()
    {
        return ($this->a + $this->b) / 2;
    }
}
