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

    /**
     * Cohen's ƒ²
     *
     * One of several effect size measures to use in the context of an F-test
     * for ANOVA or multiple regression. Its amount of bias (overestimation of
     * the effect size for the ANOVA) depends on the bias of its underlying
     * measurement of variance explained (R², η², ω²)
     * https://en.wikipedia.org/wiki/Effect_size
     *
     *        R²
     * ƒ² = ------
     *      1 - R²
     *
     *        η²
     * ƒ² = ------
     *      1 - η²
     *
     *        ω²
     * ƒ² = ------
     *      1 - ω²
     *
     * @param number $measure_of_variance_explained (R², η², ω²)
     *
     * @return number
     */
    public static function cohensF($measure_of_variance_explained)
    {
        return $measure_of_variance_explained / (1 - $measure_of_variance_explained);
    }

    /**
     * Cohen's q
     *
     * The difference between two Fisher transformed Pearson regression coefficients.
     * https://en.wikipedia.org/wiki/Effect_size
     *
     *     1     1 + r₁   1     1 + r₂
     * q = - log ------ - - log ------
     *     2     1 - r₁   2     1 - r₂
     *
     * where r₁ and r₂ are the regressions being compared
     *
     * @param number $r₁
     * @param number $r₂
     *
     * @return number
     */
    public static function cohensQ($r₁, $r₂)
    {
        if ($r₁ >= 1 || $r₂ >= 1) {
            throw new \Exception('r must be less than 0');
        }

        $½ = 0.5;

        return abs(($½ * log((1 + $r₁) / (1 - $r₁))) - ($½ * log((1 + $r₂) / (1 - $r₂))));
    }
}
