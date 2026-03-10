<?php

namespace MathPHP\Statistics\Regression;

class Multilinear extends Polynomial
{
    /**
     * @param list<array{float|non-empty-list<float>, float}> $points [ [ x₁ | [ x₁₁, x₁₂, x₁ₖ ], y₁ ], [ x₂ | [ x₂₁, x₂₂, x₂ₖ ], y₂ ], ... ]
     */
    public function __construct(array $points)
    {
        parent::__construct($points);
    }
}
