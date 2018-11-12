<?php
namespace MathPHP\Statistics;

use MathPHP\Functions\Map\Single;
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
 */
class Outlier
{
    public static function GrubbsStatistic(array $data): float
    {
        $μ = Average::mean($data);
        $σ = Descriptive::standardDeviation($data);
            
        return max(Single::abs(Single::subtract($data, $μ))) / $σ;
    }
    
    /**
     * The critical Grubbs Value
     * https://en.wikipedia.org/wiki/Grubbs%27_test_for_outliers
     * https://www.itl.nist.gov/div898/handbook/eda/section3/eda35h1.htm
     *
     * The Critical Gubbs value is used to determine if a value in a set of data is
     * likely to be an outlier.
     */
    public static function CriticalGrubbs($𝛼, $n): float
    {
        $studentT = new StudentT($n - 2);
        $T = $studentT->inverse($𝛼 / $n);
        return ($n - 1) * sqrt($T ** 2 / $n / ($n - 2 + $T ** 2));
    }
}
