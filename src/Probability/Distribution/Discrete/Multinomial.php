<?php
namespace Math\Probability\Distribution\Discrete;

use Math\Probability\Combinatorics;

class Multinomial extends Discrete
{
    /**
     * Multinomial distribution (multivariate) - probability mass function
     *
     * https://en.wikipedia.org/wiki/Multinomial_distribution
     *
     *          n!
     * pmf = ------- p₁ˣ¹⋯pkˣᵏ
     *       x₁!⋯xk!
     *
     * n = number of trials (sum of the frequencies) = x₁ + x₂ + ⋯ xk
     *
     * @param  array $frequencies
     * @param  array $probabilities
     *
     * @return float
     */
    public static function PMF(array $frequencies, array $probabilities): float
    {
        // Must have a probability for each frequency
        if (count($frequencies) !== count($probabilities)) {
            throw new \Exception('Number of frequencies does not match number of probabilities.');
        }

        // Probabilities must add up to 1
        if (round(array_sum($probabilities), 1) != 1) {
            throw new \Exception('Probabilities do not add up to 1.');
        }

        $n   = array_sum($frequencies);
        $n！ = Combinatorics::factorial($n);

        $x₁！⋯xk！ = array_product(array_map(
            'Math\Probability\Combinatorics::factorial',
            $frequencies
        ));

        $p₁ˣ¹⋯pkˣᵏ = array_product(array_map(
            function ($x, $p) {
                return $p**$x;
            },
            $frequencies,
            $probabilities
        ));

        return ($n！ / $x₁！⋯xk！) * $p₁ˣ¹⋯pkˣᵏ;
    }
}
