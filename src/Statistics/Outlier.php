<?php
namespace MathPHP\Statistics;

use MathPHP\Exception;
use MathPHP\Functions\Map\Single;
use MathPHP\Probability\Distribution\Continuous\StandardNormal;
use MathPHP\Probability\Distribution\Continuous\StudentT;
use MathPHP\Statistics\Average;
use MathPHP\Statistics\Descriptive;

/**
 * Tests for outliers in data
 *  - Grubbs Test
 *  - Tietjen-Moore Test
 */
class Outlier
{
    /**
     * The Grubbs Statistic (G) of a series of data
     *
     * G is the largest z-score for a set of data
     * The statistic can be calculated, looking at only the maximum value ("upper")
     * the minimum value ("lower"), or the data point with the largest residual ("two")
     *
     * @param array $data
     * @param $tails ("upper" "lower", or "two")
     *
     * @return float
     */
    public static function GrubbsStatistic(array $data, string $tails = "two"): float
    {
        $tails = $tails != "upper" && $tails != "lower" && $tails != "two" ? "two" : $tails;
        $μ = Average::mean($data);
        $σ = Descriptive::standardDeviation($data);
        
        $difference = Single::multiply(Single::subtract($data, $μ), ($tails == "upper" ? 1 : -1));
        $max = $tails == "two" ? max(Single::abs($difference)) : max($difference);
        return $max / $σ;
    }
    
    /**
     * The critical Grubbs Value
     * https://en.wikipedia.org/wiki/Grubbs%27_test_for_outliers
     * https://www.itl.nist.gov/div898/handbook/eda/section3/eda35h1.htm
     *
     * The Critical Gubbs value is used to determine if a value in a set of data is
     * likely to be an outlier.
     *
     * @param float $𝛼 Significance Level
     * @param int $n Size of the data set
     * @param int $tails (1 or 2) one or two-tailed test
     *
     * @return float
     */
    public static function CriticalGrubbs(float $𝛼, int $n, int $tails = 2): float
    {
        if ($tails < 1 || $tails > 2) {
            throw new Exception\BadParameterException('Tails must be 1 or 2');
        }
        $studentT = new StudentT($n - 2);
        $T = $studentT->inverse($𝛼 / $n / $tails);
        return ($n - 1) * sqrt($T ** 2 / $n / ($n - 2 + $T ** 2));
    }
}
