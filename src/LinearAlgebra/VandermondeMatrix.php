<?php
namespace MathPHP\LinearAlgebra;

/**
 * Vandermonde matrix
 * A matrix with the terms of a geometric progression in each row.
 *
 * [α₁⁰ α₁¹ α₁² ⋯ α₁ⁿ⁻¹]
 * [α₂⁰ α₂¹ α₂² ⋯ α₂ⁿ⁻¹]
 * [α₃⁰ α₃¹ α₃² ⋯ α₃ⁿ⁻¹]
 * [ ⋮   ⋮   ⋮   ⋱ ⋮    ]
 * [αm⁰ αm¹ αm² ⋯ αmⁿ⁻¹]
 *
 * Ex:
 *  M = [1, 2, 3], n = 4
 *
 *      [1⁰ 1¹ 1² 1³]   [1 1 1 1 ]
 *  V = [2⁰ 2¹ 2² 2³] = [1 2 4 8 ]
 *      [3⁰ 3¹ 3² 3³]   [1 3 9 27]
 *
 * https://en.wikipedia.org/wiki/Vandermonde_matrix
 */
class VandermondeMatrix extends Matrix
{
    /**
     * Create the Vandermonde Matrix from a simple array.
     *
     * @param array $M (α₁, α₂, α₃ ⋯ αm)
     * @param int   $n
     */
    public function __construct(array $M, int $n)
    {
        $this->n = $n;
        $this->m = count($M);
        
        $A = [];
        foreach ($M as $row => $α) {
            for ($i = 0; $i < $n; $i++) {
                $A[$row][$i] = $α**$i;
            }
        }
        $this->A = $A;
    }
}
