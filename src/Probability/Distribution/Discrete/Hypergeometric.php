<?php
namespace MathPHP\Probability\Distribution\Discrete;

use MathPHP\Exception;
use MathPHP\Functions\Special;
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
        self::checkLimits($N, $K, $n, $k);

        $KCk          = Combinatorics::combinations($K, $k);
        $⟮N − K⟯C⟮n − k⟯ = Combinatorics::combinations(($N - $K), ($n - $k));
        $NCn          = Combinatorics::combinations($N, $n);

        return ($KCk * $⟮N − K⟯C⟮n − k⟯) / $NCn;
    }

    /**
     * Cumulative distribution function
     *
     *           (  n  )(  N - n  )      _                           _
     *           (k + 1)(K - k - 1)     | 1, k + 1 - K, k + 1 - n      |
     * cdf = 1 - ------------------ ₃F₂ |                          ; 1 |
     *                  (N)             | k + 2, N + k + 2 - K - n     |
     *                  (K)             |_                            _|
     *
     * N is the population size,
     * K is the number of success states in the population,
     * n is the number of draws,
     * k is the number of observed successes,
     * (a)
     * (b) is a binomial coefficient.
     * ₃F₂ is the generalized hypergeometric function
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
    public static function cdf(int $N, int $K, int $n, int $k): float
    {
        self::checkLimits($N, $K, $n, $k);

        $nC⟮k ＋ 1⟯         = Combinatorics::combinations($n, $k + 1);
        $⟮N − n⟯C⟮K − k − 1⟯ = Combinatorics::combinations(($N - $n), ($K - $k - 1));
        $NCK              = Combinatorics::combinations($N, $K);

        $₃F₂ = Special::generalizedHypergeometric(3, 2, 1, $k + 1 - $K, $k + 1 - $n, $k + 2, $N + $k + 2 - $K - $n, 1);

        return (($nC⟮k ＋ 1⟯ * $⟮N − n⟯C⟮K − k − 1⟯) / $NCK) * $₃F₂;
    }

    /**
     * Check limits
     *
     * @param  int $N population size
     * @param  int $K number of success states in the population
     * @param  int $n number of draws
     * @param  int $k number of observed successes
     */
    private static function checkLimits(int $N, int $K, int $n, int $k)
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
    }
}
