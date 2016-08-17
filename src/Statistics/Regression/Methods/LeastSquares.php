<?php
namespace Math\Statistics\Regression\Methods;

use Math\Statistics\Average;
use Math\Statistics\RandomVariable;
use Math\Functions\Map\Single;
use Math\Functions\Map\Multi;
use Math\Probability\Distribution\Continuous\F;
use Math\Probability\Distribution\Continuous\StudentT;
use Math\LinearAlgebra\Matrix;
use Math\LinearAlgebra\ColumnVector;
use Math\LinearAlgebra\VandermondeMatrix;

trait LeastSquares
{
    /**
     * Linear least squares fitting using Matrix algebra (Polynomial).
     *
     * Generalizing from a straight line (first degree polynomial) to a kᵗʰ degree polynomial:
     *  y = a₀ + a₁x + ⋯ + akxᵏ
     *
     * Leads to equations in matrix form:
     *  [n    Σxᵢ   ⋯  Σxᵢᵏ  ] [a₀]   [Σyᵢ   ]
     *  [Σxᵢ  Σxᵢ²  ⋯  Σxᵢᵏ⁺¹] [a₁]   [Σxᵢyᵢ ]
     *  [ ⋮     ⋮    ⋱  ⋮    ] [ ⋮ ] = [ ⋮    ]
     *  [Σxᵢᵏ Σxᵢᵏ⁺¹ ⋯ Σxᵢ²ᵏ ] [ak]   [Σxᵢᵏyᵢ]
     *
     * This is a Vandermonde matrix:
     *  [1 x₁ ⋯ x₁ᵏ] [a₀]   [y₁]
     *  [1 x₂ ⋯ x₂ᵏ] [a₁]   [y₂]
     *  [⋮  ⋮  ⋱ ⋮ ] [ ⋮ ] = [ ⋮]
     *  [1 xn ⋯ xnᵏ] [ak]   [yn]
     *
     * Can write as equation:
     *  y = Xa
     *
     * Solve by premultiplying by transpose Xᵀ:
     *  Xᵀy = XᵀXa
     *
     * Invert to yield vector solution:
     *  a = (XᵀX)⁻¹Xᵀy
     *
     * (http://mathworld.wolfram.com/LeastSquaresFittingPolynomial.html)
     *
     * For reference, the traditional way to do least squares:
     *        _ _   __
     *        x y - xy        _    _
     *   m = _________    b = y - mx
     *        _     __
     *       (x)² - x²
     *
     * @param  array $ys y values
     * @param  array $xs x values
     *
     * @return array [m, b]
     */
    public function leastSquares($ys, $xs, $order = 1, $fit_constant = 1)
    {
        $this->fit_constant = $fit_constant;
        $this->p = $order;
        $this->ν = $this->n - $this->p - $this->fit_constant;
        // y = Xa
        $X  = new VandermondeMatrix($xs, $this->p + 1);
        if ($this->fit_constant == 0) {
            $X = $X->columnExclude(0);
        }
        $y  = new ColumnVector($ys);
        // a = (XᵀX)⁻¹Xᵀy
        $Xᵀ        = $X->transpose();
        $⟮XᵀX⟯⁻¹Xᵀy = $Xᵀ->multiply($X)->inverse()->multiply($Xᵀ)->multiply($y);
        return $⟮XᵀX⟯⁻¹Xᵀy;
    }

    /**
     * Sum Of Squares
     */
     
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
            function ($ŷᵢ) use ($ȳ) {
                return ($ŷᵢ - $ȳ)**2;
            },
            $this->yHat()
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
      * SSres = ∑(yᵢ - f(xᵢ))²
      *       = ∑(yᵢ - ŷᵢ)²
      *
      *  where yᵢ is an observed value
      *        ŷᵢ is a value predicted by the regression model
      *
      * @return number
      */
    public function sumOfSquaresResidual()
    {
        $Ŷ = $this->yHat();
        return array_sum(array_map(
            function ($yᵢ, $ŷᵢ) {
                return ($yᵢ - $ŷᵢ)**2;
            },
            $this->ys,
            $Ŷ
        ));
    }

    /**
      * SStot - The total Sum Squares
      *
      * the sum, over all observations, of the squared differences of
      * each observation from the overall mean.
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

    /**
     * Mean Square Errors
     *
     * The mean square errors are the sum of squares divided by their
     * individual degrees of freedom.
     *
     * Source    |     df
     * ----------|--------------
     * SSTO      |    n - 1
     * SSE       |    n - p - 1
     * SSR       |    p
     */
     
    public function meanSquareRegression()
    {
        $p = $this->p;
        $SSᵣ = $this->sumOfSquaresRegression();
        $MSR = $SSᵣ / $p;
        return $MSR;
    }
    
