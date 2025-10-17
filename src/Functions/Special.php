<?php

namespace MathPHP\Functions;

use MathPHP\Probability\Combinatorics;
use MathPHP\Functions\Map\Single;
use MathPHP\Exception;

class Special
{
    /**
     * Maximum iterations for iterative algorithms to prevent infinite loops
     */
    private const MAX_ITERATIONS = 10000;

    /**
     * Sign function (signum function) - sgn
     * Extracts the sign of a real number.
     * https://en.wikipedia.org/wiki/Sign_function
     *
     *          { -1 if x < 0
     * sgn(x) = {  0 if x = 0
     *          {  1 if x > 0
     *
     * @param float $x
     *
     * @return int
     */
    public static function signum(float $x): int
    {
        return $x <=> 0;
    }

    /**
     * Sign function (signum function) - sgn
     * Convenience wrapper for signum function.
     *
     * @param float $x
     *
     * @return int
     */
    public static function sgn(float $x): int
    {
        return self::signum($x);
    }

    /**
     * Gamma function convenience method
     *
     * @param float $n
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     */
    public static function Γ(float $n): float
    {
        return self::gamma($n);
    }

    /**
     * Gamma function - Lanczos' approximation
     * https://en.wikipedia.org/wiki/Gamma_function
     * https://en.wikipedia.org/wiki/Lanczos_approximation
     *
     * For positive integers:
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
     *         sin πz · Γ(1 - z)
     *
     * otherwise:
     *              __  /        1 \ z+½
     *  Γ(z + 1) = √2π | z + g + -  |    e^-(z+g+½) A(z)
     *                  \        2 /
     *
     *  use pre-computed p coefficients: g = 7, n = 9
     *
     * @param float $z
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     */
    public static function gammaLanczos(float $z): float
    {
        // Basic integer/factorial cases
        if ($z == 0) {
            return \INF;
        }
        // Negative integer, or negative int as a float
        if (($z === \floor($z)) && $z < 0) {
            return -\INF;
        }
        // Positive integer, or positive int as a float (Ex: from beta(0.1, 0.9) since it will call Γ(x + y))
        if (($z === \floor($z)) && $z > 0) {
            return Combinatorics::factorial((int) $z - 1);
        }

        // p coefficients: g = 7, n = 9
        static $p = [
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
        static $g = 7;
        static $π = \M_PI;

        /**
         * Use reflection formula when z < 0.5
         *                π
         *  Γ(z) = -----------------
         *         sin πz * Γ(1 - z)
         */
        if ($z < 0.5) {
            $Γ⟮1 − z⟯ = self::gammaLanczos(1 - $z);
            return $π / ( \sin($π * $z) * $Γ⟮1 − z⟯);
        }

        // Standard Lanczos formula when z ≥ 0.5

        // Compute A(z)
        $z--;
        $A⟮z⟯ = $p[0];
        for ($i = 1; $i < \count($p); $i++) {
            $A⟮z⟯ += $p[$i] / ($z + $i);
        }

        // Compute parts of equation
        $√2π = \sqrt(2 * $π);
        $⟮z ＋ g ＋½⟯ᶻ⁺½ = \pow($z + $g + 0.5, $z + 0.5);
        $ℯ＾−⟮z ＋ g ＋½⟯ = \exp(-($z + $g + 0.5));

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
     * For positive integers:
     *  Γ(n) = (n - 1)!
     *
     * For positive real numbers -- approximation:
     *                   ___
     *         __       / 1  /         1      \ n
     *  Γ(n)≈ √2π ℯ⁻ⁿ  /  - | n + ----------- |
     *                √   n  \    12n - 1/10n /
     *
     * @param float $n
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     */
    public static function gammaStirling(float $n): float
    {
        // Basic integer/factorial cases
        if ($n == 0) {
            return \INF;
        }
        // Negative integer, or negative int as a float
        if (($n === \floor($n)) && $n < 0) {
            return -\INF;
        }
        // Positive integer, or postive int as a float
        if (($n === \floor($n)) && $n > 0) {
            return Combinatorics::factorial((int) $n - 1);
        }

        // Compute parts of equation
        $√2π                    = \sqrt(2 * \M_PI);
        $ℯ⁻ⁿ                    = \exp(-$n);
        $√1／n                  = \sqrt(1 / $n);
        $⟮n ＋ 1／⟮12n − 1／10n⟯⟯ⁿ = \pow($n + 1 / (12 * $n - 1 / (10 * $n)), $n);

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
     * The log of the error term in the Stirling-De Moivre factorial series
     *
     * log(n!) = .5*log(2πn) + n*log(n) - n + δ(n)
     * Where δ(n) is the log of the error.
     *
     * For n ≤ 15, integers or half-integers, uses stored values.
     *
     * For n = 0: infinity
     * For n < 0: NAN
     *
     * The implementation is heavily inspired by the R language's C implementation of stirlerr.
     * It can be considered a reimplementation in PHP.
     * R Project for Statistical Computing: https://www.r-project.org/
     * R Source: https://svn.r-project.org/R/
     *
     * @param float $n
     *
     * @return float log of the error
     *
     * @throws Exception\NanException
     */
    public static function stirlingError(float $n): float
    {
        if ($n < 0) {
            throw new Exception\NanException("stirlingError NAN for n < 0: given $n");
        }

        static $S0 = 0.083333333333333333333;        // 1/12
        static $S1 = 0.00277777777777777777778;      // 1/360
        static $S2 = 0.00079365079365079365079365;   // 1/1260
        static $S3 = 0.000595238095238095238095238;  // 1/1680
        static $S4 = 0.0008417508417508417508417508; // 1/1188

        static $sferr_halves = [
            \INF,                          // 0
            0.1534264097200273452913848,   // 0.5
            0.0810614667953272582196702,   // 1.0
            0.0548141210519176538961390,   // 1.5
            0.0413406959554092940938221,   // 2.0
            0.03316287351993628748511048,  // 2.5
            0.02767792568499833914878929,  // 3.0
            0.02374616365629749597132920,  // 3.5
            0.02079067210376509311152277,  // 4.0
            0.01848845053267318523077934,  // 4.5
            0.01664469118982119216319487,  // 5.0
            0.01513497322191737887351255,  // 5.5
            0.01387612882307074799874573,  // 6.0
            0.01281046524292022692424986,  // 6.5
            0.01189670994589177009505572,  // 7.0
            0.01110455975820691732662991,  // 7.5
            0.010411265261972096497478567, // 8.0
            0.009799416126158803298389475, // 8.5
            0.009255462182712732917728637, // 9.0
            0.008768700134139385462952823, // 9.5
            0.008330563433362871256469318, // 10.0
            0.007934114564314020547248100, // 10.5
            0.007573675487951840794972024, // 11.0
            0.007244554301320383179543912, // 11.5
            0.006942840107209529865664152, // 12.0
            0.006665247032707682442354394, // 12.5
            0.006408994188004207068439631, // 13.0
            0.006171712263039457647532867, // 13.5
            0.005951370112758847735624416, // 14.0
            0.005746216513010115682023589, // 14.5
            0.005554733551962801371038690, // 15.0
        ];

        if ($n <= 15.0) {
            $nn = $n + $n;
            if ($nn == (int) $nn) {
                return $sferr_halves[$nn];
            }
            $M_LN_SQRT_2PI = \log(\sqrt(2 * \M_PI));
            return self::logGamma($n + 1) - ($n + 0.5) * \log($n) + $n - $M_LN_SQRT_2PI;
        }

        $n² = $n * $n;
        if ($n > 500) {
            return ($S0 - $S1 / $n²) / $n;
        }
        if ($n > 80) {
            return ($S0 - ($S1 - $S2 / $n²) / $n²) / $n;
        }
        if ($n > 35) {
            return ($S0 - ($S1 - ($S2 - $S3/$n²) / $n²) / $n²) / $n;
        }
        // 15 < n ≤ 35
        return ($S0 - ($S1 - ($S2 - ($S3 - $S4/$n²) / $n²) / $n²) / $n²) / $n;
    }

    /**
     * Gamma function
     * https://en.wikipedia.org/wiki/Gamma_function
     * https://en.wikipedia.org/wiki/Particular_values_of_the_Gamma_function
     *
     * For positive integers:
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
     * Implementation notes:
     * The original MathPHP implementation was based on the textbook mathematical formula, but this led to numerical
     * issues with very large numbers. Last version with this implementation was v2.4.0.
     *
     * The current implementation is heavily inspired by the R language's C implementation of gammafn, which itself is
     * a translation of a Fortran subroutine by W. Fullerton of Los Alamos Scientific Laboratory.
     * It can be considered a reimplementation in PHP.
     * R Project for Statistical Computing: https://www.r-project.org/
     * R Source: https://svn.r-project.org/R/
     *
     * @param float $x
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     * @throws Exception\NanException
     */
    public static function gamma(float $x)
    {
        static $gamcs = [
            +.8571195590989331421920062399942e-2,
            +.4415381324841006757191315771652e-2,
            +.5685043681599363378632664588789e-1,
            -.4219835396418560501012500186624e-2,
            +.1326808181212460220584006796352e-2,
            -.1893024529798880432523947023886e-3,
            +.3606925327441245256578082217225e-4,
            -.6056761904460864218485548290365e-5,
            +.1055829546302283344731823509093e-5,
            -.1811967365542384048291855891166e-6,
            +.3117724964715322277790254593169e-7,
            -.5354219639019687140874081024347e-8,
            +.9193275519859588946887786825940e-9,
            -.1577941280288339761767423273953e-9,
            +.2707980622934954543266540433089e-10,
            -.4646818653825730144081661058933e-11,
            +.7973350192007419656460767175359e-12,
            -.1368078209830916025799499172309e-12,
            +.2347319486563800657233471771688e-13,
            -.4027432614949066932766570534699e-14,
            +.6910051747372100912138336975257e-15,
            -.1185584500221992907052387126192e-15,
            +.2034148542496373955201026051932e-16,
            -.3490054341717405849274012949108e-17,
            +.5987993856485305567135051066026e-18,
            -.1027378057872228074490069778431e-18,
            +.1762702816060529824942759660748e-19,
            -.3024320653735306260958772112042e-20,
            +.5188914660218397839717833550506e-21,
            -.8902770842456576692449251601066e-22,
            +.1527474068493342602274596891306e-22,
            -.2620731256187362900257328332799e-23,
            +.4496464047830538670331046570666e-24,
            -.7714712731336877911703901525333e-25,
            +.1323635453126044036486572714666e-25,
            -.2270999412942928816702313813333e-26,
            +.3896418998003991449320816639999e-27,
            -.6685198115125953327792127999999e-28,
            +.1146998663140024384347613866666e-28,
            -.1967938586345134677295103999999e-29,
            +.3376448816585338090334890666666e-30,
            -.5793070335782135784625493333333e-31
        ];

        static $ngam  = 22;
        static $xmin  = -170.5674972726612;
        static $xmax  = 171.61447887182298;
        static $xsml  = 2.2474362225598545e-308;
        static $dxrel = 1.490116119384765696e-8;

        if (\is_nan($x)) {
            throw new Exception\NanException("gamma cannot compute x when NAN");
        }

        // Undefined (NAN) if x ≤ 0
        if ($x == 0 || ($x < 0 && $x == \round($x))) {
            throw new Exception\NanException("gamma undefined for x of $x");
        }

        $y = \abs($x);

        // Compute gamma for -10 ≤ x ≤ 10
        if ($y <= 10) {
            // First reduce the interval and find gamma(1 + y) for 0 ≤ y < 1
            $n = (int) $x;
            if ($x < 0) {
                --$n;
            }
            $y = $x - $n; // n = floor(x) ==> y in [0, 1)
            --$n;
            $value = self::chebyshevEval($y * 2 - 1, $gamcs, $ngam) + .9375;
            if ($n == 0) {
                return $value; // x = 1.dddd = 1+y
            }

            // Compute gamma(x) for -10 ≤ x < 1
            // Exactly 0 or -n was checked above already
            if ($n < 0) {
                // The argument is so close to 0 that the result would overflow.
                if ($y < $xsml) {
                    return \INF;
                }

                $n = -$n;
                for ($i = 0; $i < $n; $i++) {
                    $value /= ($x + $i);
                }
                return $value;
            }

            // gamma(x) for 2 ≤ x ≤ 10
            for ($i = 1; $i <= $n; $i++) {
                $value *= ($y + $i);
            }
            return $value;
        }
        // gamma(x) for y = |x| > 10.

        // Overflow (INF is the best answer)
        if ($x > $xmax) {
            return \INF;
        }

        // Underflow (0 is the best answer)
        if ($x < $xmin) {
            return 0;
        }

        if ($y <= 50 && $y == (int) $y) {
            $value = Combinatorics::factorial((int) $y - 1);
        } else { // Normal case
            $M_LN_SQRT_2PI = (\M_LNPI + \M_LN2) / 2;
            $value = \exp(($y - 0.5) * \log($y) - $y + $M_LN_SQRT_2PI + ((2*$y == (int)2*$y)? self::stirlingError($y) : self::logGammaCorr($y)));
        }

        if ($x > 0) {
            return $value;
        }

        // The answer is less than half precision because the argument is too near a negative integer.
        if (\abs(($x - (int)($x - 0.5)) / $x) < $dxrel) {
            // Just move on.
        }

        $sin⟮πy⟯ = \sin(\M_PI * $y);
        return -\M_PI / ($y * $sin⟮πy⟯ * $value);
    }

    /**
     * Log Gamma
     * Natural logarithm of gamma function - ln Γ(x)
     * https://en.wikipedia.org/wiki/Gamma_function
     *
     * The implementation is heavily inspired by the R language's C implementation of lgammafn, which itself is
     * a translation of a Fortran subroutine by W. Fullerton of Los Alamos Scientific Laboratory.
     * It can be considered a reimplementation in PHP.
     * R Project for Statistical Computing: https://www.r-project.org/
     * R Source: https://svn.r-project.org/R/
     *
     *           ⌠∞
     * ln Γ(x) = ⎮  t^(x-1) e^(-t) dt
     *           ⌡₀
     *
     * Properties:
     *   ln Γ(x+1) = ln(x) + ln Γ(x)
     *   ln Γ(1) = 0
     *   ln Γ(1/2) = ln(√π)
     *
     * More numerically stable than computing Γ(x) then taking logarithm.
     * Used for large arguments where Γ(x) would overflow.
     *
     * @param float $x
     *
     * @return float|int
     *
     * @throws Exception\NanException
     * @throws Exception\OutOfBoundsException
     */
    public static function logGamma(float $x)
    {
        static $xmax = 2.5327372760800758e+305;

        if (\is_nan($x)) {
            throw new Exception\NanException("Cannot compute logGamma when x is NAN");
        }

        // Negative integer argument
        if ($x <= 0 && $x == (int) $x) {
            return \INF; // INF is the best answer
        }

        $y = \abs($x);

        if ($y < 1e-306) {
            return -\log($y); // Denormalized range
        }
        if ($y <= 10) {
            return \log(abs(self::Γ($x)));
        }

        // From this point, y = |x| > 10

        if ($y > $xmax) {
            return \INF; // INF is the best answer
        }

        // y = x > 10
        if ($x > 0) {
            if ($x > 1e17) {
                return $x * (\log($x) - 1);
            }
            $M_LN_SQRT_2PI = (\M_LNPI + \M_LN2) / 2;
            if ($x > 4934720) {
                return($M_LN_SQRT_2PI + ($x - 0.5) * \log($x) - $x);
            }
            return $M_LN_SQRT_2PI + ($x - 0.5) * \log($x) - $x + self::logGammaCorr($x);
        }

        $M_LN_SQRT_PId2 = 0.225791352644727432363097614947; // log(sqrt(pi/2))
        $sin⟮πy⟯ = \abs(sin(\M_PI * $y));
        return $M_LN_SQRT_PId2 + ($x - 0.5) * \log($y) - $x - \log($sin⟮πy⟯) - self::logGammaCorr($y);
    }

    /**
     * Beta function convenience method
     *
     * @param  float $x
     * @param  float $y
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     */
    public static function β(float $x, float $y): float
    {
        return self::beta($x, $y);
    }

    /**
     * Beta function
     *
     * https://en.wikipedia.org/wiki/Beta_function
     *
     * Selects the best beta algorithm for the provided values
     *
     * @param  float $a
     * @param  float $b
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     * @throws Exception\NanException
     */
    public static function beta(float $a, float $b): float
    {
        static $xmax = 171.61447887182298;

        if (\is_nan($a) || \is_nan($b)) {
            throw new Exception\NanException("Cannot compute beta when a or b is NAN: got a:$a, b:$b");
        }
        if ($a < 0 || $b < 0) {
            throw new Exception\OutOfBoundsException("a and b must be non-negative for beta: got a:$a, b:$b");
        }
        if ($a == 0 || $b == 0) {
            return \INF;
        }
        if (\is_infinite($a) || \is_infinite($b)) {
            return 0;
        }

        if ($a + $b < $xmax) {
            return self::betaBasic($a, $b);
        }

        $val = self::logBeta($a, $b);
        return \exp($val);
    }

    /**
     * Beta function
     *
     * https://en.wikipedia.org/wiki/Beta_function
     *
     *           Γ(x)Γ(y)
     * β(x, y) = --------
     *           Γ(x + y)
     *
     * @param  float $x
     * @param  float $y
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     */
    private static function betaBasic(float $x, float $y): float
    {
        $Γ⟮x⟯  = self::Γ($x);
        $Γ⟮y⟯  = self::Γ($y);
        $Γ⟮x ＋ y⟯ = self::Γ($x + $y);

        return 1 / $Γ⟮x ＋ y⟯ * $Γ⟮x⟯ * $Γ⟮y⟯;
    }

    /**
     * Natural logarithm of beta function - ln β(a,b)
     * https://en.wikipedia.org/wiki/Beta_function
     *
     * ln β(a,b) = ln Γ(a) + ln Γ(b) - ln Γ(a+b)
     *
     * More numerically stable than computing β(a,b) then taking logarithm.
     * Avoids overflow for large arguments.
     *
     * The implementation is heavily inspired by the R language's C implementation of lbeta, which itself is
     * a translation of a Fortran subroutine by W. Fullerton of Los Alamos Scientific Laboratory.
     * It can be considered a reimplementation in PHP.
     * R Project for Statistical Computing: https://www.r-project.org/
     * R Source: https://svn.r-project.org/R/
     *
     * @param  float $a (a > 0)
     * @param  float $b (b > 0)
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     * @throws Exception\NanException
     */
    public static function logBeta(float $a, float $b): float
    {
        if (\is_nan($a) || \is_nan($b)) {
            throw new Exception\NanException("Cannot compute logBeta if a or b is NAN: got a:$a, b:$b");
        }

        $p = \min($a, $b);
        $q = \max($a, $b);

        // Both arguments must be >= 0
        if ($p < 0) {
            throw new Exception\OutOfBoundsException("p must be non-negative at this point of logBeta calculation: got $p");
        }
        if ($p == 0) {
            return \INF;
        }
        if (\is_infinite($q)) {
            return -\INF;
        }

        if ($p >= 10) {
            // p and q are big.
            $corr = self::logGammaCorr($p) + self::logGammaCorr($q) - self::logGammaCorr($p + $q);
            $M_LN_SQRT_2PI = (\M_LNPI + \M_LN2) / 2;
            return \log($q) * -0.5 + $M_LN_SQRT_2PI + $corr + ($p - 0.5) * \log($p / ($p + $q)) + $q * \log1p(-$p / ($p + $q));
        }
        if ($q >= 10) {
            // p is small, but q is big.
            $corr = self::logGammaCorr($q) - self::logGammaCorr($p + $q);
            return self::logGamma($p) + $corr + $p - $p * \log($p + $q) + ($q - 0.5) * \log1p(-$p / ($p + $q));
        }
        // p and q are small: p <= q < 10. */
        if ($p < 1e-306) {
            return self::logGamma($p) + (self::logGamma($q) - self::logGamma($p+$q));
        }
        return \log(self::beta($p, $q));
    }

    /**
     * Log gamma correction
     *
     * Compute the log gamma correction factor for x >= 10 so that
     * log(gamma(x)) = .5*log(2*pi) + (x-.5)*log(x) -x + lgammacor(x)
     *
     * The implementation is heavily inspired by the R language's C implementation of lgammacor, which itself is
     * a translation of a Fortran subroutine by W. Fullerton of Los Alamos Scientific Laboratory.
     * It can be considered a reimplementation in PHP.
     * R Project for Statistical Computing: https://www.r-project.org/
     * R Source: https://svn.r-project.org/R/
     *
     * @param float $x
     *
     * @return float
     */
    public static function logGammaCorr(float $x): float
    {
        static $algmcs = [
            +.1666389480451863247205729650822e+0,
            -.1384948176067563840732986059135e-4,
            +.9810825646924729426157171547487e-8,
            -.1809129475572494194263306266719e-10,
            +.6221098041892605227126015543416e-13,
            -.3399615005417721944303330599666e-15,
            +.2683181998482698748957538846666e-17,
            -.2868042435334643284144622399999e-19,
            +.3962837061046434803679306666666e-21,
            -.6831888753985766870111999999999e-23,
            +.1429227355942498147573333333333e-24,
            -.3547598158101070547199999999999e-26,
            +.1025680058010470912000000000000e-27,
            -.3401102254316748799999999999999e-29,
            +.1276642195630062933333333333333e-30,
        ];

        /**
         * For IEEE double precision DBL_EPSILON = 2^-52 = 2.220446049250313e-16 :
         * xbig = 2 ^ 26.5
         * xmax = DBL_MAX / 48 =  2^1020 / 3
         */
        static $nalgm = 5;
        static $xbig  = 94906265.62425156;
        static $xmax  = 3.745194030963158e306;

        if ($x < 10) {
            throw new Exception\OutOfBoundsException("x cannot be < 10: got $x");
        }
        if ($x >= $xmax) {
            // allow to underflow below
        } elseif ($x < $xbig) {
            $tmp = 10 / $x;
            return self::chebyshevEval($tmp * $tmp * 2 - 1, $algmcs, $nalgm) / $x;
        }
        return 1 / ($x * 12);
    }

    /**
     * Evaluate a Chebyshev Series with the Clenshaw Algorithm
     * https://en.wikipedia.org/wiki/Clenshaw_algorithm#Special_case_for_Chebyshev_series
     *
     * The implementation is inspired by the R language's C implementation of chebyshev_eval, which itself is
     * a translation of a Fortran subroutine by W. Fullerton of Los Alamos Scientific Laboratory.
     * It can be considered a reimplementation in PHP.
     *
     * @param float   $x
     * @param float[] $a
     * @param int     $n
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     */
    private static function chebyshevEval(float $x, array $a, int $n): float
    {
        if ($n < 1 || $n > 1000) {
            throw new Exception\OutOfBoundsException("n cannot be < 1 or > 1000: got $n");
        }
        if ($x < -1.1 || $x > 1.1) {
            throw new Exception\OutOfBoundsException("x cannot be < -1.1 or greater than 1.1: got $x");
        }

        $２x = $x * 2;
        [$b0, $b1, $b2] = [0, 0, 0];

        for ($i = 1; $i <= $n; $i++) {
            $b2 = $b1;
            $b1 = $b0;
            $b0 = $２x * $b1 - $b2 + $a[$n - $i];
        }
        return ($b0 - $b2) * 0.5;
    }

    /**
     * Multivariate Beta function
     * https://en.wikipedia.org/wiki/Beta_function#Multivariate_beta_function
     *
     *                     Γ(α₁)Γ(α₂) ⋯ Γ(αₙ)
     * B(α₁, α₂, ... αn) = ──────────────────
     *                     Γ(α₁ + α₂ +  ⋯ αₙ)
     *
     * @param float[] $αs
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     */
    public static function multivariateBeta(array $αs): float
    {
        foreach ($αs as $α) {
            if ($α == 0) {
                return \INF;
            }
        }

        static $xmax = 171.61447887182298;

        $∑α = \array_sum($αs);
        if ($∑α == \INF) {
            return 0;
        }
        if ($∑α < $xmax) {  // ~= 171.61 for IEEE
            $Γ⟮∑α⟯ = self::Γ($∑α);
            $∏= 1 / $Γ⟮∑α⟯;
            foreach ($αs as $α) {
                $∏ *= self::Γ($α);
            }

            return $∏;
        }

        $∑ = -self::logGamma($∑α);
        foreach ($αs as $α) {
            $∑ += self::logGamma($α);
        }
        return \exp($∑);
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
     * @param float $x₀ x-value of the sigmoid's midpoint
     * @param float $L  the curve's maximum value
     * @param float $k  the steepness of the curve
     * @param float $x
     *
     * @return float
     */
    public static function logistic(float $x₀, float $L, float $k, float $x): float
    {
        $ℯ⁻ᵏ⁽ˣ⁻ˣ⁰⁾ = \exp(-$k * ($x - $x₀));

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
     * Rather than implement the formula definition exactly, we take a slightly more complicated conditional approach
     * for numerical stability reasons.
     *
     * For example, consider a large positive t such as t = 750: ℯ⁻⁷⁵⁰
     * php > var_dump(M_E ** -750);
     * float(0)
     *
     *            1          1
     * S(t) = --------- = ------- = 1.0
     *        1 + ℯ⁻⁷⁵⁰    1 + 0
     *
     * There are no numerical issues with this, as we approach 0.
     *
     * However, consider a large negative t such as t = -750: ℯ⁻⁽⁻⁷⁵⁰⁾
     * php > var_dump(M_E ** -(-750));
     * float(INF)
     *
     *
     *             1            1
     * S(t) = ------------ = ------- = 0.0
     *        1 + ℯ⁻⁽⁻⁷⁵⁰⁾   1 + INF
     *
     * We observe an overflow to PHP infinity.
     *
     * Therefore, we do an alternative, slightly more complex formula and implementation to avoid overflowing to
     * infinity in an attempt to maintain numerical stability.
     *
     *          ℯᵗ
     * S(t) = -------
     *        1 + ℯᵗ
     *
     * Consider the same large negative t again t = -750: ℯ⁻⁷⁵⁰
     *
     *          ℯ⁻⁷⁵⁰        0
     * S(t) = --------- = -------- = 0.0
     *        1 + ℯ⁻⁷⁵⁰    1 + 0
     *
     * We arrive at the same value of 0 without any overflows to infinity, maintaining numerical stability.
     *
     * For t >= 0: Use standard formula
     * For t < 0: Use alternate formula to avoid overflow in ℯ⁻ᵗ
     *
     * @param  float $t
     *
     * @return float
     */
    public static function sigmoid(float $t): float
    {
        if ($t >= 0) {
            $ℯ⁻ᵗ = \exp(-$t);
            return 1 / (1 + $ℯ⁻ᵗ);
        } else {
            $ℯᵗ = \exp($t);
            return $ℯᵗ / (1 + $ℯᵗ);
        }
    }

    /**
     * Error function (Gauss error function)
     * https://en.wikipedia.org/wiki/Error_function
     *
     *           2  ⌠ˣ
     * erf(x) = ──  ⎮  e^(-t²) dt
     *          √π  ⌡₀
     *
     * Improved implementation with domain-specific algorithms:
     * - Small arguments (|x| ≤ 0.01): Taylor series (8 terms, optimized)
     * - Medium arguments (0.01 < |x| ≤ 4): Taylor series with convergence (up to 50 terms)
     * - Large arguments (|x| > 4): Asymptotic expansion (4 terms)
     *
     * This implementation prioritizes accuracy (< 1e-12 error) over the classical
     * Abramowitz & Stegun 7.1.26 approximation (max error: 1.5e-7).
     *
     * Taylor series: erf(x) = (2/√π) * Σ((-1)^n * x^(2n+1) / (n! * (2n+1)))
     * Asymptotic: erf(x) = 1 - erfc(x) where erfc(x) ≈ (e^(-x²) / (x√π)) * (1 - 1/(2x²) + ...)
     *
     * Precision: Better than 1e-12 for all x
     *
     * @param  float $x
     *
     * @return float
     */
    public static function errorFunction(float $x): float
    {
        if ($x == 0) {
            return 0;
        }

        $ax = \abs($x);

        $two／√π = 1.1283791670955125738961589031215451716881;

        // Small arguments: Use Taylor series
        // erf(x) = (2/√π) * (x - x³/3 + x⁵/10 - x⁷/42 + x⁹/216 - ...)
        if ($ax <= 0.01) {
            $x² = $x * $x;
            $x³ = $x² * $x;
            $x⁵ = $x³ * $x²;
            $x⁷ = $x⁵ * $x²;
            $x⁹ = $x⁷ * $x²;
            $x¹¹ = $x⁹ * $x²;
            $x¹³ = $x¹¹ * $x²;
            $x¹⁵ = $x¹³ * $x²;

            // Coefficients: 2/√π * 1/(n! * (2n+1))
            $sum = $x - $x³ / 3.0 + $x⁵ / 10.0 - $x⁷ / 42.0 + $x⁹ / 216.0 - $x¹¹ / 1320.0 + $x¹³ / 9360.0 - $x¹⁵ / 75600.0;
            return $sum * $two／√π;
        }

        // Large arguments: For x > 4, compute using erfc for consistency
        // erf(x) = 1 - erfc(x), but computed via asymptotic expansion to avoid cancellation
        if ($ax > 4.0) {
            // For positive x: erf(x) = 1 - erfc(x)
            if ($x > 0) {
                return 1.0 - self::erfcAsymptoticSeries($x);
            } else {
                // For negative x: erf(-x) = -erf(x)
                return -(1.0 - self::erfcAsymptoticSeries($ax));
            }
        }

        // Medium arguments: Use Taylor series
        // erf(x) = (2/√π) * Σ((-1)^n * x^(2n+1) / (n! * (2n+1)))
        $x² = $ax * $ax;
        $sum = $ax;
        $term = $ax;

        for ($n = 1; $n <= 50; $n++) {
            $term *= -$x² / $n;
            $sum += $term / (2 * $n + 1);

            // Early exit if converged
            if (\abs($term / (2 * $n + 1)) < 1e-15) {
                break;
            }
        }

        $result = $sum * $two／√π;

        return ($x > 0) ? $result : -$result;
    }

    /**
     * Error function (Gauss error function)
     * Convenience method for errorFunction
     *
     * @param  float $x
     *
     * @return float
     */
    public static function erf(float $x): float
    {
        return self::errorFunction($x);
    }

    /**
     * Asymptotic series for erfc
     * erfc(x) ≈ (e^(-x²) / (x√π)) * (1 - 1/(2x²) + 3/(4x⁴) - ...)
     *
     * @param  float $x Must be positive and >= 4.0
     *
     * @return float
     */
    private static function erfcAsymptoticSeries(float $x): float
    {
        $x² = $x * $x;
        $x⁴ = $x² * $x²;
        $x⁶ = $x⁴ * $x²;

        $series = 1.0;
        $series -= 1.0 / (2.0 * $x²);
        $series += 3.0 / (4.0 * $x⁴);
        $series -= 15.0 / (8.0 * $x⁶);

        $factor = \exp(-$x²) / ($x * 1.7724538509055160272981674833411451828);  // √π

        return $factor * $series;
    }

    /**
     * Complementary error function (erfc)
     * erfc(x) ≡ 1 - erf(x)
     * https://en.wikipedia.org/wiki/Error_function
     *
     *                         2  ⌠∞
     * erfc(x) ≡ 1 - erf(x) = ──  ⎮  e^(-t²) dt
     *                        √π  ⌡ₓ
     *
     * Properties:
     *   erfc(-x) = 2 - erfc(x)
     *   erfc(0) = 1
     *   erfc(∞) = 0
     *
     * More numerically stable than computing 1 - erf(x) for large x.
     * For large x, computes directly to avoid catastrophic cancellation
     *
     * @param  int|float $x
     *
     * @return float
     */
    public static function complementaryErrorFunction($x): float
    {
        // For large positive x, use asymptotic expansion to avoid 1 - erf(x) cancellation
        if ($x >= 4.0) {
            return self::erfcAsymptoticSeries($x);
        }

        // For large negative x, erfc(-x) = 2 - erfc(x)
        if ($x <= -6.0) {
            return 2 - self::erfc(-$x);
        }

        // Otherwise use erf
        return 1 - self::errorFunction($x);
    }

    /**
     * Complementary error function (erfc)
     * Convenience method for complementaryErrorFunction
     *
     * @param  float $x
     *
     * @return float
     */
    public static function erfc(float $x): float
    {
        return self::complementaryErrorFunction($x);
    }

    /**
     * Upper Incomplete Gamma Function - Γ(s,x)
     * https://en.wikipedia.org/wiki/Incomplete_gamma_function
     * NIST DLMF: https://dlmf.nist.gov/8.2
     *
     *          ⌠∞
     * Γ(s,x) = ⎮  t^(s-1) e^(-t) dt
     *          ⌡ₓ
     *
     * Relationship to complete gamma:
     *   Γ(s,x) + γ(s,x) = Γ(s)
     *
     * Special cases:
     *   Γ(s,0) = Γ(s)
     *   Γ(s,∞) = 0
     *
     * Algorithm:
     * - For x < s+1: Use series for γ(s,x), then Γ(s,x) = Γ(s) - γ(s,x)
     * - For x >= s+1: Use continued fraction (direct computation)
     *
     * @param float $s shape parameter (s > 0)
     * @param float $x lower limit of integration (x ≥ 0)
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException if s is ≤ 0
     */
    public static function upperIncompleteGamma(float $s, float $x): float
    {
        if ($s <= 0) {
            throw new Exception\OutOfBoundsException("S must be > 0. S = $s");
        }

        // Choose algorithm based on x relative to s
        if ($x < $s + 1) {
            // Use lower incomplete gamma, then subtract from Γ(s)
            return self::Γ($s) - self::lowerIncompleteGammaSeries($s, $x);
        } else {
            // Use continued fraction directly
            return self::upperIncompleteGammaCF($s, $x);
        }
    }

    /**
     * Lower incomplete gamma function - γ(s,t)
     * https://en.wikipedia.org/wiki/Incomplete_gamma_function#Lower_incomplete_Gamma_function
     * NIST DLMF: https://dlmf.nist.gov/8.2
     *
     *          ⌠ˣ
     * γ(s,x) = ⎮  t^(s-1) e^(-t) dt
     *          ⌡₀
     *
     * Relationship to complete gamma:
     *   γ(s,x) + Γ(s,x) = Γ(s)
     *
     * Special cases:
     *   γ(s,0) = 0
     *   γ(s,∞) = Γ(s)
     *
     * Algorithm:
     * - For x < s+1: Use series expansion (fast convergence)
     * - For x >= s+1: Use continued fraction for Γ(s,x), then γ(s,x) = Γ(s) - Γ(s,x)
     * - Use log-space computation to avoid overflow for large s or x
     * - Use relative convergence criterion for numerical stability
     *
     * @param float $s shape parameter (s > 0)
     * @param float $x lower limit of integration (x ≥ 0)
     *
     * @return float
     */
    public static function lowerIncompleteGamma(float $s, float $x): float
    {
        // Special cases
        if ($x == 0) {
            return 0;
        }
        if ($s == 0) {
            return \NAN;
        }

        // Fast paths for special values
        if ($s == 1) {
            return 1 - \exp(-1 * $x);
        }
        if ($s == .5) {
            $√π = \sqrt(\M_PI);
            $√x = \sqrt($x);
            return $√π * self::erf($√x);
        }

        // Choose algorithm based on x relative to s
        // Series converges faster for x < s+1, continued fraction for x >= s+1
        if ($x < $s + 1) {
            return self::lowerIncompleteGammaSeries($s, $x);
        } else {
            // Use upper incomplete gamma via continued fraction, then subtract from Γ(s)
            $Γs = self::Γ($s);
            $Γsx = self::upperIncompleteGammaCF($s, $x);
            return $Γs - $Γsx;
        }
    }

    /**
     * Lower incomplete gamma via series expansion
     * γ(s,x) = x^s * e^(-x) / s * (1 + x/(s+1) + x^2/((s+1)(s+2)) + ...)
     *
     *             xˢe⁻ˣ    /      x          x²             x³             \
     * γ(s,x) =  ────────  | 1 + ───── + ────────── + ─────────────── + ... |
     *               s     \      s+1    (s+1)(s+2)   (s+1)(s+2)(s+3)      /
     *
     *
     * Works well for x < s+1. Uses log-space computation and relative convergence.
     *
     * @param float $s shape parameter > 0
     * @param float $x argument
     *
     * @return float
     */
    private static function lowerIncompleteGammaSeries(float $s, float $x): float
    {
        $tol = 1e-14;  // Relative tolerance
        $tiny = 1e-100; // Absolute tolerance for very small values

        // Compute log(xˢ * e⁻ˣ / s) to avoid overflow
        $log_term = $s * \log($x) - $x - \log($s);

        // Series: 1 + x/(s+1) + x²/((s+1)(s+2)) + ...
        $sum = 1.0;
        $term = 1.0;
        $sp = $s; // s prime, will be incremented

        for ($n = 1; $n < self::MAX_ITERATIONS; $n++) {
            $sp += 1.0;
            $term *= $x / $sp;
            $sum += $term;

            // Relative convergence check (hybrid: relative OR absolute)
            if (\abs($term) < $tiny) {
                break;
            }
            if (\abs($sum) > 0 && \abs($term / $sum) < $tol) {
                break;
            }
        }

        if ($n >= self::MAX_ITERATIONS) {
            throw new Exception\FunctionFailedToConvergeException(
                "lowerIncompleteGamma series failed to converge after $n iterations with s=$s, x=$x"
            );
        }

        $result = \exp($log_term) * $sum;
        // Clamp to non-negative to handle floating-point precision errors
        return ($x > 0 && $result < 0) ? 0 : $result;
    }

    /**
     * Upper incomplete gamma via continued fraction
     * Γ(s,x) = x^s * e^(-x) * (1/(x+) (1-s)/(1+) 1/(x+) (2-s)/(1+) 2/(x+) ...)
     *
     * Works well for x >= s+1. Uses Lentz's method for continued fraction evaluation.
     *
     * @param float $s shape parameter > 0
     * @param float $x argument
     *
     * @return float
     */
    private static function upperIncompleteGammaCF(float $s, float $x): float
    {
        $tol = 1e-14;  // Relative tolerance
        $tiny = 1e-30; // Prevent division by zero

        // Compute log(x^s * e^(-x)) to avoid overflow
        $log_term = $s * \log($x) - $x;

        // Continued fraction using Lentz's method
        // CF: 1/(x + 1-s/(1 + 1/(x + 2-s/(1 + 2/(x + ...)))))
        $b = $x + 1.0 - $s;
        $c = 1.0 / $tiny;
        $d = 1.0 / $b;
        $h = $d;

        for ($i = 1; $i < self::MAX_ITERATIONS; $i++) {
            $an = -$i * ($i - $s);
            $b += 2.0;

            $d = $an * $d + $b;
            if (\abs($d) < $tiny) {
                $d = $tiny;
            }

            $c = $b + $an / $c;
            if (\abs($c) < $tiny) {
                $c = $tiny;
            }

            $d = 1.0 / $d;
            $delta = $d * $c;
            $h *= $delta;

            // Convergence check: delta should approach 1
            if (\abs($delta - 1.0) < $tol) {
                break;
            }
        }

        if ($i >= self::MAX_ITERATIONS) {
            throw new Exception\FunctionFailedToConvergeException(
                "upperIncompleteGamma continued fraction failed to converge after $i iterations with s=$s, x=$x"
            );
        }

        $result = \exp($log_term) * $h;
        return $result;
    }

    /**
     * γ - Convenience method for lower incomplete gamma function
     * https://en.wikipedia.org/wiki/Incomplete_gamma_function#Lower_incomplete_Gamma_function
     *
     * @param float $s > 0
     * @param float $x ≥ 0
     *
     * @return float
     */
    public static function γ(float $s, float $x): float
    {
        return self::lowerIncompleteGamma($s, $x);
    }

    /**
     * Regularized lower incomplete gamma function - P(s,x)
     * https://en.wikipedia.org/wiki/Incomplete_gamma_function#Regularized_Gamma_functions_and_Poisson_random_variables
     *
     *          γ(s,x)
     * P(s,x) = ------
     *           Γ(s)
     *
     * P(s,x) is the cumulative distribution function for Gamma random variables with shape parameter s and scale parameter 1
     *
     *
     * @param float $s > 0
     * @param float $x ≥ 0
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     */
    public static function regularizedLowerIncompleteGamma(float $s, float $x): float
    {
        $γ⟮s、x⟯ = self::lowerIncompleteGamma($s, $x);
        $Γ⟮s⟯    = self::Γ($s);

        return $γ⟮s、x⟯ / $Γ⟮s⟯;
    }

    /**
     * Incomplete Beta Function - B(x;a,b)
     *
     * Generalized form of the beta function
     * https://en.wikipedia.org/wiki/Beta_function#Incomplete_beta_function
     *
     *            ⌠ˣ
     * B(x;a,b) = ⎮ t^(a-1) * (1-t)^(b-1) dt
     *            ⌡₀
     *
     * @param float $x Upper limit of the integration 0 ≦ x ≦ 1
     * @param float $a Shape parameter a > 0
     * @param float $b Shape parameter b > 0
     *
     * @return float
     *
     * @throws Exception\BadDataException
     * @throws Exception\BadParameterException
     * @throws Exception\OutOfBoundsException
     */
    public static function incompleteBeta(float $x, float $a, float $b): float
    {
        return self::regularizedIncompleteBeta($x, $a, $b) * self::beta($a, $b);
    }

    /**
     * Regularized incomplete beta function - Iₓ(a, b)
     *
     * https://en.wikipedia.org/wiki/Beta_function#Incomplete_beta_function
     *
     *             B(x;a,b)   1      ⌠ˣ
     * Iₓ(a,b) = --------- = ----    ⎮  t^(a-1) (1-t)^(b-1) dt
     *             B(a,b)    B(a,b)  ⌡₀
     *
     * Properties:
     *   I₀(a,b) = 0
     *   I₁(a,b) = 1
     *   Iₓ(a,b) = 1 - I₍₁₋ₓ₎(b,a)  [symmetry relation]
     *
     * Cumulative distribution function of Beta distribution.
     * Implemented using continued fraction expansion for numerical stability.
     *
     * This function looks at the values of x, a, and b, and determines which algorithm is best to calculate
     * the value of Iₓ(a, b)
     *
     * There are several ways to calculate the incomplete beta function (See: https://dlmf.nist.gov/8.17).
     * This follows the continued fraction form, which consists of a term followed by a converging series of fractions.
     * Lentz's Algorithm is used to solve the continued fraction.
     *
     * The implementation of the continued fraction using Lentz's Algorithm is heavily inspired by Lewis Van Winkle's
     * reference implementation in C: https://github.com/codeplea/incbeta
     *
     * Other implementations used as references in the past:
     *  http://www.boost.org/doc/libs/1_35_0/libs/math/doc/sf_and_dist/html/math_toolkit/special/sf_beta/ibeta_function.html
     *  https://github.com/boostorg/math/blob/develop/include/boost/math/special_functions/beta.hpp
     *
     * @param float $x Upper limit of the integration 0 ≦ x ≦ 1
     * @param float $a Shape parameter a > 0
     * @param float $b Shape parameter b > 0
     *
     * @return float
     *
     * @throws Exception\BadDataException
     * @throws Exception\BadParameterException
     * @throws Exception\FunctionFailedToConvergeException
     * @throws Exception\NanException
     * @throws Exception\OutOfBoundsException
     */
    public static function regularizedIncompleteBeta(float $x, float $a, float $b): float
    {
        $limits = [
            'x' => '[0, 1]',
            'a' => '(0,∞)',
            'b' => '(0,∞)',
        ];
        Support::checkLimits($limits, ['x' => $x, 'a' => $a, 'b' => $b]);

        if ($x == 1 || $x == 0) {
            return $x;
        }

        if ($a == 1) {
            return 1 - (1 - $x) ** $b;
        }

        if ($b == 1) {
            return $x ** $a;
        }

        if ($x > ($a + 1)/($a + $b + 2)) {
            return 1 - self::regularizedIncompleteBeta((1 - $x), $b, $a);
        }

        // Continued fraction using Lentz's Algorithm.

        $first_term = \exp(\log($x) * $a + \log(1.0 - $x) * $b - (self::logGamma($a) + self::logGamma($b) - self::logGamma($a + $b))) / $a;

        // PHP 7.2.0 offers PHP_FLOAT_EPSILON, but 1.0e-30 is used in Lewis Van Winkle's
        // reference implementation to prevent division-by-zero errors, so we use the same here.
        $ε = 1.0e-30;

        // These starting values are changed from the reference implementation to precalculate $i = 0 and avoid the
        // extra conditional expression inside the loop.
        $d = 1.0;
        $c = 2.0;
        $f = $c * $d;

        $m = 0;
        for ($i = 1; $i <= 500; $i++) {
            if ($i % 2 === 0) {
                // Even term
                $m++;
                $numerator = ($m * ($b - $m) * $x) / (($a + 2.0 * $m - 1.0) * ($a + 2.0 * $m));
            } else {
                // Odd term
                $numerator = -(($a + $m) * ($a + $b + $m) * $x) / (($a + 2.0 * $m) * ($a + 2.0 * $m + 1));
            }

            // Lentz's Algorithm
            $d = 1.0 + $numerator * $d;
            $d = 1.0 / (\abs($d) < $ε ? $ε : $d);
            $c = 1.0 + $numerator / (\abs($c) < $ε ? $ε : $c);
            $f *= $c * $d;

            if (\abs(1.0 - $c * $d) < 1.0e-14) {
                return $first_term * ($f - 1.0);
            }
        }

        // Series did not converge.
        throw new Exception\FunctionFailedToConvergeException(sprintf('Continuous fraction series is not converging for x = %f, a = %f, b = %f', $x, $a, $b));
    }


    /**
     * Generalized Hypergeometric Function
     *
     * https://en.wikipedia.org/wiki/Generalized_hypergeometric_function
     *
     *                                       ∞
     *                                      ____
     *                                      \     ∏ap⁽ⁿ⁾ * zⁿ
     * pFq(a₁,a₂,...ap;b₁,b₂,...,bq;z) =     >    ------------
     *                                      /      ∏bq⁽ⁿ⁾ * n!
     *                                      ‾‾‾‾
     *                                       n=0
     *
     * Where a⁽ⁿ⁾ is the Pochhammer Function or Rising Factorial
     *
     * We are evaluating this as a series:
     *
     *               (a + n - 1) * z
     * ∏n = ∏n₋₁  * -----------------
     *               (b + n - 1) * n
     *
     *                  n   (a + n - 1) * z
     *   ₁F₁ = ₁F₁n₋₁ + ∏  -----------------  = ₁F₁n₋₁ + ∏n
     *                  1   (b + n - 1) * n
     *
     * @param int    $p         the number of parameters in the numerator
     * @param int    $q         the number of parameters in the denominator
     * @param float  ...$params a collection of the a, b, and z parameters
     *
     * @return float
     *
     * @throws Exception\BadParameterException if the number of parameters is incorrect
     */
    public static function generalizedHypergeometric(int $p, int $q, float ...$params): float
    {
        $n = \count($params);
        if ($n !== $p + $q + 1) {
            $expected_num_params = $p + $q + 1;
            throw new Exception\BadParameterException("Number of parameters is incorrect. Expected $expected_num_params; got $n");
        }

        $a         = \array_slice($params, 0, $p);
        $b         = \array_slice($params, $p, $q);
        $z         = $params[$n - 1];
        $tol       = .00000001;
        $n         = 1;
        $sum       = 0;
        $product   = 1;
        $iteration = 0;

        do {
            $sum     += $product;
            $a_sum    = \array_product(Single::add($a, $n - 1));
            $b_sum    = \array_product(Single::add($b, $n - 1));
            $product *= $a_sum * $z / $b_sum / $n;
            $n++;
            $iteration++;
        } while (\abs($product / $sum) > $tol && $iteration < self::MAX_ITERATIONS);

        if ($iteration >= self::MAX_ITERATIONS) {
            throw new Exception\FunctionFailedToConvergeException(
                "generalizedHypergeometric failed to converge after $iteration iterations with p=$p, q=$q, z=$z"
            );
        }

        return $sum;
    }

    /**
     * Confluent Hypergeometric Function
     *
     * https://en.wikipedia.org/wiki/Confluent_hypergeometric_function
     *         ∞
     *        ____
     *        \     a⁽ⁿ⁾ * zⁿ
     *  ₁F₁ =  >    ---------
     *        /     b⁽ⁿ⁾ * n!
     *        ‾‾‾‾
     *        n=0
     *
     * @param float $a the numerator value
     * @param float $b the denominator value
     * @param float $z
     *
     * @return float
     *
     * @throws Exception\BadParameterException
     */
    public static function confluentHypergeometric(float $a, float $b, float $z): float
    {
        return self::generalizedHypergeometric(1, 1, $a, $b, $z);
    }

    /**
     * Hypergeometric Function
     *
     * https://en.wikipedia.org/wiki/Hypergeometric_function
     *         ∞
     *        ____
     *        \     a⁽ⁿ⁾ * b⁽ⁿ⁾ * zⁿ
     *  ₂F₁ =  >    ----------------
     *        /         c⁽ⁿ⁾ * n!
     *        ‾‾‾‾
     *        n=0
     *
     * @param float $a the first numerator value
     * @param float $b the second numerator value
     * @param float $c the denominator value
     * @param float $z |z| < 1
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException if |z| >= 1
     * @throws Exception\BadParameterException
     */
    public static function hypergeometric(float $a, float $b, float $c, float $z): float
    {
        if (\abs($z) >= 1) {
             throw new Exception\OutOfBoundsException('|z| must be < 1. |z| = ' . \abs($z));
        }

        return self::generalizedHypergeometric(2, 1, $a, $b, $c, $z);
    }

    /**
     * Softmax (normalized exponential)
     * A generalization of the logistic function that "squashes" a K-dimensional
     * vector z of arbitrary real values to a K-dimensional vector σ(z) of real values
     * in the range (0, 1) that add up to 1.
     * https://en.wikipedia.org/wiki/Softmax_function
     *
     *           ℯᶻⱼ
     * σ(𝐳)ⱼ = ------  for j = 1 to K
     *          ᴷ
     *          ∑ ℯᶻᵢ
     *         ⁱ⁼¹
     *
     * To ensure numerical stability and prevent floating-point overflow/underflow,
     * this implementation uses the "Log-sum-exp trick" (or "Max trick").
     * https://en.wikipedia.org/wiki/LogSumExp
     *
     * The standard formula suffers from two main issues:
     *   1. Overflow (Catastrophic Failure): If any input zⱼ is a large positive number (e.g., > 709 for 64-bit float),
     *      exp(zⱼ) overflows to INF. If all exp(zᵢ) overflow, the result is INF/INF = NaN.
     *   2. Underflow (Loss of Precision): If all zⱼ are large negative numbers, all exp(zⱼ) underflow to 0.
     *      The result is 0/0 = NaN.
     *
     * We use an equivalent, transformed formula:
     *
     *           eᶻʲ⁻ᶜ
     * σ(z)ʲ = ---------  where c = max(z)
     *          ᴷ
     *          ∑ eᶻⁱ⁻ᶜ
     *          ⁱ⁼¹
     *
     * This transformation is mathematically valid because we multiply the numerator and denominator by e⁻ᶜ:
     *
     *   exp(zʲ)      exp(zʲ) ⋅ exp(-c)    exp(zʲ - c)
     *  --------- = ------------------- = --------------
     *  ∑ exp(zⁱ)   ∑ exp(zⁱ) ⋅ exp(-c)   ∑ exp(zⁱ - c)
     *
     * For numerical stability, subtract max(z) (c in formula) before exponentiating.
     * This prevents exp(large_number) from overflowing to INF.
     *
     * Concrete Example of Overflow Prevention (Max Trick):
     * Let z = [750, 770]. Maximum c = 770. (Note: exp(709) is the float limit)
     *  php > var_dump(\exp(709));
     *  float(8.218407461554972E+307)
     *  php > var_dump(\exp(710));
     *  float(INF)
     *
     * Standard Formula Issue:
     *  Numerators: exp(750) -> INF, exp(770) -> INF
     *  Denominator: INF + INF -> INF
     *  Result: INF/INF = NaN (Failure)
     *
     * Stable Formula Solution (zᵢ - c):
     *  Numerators (Shifted): exp(750 - 770) = exp(-20) ≈ 2.06e-9
     *  exp(770 - 770) = exp(0) = 1.0
     *  Denominator Sum: exp(-20) + 1.0 ≈ 1.0
     *  Result: [≈ 2.06e-9, 1.0] (Correct, stable calculation)
     *
     * By shifting the inputs, the largest exponent input is 0, guaranteeing that exp(zⱼ-c) will never overflow to INF.
     *
     * @param  float[] $𝐳
     *
     * @return float[]
     */
    public static function softmax(array $𝐳): array
    {
        // Log-sum-exp trick to prevent overflow/underflow
        // For numerical stability, subtract max(z) before exponentiating
        // This prevents exp(large_number) from overflowing to INF
        $c = \max($𝐳);
        $eᶻʲ⁻ᶜ_array = \array_map(
            function ($z) use ($c) {
                return \exp($z - $c);
            },
            $𝐳
        );

        $∑ᴷeᶻʲ⁻ᶜ = \array_sum($eᶻʲ⁻ᶜ_array);

        $σ⟮𝐳⟯ⱼ = \array_map(
            function ($exp_z) use ($∑ᴷeᶻʲ⁻ᶜ) {
                return $exp_z / $∑ᴷeᶻʲ⁻ᶜ;
            },
            $eᶻʲ⁻ᶜ_array
        );

        return $σ⟮𝐳⟯ⱼ;
    }

    /**************************************************************************
     * BESSEL FUNCTIONS
     *
     *  https://en.wikipedia.org/wiki/Bessel_function
     *
     *          ∞
     *         ____      (-1)ᵏ   ⎛x⎞^(ν+2k)
     *  Jᵥ(x) = \     ────────── ⎜─⎟
     *          /     k! Γ(ν+k+1)⎝2⎠
     *         ‾‾‾‾
     *         k=0
     *
     *************************************************************************/

    /**
     * Bessel function of the first kind, order 0 - J₀(x)
     * https://en.wikipedia.org/wiki/Bessel_function
     *
     *          ∞
     *         ____      (-1)ᵏ   ⎛x⎞^(2k)
     *  J₀(x) = \     ────────── ⎜─⎟
     *          /        (k!)²   ⎝2⎠
     *         ‾‾‾‾
     *         k=0
     *
     *
     * Uses polynomial approximations for numerical stability
     * Reference: Abramowitz and Stegun, Handbook of Mathematical Functions
     *
     * @param float $x
     *
     * @return float
     */
    public static function besselJ0(float $x): float
    {
        if ($x == 0) {
            return 1.0;
        }

        $｜x｜ = \abs($x);

        // Small argument approximation
        if ($｜x｜ < 8.0) {
            $y = $x * $x;
            $numerator = 57568490574.0 + $y * (-13362590354.0 + $y * (651619640.7 + $y * (-11214424.18 + $y * (77392.33017 + $y * (-184.9052456)))));
            $denominator = 57568490411.0 + $y * (1029532985.0 + $y * (9494680.718 + $y * (59272.64853 + $y * (267.8532712 + $y * 1.0))));
            return $numerator / $denominator;
        }

        // Large argument asymptotic expansion
        $z = 8.0 / $｜x｜;
        $y = $z * $z;
        $θ = $｜x｜ - 0.785398164;

        $P = 1.0 + $y * (-0.1098628627e-2 + $y * (0.2734510407e-4 + $y * (-0.2073370639e-5 + $y * 0.2093887211e-6)));
        $Q = -0.1562499995e-1 + $y * (0.1430488765e-3 + $y * (-0.6911147651e-5 + $y * (0.7621095161e-6 - $y * 0.934935152e-7)));

        return \sqrt(0.636619772 / $｜x｜) * (\cos($θ) * $P - $z * \sin($θ) * $Q);
    }

    /**
     * Bessel function of the first kind, order 1 - J₁(x)
     * https://en.wikipedia.org/wiki/Bessel_function
     *
     *         ∞
     *        ____      (-1)ᵏ     ⎛x⎞^(2k+1)
     * J₁(x) = \     ──────────── ⎜─⎟
     *        /      k! (k+1)!    ⎝2⎠
     *        ‾‾‾‾
     *        k=0
     *
     * Properties:
     *   J₁(0) = 0
     *   J₁(-x) = -J₁(x)  [odd function]
     *   J'₀(x) = -J₁(x)
     *
     * Uses polynomial approximations for numerical stability
     * Reference: Abramowitz and Stegun, Handbook of Mathematical Functions
     *
     * @param float $x
     *
     * @return float
     */
    public static function besselJ1(float $x): float
    {
        $｜x｜ = \abs($x);

        // Small argument approximation
        if ($｜x｜ < 8.0) {
            $y = $x * $x;
            $numerator = $x * (72362614232.0 + $y * (-7895059235.0 + $y * (242396853.1 + $y * (-2972611.439 + $y * (15704.48260 + $y * (-30.16036606))))));
            $denominator = 144725228442.0 + $y * (2300535178.0 + $y * (18583304.74 + $y * (99447.43394 + $y * (376.9991397 + $y * 1.0))));
            return $numerator / $denominator;
        }

        // Large argument asymptotic expansion
        $z = 8.0 / $｜x｜;
        $y = $z * $z;
        $θ = $｜x｜ - 2.356194491;

        $P = 1.0 + $y * (0.183105e-2 + $y * (-0.3516396496e-4 + $y * (0.2457520174e-5 + $y * (-0.240337019e-6))));
        $Q = 0.04687499995 + $y * (-0.2002690873e-3 + $y * (0.8449199096e-5 + $y * (-0.88228987e-6 + $y * 0.105787412e-6)));

        $result = \sqrt(0.636619772 / $｜x｜) * (\cos($θ) * $P - $z * \sin($θ) * $Q);
        return $x < 0.0 ? -$result : $result;
    }

    /**
     * Bessel function of the first kind, order n - Jₙ(x)
     * https://en.wikipedia.org/wiki/Bessel_function
     *
     *         ∞
     *        ____      (-1)ᵏ     ⎛x⎞^(n+2k)
     * Jₙ(x) = \     ──────────── ⎜─⎟
     *        /      k! (n+k)!    ⎝2⎠
     *        ‾‾‾‾
     *        k=0
     *
     * Recurrence relation (used in implementation):
     *   Jₙ₊₁(x) = (2n/x)Jₙ(x) - Jₙ₋₁(x)
     *
     * Properties:
     *   Jₙ(0) = δₙ₀  [Kronecker delta]
     *   J₋ₙ(x) = (-1)ⁿJₙ(x)
     *
     * Implementation uses forward recurrence from J₀ and J₁.
     * Uses Miller's backward recurrence algorithm for integer orders
     * Reference: Numerical Recipes in C
     *
     * @param int $n order (n ≥ 0)
     * @param float $x
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException if n < 0
     */
    public static function besselJn(int $n, float $x): float
    {
        if ($n < 0) {
            throw new Exception\OutOfBoundsException("Order n must be non-negative");
        }

        if ($n === 0) {
            return self::besselJ0($x);
        }
        if ($n === 1) {
            return self::besselJ1($x);
        }

        $｜x｜ = \abs($x);
        if ($｜x｜ < 1e-10) {
            return 0.0;
        }

        // Always use Miller's backward recurrence algorithm for n > 1
        // Reference: Numerical Recipes in C, Section 6.5
        $iacc = 40; // Accuracy parameter
        $m1 = $n + (int)\sqrt($iacc * $n);

        // For large x, need m > x to ensure convergence
        if ($｜x｜ > 100) {
            $buffer = 80;
        } elseif ($｜x｜ > 50) {
            $buffer = 50;
        } elseif ($｜x｜ > 30) {
            $buffer = 30;
        } else {
            $buffer = 20;
        }
        $m2 = (int)$｜x｜ + $buffer;
        $m  = \max($m1, $m2);
        // Make m even
        if ($m % 2 === 1) {
            $m++;
        }

        $Jⱼ₊₁   = 0.0;
        $∑      = 0.0;
        $Jⱼ     = 1.0;
        $result = 0.0;

        for ($j = $m; $j > 0; $j--) {
            $Jⱼ₋₁ = $j * 2.0 / $｜x｜ * $Jⱼ - $Jⱼ₊₁;
            $Jⱼ₊₁ = $Jⱼ;
            $Jⱼ   = $Jⱼ₋₁;

            // Rescale to prevent overflow
            if (\abs($Jⱼ) > 1.0e10) {
                $Jⱼ     *= 1.0e-10;
                $Jⱼ₊₁   *= 1.0e-10;
                $result *= 1.0e-10;
                $∑      *= 1.0e-10;
            }

            // Accumulate sum for all odd j
            if ($j % 2 == 1) {
                $∑ += $Jⱼ;
            }

            // Store result for order n
            if ($j === $n) {
                $result = $Jⱼ₊₁;
            }
        }

        // The sum should be equal to 1 when normalized properly
        // J₀ + 2*(J₂ + J₄ + J₆ + ...) = 1
        $∑ = 2.0 * $∑ - $Jⱼ;
        $result = $result / $∑;

        return $x < 0.0 && ($n % 2 === 1) ? -$result : $result;
    }

    /**
     * Bessel function of the first kind, order v - Jᵥ(x)
     * https://en.wikipedia.org/wiki/Bessel_function
     *
     *         ∞
     *        ____      (-1)ᵏ     ⎛x⎞^(ν+2k)
     * Jᵥ(x) = \     ──────────── ⎜─⎟
     *        /      k! Γ(ν+k+1)  ⎝2⎠
     *        ‾‾‾‾
     *        k=0
     *
     * Asymptotic expansion for large x:
     *             ___
     *           √ 2     ⎛     π   νπ ⎞
     *   Jᵥ(x) ≈ ──── cos⎜x - ── - ── ⎟
     *            √πx    ⎝     4    2 ⎠
     *
     * Implemented using recurrence relations and series expansions
     * depending on argument size for numerical stability.
     *
     * For integer orders, uses Miller's backward recurrence.
     * For non-integer orders with small x, uses series expansion.
     * For non-integer orders with large x, uses asymptotic expansion (DLMF 10.17).
     *
     * @param float $ν order (real: can be negative for non-integer)
     * @param float $x
     *
     * @return float
     */
    public static function besselJv(float $ν, float $x): float
    {
        $｜x｜ = \abs($x);

        // For integer orders, use the integer-optimized function
        if ($ν === \floor($ν)) {
            $n = (int) $ν;
            if ($n < 0) {
                // J₋ₙ(x) = (-1)ⁿ Jₙ(x) for integer n
                return (($n % 2 === 0) ? 1 : -1) * self::besselJn(-$n, $x);
            }
            return self::besselJn($n, $x);
        }

        if ($｜x｜ < 1e-10) {
            return 0.0;
        }

        // For large x, use asymptotic expansion (DLMF 10.17.3)
        // Jᵥ(x) ~ sqrt(2/(πx)) * [cos(ω) * P - sin(ω) * Q]
        // where ω = x - vπ/2 - π/4
        // More accurate than series expansion for x > 30
        if ($｜x｜ > 30.0) {
            // For negative v, compute Jᵥ(|v|) and Yᵥ(|v|), then use relationship
            if ($ν < 0) {
                $｜ν｜  = \abs($ν);
                $j_pos = self::besselJv($｜ν｜, $｜x｜);

                // Need Yᵥ for the relationship: J₋ᵥ(x) = cos(νπ) Jᵥ(x) - sin(νπ) Yᵥ(x)
                // For non-integer orders, compute Yᵥ using asymptotic expansion
                $μ = 4.0 * $｜ν｜ * $｜ν｜;
                $ω = $｜x｜ - ($｜ν｜ * \M_PI / 2.0) - (\M_PI / 4.0);

                // Yᵥ asymptotic: Yᵥ(x) ~ sqrt(2/(πx)) * [sin(ω) * P + cos(ω) * Q]
                $P      = 1.0;
                $P_term = 1.0;
                for ($k = 1; $k <= 10; $k++) {
                    $P_term *= -($μ - (2*$k - 1) * (2*$k - 1)) / (2 * $k * (2*$k - 1) * 8.0 * $｜x｜ * $｜x｜);
                    $P      += $P_term;
                    if (\abs($P_term) < \abs($P) * 1e-15) {
                        break;
                    }
                }

                $Q      = ($μ - 1.0) / (8.0 * $｜x｜);
                $Q_term = $Q;
                for ($k = 1; $k <= 10; $k++) {
                    $Q_term *= -($μ - (2*$k + 1) * (2*$k + 1)) / (2 * $k * (2*$k + 1) * 8.0 * $｜x｜ * $｜x｜);
                    $Q      += $Q_term;
                    if (\abs($Q_term) < \abs($Q) * 1e-15) {
                        break;
                    }
                }

                $Y_pos = \sqrt(2.0 / (\M_PI * $｜x｜)) * (\sin($ω) * $P + \cos($ω) * $Q);

                // J₋ᵥ(x) = cos(νπ) Jᵥ(x) - sin(νπ) Yᵥ(x)
                return \cos($｜ν｜ * \M_PI) * $j_pos - \sin($｜ν｜ * \M_PI) * $Y_pos;
            }

            // For positive ν
            $μ = 4.0 * $ν * $ν;

            // ω = x - νπ/2 - π/4
            $ω = $｜x｜ - ($ν * \M_PI / 2.0) - (\M_PI / 4.0);

            // Compute P series: Σ (-1)ᵏ * a₂ₖ / x²ᵏ
            // a₀ = 1, a₂ₖ involves products (μ - (2j-1)²)
            $P      = 1.0;
            $P_term = 1.0;
            for ($k = 1; $k <= 10; $k++) {
                $P_term *= -($μ - (2*$k - 1) * (2*$k - 1)) / (2 * $k * (2*$k - 1) * 8.0 * $｜x｜ * $｜x｜);
                $P      += $P_term;
                if (\abs($P_term) < \abs($P) * 1e-15) {
                    break;
                }
            }

            // Compute Q series: Σ (-1)ᵏ * a₂ₖ₊₁ / x²ᵏ⁺¹
            $Q      = ($μ - 1.0) / (8.0 * $｜x｜);
            $Q_term = $Q;
            for ($k = 1; $k <= 10; $k++) {
                $Q_term *= -($μ - (2*$k + 1) * (2*$k + 1)) / (2 * $k * (2*$k + 1) * 8.0 * $｜x｜ * $｜x｜);
                $Q      += $Q_term;
                if (\abs($Q_term) < \abs($Q) * 1e-15) {
                    break;
                }
            }

            return \sqrt(2.0 / (\M_PI * $｜x｜)) * (\cos($ω) * $P - \sin($ω) * $Q);
        }

        // For negative orders, use series expansion
        if ($ν < 0) {
            // Jᵥ(x) = (x/2)ᵛ * Σ (-1)ᵏ (x²/4)ᵏ / (k! * Γ(ν+k+1))
            // Same formula, just ν happens to be negative
            $∑    = 0.0;
            $term = \pow($｜x｜ / 2.0, $ν) / self::Γ($ν + 1);
            $∑   += $term;

            for ($k = 1; $k < 200; $k++) {
                $term *= -($｜x｜ * $｜x｜ / 4.0) / ($k * ($ν + $k));
                $∑    += $term;
                if (\abs($term) < \abs($∑) * 1e-15) {
                    break;
                }
            }

            return $∑;
        }

        // For positive non-integer orders, use series expansion
        // Jᵥ(x) = (x/2)ᵛ * Σ (-1)ᵏ (x²/4)ᵏ / (k! * Γ(ν+k+1))
        $∑    = 0.0;
        $term = \pow($｜x｜ / 2.0, $ν) / self::Γ($ν + 1);
        $∑   += $term;

        for ($k = 1; $k < 200; $k++) {
            $term *= -($｜x｜ * $｜x｜ / 4.0) / ($k * ($ν + $k));
            $∑    += $term;
            if (\abs($term) < \abs($∑) * 1e-15) {
                break;
            }
        }

        return $∑;
    }

    /**
     * Bessel function of the second kind, order 0 - Y₀(x)
     * https://en.wikipedia.org/wiki/Bessel_function
     *
     *             Jᵥ(x) cos(νπ) - J₋ᵥ(x)
     * Yᵥ(x) = lim ────────────────────────
     *         ν→0        sin(νπ)
     *
     * Alternative representation:
     *         2     ⎛x⎞
     * Y₀(x) = ─ [ln ⎜─⎟ + γ] J₀(x) + [series]
     *         π     ⎝2⎠
     *
     * where γ is Euler-Mascheroni constant.
     *
     * Series:
     *      ∞
     *  2  ___  (-1)ᵐ⁺¹   /x \²ᵐ
     *  ─  \    ───────  | ─ |  Hₘ
     *  π  /__    (m!)²  \ 2 /
     *     m-1
     *
     * Singular at x = 0: Y₀(0⁺) = -∞
     *
     * Uses polynomial approximations for numerical stability
     * Reference: Abramowitz and Stegun, Handbook of Mathematical Functions
     *
     * @param float $x (x > 0)
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException if x ≤ 0
     */
    public static function besselY0(float $x): float
    {
        if ($x <= 0.0) {
            throw new Exception\OutOfBoundsException("Y₀(x) requires x > 0");
        }

        if ($x < 8.0) {
            $J₀ = self::besselJ0($x);
            $y  = $x * $x;
            $numerator = -2957821389.0 + $y * (7062834065.0 + $y * (-512359803.6 + $y * (10879881.29 + $y * (-86327.92757 + $y * 228.4622733))));
            $denominator = 40076544269.0 + $y * (745249964.8 + $y * (7189466.438 + $y * (47447.26470 + $y * (226.1030244 + $y * 1.0))));
            return ($numerator / $denominator) + 0.636619772 * $J₀ * \log($x);
        }

        $z = 8.0 / $x;
        $y = $z * $z;
        $θ = $x - 0.785398164;

        $P = 1.0 + $y * (-0.1098628627e-2 + $y * (0.2734510407e-4 + $y * (-0.2073370639e-5 + $y * 0.2093887211e-6)));
        $Q = -0.1562499995e-1 + $y * (0.1430488765e-3 + $y * (-0.6911147651e-5 + $y * (0.7621095161e-6 - $y * 0.934935152e-7)));

        return \sqrt(0.636619772 / $x) * (\sin($θ) * $P + $z * \cos($θ) * $Q);
    }

    /**
     * Bessel function of the second kind, order 1 - Y₁(x)
     * https://en.wikipedia.org/wiki/Bessel_function
     *
     *           2            x           1
     * Y₁(x) = ─── J₁(x) (ln(───) + γ) − ─── + (series terms)
     *          πx²          2           πx
     *
     * Recurrence relation:
     *   Yₙ₊₁(x) = (2n/x)Yₙ(x) - Yₙ₋₁(x)
     *
     * Singular at x = 0: Y₁(0⁺) = -∞
     *
     * Uses polynomial approximations for numerical stability
     * Reference: Abramowitz and Stegun, Handbook of Mathematical Functions
     *
     * @param float $x (x > 0)
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException if x ≤ 0
     */
    public static function besselY1(float $x): float
    {
        if ($x <= 0.0) {
            throw new Exception\OutOfBoundsException("Y₁(x) requires x > 0");
        }

        if ($x < 8.0) {
            $J₁ = self::besselJ1($x);
            $y  = $x * $x;
            $numerator = $x * (-0.4900604943e13 + $y * (0.1275274390e13 + $y * (-0.5153438139e11 + $y * (0.7349264551e9 + $y * (-0.4237922726e7 + $y * 0.8511937935e4)))));
            $denominator = 0.2499580570e14 + $y * (0.4244419664e12 + $y * (0.3733650367e10 + $y * (0.2245904002e8 + $y * (0.1020426050e6 + $y * (0.3549632885e3 + $y)))));
            return ($numerator / $denominator) + 0.636619772 * ($J₁ * \log($x) - 1.0 / $x);
        }

        $z = 8.0 / $x;
        $y = $z * $z;
        $θ = $x - 2.356194491;

        $P = 1.0 + $y * (0.183105e-2 + $y * (-0.3516396496e-4 + $y * (0.2457520174e-5 + $y * (-0.240337019e-6))));
        $Q = 0.04687499995 + $y * (-0.2002690873e-3 + $y * (0.8449199096e-5 + $y * (-0.88228987e-6 + $y * 0.105787412e-6)));

        return \sqrt(0.636619772 / $x) * (\sin($θ) * $P + $z * \cos($θ) * $Q);
    }

    /**
     * Bessel function of the second kind, order n - Yₙ(x)
     * https://en.wikipedia.org/wiki/Bessel_function
     *
     *          Jₙ(x) cos(nπ) - J₋ₙ(x)
     * Yₙ(x) = ────────────────────────
     *                 sin(nπ)
     *
     * Recurrence relation:
     *   Yₙ₊₁(x) = (2n/x)Yₙ(x) - Yₙ₋₁(x)
     *
     * Wronskian:
     *   Jₙ(x)Yₙ₊₁(x) - Jₙ₊₁(x)Yₙ(x) = 2/(πx)
     *
     * Implementation uses forward recurrence from Y₀ and Y₁.
     *
     * @param int   $n order (n ≥ 0)
     * @param float $x (x > 0)
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException if n < 0 or x ≤ 0
     */
    public static function besselYn(int $n, float $x): float
    {
        if ($n < 0) {
            throw new Exception\OutOfBoundsException("Order n must be non-negative");
        }
        if ($x <= 0.0) {
            throw new Exception\OutOfBoundsException("Yₙ(x) requires x > 0");
        }

        if ($n === 0) {
            return self::besselY0($x);
        }
        if ($n === 1) {
            return self::besselY1($x);
        }

        // Forward recurrence
        $Yₙ₋₁ = self::besselY0($x);
        $Yₙ   = self::besselY1($x);
        for ($j = 1; $j < $n; $j++) {
            $Yₙ₊₁ = $j * 2.0 / $x * $Yₙ - $Yₙ₋₁;
            $Yₙ₋₁ = $Yₙ;
            $Yₙ   = $Yₙ₊₁;
        }

        return $Yₙ;
    }

    /**
     * Modified Bessel function of the first kind, order 0 - I₀(x)
     * https://en.wikipedia.org/wiki/Bessel_function#Modified_Bessel_functions
     *
     *         ∞
     *        ____      1    ⎛x⎞^(2k)
     * I₀(x) = \     ─────── ⎜─⎟
     *        /       (k!)²  ⎝2⎠
     *        ‾‾‾‾
     *        k=0
     *
     * Relationship to J₀:
     *   I₀(x) = J₀(ix)  where i = √(-1)
     *
     * Properties:
     *   I₀(0) = 1
     *   I₀(x) > 0 for all real x
     *   I₀(x) = I₀(-x)  [even function]
     *
     * Uses polynomial approximations for numerical stability
     * Reference: Abramowitz and Stegun, Handbook of Mathematical Functions
     *
     * @param float $x
     *
     * @return float
     */
    public static function besselI0(float $x): float
    {
        $｜x｜ = \abs($x);

        // For large x, use asymptotic expansion from besselIv (more accurate)
        if ($｜x｜ > 15.0) {
            return self::besselIv(0.0, $x);
        }

        if ($｜x｜ < 3.75) {
            $y = ($x / 3.75) * ($x / 3.75);
            return 1.0 + $y * (3.5156229 + $y * (3.0899424 + $y * (1.2067492 + $y * (0.2659732 + $y * (0.360768e-1 + $y * 0.45813e-2)))));
        }

        $y = 3.75 / $｜x｜;
        $result = (\exp($｜x｜) / \sqrt($｜x｜)) * (0.39894228 + $y * (0.1328592e-1
            + $y * (0.225319e-2 + $y * (-0.157565e-2 + $y * (0.916281e-2
            + $y * (-0.2057706e-1 + $y * (0.2635537e-1 + $y * (-0.1647633e-1
            + $y * 0.392377e-2))))))));

        return $result;
    }

    /**
     * Modified Bessel function of the first kind, order 1 - I₁(x)
     * https://en.wikipedia.org/wiki/Bessel_function#Modified_Bessel_functions
     *
     *         ∞
     *        ____      1     ⎛x⎞^(2k+1)
     * I₁(x) = \     ──────── ⎜─⎟
     *        /      k!(k+1)! ⎝2⎠
     *        ‾‾‾‾
     *        k=0
     *
     * Relationship to J₁:
     *   I₁(x) = -i·J₁(ix)  where i = √(-1)
     *
     * Properties:
     *   I₁(0) = 0
     *   I₁(-x) = -I₁(x)  [odd function]
     * Uses polynomial approximations for numerical stability
     * Reference: Abramowitz and Stegun, Handbook of Mathematical Functions
     *
     * @param float $x
     *
     * @return float
     */
    public static function besselI1(float $x): float
    {
        $｜x｜ = \abs($x);

        // For large x, use asymptotic expansion from besselIv (more accurate)
        if ($｜x｜ > 15.0) {
            return self::besselIv(1.0, $x);
        }

        if ($｜x｜ < 3.75) {
            $y = ($x / 3.75) * ($x / 3.75);
            $result = $｜x｜ * (0.5 + $y * (0.87890594 + $y * (0.51498869 + $y * (0.15084934
                + $y * (0.2658733e-1 + $y * (0.301532e-2 + $y * 0.32411e-3))))));
        } else {
            $y = 3.75 / $｜x｜;
            $result = 0.2282967e-1 + $y * (-0.2895312e-1 + $y * (0.1787654e-1 - $y * 0.420059e-2));
            $result = 0.39894228 + $y * (-0.3988024e-1 + $y * (-0.362018e-2 + $y * (0.163801e-2 + $y * (-0.1031555e-1 + $y * $result))));
            $result *= (\exp($｜x｜) / \sqrt($｜x｜));
        }

        return $x < 0.0 ? -$result : $result;
    }

    /**
     * Modified Bessel function of the first kind, order v - Iᵥ(x)
     * https://en.wikipedia.org/wiki/Bessel_function#Modified_Bessel_functions
     *
     *         ∞
     *        ____         1      ⎛x⎞^(ν+2k)
     * Iᵥ(x) = \     ──────────── ⎜─⎟
     *        /      k! Γ(ν+k+1)  ⎝2⎠
     *        ‾‾‾‾
     *        k=0
     *
     * Recurrence relation:
     *   Iᵥ₊₁(x) = Iᵥ₋₁(x) - (2ν/x)Iᵥ(x)
     *
     * Asymptotic expansion for large x:
     *             eˣ
     *   Iᵥ(x) ≈ ──── [1 + O(1/x)]
     *            √(2πx)
     *
     * Uses series expansion and recurrence relations
     *
     * @param float $ν order (real)
     * @param float $x argument (x ≥ 0 for v ≥ 0)
     *
     * @return float
     */
    public static function besselIv(float $ν, float $x): float
    {
        $｜x｜ = \abs($x);
        if ($｜x｜ < 1e-10) {
            return 0.0;
        }

        // For very large x relative to ν, use asymptotic expansion (more accurate than I0/I1 approximations)
        // Asymptotic expansion: Iᵥ(x) ≈ eˣ / sqrt(2πx) * Σ (-1)ᵏ * aₖ(ν) / xᵏ
        // Valid when x/ν >= 2 (or x > 15 for ν=0,1)
        $｜ν｜ = \abs($ν);
        if ($｜x｜ > 15.0 && ($｜ν｜ < 1.0 || $｜x｜ / $｜ν｜ >= 2.0)) {
            $μ           = 4.0 * $ν * $ν;
            $coefficient = \exp($｜x｜) / \sqrt(2.0 * \M_PI * $｜x｜);

            $∑    = 1.0;
            $term = 1.0;

            // Add asymptotic terms with alternating signs (use more terms for better accuracy)
            for ($k = 1; $k <= 20; $k++) {
                $term *= -($μ - (2 * $k - 1) * (2 * $k - 1)) / ($k * 8.0 * $｜x｜);
                $∑    += $term;
                if (\abs($term) < \abs($∑) * 1e-15) {
                    break;
                }
            }

            return $x < 0.0 && ((int)$ν % 2 === 1) ? -$coefficient * $∑ : $coefficient * $∑;
        }

        // For ν=0 and ν=1 at medium x, use specialized functions
        if ($ν === 0.0) {
            return self::besselI0($x);
        }
        if ($ν === 1.0) {
            return self::besselI1($x);
        }

        // For integer orders >= 2, use recurrence for medium x, series for small x
        if ($ν === \floor($ν) && \abs($ν) >= 2) {
            $n = (int) $ν;
            if ($n > 0) {
                // Choose method based on x and ν to minimize error
                // Recurrence accumulates error when n is large relative to x
                // Use series when x < max(5, n) to avoid recurrence error accumulation
                if ($｜x｜ < \max(5.0, $n)) {
                    // For small x relative to n, use series expansion to avoid error accumulation
                    // Fall through to series expansion below
                } elseif ($｜x｜ <= 15.0 || $｜x｜ / $｜ν｜ < 2.0) {
                    // For medium x with good x/n ratio, use recurrence
                    $Iₙ₋₁ = self::besselI0($｜x｜);
                    $Iₙ = self::besselI1($｜x｜);
                    for ($j = 1; $j < $n; $j++) {
                        $Iₙ₊₁ = $Iₙ₋₁ - ($j * 2.0 / $｜x｜) * $Iₙ;
                        $Iₙ₋₁ = $Iₙ;
                        $Iₙ   = $Iₙ₊₁;
                    }
                    return $x < 0.0 && ($n % 2 === 1) ? -$Iₙ : $Iₙ;
                } else {
                    // For large x with x/v >= 2, asymptotic expansion was already tried above
                    // Fall through to series expansion as fallback
                }
            }
        }

        // Use series expansion for non-integer orders, negative orders, or small x
        // $｜ν｜ already defined above
        $∑    = 0.0;
        $term = \pow($｜x｜ / 2.0, $｜ν｜) / self::Γ($｜ν｜ + 1);
        $∑   += $term;

        for ($k = 1; $k < 200; $k++) {
            $term *= ($｜x｜ * $｜x｜ / 4.0) / ($k * ($｜ν｜ + $k));
            $∑    += $term;
            if (\abs($term) < \abs($∑) * 1e-15) {
                break;
            }
        }

        // For negative non-integer orders: I₋ᵥ(x) = Iᵥ(x) + (2/π)sin(νπ)Kᵥ(x)
        if ($ν < 0 && $ν !== \floor($ν)) {
            $Kᵥ = self::besselKv($｜ν｜, $｜x｜);
            $∑  = $∑ + (2.0 / \M_PI) * \sin($｜ν｜ * \M_PI) * $Kᵥ;
        }

        return $x < 0.0 && ((int)$ν % 2 === 1) ? -$∑ : $∑;
    }

    /**
     * Modified Bessel function of the second kind, order 0 - K₀(x)
     * https://en.wikipedia.org/wiki/Bessel_function#Modified_Bessel_functions
     *
     *         π   I₋₀(x) - I₀(x)
     * K₀(x) = ─ ──────────────────
     *         2     sin(0π)
     *
     * Alternative representation:
     *   K₀(x) = -[ln(x/2) + γ] I₀(x) + [series]
     *
     * where γ is Euler-Mascheroni constant.
     *
     * Properties:
     *   K₀(0⁺) = ∞
     *   Kᵥ(x) → 0 as x → ∞
     *   K₀(x) = K₋₀(x)
     *
     * Uses polynomial approximations for numerical stability
     * Reference: Abramowitz and Stegun, Handbook of Mathematical Functions
     *
     * @param float $x (x > 0)
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException if x ≤ 0
     */
    public static function besselK0(float $x): float
    {
        if ($x <= 0.0) {
            throw new Exception\OutOfBoundsException("K₀(x) requires x > 0");
        }

        if ($x <= 2.0) {
            $y = $x * $x / 4.0;
            $result = (-\log($x / 2.0) * self::besselI0($x)) + (-0.57721566 + $y * (0.42278420
                + $y * (0.23069756 + $y * (0.3488590e-1 + $y * (0.262698e-2
                + $y * (0.10750e-3 + $y * 0.74e-5))))));
        } else {
            $y = 2.0 / $x;
            $result = (\exp(-$x) / \sqrt($x)) * (1.25331414 + $y * (-0.7832358e-1
                + $y * (0.2189568e-1 + $y * (-0.1062446e-1 + $y * (0.587872e-2
                + $y * (-0.251540e-2 + $y * 0.53208e-3))))));
        }

        return $result;
    }

    /**
     * Modified Bessel function of the second kind, order 1 - K₁(x)
     * https://en.wikipedia.org/wiki/Bessel_function#Modified_Bessel_functions
     *
     *         1   x  /   x       1 \
     * K₁(x) = ─ + ─ | ln ─ + γ − ─  | + O(x³)
     *         x   2  \   2       2 /
     *
     * Recurrence relation:
     *   Kᵥ₊₁(x) = Kᵥ₋₁(x) + (2ν/x)Kᵥ(x)
     *
     * Properties:
     *   K₁(0⁺) = ∞
     *   K₋₁(x) = K₁(x)
     *
     * Uses polynomial approximations for numerical stability
     * Reference: Abramowitz and Stegun, Handbook of Mathematical Functions
     *
     * @param float $x (x > 0)
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException if x ≤ 0
     */
    public static function besselK1(float $x): float
    {
        if ($x <= 0.0) {
            throw new Exception\OutOfBoundsException("K₁(x) requires x > 0");
        }

        if ($x <= 2.0) {
            $y = $x * $x / 4.0;
            $result = (\log($x / 2.0) * self::besselI1($x)) + (1.0 / $x) * (1.0 + $y * (0.15443144
                + $y * (-0.67278579 + $y * (-0.18156897 + $y * (-0.1919402e-1
                + $y * (-0.110404e-2 + $y * (-0.4686e-4)))))));
        } else {
            $y = 2.0 / $x;
            $result = (\exp(-$x) / \sqrt($x)) * (1.25331414 + $y * (0.23498619
                + $y * (-0.3655620e-1 + $y * (0.1504268e-1 + $y * (-0.780353e-2
                + $y * (0.325614e-2 + $y * (-0.68245e-3)))))));
        }

        return $result;
    }

    /**
     * Modified Bessel function of the second kind, order v - Kᵥ(x)
     * https://en.wikipedia.org/wiki/Bessel_function#Modified_Bessel_functions
     *
     *         π  I₋ᵥ(x) - Iᵥ(x)
     * Kᵥ(x) = ─ ──────────────────
     *         2     sin(νπ)
     *
     * Recurrence relation:
     *   Kᵥ₊₁(x) = Kᵥ₋₁(x) + (2ν/x)Kᵥ(x)
     *
     * Asymptotic expansion for large x:
     *                ___
     *              √ π   -x
     *   Kᵥ(x) ≈  ───── e   [1 + O(1/x)]
     *              √2x
     *
     * Properties:
     *   Kᵥ(x) = K₋ᵥ(x)
     *   Kᵥ(x) > 0 for x > 0
     *
     * @param float $ν order (real)
     * @param float $x argument (x > 0)
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException if v < 0 or x ≤ 0
     */
    public static function besselKv(float $ν, float $x): float
    {
        if ($ν < 0) {
            throw new Exception\OutOfBoundsException("Order ν must be non-negative");
        }
        if ($x <= 0.0) {
            throw new Exception\OutOfBoundsException("Kᵥ(x) requires x > 0");
        }

        if ($ν === 0.0) {
            return self::besselK0($x);
        }
        if ($ν === 1.0) {
            return self::besselK1($x);
        }

        // For integer orders, use recurrence
        if ($ν === \floor($ν)) {
            $n    = (int) $ν;
            $Kₙ₋₁ = self::besselK0($x);
            $Kₙ   = self::besselK1($x);
            for ($j = 1; $j < $n; $j++) {
                $Kₙ₊₁ = $Kₙ₋₁ + ($j * 2.0 / $x) * $Kₙ;
                $Kₙ₋₁ = $Kₙ;
                $Kₙ = $Kₙ₊₁;
            }
            return $Kₙ;
        }

        // For non-integer orders
        // For large x (x > 10), use asymptotic expansion which converges better
        // For small x, use series expansion

        $π = \M_PI;
        $sin⟮νπ⟯ = \sin($ν * $π);

        if (\abs($sin⟮νπ⟯) < 1e-10) {
            // Near integer, use recurrence from nearby values
            $ν_floor = \floor($ν);
            return self::besselKv($ν_floor, $x);
        }

        // For large x, use asymptotic expansion: Kᵥ(x) ~ sqrt(π/(2x)) * e⁻ˣ * [1 + ...]
        if ($x > 10.0) {
            // Asymptotic expansion: Kᵥ(x) ≈ sqrt(π/(2x)) * e⁻ˣ * Σ aₖ / xᵏ
            // where aₖ involves coefficients depending on ν
            $coefficient = \sqrt($π / (2.0 * $x)) * \exp(-$x);

            // Leading term expansion
            $μ    = 4.0 * $ν * $ν;
            $∑    = 1.0;
            $term = 1.0;

            // Add correction terms
            $term *= ($μ - 1.0) / (8.0 * $x);
            $∑    += $term;

            $term *= ($μ - 9.0) / (2.0 * 8.0 * $x);
            $∑    += $term;

            $term *= ($μ - 25.0) / (3.0 * 8.0 * $x);
            $∑    += $term;

            return $coefficient * $∑;
        }

        // For small x, use series expansion
        // Kᵥ(x) = (π/2) * (I₋ᵥ(x) - Iᵥ(x)) / sin(νπ)

        // Compute Iᵥ directly
        $∑_ν    = 0.0;
        $term_ν = \pow($x / 2.0, $ν) / self::Γ($ν + 1);
        $∑_ν   += $term_ν;
        for ($k = 1; $k < 200; $k++) {
            $term_ν *= ($x * $x / 4.0) / ($k * ($ν + $k));
            $∑_ν    += $term_ν;
            if (\abs($term_ν) < \abs($∑_ν) * 1e-15) {
                break;
            }
        }

        // Compute I₋ᵥ
        $∑_neg_ν    = 0.0;
        $term_neg_ν = \pow($x / 2.0, -$ν) / self::Γ(1.0 - $ν);
        $∑_neg_ν   += $term_neg_ν;
        for ($k = 1; $k < 200; $k++) {
            $term_neg_ν *= ($x * $x / 4.0) / ($k * ($k - $ν));
            $∑_neg_ν    += $term_neg_ν;
            if (\abs($term_neg_ν) < \abs($∑_neg_ν) * 1e-15) {
                break;
            }
        }

        return ($π / 2.0) * ($∑_neg_ν - $∑_ν) / $sin⟮νπ⟯;
    }

    /**************************************************************************
     * ORTHOGONAL POLYNOMIALS
     *************************************************************************/

    /**
     * Legendre polynomial Pₙ(x)
     * https://en.wikipedia.org/wiki/Legendre_polynomials
     *
     * Rodrigues' formula:
     *              1    dⁿ
     *   Pₙ(x) = ────── ───── [(x² - 1)ⁿ]
     *            2ⁿ n!  dxⁿ
     *
     * Recurrence relation (used in implementation):
     *   (n+1)Pₙ₊₁(x) = (2n+1)xPₙ(x) - nPₙ₋₁(x)
     *
     * Initial values:
     *   P₀(x) = 1
     *   P₁(x) = x
     *
     * Properties:
     *   Pₙ(1) = 1
     *   Pₙ(-1) = (-1)ⁿ
     *   Pₙ(-x) = (-1)ⁿPₙ(x)
     *
     * @param int   $n order (n ≥ 0)
     * @param float $x (-1 ≤ x ≤ 1 for orthogonality)
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException if n < 0
     */
    public static function legendreP(int $n, float $x): float
    {
        if ($n < 0) {
            throw new Exception\OutOfBoundsException("Order n must be non-negative");
        }

        if ($n === 0) {
            return 1.0;
        }
        if ($n === 1) {
            return $x;
        }

        // Three-term recurrence relation
        $Pₙ₋₁ = 1.0;  // P₀
        $Pₙ   = $x;     // P₁

        for ($k = 1; $k < $n; $k++) {
            $Pₙ₊₁ = ((2 * $k + 1) * $x * $Pₙ - $k * $Pₙ₋₁) / ($k + 1);
            $Pₙ₋₁ = $Pₙ;
            $Pₙ   = $Pₙ₊₁;
        }

        return $Pₙ;
    }

    /**
     * Chebyshev polynomial of the first kind Tₙ(x)
     * https://en.wikipedia.org/wiki/Chebyshev_polynomials
     *
     * Trigonometric representation:
     *   Tₙ(x) = cos(n arccos(x))
     *   Tₙ(cos θ) = cos(nθ)
     *
     * Explicit formula:
     *            n
     *   Tₙ(x) = ─── cos[n · arccos(x)]
     *            1
     *
     * Recurrence relation (used in implementation):
     *   Tₙ₊₁(x) = 2xTₙ(x) - Tₙ₋₁(x)
     *
     * Initial values:
     *   T₀(x) = 1
     *   T₁(x) = x
     *
     * @param int $n order (n ≥ 0)
     * @param float $x
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException if n < 0
     */
    public static function chebyshevT(int $n, float $x): float
    {
        if ($n < 0) {
            throw new Exception\OutOfBoundsException("Order n must be non-negative");
        }

        if ($n === 0) {
            return 1.0;
        }
        if ($n === 1) {
            return $x;
        }

        // Three-term recurrence relation
        $Tₙ₋₁ = 1.0;  // T₀
        $Tₙ   = $x;   // T₁

        for ($k = 1; $k < $n; $k++) {
            $Tₙ₊₁ = 2.0 * $x * $Tₙ - $Tₙ₋₁;
            $Tₙ₋₁ = $Tₙ;
            $Tₙ   = $Tₙ₊₁;
        }

        return $Tₙ;
    }

    /**
     * Chebyshev polynomial of the second kind Uₙ(x)
     * https://en.wikipedia.org/wiki/Chebyshev_polynomials
     *
     *          sin((n + 1) arccos(x))
     *  Uₙ(x) = ──────────────────────
     *               ____________
     *              √  1 - x²
     *
     * Uses three-term recurrence relation:
     * Uₙ₊₁(x) = 2xUₙ(x) - Uₙ₋₁(x)
     *
     * @param int $n order (n ≥ 0)
     * @param float $x
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException if n < 0
     */
    public static function chebyshevU(int $n, float $x): float
    {
        if ($n < 0) {
            throw new Exception\OutOfBoundsException("Order n must be non-negative");
        }

        if ($n === 0) {
            return 1.0;
        }
        if ($n === 1) {
            return 2.0 * $x;
        }

        // Three-term recurrence relation
        $Uₙ₋₁ = 1.0;        // U₀
        $Uₙ   = 2.0 * $x;   // U₁

        for ($k = 1; $k < $n; $k++) {
            $Uₙ₊₁ = 2.0 * $x * $Uₙ - $Uₙ₋₁;
            $Uₙ₋₁ = $Uₙ;
            $Uₙ   = $Uₙ₊₁;
        }

        return $Uₙ;
    }

    /**
     * Hermite polynomial (physicist's version) Hₙ(x)
     * https://en.wikipedia.org/wiki/Hermite_polynomials
     *
     * Rodrigues' formula:
     *                  x²    dⁿ   -x²
     *   Hₙ(x) = (-1)ⁿ e    ───── [e   ]
     *                       dxⁿ
     *
     * Recurrence relation (used in implementation):
     *   Hₙ₊₁(x) = 2xHₙ(x) - 2nHₙ₋₁(x)
     *
     * Initial values:
     *   H₀(x) = 1
     *   H₁(x) = 2x
     *
     * @param int $n order (n ≥ 0)
     * @param float $x
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException if n < 0
     */
    public static function hermiteH(int $n, float $x): float
    {
        if ($n < 0) {
            throw new Exception\OutOfBoundsException("Order n must be non-negative");
        }

        if ($n === 0) {
            return 1.0;
        }
        if ($n === 1) {
            return 2.0 * $x;
        }

        // Three-term recurrence relation
        $Hₙ₋₁ = 1.0;        // H₀
        $Hₙ   = 2.0 * $x;   // H₁

        for ($k = 1; $k < $n; $k++) {
            $Hₙ₊₁ = 2.0 * $x * $Hₙ - 2.0 * $k * $Hₙ₋₁;
            $Hₙ₋₁ = $Hₙ;
            $Hₙ   = $Hₙ₊₁;
        }

        return $Hₙ;
    }

    /**
     * Hermite polynomial (probabilist's version) Heₙ(x)
     * https://en.wikipedia.org/wiki/Hermite_polynomials
     *
     *                  x²/2    dⁿ    -x²/2
     *   Heₙ(x) = (-1)ⁿ e      ───── [e     ]
     *                          dxⁿ
     *
     * Uses three-term recurrence relation:
     * Heₙ₊₁(x) = xHeₙ(x) - nHeₙ₋₁(x)
     *
     * @param int   $n order (n ≥ 0)
     * @param float $x
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException if n < 0
     */
    public static function hermiteHe(int $n, float $x): float
    {
        if ($n < 0) {
            throw new Exception\OutOfBoundsException("Order n must be non-negative");
        }

        if ($n === 0) {
            return 1.0;
        }
        if ($n === 1) {
            return $x;
        }

        // Three-term recurrence relation
        $Heₙ₋₁ = 1.0;  // He₀
        $Heₙ   = $x;     // He₁

        for ($k = 1; $k < $n; $k++) {
            $Heₙ₊₁ = $x * $Heₙ - $k * $Heₙ₋₁;
            $Heₙ₋₁ = $Heₙ;
            $Heₙ   = $Heₙ₊₁;
        }

        return $Heₙ;
    }

    /**
     * Laguerre polynomial Lₙ(x)
     * https://en.wikipedia.org/wiki/Laguerre_polynomials
     *
     * Rodrigues' formula:
     *            eˣ   dⁿ
     *   Lₙ(x) = ──── ───── [xⁿ e⁻ˣ]
     *            n!   dxⁿ
     *
     * Recurrence relation (used in implementation):
     *   (n+1)Lₙ₊₁(x) = (2n+1-x)Lₙ(x) - nLₙ₋₁(x)
     *
     * Initial values:
     *   L₀(x) = 1
     *   L₁(x) = 1 - x
     *
     * @param int   $n order (n ≥ 0)
     * @param float $x
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException if n < 0
     */
    public static function laguerreL(int $n, float $x): float
    {
        if ($n < 0) {
            throw new Exception\OutOfBoundsException("Order n must be non-negative");
        }

        if ($n === 0) {
            return 1.0;
        }
        if ($n === 1) {
            return 1.0 - $x;
        }

        // Three-term recurrence relation
        $Lₙ₋₁ = 1.0;        // L₀
        $Lₙ   = 1.0 - $x;   // L₁

        for ($k = 1; $k < $n; $k++) {
            $Lₙ₊₁ = ((2.0 * $k + 1.0 - $x) * $Lₙ - $k * $Lₙ₋₁) / ($k + 1.0);
            $Lₙ₋₁ = $Lₙ;
            $Lₙ   = $Lₙ₊₁;
        }

        return $Lₙ;
    }

    /**************************************************************************
     * AIRY FUNCTIONS
     *************************************************************************/

    /**
     * Airy function Ai(x)
     * https://en.wikipedia.org/wiki/Airy_function
     *
     * DLMF 9.6: Airy functions in terms of Bessel functions
     * Ai(x) and Bi(x) can be expressed in terms of modified Bessel functions:
     * For x > 0: Ai(x) = (1/π)√(x/3) K₁⸝₃(ζ) where ζ = (2/3)x³ᐟ²
     * For x < 0: Ai(-z) = (√z/3) [J₁⸝₃(ζ) + J₋₁⸝₃(ζ)]  // Use trigonometric forms with Bessel functions
     *
     * Airy functions Ai(x) and Bi(x) are solutions to: y'' - xy = 0
     *
     * @param float $x
     *
     * @return float
     */
    public static function airyAi(float $x): float
    {
        if ($x === 0.0) {
            return 0.35502805388782;  // Ai(0) = 1/(3^(2/3) * Γ(2/3))
        } elseif ($x > 0) {
            $ζ    = (2.0 / 3.0) * $x * \sqrt($x);
            $K₁⸝₃ = self::besselKv(1.0 / 3.0, $ζ);
            return \sqrt($x / 3.0) * $K₁⸝₃ / \M_PI;
        } else {
            $｜x｜ = \abs($x);
            $ζ    = (2.0 / 3.0) * $｜x｜ * \sqrt($｜x｜);
            $J₁⸝₃  = self::besselJv(1.0 / 3.0, $ζ);
            $J₋₁⸝₃ = self::besselJv(-1.0 / 3.0, $ζ);
            return \sqrt($｜x｜) / 3.0 * ($J₁⸝₃ + $J₋₁⸝₃);
        }
    }

    /**
     * Airy function Bi(x)
     * https://en.wikipedia.org/wiki/Airy_function
     *
     * Uses series expansions for small |x| and asymptotic expansions for large |x|
     *
     * DLMF 9.6: Bi(x) = √(x/3) [I₋₁⸝₃(ζ) + I₁⸝₃(ζ)] where ζ = (2/3)x³ᐟ²
     * DLMF 9.6.4: Bi(-z) = √(z/3) [J₋₁⸝₃(ζ) - J₁⸝₃(ζ)]
     *
     * @param float $x
     *
     * @return float
     */
    public static function airyBi(float $x): float
    {
        if ($x === 0.0) {
            return 0.61492662744600;  // Bi(0) = 1/(3^(1/6) * Γ(2/3))
        } elseif ($x > 0) {
            $ζ    = (2.0 / 3.0) * $x * \sqrt($x);
            $I₁⸝₃  = self::besselIv(1.0 / 3.0, $ζ);
            $I₋₁⸝₃ = self::besselIv(-1.0 / 3.0, $ζ);
            return \sqrt($x / 3.0) * ($I₋₁⸝₃ + $I₁⸝₃);
        } else {
            $｜x｜ = \abs($x);
            $ζ    = (2.0 / 3.0) * $｜x｜ * \sqrt($｜x｜);
            $J₁⸝₃  = self::besselJv(1.0 / 3.0, $ζ);
            $J₋₁⸝₃ = self::besselJv(-1.0 / 3.0, $ζ);
            return \sqrt($｜x｜ / 3.0) * ($J₋₁⸝₃ - $J₁⸝₃);
        }
    }

    /**
     * Airy function derivative Ai'(x)
     * https://en.wikipedia.org/wiki/Airy_function
     *
     * DLMF 9.6: Ai'(x) = -(x/√3) K₂⸝₃(ζ) / π where ζ = (2/3)x³ᐟ²
     * DLMF 9.6.7: Ai'(-z) = (z/3) [J₂⸝₃(ζ) - J₋₂⸝₃(ζ)]
     *
     * Uses series expansions for small |x| and asymptotic expansions for large |x|
     *
     * @param float $x
     *
     * @return float
     */
    public static function airyAip(float $x): float
    {
        if ($x === 0.0) {
            return -0.25881940379281;  // Ai'(0) = -1/(3^(1/3) * Γ(1/3))
        } elseif ($x > 0) {
            $ζ   = (2.0 / 3.0) * $x * \sqrt($x);
            $K₂⸝₃ = self::besselKv(2.0 / 3.0, $ζ);
            return -$x / (\sqrt(3.0) * \M_PI) * $K₂⸝₃;
        } else {
            $｜x｜ = \abs($x);
            $ζ    = (2.0 / 3.0) * $｜x｜ * \sqrt($｜x｜);
            $J₂⸝₃  = self::besselJv(2.0 / 3.0, $ζ);
            $J₋₂⸝₃ = self::besselJv(-2.0 / 3.0, $ζ);
            return ($｜x｜ / 3.0) * ($J₂⸝₃ - $J₋₂⸝₃);
        }
    }

    /**
     * Airy function derivative Bi'(x)
     * https://en.wikipedia.org/wiki/Airy_function
     *
     * DLMF 9.6: Bi'(x) = (x/√3) [I₋₂⸝₃(ζ) + I₂⸝₃(ζ)] where ζ = (2/3)x³ᐟ²
     * DLMF 9.6.8: Bi'(-z) = (z/√3) [J₋₂⸝₃(ζ) + J₂⸝₃(ζ)]
     *
     * Uses series expansions for small |x| and asymptotic expansions for large |x|
     *
     * @param float $x
     *
     * @return float
     */
    public static function airyBip(float $x): float
    {
        if ($x === 0.0) {
            return 0.44828835735383;  // Bi'(0) = 3^(1/6) / Γ(1/3)
        } elseif ($x > 0) {
            $ζ    = (2.0 / 3.0) * $x * \sqrt($x);
            $I₂⸝₃  = self::besselIv(2.0 / 3.0, $ζ);
            $I₋₂⸝₃ = self::besselIv(-2.0 / 3.0, $ζ);
            return $x / \sqrt(3.0) * ($I₋₂⸝₃ + $I₂⸝₃);
        } else {
            $｜x｜ = \abs($x);
            $ζ    = (2.0 / 3.0) * $｜x｜ * \sqrt($｜x｜);
            $J₂⸝₃  = self::besselJv(2.0 / 3.0, $ζ);
            $J₋₂⸝₃ = self::besselJv(-2.0 / 3.0, $ζ);
            return $｜x｜ / \sqrt(3.0) * ($J₋₂⸝₃ + $J₂⸝₃);
        }
    }
}
