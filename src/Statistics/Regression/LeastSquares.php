<?php
namespace Math\Statistics\Regression;

use Math\Statistics\{Average, RandomVariable};
use Math\Functions\Map\{Single, Multi};
use Math\Probability\Distribution\Continuous\{F, StudentT};
use Math\LinearAlgebra\{Matrix, ColumnVector, Vandermonde};

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
    public function leastSquares($ys, $xs)
    {
        // y = Xa
        $X  = new Vandermonde($xs, 2);
        $y  = new ColumnVector($ys);

        // a = (XᵀX)⁻¹Xᵀy
        $Xᵀ        = $X->transpose();
        $⟮XᵀX⟯⁻¹Xᵀy = $Xᵀ->multiply($X)->inverse()->multiply($Xᵀ)->multiply($y);

        // Get slope (m) and y intercept (b) from vector solution a (⟮XᵀX⟯⁻¹Xᵀy)
        $m = $⟮XᵀX⟯⁻¹Xᵀy[1][0];
        $b = $⟮XᵀX⟯⁻¹Xᵀy[0][0];

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
     * @return array [m => p, b => p]
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
     * The F statistic of the regression (F test)
     *
     *      MSm      SSᵣ/1
     * F₀ = --- = -----------
     *      MSₑ   SSₑ/(n - 2)
     *
     *  where:
     *    MSm = mean square model (regression mean square) = SSᵣ / df(SSᵣ) = SSᵣ/1
     *    MSₑ = mean square error (estimate of variance σ² of the random error)
     *        = SSₑ/(n - 2)
     *
     *    SSᵣ = sum of squares of the regression
     *    SSₑ = sum of squares of residuals
     *
     * @return number
     */
    public function FStatistic()
    {
        // The number of regression parameters.
        $p = 2;
        $n = $this->n;

        $SSᵣ = $this->sumOfSquaresRegression();
        $SSₑ = $this->sumOfSquaresResidual();

        // Mean of Squares for model (regression mean square)
        $MSm = $SSᵣ / ($p - 1);

        // Mean of Squares for Error
        $MSₑ = $SSₑ / ($n - $p);

        $F = $MSm / $MSₑ;

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
        $ν  = $n - 2;
        $d₁ = $n - $ν - 1;
        $d₂ = $ν;

        return (F::CDF($F, $d₁, $d₂));
    }
}
