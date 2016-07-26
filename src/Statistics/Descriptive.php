<?php
namespace Math\Statistics;

use Math\Statistics\Average;
use Math\Statistics\RandomVariable;

class Descriptive
{
    const POPULATION = true;
    const SAMPLE     = false;

    /**
     * Range - the difference between the largest and smallest values
     * It is the size of the smallest interval which contains all the data.
     * It provides an indication of statistical dispersion.
     * (https://en.wikipedia.org/wiki/Range_(statistics))
     *
     * R = max x - min x
     *
     * @param array $numbers
     * @return number
     */
    public static function range(array $numbers)
    {
        if (empty($numbers)) {
            return null;
        }
        return max($numbers) - min($numbers);
    }

    /**
     * Midrange - the mean of the largest and smallest values
     * It is the midpoint of the range; as such, it is a measure of central tendency.
     * (https://en.wikipedia.org/wiki/Mid-range)
     *
     *     max x + min x
     * M = -------------
     *           2
     *
     * @param array $numbers
     * @return number
     */
    public static function midrange(array $numbers)
    {
        if (empty($numbers)) {
            return null;
        }
        return Average::mean([ min($numbers), max($numbers) ]);
    }

    /**
     * Variance
     *
     * Variance measures how far a set of numbers are spread out.
     * A variance of zero indicates that all the values are identical.
     * Variance is always non-negative: a small variance indicates that the data points
     * tend to be very close to the mean (expected value) and hence to each other.
     * A high variance indicates that the data points are very spread out around the mean
     * and from each other.
     * (https://en.wikipedia.org/wiki/Variance)
     *
     *      ∑⟮xᵢ - μ⟯²
     * σ² = ----------
     *          ν
     *
     * Generalized method that allows setting the degrees of freedom.
     * For population variance, set d.f. (ν) to n
     * For sample variance, set d.f (ν) to n - 1
     * Or use popluationVariance or sampleVaraince covenience methods.
     * 
     * μ is the population mean
     * ν is the degrees of freedom, which usually is
     *   the number of numbers in the population set or n - 1 for sample set.
     *
     * @param array $numbers
     * @param int   $ν degrees of freedom
     * @return numeric
     */
    public static function variance(array $numbers, int $ν)
    {
        if (empty($numbers)) {
            return null;
        }
        if ($ν <= 0) {
            throw new \Exception('Degrees of freedom must be > 0');
        }

        $∑⟮xᵢ − μ⟯² = RandomVariable::sumOfSquaresDeviations($numbers);
    
        return $∑⟮xᵢ − μ⟯² / $ν;
    }

    /**
     * Population variance - Use when all possible observations of the system are present.
     * If used with a subset of data (sample variance), it will be a biased variance.
     *
     *      ∑⟮xᵢ - μ⟯²
     * σ² = ----------
     *          N
     *
     * μ is the population mean
     * N is the number of numbers in the population set
     *
     * @param array $numbers
     * @return numeric
     */
    public static function populationVariance(array $numbers)
    {
        $N = count($numbers);
        return self::variance($numbers, $N);
    }

    /**
     * Unbiased sample variance
     * Use when only a subset of all possible observations of the system are present.
     *
     *      ∑⟮xᵢ - x̄⟯²
     * S² = ----------
     *        n - 1
     *
     * x̄ is the sample mean
     * n is the number of numbers in the sample set
     *
     * @param array $numbers
     * @return numeric
     */
    public static function sampleVariance(array $numbers)
    {
        if (count($numbers) == 1) {
            return 0;
        }

        $n = count($numbers);
        return self::variance($numbers, $n - 1);
    }

    /**
     * Wrapper for computing the variance
     * @param array $numbers
     * @param bool  $population: true uses population variance; false uses sample variance;
     *                            Default is true (population variance)
     * @return numeric
     */
    public static function variance2(array $numbers, bool $population = true)
    {
        return $population
        ? self::populationVariance($numbers)
        : self::sampleVariance($numbers);
    }

