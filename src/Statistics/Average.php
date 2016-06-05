<?php
namespace Math\Statistics;

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
        return pow(array_reduce($numbers, function ($carry, $a) { return !empty($carry) ? $carry * $a : $a; }), 1/$n);
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
        if ( !empty(array_filter( $numbers, function ($x) { return $x < 0; } ))) {
            throw new \Exception('Harmonic mean cannot be computed for negative values.');
        }

        $n = count($numbers);
        return $n / array_sum(array_map( function ($x) { return 1 / $x; }, $numbers ));
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
            function ($x) { return $x**2; },
            $numbers
        ) );
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
     * A function of two non-negative numbers which is equal to their difference divided by the logarithm of their quotient.
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
     * Get a report of all the averages over a list of numbers
     * Includes mean, median mode, geometric mean, harmonic mean, quardratic mean
     *
     * @param array $numbers
     *
     * @return array [ mean, median, mode, geometric_mean, harmonic_mean, quadratic_mean ]
     */
    public static function getAverages(array $numbers): array
    {
        return [
            'mean'           => self::mean($numbers),
            'median'         => self::median($numbers),
            'mode'           => self::mode($numbers),
            'geometric_mean' => self::geometricMean($numbers),
            'harmonic_mean'  => self::harmonicMean($numbers),
            'quadratic_mean' => self::quadraticMean($numbers),
        ];
    }
}
