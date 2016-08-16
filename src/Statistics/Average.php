<?php
namespace Math\Statistics;

use Math\Functions\Map;

/**
 * Statistical averages
 * Mean, median, mode
 * Geometric mean, harmonic mean, arithmetic-geometric mean
 */
class Average
{
    /**
     * Calculate the mean average of a list of numbers
     *
     *     ∑⟮xᵢ⟯
     * x̄ = -----
     *       n
     *
     * @param array $numbers
     *
     * @return number
     */
    public static function mean(array $numbers)
    {
        if (empty($numbers)) {
            return null;
        }
        return array_sum($numbers) / count($numbers);
    }

    /**
     * Calculate the median average of a list of numbers
     *
     * @param array $numbers
     *
     * @return number
     */
    public static function median(array $numbers)
    {
        if (empty($numbers)) {
            return null;
        }

        // Reset the array key indexes because we don't know what might be passed in
        $numbers = array_values($numbers);

        // For odd number of numbers, take the middle indexed number
        if (count($numbers) % 2 == 1) {
            $middle_index = intdiv(count($numbers), 2);
            sort($numbers);
            return $numbers[$middle_index];
        }

        // For even number of items, take the mean of the middle two indexed numbers
        $left_middle_index  = intdiv(count($numbers), 2) - 1;
        $right_middle_index = $left_middle_index + 1;
        sort($numbers);
        return self::mean([ $numbers[$left_middle_index], $numbers[$right_middle_index] ]);
    }

    /**
     * Return the kth smallest value in an array
     *
     * if $a = [1,2,3,4,6,7]
     * kthSmallest($a, 4) = 6
     *
     * @param array $numbers
     * @param int $k zero indexed
     *
     * @return number
     */
    public static function kthSmallest(array $numbers, int $k)
    {
        $n = count($numbers);
        if (empty($numbers) || $k >= $n) {
            return null;
        }

        // Reset the array key indexes because we don't know what might be passed in
        $numbers = array_values($numbers);
        sort($numbers);

        return $numbers[$k];
    }
    
    /**
     * Calculate the mode average of a list of numbers
     * If multiple modes (bimodal, trimodal, etc.), all modes will be returned.
     * Always returns an array, even if only one mode.
     *
     * @param array $numbers
     *
     * @return array of mode(s)
     */
    public static function mode(array $numbers): array
    {
        if (empty($numbers)) {
            return [];
        }

        // Count how many times each number occurs
        // Determine the max any number occurs
        // Find all numbers that occur max times
        $number_counts = array_count_values($numbers);
        $max           = max($number_counts);
        $modes         = array();
        foreach ($number_counts as $number => $count) {
            if ($count === $max) {
                $modes[] = $number;
            }
        }
        return $modes;
    }

    /**
     * Geometric mean
     * A type of mean which indicates the central tendency or typical value of a set of numbers
     * by using the product of their values (as opposed to the arithmetic mean which uses their sum).
     * https://en.wikipedia.org/wiki/Geometric_mean
     *                    __________
     * Geometric mean = ⁿ√a₀a₁a₂ ⋯
     *
     * @param  array  $numbers
     * @return number
     */
    public static function geometricMean(array $numbers)
    {
        if (empty($numbers)) {
            return null;
        }

        $n = count($numbers);
        return pow(array_reduce($numbers, function ($carry, $a) {
            return !empty($carry) ? $carry * $a : $a;
        }), 1/$n);
    }

    /**
     * Harmonic mean (subcontrary mean)
     * The harmonic mean can be expressed as the reciprocal of the arithmetic mean of the reciprocals.
     * Appropriate for situations when the average of rates is desired.
     * https://en.wikipedia.org/wiki/Harmonic_mean
     *
     * @param  array  $numbers
     * @return number
     */
    public static function harmonicMean(array $numbers)
    {
        if (empty($numbers)) {
            return null;
        }

        // Can't be computed for negative values.
        if (!empty(array_filter($numbers, function ($x) {
            return $x < 0;
        }))) {
            throw new \Exception('Harmonic mean cannot be computed for negative values.');
        }

        $n = count($numbers);
        return $n / array_sum(array_map(function ($x) {
            return 1 / $x;
        }, $numbers));
    }

