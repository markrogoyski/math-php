<?php
namespace Math\Statistics\Regression;

/**
 * Base class for regressions.
 */
abstract class Regression
{
    /**
     * Array indexes for points
     * @var int
     */
    const X = 0;
    const Y = 1;

    protected $points;
    protected $xs;
    protected $ys;

    abstract public function getEquation();

    abstract public function getParameters();

    /**
     * Get points
     *
     * @return array
     */
    public function getPoints(): array
    {
        return $this->points;
    }

    /**
     * Get Xs (x values of each point)
     *
     * @return array of x values
     */
    public function getXs(): array
    {
        return $this->xs;
    }

    /**
     * Get Ys (y values of each point)
     *
     * @return array of y values
     */
    public function getYs(): array
    {
        return $this->ys;
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
}
