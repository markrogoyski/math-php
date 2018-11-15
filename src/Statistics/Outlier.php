<?php
namespace MathPHP\Statistics;

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
        if ($tails != "upper" && $tails != "lower" && $tails != "two") {
            //throw exception;
        }
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
            //throw Exception;
        }
        $studentT = new StudentT($n - 2);
        $T = $studentT->inverse($𝛼 / $n / $tails);
        return ($n - 1) * sqrt($T ** 2 / $n / ($n - 2 + $T ** 2));
    }

    /**
     * The Tietjen-Moore Statistic of a set of data
     *
     * https://www.itl.nist.gov/div898/handbook/eda/section3/eda35h2.htm
     *
     * @param array $data
     * @param int $k The number of outliers to test for
     * @param $tails ("upper" "lower", or "two")
     *
     * @return float
     */
    public static function TietjenMooreStatistic(array $data, int $k, string $tails = "lower"): float
    {
        $ybar = Average::mean($data);
        $den = array_sum(Single::square(Single::subtract($data, $zbar)));
        $n = count($data);
        $num = 0;
        $smaller_set = [];
        if ($tails == "two") {
            $z = Single::abs(Single::subtract($data, $ybar));
            $kthlargest = Average::kthlargest($z, $n - $k);
            foreach ($data as $value) {
                if (abs($value - $ybar) <= $kthlargest) {
                    $smaller_set[] = $value;
                }
            }
        } elseif ($tails == "upper") {
            $kthlargest = Average::kthlargest($data, $n - $k);
            foreach ($z as $value) {
                if ($value <= $kthlargest) {
                    $smaller_set[] = $value;
                }
            }
        } elseif ($tails == "lower") {
            $kthlargest = Average::kthlargest($data, $k + 1);
            foreach ($z as $value) {
                if ($value >= $kthlargest) {
                    $smaller_set[] = $value;
                }
            }
        }
        $ykbar = Average::mean($smaller_set);
        $num = array_sum(Single::square(Single::subtract($smaller_set, $ykbar)));
        return $num / $den;
    }

    /**
     * The critical Tietjen-Moore Value
     *
     * The Critical Tietjen-Moore value is used to determine if multiple values in a set of data are
     * likely to be outliers.
     *
     * The critical value is determined by simulation.
     *
     * @param float $𝛼 Significance Level
     * @param int $n Size of the data set
     * @param int $k The number of outliers to test for
     *
     * @return float
     */
    public function CriticalTietjenMoore(float $𝛼, int $n, int $k, string $tails = "lower")
    {
        $number_of_simulations = 100000;
        $normal = new StandardNormal();
        $CriticalList = [];
        for ($i = 0; $i < $number_of_simulations; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $data[] = $normal->rand();
            }
            $criticalList[] = TietjenMooreStatistic($data, $k, $tails);
        }
        return Average::kthlargest($criticalList, $𝛼 * $number_of_simulations);
    }
}
