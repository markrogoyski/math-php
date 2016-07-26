<?php
namespace Math\Statistics\Regression;

use Math\Statistics\Average;

/**
 * Theil–Sen estimator
 * Also known as Sen's slope estimator, slope selection, the single median method,
 * the Kendall robust line-fit method, and the Kendall–Theil robust line.
 *
 * A method for robustly fitting a line to a set of points (simple linear regression) that
 * chooses the median slope among all lines through pairs of two-dimensional sample points.
 *
 * https://en.wikipedia.org/wiki/Theil%E2%80%93Sen_estimator
 */
class TheilSen extends Regression
{
    /**
     * Calculate the regression parameters using the Theil-Sen method
     *
     * Procedure:
     * Calculate the slopes of all pairs of points and select the median value
     * Calculate the intercept using the slope, and the medians of the X and Y values.
     */
    public function calculate()
    {
        // The slopes array will be a list of slopes between all pairs of points
        $slopes = [];
        $n      = count($this->points);

        for ($i = 0; $i < $n; $i++)
        {
            for ($j = $i + 1; $j < $n; $j++)
            {
                $pointi   = $this->points[$i];
                $pointj   = $this->points[$j];
                $slopes[] = ($pointj[self::Y] - $pointi[self::Y]) / ($pointj[self::X] - $pointi[self::X]);
            }
        }

        $this->m = Average::median($slopes);
        $this->b = Average::median($this->ys) - $this->m * Average::median($this->xs);
    }
    
    /**
     * Get regression parameters
     * m = slope
     * b = y intercept
     *
     * @return array [ m => number, b => number ]
     */
    public function getParameters(): array
    {
        return [
            'm' => $this->m,
            'b' => $this->b,
        ];
    }
    /**
     * Get regression equation (y = mx + b)
     *
     * @return string
     */
    public function getEquation(): string
    {
        return sprintf('y = %fx + %f', $this->m, $this->b);
    }
    /**
     * Evaluate the line equation from linear regression parameters for a value of x
     * y = mx + b
     *
     * @param number $x
     *
     * @return number y evaluated
     */
    public function evaluate($x)
    {
        $m  = $this->m;
        $b  = $this->b;
        $mx = $m * $x;
        return $mx + $b;
    }
}
