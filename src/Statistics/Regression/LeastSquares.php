<?php
namespace Math\Statistics\Regression;

use Math\Statistics\RandomVariable;
use Math\Statistics\Average;
use Math\Functions\Map\Single;
use Math\Functions\Map\Multi;
use Math\Probability\Distribution\Continuous\F;
use Math\Probability\Distribution\Continuous\StudentT;

trait LeastSquares
{
    /**
     * Linear least squares
     *        _ _   __
     *        x y - xy
     *   m = _________
     *        _     __
     *       (x)² - x²
     *
     *       _    _
     *   b = y - mx
     *
     * @param  array $ys y values
     * @param  array $xs x values
     *
     * @todo Use matrix operations once Matrix class is completed
     *       $X = new Matrix($xs);
     *       $Y = new Matrix($ys);
     *       $(XᵀX)⁻¹Xᵀy = $X->transpose->mult($X)->inverse()->(mult($X)->transpose())->mult($y);
     *       return $(XᵀX)⁻¹Xᵀy;
     *
     * @return array [m, b]
     */
    public function leastSquares($ys, $xs)
    {
        // Averages used in m (slope) calculation
        $x   = Average::mean($xs);
        $y   = Average::mean($ys);
        $xy  = Average::mean(Multi::multiply($ys, $xs));
        $⟮x⟯² = $x**2;
        $x²  = Average::mean(Single::square($xs));

        // Calculate slope (m) and y intercept (b)
        $m = (($x * $y) - $xy) / ($⟮x⟯² - $x²);
        $b = $y - ($m * $x);

        return [
            'm' => $m,
            'b' => $b,
        ];
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
        $n = $this->n;

        // se(m): standard error of m
        $SSres     = $this->sumOfSquaresResidual();
        $ν         = $n - 2;
        $∑eᵢ²／ν   = $SSres / $ν;
        $∑⟮xᵢ − μ⟯² = RandomVariable::sumOfSquaresDeviations($this->xs);
        $se⟮m⟯      = sqrt($∑eᵢ²／ν / $∑⟮xᵢ − μ⟯²);

        // se(b): standard error of b
        $∑xᵢ² = array_sum(Single::square($this->xs));
        $se⟮b⟯ = $se⟮m⟯ * sqrt($∑xᵢ² / $n);
        
        return [
            'm' => $se⟮m⟯,
            'b' => $se⟮b⟯,
        ];
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
        return [
            'm' => $this->m / $se['m'],
            'b' => $this->b / $se['b'],
        ];
    }
    
    /**
     * The probabilty associated with each parameter's t value
     *
     * t probability = Student's T CDF(t,ν)
     *
     *  where:
     *    t = t value
     *    ν = n - 2  degrees of freedom
     *
     * @return array [m => tProbability, b => tProbability]
     */
    public function tProbability()
    {
        $ν  = $this->n - 2;
        $t  = $this->tValues();

        return [
            'm' => StudentT::CDF($t['m'], $ν),
            'b' => StudentT::CDF($t['b'], $ν),
        ];
    }
    
    /**
     * The F statistic of the regression
     */
    public function FStatistic()
    {
        // The number of regression parameters.
        $p = 2;
        $n = $this->n;
        
        $SSr = $this->sumOfSquaresRegression();
        $SSe = $this->sumOfSquaresResidual();
        
        // Mean of Squares for model.
        $msm = $SSr / ($p - 1);
        
        // Mean of Squares for Error
        $mse = $SSe / ($n - $p);
        
        $F = $msm / $mse;
        
        return $F;
    }
    
    /**
     * The probabilty associated with the regression F Statistic
     */
    public function FProbability()
    {
        $F = $this->FStatistic();
        $n = $this->n;
        // Degrees of Freedom.
        $df = $this->n - 2;
        
        $v1 = $n - $df - 1;
        $v2 = $df;
        return (F::CDF($F, $v1, $v2));
    }
    
}
