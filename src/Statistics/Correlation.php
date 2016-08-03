<?php
namespace Math\Statistics;

use Math\Statistics\Average;
use Math\Statistics\RandomVariable;
use Math\Functions\Special;
use Math\Functions\Map;

class Correlation
{
    const X = 0;
    const Y = 1;

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
     * @param array $Y values for random variable Y
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
     * @param array $X values for random variable X
     * @param array $Y values for random variable Y
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
     * @param array $X values for random variable X
     * @param array $Y values for random variable Y
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
     * @param array $X values for random variable X
     * @param array $Y values for random variable Y
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
     * @param array $X values for random variable X
     * @param array $Y values for random variable Y
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
     * @param array $X values for random variable X
     * @param array $Y values for random variable Y
     *
     * @return number
     */
    public static function coefficientOfDetermination(array $X, array $Y, bool $popluation = false)
    {
        return pow(self::r($X, $Y, $popluation), 2);
    }

    /**
     * τ - Kendall rank correlation coefficient (Kendall's tau)
     *
     * A statistic used to measure the ordinal association between two
     * measured quantities. It is a measure of rank correlation:
     * the similarity of the orderings of the data when ranked by each
     * of the quantities.
     * https://en.wikipedia.org/wiki/Kendall_rank_correlation_coefficient
     * https://onlinecourses.science.psu.edu/stat509/node/158
     *
     * tau-a (no rank ties):
     *
     *        nc - nd
     *   τ = ----------
     *       n(n - 1)/2
     *
     *   Where
     *     nc: number of concordant pairs
     *     nd: number of discordant pairs
     *
     * tau-b (rank ties exist):
     *
     *                 nc - nd
     *   τ = -----------------------------
     *       √(nc + nd + X₀)(nc + nd + Y₀)
     *
     *   Where
     *     X₀: number of pairs tied only on the X variable
     *     Y₀: number of pairs tied only on the Y variable
     *
     * @param array $X values for random variable X
     * @param array $Y values for random variable Y
     *
     * @todo Implement with algorithm faster than O(n²)
     *
     * @return number
     */
    public static function kendallsTau(array $X, array $Y)
    {
        if (count($X) !== count($Y)) {
            throw new \Exception('Both random variables must have the same number of elements');
        }

        $n = count($X);

        // Match X and Y pairs and sort by X rank
        $xy = array_map(
            function ($x, $y) {
                return [$x, $y];
            },
            $X,
            $Y
        );
        usort($xy, function ($a, $b) {
            return $a[0] <=> $b[0];
        });

        // Initialize counters
        $nc      = 0;  // concordant pairs
        $nd      = 0;  // discordant pairs
        $ties_x  = 0;  // ties xᵢ = xⱼ
        $ties_y  = 0;  // ties yᵢ = yⱼ
        $ties_xy = 0;  // ties xᵢ = xⱼ and yᵢ = yⱼ

        // Tally concordant, discordant, and tied pairs
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                // xᵢ = xⱼ and yᵢ = yⱼ -- neither concordant or discordant
                if ($xy[$i][self::X] == $xy[$j][self::X] && $xy[$i][self::Y] == $xy[$j][self::Y]) {
                    $ties_xy++;
                // xᵢ = xⱼ -- neither concordant or discordant
                } elseif ($xy[$i][self::X] == $xy[$j][self::X]) {
                    $ties_x++;
                // yᵢ = yⱼ -- neither concordant or discordant
                } elseif ($xy[$i][self::Y] == $xy[$j][self::Y]) {
                    $ties_y++;
                // xᵢ < xⱼ and yᵢ < yⱼ -- concordant
                } elseif ($xy[$i][self::X] < $xy[$j][self::X] && $xy[$i][self::Y] < $xy[$j][self::Y]) {
                    $nc++;
                // xᵢ > xⱼ and yᵢ < yⱼ or  xᵢ < xⱼ and yᵢ > yⱼ -- discordant
                } else {
                    $nd++;
                }
            }
        }

        // Numerator: (number of concordant pairs) - (number of discordant pairs)
        $⟮nc − nd⟯ = $nc - $nd;
       
        /* tau-a (no rank ties):
         *   
         *        nc - nd
         *   τ = ----------
         *       n(n - 1)/2
         */
        if ($ties_x == 0 && $ties_y == 0) {
            return $⟮nc − nd⟯ / (($n * ($n - 1)) / 2);
        }

        /* tau-b (rank ties exist):
         *
         *                 nc - nd
         *   τ = -----------------------------
         *       √(nc + nd + X₀)(nc + nd + Y₀)
         */
        return $⟮nc − nd⟯ / sqrt(($nc + $nd + $ties_x) * ($nc + $nd + $ties_y));
    }

    /**
     * ρ - Spearman's rank correlation coefficient (Spearman's rho)
     *
     * https://en.wikipedia.org/wiki/Spearman%27s_rank_correlation_coefficient
     *
     *          6 ∑ dᵢ²
     * ρ = 1 - ---------
     *         n(n² − 1)
     *
     *  Where
     *   dᵢ: the difference between the two ranks of each observation
     *
     * @param array $X values for random variable X
     * @param array $Y values for random variable Y
     *
     * @return number
     */
    public static function spearmansRho(array $X, array $Y)
    {
        if (count($X) !== count($Y)) {
            throw new \Exception('Both random variables must have the same number of elements');
        }

        $n = count($X);

        // Sorted Xs and Ys
        $Xs = $X;
        $Ys = $Y;
        rsort($Xs);
        rsort($Ys);

        // Determine ranks of each X and Y
        // Some items might show up multiple times, so record each successive rank.
        $rg⟮X⟯ = [];
        $rg⟮Y⟯ = [];
        foreach ($Xs as $rank => $xᵢ) {
            if (!isset($rg⟮X⟯[$xᵢ])) {
                $rg⟮X⟯[$xᵢ] = [];
            }
            $rg⟮X⟯[$xᵢ][] = $rank;
        }
        foreach ($Ys as $rank => $yᵢ) {
            if (!isset($rg⟮Y⟯[$yᵢ])) {
                $rg⟮Y⟯[$yᵢ] = [];
            }
            $rg⟮Y⟯[$yᵢ][] = $rank;
        }

        // Determine average rank of each X and Y
        // Rank will not change if value only shows up once.
        // Average is for when values show up multiple times.
        $rg⟮X⟯ = array_map(
            function ($x) {
                return array_sum($x) / count($x);
            },
            $rg⟮X⟯
        );
        $rg⟮Y⟯ = array_map(
            function ($y) {
                return array_sum($y) / count($y);
            },
            $rg⟮Y⟯
        );

        // Difference between the two ranks of each observation
        $d = array_map(
            function ($x, $y) use ($rg⟮X⟯, $rg⟮Y⟯) {
                return abs($rg⟮X⟯[$x] - $rg⟮Y⟯[$y]);
            },
            $X,
            $Y
        );

        // Numerator: 6 ∑ dᵢ²
        $d²  = Map\Single::square($d);
        $∑d² = array_sum($d²);

        // Denominator: n(n² − 1)
        $n⟮n² − 1⟯ = $n * ($n**2 - 1);

        /*
         *          6 ∑ dᵢ²
         * ρ = 1 - ---------
         *         n(n² − 1)
         */
        return 1 - ((6 * $∑d²) / $n⟮n² − 1⟯);
    }

    /**
     * Descriptive correlation report about two random variables
     *
     * @param  array $X          values for random variable X
     * @param  array $Y          values for random variable Y
     * @param  bool  $population Optional flag if all samples of a population are present
     *
     * @return array [cov, r, R2, tau, rho]
     */
    public static function describe(array $X, array $Y, bool $population = false)
    {
        return [
            'cov' => self::covariance($X, $Y, $population),
            'r'   => self::r($X, $Y, $population),
            'R2'  => self::R2($X, $Y, $population),
            'tau' => self::kendallsTau($X, $Y),
            'rho' => self::spearmansRho($X, $Y),
        ];
    }
}
