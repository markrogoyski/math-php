<?php
namespace Math\Statistics\Regression\Models;

trait LinearModel
{
    public static $B = 0; // b parameter index
    public static $M = 1; // m parameter index
    
    /**
     * Evaluate the model given all the model parameters
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
     * @return string
     */
    public static function getModelEquation($params): string
    {
        return sprintf('y = %fx + %f', $params[self::$M], $params[self::$B]);
    }
}
