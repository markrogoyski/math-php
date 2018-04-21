<?php
namespace MathPHP\Statistics\Regression;

use MathPHP\Functions\Map\Single;
use MathPHP\Statistics\Average;
use MathPHP\LinearAlgebra\VandermondeMatrix;
use MathPHP\Exception;

/**
 * LOESS - Locally Weighted Scatterplot Smoothing (Local regression)
 *
 * A non-parametric method for fitting a smooth curve between two variables.
 * https://en.wikipedia.org/wiki/Local_regression
 */
class LOESS extends NonParametricRegression
{
    use Methods\WeightedLeastSquares;

    /**
     * Smoothness parameter
     * @var number
     */
    protected $α;

    /**
     * Order of the polynomial fit
     * @var int
     */
    protected $λ;

    /**
     * Number of points considered in the local regression
     * @var number
     */
    protected $number_of_points;

    /**
     * @param array $points [ [x, y], [x, y], ... ]
     * @param float $α      Smoothness parameter (bandwidth)
     *                       Determines how much of the data is used to fit each local polynomial
     *                       ((λ + 1) / n, 1]
     * @param int    $λ      Order of the polynomial to fit
     *
     * @throws Exception\OutOfBoundsException if α is ≤ λ + 1 or > 1
     */
    public function __construct($points, float $α, int $λ)
    {
        $this->α = $α;
        $this->λ = $λ;
        
        parent::__construct($points);

        // α ∈ ((λ + 1) / n, 1]
        if (($α <= ($λ + 1) / $this->n) || $α > 1) {
            throw new Exception\OutOfBoundsException('Smoothness parameter α must be between ' . ($λ + 1) / $this->n . " and 1; given $α");
        }

        // Number of points considered in the local regression
        $this->number_of_points = min(ceil($this->α * $this->n), $this->n);
    }

    /**
     * Evaluate for x
     * Use the smoothness parameter α to determine the subset of data to consider for
     * local regression. Perform a weighted least squares regression and evaluate x.
     *
     * @param  float $x
     *
     * @return float
     *
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     */
    public function evaluate(float $x): float
    {
        $α = $this->α;
        $λ = $this->λ;

        // The number of points considered in the local regression
        $Δx    = Single::abs(Single::subtract($this->xs, $x));
        $αᵗʰΔx = Average::kthSmallest($Δx, $this->number_of_points - 1);
        $arg   = Single::min(Single::divide($Δx, $αᵗʰΔx * max($α, 1)), 1);
        
        // Kernel function: tricube = (1-arg³)³
        $tricube = Single::cube(Single::multiply(Single::subtract(Single::cube($arg), 1), -1));
        $weights = $tricube;

        // Local Regression Parameters
        $parameters = $this->leastSquares($this->ys, $this->xs, $weights, $λ);
        $X          = new VandermondeMatrix([$x], $λ + 1);

        return $X->multiply($parameters)[0][0];
    }
}
