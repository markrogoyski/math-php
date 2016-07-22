<?php
namespace Math\Probability\Distribution\Continuous;

use Math\Functions\Special;

class F extends Continuous
{
    public static function PDF($x, $d1, $d2)
    {
        $numerator = sqrt(($d1 * $x) ** $d1 * $d2 ** $d2/ ($d1 * $x + $d2) ** ($d1 + $d2));
        $denominator = $x * Special::beta($d1 / 2, $d2 / 2);
        return $numerator / $denominator;
    }
  
    public static function CDF($x, $d1, $d2)
    {
        $beta_x = $d1 * $x / ($d1 * $x + $d2);
        return Special::regularized_incomplete_beta($beta_x, $d1 / 2, $d2 / 2);
    }
}
