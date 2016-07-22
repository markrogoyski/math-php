<?php
namespace Math\Functions;

use Math\Probability\Combinatorics;
use Math\Statistics\RandomVariable;

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
     * https://en.wikipedia.org/wiki/Gamma_function
     * https://en.wikipedia.org/wiki/Particular_values_of_the_Gamma_function
     *
     * For postive integers:
     *  Γ(n) = (n - 1)!
     *
     * For half integers:
     *
     *             _   (2n)!
     * Γ(½ + n) = √π  -------
     *                 4ⁿ n!
     *
     * For real numbers: use Lanczos approximation
     *
     * @param number $n
     *
     * @return number
     */
    public static function gamma($n)
    {
        // Basic integer/factorial cases
        if ($n == 0) {
            return \INF;
        }
        // Negative integer, or negative int as a float (Ex: from beta(-0.1, -0.9) since it will call Γ(x + y))
        if ((is_int($n) || is_numeric($n) && abs($n - round($n)) < 0.00001) && $n < 0) {
            return -\INF;
        }
        // Positive integer, or postive int as a float (Ex: from beta(0.1, 0.9) since it will call Γ(x + y))
        if ((is_int($n) || is_numeric($n) && abs($n - round($n)) < 0.00001) && $n > 0) {
            return Combinatorics::factorial(round($n) - 1);
        }

        // Half integer cases (determine if int + 0.5)
        if ((round($n * 2) / 2 / $n) == 1) {
            // Compute parts of equation
            $π     = \M_PI;
            $x     = round($n - 0.5, 0);
            $√π    = sqrt($π);
            $⟮2n⟯！  = Combinatorics::factorial(2 * $x);
            $⟮4ⁿn！⟯ = 4**$x * Combinatorics::factorial($x);

            /**
             * Put it all together
             *  _  (2n)!
             * √π -------
             *     4ⁿ n!
             */
            return $√π * ($⟮2n⟯！ / $⟮4ⁿn！⟯);
        }

        // Generic real number case
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
        // Negative integer, or negative int as a float
        if ((is_int($z) || is_numeric($z) && abs($z - round($z)) < 0.00001) && $z < 0) {
            return -\INF;
        }
        // Positive integer, or postive int as a float (Ex: from beta(0.1, 0.9) since it will call Γ(x + y))
        if ((is_int($z) || is_numeric($z) && abs($z - round($z)) < 0.00001) && $z > 0) {
            return Combinatorics::factorial(round($z) - 1);
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
        // Negative integer, or negative int as a float
        if ((is_int($n) || is_numeric($n) && abs($n - round($n)) < 0.00001) && $n < 0) {
            return -\INF;
        }
        // Positive integer, or postive int as a float
        if ((is_int($n) || is_numeric($n) && abs($n - round($n)) < 0.00001) && $n > 0) {
            return Combinatorics::factorial(round($n) - 1);
        }

        // Compute parts of equation
        $√2π                    = sqrt(2 * \M_PI);
        $ℯ⁻ⁿ                    = exp(-$n);
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

    /**
     * Beta function
     *
     * https://en.wikipedia.org/wiki/Beta_function
     *
     *           Γ(x)Γ(y)
     * B(x, y) = --------
     *           Γ(x + y)
     *
     * @param  int    $x
     * @param  int    $y
     * @return float
     */
    public static function beta($x, $y): float
    {
        if ($x == 0 || $y == 0) {
            return \INF;
        }

        $Γ⟮x⟯Γ⟮y⟯   = self::gamma($x) * self::gamma($y);
        $Γ⟮x ＋ y⟯ = self::gamma($x + $y);

        return $Γ⟮x⟯Γ⟮y⟯ / $Γ⟮x ＋ y⟯;
    }

    /**
     * Logistic function (logistic sigmoid function)
     * A logistic function or logistic curve is a common "S" shape (sigmoid curve).
     * https://en.wikipedia.org/wiki/Logistic_function
     *
     *             L
     * f(x) = -----------
     *        1 + ℯ⁻ᵏ⁽ˣ⁻ˣ⁰⁾
     *
     *
     * @param number $x₀ x-value of the sigmoid's midpoint
     * @param number $L  the curve's maximum value
     * @param number $k  the steepness of the curve
     * @param number $x
     *
     * @return float
     */
    public static function logistic($x₀, $L, $k, $x)
    {
        $ℯ⁻ᵏ⁽ˣ⁻ˣ⁰⁾ = exp(-$k * ($x - $x₀));

        return $L / (1 + $ℯ⁻ᵏ⁽ˣ⁻ˣ⁰⁾);
    }

    /**
     * Sigmoid function
     * A sigmoid function is a mathematical function having an "S" shaped curve (sigmoid curve).
     * Often, sigmoid function refers to the special case of the logistic function
     * https://en.wikipedia.org/wiki/Sigmoid_function
     *
     *           1
     * S(t) = -------
     *        1 + ℯ⁻ᵗ
     *
     * @param  number $t
     *
     * @return float
     */
    public static function sigmoid($t)
    {
        $ℯ⁻ᵗ = exp(-$t);

        return 1 / (1 + $ℯ⁻ᵗ);
    }
    
    /****************************************************************************
   * Lower Incomplete Gamma Function
   *
   * https://en.wikipedia.org/wiki/Incomplete_gamma_function#Lower_incomplete_Gamma_function
   *
   * This function is exact for all integer multiples of .5 using the recurrance relation:
   *                   γ⟮s+1,x⟯= s*γ⟮s,x⟯-xˢ*eˣ
   *
   * The function can be evaluated at other points using the series:
   *              zˢ     /         x          x²             x³            \
   * γ(s,x) =  -------- |    1 + ----- + ---------- + --------------- + ... |
   *            s * eˣ   \        s+1    (s+1)(s+2)   (s+1)(s+2)(s+3)      /
   */
    public static function lowerIncompleteGamma($s, $x)
    {
        if ($s == 1) {
            return 1 - exp(-1 * $x);
        }
        if ($s == .5) {
            $√π = sqrt(\M_PI);
            $√x = sqrt($x);
            return $√π * RandomVariable::erf($√x);
        }
        if (round($s * 2, 0) == $s * 2) {
            return ($s - 1) * self::lowerIncompleteGamma($s - 1, $x) - $x ** ($s - 1) * exp(-1 * $x);
        }
        $tol = .000000000001;
        $xˢ∕s∕eˣ = $x ** $s / exp($x) / $s;
        $sum = 1;
        $fractions = [];
        $element = 1 + $tol;
        while ($element > $tol) {
            $fractions[] = $x / ++$s;
            $element = array_product($fractions);
            $sum += $element;
        }
        return $xˢ∕s∕eˣ * $sum;
    }
  
    /***************
   * Regularized Incomplete Beta Function
   *
   * This function is exact for $a and $b values that are integer multiples
   * of 1/2.
   *
   * https://en.wikipedia.org/wiki/Beta_function#Incomplete_beta_function
   * See http://www.dtic.mil/dtic/tr/fulltext/u2/642495.pdf
   *
   * For other values, we use a continuous fraction
   * www.boost.org/doc/libs/1_35_0/libs/math/doc/sf_and_dist/html/math_toolkit/special/sf_beta/ibeta_function.html
   * The accuracy of the continuous fraction might be good enough to replace the 'exact' values, and simplify the code. 
   */
    public static function regularizedIncompleteBeta($x, $a, $b)
    {
        if ($x == 1) {
            return self::beta($a, $b);
        }
        $π = \M_PI;
        // If $a and $b are integer multiples of .5 we can caculate exact.
        if (($a * 2) == round($a * 2) && ($b * 2) == round($b * 2)) {
            if (is_int($a)) {
                //Equation 50 from paper
                $sum = 0;
                for ($i=1; $i<=$a; $i++) {
                    $sum += $x ** ($i-1) * self::gamma($b + $i - 1) / self::gamma($b) / self::gamma($i);
                }
                return 1 - (1 - $x) ** $b * $sum;
            } elseif ($b == .5) {
                if ($a == .5) {
                    //Equation 61 from paper
                    return 2 / $π * atan(sqrt($x / (1 - $x)));
                }
                //Equation 60a from paper
                $k = $a + .5;
                $sum = 0;
                for ($i=1; $i<=$k-1; $i++) {
                    $sum += $x ** ($i - 1) * self::gamma($i) / self::gamma($i + .5) / self::gamma(.5);
                }
                return self::regularizedIncompleteBeta($x, .5, .5) - sqrt($x - $x * $x) * $sum;
            } else {
                //Equation 59 from paper
                $sum = 0;
                $j = $b + .5;
                for ($i=1; $i<=$j-1; $i++) {
                    $sum += self::gamma($a + $i - .5) / self::gamma($a) / self::gamma($i + .5) * (1 - $x) ** ($i - 1);
                }
                return self::regularizedIncompleteBeta($x, $a, .5) + sqrt(1 - $x) * $x ** $a * $sum;
            }
        }
        if ($a > 1 && $b > 1) {
            // Tolerance on evaluating the continued fraction.
            $tol = .0000000000000000001;
            $dif = $tol + 1; // Initialize
            $m = 0; // Counter
            $constant = $x ** $a * (1 - $x) ** $b / self::beta($a, $b);
            $α_array = [];
            $β_array = [];
            do {
                if ($m == 0) {
                    $α = 1;
                } else {
                    $α = ($a + $m - 1) * ($a + $b + $m - 1) * $m * ($b - $m) * $x ** 2 / ($a + 2 * $m - 1) ** 2;
                }
                $β₁ = $m + $m * ($b - $m) * $x / ($a + 2 * $m - 1);
                $β₂ = ($a + $m) * ($a - ($a + $b) * $x + 1 + $m * (2 - $x)) / ($a + 2 * $m + 1);
                $β = $β₁ + $β₂;
                $α_array[] = $α;
                $β_array[] = $β;
                $fraction = 1;
                $fraction_array = [];
                $count = count($β_array);
                // There's probably a more efficient algorithn to do a continuous fraction.
                // I am calculating α and β at m=0, and then at m=1, and bulding the continuous
                // fraction up in the $fraction_array, such that when the array hits element 0,
                // it has included all the α and β in their respective arrays. Then I is evaluated.
                // If I has changed less than the tolerance, return the value. Otherwise add another
                // α and β, recaculate the fraction_array, and retest I.
                for ($i=$count-1; $i>=0; $i--) {
                    if ($i == $count - 1) {
                        $fraction_array[$i] = $α_array[$i] / $β_array[$i];
                    } else {
                        $fraction_array[$i] = $α_array[$i] / ($β_array[$i]+ $fraction_array[$i+1]);
                    }
                }
                $I_new = $constant * $fraction_array[0];
                if ($m > 0) {
                    $dif = abs($I - $I_new);
                }
                $I = $I_new;
                $m++;
            } while ($dif > $tol);
            echo count($fraction_array) . "\n";
            return $I;
        } else {
            if ($a <= 1) {
                // We shift a up by one, to the region that the continuous fraction works best.
                $offset = $x ** $a * (1 - $x) ** $b / $a / self::beta($a, $b);
                return self::regularizedIncompleteBeta($x, $a + 1, $b) + $offset;
            } else { //$b <= 1
                // We shift a up by one, to the region that the continuous fraction works best.
                $offset = $x ** $a * (1 - $x) ** $b / $b / self::beta($a, $b);
                return self::regularizedIncompleteBeta($x, $a, $b + 1) - $offset;
            }
        }
    }

}
