<?php
namespace MathPHP\Probability\Distribution\Multivariate;

use MathPHP\Probability\Distribution\Continuous\Continuous;
use MathPHP\Functions\Map;
use MathPHP\Functions\Special;
use MathPHP\Functions\Support;
use MathPHP\Exception;

/**
 * Dirichlet distribution
 * https://en.wikipedia.org/wiki/Dirichlet_distribution
 */
class Dirichlet extends Continuous
{
    /**
     * Distribution parameter bounds limits
     * x ∈ (0,1)
     * α ∈ (0,∞)
     * @var array
     */
    const LIMITS = [
        'x' => '(0,1)',
        'α' => '(0,∞)',
    ];

    /**
     * Probability density function
     *
     *        1    K   αᵢ-1
     * pdf = ----  ∏ xᵢ
     *       B(α) ⁱ⁼ⁱ
     *
     * where B(α) is the multivariate Beta function
     *
     * @param array float[] $xs
     * @param array float[] $αs
     *
     * @return float
     */
    public static function pdf(array $xs, array $αs): float
    {
        if (count($xs) !== count($αs)) {
            throw new Exception\BadDataException('xs and αs must have the same number of elements');
        }

        $n = count($xs);
        for ($i = 0; $i < $n; $i++) {
            Support::checkLimits(self::LIMITS, ['x' => $xs[$i], 'α' => $αs[$i]]);
        }

        /*
         *  K   αᵢ-1
         *  ∏ xᵢ
         * ⁱ⁼ⁱ
         */
        $∏xᵢ = array_product(
            array_map(
                function ($xᵢ, $αᵢ) {
                    return $xᵢ**($αᵢ - 1);
                },
                $xs,
                $αs
            )
        );

        $B⟮α⟯ = Special::multivariateBeta($αs);

        return $∏xᵢ / $B⟮α⟯;
    }
}
