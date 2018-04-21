<?php
namespace MathPHP\Statistics\Regression;

use MathPHP\Exception;

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
 *     n∑⟮ln xᵢ ln yᵢ⟯ − ∑⟮ln xᵢ⟯ ∑⟮ln yᵢ⟯
 * b = --------------------------------
 *           n∑⟮ln xᵢ⟯² − ⟮∑⟮ln xᵢ⟯⟯²
 *         _                    _
 *        |  ∑⟮ln yᵢ⟯ − b∑⟮ln xᵢ⟯  |
 * a = exp|  ------------------  |
 *        |_          n         _|
 */
class PowerLaw extends ParametricRegression
{
    use Models\PowerModel, Methods\LeastSquares;

    /**
     * Calculate the regression parameters by least squares on linearized data
     * ln(y) = ln(A) + B*ln(x)
     *
     * @throws Exception\BadDataException
     * @throws Exception\MatrixException
     */
    public function calculate()
    {
        // Linearize the relationship by taking the log of both sides.
        $x’ = array_map('log', $this->xs);
        $y’ = array_map('log', $this->ys);
        
        // Perform Least Squares Fit
        $linearized_parameters = $this->leastSquares($y’, $x’)->getColumn(0);

        // Translate the linearized parameters back.
        $this->a = exp($linearized_parameters[0]);
        $this->b = $linearized_parameters[1];

        $this->parameters = [$this->a, $this->b];
    }
}