    /**
     * Standard deviation
     * A measure that is used to quantify the amount of variation or dispersion of a set of data values.
     * A low standard deviation indicates that the data points tend to be close to the mean
     * (also called the expected value) of the set.
     * A high standard deviation indicates that the data points are spread out over a wider range of values.
     * (https://en.wikipedia.org/wiki/Standard_deviation)
     *
     * σ = √⟮σ²⟯ = √⟮variance⟯
     *
     * @param array $numbers
     * @param bool  $population_variance: true uses population variance; false uses sample variance;
     *              Default is true (population variance)
     * @return numeric
     */
    public static function standardDeviation(array $numbers, bool $population_variance = true)
    {
        if (empty($numbers)) {
            return null;
        }

        $n = count($numbers);

        return $population_variance
            ? sqrt(self::populationVariance($numbers))
            : sqrt(self::sampleVariance($numbers));
    }

    /**
     * MAD - mean absolute deviation
     *
     * The average of the absolute deviations from a central point.
     * It is a summary statistic of statistical dispersion or variability.
     * (https://en.wikipedia.org/wiki/Average_absolute_deviation)
     *
     *       ∑|xᵢ - x̄|
     * MAD = ---------
     *           N
     *
     * x̄ is the mean
     * N is the number of numbers in the population set
     *
     * @param array $numbers
     * @return numeric
     */
    public static function meanAbsoluteDeviation(array $numbers)
    {
        if (empty($numbers)) {
            return null;
        }

        $x         = Average::mean($numbers);
        $∑│xᵢ − x│ = array_sum(array_map(
            function ($xᵢ) use ($x) {
                return abs($xᵢ - $x);
            },
            $numbers
        ));
        $N = count($numbers);

        return $∑│xᵢ − x│ / $N;
    }

    /**
     * MAD - median absolute deviation
     *
     * The average of the absolute deviations from a central point.
     * It is a summary statistic of statistical dispersion or variability.
     * It is a robust measure of the variability of a univariate sample of quantitative data.
     * (https://en.wikipedia.org/wiki/Median_absolute_deviation)
     *
     * MAD = median(|xᵢ - x̄|)
     *
     * x̄ is the median
     *
     * @param array $numbers
     * @return numeric
     */
    public static function medianAbsoluteDeviation(array $numbers)
    {
        if (empty($numbers)) {
            return null;
        }

        $x = Average::median($numbers);
        return Average::median(array_map(
            function ($xᵢ) use ($x) {
                return abs($xᵢ - $x);
            },
            $numbers
        ));
    }

    /**
     * Quartiles
     * Three points that divide the data set into four equal groups, each group comprising a quarter of the data.
     * https://en.wikipedia.org/wiki/Quartile
     *
     * There are multiple methods for computing quartiles:
     *  - Inclusive
     *  - Exclusive
     *
     * @param array $numbers
     * @param string $method What quartile method to use (optional - default: exclusive)
     *
     * @return array [ 0%, Q1, Q2, Q3, 100%, IQR ]
     */
    public static function quartiles(array $numbers, string $method = 'exclusive')
    {
        switch (strtolower($method)) {
            case 'inclusive':
                return self::quartilesInclusive($numbers);
            case 'exclusive':
                return self::quartilesExclusive($numbers);
            default:
                return self::quartilesExclusive($numbers);
        }
    }

