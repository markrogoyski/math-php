<?php
namespace MathPHP\Statistics;

use MathPHP\Probability\Distribution\Table;
use MathPHP\Functions\Map;
use MathPHP\Exception;

/**
 * Functions dealing with random variables.
 *
 * - Central moment
 * - Skewness
 * - Kurtosis
 * - Standard Error of the Mean (SEM)
 * - Confidence interval
 *
 * In probability and statistics, a random variable is a variable whose
 * value is subject to variations due to chance.
 * A random variable can take on a set of possible different values
 * (similarly to other mathematical variables), each with an associated
 * probability, in contrast to other mathematical variables.
 *
 * The mathematical function describing the possible values of a random
 * variable and their associated probabilities is known as a probability
 * distribution. Random variables can be discrete, that is, taking any of a
 * specified finite or countable list of values, endowed with a probability
 * mass function, characteristic of a probability distribution; or
 * continuous, taking any numerical value in an interval or collection of
 * intervals, via a probability density function that is characteristic of
 * a probability distribution; or a mixture of both types.
 *
 * https://en.wikipedia.org/wiki/Random_variable
 */
class RandomVariable
{
    /**
     * n-th Central moment
     * A moment of a probability distribution of a random variable about the random variable's mean.
     * It is the expected value of a specified integer power of the deviation of the random variable from the mean.
     * https://en.wikipedia.org/wiki/Central_moment
     *
     *      ∑⟮xᵢ - μ⟯ⁿ
     * μn = ----------
     *          N
     *
     * @param array $X list of numbers (random variable X)
     * @param int   $n n-th central moment to calculate
     *
     * @return number n-th central moment
     */
    public static function centralMoment(array $X, int $n)
    {
        if (empty($X)) {
            return null;
        }

        $μ         = Average::mean($X);
        $∑⟮xᵢ − μ⟯ⁿ = array_sum(array_map(
            function ($xᵢ) use ($μ, $n) {
                return pow(($xᵢ - $μ), $n);
            },
            $X
        ));
        $N = count($X);

        return $∑⟮xᵢ − μ⟯ⁿ / $N;
    }

    /**
     * Popluation skewness
     * A measure of the asymmetry of the probability distribution of a real-valued random variable about its mean.
     * https://en.wikipedia.org/wiki/Skewness
     * http://brownmath.com/stat/shape.htm
     *
     * This method tends to match Excel's SKEW.P function.
     *
     *         μ₃
     * γ₁ = -------
     *       μ₂³′²
     *
     * μ₂ is the second central moment
     * μ₃ is the third central moment
     *
     * @param array $X list of numbers (random variable X)
     *
     * @return number
     */
    public static function populationSkewness(array $X)
    {
        if (empty($X)) {
            return null;
        }

        $μ₃ = self::centralMoment($X, 3);
        $μ₂ = self::centralMoment($X, 2);
    
        $μ₂³′² = pow($μ₂, 3/2);

        return ($μ₃ /  $μ₂³′²);
    }

    /**
     * Sample skewness
     * A measure of the asymmetry of the probability distribution of a real-valued random variable about its mean.
     * https://en.wikipedia.org/wiki/Skewness
     * http://brownmath.com/stat/shape.htm
     *
     * This method tends to match Excel's SKEW function.
     *
     *         μ₃     √(n(n - 1))
     * γ₁ = ------- × -----------
     *       μ₂³′²       n - 2
     *
     * μ₂ is the second central moment
     * μ₃ is the third central moment
     * n is the sample size
     *
     * @param array $X list of numbers (random variable X)
     *
     * @return number
     */
    public static function sampleSkewness(array $X)
    {
        $n = count($X);
        if ($n < 3) {
            return null;
        }

        $μ₃    = self::centralMoment($X, 3);
        $μ₂    = self::centralMoment($X, 2);

        $μ₂³′² = pow($μ₂, 3/2);

        $√⟮n⟮n − 1⟯⟯ = sqrt($n * ($n - 1));

        return ($μ₃ / $μ₂³′²) * ( $√⟮n⟮n − 1⟯⟯ / ($n - 2) );
    }

