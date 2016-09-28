<?php
namespace Math\NumericalAnalysis\Interpolation;

use Math\Functions\Polynomial;
use Math\Functions\Piecewise;

/**
 * Clamped Cubic Spline Interpolating Polyonomial
 *
 * In numerical analysis, cubic splines are used for polynomial
 * interpolation.
 *
 * A cubic spline is a spline constructed of piecewise third-order polynomials
 * which pass through a set of m control points." In the case of the Clamped
 * cubic spline, the first derivative of piecewise polynomial is set to equal the
 * first derivative of our input at the endpoints.
 *
 * Cubic spline interpolation belongs to a collection of techniques that
 * interpolate a function or a set of values, producing a continuous polynomial.
 * In the case of the cubic spline, a piecewise function (polynomial) is produced.
 * We can either directly supply a set of inputs and their corresponding outputs
 * for said function, or if we explicitly know the function, we can define it as
 * a callback function and then generate a set of points by evaluating that
 * function at n points between a start and end point. We then use these values
 * to interpolate our piecewise polynomial.
 *
 * https://en.wikipedia.org/wiki/Spline_interpolation
 * http://mathworld.wolfram.com/CubicSpline.html
 */
class ClampedCubicSpline extends Interpolation
{
    /**
     * Interpolate
     *
     * @param          $source   The source of our approximation. Should be either
     *                           a callback function or a set of arrays. Each array
     *                           (point) contains precisely three numbers: x, y, and y'
     *                           Example array: [[1,2,1], [2,3,0], [3,4,2]].
     *                           Example callback: function($x) {return $x**2;}
     * @param numbers  ... $args (Optional) An additonal callback: our first derivative,
     *                           and arguments of our callback functions: start,
     *                           end, and n.
     *                           Example: approximate($source, $derivative, 0, 8, 5).
     *                           If $source is a set of points, do not input any
     *                           $args. Example: approximate($source).
     *
     * @return Piecewise         The interpolting (piecewise) polynomial, as an
     *                           instance of Piecewise.
     */
    public static function interpolate($source, ... $args)
    {
        // Get an array of points from our $source argument
        $points = self::getPoints($source, $args);

        // Validate input and sort points
        self::validate($points, $degree = 1);
        $sorted = self::sort($points);

        // Descriptive constants
        $x = self::X;
        $y = self::Y;

        // Initialize
        $n     = count($sorted);
        $k     = $n - 1;
        $h     = [];
        $a     = [];
        $μ     = [0];
        $z     = [0];
        $z[$k] = 0;
        $c     = [];
        $c[$k] = 0;
        $poly  = [];

        for ($i = 0; $i < $k; $i++) {
            $xᵢ     = $sorted[$i][$x];
            $xᵢ₊₁   = $sorted[$i+1][$x];
            $a[$i]  = $sorted[$i][$y];
            $h[$i]  = $xᵢ₊₁ - $xᵢ;

            if ($i == 0) {
                continue;
            }

            $xᵢ₋₁   = $sorted[$i-1][$x];
            $f⟮xᵢ⟯   = $sorted[$i][$y];    // yᵢ
            $f⟮xᵢ₊₁⟯ = $sorted[$i+1][$y];  // yᵢ₊₁
            $f⟮xᵢ₋₁⟯ = $sorted[$i-1][$y];  // yᵢ₋₁

            $α      = (3/$h[$i])*($f⟮xᵢ₊₁⟯ - $f⟮xᵢ⟯) - (3/$h[$i-1])*($f⟮xᵢ⟯ - $f⟮xᵢ₋₁⟯);
            $l      = 2*($xᵢ₊₁ - $xᵢ₋₁) - $h[$i-1]*$μ[$i-1];
            $μ[$i]  = $h[$i]/$l;
            $z[$i]  = ($α - $h[$i-1]*$z[$i-1])/$l;
        }

        for ($i = $k-1; $i >= 0; $i--) {
            $xᵢ       = $sorted[$i][$x];
            $xᵢ₊₁     = $sorted[$i+1][$x];
            $f⟮xᵢ⟯     = $sorted[$i][$y];    // yᵢ
            $f⟮xᵢ₊₁⟯   = $sorted[$i+1][$y];  // yᵢ₊₁

            $c[$i]    = $z[$i] - $μ[$i]*$c[$i+1];
            $b[$i]    = ($f⟮xᵢ₊₁⟯ - $f⟮xᵢ⟯)/$h[$i] - $h[$i]*($c[$i+1] + 2*$c[$i])/3;
            $d[$i]    = ($c[$i+1] - $c[$i])/(3*$h[$i]);

            $poly[$i] = new Polynomial([
                $d[$i],
                $c[$i] - 3*$d[$i]*$xᵢ,
                $b[$i] - 2*$c[$i]*$xᵢ + 3*$d[$i]*($xᵢ**2),
                $a[$i] - $b[$i]*$xᵢ + $c[$i]*($xᵢ**2) - $d[$i]*($xᵢ**3)
            ]);

            if ($i == 0) {
                $int[$i] = [$xᵢ, $xᵢ₊₁];
            } else {
                $int[$i] = [$xᵢ, $xᵢ₊₁, true, false];
            }
        }

        $piecewise = new Piecewise($int, $poly);

        return $piecewise;
    }

