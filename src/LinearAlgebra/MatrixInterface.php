<?php

namespace MathPHP\LinearAlgebra;

use MathPHP\Number\ObjectArithmetic;

interface MatrixInterface extends ObjectArithmetic
{
    /**
     * What type of data does the matrix contain
     *
     * @return string the type of data in the Matrix
     */
    public function getObjectType(): string;
}
