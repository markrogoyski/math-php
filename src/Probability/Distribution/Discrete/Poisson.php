<?php
namespace MathPHP\Probability\Distribution\Discrete;

use MathPHP\Probability\Combinatorics;
use MathPHP\Functions\Support;

/**
 * Poisson distribution
 * A discrete probability distribution that expresses the probability of a given number of events
 * occurring in a fixed interval of time and/or space if these events occur with a known average
 * rate and independently of the time since the last event.
 *
 * https://en.wikipedia.org/wiki/Poisson_distribution
 */
class Poisson extends Discrete
{
    /**
     * Distribution parameter bounds limits
     * k ∈ [0,∞)
     * λ ∈ [0,1]
     * @var array
     */
    const LIMITS = [
        'k' => '[0,∞)',
        'λ' => '(0,∞)',
    ];

    /**
     * Probability mass function
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
    public static function pmf(int $k, float $λ): float
    {
        Support::checkLimits(self::LIMITS, ['k' => $k, 'λ' => $λ]);

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
    public static function cdf(int $k, float $λ): float
    {
        Support::checkLimits(self::LIMITS, ['k' => $k, 'λ' => $λ]);

        return array_sum(array_map(
            function ($k) use ($λ) {
                return self::pmf($k, $λ);
            },
            range(0, $k)
        ));
    }
}
