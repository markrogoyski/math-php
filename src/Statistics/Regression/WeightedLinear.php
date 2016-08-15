<?php
namespace Math\Statistics\Regression;
use Math\Statistics\Average;
use Math\Statistics\RandomVariable;
use Math\Probability\Distribution\Continuous\StudentT;
/**
 * Weighted linear regression - least squares method
 *
 * A model with a single explanatory variable.
 * Fits a straight line through the set of n points in such a way that makes
 * the sum of squared residuals of the model (that is, vertical distances
 * between the points of the data set and the fitted line) as small as possible.
 * https://en.wikipedia.org/wiki/Simple_linear_regression
 *
 * Having data points {(xᵢ, yᵢ), i = 1 ..., n }
 * Find the equation y = mx + b
 *
 *      _ _   __
 *      x y - xy
 * m = _________
 *      _     __
 *     (x)² - x²
 *
 *     _    _
 * b = y - mx
 */
class WeightedLinear extends Regression
{
    use WeightedLeastSquares;
    
    // An array of weights
    private $ws;
    
    public function __construct(array $points, array $ws)
    {
        $this->ws = $ws;
        parent::__construct($points);
    }
    
    /**
     * Calculates the regression parameters.
     *
     */
    public function calculate()
    {
        $parameters = $this->leastSquares($this->ys, $this->xs, $this->ws);
        $this->m = $parameters['m'];
        $this->b = $parameters['b'];
    }
    /**
     * Get regression parameters (coefficients)
     * m = slope
     * b = y intercept
     *
     * @return array [ m => number, b => number ]
     */
    public function getParameters(): array
    {
        return [
            'm' => $this->m,
            'b' => $this->b,
        ];
    }
    /**
     * Get regression equation (y = mx + b)
     *
     * @return string
     */
    public function getEquation(): string
    {
        return sprintf('y = %fx + %f', $this->m, $this->b);
    }
    /**
     * Evaluate the line equation from linear regression parameters for a value of x
     * y = mx + b
     *
     * @param number $x
     *
     * @return number y evaluated
     */
    public function evaluate($x)
    {
        $m  = $this->m;
        $b  = $this->b;
        $mx = $m * $x;
        return $mx + $b;
    }
}