    /**
     * Skewness (alternative method)
     * This method tends to match most of the online skewness calculators and examples.
     * https://en.wikipedia.org/wiki/Skewness
     *
     *         1     ∑⟮xᵢ - μ⟯³
     * γ₁ =  ----- × ---------
     *       N - 1       σ³
     *
     * μ is the mean
     * σ³ is the standard deviation cubed, or, the variance raised to the 3/2 power.
     * N is the sample size
     *
     * @param array $X list of numbers (random variable X)
     *
     * @return number
     *
     * @throws Exception\OutOfBoundsException
     */
    public static function skewness(array $X)
    {
        $N  = count($X);
        if ($N < 2) {
            return null;
        }

        $μ         = Average::mean($X);
        $∑⟮xᵢ − μ⟯³ = array_sum(array_map(
            function ($xᵢ) use ($μ) {
                return pow(($xᵢ - $μ), 3);
            },
            $X
        ));
        $σ³ = pow(Descriptive::standardDeviation($X, Descriptive::SAMPLE), 3);

        $⟮σ³ × ⟮N − 1⟯⟯ = ($σ³ * ($N - 1));
        if ($⟮σ³ × ⟮N − 1⟯⟯ == 0) {
            return \NAN;
        }

        return $∑⟮xᵢ − μ⟯³ / $⟮σ³ × ⟮N − 1⟯⟯;
    }

    /**
     * Standard Error of Skewness (SES)
     *
     *         _____________________
     *        /      6n(n - 1)
     * SES = / --------------------
     *      √  (n - 2)(n + 1)(n + 3)
     *
     * @param int $n Sample size
     *
     * @return float
     *
     * @throws Exception\BadDataException if n < 3
     */
    public static function ses(int $n): float
    {
        if ($n < 3) {
            throw new Exception\BadDataException("SES requires a dataset of n > 2. N of $n given.");
        }

        $６n⟮n − 1⟯           = 6 * $n * ($n - 1);
        $⟮n − 2⟯⟮n ＋ 1⟯⟮n ＋ 2⟯ = ($n - 2) * ($n + 1) * ($n + 3);

        return sqrt($６n⟮n − 1⟯ / $⟮n − 2⟯⟮n ＋ 1⟯⟮n ＋ 2⟯);
    }

    /**
     * Excess Kurtosis
     * A measure of the "tailedness" of the probability distribution of a real-valued random variable.
     * https://en.wikipedia.org/wiki/Kurtosis
     *
     *       μ₄
     * γ₂ = ---- − 3
     *       μ₂²
     *
     * μ₂ is the second central moment
     * μ₄ is the fourth central moment
     *
     * @param array $X list of numbers (random variable X)
     *
     * @return number
     */
    public static function kurtosis(array $X)
    {
        if (empty($X)) {
            return null;
        }

        $μ₄  = self::centralMoment($X, 4);
        $μ₂² = pow(self::centralMoment($X, 2), 2);

        if ($μ₂² == 0) {
            return \NAN;
        }

        return ( $μ₄ / $μ₂² ) - 3;
    }

    /**
     * Is the kurtosis negative? (Platykurtic)
     * Indicates a flat distribution.
     *
     * @param array $X list of numbers (random variable X)
     *
     * @return bool true if platykurtic
     */
    public static function isPlatykurtic(array $X): bool
    {
        return self::kurtosis($X) < 0;
    }

    /**
     * Is the kurtosis postive? (Leptokurtic)
     * Indicates a peaked distribution.
     *
     * @param array $X list of numbers (random variable X)
     *
     * @return bool true if leptokurtic
     */
    public static function isLeptokurtic(array $X): bool
    {
        return self::kurtosis($X) > 0;
    }

    /**
     * Is the kurtosis zero? (Mesokurtic)
     * Indicates a normal distribution.
     *
     * @param array $X list of numbers (random variable X)
     *
     * @return bool true if mesokurtic
     */
    public static function isMesokurtic(array $X): bool
    {
        return self::kurtosis($X) == 0;
    }

