<?php
namespace MathPHP\Statistics;

/**
 * Effect size is a quantitative measure of the strength of a phenomenon.
 * https://en.wikipedia.org/wiki/Effect_size
 */
class EffectSize
{
    /**
     * η² (Eta-squared)
     *
     * Eta-squared describes the ratio of variance explained in the dependent
     * variable by a predictor while controlling for other predictors, making
     * it analogous to the r².
     *
     *      SSB
     * η² = ---
     *      SST
     *
     * where:
     *  SSB = sum of squares between (treatment)
     *  SST = sum of squares total
     *
     * @param  number $SSB Sum of squares between (treatment)
     * @param  number $SST Sum of squares total
     *
     * @return number
     */
    public static function etaSquared($SSB, $SST)
    {
        return $SSB / $SST;
    }
}
