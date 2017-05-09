<?php

namespace MathPHP\Functions;

use MathPHP\Algebra;
use MathPHP\Exception;
use MathPHP\Number\ObjectArithmetic;

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
 *     o Print a human readable string of a polynomial of any variable (default of x)
 *     o Evaluate a polynomial at any real number
 *     o Return the degree of a polynomial
 *     o Return the coefficients of a polynomial
 *     o Return the variable of a polynomial
 *     o Set the variable of an instantiated polynomial
 *     o Polynomial differentiation (exact)
 *     o Polynomial integration (indefinite integral)
 *     o Polynomial addition
 *     o Polynomial multiplication
 *
 * Examples:
 *     $polynomial = new Polynomial([1, -8, 12, 3]);
 *     echo $polynomial;                        // prints 'x³ - 8x² + 12x + 3'
 *     echo $polynomial(4);                     // prints -31
 *     echo $polynomial->getDegree();           // prints 3
 *     print_r($polynomial->getCoefficients()); // prints [1, -8, 12, 3]
 *     echo $polynomial->differentiate();       // prints '3x² - 16x + 12'
 *     echo $polynomial->integrate();           // prints '0.25x⁴ - 2.6666666666667x³ + 6x² + 3x'
 *     echo $polynomial->add($polynomial);      // prints '2x³ - 16x² + 24x + 6'
 *     echo $polynomial->multiply($polynomial); // prints 'x⁶ - 16x⁵ + 88x⁴ - 186x³ + 96x² + 72x + 9'
 *     echo $polynomial->getVariable();         // prints 'x'
 *     $polynomial->setVariable("r");
 *     echo $polynomial;                        // prints 'r³ - 8r² + 12r + 3'
 *
 * https://en.wikipedia.org/wiki/Polynomial
 */
class Polynomial implements ObjectArithmetic
{
    private $degree;
    private $coefficients;
    private $variable;

    /**
     * @var array Unicode characters for exponents
     */
    const SYMBOLS = ['⁰', '¹', '²', '³', '⁴', '⁵', '⁶', '⁷', '⁸', '⁹'];

    /**
     * When a polynomial is instantiated, set the coefficients and degree of
     * that polynomial as its object parameters.
     *
     * @param mixed $coefficients An array of coefficients in decreasing powers
     *                            Example: new Polynomial([1, 2, 3]) will create
     *                            a polynomial that looks like x² + 2x + 3.
     */
    public function __construct(array $coefficients, $variable = "x")
    {
        // Remove coefficients that are leading zeros
        for ($i = 0; $i < count($coefficients); $i++) {
            if ($coefficients[$i] != 0) {
                break;
            }
            unset($coefficients[$i]);
        }

        // If coefficients remain, re-index them. Otherwise return [0] for p(x) = 0
        $coefficients       = ($coefficients != []) ? array_values($coefficients) : [0];

        $this->degree       = count($coefficients) - 1;
        $this->coefficients = $coefficients;
        $this->variable     = $variable;
    }

    /**
     * When a polynomial is to be treated as a string, return it in a readable format.
     * Example: $polynomial = new Polynomial([1, -8, 12, 3]);
     *          echo $polynomial;
     *          // prints 'x³ - 8x² + 12x + 3'
     *
     * @return string A human readable representation of the polynomial
     */
    public function __toString(): string
    {
        $variable = $this->variable;

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

            // Generate the $term string. No $variable term if power = 0.
            if ($power == 0) {
                $term = "{$sign} {$coefficient}";
            } else {
                $term = "{$sign} {$coefficient}{$variable}{$exponent} ";
            }

            // Add the current term to the polynomial
            $polynomial .= $term;
        }

