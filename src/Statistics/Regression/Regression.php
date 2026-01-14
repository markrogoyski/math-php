<?php

namespace MathPHP\Statistics\Regression;

/**
 * Base class for regressions.
 */
abstract class Regression
{
    /**
     * Array of x and y points: [ [ x₁ | [ x₁₁, x₁₂, x₁ₖ ], y₁ ], [ x₂ | [ x₂₁, x₂₂, x₂ₖ ], y₂ ], ... ]
     * @var list<array{float|non-empty-list<float>, float}>
     */
    protected $points;

    /**
     * X values of the original points
     * @var list<float>
     */
    protected $xs;

    /**
     * Y values of the original points
     * @var list<float>
     */
    protected $ys;

    /**
     * X row values of the original points
     * @var list<non-empty-list<float>>
     */
    protected $xss;

    /**
     * Number of points
     * @var int
     */
    protected $n;

    /**
     * Number of columns in xss
     * @var int
     */
    protected $k;

    /**
     * Constructor - Prepares the data arrays for regression analysis
     *
     * @param list<array{float|non-empty-list<float>, float}> $points [ [ x₁ | [ x₁₁, x₁₂, x₁ₖ ], y₁ ], [ x₂ | [ x₂₁, x₂₂, x₂ₖ ], y₂ ], ... ]
     */
    public function __construct(array $points)
    {
        $this->points = $points;
        $this->n      = \count($points);
        $this->k      = empty($points) ? 0 : (\is_array($points[0]) ? \count($points[0]) : 1);

        // Get list of x points and y points.
        // This will be fine for linear or polynomial regression, where there is only one x.
        $this->xs = \array_map(function ($point) {
            return \is_array($point[0]) ? $point[0][0] : $point[0];
        }, $points);
        $this->ys = \array_map(function ($point) {
            return $point[1];
        }, $points);
        // For multilinear, the format is a list of x for each point.
        $this->xss = \array_map(function (array $point) {
            return \is_array($point[0]) ? $point[0] : [$point[0]];
        }, $points);
    }

    /**
     * Evaluate the regression equation at x
     *
     * @param float $x
     *
     * @return float
     */
    abstract public function evaluate(float $x): float;

    /**
     * Evaluate the regression equation at x vector
     *
     * @param non-empty-list<float> $vector
     * @return float
     */
    public function evaluateVector(array $vector): float
    {
        return $this->evaluate($vector[0]);
    }

    /**
     * Get points
     *
     * @return list<array{float|non-empty-list<float>, float}>
     */
    public function getPoints(): array
    {
        return $this->points;
    }

    /**
     * Get Xs (x values of each point)
     *
     * @return list<float> of x values
     */
    public function getXs(): array
    {
        return $this->xs;
    }

    /**
     * Get Ys (y values of each point)
     *
     * @return list<float> of y values
     */
    public function getYs(): array
    {
        return $this->ys;
    }

    /**
     * Get Xss (x vector of each point)
     *
     * @return list<non-empty-list<float>> of x values
     */
    public function getXss(): array
    {
        return $this->xss;
    }

    /**
     * Get sample size (number of points)
     *
     * @return int
     */
    public function getSampleSize(): int
    {
        return $this->n;
    }

    /**
     * Ŷ (yhat)
     * A list of the predicted values of Y given the regression.
     *
     * @return list<float>
     */
    public function yHat(): array
    {
        return \array_map([$this, 'evaluateVector'], $this->xss);
    }
}
