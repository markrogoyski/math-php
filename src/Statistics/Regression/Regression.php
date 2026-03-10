<?php

namespace MathPHP\Statistics\Regression;

use InvalidArgumentException;

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
     * Number of columns in xss.
     * @var int
     */
    protected $k;

    /**
     * Whether the regression supports multiple explanatory variables.
     * @var bool
     */
    protected $multipleExplanatoryVariablesSupported = false;

    /**
     * Constructor - Prepares the data arrays for regression analysis
     *
     * @param list<array{float|non-empty-list<float>, float}> $points [ [ x₁ | [ x₁₁, x₁₂, x₁ₖ ], y₁ ], [ x₂ | [ x₂₁, x₂₂, x₂ₖ ], y₂ ], ... ]
     */
    public function __construct(array $points)
    {
        $this->points = $points;
        $this->n      = \count($points);
        $this->k      = empty($points) ? 0 : (\is_array($points[0][0]) ? \count($points[0][0]) : 1);

        // For the multi regression, the format is a list of x for each point.
        // Also do some validation.
        $this->xss = \array_map(function (array $point) {
            $row = \is_array($point[0]) ? $point[0] : [$point[0]];
            $count = \count($row);
            if ($this->multipleExplanatoryVariablesSupported) {
                if ($count === 0) {
                    throw new InvalidArgumentException('For multi regression, the x values of each row must be non-empty.');
                }
                if ($count !== $this->k) {
                    throw new InvalidArgumentException('For multi regression, the x values of each row must be of the same length.');
                }
            } else {
                if ($count !== 1) {
                    throw new InvalidArgumentException('For simple regression, the x values of each row must be a single value.');
                }
            }
            return $row;
        }, $points);

        // Get a list of x points and y points for the simple regression.
        $this->xs = \array_map(function (array $point) {
            return \is_array($point[0]) ? $point[0][0] : $point[0];
        }, $points);
        $this->ys = \array_map(function (array $point) {
            return $point[1];
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