        // Cleanup front and back; drop redundant ¹ and ⁰ terms from monomials
        $polynomial = trim(str_replace([$variable . '¹ ', $variable . '⁰ '], $variable . ' ', $polynomial), '+ ');
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
    public function __invoke($x₀): float
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
     * Check that our input is either a number or a Polynomial
     * Convert any numbers to Polynomial objects
     *
     * @param mixed $input The variable to check
     * @return Polynomial
     * @throws IncorrectTypeException
     */
    private function checkNumericOrPolynomial($input): Polynomial
    {
        if (is_numeric($input)) {
            $polynomial = new Polynomial([$input]);
        } elseif ($input instanceof Polynomial) {
            $polynomial = $input;
        } else {
            throw new Exception\IncorrectTypeException('Input must be a Polynomial or a number');
        }
        return $polynomial;
    }
    /**
     * Getter method for the degree of a polynomial
     *
     * @return int The degree of a polynomial object
     */
    public function getDegree(): int
    {
        return $this->degree;
    }

    /**
     * Getter method for the coefficients of a polynomial
     *
     * @return array The coefficients array of a polynomial object
     */
    public function getCoefficients(): array
    {
        return $this->coefficients;
    }

    /**
     * Getter method for the dependent variable of a polynomial
     *
     * @return string The dependent variable of a polynomial object
     */
    public function getVariable(): string
    {
        return $this->variable;
    }

    /**
     * Setter method for the dependent variable of a polynomial
     *
     * @param string The new dependent variable of a polynomial object
     */
    public function setVariable(string $variable)
    {
        $this->variable = $variable;
    }

