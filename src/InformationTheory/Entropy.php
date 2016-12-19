<?php
namespace MathPHP\InformationTheory;

use MathPHP\Functions\Map;
use MathPHP\Exception;

/**
 * Functions dealing with information entropy in the field of statistical field of information thoery.
 *
 * - Entropy:
 *   - Shannon entropy (bits)
 *   - Shannon entropy (nats)
 *   - Shannon entropy (harts)
 *   - Cross entropy
 * - Distances and divergences
 *   - Bhattacharyya distance
 *   - Kullback-Leibler divergence
 *   - Hellinger distance
 *   - Jensen-Shannon divergence
 *
 * In information theory, entropy is the expected value (average) of the information contained in each message.
 *
 * https://en.wikipedia.org/wiki/Entropy_(information_theory)
 */
class Entropy
{
    const ONE_TOLERANCE = 0.010001;

    /**
     * Shannon entropy (bit entropy)
     * The average minimum number of bits needed to encode a string of symbols, based on the probability of the symbols.
     * https://en.wikipedia.org/wiki/Entropy_(information_theory)
     *
     * H = -∑ pᵢlog₂(pᵢ)
     *
     * H is in shannons, or bits.
     *
     * @param  array $p probability distribution
     *
     * @return float average minimum number of bits
     *
     * @throws BadDataException if probability distribution p does not add up to 1
     */
    public static function shannonEntropy(array $p)
    {
        // Probability distribution must add up to 1.0
        if (abs(array_sum($p) - 1) > self::ONE_TOLERANCE) {
            throw new Exception\BadDataException('Probability distribution p must add up to 1; p adds up to: ' . array_sum($p));
        }

        // Defensive measure against taking the log of 0 which would be -∞
        $p = array_map(
            function ($pᵢ) {
                return $pᵢ == 0 ? 1e-15 : $pᵢ;
            },
            $p
        );

        // ∑ pᵢlog₂(pᵢ)
        $∑pᵢlog₂⟮pᵢ⟯ = array_sum(array_map(
            function ($pᵢ) {
                return $pᵢ * log($pᵢ, 2);
            },
            $p
        ));

        return -$∑pᵢlog₂⟮pᵢ⟯;
    }

    /**
     * Shannon nat entropy (nat entropy)
     * The average minimum number of nats needed to encode a string of symbols, based on the probability of the symbols.
     * https://en.wikipedia.org/wiki/Entropy_(information_theory)
     *
     * H = -∑ pᵢln(pᵢ)
     *
     * H is in units of nats.
     * 1 nat = 1/ln(2) shannons or bits.
     * https://en.wikipedia.org/wiki/Nat_(unit)
     *
     * @param  array $p probability distribution
     *
     * @return float average minimum number of nats
     *
     * @throws BadDataException if probability distribution p does not add up to 1
     */
    public static function shannonNatEntropy(array $p)
    {
        // Probability distribution must add up to 1.0
        if (abs(array_sum($p) - 1) > self::ONE_TOLERANCE) {
            throw new Exception\BadDataException('Probability distribution p must add up to 1; p adds up to: ' . array_sum($p));
        }

        // Defensive measure against taking the log of 0 which would be -∞
        $p = array_map(
            function ($pᵢ) {
                return $pᵢ == 0 ? 1e-15 : $pᵢ;
            },
            $p
        );

        // ∑ pᵢln(pᵢ)
        $∑pᵢln⟮pᵢ⟯ = array_sum(array_map(
            function ($pᵢ) {
                return $pᵢ * log($pᵢ);
            },
            $p
        ));

        return -$∑pᵢln⟮pᵢ⟯;
    }

