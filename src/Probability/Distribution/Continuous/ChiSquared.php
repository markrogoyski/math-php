<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Special;

class ChiSquared extends Continuous
{
    public static function PDF($x, $k)
    {
        if (!is_int($ν)) {
            return false;
        }
        $pdf = $x ** ($k / 2 - 1) * exp(-1 * $x / 2) / 2 ** ($k / 2) / Special::Γ($k / 2);
        return $pdf;
    }
/***********
* Calculate the cumulative t value up to a point, left tail
* 
*/
    public static function CDF($x, $k)
    {
        if (!is_int($k)) {
            return false;
        }
        return Special::lowerIncompleteGamma($k / 2, $x / 2) / Special::Γ($k / 2);
    }
}
