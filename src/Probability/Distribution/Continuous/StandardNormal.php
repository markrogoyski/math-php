<?php
namespace Math\Probability\Distribution\Continuous;

class StandardNormal extends NormalDistribution
{
    public static function PDF($z)
    {
        return parent::PDF($z, 0, 1);
    }
    public static function CDF($z)
    {
        return parent::CDF($z, 0, 1);
    }
}