    /**
     * Shannon hartley entropy (hartley entropy)
     * The average minimum number of hartleys needed to encode a string of symbols, based on the probability of the symbols.
     * https://en.wikipedia.org/wiki/Entropy_(information_theory)
     *
     * H = -∑ pᵢlog₁₀(pᵢ)
     *
     * H is in units of hartleys, or harts.
     * 1 hartley = log₂(10) bit = ln(10) nat, or approximately 3.322 Sh, or 2.303 nat.
     * https://en.wikipedia.org/wiki/Hartley_(unit)
     *
     * @param  array $p probability distribution
     *
     * @return float average minimum number of hartleys
     *
     * @throws BadDataException if probability distribution p does not add up to 1
     */
    public static function shannonHartleyEntropy(array $p)
    {
        // Probability distribution must add up to 1.0
        if (abs(array_sum($p) - 1) > self::ONE_TOLERANCE) {
            throw new Exception\BadDataException('Probability distribution p must add up to 1; p adds up to: ' . array_sum($p));
        }

        // Defensive measure against taking the log of 0 which would be -∞
        $p = array_map(
            function ($pᵢ) {
                return $pᵢ == 0 ? 1e-15 : $pᵢ;
            },
            $p
        );

        // ∑ pᵢlog₁₀(pᵢ)
        $∑pᵢlog₁₀⟮pᵢ⟯ = array_sum(array_map(
            function ($pᵢ) {
                return $pᵢ * log10($pᵢ);
            },
            $p
        ));

        return -$∑pᵢlog₁₀⟮pᵢ⟯;
    }

    /**
     * Cross entropy
     * The cross entropy between two probability distributions p and q over the same underlying set of events
     * measures the average number of bits needed to identify an event drawn from the set, if a coding scheme
     * is used that is optimized for an "unnatural" probability distribution q, rather than the "true" distribution p.
     * https://en.wikipedia.org/wiki/Cross_entropy
     *
     * H(p,q) = -∑ p(x) log₂ q(x)
     *
     * @param array $p distribution p
     * @param array $q distribution q
     *
     * @return float entropy between distributions
     *
     * @throws BadDataException if p and q do not have the same number of elements
     * @throws BadDataException if p and q are not probability distributions that add up to 1
     */
    public static function crossEntropy(array $p, array $q)
    {
        // Arrays must have the same number of elements
        if (count($p) !== count($q)) {
            throw new Exception\BadDataException('p and q must have the same number of elements');
        }

        // Probability distributions must add up to 1.0
        if ((abs(array_sum($p) - 1) > self::ONE_TOLERANCE) || (abs(array_sum($q) - 1) > self::ONE_TOLERANCE)) {
            throw new Exception\BadDataException('Distributions p and q must add up to 1');
        }

        // Defensive measure against taking the log of 0 which would be -∞
        $q = array_map(
            function ($qᵢ) {
                return $qᵢ == 0 ? 1e-15 : $qᵢ;
            },
            $q
        );

        // ∑ p(x) log₂ q(x)
        $∑plog₂⟮q⟯ = array_sum(array_map(
            function ($pᵢ, $qᵢ) {
                return $pᵢ * log($qᵢ, 2);
            },
            $p,
            $q
        ));

        return -$∑plog₂⟮q⟯;
    }

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
        if ((abs(array_sum($p) - 1) > self::ONE_TOLERANCE) || (abs(array_sum($q) - 1) > self::ONE_TOLERANCE)) {
            throw new Exception\BadDataException('Distributions p and q must add up to 1');
        }

        // ∑ √(p(x) q(x))
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
        if ((abs(array_sum($p) - 1) > self::ONE_TOLERANCE) || (abs(array_sum($q) - 1) > self::ONE_TOLERANCE)) {
            throw new Exception\BadDataException('Distributions p and q must add up to 1');
        }

        // Defensive measures against taking the log of 0 which would be -∞ or dividing by 0
        $p = array_map(
            function ($pᵢ) {
                return $pᵢ == 0 ? 1e-15 : $pᵢ;
            },
            $p
        );
        $q = array_map(
            function ($qᵢ) {
                return $qᵢ == 0 ? 1e-15 : $qᵢ;
            },
            $q
        );

