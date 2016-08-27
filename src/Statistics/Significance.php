<?php
namespace Math\Statistics;

use Math\Statistics\Average;
use Math\Statistics\RandomVariable;
use Math\Probability\Distribution\Continuous\StandardNormal;
use Math\Probability\Distribution\Continuous\StudentT;

/**
 * Tests of statistical significance
 *  - Z-test
 *  - Z-score
 *  - T-test
 *  - T-score
 */
class Significance
{
    const Z_TABLE_VALUE = true;
    const Z_RAW_VALUE   = false;

    /**
     * Z-test
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
    public static function zTest($Hₐ, $n, $H₀, $σ)
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
    public static function zScore($M, $μ, $σ, bool $table_value = true): float
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
     *
     * @return array [
     *   z  => z score
     *   p1 => one-tailed p value (left or right tail depends on how Hₐ differs from H₀)
     *   p2 => two-tailed p value
     * ]
     */
    public static function tTestOneSample($Hₐ, $s, $n, $H₀)
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
     * T-score
     *
     *     Hₐ - H₀   X - μ
     * t = ------- = -----
     *      s/√n      s/√n
     */
    public static function tScore($Hₐ, $s, $n, $H₀)
    {
        return ($Hₐ - $H₀) / ($s / sqrt($n));
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
