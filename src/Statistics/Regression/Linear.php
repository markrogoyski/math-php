<?php
namespace Math\Statistics\Regression;

use Math\Statistics\Average;
use Math\Statistics\RandomVariable;
use Math\Probability\Distribution\Continuous\StudentT;

/**
 * Simple linear regression - least squares method
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
class Linear extends Regression
{
    use LeastSquares;

    /**
     * Average of x
     * @var number
     */
    private $xbar;

    /**
     * Average of y
     * @var number
     */
    private $ybar;

    /**
     * Sum of squared deviations of x
     * @var number
     */
    private $SSx;

    /**
     * Sum of squares residuals
     * @var number
     */
    private $SSres;

    /**
     * Calculates the regression parameters.
     *
     */
    public function calculate()
    {
        $parameters = $this->leastSquares($this->ys, $this->xs);
        $this->m = $parameters['m'];
        $this->b = $parameters['b'];
    }

    /**
     * Get regression parameters
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
    
    /**
     * The confidence interval of the regression
     *                      ______________
     *                     /1   (x - x̄)²
     * CI(x,p) = t * sy * / - + --------
     *                   √  n     SSx
     *
     * Where:
     *   t is the critical t for the p value
     *   sy is the estimated standard deviation of y
     *   n is the number of data points
     *   x̄ is the average of the x values
     *   SSx = ∑(x - x̄)²
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
        // Averages.
        $xbar = $this->xbar ?? Average::mean($this->xs);
        $ybar = $this->ybar ?? Average::mean($this->ys);

        // The number of data points.
        $n = $this->n;

        // Degrees of freedom.
        $ν = $n - 2;

        // Sum X sum of squares.
        $SSx = $this->SSx ?? RandomVariable::sumOfSquaresDeviations($this->xs);

        // The t-value
        $t = StudentT::inverse2Tails($p, $ν);

        // Standard error of y
        $SSres = $this->SSres ?? $this->sumOfSquaresResidual();
        $sy    = sqrt($SSres / $ν);
        
        // Put it together.
        return $t * $sy * sqrt(1/$n + ($x - $xbar)**2 / $SSx);
    }

    /**
     * The prediction interval of the regression
     *                        _________________
     *                       /1    1   (x - x̄)²
     * PI(x,p,q) = t * sy * / - +  - + --------
     *                     √  q    n     SSx
     *
     * Where:
     *   t is the critical t for the p value
     *   sy is the estimated standard deviation of y
     *   q is the number of replications
     *   n is the number of data points
     *   x̄ is the average of the x values
     *   SSx = ∑(x - x̄)²
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
        // Averages.
        $xbar = $this->xbar ?? Average::mean($this->xs);
        $ybar = $this->ybar ?? Average::mean($this->ys);

        // The number of data points.
        $n = $this->n;

        // Degrees of freedom.
        $ν = $n - 2;

        // Sum X sum of squares.
        $SSx = $this->SSx ?? RandomVariable::sumOfSquaresDeviations($this->xs);

        // The t-value
        $t = StudentT::inverse2Tails($p, $ν);

        // Standard error of y
        $SSres = $this->SSres ?? $this->sumOfSquaresResidual();
        $sy    = sqrt($SSres / $ν);
        
        // Put it together.
        return $t * $sy * sqrt(1 / $q + 1/$n + ($x - $xbar)**2 / $SSx);
    }
}
