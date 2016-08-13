<?php
namespace Math\Statistics\Regression;

/**
 * The Michaelis-Menten equation is used to model enzyme kinetics.
 *       V * x
 * y = ----------
 *       K + x
 *
 *
 * https://en.wikipedia.org/wiki/Michaelis%E2%80%93Menten_kinetics
 */
abstract class MichaelisMenten extends Regression
{
    /**
     * @var number V regression parameter
     */
    protected $V;

    /**
     * @var number K regression parameter
     */
    protected $K;

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
