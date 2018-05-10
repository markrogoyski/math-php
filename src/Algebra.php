<?php
namespace MathPHP;

use MathPHP\Number\Complex;
use MathPHP\Functions\Map\Single;

class Algebra
{
    const ZERO_TOLERANCE = 0.000000000001;

    /**
     * Greatest common divisor - recursive Euclid's algorithm
     * The largest positive integer that divides the numbers without a remainder.
     * For example, the GCD of 8 and 12 is 4.
     * https://en.wikipedia.org/wiki/Greatest_common_divisor
     *
     * gcd(a, 0) = a
     * gcd(a, b) = gcd(b, a mod b)
     *
     * @param  int $a
     * @param  int $b
     *
     * @return int
     */
    public static function gcd(int $a, int $b): int
    {
        // Base cases
        if ($a == 0) {
            return $b;
        }
        if ($b == 0) {
            return $a;
        }

        // Recursive case
        return Algebra::gcd($b, $a % $b);
    }

    /**
     * Extended greatest common divisor
     * Compute the gcd as a multiple of the inputs:
     * gcd(a, b) = a*a' + b*b'
     * https://en.wikipedia.org/wiki/Extended_Euclidean_algorithm
     * Knuth, The Art of Computer Programming, Volume 2, 4.5.2 Algorithm X.
     *
     * @param  int $a
     * @param  int $b
     *
     * @return array [gcd, a', b']
     */
    public static function extendedGcd(int $a, int $b): array
    {
        // Base cases
        if ($a == 0) {
            return [$b, 0, 1];
        }
        if ($b == 0) {
            return [$a, 1, 0];
        }

        $x₂ = 1;
        $x₁ = 0;
        $y₂ = 0;
        $y₁ = 1;

        while ($b > 0) {
            $q  = intdiv($a, $b);
            $r  = $a % $b;
            $x  = $x₂ - ($q * $x₁);
            $y  = $y₂ - ($q * $y₁);
            $x₂ = $x₁;
            $x₁ = $x;
            $y₂ = $y₁;
            $y₁ = $y;
            $a  = $b;
            $b  = $r;
        }

        return [$a, $x₂, $y₂];
    }

    /**
     * Least common multiple
     * The smallest positive integer that is divisible by both a and b.
     * For example, the LCM of 5 and 2 is 10.
     * https://en.wikipedia.org/wiki/Least_common_multiple
     *
     *              |a ⋅ b|
     * lcm(a, b) = ---------
     *             gcd(a, b)
     *
     * @param  int $a
     * @param  int $b
     *
     * @return int
     */
    public static function lcm(int $a, int $b): int
    {
        // Special case
        if ($a === 0 || $b === 0) {
            return 0;
        }

        return abs($a * $b) / Algebra::gcd($a, $b);
    }

    /**
     * Get factors of an integer
     * The decomposition of a composite number into a product of smaller integers.
     * https://en.wikipedia.org/wiki/Integer_factorization
     *
     * Method:
     *  Iterate from 1 to √x
     *  If x mod i = 0, it is a factor
     *  Furthermore, x/i is a factor
     *
     * @param  int $x
     * @return array of factors
     */
    public static function factors(int $x): array
    {
        // 0 has infinite factors
        if ($x === 0) {
            return [\INF];
        }

        $x  = abs($x);
        $√x = floor(sqrt($x));

        $factors = [];
        for ($i = 1; $i <= $√x; $i++) {
            if ($x % $i === 0) {
                $factors[] = $i;
                if ($i !== $√x) {
                    $factors[] = $x / $i;
                }
            }
        }
        sort($factors);
        return $factors;
    }

