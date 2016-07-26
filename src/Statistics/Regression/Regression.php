<?php
namespace Math\Statistics\Regression;
use Math\Statistics\Average;
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
        return $this->SumOfSquaresRegression() / ($this->SumOfSquaresRegression() + $this->SumOfSquaresResidual());
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
      public function getYHat()
     {
         return array_map([$this, 'evaluate'], $this->xs);
     }
     
     /**
      * The Sum Squares of the regression
      * 
      * SSreg = ∑(ŷᵢ - ȳ)²
      * 
      * @return number
      */
       public function SumOfSquaresRegression()
      {
          $ȳ = Average::mean($this->ys);
          return array_sum(array_map(function($y) use ($ȳ){return ($y - $ȳ) ** 2;}, $this->getYHat()));
      }
      
     /**
      * The Sum Squares of the residuals
      * 
      * SSreg = ∑(yᵢ - ŷᵢ)²
      * 
      * @return number
      */
       public function SumOfSquaresResidual()
      {
          $Ŷ = $this->getYHat();
          return array_sum(array_map(function($yᵢ, $ŷᵢ){return ($yᵢ - $ŷᵢ) ** 2;}, $this->ys, $Ŷ));
      }
}
