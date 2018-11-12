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
 *  - Dixon Q Test
 *  - Chauvenet's criterion
 *  - Peirce's criterion
 *  - Generalized ESD Test
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
        $n = count($data);
        $num = 0;
        if ($tails == "two") {
            $z = Single::abs(Single::subtract($data, $ybar));
            $zbar = Average::mean($z);
            $kthlargest = Average::kthlargest($data, $n - $k);
            $smaller_set = [];
            foreach ($z as $value) {
                if ($value <= $kthlargest) {
                    $smaller_set[] = $value;
                }
            }
            $zkbar = Average::mean($smaller_set);
            $num = array_sum(Single::square(Single::subtract($smaller_set, $zkbar)));
            $den = array_sum(Single::square(Single::subtract($data, $zbar)));
            return $num / $den;
        }
        $den = array_sum(Single::square(Single::subtract($data, $ybar)));
        $smaller_set = [];
        if ($tails == "upper") {
            $kthlargest = Average::kthlargest($data, $n - $k);
            foreach ($z as $value) {
                if ($value <= $kthlargest) {
                    $smaller_set[] = $value;
                }
            }
        }
        if ($tails == "lower") {
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
    
    public function CriticalTietjenMoore(float $𝛼, int $n, int $k)
    {
        $normal = new StandardNormal();
        $CriticalList = [];
        for ($i = 0; $i < 10000; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $data[] = $normal->rand();
            }
            $criticalList[] = TietjenMooreStatistic($data, $k);
        }
        return Average::kthlargest($criticalList, $𝛼 * 10000);
    }
}
