<?php

namespace MathPHP\Statistics\Regression;

class Multilinear extends Polynomial
{
    /**
     * @param list<array{float|non-empty-list<float>, float}> $points [ [ x₁ | [ x₁₁, x₁₂, x₁ₖ ], y₁ ], [ x₂ | [ x₂₁, x₂₂, x₂ₖ ], y₂ ], ... ]
     * @param bool $calculate_projection true whether to calculate the projection matrix.
     */
    public function __construct(array $points, bool $calculate_projection = true)
    {
        parent::__construct($points, 1, 1, $calculate_projection);
    }
}
