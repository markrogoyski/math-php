<?php
namespace MathPHP\Statistics\Regression;

use MathPHP\Exception;
use MathPHP\Functions\Map\Multi;

/**
 * Use the Hanes-Woolf method to fit an equation of the form
 *       V * x
 * y = ----------
 *       K + x
 *
 * The equation is linearized and fit using Least Squares
 */
class HanesWoolf extends ParametricRegression
{
    use Methods\LeastSquares, Models\MichaelisMenten;

    /**
     * Calculate the regression parameters by least squares on linearized data
     * x / y = x / V + K / V
     *
     * @throws Exception\BadDataException
     * @throws Exception\MatrixException
     */
    public function calculate()
    {
        // Linearize the relationship by dividing x by y
        $y’ = Multi::divide($this->xs, $this->ys);

        // Perform Least Squares Fit
        $linear_parameters = $this->leastSquares($y’, $this->xs)->getColumn(0);
        
        $V = 1 / $linear_parameters[1];
        $K = $linear_parameters[0] * $V;

        $this->parameters = [$V, $K];
    }
}
