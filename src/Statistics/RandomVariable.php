<?php
namespace Math\Statistics;

use Math\Statistics\Average;
use Math\Statistics\Descriptive;
use Math\Probability\StandardNormalTable;

/**
 * Functions dealing with random variables.
 *
 * - Covariance
 * - Correlation coefficient
 * - Central moment
 * - Skewness
 * - Kurtosis
 * - Standard Error of the Mean (SEM)
 * - Confidence interval
 * - Z score
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
     * Population Covariance
     * A measure of how much two random variables change together.
     * Average product of their deviations from their respective means.
     * The population covariance is defined in terms of the population means μx, μy
     * https://en.wikipedia.org/wiki/Covariance
     *
     * cov(X, Y) = σxy = E[⟮X - μx⟯⟮Y - μy⟯]
     *
     *                   ∑⟮xᵢ - μₓ⟯⟮yᵢ - μy⟯
     * cov(X, Y) = σxy = -----------------
     *                           N
     *
     * @param array $X values for random variable X
     * @param array $Y values for random variable Y
     *
     * @return number
     */
    public static function populationCovariance(array $X, array $Y)
    {
        if (count($X) !== count($Y)) {
            throw new \Exception("X and Y must have the same number of elements.");
        }
        $μₓ = Average::mean($X);
        $μy = Average::mean($Y);
    
        $∑⟮xᵢ − μₓ⟯⟮yᵢ − μy⟯ = array_sum(array_map(
            function ($xᵢ, $yᵢ) use ($μₓ, $μy) {
                return ( $xᵢ - $μₓ ) * ( $yᵢ - $μy );
            },
            $X,
            $Y
        ));
        $N = count($X);

        return $∑⟮xᵢ − μₓ⟯⟮yᵢ − μy⟯ / $N;
    }

    /**
     * Sample covariance
     * A measure of how much two random variables change together.
     * Average product of their deviations from their respective means.
     * The population covariance is defined in terms of the sample means x, y
     * https://en.wikipedia.org/wiki/Covariance
     *
     * cov(X, Y) = Sxy = E[⟮X - x⟯⟮Y - y⟯]
     *
     *                   ∑⟮xᵢ - x⟯⟮yᵢ - y⟯
     * cov(X, Y) = Sxy = ---------------
     *                         n - 1
     *
     * @param array $X values for random variable X
     * @param array $Y values for random variabel Y
     *
     * @return number
     */
    public static function sampleCovariance(array $X, array $Y)
    {
        if (count($X) !== count($Y)) {
            throw new \Exception("X and Y must have the same number of elements.");
        }
        $x = Average::mean($X);
        $y = Average::mean($Y);
    
        $∑⟮xᵢ − x⟯⟮yᵢ − y⟯ = array_sum(array_map(
            function ($xᵢ, $yᵢ) use ($x, $y) {
                return ( $xᵢ - $x ) * ( $yᵢ - $y );
            },
            $X,
            $Y
        ));
        $n = count($X);

        return $∑⟮xᵢ − x⟯⟮yᵢ − y⟯ / ($n - 1);
    }

    /**
     * Population correlation coefficient
     * Pearson product-moment correlation coefficient (PPMCC or PCC or Pearson's r)
     *
     * A normalized measure of the linear correlation between two variables X and Y,
     * giving a value between +1 and −1 inclusive, where 1 is total positive correlation,
     * 0 is no correlation, and −1 is total negative correlation.
     * It is widely used in the sciences as a measure of the degree of linear dependence
     * between two variables.
     * https://en.wikipedia.org/wiki/Pearson_product-moment_correlation_coefficient
     *
     * The correlation coefficient of two variables in a data sample is their covariance
     * divided by the product of their individual standard deviations.
     *
     *        cov(X,Y)
     * ρxy = ----------
     *         σx σy
     *
     *  conv(X,Y) is the population covariance
     *  σx is the population standard deviation of X
     *  σy is the population standard deviation of Y
     *
     * @param array $x values for random variable X
     * @param array $y values for random variabel Y
     *
     * @return number
     */
    public static function populationCorrelationCoefficient(array $X, array $Y)
    {
        $cov⟮X，Y⟯ = self::populationCovariance($X, $Y);
        $σx      = Descriptive::standardDeviation($X, true);
        $σy      = Descriptive::standardDeviation($Y, true);

        return $cov⟮X，Y⟯ / ( $σx * $σy );
    }

    /**
     * Sample correlation coefficient
     * Pearson product-moment correlation coefficient (PPMCC or PCC or Pearson's r)
     *
     * A normalized measure of the linear correlation between two variables X and Y,
     * giving a value between +1 and −1 inclusive, where 1 is total positive correlation,
     * 0 is no correlation, and −1 is total negative correlation.
     * It is widely used in the sciences as a measure of the degree of linear dependence
     * between two variables.
     * https://en.wikipedia.org/wiki/Pearson_product-moment_correlation_coefficient
     *
     * The correlation coefficient of two variables in a data sample is their covariance
     * divided by the product of their individual standard deviations.
     *
     *          Sxy
     * rxy = ----------
     *         sx sy
     *
     *  Sxy is the sample covariance
     *  σx is the sample standard deviation of X
     *  σy is the sample standard deviation of Y
     *
     * @param array $x values for random variable X
     * @param array $y values for random variabel Y
     *
     * @return number
     */
    public static function sampleCorrelationCoefficient(array $X, array $Y)
    {
        $Sxy = self::sampleCovariance($X, $Y);
        $sx  = Descriptive::standardDeviation($X, Descriptive::SAMPLE);
        $sy  = Descriptive::standardDeviation($Y, Descriptive::SAMPLE);

        return $Sxy / ( $sx * $sy );
    }

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
     * @param array $n n-th central moment to calculate
     *
     * @return number n-th central moment
     */
    public static function centralMoment(array $X, $n)
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
        if (empty($X)) {
            return null;
        }

        $n     = count($X);
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
     */
    public static function skewness(array $X)
    {
        if (empty($X)) {
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
        $N  = count($X);
    
        return $∑⟮xᵢ − μ⟯³ / ($σ³ * ($N - 1));
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
     * @return number
     */
    public static function SES(int $n)
    {
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
     * @return number
     */
    public static function SEK(int $n)
    {
        $２⟮SES⟯        = 2 * self::SES($n);
        $⟮n² − 1⟯       = $n**2 - 1;
        $⟮n − 3⟯⟮n ＋ 5⟯ = ($n - 3) * ($n + 5);

        return $２⟮SES⟯ * sqrt($⟮n² − 1⟯ / (($n - 3) * ($n + 5)));
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
     */
    public static function standardErrorOfTheMean(array $X): float
    {
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
     * @param number $μ  sample mean
     * @param number $n  sample size
     * @param number $σ  standard deviation
     * @param string $cl confidence level (Ex: 95, 99, 99.5, 99.9, etc.)
     *
     * @return array [ ci, lower_bound, upper_bound ]
     */
    public static function confidenceInterval($μ, $n, $σ, string $cl): array
    {
        $z = StandardNormalTable::getZScoreForConfidenceInterval($cl);

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
     * Sum of squares deviations
     *
     * ∑⟮xᵢ - μ⟯²
     * 
     * @param  array  $numbers
     *
     * @return number
     */
    public function sumOfSquaresDeviations(array $numbers)
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

    /**
     * Z score - standard score
     * https://en.wikipedia.org/wiki/Standard_score
     *
     *     x - μ
     * z = -----
     *       σ
     *
     * @param number $μ mean
     * @param number $σ standard deviation
     * @param number $x
     *
     * @return float
     */
    public static function zScore($μ, $σ, $x): float
    {
        return round(($x - $μ) / $σ, 2);
    }
}
