<?php

namespace MathPHP\Statistics\Regression;

use MathPHP\Exception\BadDataException;
use MathPHP\Exception;
use MathPHP\Exception\IncorrectTypeException;
use MathPHP\Exception\MathException;
use MathPHP\Exception\MatrixException;

/**
 * @phpstan-import-type SimpleLinearResultModel from Methods\LeastSquares
 * @phpstan-import-type PolynomialResultModel from Methods\LeastSquares
 */
class Polynomial extends ParametricRegression
{
    /** @use Methods\LeastSquares<PolynomialResultModel> */
    use Methods\LeastSquares;
    use Models\PolynomialModel;

    /**
     * @inheritdoc
     */
    protected $multipleExplanatoryVariablesSupported = true;

    /** @var bool */
    protected $calculate_projection = true;

    /**
     * @param list<array{float|non-empty-list<float>, float}> $points [ [ x₁ | [ x₁₁, x₁₂, x₁ₖ ], y₁ ], [ x₂ | [ x₂₁, x₂₂, x₂ₖ ], y₂ ], ... ]
     * @param int $order
     * @param int $fit_constant
     * @param bool $calculate_projection true whether to calculate the projection matrix.
     */
    public function __construct(array $points, int $order = 1, int $fit_constant = 1, bool $calculate_projection = true)
    {
        $this->order = $order;
        $this->fit_constant = $fit_constant;
        $this->calculate_projection = $calculate_projection;
        parent::__construct($points);
    }

    /**
     * @param list<float> $array
     * @return PolynomialResultModel
     */
    protected function createResultModel(array $array): array
    {
        return $this->createPolynomialResultModel($array);
    }

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
        $this->parameters = $this->leastSquares($this->ys, $this->xss, $this->order, $this->fit_constant, $this->calculate_projection)->getColumn(0);
    }

    /**
     * Evaluate the simple regression equation at x.
     * Uses the instance model's evaluateModel method.
     *
     * @param float $x
     *
     * @return float
     *
     * @throws BadDataException
     * @throws IncorrectTypeException
     * @throws MathException
     * @throws MatrixException
     */
    public function evaluate(float $x): float
    {
        return $this->evaluateVector([$x]);
    }

    /**
     * Evaluate the regression equation at x vector.
     * Uses the instance model's evaluateModel method.
     *
     * @param non-empty-list<float> $vector
     *
     * @return float
     *
     * @throws BadDataException
     * @throws IncorrectTypeException
     * @throws MathException
     * @throws MatrixException
     */
    public function evaluateVector(array $vector): float
    {
        if (empty($this->parameters)) {
            throw new Exception\BadDataException('Regression parameters are not calculated');
        }
        return $this->evaluateModel($vector, $this->parameters);
    }
}