    /**
     * Quartiles - Exclusive method
     * Three points that divide the data set into four equal groups, each group comprising a quarter of the data.
     * https://en.wikipedia.org/wiki/Quartile
     *
     * 0% is smallest number
     * Q1 (25%) is first quartile (lower quartile, 25th percentile)
     * Q2 (50%) is second quartile (median, 50th percentile)
     * Q3 (75%) is third quartile (upper quartile, 75th percentile)
     * 100% is largest number
     * interquartile_range is the difference between the upper and lower quartiles. (IQR = Q₃ - Q₁)
     *
     * Method used
     *  - Use the median to divide the ordered data set into two halves.
     *   - If there are an odd number of data points in the original ordered data set, do not include the median
     *     (the central value in the ordered list) in either half.
     *   - If there are an even number of data points in the original ordered data set,
     *     split this data set exactly in half.
     *  - The lower quartile value is the median of the lower half of the data.
     *    The upper quartile value is the median of the upper half of the data.
     *
     * This rule is employed by the TI-83 calculator boxplot and "1-Var Stats" functions.
     * This is the most basic method that is commonly taught in math textbooks.
     *
     * @param array $numbers
     * @return array [ 0%, Q1, Q2, Q3, 100%, IQR ]
     */
    public static function quartilesExclusive(array $numbers): array
    {
        if (empty($numbers)) {
            return array();
        }

        sort($numbers);
        $length = count($numbers);
        if ($length % 2 == 0) {
            $lower_half = array_slice($numbers, 0, $length / 2);
            $upper_half = array_slice($numbers, $length / 2);
        } else {
            $lower_half = array_slice($numbers, 0, intdiv($length, 2));
            $upper_half = array_slice($numbers, intdiv($length, 2) + 1);
        }

        $lower_quartile = Average::median($lower_half);
        $upper_quartile = Average::median($upper_half);

        return [
            '0%'   => min($numbers),
            'Q1'   => $lower_quartile,
            'Q2'   => Average::median($numbers),
            'Q3'   => $upper_quartile,
            '100%' => max($numbers),
            'IQR'  => $upper_quartile - $lower_quartile,
        ];
    }

    /**
     * Quartiles - Inclusive method (R method)
     * Three points that divide the data set into four equal groups, each group comprising a quarter of the data.
     * https://en.wikipedia.org/wiki/Quartile
     *
     * 0% is smallest number
     * Q1 (25%) is first quartile (lower quartile, 25th percentile)
     * Q2 (50%) is second quartile (median, 50th percentile)
     * Q3 (75%) is third quartile (upper quartile, 75th percentile)
     * 100% is largest number
     * interquartile_range is the difference between the upper and lower quartiles. (IQR = Q₃ - Q₁)
     *
     * Method used
     *  - Use the median to divide the ordered data set into two halves.
     *   - If there are an odd number of data points in the original ordered data set,
     *     include the median (the central value in the ordered list) in both halves.
     *   - If there are an even number of data points in the original ordered data set,
     *     split this data set exactly in half.
     *  - The lower quartile value is the median of the lower half of the data.
     *    The upper quartile value is the median of the upper half of the data.
     *
     * The values found by this method are also known as "Tukey's hinges".
     * This is the method that the programming language R uses by default.
     *
     * @param array $numbers
     * @return array [ 0%, Q1, Q2, Q3, 100%, IQR ]
     */
    public static function quartilesInclusive(array $numbers): array
    {
        if (empty($numbers)) {
            return array();
        }

        sort($numbers);
        $length = count($numbers);
        if ($length % 2 == 0) {
            $lower_half = array_slice($numbers, 0, $length / 2);
            $upper_half = array_slice($numbers, $length / 2);
        } else {
            $lower_half = array_slice($numbers, 0, intdiv($length, 2));
            $upper_half = array_slice($numbers, intdiv($length, 2) + 1);

            // Add median to both halves
            $median = Average::median($numbers);
            array_push($lower_half, $median);
            array_unshift($upper_half, $median);
        }

        $lower_quartile = Average::median($lower_half);
        $upper_quartile = Average::median($upper_half);

        return [
            '0%'   => min($numbers),
            'Q1'   => $lower_quartile,
            'Q2'   => Average::median($numbers),
            'Q3'   => $upper_quartile,
            '100%' => max($numbers),
            'IQR'  => $upper_quartile - $lower_quartile,
        ];
    }

    /**
     * IQR - Interquartile range (midspread, middle fifty)
     * A measure of statistical dispersion.
     * Difference between the upper and lower quartiles.
     * https://en.wikipedia.org/wiki/Interquartile_range
     *
     * IQR = Q₃ - Q₁
     *
     * @param array  $numbers
     * @param string $method What quartile method to use (optional - default: exclusive)
     *
     * @return number
     */
    public static function interquartileRange(array $numbers, string $method = 'exclusive')
    {
        return self::quartiles($numbers, $method)['IQR'];
    }

