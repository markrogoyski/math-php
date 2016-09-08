<?php

namespace Math\Functions;

/**
 * A convenience class for one-dimension polynomials.
 *
 * This class is used to encompass typical methods and features that you can extend
 * to polynomials. For example, polynomial differentiation follows a specific rule,
 * and thus we can build a differentiation method that returns the exact derivative
 * for polynomials.
 *
 * Input arguments: simply pass in an array of coefficients in decreasing powers.
 * Make sure to put a 0 coefficient in place of powers that are not used.
 *
 * Current features:
 *     o Print a human readable representation of a polynomial
 *     o Evaluate a polynomial at any real number
 *     o Polynomial differentiation (exact)
 *     o Polynomial integration (indefinite integral)
 *
 * Example:
 *     $polynomial = new Polynomial([1, -8, 12, 3]);
 *     echo $polynomial;                  // prints 'x³ - 8x² + 12x + 3'
 *     echo $polynomial(4);               // prints -31
 *     echo $polynomial->$differentiate() // prints '3x² - 16x + 12'
 *     echo $polynomial->$integrate()     // prints '0.25x⁴ - 2.6666666666667x³ + 6x² + 3x'
 *
 * https://en.wikipedia.org/wiki/Polynomial
 */
class Polynomial
{
    private $degree;
    private $coefficients;

    /**
     * @var array Unicode characters for exponents
     */
    const SYMBOLS = ['⁰', '¹', '²', '³', '⁴', '⁵', '⁶', '⁷', '⁸', '⁹'];

    /**
     * When a polynomial is instantiated, set the coefficients and degree of
     * that polynomial as its object parameters.
     *
     * @param array $coefficients An array of coefficients in decreasing powers.
     *                            Example: new Polynomial([1, 2, 3]) will create
     *                            a polynomial that looks like x² + 2x + 3.
     */
    public function __construct(array $coefficients)
    {
        $this->degree       = count($coefficients) - 1;
        $this->coefficients = $coefficients;
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

            // Drop coefficients that equal 1 (and -1)
            if ($coefficient == 1) {
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

    /**
     * Calculate the derivative of a polynomial and return it as a new polynomial
     * Example: $polynomial = new Polynomial([1, -8, 12, 3]); // x³ - 8x² + 12x + 3
     *          $derivative = $polynomial->differentiate();   // 3x² - 16x + 12
     *
     * @return object The derivative of our polynomial object, also a polynomial object
     */
    public function differentiate()
    {
        $derivativeCoefficients = []; // Start with empty set of coefficients

        // Iterate over each coefficient (except the last), differentiating term-by-term
        for ($i = 0; $i < $this->degree; $i++) {
            $derivativeCoefficients[] = $this->coefficients[$i] * ($this->degree - $i);
        }

        return new Polynomial($derivativeCoefficients);
    }

    /**
     * Calculate the indefinite integral of a polynomial and return it as a new polynomial
     * Example: $polynomial = new Polynomial([3, -16, 12]); // 3x² - 16x + 12
     *          $integral = $polynomial->integrate();       // x³ - 8x² + 12x
     *
     * Note that this method assumes the constant of integration to be 0.
     *
     * @return object The integral of our polynomial object, also a polynomial object
     */
    public function integrate()
    {
        $integralCoefficients = []; // Start with empty set of coefficients

        // Iterate over each coefficient, integrating term-by-term
        for ($i = 0; $i < $this->degree + 1; $i++) {
            $integralCoefficients[] = $this->coefficients[$i] / ($this->degree - $i + 1);
        }
        $integralCoefficients[] = 0; // Make the constant of integration 0

        return new Polynomial($integralCoefficients);
    }

    public function add(Polynomial $polynomial)
    {
        $sumDegree       = max($this->degree, $polynomial->degree);
        $coefficientsA = array_reverse($this->coefficients);
        $coefficientsB = array_reverse($polynomial->coefficients);

        $sumcoefficients = [];

        for ($i = 0; $i < $sumDegree + 1; $i++) {
            $a = $coefficientsA[$i] ?? 0;
            $b = $coefficientsB[$i] ?? 0;
            $sumcoefficients[$sumDegree - $i] = $a + $b;
        }

        return new Polynomial($sumcoefficients);
    }

    public function multiply(Polynomial $polynomial)
    {
        $productDegree       = $this->degree + $polynomial->degree;
        $coefficientsA = array_reverse($this->coefficients);
        $coefficientsB = array_reverse($polynomial->coefficients);

        $productcoefficients = array_fill(0, $productDegree+1, 0);

        for ($i = 0; $i < $this->degree + 1; $i++) {
            for ($j = 0; $j < $polynomial->degree + 1; $j++ ) {
                $index = $productDegree-($i+$j);
                $product = $coefficientsA[$i] * $coefficientsB[$j];
                $productcoefficients[$index] = $productcoefficients[$index] + $product;
            }
        }

        return new Polynomial($productcoefficients);
    }
}
