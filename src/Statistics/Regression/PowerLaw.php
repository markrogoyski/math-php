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
    use LeastSquares;
    /**
     * Calculate the regression parameters by least squares on linearized data
     * ln(y) = ln(A) + B*ln(x)
     */
    public function calculate()
    {
        // Linearize the relationship by taking the log of both sides.
        $xprime = array_map('log', $this->xs);
        $yprime = array_map('log', $this->ys);
        
        // Perform Least Squares Fit
        $parameters = $this->leastSquares($yprime, $xprime);
        
        // Translate the linearized parameters back.
        $this->a = exp($parameters['b']);
        $this->b = $parameters['m'];
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