    /**
     * Contraharmonic mean
     * A function complementary to the harmonic mean.
     * A special case of the Lehmer mean, L₂(x), where p = 2.
     * https://en.wikipedia.org/wiki/Contraharmonic_mean
     *
     * @param  array  $numbers
     * @return number
     */
    public static function contraharmonicMean(array $numbers)
    {
        $p = 2;
        return self::lehmerMean($numbers, $p);
    }

    /**
     * Root mean square (quadratic mean)
     * The square root of the arithmetic mean of the squares of a set of numbers.
     * https://en.wikipedia.org/wiki/Root_mean_square
     *           ___________
     *          /x₁+²x₂²+ ⋯
     * x rms = / -----------
     *        √       n
     *
     * @param  array  $numbers
     * @return number
     */
    public static function rootMeanSquare(array $numbers)
    {
        $x₁²＋x₂²＋⋯ = array_sum(array_map(
            function ($x) {
                return $x**2;
            },
            $numbers
        ));
        $n = count($numbers);
        return sqrt($x₁²＋x₂²＋⋯ / $n);
    }

    /**
     * Quadradic mean (root mean square)
     * Convenience function for rootMeanSquare
     *
     * @param  array  $numbers
     * @return number
     */
    public static function quadraticMean(array $numbers)
    {
        return self::rootMeanSquare($numbers);
    }

    /**
     * Trimean (TM, or Tukey's trimean)
     * A measure of a probability distribution's location defined as
     * a weighted average of the distribution's median and its two quartiles.
     * https://en.wikipedia.org/wiki/Trimean
     *
     *      Q₁ + 2Q₂ + Q₃
     * TM = -------------
     *            4
     *
     * @param  array  $numbers
     * @return number
     */
    public static function trimean(array $numbers)
    {
        $quartiles = Descriptive::quartiles($numbers);
        $Q₁        = $quartiles['Q1'];
        $Q₂        = $quartiles['Q2'];
        $Q₃        = $quartiles['Q3'];

        return ($Q₁ + 2*$Q₂ + $Q₃) / 4;
    }

    /**
     * Interquartile mean (IQM)
     * A measure of central tendency based on the truncated mean of the interquartile range.
     * Only the data in the second and third quartiles is used (as in the interquartile range),
     * and the lowest 25% and the highest 25% of the scores are discarded.
     * https://en.wikipedia.org/wiki/Interquartile_mean
     *
     * @param  array  $numbers
     * @return number
     */
    public static function interquartileMean(array $numbers)
    {
        return self::truncatedMean($numbers, 25);
    }

    /**
     * IQM (Interquartile mean)
     * Convenience function for interquartileMean
     *
     * @param  array  $numbers
     * @return number
     */
    public static function iqm(array $numbers)
    {
        return self::truncatedMean($numbers, 25);
    }

    /**
     * Cubic mean
     * https://en.wikipedia.org/wiki/Cubic_mean
     *              _________
     *             / 1  n
     * x cubic = ³/  -  ∑ xᵢ³
     *           √   n ⁱ⁼¹
     *
     * @param  array $numbers
     * @return number
     */
    public static function cubicMean(array $numbers)
    {
        $n    = count($numbers);
        $∑xᵢ³ = array_sum(array_map(
            function ($x) {
                return $x**3;
            },
            $numbers
        ));

        return pow(1/$n * $∑xᵢ³, 1/3);
    }

