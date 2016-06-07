<?php
namespace Math\Functions;

use Math\Probability\Combinatorics;

class Special
{
    /**
     * Sign function (signum function) - sgn
     * Extracts the sign of a real number.
     * https://en.wikipedia.org/wiki/Sign_function
     *
     *          { -1 if x < 0
     * sgn(x) = {  0 if x = 0
     *          {  1 if x > 0
     *
     * @param number $x
     *
     * @return int
     */
    public static function signum(float $x): int
    {
        if ($x == 0) {
            return 0;
        }
        return $x < 0 ? -1 : 1;
    }

    /**
     * Sign function (signum function) - sgn
     * Convenience wrapper for signum function.
     */
    public static function sgn(float $x): int
    {
        return self::signum($x);
    }

    /**
     * Gamma function
     * Convenience wrapper for gammaLanczos
     * https://en.wikipedia.org/wiki/Gamma_function
     *
     * @param number $n
     *
     * @return number
     */
    public static function gamma($n)
    {
        return self::gammaLanczos($n);
    }

    /**
     * Gamma function - Lanczos' approximation
     * https://en.wikipedia.org/wiki/Gamma_function
     * https://en.wikipedia.org/wiki/Lanczos_approximation
     *
     * For postive integers:
     *  Γ(n) = (n - 1)!
     *
     * If z is < 0.5, use reflection formula:
     *
     *                   π
     *  Γ(1 - z)Γ(z) = ------
     *                 sin πz
     *
     *  therefore:
     *
     *                π
     *  Γ(z) = -----------------
     *         sin πz * Γ(1 - z)
     *
     * otherwise:
     *              __  /        1 \ z+½
     *  Γ(z + 1) = √2π | z + g + -  |    e^-(z+g+½) A(z)
     *                  \        2 /
     *
     *  use pre-computed p coefficients: g = 7, n = 9
     *
     * @param number $z
     *
     * @return number
     */
    public static function gammaLanczos($z)
    {
        // Basic integer/factorial cases
        if ($z == 0) {
            return \INF;
        }
        if (is_int($z) && $z < 0) {
            return -\INF;
        }
        if (is_int($z) && $z > 0) {
            return Combinatorics::factorial($z - 1);
        }

        // p coefficients: g = 7, n = 9
        $p = [
            0.99999999999980993227684700473478,
            676.520368121885098567009190444019,
            -1259.13921672240287047156078755283,
            771.3234287776530788486528258894,
            -176.61502916214059906584551354,
            12.507343278686904814458936853,
            -0.13857109526572011689554707,
            9.984369578019570859563e-6,
            1.50563273514931155834e-7,
        ];
        $g = 7;
        $π = \M_PI;

        /**
         * Use reflection formula when z < 0.5
         *                π
         *  Γ(z) = -----------------
         *         sin πz * Γ(1 - z)
         */
        if ($z < 0.5) {
            $Γ⟮1 − z⟯ = self::gammaLanczos(1 - $z);
            return $π / (sin($π * $z) * $Γ⟮1 − z⟯);
        }

        // Standard Lanczos formula when z ≥ 0.5

        // Compute A(z)
        $z--;
        $A⟮z⟯ = $p[0];
        for ($i = 1; $i < count($p); $i++) {
            $A⟮z⟯ += $p[$i] / ($z + $i);
        }

        // Compute parts of equation
        $√2π = sqrt(2 * $π);
        $⟮z ＋ g ＋½⟯ᶻ⁺½ = pow($z + $g + 0.5, $z + 0.5);
        $ℯ＾−⟮z ＋ g ＋½⟯ = exp(-($z + $g + 0.5));

        /**
         * Put it all together:
         *   __  /        1 \ z+½
         *  √2π | z + g + -  |    e^-(z+g+½) A(z)
         *       \        2 /
         */
        return $√2π * $⟮z ＋ g ＋½⟯ᶻ⁺½ * $ℯ＾−⟮z ＋ g ＋½⟯ * $A⟮z⟯;
    }

    /**
     * Gamma function - Stirling approximation
     * https://en.wikipedia.org/wiki/Gamma_function
     * https://en.wikipedia.org/wiki/Stirling%27s_approximation
     * https://www.wolframalpha.com/input/?i=Gamma(n)&lk=3
     *
     * For postive integers:
     *  Γ(n) = (n - 1)!
     *
     * For positive real numbers -- approximation:
     *                   ___
     *         __       / 1  /         1      \ n
     *  Γ(n)≈ √2π ℯ⁻ⁿ  /  - | n + ----------- |
     *                √   n  \    12n - 1/10n /
     *
     * @param number $n
     *
     * @return number
     */
    public static function gammaStirling($n)
    {
        // Basic integer/factorial cases
        if ($n == 0) {
            return \INF;
        }
        if (is_int($n) && $n < 0) {
            return -\INF;
        }
        if (is_int($n) && $n > 0) {
            return Combinatorics::factorial($n - 1);
        }

        // Compute parts of equation
        $√2π                    = sqrt(2 * \M_PI);
        $ℯ⁻ⁿ                    = pow(\M_E, -$n);
        $√1／n                  = sqrt(1 / $n);
        $⟮n ＋ 1／⟮12n − 1／10n⟯⟯ⁿ = pow($n + 1 / (12*$n - 1/(10*$n)), $n);

        /**
         * Put it all together:
         *                   ___
         *         __       / 1  /         1      \ n
         *  Γ(n)≈ √2π ℯ⁻ⁿ  /  - | n + ----------- |
         *                √   n  \    12n - 1/10n /
         */
        return $√2π * $ℯ⁻ⁿ * $√1／n * $⟮n ＋ 1／⟮12n − 1／10n⟯⟯ⁿ;
    }
}