    /**
     * IQR - Interquartile range (midspread, middle fifty)
     * Convenience wrapper function for interquartileRange.
     *
     * @param array  $numbers
     * @param string $method What quartile method to use (optional - default: exclusive)
     *
     * @return number
     */
    public static function IQR(array $numbers, string $method = 'exclusive')
    {
        return self::quartiles($numbers, $method)['IQR'];
    }

    /**
     * Compute the P-th percentile of a list of numbers
     *
     * Nearest rank method
     * P-th percentile (0 <= P <= 100) of a list of N ordered values (sorted from least to greatest)
     * is the smallest value in the list such that P percent of the data is less than or equal to that value.
     * This is obtained by first calculating the ordinal rank,
     * and then taking the value from the ordered list that corresponds to that rank.
     * https://en.wikipedia.org/wiki/Percentile
     *
     *     ⌈  P      ⌉
     * n = | --- × N |
     *     | 100     |
     *
     * n: ordinal rank
     * P: percentile
     * N: number of elements in list
     *
     * @param array $numbers
     * @param int   $P percentile to calculate
     * @return number in list corresponding to P percentile
     * @throws \Exception if $P percentile is not between 0 and 100
     */
    public static function percentile(array $numbers, int $P)
    {
        if ($P < 0 || $P > 100) {
            throw new \Exception('Percentile P must be between 0 and 100.');
        }
        sort($numbers);

        $N = count($numbers);
        $n = ($P / 100) * $N;

        return $numbers[ ceil($n) - 1 ];
    }

    /**
     * Midhinge
     * The average of the first and third quartiles and is thus a measure of location.
     * Equivalently, it is the 25% trimmed mid-range or 25% midsummary; it is an L-estimator.
     * https://en.wikipedia.org/wiki/Midhinge
     *
     * Midhinge = (first quartile, third quartile) / 2
     *
     * @param  array  $numbers
     * @return number
     */
    public static function midhinge(array $numbers)
    {
        $quartiles = self::quartiles($numbers);
        $Q1        = $quartiles['Q1'];
        $Q2        = $quartiles['Q3'];
        return Average::mean([$Q1, $Q2]);
    }

    /**
     * Get a report of all the descriptive statistics over a list of numbers
     * Includes mean, median, mode, range, midrange, variance, standard deviation, quartiles, etc.
     *
     * @param array $numbers
     * @param bool  $population: true means all possible observations of the system are present;
     *              false means a sample is used.
     * @return array [ n, mean, median, mode, range, midrange, variance, sd, mean_mad,
     *                 median_mad, quartiles, skewness, kurtosis, sem, ci_95, ci_99 ]
     */
    public static function describe(array $numbers, bool $population = false): array
    {
        $n = count($numbers);
        $μ = Average::mean($numbers);
        $σ = self::standardDeviation($numbers, $population);

        return [
            'n'                  => $n,
            'mean'               => $μ,
            'median'             => Average::median($numbers),
            'mode'               => Average::mode($numbers),
            'range'              => self::range($numbers),
            'midrange'           => self::midrange($numbers),
            'variance'           => $population ? self::populationVariance($numbers) : self::sampleVariance($numbers),
            'sd'                 => $σ,
            'mean_mad'           => self::meanAbsoluteDeviation($numbers),
            'median_mad'         => self::medianAbsoluteDeviation($numbers),
            'quartiles'          => self::quartiles($numbers),
            'midhinge'           => self::midhinge($numbers),
            'skewness'           => $population ? RandomVariable::populationSkewness($numbers) : RandomVariable::sampleSkewness($numbers),
            'kurtosis'           => RandomVariable::kurtosis($numbers),
            'sem'                => RandomVariable::standardErrorOfTheMean($numbers),
            'ci_95'              => RandomVariable::confidenceInterval($μ, $n, $σ, 95),
            'ci_99'              => RandomVariable::confidenceInterval($μ, $n, $σ, 99),
        ];
    }
}
