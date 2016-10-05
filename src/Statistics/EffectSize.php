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
     * https://en.wikipedia.org/wiki/Effect_size
     *
     *      SSt
     * η² = ---
     *      SST
     *
     * where:
     *  SSt = sum of squares treatment
     *  SST = sum of squares total
     *
     * @param  number $SSt Sum of squares treatment
     * @param  number $SST Sum of squares total
     *
     * @return number
     */
    public static function etaSquared($SSt, $SST)
    {
        return $SSt / $SST;
    }

    /**
     * η²p (Partial eta-squared)
     *
     * https://en.wikipedia.org/wiki/Effect_size
     *
     *          SSt
     * η²p = ---------
     *       SSt + SSE
     *
     * where:
     *  SSt = sum of squares treatment
     *  SSE = sum of squares error
     *
     * @param  number $SSB Sum of squares treatment
     * @param  number $SSE Sum of squares error
     *
     * @return number
     */
    public static function partialEtaSquared($SSt, $SSE)
    {
        return $SSt / ($SSt + $SSE);
    }


    /**
     * ω² (omega-squared)
     *
     * A less biased estimator of the variance explained in the population.
     * https://en.wikipedia.org/wiki/Effect_size
     *
     *      SSt - dft * MSE
     * ω² = ---------------
     *         SST + MSE
     *
     * where:
     *  SSt = sum of squares treatment
     *  SST = sum of squares total
     *  dft = degrees of freedom treatment
     *  MSE = Mean squares error
     *
     * @param number $SSt Sum of squares treatment
     * @param number $dft Degrees of freedom treatment
     * @param number $SST Sum of squares total
     * @param number $MSE Mean squares error
     *
     * @return number
     */
    public static function omegaSquared($SSt, $dft, $SST, $MSE)
    {
        return ($SSt - $dft * $MSE) / ($SST + $MSE);
    }
}
