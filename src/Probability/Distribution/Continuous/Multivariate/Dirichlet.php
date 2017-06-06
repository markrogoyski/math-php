<?php
namespace MathPHP\Probability\Distribution\Continuous\Multivariate;

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

        $B⟮α⟯ = self::multivariateBetaFunction($αs);

        return $∏xᵢ / $B⟮α⟯;

    }
    
    /**
     * Multivariate Beta function
     * https://en.wikipedia.org/wiki/Beta_function#Multivariate_beta_function
     *
     *                     Γ(α₁)Γ(α₂) ⋯ Γ(αn)
     * B(α₁, α₂, ... αn) = ------------------
     *                      Γ(α₁ + α₂ ⋯ αn)
     *
     * @param array float[] $αs
     *
     * @return float
     */
    private static function multivariateBetaFunction(array $αs): float
    {
        $∏Γ⟮α⟯ = 1;
        foreach ($αs as $α) {
            $∏Γ⟮α⟯ *= Special::Γ($α);
        }

        $Γ⟮∑α⟯ = Special::Γ(array_sum($αs));

        return $∏Γ⟮α⟯ / $Γ⟮∑α⟯;
    }
}
