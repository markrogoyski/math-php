<?php
namespace Math\Statistics\Regression;

use Math\Statistics\Average;
use Math\Statistics\RandomVariable;

/**
 * Base class for regressions.
 */
abstract class Regression
{
    /**
     * Array indexes for points
     * @var int
     */
    const X = 0;
    const Y = 1;

    protected $points;
    protected $xs;
    protected $ys;
    
    /**
     * Constructor - Prepares the data arrays for regression analysis
     *
     * @param array $points [ [x, y], [x, y], ... ]
     */
    public function __construct(array $points)
    {
        $this->points = $points;
        $this->n      = count($points);

        // Get list of x points and y points.
        $this->xs = array_map(function ($point) {
            return $point[self::X];

        }, $points);
        $this->ys = array_map(function ($point) {
            return $point[self::Y];

        }, $points);

        $this->calculate();
    }

    /**
     * Return the model as a string
     */
    public function __toString(): string
    {
        return $this->getEquation();
    }
    
    abstract public function getEquation();

    abstract public function getParameters();

    /**
     * Get points
     *
     * @return array
     */
    public function getPoints(): array
    {
        return $this->points;
    }

    /**
     * Get Xs (x values of each point)
     *
     * @return array of x values
     */
    public function getXs(): array
    {
        return $this->xs;
    }

    /**
     * Get Ys (y values of each point)
     *
     * @return array of y values
     */
    public function getYs(): array
    {
        return $this->ys;
    }

    /**
     * Get sample size (number of points)
     *
     * @return int
     */
    public function getSampleSize(): int
    {
        return $this->n;
    }

    /**
     * R - correlation coefficient (Pearson's r)
     *
     * A measure of the strength and direction of the linear relationship
     * between two variables
     * that is defined as the (sample) covariance of the variables
     * divided by the product of their (sample) standard deviations.
     *
     *      n∑⟮xy⟯ − ∑⟮x⟯∑⟮y⟯
     * --------------------------------
     * √［（n∑x² − ⟮∑x⟯²）（n∑y² − ⟮∑y⟯²）］
     *
     * @param array $points [ [x, y], [x, y], ... ]
     *
     * @return number
     */
    public function correlationCoefficient()
    {
        return sqrt($this->coefficientOfDetermination());
    }

    /**
     * R - correlation coefficient
     * Convenience wrapper for correlationCoefficient
     *
     * @param array $points [ [x, y], [x, y], ... ]
     *
     * @return number
     */
    public function r()
    {
        return self::correlationCoefficient();
    }

    /**
     * R² - coefficient of determination
     *
     * Indicates the proportion of the variance in the dependent variable
     * that is predictable from the independent variable.
     * Range of 0 - 1. Close to 1 means the regression line is a good fit
     * https://en.wikipedia.org/wiki/Coefficient_of_determination
     *
     * @param array $points [ [x, y], [x, y], ... ]
     *
     * @return number
     */
    public function coefficientOfDetermination()
    {
        return $this->sumOfSquaresRegression() / ($this->sumOfSquaresRegression() + $this->sumOfSquaresResidual());
    }

    /**
     * R² - coefficient of determination
     * Convenience wrapper for coefficientOfDetermination
     *
     * @param array $points [ [x, y], [x, y], ... ]
     *
     * @return number
     */
    public function r2()
    {
        return $this->coefficientOfDetermination();
    }
    
    /**
     * Ŷ (yhat)
     * A list of the predicted values of Y given the regression.
     *
     * @return array
     */
    public function yHat()
    {
        return array_map([$this, 'evaluate'], $this->xs);
    }
     
    /**
     * SSreg - The Sum Squares of the regression (Explained sum of squares)
     *
     * The sum of the squares of the deviations of the predicted values from
     * the mean value of a response variable, in a standard regression model. 
     * https://en.wikipedia.org/wiki/Explained_sum_of_squares
     * 
     * SSreg = ∑(ŷᵢ - ȳ)²
     * 
     * @return number
     */
    public function sumOfSquaresRegression()
       {
            $ȳ = Average::mean($this->ys);

            return array_sum(array_map(
                function($y) use ($ȳ) {
                    return ($y - $ȳ)**2;
                }, $this->yHat()
            ));
      }
      
     /**
      * SSres - The Sum Squares of the residuals (RSS - Residual sum of squares)
      *
      * The sum of the squares of residuals (deviations predicted from actual
      * empirical values of data). It is a measure of the discrepancy between
      * the data and an estimation model.
      * https://en.wikipedia.org/wiki/Residual_sum_of_squares
      * 
      * SSres = ∑(yᵢ - ŷᵢ)²
      * 
      * @return number
      */
    public function sumOfSquaresResidual()
    {
        $Ŷ = $this->yHat();
        return array_sum(array_map(
            function ($yᵢ, $ŷᵢ) {
                return ($yᵢ - $ŷᵢ)**2;
            }, $this->ys, $Ŷ
        ));
    }
    
    /**
      * SStot - The total Sum Squares
      *
      * The sum of the squares of the dependent data array
      * https://en.wikipedia.org/wiki/Total_sum_of_squares
      * 
      * SStot = ∑(yᵢ - ȳ)²
      * 
      * @return number
      */
    public function sumOfSquaresTotal()
    {
        return RandomVariable::sumOfSquaresDeviations($this->ys);
    }
}
