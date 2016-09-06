<?php
namespace Math\Statistics;

use Math\Probability\Distribution\Continuous\StandardNormal;
use Math\Probability\Distribution\Continuous\StudentT;
use Math\Probability\Distribution\Continuous\ChiSquared;

/**
 * Tests of statistical significance
 *  - Z-test (one sample)
 *  - Z-score
 *  - T-test (one and two samples)
 *  - T-score
 *  - χ² test
 *  - SEM (Standard Error of the Mean)
 */
class Significance
{
    const Z_TABLE_VALUE = true;
    const Z_RAW_VALUE   = false;

    /**
     * One-sample Z-test
     * When the population mean and standard deviation are known.
     * https://en.wikipedia.org/wiki/Z-test
     *
     *     Hₐ - H₀   M - μ   M - μ   M - μ
     * z = ------- = ----- = ----- = -----
     *        σ        σ      SEM     σ/√n
     *
     * p1 = CDF below if left tailed
     *    = CDF above if right tailed
     * p2 = CDF outside
     *
     * @param number $Hₐ Alternate hypothesis (M Sample mean)
     * @param int    $n  Sample size
     * @param number $H₀ Null hypothesis (μ Population mean)
     * @param number $σ  SD of population (Standard error of the mean)
     *
     * @return array [
     *   z  => z score
     *   p1 => one-tailed p value (left or right tail depends on how Hₐ differs from H₀)
     *   p2 => two-tailed p value
     * ]
     */
    public static function zTest($Hₐ, $n, $H₀, $σ): array
    {
        // Calculate z score (test statistic)
        $sem = self::sem($σ, $n);
        $z   = self::zScore($Hₐ, $H₀, $sem, self::Z_RAW_VALUE);

        // One- and two-tailed P values
        if ($Hₐ < $H₀) {
            $p1 = StandardNormal::CDF($z);
        } else {
            $p1 = StandardNormal::above($z);
        }
        $p2 = StandardNormal::outside(-abs($z), abs($z));

        return [
            'z'  => $z,
            'p1' => $p1,
            'p2' => $p2,
        ];
    }

    /**
     * Z score - standard score
     * https://en.wikipedia.org/wiki/Standard_score
     *
     *     M - μ
     * z = -----
     *       σ
     *
     * @param number $M           Sample mean
     * @param number $μ           Population mean
     * @param number $σ           Population standard deviation
     * @param bool   $table_value Whether to return a rouned z score for looking up in a standard normal table, or the raw z score value
     *
     * @return float
     */
    public static function zScore($M, $μ, $σ, bool $table_value = false): float
    {
        $z = ($M - $μ) / $σ;

        return $table_value
            ? round($z, 2)
            : $z;
    }

    /**
     * One-sample Student's t-test
     * Compares sample mean to the population mean.
     * https://en.wikipedia.org/wiki/Student%27s_t-test
     *
     *     Hₐ - H₀   M - μ   M - μ   M - μ
     * z = ------- = ----- = ----- = -----
     *        σ        σ      SEM     σ/√n
     *
     * p1 = CDF below if left tailed
     *    = CDF above if right tailed
     * p2 = CDF outside
     *
     * @param number $Hₐ Alternate hypothesis (M Sample mean)
     * @param number $s  SD of sample
     * @param int    $n  Sample size
     * @param number $H₀ Null hypothesis (μ₀ Population mean)
     *
     * @return array [
     *   z  => z score
     *   p1 => one-tailed p value (left or right tail depends on how Hₐ differs from H₀)
     *   p2 => two-tailed p value
     * ]
     */
    public static function tTestOneSample($Hₐ, $s, $n, $H₀): array
    {
        // Calculate test statistic t
        $t = self::tScore($Hₐ, $s, $n, $H₀);

        // Degrees of freedom
        $ν = $n - 1;

        // One- and two-tailed P values
        if ($Hₐ < $H₀) {
            $p1 = StudentT::CDF($t, $ν);
        } else {
            $p1 = StudentT::above($t, $ν);
        }
        $p2 = StudentT::outside(-abs($t), abs($t), $ν);

        return [
            't'  => $t,
            'p1' => $p1,
            'p2' => $p2,
        ];
    }

