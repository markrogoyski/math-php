<?php

namespace Math\Functions;

/**
 * A convenience class for piecewise functions.
 *
 * https://en.wikipedia.org/wiki/Piecewise
 */
class Piecewise
{
    private $intervals;
    private $functions;

    public function __construct(array $intervals, array $functions)
    {
        $this->intervals = $intervals;
        $this->functions = $functions;
    }

    /**
     * When a polynomial is to be treated as a string, return it in a readable format.
     * Example: $polynomial = new Polynomial([1, -8, 12, 3]);
     *          echo $polynomial;
     *          // prints 'x³ - 8x² + 12x + 3'
     *
     * @return string A human readable representation of the polynomial
     */
    public function __toString()
    {
        // Start with an empty polynomial
        $polynomial = '';

        // Iterate over each coefficient to generate the string for each term and add to the polynomial
        foreach ($this->coefficients as $i => $coefficient) {
            if ($coefficient == 0) {
                continue;
            }

            // Power of the current term
            $power = $this->degree - $i;

            // Build the exponent of our string as a unicode character
            $exponent = '';
            for ($j = 0; $j < strlen($power); $j++) {
                $digit     = intval(strval($power)[$j]); // The j-th digit of $power
                $exponent .= self::SYMBOLS[$digit];      // The corresponding unicode character
            };

            // Get the sign for the term
            $sign = ($coefficient > 0) ? '+' : '-';

            // Drop the sign from the coefficient, as it is handled by $sign
            $coefficient = abs($coefficient);

            // Drop coefficients that equal 1 (and -1) if they are not the 0th-degree term
            if ($coefficient == 1 and $this->degree - $i != 0) {
                $coefficient = '';
            }

            // Generate the $term string. No x term if power = 0.
            if ($power == 0) {
                $term = "{$sign} {$coefficient}";
            } else {
                $term = "{$sign} {$coefficient}x{$exponent} ";
            }

            // Add the current term to the polynomial
            $polynomial .= $term;
        }

        // Cleanup front and back; drop redundant ¹ and ⁰ terms from monomials
        $polynomial = trim(str_replace(['x¹ ','x⁰ '], 'x ', $polynomial), '+ ');
        $polynomial = preg_replace('/^\-\s/', '-', $polynomial);

        $polynomial = ($polynomial !== '') ? $polynomial : '0';

        return $polynomial;
    }

    /**
     * When a polynomial is being evaluated at a point x₀, build a callback
     * function and return the value of the callback function at x₀
     * Example: $polynomial = new Polynomial([1, -8, 12, 3]);
     *          echo $polynomial(4);
     *          // prints -13
     *
     * @param number $x₀ The value at which we are evaluting our polynomial
     *
     * @return number The result of our polynomial evaluated at $x₀
     */
    public function __invoke($x₀)
    {
        // Set object parameters as local variables so they can be used with the use function
        $degree       = $this->degree;
        $coefficients = $this->coefficients;

        // Start with the zero polynomial
        $polynomial = function ($x) {
            return 0;
        };

        // Iterate over each coefficient to create a callback function for each term
        for ($i = 0; $i < $degree + 1; $i++) {
            // Create a callback function for the current term
            $term = function ($x) use ($degree, $coefficients, $i) {
                return $coefficients[$i] * $x**($degree - $i);
            };
            // Add the new term to the polynomial
            $polynomial = Arithmetic::add($polynomial, $term);
        }

        return $polynomial($x₀);
    }

    public function inPiece ($x, $intervals)
    {
        foreach ($intervals as $i => $interval) {
            $a = $interval[0];
            $b = $interval[1];
            if ($x >= $a and $x <= $b) {
                return $i;
            }
        }
    }
}