    /**
     * Truncated mean (trimmed mean)
     * The mean after discarding given parts of a probability distribution or sample
     * at the high and low end, and typically discarding an equal amount of both.
     * This number of points to be discarded is given as a percentage of the total number of points.
     * https://en.wikipedia.org/wiki/Truncated_mean
     *
     * Trim count = floor( (trim percent / 100) * sample size )
     *
     * For example: [8, 3, 7, 1, 3, 9] with a trim of 20%
     * First sort the list: [1, 3, 3, 7, 8, 9]
     * Sample size = 6
     * Then determine trim count: floot(20/100 * 6 ) = 1
     * Trim the list by removing 1 from each end: [3, 3, 7, 8]
     * Finally, find the mean: 5.2
     *
     * @param  array  $numbers
     * @param  int    $trim_percent Percent between 0-99
     * @return number
     */
    public static function truncatedMean(array $numbers, int $trim_percent)
    {
        if ($trim_percent < 0 || $trim_percent > 99) {
            throw new \Exception('Trim percent must be between 0 and 99.');
        }

        $n          = count($numbers);
        $trim_count = floor($n * ($trim_percent / 100));

        sort($numbers);
        for ($i = 1; $i <= $trim_count; $i++) {
            array_shift($numbers);
            array_pop($numbers);
        }
        return self::mean($numbers);
    }

    /**
     * Lehmer mean
     * https://en.wikipedia.org/wiki/Lehmer_mean
     *
     *          ∑xᵢᵖ
     * Lp(x) = ------
     *         ∑xᵢᵖ⁻¹
     *
     * Special cases:
     *  L-∞(x) is the min(x)
     *  L₀(x) is the harmonic mean
     *  L½(x₀, x₁) is the geometric mean if computed against two numbers
     *  L₁(x) is the arithmetic mean
     *  L₂(x) is the contraharmonic mean
     *  L∞(x) is the max(x)
     *
     * @param  array  $numbers
     * @param  number $p
     * @return number
     */
    public static function lehmerMean(array $numbers, $p)
    {
        // Special cases for infinite p
        if ($p == -\INF) {
            return min($numbers);
        }
        if ($p == \INF) {
            return max($numbers);
        }

        // Standard case for non-infinite p
        $∑xᵢᵖ = array_sum(array_map(
            function ($x) use ($p) {
                return $x**$p;
            },
            $numbers
        ));
        $∑xᵢᵖ⁻¹ = array_sum(array_map(
            function ($x) use ($p) {
                return $x**($p - 1);
            },
            $numbers
        ));

        return $∑xᵢᵖ / $∑xᵢᵖ⁻¹;
    }

    /**
     * Generalized mean (power mean, Hölder mean)
     * https://en.wikipedia.org/wiki/Generalized_mean
     *
     *          / 1  n    \ 1/p
     * Mp(x) = |  -  ∑ xᵢᵖ|
     *          \ n ⁱ⁼¹   /
     *
     * Special cases:
     *  M-∞(x) is min(x)
     *  M₋₁(x) is the harmonic mean
     *  M₀(x) is the geometric mean
     *  M₁(x) is the arithmetic mean
     *  M₂(x) is the quadratic mean
     *  M₃(x) is the cubic mean
     *  M∞(x) is max(X)
     *
     * @param  array  $numbers
     * @param  number $p
     * @return number
     */
    public static function generalizedMean(array $numbers, $p)
    {
        // Special cases for infinite p
        if ($p == -\INF) {
            return min($numbers);
        }
        if ($p == \INF) {
            return max($numbers);
        }

        // Special case for p = 0 (geometric mean)
        if ($p == 0) {
            return self::geometricMean($numbers);
        }

        // Standard case for non-infinite p
        $n    = count($numbers);
        $∑xᵢᵖ = array_sum(array_map(
            function ($x) use ($p) {
                return $x**$p;
            },
            $numbers
        ));

        return pow(1/$n * $∑xᵢᵖ, 1/$p);
    }

    /**
     * Power mean (generalized mean)
     * Convenience method for generalizedMean
     *
     * @param  array  $numbers
     * @param  number $p
     * @return number
     */
    public static function powerMean(array $numbers, $p)
    {
        return self::generalizedMean($numbers, $p);
    }