    /**
     * Determine where the input $source argument is a callback function, a set
     * of arrays, or neither. If $source is a callback function, run it through
     * the functionToPoints() method with the input $args, and set $points to
     * output array. If $source is a set of arrays, simply set $points to
     * $source. If $source is neither, throw an Exception.
     *
     * @todo  Add method to verify function is continuous on our interval
     * @todo  Add method to verify input arguments are valid.
     *        Verify $start and $end are numbers, $end > $start, and $points is an integer > 1
     *
     * @param          $source The source of our approximation. Should be either
     *                         a callback function or a set of arrays.
     * @param  array   $args   The arguments of our callback function: start,
     *                         end, and n. Example: [0, 8, 5]. If $source is a
     *                         set of arrays, $args will default to [].
     *
     * @return array
     * @throws Exception if $source is not callable or a set of arrays
     */
    public static function getPoints($source, array $args = []): array
    {
        if (is_callable($source)) {
            $function   = $source;
            $derivative = $args[0];
            $start      = $args[1];
            $end        = $args[2];
            $n          = $args[3];
            $points     = self::functionToPoints($function, $derivative, $start, $end, $n);
        } elseif (is_array($source)) {
            $points   = $source;
        } else {
            throw new \Exception("Input source is incorrect. You need to input
                                  either a callback function or a set of arrays");
        }

        return $points;
    }

    /**
     * Evaluate our callback function at n evenly spaced points on the interval
     * between start and end
     *
     * @param  callable $function   f(x) callback function
     * @param  callable $derivative f'(x) callback function
     * @param  number   $start      the start of the interval
     * @param  number   $end        the end of the interval
     * @param  number   $n          the number of function evaluations
     *
     * @return array
     */
    protected static function functionToPoints(callable $function, callable $derivative, $start, $end, $n): array
    {
        $points = [];
        $h      = ($end-$start)/($n-1);

        for ($i = 0; $i < $n; $i++) {
            $xᵢ         = $start + $i*$h;
            $f⟮xᵢ⟯       = $function($xᵢ);
            $f’⟮xᵢ⟯      = $derivative($xᵢ);
            $points[$i] = [$xᵢ, $f⟮xᵢ⟯, $f’⟮xᵢ⟯];
        }
        return $points;
    }

    /**
     * Validate that there are enough input arrays (points), that each point array
     * has precisely two numbers, and that no two points share the same first number
     * (x-component)
     *
     * @param  array  $points Array of arrays (points)
     * @param  number $degree The miminum number of input arrays
     *
     * @return bool
     * @throws Exception if there are less than two points
     * @throws Exception if any point does not contain two numbers
     * @throws Exception if two points share the same first number (x-component)
     */
    public static function validate(array $points, $degree = 2): bool
    {
        if (count($points) < $degree) {
            throw new \Exception("You need to have at least $degree sets of
                                  coordinates (arrays) for this technique");
        }

        $x_coordinates = [];
        foreach ($points as $point) {
            if (count($point) !== 2) {
                throw new \Exception("Each array needs to have have precisely
                                      two numbers, an x- and y-component");
            }

            $x_component = $point[self::X];
            if (in_array($x_component, $x_coordinates)) {
                throw new \Exception("Not a function. Your input array contains
                                      more than one coordinate with the same
                                      x-component.");
            }
            array_push($x_coordinates, $x_component);
        }

        return true;
    }
}
