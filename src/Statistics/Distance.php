<?php
namespace MathPHP\Statistics;

use MathPHP\Functions\Map;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\Exception;

/**
 * Functions dealing with statistical distance and divergence.
 * Related to probability and information theory and entropy.
 *
 * - Distances
 *   - Bhattacharyya distance
 *   - Hellinger distance
 *   - Mahalanobis distance
 * - Divergences
 *   - Kullback-Leibler divergence
 *   - Jensen-Shannon divergence
 *
 * In statistics, probability theory, and information theory, a statistical distance quantifies the distance between
 * two statistical objects, which can be two random variables, or two probability distributions or samples, or the
 * distance can be between an individual sample point and a population or a wider sample of points.
 *
 * In statistics and information geometry, divergence or a contrast function is a function which establishes the "distance"
 * of one probability distribution to the other on a statistical manifold. The divergence is a weaker notion than that of
 * the distance, in particular the divergence need not be symmetric (that is, in general the divergence from p to q is not
 * equal to the divergence from q to p), and need not satisfy the triangle inequality.
 *
 * https://en.wikipedia.org/wiki/Statistical_distance
 * https://en.wikipedia.org/wiki/Divergence_(statistics)
 */
class Distance
{
    const ONE_TOLERANCE = 0.010001;

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
     * @throws Exception\BadDataException if p and q do not have the same number of elements
     * @throws Exception\BadDataException if p and q are not probability distributions that add up to 1
     */
    public static function bhattacharyyaDistance(array $p, array $q): float
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
     * @throws Exception\BadDataException if p and q do not have the same number of elements
     * @throws Exception\BadDataException if p and q are not probability distributions that add up to 1
     */
    public static function kullbackLeiblerDivergence(array $p, array $q): float
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
            function ($P, $Q) {
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
     * @throws Exception\BadDataException if p and q do not have the same number of elements
     * @throws Exception\BadDataException if p and q are not probability distributions that add up to 1
     */
    public static function hellingerDistance(array $p, array $q): float
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
     * @throws Exception\BadDataException if p and q do not have the same number of elements
     * @throws Exception\BadDataException if p and q are not probability distributions that add up to 1
     */
    public static function jensenShannonDivergence(array $p, array $q): float
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

    /**
     * Mahalanobis distance
     *
     * https://en.wikipedia.org/wiki/Mahalanobis_distance
     *
     * The Mahalanobis distance measures the distance between two points in multidimensional
     * space, scaled by the standard deviation of the data in each dimension.
     *
     * If x and y are vectors of points in space, and S is the covariance matrix of that space,
     * the Mahalanobis distance, D, of the point within the space is:
     *
     *    D = √[(x-y)ᵀ S⁻¹ (x-y)]
     *
     * If y is not provided, the distances will be calculated from x to the centroid of the dataset.
     *
     * The Mahalanobis distance can also be used to measure the distance between two sets of data.
     * If x has more than one column, the combined data covariance matrix is used, and the distance
     * will be calculated between the centroids of each data set.
     *
     * @param Matrix      $x    a vector in the vector space. ie [[1],[2],[4]] or a matrix of data
     * @param Matrix      $data an array of data. i.e. [[1,2,3,4],[6,2,8,1],[0,4,8,1]]
     * @param Matrix|null $y    a vector in the vector space
     *
     * @return float Mahalanobis Distance
     *
     * @throws Exception\BadParameterException
     * @throws Exception\IncorrectTypeException
     * @throws Exception\MatrixException
     * @throws Exception\OutOfBoundsException
     * @throws Exception\VectorException
     */
    public static function Mahalanobis(Matrix $x, Matrix $data, Matrix $y = null): float
    {
        $Centroid = $data->sampleMean()->asColumnMatrix();
        $Nx       = $x->getN();

        if ($Nx > 1) {
            // Combined covariance Matrix
            $S = $data->augment($x)->covarianceMatrix();
            $diff = $x->sampleMean()->asColumnMatrix()->subtract($Centroid);
        } else {
            $S = $data->covarianceMatrix();
            if ($y === null) {
                $y = $Centroid;
            }
            $diff = $x->subtract($y);
        }

        $S⁻¹ = $S->inverse();
        $D   = $diff->transpose()->multiply($S⁻¹)->multiply($diff);
        return sqrt($D[0][0]);
    }
}