    /**
     * Simple n-point moving average SMA
     * The unweighted mean of the previous n data.
     *
     * First calculate initial average:
     *  ⁿ⁻¹
     *   ∑ xᵢ
     *  ᵢ₌₀
     *
     * To calculating successive values, a new value comes into the sum and an old value drops out:
     *  SMAtoday = SMAyesterday + NewNumber/N - DropNumber/N
     *
     * @param  array  $numbers
     * @param  int    $n       n-point moving average
     *
     * @return array of averages for each n-point time period
     */
    public static function simpleMovingAverage(array $numbers, int $n): array
    {
        $m   = count($numbers);
        $SMA = [];

        // Counters
        $new       = $n; // New value comes into the sum
        $drop      = 0;  // Old value drops out
        $yesterday = 0;  // Yesterday's SMA

        // Base case: initial average
        $SMA[] = array_sum(array_slice($numbers, 0, $n)) / $n;

        // Calculating successive values: New value comes in; old value drops out
        while ($new < $m) {
            $SMA[] = $SMA[$yesterday] + ($numbers[$new] / $n) - ($numbers[$drop] / $n);
            $drop++;
            $yesterday++;
            $new++;
        }

        return $SMA;
    }

    /**
     * Cumulative moving average (CMA)
     *
     * Base case for initial average:
     *         x₀
     *  CMA₀ = --
     *         1
     *
     * Standard case:
     *         xᵢ + (i * CMAᵢ₋₁)
     *  CMAᵢ = -----------------
     *              i + 1
     *
     * @param  array  $numbers
     *
     * @return array of cumulative averages
     */
    public static function cumulativeMovingAverage(array $numbers): array
    {
        $m        = count($numbers);
        $CMA      = [];

        // Base case: first average is just itself
        $CMA[] = $numbers[0];

        for ($i = 1; $i < $m; $i++) {
            $CMA[] = (($numbers[$i]) + ($CMA[$i - 1] * $i)) / ($i + 1);
        }

        return $CMA;
    }

    /**
     * Weighted n-point moving average (WMA)
     *
     * Similar to simple n-point moving average,
     * however, each n-point has a weight associated with it,
     * and instead of dividing by n, we divide by the sum of the weights.
     *
     * Each weighted average = ∑(weighted values) / ∑(weights)
     *
     * @param  array  $numbers
     * @param  int    $n       n-point moving average
     * @param  array  $weights Weights for each n points
     *
     * @return array of averages
     */
    public static function weightedMovingAverage(array $numbers, int $n, array $weights): array
    {
        if (count($weights) !== $n) {
            throw new \Exception("Number of weights must equal number of n-points");
        }

        $m   = count($numbers);
        $∑w  = array_sum($weights);
        $WMA = [];

        for ($i = 0; $i <= $m - $n; $i++) {
            $∑wp   = array_sum(Map\Multi::multiply(array_slice($numbers, $i, $n), $weights));
            $WMA[] = $∑wp / $∑w;
        }

        return $WMA;
    }

    /**
     * Exponential moving average (EMA)
     *
     * The start of the EPA is seeded with the first data point.
     * Then each day after that:
     *  EMAtoday = α⋅xtoday + (1-α)EMAyesterday
     *
     *   where
     *    α: coefficient that represents the degree of weighting decrease, a constant smoothing factor between 0 and 1.
     *
     * @param array  $numbers
     * @param int    $n       Length of the EPA
     *
     * @return array of exponential moving averages
     */
    public static function exponentialMovingAverage(array $numbers, int $n): array
    {
        $m   = count($numbers);
        $α   = 2 / ($n + 1);
        $EMA = [];

        // Start off by seeding with the first data point
        $EMA[] = $numbers[0];

        // Each day after: EMAtoday = α⋅xtoday + (1-α)EMAyesterday
        for ($i = 1; $i < $m; $i++) {
            $EMA[] = ($α * $numbers[$i]) + ((1 - $α) * $EMA[$i - 1]);
        }

        return $EMA;
    }

