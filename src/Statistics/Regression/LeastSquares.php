<?php
namespace Math\Statistics\Regression;

use Math\Statistics\Average;
use Math\Functions\Map\Single;
use Math\Functions\Map\Multi;

trait LeastSquares
{
    /**
     * Linear least squares
     *        _ _   __
     *        x y - xy
     *   m = _________
     *        _     __
     *       (x)² - x²
     *
     *       _    _
     *   b = y - mx
     *
     * @param  array $ys y values
     * @param  array $xs x values
     *
     * @todo Use matrix operations once Matrix class is completed
     *       $X = new Matrix($xs);
     *       $Y = new Matrix($ys);
     *       $(XᵀX)⁻¹Xᵀy = $X->transpose->mult($X)->inverse()->(mult($X)->transpose())->mult($y);
     *       return $(XᵀX)⁻¹Xᵀy;
     *
     * @return array [m, b]
     */
    function leastSquares($ys, $xs)
    {
        // Averages used in m (slope) calculation
        $x   = Average::mean($xs);
        $y   = Average::mean($ys);
        $xy  = Average::mean(Multi::multiply($ys, $xs));
        $⟮x⟯² = $x**2;
        $x²  = Average::mean(Single::square($xs));

        // Calculate slope (m) and y intercept (b)
        $m = (($x * $y) - $xy) / ($⟮x⟯² - $x²);
        $b = $y - ($m * $x);

        return [
            'm' => $m,
            'b' => $b,
        ];
    }
}