    /**
     * Calculate the derivative of a polynomial and return it as a new polynomial
     * Example: $polynomial = new Polynomial([1, -8, 12, 3]); // x³ - 8x² + 12x + 3
     *          $derivative = $polynomial->differentiate();   // 3x² - 16x + 12
     *
     * @return object The derivative of our polynomial object, also a polynomial object
     */
    public function differentiate(): Polynomial
    {
        $derivativeCoefficients = []; // Start with empty set of coefficients

        // Iterate over each coefficient (except the last), differentiating term-by-term
        for ($i = 0; $i < $this->degree; $i++) {
            $derivativeCoefficients[] = $this->coefficients[$i] * ($this->degree - $i);
        }

        // If the array of coefficients is empty, we are differentiating a constant. Return [0].
        $derivativeCoefficients = ($derivativeCoefficients !== []) ? $derivativeCoefficients : [0];

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
    public function integrate(): Polynomial
    {
        $integralCoefficients = []; // Start with empty set of coefficients

        // Iterate over each coefficient, integrating term-by-term
        for ($i = 0; $i < $this->degree + 1; $i++) {
            $integralCoefficients[] = $this->coefficients[$i] / ($this->degree - $i + 1);
        }
        $integralCoefficients[] = 0; // Make the constant of integration 0

        return new Polynomial($integralCoefficients);
    }

    /**
     * Return a new polynomial that is the sum of the current polynomial and an
     * input polynomial
     * Example: $polynomial = new Polynomial([3, -16, 12]); // 3x² - 16x + 12
     *          $integral   = $polynomial->integrate();     // x³ - 8x² + 12x
     *          $sum        = $polynomial->add($integral);  // x³ - 5x² - 4x + 12
     *
     * @param mixed $polynomial The polynomial or scaler we are adding to our current polynomial
     *
     * @return object The sum of our polynomial objects, also a polynomial object
     */
    public function add($polynomial): Polynomial
    {
        $polynomial = $this->checkNumericOrPolynomial($polynomial);
        // Calculate the degree of the sum of the polynomials
        $sumDegree = max($this->degree, $polynomial->degree);

        // Reverse the coefficients arrays so you can sum component-wise
        $coefficientsA = array_reverse($this->coefficients);
        $coefficientsB = array_reverse($polynomial->coefficients);

        // Start with an array of coefficients that all equal 0
        $sumCoefficients = array_fill(0, $sumDegree+1, 0);

        // Iterate through each degree. Get coefficients by summing component-wise.
        for ($i = 0; $i < $sumDegree + 1; $i++) {
            // Calculate the degree of the current sum
            $degree = $sumDegree - $i;

            // Get the coefficient of the i-th degree term from each polynomial if it exists, otherwise use 0
            $a = $coefficientsA[$i] ?? 0;
            $b = $coefficientsB[$i] ?? 0;

            // The new coefficient is the sum of the original coefficients
            $sumCoefficients[$degree] = $sumCoefficients[$degree] + $a + $b;
        }

        return new Polynomial($sumCoefficients);
    }

    /**
     * Return a new polynomial that is the difference of the current polynomial and an
     * input polynomial
     * Example: $polynomial = new Polynomial([3, -16, 12]); // 3x² - 16x + 12
     *          $integral   = $polynomial->diferentiate();     // 6x - 16
     *          $difference        = $polynomial->subtract($derivative);  // 3x² - 22x + 28
     *
     * @param mixed $polynomial The polynomial or scaler we are subtracting from our current polynomial
     *
     * @return object The defference of our polynomial objects, also a polynomial object
     */
    public function subtract($polynomial): Polynomial
    {
        $polynomial = $this->checkNumericOrPolynomial($polynomial);
        // Calculate the degree of the sum of the polynomials
        $difDegree = max($this->degree, $polynomial->degree);

        // Reverse the coefficients arrays so you can sum component-wise
        $coefficientsA = array_reverse($this->coefficients);
        $coefficientsB = array_reverse($polynomial->coefficients);

        // Start with an array of coefficients that all equal 0
        $difCoefficients = array_fill(0, $difDegree+1, 0);

        // Iterate through each degree. Get coefficients by summing component-wise.
        for ($i = 0; $i < $difDegree + 1; $i++) {
            // Calculate the degree of the current sum
            $degree = $difDegree - $i;

            // Get the coefficient of the i-th degree term from each polynomial if it exists, otherwise use 0
            $a = $coefficientsA[$i] ?? 0;
            $b = $coefficientsB[$i] ?? 0;

            // The new coefficient is the sum of the original coefficients
            $difCoefficients[$degree] = $a - $b;
        }

        return new Polynomial($difCoefficients);
    }

    /**
     * Return a new polynomial that is the product of the current polynomial and an
     * input polynomial
     * Example: $polynomial = new Polynomial([2, -16]);          // 2x - 16
     *          $integral   = $polynomial->integrate();          // x² - 16x
     *          $product    = $polynomial->multiply($integral);  // 2x³ - 48x² + 256x
     *
     * @param mixed $polynomial The polynomial or scaler we are multiplying with our current polynomial
     *
     * @return object The product of our polynomial objects, also a polynomial object
     */
    public function multiply($polynomial): Polynomial
    {
        $polynomial = $this->checkNumericOrPolynomial($polynomial);
        // Calculate the degree of the product of the polynomials
        $productDegree = $this->degree + $polynomial->degree;

        // Reverse the coefficients arrays so you can multiply component-wise
        $coefficientsA = array_reverse($this->coefficients);
        $coefficientsB = array_reverse($polynomial->coefficients);

        // Start with an array of coefficients that all equal 0
        $productCoefficients = array_fill(0, $productDegree+1, 0);

        // Iterate through the product of terms component-wise
        for ($i = 0; $i < $this->degree + 1; $i++) {
            for ($j = 0; $j < $polynomial->degree + 1; $j++) {
                // Calculate the degree of the current product
                $degree = $productDegree-($i+$j);

                // Calculate the product of the coefficients
                $product = $coefficientsA[$i] * $coefficientsB[$j];

                // Add the product to the existing coefficient of the current degree
                $productCoefficients[$degree] = $productCoefficients[$degree] + $product;
            }
        }

        return new Polynomial($productCoefficients);
    }

    /**
     * Calculate the roots of a polynomial
     *
     * Closed form solutions only exist if the degree is less than 5
     *
     * @return array of roots
     */
    public function roots(): array
    {
        switch ($this->degree) {
            case 1:
                return [-1 * $this->coefficients[1] / $this->coefficients[0]];
            case 2:
                return Algebra::quadratic(...$this->coefficients);
            case 3:
                return Algebra::cubic(...$this->coefficients);
            case 4:
                return Algebra::quartic(...$this->coefficients);
            default:
                return [\NAN];
        }
    }
}
