<?php
namespace MathPHP\Statistics\Regression;

use MathPHP\Statistics\Average;

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
class TheilSen extends ParametricRegression
{
    use Models\LinearModel;

    /**
     * Calculate the regression parameters using the Theil-Sen method
     *
     * Procedure:
     * Calculate the slopes of all pairs of points and select the median value
     * Calculate the intercept using the slope, and the medians of the X and Y values.
     *   b = Ymedian - (m * Xmedian)
     */
    public function calculate()
    {
        // The slopes array will be a list of slopes between all pairs of points
        $slopes = [];
        $n      = count($this->points);
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $pointi   = $this->points[$i];
                $pointj   = $this->points[$j];
                $slopes[] = ($pointj[1] - $pointi[1]) / ($pointj[0] - $pointi[0]);
            }
        }

        $this->m = Average::median($slopes);
        $this->b = Average::median($this->ys) - ($this->m * Average::median($this->xs));

        $this->parameters = [$this->b, $this->m];
    }
}
