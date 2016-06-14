<?php
namespace Math\Probability;

use Math\Probability\Combinatorics;
use Math\Statistics\RandomVariable;

class Distribution
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
     * Continuous uniform distribution
     * Computes the probability of a specific interval within the continuous uniform distribution.
     * https://en.wikipedia.org/wiki/Uniform_distribution_(continuous)
     *
     * x₂ − x₁
     * -------
     *  b − a
     *
     * @param number $a lower boundary of the distribution
     * @param number $b upper boundary of the distribution
     * @param number $x₁ lower boundary of the probability interval
     * @param number $x₂ upper boundary of the probability interval
     *
     * @return number probability of specific interval
     */
    public static function continuousUniform(float $a, float $b, float $x₁, float $x₂)
    {
        if (( $x₁ < $a || $x₁ > $b ) || ( $x₂ < $a || $x₂ > $b )) {
            throw new \Exception('x values are outside of the distribution.');
        }
        return ( $x₂ - $x₁ ) / ( $b - $a );
    }

    /**
     * Exponential distribution - probability density function
     * https://en.wikipedia.org/wiki/Exponential_distribution
     *
     * f(x;λ) = λℯ^⁻λx  x ≥ 0
     *        = 0       x < 0
     *
     * @param float $λ often called the rate parameter
     * @param float $x the random variable
     *
     * @return float
     */
    public static function exponentialPDF(float $λ, float $x): float
    {
        if ($x < 0) {
            return 0;
        }

        return $λ * exp(-$λ * $x);
    }

    /**
     * Cumulative exponential distribution - cumulative distribution function
     * https://en.wikipedia.org/wiki/Exponential_distribution
     *
     * f(x;λ) = 1 − ℯ^⁻λx  x ≥ 0
     *        = 0          x < 0
     *
     * @param float $λ often called the rate parameter
     * @param float $x the random variable
     *
     * @return float
     */
    public static function exponentialCDF(float $λ, float $x): float
    {
        if ($x < 0) {
            return 0;
        }

        return 1 - exp(-$λ * $x);
    }

    /**
     * Cumulative exponential distribution between two numbers
     * Probability that an exponentially distributed random variable X
     * is between two numbers x₁ and x₂.
     *
     * P(x₁ ≤ X ≤ x₂) = P(X ≤ x₂) − P(X ≤ x₁)
     *                = (1 − ℯ^⁻λx₂) − (1 − ℯ^⁻λx₁)
     *
     * @param float $λ often called the rate parameter
     * @param float $x₁ random variable 1
     * @param float $x₂ random variable 2
     *
     * @return float
     */
    public static function exponentialCDFBetween(float $λ, float $x₁, float $x₂): float
    {
        return self::exponentialCDF($λ, $x₂) - self::exponentialCDF($λ, $x₁);
    }

    /**
     * Normal distribution - probability density function
     *
     * https://en.wikipedia.org/wiki/Normal_distribution
     *
     *              1
     * f(x|μ,σ) = ----- ℯ^−⟮x − μ⟯²∕2σ²
     *            σ√⟮2π⟯
     *
     * @param number $x random variable
     * @param number $μ mean
     * @param number $σ standard deviation
     *
     * @return float f(x|μ,σ)
     */
    public static function normalPDF($x, $μ, $σ): float
    {
        $σ√⟮2π⟯ = $σ * sqrt(2 * \M_PI);

        $⟮x − μ⟯²∕2σ² = pow(($x - $μ), 2) / (2 * $σ**2);

        $ℯ＾−⟮x − μ⟯²∕2σ² = exp(-$⟮x − μ⟯²∕2σ²);

        return ( 1 / $σ√⟮2π⟯ ) * $ℯ＾−⟮x − μ⟯²∕2σ²;
    }

    /**
     * Normal distribution - cumulative distribution function
     * Probability of being below X.
     * Area under the normal distribution from -∞ to X.
     *             _                  _
     *          1 |         / x - μ \  |
     * cdf(x) = - | 1 + erf|  ----- |  |
     *          2 |_        \  σ√2  / _|
     *
     * @param number $x upper bound
     * @param number $μ mean
     * @param number $σ standard deviation
     *
     * @return float cdf(x) below
     */
    public static function normalCDF($x, $μ, $σ): float
    {
        return 1/2 * ( 1 + RandomVariable::erf(($x - $μ) / ($σ * sqrt(2))) );
    }

    /**
     * Normal distribution above - cumulative distribution function
     * Probability of being above X.
     * Area under the normal distribution from X to ∞
     *
     * @param number $x lower bound
     * @param number $μ mean
     * @param number $σ standard deviation
     *
     * @return float cdf(x) above
     */
    public static function normalCDFAbove($x, $μ, $σ): float
    {
        return 1 - self::normalCDF($x, $μ, $σ);
    }

    /**
     * Normal distribution between two points - cumulative distribution function
     * Probability of being bewteen x₁ and x₂.
     * Area under the normal distribution from x₁ to x₂.
     *
     * @param number x₁ lower bound
     * @param number x₂ upper bound
     * @param number $μ mean
     * @param number $σ standard deviation
     *
     * @return float cdf(x) between
     */
    public static function normalCDFBetween($x₁, $x₂, $μ, $σ): float
    {
        return self::normalCDF($x₂, $μ, $σ) - self::normalCDF($x₁, $μ, $σ);
    }

    /**
     * Normal distribution outside two points - cumulative distribution function
     * Probability of being bewteen below x₁ and above x₂.
     * Area under the normal distribution from -∞ to x₁ and x₂ to ∞.
     *
     * @param number x₁ lower bound
     * @param number x₂ upper bound
     * @param number $μ mean
     * @param number $σ standard deviation
     *
     * @return float cdf(x) between
     */
    public static function normalCDFOutside($x₁, $x₂, $μ, $σ): float
    {
        return self::normalCDF($x₁, $μ, $σ) + self::normalCDFAbove($x₂, $μ, $σ);
    }

    /**
     * Log normal distribution - probability density function
     *
     * https://en.wikipedia.org/wiki/Log-normal_distribution
     *
     *                 (ln x - μ)²
     *         1     - ----------
     * pdf = ----- ℯ       2σ²
     *       xσ√2π
     *
     * @param  number $x
     * @param  number $μ
     * @param  number $σ
     * @return number
     */
    public static function logNormalPDF($x, $μ, $σ)
    {
        $π          = \M_PI;

        $xσ√2π      = $x * $σ * sqrt(2 * $π);
        $⟮ln x − μ⟯² = pow(log($x) - $μ, 2);
        $σ²         = $σ**2;

        return (1 / $xσ√2π) * exp(-($⟮ln x − μ⟯² / (2 *$σ²)));
    }

    /**
     * Log normal distribution - cumulative distribution function
     *
     * https://en.wikipedia.org/wiki/Log-normal_distribution
     *
     *       1   1      / ln x - μ \
     * cdf = - + - erf |  --------  |
     *       2   2      \   √2σ     /
     *
     * @param  number $x
     * @param  number $μ
     * @param  number $σ
     * @return number
     */
    public static function logNormalCDF($x, $μ, $σ)
    {
        $π          = \M_PI;

        $⟮ln x − μ⟯ = log($x) - $μ;
        $√2σ       = sqrt(2) * $σ;

        return 1/2 + 1/2 * RandomVariable::erf($⟮ln x − μ⟯ / $√2σ);
    }

    /**
     * Pareto distribution - probability density function
     *
     * https://en.wikipedia.org/wiki/Pareto_distribution
     *
     *          abᵃ
     * P(x) =  ----  for x ≥ b
     *         xᵃ⁺¹
     *
     * P(x) = 0      for x < b
     *
     * @param  number $a shape parameter
     * @param  number $b scale parameter
     * @param  number $x
     */
    public static function paretoPDF($a, $b, $x)
    {
        if ($x < $b) {
            return 0;
        }

        $abᵃ  = $a * $b**$a;
        $xᵃ⁺¹ = pow($x, $a + 1);

        return $abᵃ / $xᵃ⁺¹;
    }

    /**
     * Pareto distribution - cumulative distribution function
     *
     * https://en.wikipedia.org/wiki/Pareto_distribution
     *
     *             / b \ᵃ
     * D(x) = 1 - |  -  | for x ≥ b
     *             \ x /
     *
     * D(x) = 0           for x < b
     *
     * @param  number $a shape parameter
     * @param  number $b scale parameter
     * @param  number $x
     */
    public static function paretoCDF($a, $b, $x)
    {
        if ($x < $b) {
            return 0;
        }

        return 1 - pow($b / $x, $a);
    }
}
