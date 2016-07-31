<?php
namespace Math\Statistics;

use Math\Statistics\Average;

class Correlation
{
    /**
     * Covariance
     * Convenience method to access population and sample covariance.
     *
     * A measure of how much two random variables change together.
     * Average product of their deviations from their respective means.
     * The population covariance is defined in terms of the sample means x, y
     * https://en.wikipedia.org/wiki/Covariance
     *
     * @param array $X values for random variable X
     * @param array $Y values for random variable Y
     * @param bool  $popluation Optional flag for population or sample covariance
     *
     * @return number
     */
    public static function covariance(array $X, array $Y, bool $population = false)
    {
        return $population
            ? self::populationCovariance($X, $Y)
            : self::sampleCovariance($X, $Y);
    }

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
     *`
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
     * r - correlation coefficient
     * Pearson product-moment correlation coefficient (PPMCC or PCC or Pearson's r)
     *
     * Convenience method for population and sample correlationCoefficient
     *
     * @param array $x values for random variable X
     * @param array $y values for random variabel Y
     * @param bool  $popluation Optional flag for population or sample covariance
     *
     * @return number
     */
    public static function r(array $X, array $Y, bool $popluation = false)
    {
        return $popluation
            ? self::populationCorrelationCoefficient($X, $Y)
            : self::sampleCorrelationCoefficient($X, $Y);
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
     * R² - coefficient of determination
     * Convenience wrapper for coefficientOfDetermination
     *
     * @param array $x values for random variable X
     * @param array $y values for random variabel Y
     *
     * @return number
     */
    public static function R2(array $X, array $Y, bool $popluation = false)
    {
        return pow(self::r($X, $Y, $popluation), 2);
    }

    /**
     * R² - coefficient of determination
     *
     * Indicates the proportion of the variance in the dependent variable
     * that is predictable from the independent variable.
     * Range of 0 - 1. Close to 1 means the regression line is a good fit
     * https://en.wikipedia.org/wiki/Coefficient_of_determination
     *
     * @param array $x values for random variable X
     * @param array $y values for random variabel Y
     *
     * @return number
     */
    public static function coefficientOfDetermination(array $X, array $Y, bool $popluation = false)
    {
        return pow(self::r($X, $Y, $popluation), 2);
    }
}
