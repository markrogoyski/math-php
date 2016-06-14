<?php
namespace Math\Probability\Distribution;

use Math\Probability\Combinatorics;
use Math\Statistics\RandomVariable;

class Discrete
{
    /**
     * Binomial distribution - probability mass function
     * https://en.wikipedia.org/wiki/Binomial_distribution
     *
     * P(X = r) = nCr pʳ (1 - p)ⁿ⁻ʳ
     *
     * @param  int   $n number of events
     * @param  int   $r number of successful events
     * @param  float $P probability of success
     *
     * @return float
     */
    public static function binomialPMF(int $n, int $r, float $p): float
    {
        if ($p < 0 || $p > 1) {
            throw new \Exception("Probability $p must be between 0 and 1.");
        }

        $nCr       = Combinatorics::combinations($n, $r);
        $pʳ        = pow($p, $r);
        $⟮1 − p⟯ⁿ⁻ʳ = pow(1 - $p, $n - $r);

        return $nCr * $pʳ * $⟮1 − p⟯ⁿ⁻ʳ;
    }

    /**
     * Binomial distribution - cumulative distribution function
     * Computes and sums the binomial distribution at each of the values in r.
     *
     * @param  int   $n number of events
     * @param  int   $r number of successful events
     * @param  float $P probability of success
     *
     * @return float
     */
    public static function binomialCDF(int $n, int $r, float $p): float
    {
        $cumulative_probability = 0;
        for ($i = $r; $i >= 0; $i--) {
            $cumulative_probability += self::binomialPMF($n, $i, $p);
        }
        return $cumulative_probability;
    }

    /**
     * Negative binomial distribution (Pascal distribution)
     * https://en.wikipedia.org/wiki/Negative_binomial_distribution
     *
     * b(x; r, P) = ₓ₋₁Cᵣ₋₁ Pʳ * (1 - P)ˣ⁻ʳ
     *
     * @param  int   $x number of trials required to produce r successes
     * @param  int   $r number of successful events
     * @param  float $P probability of success on an individual trial
     *
     * @return float
     */
    public static function negativeBinomial(int $x, int $r, float $P): float
    {
        if ($P < 0 || $P > 1) {
            throw new \Exception("Probability $P must be between 0 and 1.");
        }

        $ₓ₋₁Cᵣ₋₁   = Combinatorics::combinations($x - 1, $r - 1);
        $Pʳ        = pow($P, $r);
        $⟮1 − P⟯ˣ⁻ʳ = pow(1 - $P, $x - $r);

        return $ₓ₋₁Cᵣ₋₁ * $Pʳ * $⟮1 − P⟯ˣ⁻ʳ;
    }

    /**
     * Pascal distribution (convenience method for negative binomial distribution)
     * https://en.wikipedia.org/wiki/Negative_binomial_distribution
     *
     * b(x; r, P) = ₓ₋₁Cᵣ₋₁ pʳ * (1 - P)ˣ⁻ʳ
     *
     * @param  int   $x number of trials required to produce r successes
     * @param  int   $r number of successful events
     * @param  float $P probability of success on an individual trial
     *
     * @return float
     */
    public static function pascal(int $x, int $r, float $P): float
    {
        return self::negativeBinomial($x, $r, $P);
    }

    /**
     * Poisson distribution - probability mass function
     * A discrete probability distribution that expresses the probability of a given number of events
     * occurring in a fixed interval of time and/or space if these events occur with a known average
     * rate and independently of the time since the last event.
     * https://en.wikipedia.org/wiki/Poisson_distribution
     *
     *                              λᵏℯ^⁻λ
     * P(k events in an interval) = ------
     *                                k!
     *
     * @param  int   $k events in the interval
     * @param  float $λ average number of successful events per interval
     *
     * @return float The Poisson probability of observing k successful events in an interval
     */
    public static function poissonPMF(int $k, float $λ): float
    {
        if ($k < 0 || $λ < 0) {
            throw new \Exception('k and λ must be greater than 0.');
        }

        $λᵏℯ＾−λ = pow($λ, $k) * exp(-$λ);
        $k！     = Combinatorics::factorial($k);

        return $λᵏℯ＾−λ / $k！;
    }

    /**
     * Cumulative Poisson Probability (lower culmulative distribution) - CDF
     * The probability that the Poisson random variable is greater than some specified lower limit,
     * and less than some specified upper limit.
     *
     *           k  λˣℯ^⁻λ
     * P(k,λ) =  ∑  ------
     *          ₓ₌₀  xᵢ!
     *
     * @param  int   $k events in the interval
     * @param  float $λ average number of successful events per interval
     *
     * @return float The cumulative Poisson probability
     */
    public static function poissonCDF(int $k, float $λ): float
    {
        return array_sum(array_map(
            function ($k) use ($λ) {
                return self::poissonPMF($k, $λ);
            },
            range(0, $k)
        ));
    }

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
    public static function multinomialPMF(array $frequencies, array $probabilities): float
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
