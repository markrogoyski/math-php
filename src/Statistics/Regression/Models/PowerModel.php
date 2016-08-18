<?php
namespace Math\Statistics\Regression\Models;

trait PowerModel
{
    protected static $B = 1; // b parameter index
    protected static $A = 0; // m parameter index
    
   /**
    * Evaluate the power curve equation from power law regression parameters for a value of x
    * y = axᵇ
    *
    * @param number $x
    * @param array $params
    *
    * @return number y evaluated
    */
    public static function evaluateModel($x, array $params)
    {
        $a = $params[self::$A];
        $b = $params[self::$B];

        return $a * $x**$b;
    }
    
        /**
     * Get regression parameters (a and b)
     *
     * @return array [ a => number, b => number ]
     */
    public static function getModelParameters($params): array
    {
        return [
            'a' => $params[self::$A],
            'b' => $params[self::$B],
        ];
    }

    /**
     * Get regression equation (y = axᵇ) in format y = ax^b
     *
     * @return string
     */
    public static function getModelEquation($params): string
    {
        return sprintf('y = %fx^%f', $params[self::$A], $params[self::$B]);
    }
}
