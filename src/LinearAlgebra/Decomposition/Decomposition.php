<?php

namespace MathPHP\LinearAlgebra\Decomposition;

use MathPHP\LinearAlgebra\NumericMatrix;

abstract class Decomposition
{
    /**
     * @param NumericMatrix $M
     * @return static
     */
    abstract public static function decompose(NumericMatrix $M);
}