    /**
     * Standard Error of Kurtosis (SEK)
     *
     *                ______________
     *               /    (n² - 1)
     * SEK = 2(SES) / --------------
     *             √  (n - 3)(n + 5)
     *
     * @param int $n Sample size
     *
     * @return float
     *
     * @throws Exception\BadDataException if n < 4
     */
    public static function sek(int $n): float
    {
        if ($n < 4) {
            throw new Exception\BadDataException("SEK requires a dataset of n > 3. N of $n given.");
        }

        $２⟮SES⟯        = 2 * self::ses($n);
        $⟮n² − 1⟯       = $n**2 - 1;
        $⟮n − 3⟯⟮n ＋ 5⟯ = ($n - 3) * ($n + 5);

        return $２⟮SES⟯ * sqrt($⟮n² − 1⟯ / $⟮n − 3⟯⟮n ＋ 5⟯);
    }

    /**
     * Standard error of the mean (SEM)
     * The standard deviation of the sample-mean's estimate of a population mean.
     * https://en.wikipedia.org/wiki/Standard_error
     *
     *       s
     * SEₓ = --
     *       √n
     *
     * s = sample standard deviation
     * n = size (number of observations) of the sample
     *
     * @param array $X list of numbers (random variable X)
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     */
    public static function standardErrorOfTheMean(array $X)
    {
        if (empty($X)) {
            return null;
        }

        $s  = Descriptive::standardDeviation($X, Descriptive::SAMPLE);
        $√n = sqrt(count($X));
        return $s / $√n;
    }

    /**
     * SEM - Convenience method for standard error of the mean
     *
     * @param array $X list of numbers (random variable X)
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     */
    public static function sem(array $X): float
    {
        return self::standardErrorOfTheMean($X);
    }

    /**
     * Confidence interval
     * Finds CI given a sample mean, sample size, and standard deviation.
     * Uses Z score.
     * https://en.wikipedia.org/wiki/Confidence_interval
     *          σ
     * ci = z* --
     *         √n
     *
     * interval = (μ - ci, μ + ci)
     *
     * Available confidence levels: See Probability\StandardNormalTable::Z_SCORES_FOR_CONFIDENCE_INTERVALS
     *
     * @param number $μ sample mean
     * @param int $n sample size
     * @param number $σ standard deviation
     * @param string $cl confidence level (Ex: 95, 99, 99.5, 99.9, etc.)
     *
     * @return array [ ci, lower_bound, upper_bound ]
     *
     * @throws Exception\BadDataException
     */
    public static function confidenceInterval($μ, int $n, $σ, string $cl): array
    {
        if ($n === 0) {
            return ['ci' => null, 'lower_bound' => null, 'upper_bound' => null];
        }

        $z = Table\StandardNormal::getZScoreForConfidenceInterval($cl);

        $ci = $z * ($σ / sqrt($n));

        $lower_bound = $μ - $ci;
        $upper_bound = $μ + $ci;

        return [
            'ci' => $ci,
            'lower_bound' => $lower_bound,
            'upper_bound' => $upper_bound,
        ];
    }

    /**
     * Sum of squares
     *
     * ∑⟮xᵢ⟯²
     *
     * @param array $numbers
     *
     * @return number
     */
    public static function sumOfSquares(array $numbers)
    {
        if (empty($numbers)) {
            return null;
        }

         $∑⟮xᵢ⟯² = array_sum(Map\Single::square($numbers));

         return $∑⟮xᵢ⟯²;
    }

    /**
     * Sum of squares deviations
     *
     * ∑⟮xᵢ - μ⟯²
     *
     * @param  array  $numbers
     *
     * @return number
     */
    public static function sumOfSquaresDeviations(array $numbers)
    {
        if (empty($numbers)) {
            return null;
        }

        $μ         = Average::mean($numbers);
        $∑⟮xᵢ − μ⟯² = array_sum(array_map(
            function ($xᵢ) use ($μ) {
                return pow(($xᵢ - $μ), 2);
            },
            $numbers
        ));

        return $∑⟮xᵢ − μ⟯²;
    }
}
