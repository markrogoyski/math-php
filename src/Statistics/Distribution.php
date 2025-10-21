<?php

namespace MathPHP\Statistics;

class Distribution
{
    public const PRINT = true;

    /**
     * Frequency distribution
     * A table that displays the frequency of various outcomes in a sample.
     * Each entry in the table contains the frequency or count of the occurrences of values
     * within a particular group or interval.
     * The table summarizes the distribution of values in the sample.
     * https://en.wikipedia.org/wiki/Frequency_distribution
     *
     * The values of the input array will be the keys of the result array.
     * The count of the values will be the value of the result array for that key.
     *
     * @param array<scalar> $values Ex: ( A, A, A, B, B, C )
     *
     * @return array<scalar, int> frequency distribution Ex: ( A => 3, B => 2, C => 1 )
     */
    public static function frequency(array $values): array
    {
        $frequencies = array();
        foreach ($values as $value) {
            if (!isset($frequencies[$value])) {
                $frequencies[$value] = 1;
            } else {
                $frequencies[$value]++;
            }
        }
        return $frequencies;
    }

    /**
     * Relative frequency distribution
     * Frequency distribution relative to the sample size.
     *
     * Relative Frequency = Frequency / Sample Size
     *
     * The values of the input array will be the keys of the result array.
     * The relative frequency of the values will be the value of the result array for that key.
     *
     * @param array<scalar> $values Ex: ( A, A, A, A, A, A, B, B, B, C )
     *
     * @return array<scalar, float> relative frequency distribution Ex: ( A => 0.6, B => 0.3, C => 0.1 )
     */
    public static function relativeFrequency(array $values): array
    {
        $sample_size          = \count($values);
        $relative_frequencies = array();
        foreach (self::frequency($values) as $subject => $frequency) {
            $relative_frequencies[$subject] = $frequency / $sample_size;
        }
        return $relative_frequencies;
    }

    /**
     * Cumulative frequency distribution
     *
     * The values of the input array will be the keys of the result array.
     * The cumulative frequency of the values will be the value of the result array for that key.
     *
     * @param array<scalar> $values Ex: ( A, A, A, A, A, A, B, B, B, C )
     *
     * @return array<scalar, int> cumulative frequency distribution Ex: ( A => 6, B => 9, C => 10 )
     */
    public static function cumulativeFrequency(array $values): array
    {
        $running_total          = 0;
        $cumulative_frequencies = array();
        foreach (self::frequency($values) as $value => $frequency) {
            $running_total += $frequency;
            $cumulative_frequencies[$value] = $running_total;
        }
        return $cumulative_frequencies;
    }

    /**
     * Cumulative relative frequency distribution
     * Cumulative frequency distribution relative to the sample size.
     *
     * Cumulative relative frequency = cumulative frequency / sample size
     *
     * The values of the input array will be the keys of the result array.
     * The cumulative frequency of the values will be the value of the result array for that key.
     *
     * @param array<scalar> $values Ex: ( A, A, A, A, A, A, B, B, B, C )
     *
     * @return array<scalar, float> cumulative relative frequency distribution Ex: ( A => 0.6, B => 0.9, C => 1 )
     */
    public static function cumulativeRelativeFrequency(array $values): array
    {
        $sample_size            = \count($values);
        $cumulative_frequencies = self::cumulativeFrequency($values);
        return \array_map(
            function ($frequency) use ($sample_size) {
                return $frequency / $sample_size;
            },
            $cumulative_frequencies
        );
    }

