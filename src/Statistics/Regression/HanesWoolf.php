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
 *
 * @phpstan-import-type SimpleLinearResultModel from Methods\LeastSquares
 * @phpstan-import-type PolynomialResultModel from Methods\LeastSquares
 */
class HanesWoolf extends ParametricRegression
{
    /** @use Methods\LeastSquares<SimpleLinearResultModel> */
    use Methods\LeastSquares;
    use Models\MichaelisMenten;

    /**
     * @param list<float> $array
     * @return SimpleLinearResultModel
     */
    protected function createResultModel(array $array): array
    {
        return $this->createSimpleLinearResultModel($array);
    }

    /**
     * Calculate the regression parameters by least squares on linearized data
     * x / y = x / V + K / V
     *
     * @throws Exception\BadDataException
     * @throws Exception\MatrixException
     * @throws Exception\MathException
     */
    public function calculate(): void
    {
        // Linearize the relationship by dividing x by y
        $y’ = Multi::divide($this->xs, $this->ys);

        // Perform Least Squares Fit
        $linear_parameters = $this->leastSquares($y’, $this->xs)->getColumn(0);

        $V = 1 / $linear_parameters[1];
        $K = $linear_parameters[0] * $V;

        $this->parameters = [$V, $K];
    }

    /**
     * Evaluate the regression equation at x
     * Uses the instance model's evaluateModel method.
     *
     * @param  float $x
     *
     * @return float
     */
    public function evaluate(float $x): float
    {
        return $this->evaluateModel($x, $this->parameters);
    }
}
