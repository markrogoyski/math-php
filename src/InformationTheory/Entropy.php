<?php
namespace MathPHP\InformationTheory;

use MathPHP\Functions\Map;
use MathPHP\Exception;

/**
 * Functions dealing with information entropy in the field of statistical field of information thoery.
 *
 * - Bhattacharyya distance
 * - Kullback-Leibler divergence
 *
 * In information theory, entropy is the expected value (average) of the information contained in each message.
 *
 * https://en.wikipedia.org/wiki/Entropy_(information_theory)
 */
class Entropy
{
    /**
     * Bhattacharyya distance
     * Measures the similarity of two discrete or continuous probability distributions.
     * https://en.wikipedia.org/wiki/Bhattacharyya_distance
     *
     * For probability distributions p and q over the same domain X,
     * the Bhattacharyya distance is defined as:
     *
     * DB(p,q) = -ln(BC(p,q))
     *
     * where BC is the Bhattacharyya coefficient:
     *
     * BC(p,q) = ∑ √(p(x) q(x))
     *          x∈X
     *
     * @param array $p distribution p
     * @param array $q distribution q
     *
     * @return float distance between distributions
     *
     * @throws BadDataException if p and q do not have the same number of elements
     * @throws BadDataException if p and q are not probability distributions that add up to 1
     */
    public static function bhattacharyyaDistance(array $p, array $q)
    {
        // Arrays must have the same number of elements
        if (count($p) !== count($q)) {
            throw new Exception\BadDataException('p and q must have the same number of elements');
        }

        // Probability distributions must add up to 1.0
        if ((array_sum($p) != 1) || (array_sum($q) != 1)) {
            throw new Exception\BadDataException('Distributions p anad q must add up to 1');
        }

        $BC⟮p、q⟯ = array_sum(Map\Single::sqrt(Map\Multi::multiply($p, $q)));

        return -log($BC⟮p、q⟯);
    }

    /**
     * Kullback-Leibler divergence
     * (also known as: discrimination information, information divergence, information gain, relative entropy, KLIC, KL divergence)
     * A measure of the difference between two probability distributions P and Q.
     * https://en.wikipedia.org/wiki/Kullback%E2%80%93Leibler_divergence
     *
     *                       P(i)
     * Dkl(P‖Q) = ∑ P(i) log ----
     *            ⁱ          Q(i)
     *
     *
     *
     * @param  array  $p distribution p
     * @param  array  $q distribution q
     *
     * @return float difference between distributions
     *
     * @throws BadDataException if p and q do not have the same number of elements
     * @throws BadDataException if p and q are not probability distributions that add up to 1
     */
    public static function kullbackLeiblerDivergence(array $p, array $q)
    {
        // Arrays must have the same number of elements
        if (count($p) !== count($q)) {
            throw new Exception\BadDataException('p and q must have the same number of elements');
        }

        // Probability distributions must add up to 1.0
        if ((array_sum($p) != 1) || (array_sum($q) != 1)) {
            throw new Exception\BadDataException('Distributions p anad q must add up to 1');
        }

        $Dkl⟮P‖Q⟯ = array_sum(array_map(
            function ($P, $Q) use ($p, $q) {
                return $P * log($P / $Q);
            },
            $p,
            $q
        ));

        return $Dkl⟮P‖Q⟯;
    }
}