        // ∑ P(i) log(P(i)/Q(i))
        $Dkl⟮P‖Q⟯ = array_sum(array_map(
            function ($P, $Q) use ($p, $q) {
                return $P * log($P / $Q);
            },
            $p,
            $q
        ));

        return $Dkl⟮P‖Q⟯;
    }

    /**
     * Hellinger distance
     * Used to quantify the similarity between two probability distributions. It is a type of f-divergence.
     * https://en.wikipedia.org/wiki/Hellinger_distance
     *
     *          1   _______________
     * H(P,Q) = -- √ ∑ (√pᵢ - √qᵢ)²
     *          √2
     *
     * @param array $p distribution p
     * @param array $q distribution q
     *
     * @return float difference between distributions
     *
     * @throws BadDataException if p and q do not have the same number of elements
     * @throws BadDataException if p and q are not probability distributions that add up to 1
     */
    public static function hellingerDistance(array $p, array $q)
    {
        // Arrays must have the same number of elements
        if (count($p) !== count($q)) {
            throw new Exception\BadDataException('p and q must have the same number of elements');
        }

        // Probability distributions must add up to 1.0
        if ((abs(array_sum($p) - 1) > self::ONE_TOLERANCE) || (abs(array_sum($q) - 1) > self::ONE_TOLERANCE)) {
            throw new Exception\BadDataException('Distributions p and q must add up to 1');
        }

        // Defensive measures against taking the log of 0 which would be -∞ or dividing by 0
        $p = array_map(
            function ($pᵢ) {
                return $pᵢ == 0 ? 1e-15 : $pᵢ;
            },
            $p
        );
        $q = array_map(
            function ($qᵢ) {
                return $qᵢ == 0 ? 1e-15 : $qᵢ;
            },
            $q
        );

        // √ ∑ (√pᵢ - √qᵢ)²
        $√∑⟮√pᵢ − √qᵢ⟯² = sqrt(array_sum(array_map(
            function ($pᵢ, $qᵢ) {
                return (sqrt($pᵢ) - sqrt($qᵢ))**2;
            },
            $p,
            $q
        )));

        return (1 / sqrt(2)) * $√∑⟮√pᵢ − √qᵢ⟯²;
    }

    /**
     * Jensen-Shannon divergence
     * Also known as: information radius (IRad) or total divergence to the average.
     * A method of measuring the similarity between two probability distributions.
     * It is based on the Kullback–Leibler divergence, with some notable (and useful) differences,
     * including that it is symmetric and it is always a finite value.
     * https://en.wikipedia.org/wiki/Jensen%E2%80%93Shannon_divergence
     *
     *            1          1
     * JSD(P‖Q) = - D(P‖M) + - D(Q‖M)
     *            2          2
     *
     *           1
     * where M = - (P + Q)
     *           2
     *
     *       D(P‖Q) = Kullback-Leibler divergence
     *
     * @param array $p distribution p
     * @param array $q distribution q
     *
     * @return float difference between distributions
     *
     * @throws BadDataException if p and q do not have the same number of elements
     * @throws BadDataException if p and q are not probability distributions that add up to 1
     */
    public static function jensenShannonDivergence(array $p, array $q)
    {
        // Arrays must have the same number of elements
        if (count($p) !== count($q)) {
            throw new Exception\BadDataException('p and q must have the same number of elements');
        }

        // Probability distributions must add up to 1.0
        if ((abs(array_sum($p) - 1) > self::ONE_TOLERANCE) || (abs(array_sum($q) - 1) > self::ONE_TOLERANCE)) {
            throw new Exception\BadDataException('Distributions p and q must add up to 1');
        }

        $M = array_map(
            function ($pᵢ, $qᵢ) {
                return ($pᵢ + $qᵢ) / 2;
            },
            $p,
            $q
        );

        $½D⟮P‖M⟯ = self::kullbackLeiblerDivergence($p, $M) / 2;
        $½D⟮Q‖M⟯ = self::kullbackLeiblerDivergence($q, $M) / 2;

        return $½D⟮P‖M⟯ + $½D⟮Q‖M⟯;
    }
}
