<?php
namespace Math\Statistics\Regression;

use Math\Statistics\Average;

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
    /**
     * Calculates the regression parameters.
     *
     */
    public function calculate()
    {
        // Averages used in m (slope) calculation
        $x   = Average::mean($this->xs);
        $y   = Average::mean($this->ys);
        $xy  = Average::mean(array_map(
            function ($point) {
                return $point[self::X] * $point[self::Y];
            }, $this->points
        ));
        $⟮x⟯² = pow($x, 2);
        $x²  = Average::mean(array_map(
            function ($i) {
                return $i**2;
            }, $this->xs
        ));

        // Calculate slope (m) and y intercept (b)
        $this->m = (( $x * $y ) - $xy) / ($⟮x⟯² - $x²);
        $this->b = $y - ($this->m * $x);
    }

    /**
     * Get regression parameters
     * m = slope
     * b = y intercept
     *
     * @return array [ m => number, b => number ]
     */
    public function getParameters()
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
     * Consider saving the parameters of this equation in $this to speed up case where this is
     * called many times.
     * 
     * @param number $x
     * @param number $p:  0 < p < 1 The P value to use
     *
     * @return number
     */
    public function getCI($x, $p){

        // Averages.
        $xbar = Average::mean($this->xs);
        $ybar =  Average::mean($this->ys);

        // The number of data points.
        $n = count ($this->points);

        // Degrees of freedom.
        $ν = $n - 2;

        // Sum X sum of squares.
        $SSx = RandomVariable::sumOfSquaresDeviations($this->xs);

        // The Y sum of squares.
        $SSy = RandomVariable::sumOfSquaresDeviations($this->ys);

        // The t-value
        $t = StudentsT::inverse2Tails($p, $ν);

        // Standard error of y
        $SSres = $this->sumOfSquaresResidual();
        $sy = sqrt($SSres / $ν);
        
        // Put it together.
        return $t * $sy * sqrt(1/$n + ($x - $xbar) ** 2 / $SSx);
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
     * Consider saving the parameters of this equation in $this to speed up case where this method is
     * called many times.
     * 
     * @param number $x
     * @param number $p:  0 < p < 1 The P value to use
     * @param int $q
     *
     * @return number
     */
    public function getPI($x, $p, $q = 1){

        // Averages.
        $xbar = Average::mean($this->xs);
        $ybar = Average::mean($this->ys);

        // The number of data points.
        $n = count ($this->points);

        // Degrees of freedom.
        $ν = $n - 2;

        // Sum X sum of squares.
        $SSx = RandomVariable::sumOfSquaresDeviations($this->xs);

        // The Y sum of squares.
        $SSy = RandomVariable::sumOfSquaresDeviations($this->ys);

        // The t-value
        $t = StudentsT::inverse2Tails($p, $ν);

        // Standard error of y
        $SSres = $this->sumOfSquaresResidual();
        $sy = sqrt($SSres / $ν);
        
        // Put it together.
        return $t * $sy * sqrt(1 / $q + 1/$n + ($x - $xbar) ** 2 / $SSx);
    }

}
