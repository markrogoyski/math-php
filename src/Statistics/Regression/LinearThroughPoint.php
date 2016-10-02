<?php
namespace MathPHP\Statistics\Regression;

use MathPHP\Statistics\Average;
use MathPHP\Functions\Map\Multi;
use MathPHP\Functions\Map\Single;
use MathPHP\Probability\Distribution\Continuous\StudentT;

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
class LinearThroughPoint extends ParametricRegression
{
    use Methods\LeastSquares, Models\LinearModel;

    /**
     * Given a set of data ($points) and a point($force), perform a least squares
     * regression of the data, such that the regression is forced to pass through
     * the specified point.
     *
     * This procedure is most frequently used with $force = [0,0], the origin.
     *
     * @param array $points
     * @param array $force Point to force regression line through (default: origin)
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

        $parameters = $this->leastSquares($y’, $x’, 1, 0)->getColumn(0);

        $this->m = $parameters[0];
        $this->b = $this->w - $this->m * $this->v;

        $this->parameters = [$this->b, $this->m];
    }
}
