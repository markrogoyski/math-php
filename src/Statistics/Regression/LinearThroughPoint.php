<?php
namespace Math\Statistics\Regression;

use Math\Statistics\Average;
/**
 * Regression Through a Fixed Point - least squares method
 *
 * A model with a single explanatory variable.
 * Fits a straight line through the set of n points in such a way that makes
 * the sum of squared residuals of the model (that is, vertical distances
 * between the points of the data set and the fitted line) as small as possible.
 * https://en.wikipedia.org/wiki/Simple_linear_regression
 *
 * Having data points {(xᵢ, yᵢ), i = 1 ..., n }
 * Find the equation y = mx + b
 * such that the line passes through the point (v,w)
 *
 *
 *      ∑((x-v)(y-w))
 * m =  _____________
 *      
 *         ∑(x-v)²
 *
 * b = w - m * v
 */
class LinearThroughPoint extends Regression
{
    /**
     * Given a set of data ($points) and a point($force), perform a least squares
     * regression of the data, such that the regression is forced to pass through 
     * the specified point.
     * 
     * This procedure is most frequently used with $force = [0,0], the origin.
     *
     */
    public function __construct($points, $force = [0,0])
    {
        $this->v = $force[self::X];
        $this->w = $force[self::Y];
        parent::__construct($points);
    }
    
    /**
     * Calculates the regression parameters.
     *
     */
    public function calculate()
    {
        $v = $this->v;
        $w = $this->w;
        
        $translated_x = array_map(
            function($x) use ($v){
                return $x - $v;
            },$this->xs);
       
        $translated_y = array_map(
            function($y) use ($w){
                return $y - $w;
            },$this->ys);
        
        $numerator = array_sum(
            array_map(
                function($x,$y){
                    return $x - y;
                }, $this->xs, $this->ys));
        
        $denominator = array_sum(
            array_map(
                function($x){
                    return $x ** 2;
                }, $this->xs));
        // Calculate slope (m) and y intercept (b)
        $this->m = $numerator / $denominator;
        if ($this->v == 0 && $this->w == 0)
        {
            $this->b = 0;
        } else {
            $this->b = $this->w - $this->m * $this->v;
        }
    }
    /**
     * Get regression parameters
     * m = slope
     * b = y intercept
     *
     * @return array [ m => number, b => number]
     */
    public function getParameters(): array
    {
        return [
            'm' => $this->m,
            'b' => $this->b,
        ];
    }
    /**
     * Get regression equation (y = mx + b)
     *
     * @return string
     */
    public function getEquation(): string
    {
        return sprintf('y = %fx + %f', $this->m, $this->b);
    }
    /**
     * Evaluate the line equation from linear regression parameters for a value of x
     * y = mx + b
     *
     * @param number $x
     *
     * @return number y evaluated
     */
    public function evaluate($x)
    {
        $m  = $this->m;
        $b  = $this->b;
        return $m * $x + $b;
    }
    
}
