<?php
namespace Math\Statistics\Regression;
use Math\Functions\Map\Multi;
/**
 * Use the Hanes-Woolf method to fit an equation of the form
 *       V * x
 * y = ----------
 *       K + x
 *
 * The equation is linearized and fit using Least Squares
 */
class LineweaverBurk extends Regression
{
    use LeastSquares;
    /**
     * @var number V regression parameter
     */
    private $V;
    /**
     * @var number K regression parameter
     */
    private $K;
    /**
     * Calculate the regression parameters by least squares on linearized data
     * x / y = x / V + K / V
     */
    public function calculate()
    {
        // Linearize the relationship by dividing x by y
        $y’ = Multi::disvide($this->xs, $this->ys);
        // Perform Least Squares Fit
        $parameters = $this->leastSquares($y’, $this->xs);
        // Translate the linearized parameters back.
        $this->V = 1 / $parameters['m'];
        $this->K = $parameters['b'] * $this->V;
    }
    /**
     * Get regression parameters (V and K)
     *
     * @return array [ V => number, K => number ]
     */
    public function getParameters(): array
    {
        return [
            'V' => $this->V,
            'K' => $this->K,
        ];
    }
    /**
     * Get regression equation (y = V * X / (K + X))
     *
     * @return string
     */
    public function getEquation(): string
    {
        return sprintf('y = %fx/(%f+x)', $this->V, $this->K);
    }
   /**
    * Evaluate the equation using the regression parameters
    * y = V * X / (K + X)
    *
    * @param number $x
    *
    * @return number y evaluated
    */
    public function evaluate($x)
    {
        return ($this->V * $x) / ($this->K + $x);
    }
}
