<?php
namespace Math\Statistics\Regression;

use Math\Functions\Map\Single;
use Math\Statistics\Average;
use Math\LinearAlgebra\VandermondeMatrix;

/**
 * LOESS - Locally Weighted Scatterplot Smoothing
 *
 * https://en.wikipedia.org/wiki/Local_regression
 */
class LOESS extends NonParametricRegression
{
    use Methods\WeightedLeastSquares;

    /**
     * Smoothness parameter
     * @var number
     */
    protected $q;

    /**
     * Order of the polynomial fit
     * @var int
     */
    protected $d;
    
    /**
     * @param array  $points [ [x, y], [x, y], ... ]
     * @param number $q      Smoothness parameter
     * @param int    $d      Order of the polynomial to fit
     */
    public function __construct($points, $q, int $d)
    {
        // If q <= (d + 1) / 2 or q > 1 throw exception
        $this->q = $q;
        $this->d = $d;
        
        parent::__construct($points);
    }
    
    /**
     * Use the smoothness parameter $q to determine the subset of data to consider for
     * local regression. Perform a weighted least squares regression and evaluate $x.
     */
    public function evaluate($x)
    {
        $q = $this->q;
        $d = $this->d;
        $n = $this->n;
        
        // The number of points considered in the local regression
        $number_of_points = min(ceil($q * $n), $n);
        $delta_x = Single::abs(Single::subtract($this->xs, $x));
        $qth_deltax = Average::kthSmallest($delta_x, $number_of_points - 1);
        $arg = Single::min(Single::divide($delta_x, $qth_deltax * max($q, 1)), 1);
        
        // tricube = (1-arg³)³
        $tricube = Single::cube(Single::multiply(Single::subtract(Single::cube($arg), 1), -1));
        $weights = $tricube;
        // Local Regression Parameters
        $parameters = $this->leastSquares($this->ys, $this->xs, $weights, $d);
        $X = new VandermondeMatrix([$x], $d + 1);
        return $X->multiply($parameters)[0][0];
    }
}
