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

    /**
     * R - correlation coefficient (Pearson's r)
     *
     * A measure of the strength and direction of the linear relationship
     * between two variables
     * that is defined as the (sample) covariance of the variables
     * divided by the product of their (sample) standard deviations.
     *
     *      n∑⟮xy⟯ − ∑⟮x⟯∑⟮y⟯
     * --------------------------------
     * √［（n∑x² − ⟮∑x⟯²）（n∑y² − ⟮∑y⟯²）］
     *
     * @param array $points [ [x, y], [x, y], ... ]
     *
     * @return number
     */
    public static function correlationCoefficient(array $points)
    {
        // Get list of x points and y points.
        $xs = array_map(function ($point) {
            return $point[self::X];
        }, $points);
        $ys = array_map(function ($point) {
            return $point[self::Y];
        }, $points);
        $n  = count($points);

        // Numerator calculations
        $n∑⟮xy⟯ = $n * array_sum(array_map(
            function ($x, $y) {
                return $x * $y;
            },
            $xs,
            $ys
        ));
        $∑⟮x⟯∑⟮y⟯ = array_sum($xs) * array_sum($ys);

        // Denominator calculations
        $n∑x² = $n * array_sum(array_map(
            function ($x) {
                return $x**2;
            },
            $xs
        ));
        $⟮∑x⟯² = pow(array_sum($xs), 2);

        $n∑y² = $n * array_sum(array_map(
            function ($y) {
                return $y**2;
            },
            $ys
        ));
        $⟮∑y⟯² = pow(array_sum($ys), 2);

        return ( $n∑⟮xy⟯ - $∑⟮x⟯∑⟮y⟯ ) / sqrt(($n∑x² - $⟮∑x⟯²) * ($n∑y² - $⟮∑y⟯²));
    }

    /**
     * R - correlation coefficient
     * Convenience wrapper for correlationCoefficient
     *
     * @param array $points [ [x, y], [x, y], ... ]
     *
     * @return number
     */
    public static function r(array $points)
    {
        return self::correlationCoefficient($points);
    }

    /**
     * R² - coefficient of determination
     *
     * Indicates the proportion of the variance in the dependent variable
     * that is predictable from the independent variable.
     * Range of 0 - 1. Close to 1 means the regression line is a good fit
     * https://en.wikipedia.org/wiki/Coefficient_of_determination
     *
     * @param array $points [ [x, y], [x, y], ... ]
     *
     * @return number
     */
    public static function coefficientOfDetermination(array $points)
    {
        return pow(self::correlationCoefficient($points), 2);
    }

    /**
     * R² - coefficient of determination
     * Convenience wrapper for coefficientOfDetermination
     *
     * @param array $points [ [x, y], [x, y], ... ]
     *
     * @return number
     */
    public static function r2(array $points)
    {
        return pow(self::correlationCoefficient($points), 2);
    }
}
