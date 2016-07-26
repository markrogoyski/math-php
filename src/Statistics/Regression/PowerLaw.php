<?php
namespace Math\Statistics\Regression;

use Math\Statistics\Average;

/**
 * Power law regression (power curve) - Least squares fitting
 * http://mathworld.wolfram.com/LeastSquaresFittingPowerLaw.html
 *
 * A functional relationship between two quantities,
 * where a relative change in one quantity results in a proportional
 * relative change in the other quantity,
 * independent of the initial size of those quantities: one quantity
 * varies as a power of another.
 * https://en.wikipedia.org/wiki/Power_law
 *
 * y = Axᴮ
 *
 * Using least squares fitting: y = axᵇ
 *
 *     n∑⟮ln xᵢ ln yᵢ⟯ − ∑⟮ln xᵢ⟯ ∑⟮ln yᵢ⟯
 * b = --------------------------------
 *           n∑⟮ln xᵢ⟯² − ⟮∑⟮ln xᵢ⟯⟯²
 *         _                    _
 *        |  ∑⟮ln yᵢ⟯ − b∑⟮ln xᵢ⟯  |
 * a = exp|  ------------------  |
 *        |_          n         _|
 */
class PowerLaw extends Regression
{
    /**
     * Calculate the regression parameters by least squares on linearized data
     * ln(y) = ln(A) + B*ln(x)
     */
    public function calculate()
    {
        $n = $this->n;

        // Intermediate b calculations
        $n∑⟮ln xᵢ ln yᵢ⟯ = $n * array_sum(array_map(
            function ($x, $y) {
                return log($x) * log($y);
            },
            $this->xs,
            $this->ys
        ));

        $∑⟮ln xᵢ⟯ = array_sum(array_map(
            function ($x) {
                return log($x);
            },
            $this->xs
        ));
        $∑⟮ln yᵢ⟯ = array_sum(array_map(
            function ($y) {
                return log($y);
            },
            $this->ys
        ));
        $∑⟮ln xᵢ⟯ ∑⟮ln yᵢ⟯ = $∑⟮ln xᵢ⟯ * $∑⟮ln yᵢ⟯;

        $n∑⟮ln xᵢ⟯² = $n * array_sum(array_map(
            function ($x) {
                return pow(log($x), 2);
            },
            $this->xs
        ));
        $⟮∑⟮ln xᵢ⟯⟯² = pow(
            array_sum(array_map(function ($x) {
                return log($x);
            }, $this->xs)),
            2
        );

        // Calculate a and b
        $numerator   = $n∑⟮ln xᵢ ln yᵢ⟯ - $∑⟮ln xᵢ⟯ ∑⟮ln yᵢ⟯ ;
        $denominator = $n∑⟮ln xᵢ⟯² - $⟮∑⟮ln xᵢ⟯⟯²;
        $this->b = $numerator / $denominator;
        $this->a = exp(( $∑⟮ln yᵢ⟯ - $this->b * $∑⟮ln xᵢ⟯ ) / $n);
    }

    /**
     * Get regression parameters (a and b)
     *
     * @return array [ a => number, b => number ]
     */
    public function getParameters(): array
    {
        return [
            'a' => $this->a,
            'b' => $this->b,
        ];
    }

    /**
     * Get regression equation (y = axᵇ) in format y = ax^b
     *
     * @return string
     */
    public function getEquation(): string
    {
        return sprintf('y = %fx^%f', $this->a, $this->b);
    }

   /**
    * Evaluate the power curve equation from power law regression parameters for a value of x
    * y = axᵇ
    *
    * @param number $x
    *
    * @return number y evaluated
    */
    public function evaluate($x)
    {
        return $this->a * $x**$this->b;
    }
}
