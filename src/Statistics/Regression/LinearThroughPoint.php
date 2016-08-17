<?php
namespace Math\Statistics\Regression;

use Math\Statistics\Average;
use Math\Functions\Map\Multi;
use Math\Functions\Map\Single;
use Math\Probability\Distribution\Continuous\StudentT;

/**
 * Linear Regression Through a Fixed Point - least squares method
 *
 * A model with a single explanatory variable.
 * Fits a straight line through the set of n points in such a way that makes
 * the sum of squared residuals of the model (that is, vertical distances
 * between the points of the data set and the fitted line) as small as possible.
 * https://en.wikipedia.org/wiki/Simple_linear_regression
 *
 * Having data points {(xᵢ, yᵢ), i = 1 ..., n }
 * Find the equation y = mx + b
 * such that the line passes through the point (v,w)
 *
 *      ∑((x-v)(y-w))
 * m =  _____________
 *
 *         ∑(x-v)²
 *
 * b = w - m * v
 */
class LinearThroughPoint extends Regression
{
    use Methods\LeastSquares, Models\LinearModel;
    /**
     * Given a set of data ($points) and a point($force), perform a least squares
     * regression of the data, such that the regression is forced to pass through
     * the specified point.
     *
     * This procedure is most frequently used with $force = [0,0], the origin.
     *
     */
    public function __construct(array $points, array $force = [0,0])
    {
        $this->v = $force[0];
        $this->w = $force[1];
        parent::__construct($points);
    }
    
    /**
     * Calculates the regression parameters.
     */
    public function calculate()
    {
        $v = $this->v;
        $w = $this->w;
        
        $x’ = Single::subtract($this->xs, $v);
        $y’ = Single::subtract($this->ys, $w);
        $parameters = $this->leastSquares($y’, $x’, 1, 0)->transpose()[0];
        $this->m = $parameters[0];
        $this->b = $this->w - $this->m * $this->v;
        $this->parameters = [$this->b, $this->m];
    }

    public function getCI($x, $p)
    {
        $v  = $this->v;
        $x’ = Single::subtract($this->xs, $v);
        $∑x = array_sum(Single::square($x’));

        // Degrees of freedom
        $ν = $this->n - 1;

        // The t-value
        $t = StudentT::inverse2Tails($p, $ν);

        // Standard error of y
        $SSres = $this->SSres ?? $this->sumOfSquaresResidual();
        $sy    = sqrt($SSres / $ν);

        // Put it together.
        return ($x - $v) * $t * $sy / sqrt($∑x);
    }
    /**
     * The prediction interval of the regression
     *                        ___________
     *                       /1     x²
     * PI(x,p,q) = t * sy * / - + ------
     *                     √  q    ∑xᵢ²
     *
     * Where:
     *   t is the critical t for the p value
     *   sy is the estimated standard deviation of y
     *   q is the number of replications
     *
     * If $p = .05, then we can say we are 95% confidence that the future averages of $q trials at $x
     * will be within an interval of evaluate($x) ± getPI($x, .05, $q).
     *
     * @param number $x
     * @param number $p  0 < p < 1 The P value to use
     * @param int    $q  Number of trials
     *
     * @return number
     */
    public function getPI($x, $p, $q = 1)
    {
        $v  = $this->v;
        $x’ = Single::subtract($this->xs, $v);
        $∑x = array_sum(Single::square($x’));

        // Degrees of freedom
        $ν = $this->n - 1;
        
        // The t-values
        $t = StudentT::inverse2Tails($p, $ν);

        // Standard error of y
        $SSres = $this->SSres ?? $this->sumOfSquaresResidual();
        $sy    = sqrt($SSres / $ν);

        // Put it together.
        return $t * $sy * sqrt(1 / $q + ($x - $v) ** 2 / $∑x);
    }
}
