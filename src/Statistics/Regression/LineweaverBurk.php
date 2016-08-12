<?php
namespace Math\Statistics\Regression;

use Math\Functions\Map\Single;

/**
 * Use the Lineweaver-Burk method to fit an equation of the form
 *       V * x
 * y = ----------
 *       K + x
 *
 * The equation is linearized and fit using Least Squares
 */
class LineweaverBurk extends Regression
{
    use LeastSquares;

    /**
     * Calculate the regression parameters by least squares on linearized data
     * y⁻¹ = K * V⁻¹ * x⁻¹ + V⁻¹
     */
    public function calculate()
    {
        // Linearize the relationship by taking the inverse of both x and y
        $x’ = Single::pow($this->xs, -1);
        $y’ = Single::pow($this->ys, -1);

        // Perform Least Squares Fit
        $parameters = $this->leastSquares($y’, $x’);

        // Translate the linearized parameters back.
        $this->V = 1 / $parameters['b'];
        $this->K = $parameters['m'] * $this->V;
    }

}
