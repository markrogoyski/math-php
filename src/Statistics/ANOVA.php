<?php
namespace Math\Statistics;

use Math\Statistics\Average;
use Math\Statistics\Descriptive;
use Math\Statistics\RandomVariable;
use Math\Probability\Distribution\Continuous\F;

/**
 * ANOVA (Analysis of Variance)
 */
class ANOVA
{
    /**
     * One-way ANOVA
     * Technique used to compare means of three or more samples
     * (using the F distribution).
     * https://en.wikipedia.org/wiki/One-way_analysis_of_variance
     *
     * Produces the following analysis of the data:
     *
     * ANOVA hypothesis test summary data
     *
     *           | SS | df | MS | F | P |
     * Treatment |    |    |    |   |   |
     * Error     |    |    |    |
     * Total     |    |    |
     *
     *  where:
     *   Treament is between groups
     *   Error is within groups
     *   SS = Sum of squares
     *   df = Degrees of freedom
     *   MS = Mean squares
     *   F  = F statistic
     *   P  = P value
     *
     * Data summary table
     *
     *       | N | Sum | Mean | SS | Variance | SD | SEM |
     * 0     |   |     |      |    |          |    |     |
     * 1     |   |     |      |    |          |    |     |
     * ...   |   |     |      |    |          |    |     |
     * Total |   |     |      |    |          |    |     |
     *
     *  where:
     *   Each row is the summary for a sample, numbered from 0 to m - 1
     *   m   = Number of samples
     *   N   = Sample size
     *   SS  = Sum of squares
     *   SD  = Standard deviation
     *   SEM = Standard error of the mean
     *
     * Calculations
     *
     * Sum of Squares
     * SST (sum of squares total)
     * ∑⟮xᵢ − μ⟯²
     *  where:
     *   xᵢ = each element of all samples
     *   μ  = mean total of all elements of all samples
     *
     * SSB (sum of squares between - treatment)
     * ∑n(x - μ)²
     *  where:
     *   n = sample size
     *   x = sample mean
     *   μ  = mean total of all elements of all samples
     *
     * SSW (sum of squares within - error)
     * ∑∑⟮xᵢ − μ⟯²  Sum of sum of squared deviations of each sample
     *  where:
     *   xᵢ = each element of the sample
     *   μ  = mean of the sample
     *
     * Degrees of Freedom
     * dfT (degrees of freedom for the total)
     * mn - 1
     *
     * dfB (degrees of freedom between - treatment)
     * m - 1
     *
     * dfW (degrees of freedom within - error)
     * m(n - 1)
     *
     *  where:
     *   m = number of samples
     *   n = number of elements in each sample
     *
     * Mean Squares
     * MSB (Mean squares between - treatment)
     * SSB / dfB
     *
     * MSW (Mean squares within - error)
     * SSW / dfW
     *
     * Test Statistics
     * F = MSB / MSW
     * P = F distribution CDF above F with degrees of freedom dfB and df W
     *
     * @param  array ...$samples Samples to analyze (at least 3 or more samples)
     *
     * @return array [
     *                 ANOVA => [
     *                   treatment => [SS, df, MS, F, P],
     *                   error     => [SS, df, MS],
     *                   total     => [SS, df],
     *                 ],
     *                 total_summary => [n, sum, mean, SS, variance, sd, sem],
     *                 data_summary  => [
     *                   0     => [n, sum, mean, SS, variance, sd, sem],
     *                   1     => [n, sum, mean, SS, variance, sd, sem],
     *                   ...
     *                 ]
     *               ]
     */
    public static function oneWay(array ...$samples)
    {
        // Must have at least three samples
        $m = count($samples);
        if ($m < 3) {
            throw new \Exception('Must have at least three samples');
        }

        // All samples must have the same number of items
        $n = count($samples[0]);
        for ($i = 1; $i < $m; $i++) {
            if (count($samples[$i]) !== $n) {
                throw new \Exception('All samples must have the same number of values');
            }
        }

        // Summary data for each sample
        $summary_data = [];
        foreach ($samples as $i => $sample) {
            $summary_data[$i]             = [];
            $summary_data[$i]['n']        = $n;
            $summary_data[$i]['sum']      = array_sum($sample);
            $summary_data[$i]['mean']     = Average::mean($sample);
            $summary_data[$i]['SS']       = RandomVariable::sumOfSquares($sample);
            $summary_data[$i]['variance'] = Descriptive::sampleVariance($sample);
            $summary_data[$i]['sd']       = Descriptive::sd($sample);
            $summary_data[$i]['sem']      = RandomVariable::standardErrorOfTheMean($sample);
        }

        // Totals summary
        $all_elements = array_reduce(
            $samples,
            function ($merged, $sample) {
                return array_merge($merged, $sample);
            },
            array()
        );
        $μ     = Average::mean($all_elements);
        $total = [
            'n'        => count($all_elements),
            'sum'      => array_sum($all_elements),
            'mean'     => $μ,
            'SS'       => RandomVariable::sumOfSquares($all_elements),
            'variance' => Descriptive::sampleVariance($all_elements),
            'sd'       => Descriptive::sd($all_elements),
            'sem'      => RandomVariable::standardErrorOfTheMean($all_elements),
        ];

        // ANOVA sum of squares
        $SST = RandomVariable::sumOfSquaresDeviations($all_elements);
        $SSB = array_sum(array_map(
            function ($sample) use ($n, $μ) {
                return $n * (Average::mean($sample) - $μ)**2;
            },
            $samples
        ));
        $SSW = array_sum(array_map(
            'Math\Statistics\RandomVariable::sumOfSquaresDeviations',
            $samples
        ));

        // ANOVA degrees of freedom
        $dfT = $m * $n - 1;
        $dfB = $m - 1;
        $dfW = $m * ($n - 1);

        // ANOVA mean squares
        $MSB = $SSB / $dfB;
        $MSW = $SSW / $dfW;

        // Test statistics
        $F = $MSB / $MSW;
        $P = F::above($F, $dfB, $dfW);

        // Return ANOVA report
        return [
            'ANOVA' => [
                'treatment' => [
                    'SS' => $SSB,
                    'df' => $dfB,
                    'MS' => $MSB,
                    'F'  => $F,
                    'P'  => $P,
                ],
                'error' => [
                    'SS' => $SSW,
                    'df' => $dfW,
                    'MS' => $MSW,
                ],
                'total' => [
                    'SS' => $SST,
                    'df' => $dfT,
                ],
            ],
            'total_summary' => $total,
            'data_summary'  => $summary_data,
        ];
    }
}
