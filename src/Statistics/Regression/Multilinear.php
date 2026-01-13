<?php

namespace MathPHP\Statistics\Regression;

use MathPHP\Exception;
use MathPHP\Exception\BadDataException;

class Multilinear extends ParametricRegression
{
    use Methods\LeastSquares;
    use Models\MultilinearModel;

    /**
     * Calculates the regression parameters.
     *
     * @throws Exception\BadDataException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     * @throws Exception\MathException
     */
    public function calculate(): void
    {
        $this->parameters = $this->leastSquares($this->ys, $this->xss)->getColumn(0);
    }

    /**
     * Evaluate the regression equation at x.
     *
     * @param float $x
     *
     * @return float
     *
     * @throws BadDataException
     */
    public function evaluate(float $x): float
    {
        throw new Exception\BadDataException('Multilinear regression does not support evaluate(x)');
    }

    /**
     * Evaluate the regression equation at x vector.
     * Uses the instance model's evaluateModel method.
     *
     * @param non-empty-list<float> $vector
     *
     * @return float
     */
    public function evaluateVector(array $vector): float
    {
        return $this->evaluateModel($vector, $this->parameters);
    }
}