    /**
     * Two-sample t-test
     * Test the means of two samples.
     * https://en.wikipedia.org/wiki/Student%27s_t-test
     *
     *      μ₁ - μ₂ - Δ
     * z = --------------
     *        _________
     *       /σ₁²   σ₂²
     *      / --- + ---
     *     √   n₁    n₂
     *
     * where
     *  μ₁ is sample mean 1
     *  μ₂ is sample mean 2
     *  Δ  is the hypothesized difference between the population means (0 if testing for equal means)
     *  σ₁ is standard deviation of sample mean 1
     *  σ₂ is standard deviation of sample mean 2
     *  n₁ is sample size of mean 1
     *  n₂ is sample size of mean 2
     *
     * For Student's t distribution CDF, degrees of freedom:
     *  ν = (n₁ - 1) + (n₂ - 1)
     *
     * p1 = CDF above
     * p2 = CDF outside
     *
     * @param number $μ₁ Sample mean of population 1
     * @param number $μ₂ Sample mean of population 2
     * @param number $n₁ Sample size of population 1
     * @param number $n₂ Sample size of population 1
     * @param number $σ₁ Standard deviation of sample mean 1
     * @param number $σ₂ Standard deviation of sample mean 2
     * @param number $Δ  (Optional) hypothesized difference between the population means (0 if testing for equal means)
     *
     * @return array [
     *   t  => t score
     *   p1 => one-tailed p value
     *   p2 => two-tailed p value
     * ]
     */
    public static function tTestTwoSample($μ₁, $μ₂, $n₁, $n₂, $σ₁, $σ₂, $Δ = 0): array
    {
        // Calculate t score (test statistic)
        $t = ($μ₁ - $μ₂ - $Δ) / sqrt((($σ₁**2) / $n₁) + (($σ₂**2) / $n₂));

        // Degrees of freedom
        $ν = ($n₁ - 1) + ($n₂ - 1);

        // One- and two-tailed P values
        $p1 = StudentT::above(abs($t), $ν);
        $p2 = StudentT::outside(-abs($t), abs($t), $ν);

        return [
            't'  => $t,
            'p1' => $p1,
            'p2' => $p2,
        ];
    }

    /**
     * T-score
     *
     *     Hₐ - H₀   X - μ
     * t = ------- = -----
     *      s/√n      s/√n
     *
     * @param number $Hₐ Alternate hypothesis (M Sample mean)
     * @param number $s  SD of sample
     * @param int    $n  Sample size
     * @param number $H₀ Null hypothesis (μ₀ Population mean)
     *
     * @return number
     */
    public static function tScore($Hₐ, $s, $n, $H₀)
    {
        return ($Hₐ - $H₀) / ($s / sqrt($n));
    }

    /**
     * χ² test (chi-squared goodness of fit test)
     * Tests the hypothesis that data were generated according to a
     * particular chance model (Statistics [Freedman, Pisani, Purves]).
     * https://en.wikipedia.org/wiki/Chi-squared_test#Example_chi-squared_test_for_categorical_data
     *
     *        (Oᵢ - Eᵢ)²
     * χ² = ∑ ----------
     *            Eᵢ
     *  where:
     *   O = observed value
     *   E = expected value
     *
     * k (degrees of freedom) = number of terms - 1
     *
     * p = χ² distribution CDF(χ², k)
     *
     * @param  array  $observed
     * @param  array  $expected
     *
     * @return array [chi-square, p]
     * @throws Exception if count of observed does not equal count of expected
     */
    public static function chiSquaredTest(array $observed, array $expected)
    {
        // Arrays must have the same number of elements
        if (count($observed) !== count($expected)) {
            throw new \Exception('Observed and expected must have the same number of elements');
        }

        // Reset array indexes and initialize
        $O  = array_values($observed);
        $E  = array_values($expected);
        $n  = count($observed);        // number of terms
        $k  = $n - 1;                  // degrees of freedom
        $χ² = 0;

        /*
         *        (Oᵢ - Eᵢ)²
         * χ² = ∑ ----------
         *            Eᵢ
         */
        for ($i = 0; $i < $n; $i++) {
            $χ² += (($O[$i] - $E[$i])**2) / $E[$i];
        }

        $p = ChiSquared::above($χ², $k);

        return [
            'chi-square' => $χ²,
            'p'          => $p,
        ];
    }

    /**
     * Standard error of the mean (SEM)
     * Can be considered true standard deviation of the sample mean.
     * Used in the Z test.
     * https://en.wikipedia.org/wiki/Standard_error
     *
     *       σ
     * SEM = --
     *       √n
     *
     * @param number $σ Population standard deviation
     * @param int    $n Sample size (number of observations of the sample)
     *
     * @return float
     */
    public static function sem($σ, $n)
    {
        return $σ / sqrt($n);
    }
}
