<?php
namespace MathPHP\Probability\Distribution\Discrete;

use MathPHP\Exception;
use MathPHP\Functions\Support;
use MathPHP\Probability\Combinatorics;

/**
 * Hypergeometric distribution
 *
 * https://en.wikipedia.org/wiki/Hypergeometric_distribution
 */
class Hypergeometric extends Discrete
{
    /**
     * Distribution parameter bounds limits
     * N ∈ [0,∞)
     * K ∈ [0,N]
     * n ∈ [0,N]
     * k ∈ [max(0, n + K - N),min(n, K)]
     * @var array
     */
    const LIMITS = [
        'N' => '[0,∞)',
    ];

    /**
     * Probability mass function
     *
     * The probability of k successes in n draws, without replacement, from a finite population of size N
     * that contains exactly K successes, wherein each draw is either a success or a failure.
     *
     *       (K)(N - K)
     *       (k)(n - k)
     * pmf = ----------
     *          (N)
     *          (n)
     *
     * N is the population size,
     * K is the number of success states in the population,
     * n is the number of draws,
     * k is the number of observed successes,
     * (a)
     * (b) is a binomial coefficient.
     *
     * N ∈ {0, 1, 2, ...}
     * K ∈ {0, 1, 2, ..., N}
     * n ∈ {0, 1, 2, ..., N}
     * k ∈ {max(0, n + K - N), ..., min(n, K)}
     * 
     * @param  int $N population size
     * @param  int $K number of success states in the population
     * @param  int $n number of draws
     * @param  int $k number of observed successes
     *
     * @return float
     */
    public static function pmf(int $N, int $K, int $n, int $k): float
    {
        $limits = array_merge(
            self::LIMITS,
            [
                'K' => "[0,$N]",
                'n' => "[0,$N]",
                'k' => '[' . max(0, $n + $K - $N) . ',' . min($n, $K) . ']',
            ]
        );
        Support::checkLimits($limits, ['N' => $N, 'K' => $K, 'n' => $n, 'k' => $k]);

        $KCk          = Combinatorics::combinations($K, $k);
        $⟮N − K⟯C⟮n − k⟯ = Combinatorics::combinations(($N - $K), ($n - $k));
        $NCn          = Combinatorics::combinations($N, $n);

        return ($KCk * $⟮N − K⟯C⟮n − k⟯) / $NCn;
    }
}
