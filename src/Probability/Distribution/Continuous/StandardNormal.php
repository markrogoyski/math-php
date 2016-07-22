<?php
namespace Math\Probability\Distribution\Continuous;

class StandardNormal
{
    public static function PDF($z)
    {
        return Normal::PDF($z, 0, 1);
    }

    public static function CDF($z)
    {
        return Normal::CDF($z, 0, 1);
    }
}
