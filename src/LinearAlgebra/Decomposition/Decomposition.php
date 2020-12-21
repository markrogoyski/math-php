<?php

namespace MathPHP\LinearAlgebra\Decomposition;

use MathPHP\LinearAlgebra\Matrix;

abstract class Decomposition
{
    abstract public static function decompose(Matrix $M);
}
