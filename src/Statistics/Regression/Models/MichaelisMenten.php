<?php
namespace Math\Statistics\Regression\Models;
/**
 * The Michaelis-Menten equation is used to model enzyme kinetics.
 *       V * x
 * y = ----------
 *       K + x
 *
 *
 * https://en.wikipedia.org/wiki/Michaelis%E2%80%93Menten_kinetics
 */
trait MichaelisMenten
{
    
    /**
     * Get regression parameters (V and K)
     *
     * @return array [ V => number, K => number ]
     */
    public static function getModelParameters($params): array
    {
        return [
            'V' => $params[0],
            'K' => $params[1],
        ];
    }
    /**
     * Get regression equation (y = V * X / (K + X))
     *
     * @return string
     */
    public static function getModelEquation($params): string
    {
        return sprintf('y = %fx/(%f+x)', $params[0], $params[1]);
    }
    /**
     * Evaluate the equation using the regression parameters
     * y = V * X / (K + X)
     *
     * @param number $x
     *
     * @return number y evaluated
     */
    public static function evaluateModel($x, $params)
    {
        return ($params[0] * $x) / ($params[1] + $x);
    }
}
