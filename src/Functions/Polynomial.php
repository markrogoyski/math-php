<?php

namespace Math\Functions;

class Polynomial
{
    private $degree;
    private $coefficient;

    public function __construct(array $coefficient)
    {
        $this->degree = count($coefficient) - 1;
        $this->coefficient = $coefficient;
    }
}