    /**
     * Assign a fractional average ranking to data - ("1 2.5 2.5 4" ranking)
     * https://en.wikipedia.org/wiki/Ranking
     *
     * Fractional ranking assigns tied values the average of the ranks they would have
     * received if they had been slightly different. This is also known as "average rank"
     * or "mean rank" method.
     *
     * Algorithm:
     * 1. Track original positions of all input values
     * 2. Sort values in ascending order
     * 3. For each group of tied values (detected using == comparison):
     *    - Calculate the fractional rank as the average of all positions in the tie group
     *    - Assign this fractional rank to all tied values
     * 4. Return ranks in original input order
     *
     * Tie Detection:
     * Uses exact equality (==) for tie detection, matching R and SciPy behavior.
     * Floating-point values that differ by machine epsilon are treated as distinct values.
     *
     * Example 1 - Simple ties:
     *   Input:  [1, 2, 2, 3]
     *   Sorted: [1, 2, 2, 3]
     *   Ranks:  [1, 2.5, 2.5, 4]
     *   Explanation:
     *     - 1 gets rank 1 (position 1)
     *     - Two 2's tie for positions 2 and 3, so each gets (2+3)/2 = 2.5
     *     - 3 gets rank 4 (position 4)
     *
     * Example 2 - Original order preserved:
     *   Input:  [3, 1, 2, 2]
     *   Sorted: [1, 2, 2, 3]
     *   Ranks:  [4, 1, 2.5, 2.5]
     *   Explanation:
     *     - First element (3) is largest → rank 4
     *     - Second element (1) is smallest → rank 1
     *     - Third and fourth elements (2, 2) tie → ranks 2.5, 2.5
     *
     * Example 3 - Multiple tied values:
     *   Input:  [1, 2, 3, 3, 3, 4, 5]
     *   Ranks:  [1, 2, 4, 4, 4, 6, 7]
     *   Explanation:
     *     - Three 3's tie for positions 3, 4, and 5, so each gets (3+4+5)/3 = 4
     *
     * Similar to R: rank(values, ties.method='average')
     * Similar to SciPy: scipy.stats.rankdata(values, method='average')
     *
     * @param array<scalar> $values to be ranked
     *
     * @return array<float> Rankings of the data in the same order the values were input
     */
    public static function fractionalRanking(array $values): array
    {
        // Create array of [original_index => value] to track original positions
        $indexed_values = [];
        foreach ($values as $index => $value) {
            $indexed_values[] = ['index' => $index, 'value' => $value];
        }

        // Sort by value
        \usort($indexed_values, function ($a, $b) {
            return $a['value'] <=> $b['value'];
        });

        // Assign ranks with exact equality for tie detection
        $ranks = [];
        $n = \count($indexed_values);

        for ($i = 0; $i < $n; $i++) {
            // Find all values that exactly equal current value
            $tie_indices = [$i];
            $current_value = $indexed_values[$i]['value'];

            // Look ahead for exact ties
            for ($j = $i + 1; $j < $n; $j++) {
                if ($current_value == $indexed_values[$j]['value']) {
                    $tie_indices[] = $j;
                } else {
                    break; // Values are sorted, so no more ties possible
                }
            }

            // Calculate fractional rank (average of all tied positions)
            // Ranks are 1-indexed, so position i has rank i+1
            $rank_sum = 0;
            foreach ($tie_indices as $idx) {
                $rank_sum += $idx + 1;
            }
            $fractional_rank = $rank_sum / \count($tie_indices);

            // Assign same rank to all tied values
            foreach ($tie_indices as $idx) {
                $ranks[$indexed_values[$idx]['index']] = $fractional_rank;
            }

            // Skip past all tied values
            $i = $tie_indices[\count($tie_indices) - 1];
        }

        // Sort ranks by original index to return in original order
        \ksort($ranks);

        return \array_values($ranks);
    }

    /**
     * Assign a standard competitive ranking to data - ("1224" ranking)
     * https://en.wikipedia.org/wiki/Ranking
     *
     * Similar to R: rank(values, ties.method='min')
     *
     * @param array<scalar> $values to be ranked
     *
     * @return array<int> Rankings of the data in the same order the values were input
     */
    public static function standardCompetitionRanking(array $values): array
    {
        $count = \count($values);
        $Xs    = $values;
        \sort($Xs);

        $ranking⟮X⟯    = [];
        $ranking⟮X⟯[0] = 1;
        for ($i = 1; $i < $count; $i++) {
            $ranking⟮X⟯[$i] = $Xs[$i] == $Xs[$i - 1]
                ? $ranking⟮X⟯[$i - 1]
                : $i + 1;
        }

        /** @var array<string, int<1, max>> $ranking⟮X⟯ */
        $ranking⟮X⟯ = \array_combine(\array_map('\strval', $Xs), $ranking⟮X⟯);

        // Map ranks to values in order they were originally input
        return \array_map(
            function ($value) use ($ranking⟮X⟯) {
                return $ranking⟮X⟯[\strval($value)];
            },
            $values
        );
    }