    /**
     * Quadratic equation
     * An equation having the form: ax² + bx + c = 0
     * where x represents an unknown, or the root(s) of the equation,
     * and a, b, and c represent known numbers such that a is not equal to 0.
     * The numbers a, b, and c are the coefficients of the equation
     * https://en.wikipedia.org/wiki/Quadratic_equation
     *
     *           _______
     *     -b ± √b² -4ac
     * x = -------------
     *           2a
     *
     * Edge case where a = 0 and formula is not quadratic:
     *
     * 0x² + bx + c = 0
     *
     *     -c
     * x = ---
     *      b
     *
     * Note: If discriminant is negative, roots will be NAN.
     *
     * @param  number $a x² coefficient
     * @param  number $b x coefficient
     * @param  number $c constant coefficient
     * @param  bool $return_complex Whether to return complex numbers or NANs if imaginary roots
     *
     * @return array  [x₁, x₂]           roots of the equation, or
     *                [NAN, NAN]         if discriminant is negative, or
     *                [Complex, Complex] if discriminant is negative and complex option is on or
     *                [x]                if a = 0 and formula isn't quadratics
     *
     * @throws Exception\IncorrectTypeException
     */
    public static function quadratic($a, $b, $c, bool $return_complex = false): array
    {
        // Formula not quadratic (a = 0)
        if ($a === 0) {
            return [-$c / $b];
        }

        // Discriminant intermediate calculation and imaginary number check
        $⟮b² − 4ac⟯ = self::discriminant($a, $b, $c);
        if ($⟮b² − 4ac⟯ < 0) {
            if (!$return_complex) {
                return [\NAN, \NAN];
            }
            $complex = new Number\Complex(0, sqrt(-1 * $⟮b² − 4ac⟯));
            $x₁ = $complex->multiply(-1)->subtract($b)->divide(2 * $a);
            $x₂ = $complex->subtract($b)->divide(2 * $a);
        } else {
            // Standard quadratic equation case
            $√⟮b² − 4ac⟯ = sqrt(self::discriminant($a, $b, $c));
            $x₁         = (-$b - $√⟮b² − 4ac⟯) / (2*$a);
            $x₂         = (-$b + $√⟮b² − 4ac⟯) / (2*$a);
        }

        return [$x₁, $x₂];
    }

    /**
     * Discriminant
     * https://en.wikipedia.org/wiki/Discriminant
     *
     * Δ = b² - 4ac
     *
     * @param  number $a x² coefficient
     * @param  number $b x coefficient
     * @param  number $c constant coefficient
     *
     * @return number
     */
    public static function discriminant($a, $b, $c)
    {
        return $b**2 - (4 * $a * $c);
    }