    public function meanSquareResidual()
    {
        $ν = $this->ν;
        $SSₑ = $this->sumOfSquaresResidual();
        // Mean of Squares for Error
        $MSE = $SSₑ / $ν;
        return $MSE;
    }
    
    public function meanSquareTotal()
    {
        // Need to make sure the 1 is not $this->fit_parameters;
        $MSTO = $this->sumOfSquaresTotal() / ($this->n - 1);
        return $MSTO;
    }
    
    /**
     * Error Standard Deviation
     *
     * Also called the standard error of the residuals
     *
     */
    public function errorSD()
    {
        return sqrt($this->meanSquareResidual());
    }
     
    /**
     * The degrees of freedom of the regression
     */
    public function degreesOfFreedom()
    {
        return $this->ν;
    }
     
    /**
     * Standard error of the regression parameters (coefficients)
     *
     *              _________
     *             /  ∑eᵢ²
     *            /  -----
     * se(m) =   /     ν
     *          /  ---------
     *         √   ∑⟮xᵢ - μ⟯²
     *
     *  where
     *    eᵢ = residual (difference between observed value and value predicted by the model)
     *    ν  = n - 2  degrees of freedom
     *
     *           ______
     *          / ∑xᵢ²
     * se(b) = /  ----
     *        √    n
     *
     * @return array [m => se(m), b => se(b)]
     */
    public function standardErrors()
    {
        $X = new VandermondeMatrix($this->xs, 2);
        $⟮XᵀX⟯⁻¹ = $X->transpose()->multiply($X)->inverse();
        $σ² = ($this->errorSD()) ** 2;
        $standard_error_matrix = $⟮XᵀX⟯⁻¹->scalarMultiply($σ²);
        $standard_error_array = Single::sqrt($standard_error_matrix->getDiagonalElements());
        
        return [
            'm' => $standard_error_array[1],
            'b' => $standard_error_array[0],
        ];
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
     * The t values associated with each of the regression parameters (coefficients)
     *
     *       β
     * t = -----
     *     se(β)
     *
     *  where:
     *    β     = regression parameter (coefficient)
     *    se(β) = standard error of the regression parameter (coefficient)
     *
     * @return  array [m => t, b => t]
     */
    public function tValues()
    {
        $se = $this->standardErrors();
        $m = $this->parameters[1];
        $b = $this->parameters[0];
        return [
            'm' => $m / $se['m'],
            'b' => $b / $se['b'],
        ];
    }
    /**
     * The probabilty associated with each parameter's t value
     *
     * t probability = Student's T CDF(t,ν)
     *
     *  where:
     *    t = t value
     *    ν = n - p - alpha  degrees of freedom
     *
     *  alpha = 1 if the regression includes a constant term
     *
     * @return array [m => p, b => p]
     */
    public function tProbability()
    {
        $ν  = $this->ν;
        $t  = $this->tValues();
        return [
            'm' => StudentT::CDF($t['m'], $ν),
            'b' => StudentT::CDF($t['b'], $ν),
        ];
    }
    /**
     * The F statistic of the regression (F test)
     *
     *      MSm      SSᵣ/p
     * F₀ = --- = -----------
     *      MSₑ   SSₑ/(n - p - α)
     *
     *  where:
     *    MSm = mean square model (regression mean square) = SSᵣ / df(SSᵣ) = SSᵣ/p
     *    MSₑ = mean square error (estimate of variance σ² of the random error)
     *        = SSₑ/(n - p - α)
     *    p   = the order of the fitted polynomial
     *    α   = 1 if the model includes a constant term, 0 otherwise. (p+α = total number of model parameters)
     *    SSᵣ = sum of squares of the regression
     *    SSₑ = sum of squares of residuals
     *
     * @return number
     */
    public function FStatistic()
    {
        $F = $this->meanSquareRegression() / $this->meanSquareResidual();
        return $F;
    }
    /**
     * The probabilty associated with the regression F Statistic
     *
     * F probability = F distribution CDF(F,d₁,d₂)
     *
     *  where:
     *    F  = F statistic
     *    d₁ = degrees of freedom 1
     *    d₂ = degrees of freedom 2
     *
     *    ν  = degrees of freedom
     *
     * @return number
     */
    public function FProbability()
    {
        $F = $this->FStatistic();
        $n = $this->n;
        // Degrees of freedom
        // Need to make sure the 1 in $d₁ should not be $this->fit_parameters;
        $ν  = $this->ν;
        $d₁ = $n - $ν - 1;
        $d₂ = $ν;
        return (F::CDF($F, $d₁, $d₂));
    }
}
