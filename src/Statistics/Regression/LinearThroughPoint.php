<?php
namespace Math\Statistics\Regression;

use Math\Statistics\Average;
use Math\Functions\Map\{Multi, Single};
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
        $this->v = $force[self::X];
        $this->w = $force[self::Y];
        parent::__construct($points);
    }
    
    /**
     * Calculates the regression parameters.
     */
    public function calculate()
    {
        $v = $this->v;
        $w = $this->w;
        
        $x’ = Single::add($this->xs, -1 * $v);
        $y’ = Single::add($this->ys, -1 * $w);
        
        $numerator   = array_sum(Multi::multiply($x’, $y’));
        $denominator = array_sum(Single::square($x’));
        
        // Calculate slope (m) and y intercept (b)
        $this->m = $numerator / $denominator;
        if ($v == 0 && $w == 0) {
            $this->b = 0;
        } else {
            $this->b = $w - $this->m * $v;
        }
    }

    /**
     * Get regression parameters
     * m = slope
     * b = y intercept
     *
     * @return array [ m => number, b => number]
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
        return $m * $x + $b;
    }

    /**
     * SSreg - The Sum Squares of the regression (Explained sum of squares)
     *
     * The sum of the squares of the deviations of the predicted values from
     * the mean value of a response variable, in a standard regression model.
     * https://en.wikipedia.org/wiki/Explained_sum_of_squares
     *
     * SSreg = ∑ŷᵢ²
     *
     * @return number
     */
    public function sumOfSquaresRegression()
    {
        return array_sum(Single::square($this->getYHat()));
    }
    
    /**
      * SStot - The total Sum Squares
      *
      * The sum of the squares of the dependent data array
      * https://en.wikipedia.org/wiki/Total_sum_of_squares
      *
      * SStot = ∑yᵢ²
      *
      * @return number
      */
    public function sumOfSquaresTotal()
    {
        return array_sum(Single::square($this->ys));
    }
    
    /**
     * The confidence interval of the regression
     *                          ______
     *                         /  1
     * CI(x,p) = x * t * sy * / ------
     *                       √   ∑xᵢ²
     *
     * Where:
     *   t is the critical t for the p value
     *   sy is the estimated standard deviation of y
     *
     * If $p = .05, then we can say we are 95% confidence the actual regression line
     * will be within an interval of evaluate($x) ± getCI($x, .05).
     *
     * @param number $x
     * @param number $p:  0 < p < 1 The P value to use
     *
     * @return number
     */
    public function getCI($x, $p)
    {
        
        $∑x = array_sum(Single::square($this->xs));
       
        // The t-value
        $t = StudentT::inverse2Tails($p, $ν);
        // Standard error of y
        $SSres = $this->SSres ?? $this->sumOfSquaresResidual();
        $sy    = sqrt($SSres / $ν);
        
        // Put it together.
        return $x * $t * $sy / sqrt($∑x);
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
        $∑x = array_sum(Single::square($this->xs));
        // Degrees of freedom.
        $ν = $n - 1;
        
        // The t-value
        $t = StudentT::inverse2Tails($p, $ν);
        // Standard error of y
        $SSres = $this->SSres ?? $this->sumOfSquaresResidual();
        $sy    = sqrt($SSres / $ν);
        
        // Put it together.
        return $t * $sy * sqrt(1 / $q + $x ** 2 / $∑x);
    }
}