    /**
     * Cubic equation
     * An equation having the form: z³ + a₂z² + a₁z + a₀ = 0
     * https://en.wikipedia.org/wiki/Cubic_function
     * http://mathworld.wolfram.com/CubicFormula.html
     *
     * The coefficient a₃ of z³ may be taken as 1 without loss of generality by dividing the entire equation through by a₃.
     *
     * If a₃ ≠ 0, then divide a₂, a₁, and a₀ by a₃.
     *
     *     3a₁ - a₂²
     * Q ≡ ---------
     *         9
     *
     *     9a₂a₁ - 27a₀ - 2a₂³
     * R ≡ -------------------
     *             54
     *
     * Polynomial discriminant D
     * D ≡ Q³ + R²
     *
     * If D > 0, one root is real, and two are are complex conjugates.
     * If D = 0, all roots are real, and at least two are equal.
     * If D < 0, all roots are real and unequal.
     *
     * If D < 0:
     *
     *                    R
     * Define θ = cos⁻¹  ----
     *                   √-Q³
     *
     * Then the real roots are:
     *
     *        __      /θ\
     * z₁ = 2√-Q cos | - | - ⅓a₂
     *                \3/
     *
     *        __      /θ + 2π\
     * z₂ = 2√-Q cos | ------ | - ⅓a₂
     *                \   3  /
     *
     *        __      /θ + 4π\
     * z₃ = 2√-Q cos | ------ | - ⅓a₂
     *                \   3  /
     *
     * If D = 0 or D > 0:
     *       ______
     * S ≡ ³√R + √D
     *       ______
     * T ≡ ³√R - √D
     *
     * If D = 0:
     *
     *      -a₂   S + T
     * z₁ = --- - -----
     *       3      2
     *
     *      S + T - a₂
     * z₂ = ----------
     *           3
     *
     *      -a₂   S + T
     * z₃ = --- - -----
     *       3      2
     *
     * If D > 0:
     *
     *      S + T - a₂
     * z₁ = ----------
     *           3
     *
     * z₂ = Complex conjugate; therefore, NAN
     * z₃ = Complex conjugate; therefore, NAN
     *
     * @param  number $a₃ z³         coefficient
     * @param  number $a₂ z²         coefficient
     * @param  number $a₁ z          coefficient
     * @param  number $a₀ constant coefficient
     * @param  bool $return_complex whether to return complex numbers
     *
     * @return array of roots (three real roots, or one real root and two NANs because complex numbers not yet supported)
     *                        (If $a₃ = 0, then only two roots of quadratic equation)
     *
     * @throws Exception\IncorrectTypeException
     */
    public static function cubic($a₃, $a₂, $a₁, $a₀, bool $return_complex = false): array
    {
        if ($a₃ === 0) {
            return self::quadratic($a₂, $a₁, $a₀);
        }

        // Take coefficient a₃ of z³ to be 1
        $a₂ = $a₂ / $a₃;
        $a₁ = $a₁ / $a₃;
        $a₀ = $a₀ / $a₃;

        // Intermediate variables
        $Q = (3*$a₁ - $a₂**2) / 9;
        $R = (9*$a₂*$a₁ - 27*$a₀ - 2*$a₂**3) / 54;

        // Polynomial discriminant
        $D = $Q**3 + $R**2;

        // All roots are real and unequal
        if ($D < 0) {
            $θ     = acos($R / sqrt((-$Q)**3));
            $２√−Q = 2 * sqrt(-$Q);
            $π     = \M_PI;

            $z₁    = $２√−Q * cos($θ / 3) - ($a₂ / 3);
            $z₂    = $２√−Q * cos(($θ + 2*$π) / 3) - ($a₂ / 3);
            $z₃    = $２√−Q * cos(($θ + 4*$π) / 3) - ($a₂ / 3);

            return [$z₁, $z₂, $z₃];
        }

        // Intermediate calculations
        $S = Arithmetic::cubeRoot($R + sqrt($D));
        $T = Arithmetic::cubeRoot($R - sqrt($D));

        // All roots are real, and at least two are equal
        if ($D === 0 || ($D > -self::ZERO_TOLERANCE && $D < self::ZERO_TOLERANCE)) {
            $z₁ = -$a₂ / 3 - ($S + $T) / 2;
            $z₂ = $S + $T - $a₂ / 3;
            $z₃ = -$a₂ / 3 - ($S + $T) / 2;

            return [$z₁, $z₂, $z₃];
        }

        // D > 0: One root is real, and two are are complex conjugates
        $z₁ = $S + $T - $a₂ / 3;

        if (!$return_complex) {
            return [$z₁, \NAN, \NAN];
        } else {
            $quad_a = 1;
            $quad_b = $a₂ + $z₁;
            $quad_c = $a₁ + $quad_b * $z₁;
            $complex_roots = self::quadratic($quad_a, $quad_b, $quad_c, true);
            return array_merge([$z₁], $complex_roots);
        }
    }

