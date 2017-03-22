<?php
namespace MathPHP;

class Arithmetic
{
    /**
     * Cube root ³√x
     * This function is necessary because pow($x, 1/3) returns NAN for negative values.
     * PHP does not have the cbrt built-in function.
     *
     * @param  number $x
     *
     * @return number
     */
    public static function cubeRoot($x)
    {
        if ($x >= 0) {
            return pow($x, 1/3);
        }

        return -pow(abs($x), 1/3);
    }
}
