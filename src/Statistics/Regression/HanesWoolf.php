<?php
namespace Math\Statistics\Regression;

use Math\Functions\Map\Multi;

/**
 * Use the Hanes-Woolf method to fit an equation of the form
 *       V * x
 * y = ----------
 *       K + x
 *
 * The equation is linearized and fit using Least Squares
 */
class HanesWoolf extends MichaelisMenten
{
    use LeastSquares;

    /**
     * Calculate the regression parameters by least squares on linearized data
     * x / y = x / V + K / V
     */
    public function calculate()
    {
        // Linearize the relationship by dividing x by y
        $y’ = Multi::divide($this->xs, $this->ys);
        // Perform Least Squares Fit
        $parameters = $this->leastSquares($y’, $this->xs);
        // Translate the linearized parameters back.
        $this->V = 1 / $parameters['m'];
        $this->K = $parameters['b'] * $this->V;
    }
}