    /**
     * Quartic equation
     * An equation having the form: a₄z⁴ + a₃z³ + a₂z² + a₁z + a₀ = 0
     * https://en.wikipedia.org/wiki/Quartic_function
     *
     * @param  number $a₄ z⁴          coefficient
     * @param  number $a₃ z³          coefficient
     * @param  number $a₂ z²          coefficient
     * @param  number $a₁ z           coefficient
     * @param  number $a₀ constant coefficient
     * @param  bool $return_complex whether to return complex numbers
     *
     * @return array of roots
     *
     * @throws Exception\IncorrectTypeException
     */
    public static function quartic($a₄, $a₃, $a₂, $a₁, $a₀, bool $return_complex = false): array
    {
        // Not actually quartic.
        if ($a₄ === 0) {
            return self::cubic($a₃, $a₂, $a₁, $a₀, $return_complex);
        }

        // Take coefficient a₄ of z⁴ to be 1
        $a₃ = $a₃ / $a₄;
        $a₂ = $a₂ / $a₄;
        $a₁ = $a₁ / $a₄;
        $a₀ = $a₀ / $a₄;
        $a₄ = 1;

        // Has a zero root.
        if ($a₀ === 0) {
            return array_merge([0], self::cubic($a₄, $a₃, $a₂, $a₁, $return_complex));
        }
        
        // Is Biquadratic
        if ($a₃ == 0 && $a₁ == 0) {
            $quadratic_roots = self::quadratic($a₄, $a₂, $a₀, $return_complex);

            // Sort so any complex roots are at the end of the array.
            rsort($quadratic_roots);
            $z₊ = $quadratic_roots[0];
            $z₋ = $quadratic_roots[1];
            if (!$return_complex) {
                return [sqrt($z₊), -1 * sqrt($z₊), sqrt($z₋), -1 * sqrt($z₋)];
            } else {
                $Cz₊ = new Complex($z₊, 0);
                $Cz₋ = new Complex($z₋, 0);
                $z₁ = $z₊ < 0 ? $Cz₊->sqrt()  : sqrt($z₊);
                $z₂ = $z₊ < 0 ? $z₁->negate() : $z₁ * -1;
                $z₃ = $z₋ < 0 ? $Cz₋->sqrt()  : sqrt($z₋);
                $z₄ = $z₋ < 0 ? $z₃->negate() : $z₃ * -1;
                return [$z₁, $z₂, $z₃, $z₄];
            }
        }
        
        // Is a depressed quartic
        // y⁴ + py² + qy + r = 0
        if ($a₃ == 0) {
            $p = $a₂;
            $q = $a₁;
            $r = $a₀;
            // Create the resolvent cubic.
            // 8m³ + 8pm² + (2p² - 8r)m - q² = 0
            $cubic_roots = self::cubic(8, 8 * $p, 2 * $p ** 2 - 8 * $r, -1 * $q ** 2);
            
            // $z₁ will always be a real number, so select it.
            $m             = $cubic_roots[0];
            $roots1        = self::quadratic(1, sqrt(2*$m), $p / 2 + $m - $q/2/sqrt(2*$m), $return_complex);
            $roots2        = self::quadratic(1, -1 * sqrt(2*$m), $p / 2 + $m + $q/2/sqrt(2*$m), $return_complex);
            $discriminant1 = self::discriminant(1, sqrt(2*$m), $p / 2 + $m - $q/2/sqrt(2*$m));
            $discriminant2 = self::discriminant(1, -1 * sqrt(2*$m), $p / 2 + $m + $q/2/sqrt(2*$m));
            
            // sort the real roots first.
            $sorted_results = $discriminant1>$discriminant2 ? array_merge($roots1, $roots2) : array_merge($roots2, $roots1);
            return $sorted_results;
        }

        // Create the factors for a depressed quartic.
        $p = $a₂ - (3 * $a₃ ** 2 / 8);
        $q = $a₁ + $a₃ ** 3 / 8 - $a₃ * $a₂ / 2;
        $r = $a₀ - 3 * $a₃ ** 4 / 256 + $a₃ ** 2 * $a₂ / 16 - $a₃ * $a₁ / 4;
        
        $depressed_quartic_roots = self::quartic(1, 0, $p, $q, $r, $return_complex);
        
        // The roots for this polynomial are the roots of the depressed polynomial minus a₃/4.
        if (!$return_complex) {
            return Single::subtract($depressed_quartic_roots, $a₃ / 4);
        } else {
            $quartic_roots = [];
            foreach ($depressed_quartic_roots as $key => $root) {
                if (is_float($root)) {
                    $quartic_roots[$key] = $root - $a₃ / 4;
                } else {
                    $quartic_roots[$key] = $root->subtract($a₃ / 4);
                }
            }
            return $quartic_roots;
        }
    }
}