    /**
     * Arithmetic-Geometric mean
     *
     * First, compute the arithmetic and geometric means of x and y, calling them a₁ and g₁ respectively.
     * Then, use iteration, with a₁ taking the place of x and g₁ taking the place of y.
     * Both a and g will converge to the same mean.
     * https://en.wikipedia.org/wiki/Arithmetic%E2%80%93geometric_mean
     *
     * x and y ≥ 0
     * If x or y = 0, then agm = 0
     * If x or y < 0, then NaN
     *
     * @param  number $x
     * @param  number $y
     * @return float
     */
    public static function arithmeticGeometricMean($x, $y): float
    {
        // x or y < 0 = NaN
        if ($x < 0 || $y < 0) {
            return \NAN;
        }

        // x or y zero = 0
        if ($x == 0 || $y == 0) {
            return 0;
        }

        // Standard case x and y > 0
        list($a, $g) = [$x, $y];
        foreach (range(1, 10) as $_) {
            list($a, $g) = [self::mean([$a, $g]), self::geometricMean([$a, $g])];
        }
        return $a;
    }

    /**
     * Convenience method for arithmeticGeometricMean
     *
     * @param  number $x
     * @param  number $y
     * @return float
     */
    public static function agm($x, $y): float
    {
        return self::arithmeticGeometricMean($x, $y);
    }

    /**
     * Logarithmic mean
     * A function of two non-negative numbers which is equal to their
     * difference divided by the logarithm of their quotient.
     *
     * https://en.wikipedia.org/wiki/Logarithmic_mean
     *
     *  Mlm(x, y) = 0 if x = 0 or y = 0
     *              x if x = y
     *  otherwise:
     *                y - x
     *             -----------
     *             ln y - ln x
     *
     * @param  number $x
     * @param  number $y
     * @return number
     */
    public static function logarithmicMean($x, $y)
    {
        if ($x == 0 || $y == 0) {
            return 0;
        }
        if ($x == $y) {
            return $x;
        }

        return ($y - $x) / (log($y) - log($x));
    }

    /**
     * Heronian mean
     * https://en.wikipedia.org/wiki/Heronian_mean
     *            __
     * H = ⅓(A + √AB + B)
     *
     * @param  number $A
     * @param  number $B
     * @return number
     */
    public static function heronianMean($A, $B)
    {
        return 1/3 * ($A + sqrt($A*$B) + $B);
    }

    /**
     * Identric mean
     * https://en.wikipedia.org/wiki/Identric_mean
     *                 ____
     *          1     / xˣ
     * I(x,y) = - ˣ⁻ʸ/  --
     *          ℯ   √   yʸ
     *
     * @param  number $x
     * @param  number $y
     * @return number
     */
    public static function identricMean($x, $y)
    {
        // x and y must be positive
        if ($x <= 0 || $y <= 0) {
            throw new \Exception('x and y must be positive real numbers.');
        }

        // Special case: x if x = y
        if ($x == $y) {
            return $x;
        }

        // Standard case
        $ℯ  = \M_E;
        $xˣ = $x**$x;
        $yʸ = $y**$y;

        return 1/$ℯ * pow($xˣ/$yʸ, 1/($x - $y));
    }

    /**
     * Get a report of all the averages over a list of numbers
     * Includes mean, median mode, geometric mean, harmonic mean, quardratic mean
     *
     * @param array $numbers
     *
     * @return array [ mean, median, mode, geometric_mean, harmonic_mean,
     *                 contraharmonic_mean, quadratic_mean, trimean, iqm, cubic_mean ]
     */
    public static function describe(array $numbers): array
    {
        return [
            'mean'                => self::mean($numbers),
            'median'              => self::median($numbers),
            'mode'                => self::mode($numbers),
            'geometric_mean'      => self::geometricMean($numbers),
            'harmonic_mean'       => self::harmonicMean($numbers),
            'contraharmonic_mean' => self::contraharmonicMean($numbers),
            'quadratic_mean'      => self::quadraticMean($numbers),
            'trimean'             => self::trimean($numbers),
            'iqm'                 => self::iqm($numbers),
            'cubic_mean'          => self::cubicMean($numbers),
        ];
    }
}
