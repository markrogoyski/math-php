<?php

namespace Math\Functions;

class Polynomial
{
    private $degree;
    private $coefficient;

    public function __construct(array $coefficient)
    {
        $this->degree = count($coefficient) - 1;
        $this->coefficient = $coefficient;
    }

    public function __toString()
    {
        $polynomial = ""; // Start with an empty polynomial
        $symbol     = ["⁰", "¹", "²", "³", "⁴", "⁵", "⁶", "⁷", "⁸", "⁹"]; // Unicode characters

        // Iterate over each coefficient to generate the string for each term
        for ($i = 0; $i < $this->degree + 1; $i++) {

            // If coefficient is 0, skip to the next term
            if ($this->coefficient[$i] == 0) {
                continue;

            // Otherwise, use the coefficient as is
            } else {
                $coefficient = intval($this->coefficient[$i]);
            }

            // Build the exponent of our string as a unicode character
            $exponent = "";                 // Start with empty exponent
            $power    = $this->degree - $i; // Power of the current term
            for ($j = 0; $j < strlen($power); $j++) {
                $digit     = intval(strval($power)[$j]); // The j-th digit of $power
                $exponent .= $symbol[$digit];            // The corresponding unicode character
            };

            // Drop redundant ¹ term from monomials
            if ($exponent == "¹") {
                $exponent = "";
            }

            // Get the sign for the term
            if ($coefficient > 0) {
                if ($power == $this->degree) {
                    $sign = ""; // If the first term is positive, drop the redundant + sign
                } else {
                $sign = "+";
                }
            } else {
                $sign = "-";
            }

            // Drop the sign from the coefficient, as it is handled by $sign
            $coefficient = abs($coefficient);

            // Generate the $term string
            if ($power == 0) {
                $term = "{$sign} {$coefficient}"; // No x term if $power = 0
            } else {
                // Drop coefficients that equal 1 (and -1)
                if ($coefficient == 1) {
                    $coefficient = "";
                }
                $term = "{$sign} {$coefficient}x{$exponent} "; // Nonzero x term
            }

            // Add the current term to the polynomial
            $polynomial .= $term;
        }

        return $polynomial;
    }

    public function __invoke($x₀)
    {
        // Set object parameters as local variables so they can be used with the use function
        $degree = $this->degree;
        $coefficient = $this->coefficient;

        // Start with the zero polynomial
        $polynomial = function($x) {
            return 0;
        };

        // Iterate over each coefficient to create a callback function for each term
        for ($i = 0; $i < $degree + 1; $i++) {
            // Create a callback function for the current term
            $term = function ($x) use ($degree, $coefficient, $i) {
                return $coefficient[$i] * $x**($degree - $i);
            };
            // Add the new term to the polynomial
            $polynomial = Arithmetic::add($polynomial, $term);
        }

        return $polynomial($x₀);
    }
}