    /**
     * Assign a modified competitive ranking to data - ("1334" ranking)
     * https://en.wikipedia.org/wiki/Ranking
     *
     * Similar to R: rank(values, ties.method='max')
     *
     * @param array<scalar> $values to be ranked
     *
     * @return array<int> Rankings of the data in the same order the values were input
     */
    public static function modifiedCompetitionRanking(array $values): array
    {
        $count = \count($values);
        $Xs    = $values;
        \sort($Xs);

        $ranking⟮X⟯            = [];
        $ranking⟮X⟯[$count - 1] = $count;
        for ($i = $count - 2; $i >= 0; $i--) {
            $ranking⟮X⟯[$i] = $Xs[$i] == $Xs[$i + 1]
                ? $ranking⟮X⟯[$i + 1]
                : $i + 1;
        }
        \sort($ranking⟮X⟯);

        /** @var array<string, int<0, max>> $ranking⟮X⟯ */
        $ranking⟮X⟯ = \array_combine(\array_map('\strval', $Xs), $ranking⟮X⟯);

        // Map ranks to values in order they were originally input
        return \array_map(
            function ($value) use ($ranking⟮X⟯) {
                return $ranking⟮X⟯[\strval($value)];
            },
            $values
        );
    }

    /**
     * Assign an ordinal ranking to data - ("1234" ranking)
     * https://en.wikipedia.org/wiki/Ranking
     *
     * Similar to R: rank(values, ties.method='first')
     *
     * @param array<scalar> $values to be ranked
     *
     * @return array<int> Rankings of the data in the same order the values were input
     */
    public static function ordinalRanking(array $values): array
    {
        $Xs = $values;
        \sort($Xs);

        $ranking⟮X⟯ = [];
        foreach ($Xs as $i => $x) {
            $ranking⟮X⟯[\strval($x)][] = $i + 1;
        }

        // Map ranks to values in order they were originally input
        $rankedValues = [];
        foreach ($values as $value) {
            $rankedValues[] = \array_shift($ranking⟮X⟯[\strval($value)]);
        }
        return $rankedValues;
    }

    /**
     * Stem and leaf plot
     * Device for presenting quantitative data in a graphical format, similar to a histogram,
     * to assist in visualizing the shape of a distribution.
     * https://en.wikipedia.org/wiki/Stem-and-leaf_display
     *
     * Returns an array with the keys as the stems, and the values are arrays containing the leaves.
     *
     * Optional parameter to print the stem and leaf plot.
     * Given input array: [ 44 46 47 49 63 64 66 68 68 72 72 75 76 81 84 88 106 ]
     * Prints:
     *   4 | 4 6 7 9
     *   5 |
     *   6 | 3 4 6 8 8
     *   7 | 2 2 5 6
     *   8 | 1 4 8
     *   9 |
     *  10 | 6
     *
     * @param array<int> $values
     * @param bool  $print  Optional setting to print the distribution
     *
     * @return array<int, array<int>> keys are the stems, values are the leaves
     */
    public static function stemAndLeafPlot(array $values, bool $print = false): array
    {
        // Split each value into stem and leaf
        \sort($values);
        $plot = array();
        foreach ($values as $value) {
            $stem = intdiv($value, 10);
            $leaf = $value % 10;
            if (!isset($plot[$stem])) {
                $plot[$stem] = array();
            }
            $plot[$stem][] = $leaf;
        }

        // Fill in any empty keys in the distribution we had no stem/leaves for
        $min = \min(\array_keys($plot));
        $max = \max(\array_keys($plot));
        for ($stem = $min; $stem <= $max; $stem++) {
            if (!isset($plot[$stem])) {
                $plot[$stem] = array();
            }
        }
        \ksort($plot);

        // Optionally print the stem and leaf plot
        if ($print === true) {
            $length = \max(\array_map(function ($stem) {
                return \strlen((string)$stem);
            }, \array_keys($plot)));
            foreach ($plot as $stem => $leaves) {
                \printf("%{$length}d | %s\n", $stem, \implode(' ', $leaves));
            }
        }

        return $plot;
    }
}
