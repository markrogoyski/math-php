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
    function leastSquares($ys, $xs)
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
    
    function standardErrors()
    {
        $n = $this->n;
        $SSe = $this->sumOfSquaresResidual();
        $df = $n - 2;    // Degrees of freedom
        $sigma_squared = $SSe / $df;
        $SSx = RandomVariable::sumOfSquaresDeviations($this->xs);
        $sm = sqrt($sigma_squared / $SSx);
        
        $sum_x_squared = array_sum(Single::squared($this->xs));
        $sb = $sm * sqrt($sum_x_squared / $n);
        
        return [
            'm' => $sm,
            'b' => $sb,
        ];
    }
    
    /**
     * The t values associated with each of the regression parameters
     * 
     * t = β / se
     */
    function TValues()
    {
        $se = $this->standardErrors();
        return [$this->m / $se['m'], $this->b / $se['b']];
    }
    
    /**
     * The probabilty associated with each parameter's t value
     */
    function TProbability()
    {
        // Degrees of Freedom.
        $df = $this->n - 2;
        
        $ts = array_map(
            function ($t) use ($df){
                return StudentT::CDF($t, $df);
            },
            $this->TValues()
        );
        return $ts;
    }
    
    /**
     * The F statistic of the regression
     */
    function FStatistic()
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
    function FProbability()
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
