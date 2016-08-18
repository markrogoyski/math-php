<?php
namespace Math\Statistics\Regression\Models;

trait LinearModel
{
    protected static $B = 0; // b parameter index
    protected static $M = 1; // m parameter index
    
    /**
     * Evaluate the model given all the model parameters
     * y = mx + b
     *
     * @param number $x
     * @param array  $params
     *
     * @return number y evaluated
     */
    public static function evaluateModel($x, $params)
    {
        $m = $params[self::$M];
        $b = $params[self::$B];

        return $m * $x + $b;
    }
    /**
     * Get regression parameters (coefficients)
     * m = slope
     * b = y intercept
     *
     * @param array $params
     *
     * @return array [ m => number, b => number ]
     */
    public static function getModelParameters($params): array
    {
        return [
            'm' => $params[self::$M],
            'b' => $params[self::$B],
        ];
    }
    
    /**
     * Get regression equation (y = mx + b)
     *
     * @param array $params
     *
     * @return string
     */
    public static function getModelEquation($params): string
    {
        return sprintf('y = %fx + %f', $params[self::$M], $params[self::$B]);
    }
}
