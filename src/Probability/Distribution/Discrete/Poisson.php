<?php
namespace Math\Probability\Distribution\Discrete;

class Poisson extends Discrete
{
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
    public static function PMF(int $k, float $λ): float
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
    public static function CDF(int $k, float $λ): float
    {
        return array_sum(array_map(
            function ($k) use ($λ) {
                return self::poissonPMF($k, $λ);
            },
            range(0, $k)
        ));
    }
}
