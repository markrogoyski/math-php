<?php

namespace MathPHP\Functions;

use MathPHP\Probability\Combinatorics;
use MathPHP\Functions\Map\Single;
use MathPHP\Exception;

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
        if ((\abs($z - \round($z)) < 0.00001) && $z < 0) {
            return -\INF;
        }
        // Positive integer, or positive int as a float (Ex: from beta(0.1, 0.9) since it will call Γ(x + y))
        if ((\abs($z - \round($z)) < 0.00001) && $z > 0) {
            return Combinatorics::factorial((int) \round($z) - 1);
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
     * For postive integers:
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
        if ((\abs($n - \round($n)) < 0.00001) && $n < 0) {
            return -\INF;
        }
        // Positive integer, or postive int as a float
        if ((\abs($n - \round($n)) < 0.00001) && $n > 0) {
            return Combinatorics::factorial((int) \round($n) - 1);
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
     * For n <=15, integers or half-integers, uses stored values.
     *
     * @param float $n
     *
     * @return float log of the error
     */
    public static function stirlingError(float $n)
    {
        $S0 = 0.083333333333333333333;        // 1/12
        $S1 = 0.00277777777777777777778;      // 1/360
        $S2 = 0.00079365079365079365079365;   // 1/1260
        $S3 = 0.000595238095238095238095238;  // 1/1680
        $S4 = 0.0008417508417508417508417508; // 1/1188

        $sferr_halves = [
            0.0, /* n=0 - wrong, place holder only */
            0.1534264097200273452913848,  /* 0.5 */
            0.0810614667953272582196702,  /* 1.0 */
            0.0548141210519176538961390,  /* 1.5 */
            0.0413406959554092940938221,  /* 2.0 */
            0.03316287351993628748511048, /* 2.5 */
            0.02767792568499833914878929, /* 3.0 */
            0.02374616365629749597132920, /* 3.5 */
            0.02079067210376509311152277, /* 4.0 */
            0.01848845053267318523077934, /* 4.5 */
            0.01664469118982119216319487, /* 5.0 */
            0.01513497322191737887351255, /* 5.5 */
            0.01387612882307074799874573, /* 6.0 */
            0.01281046524292022692424986, /* 6.5 */
            0.01189670994589177009505572, /* 7.0 */
            0.01110455975820691732662991, /* 7.5 */
            0.010411265261972096497478567, /* 8.0 */
            0.009799416126158803298389475, /* 8.5 */
            0.009255462182712732917728637, /* 9.0 */
            0.008768700134139385462952823, /* 9.5 */
            0.008330563433362871256469318, /* 10.0 */
            0.007934114564314020547248100, /* 10.5 */
            0.007573675487951840794972024, /* 11.0 */
            0.007244554301320383179543912, /* 11.5 */
            0.006942840107209529865664152, /* 12.0 */
            0.006665247032707682442354394, /* 12.5 */
            0.006408994188004207068439631, /* 13.0 */
            0.006171712263039457647532867, /* 13.5 */
            0.005951370112758847735624416, /* 14.0 */
            0.005746216513010115682023589, /* 14.5 */
            0.005554733551962801371038690, /* 15.0 */
        ];

        if ($n <= 15.0) {
            $nn = $n + $n;
            if ($nn == (int)$nn) {
                return $sferr_halves[$nn];
            }
            $M_LN_SQRT_2PI = \log(\sqrt(2 * \pi()));
            return self::logGamma($n + 1) - ($n + 0.5) * \log($n) + $n - $M_LN_SQRT_2PI;
        }

        $nn = $n * $n;
        if ($n > 500) {
            return ($S0 - $S1 / $nn) / $n;
        }
        if ($n > 80) {
            return ($S0 - ($S1 - $S2 / $nn) / $nn) / $n;
        }
        if ($n > 35) {
            return ($S0 - ($S1 - ($S2 - $S3/$nn)/$nn)/$nn)/$n;
        }
        /* 15 < n <= 35 : */
        return ($S0-($S1-($S2-($S3-$S4/$nn)/$nn)/$nn)/$nn)/$n;
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
     * @param float $n
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     * @throws Exception\NanException
     */
    public static function gamma(float $x)
    {
        $gamcs = [
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

        /* For IEEE double precision DBL_EPSILON = 2^-52 = 2.220446049250313e-16 :
         * (xmin, xmax) are non-trivial, see ./gammalims.c
         * xsml = exp(.01)*DBL_MIN
         * dxrel = sqrt(DBL_EPSILON) = 2 ^ -26
         */
        $ngam = 22;
        $xmin = -170.5674972726612;
        $xmax = 171.61447887182298;
        $xsml = 2.2474362225598545e-308;
        $dxrel = 1.490116119384765696e-8;

        if (\is_nan($x)) {
            throw new Exception\NanException();
        }

        // If the argument is exactly zero or a negative integer
        // function is undefined.
        if ($x == 0 || ($x < 0 && $x == \round($x))) {
            throw new Exception\NanException();
        }

        $y = abs($x);

        if ($y <= 10) {
            /* Compute gamma(x) for -10 <= x <= 10
             * Reduce the interval and find gamma(1 + y) for 0 <= y < 1
             * first of all.
             */

            $n = (int) $x;
            if ($x < 0) {
                --$n;
            }
            $y = $x - $n; // n = floor(x) ==> y in [ 0, 1 )
            --$n;
            $value = self::chebyshev_eval($y * 2 - 1, $gamcs, $ngam) + .9375;
            if ($n == 0) {
                return $value;/* x = 1.dddd = 1+y */
            }
            if ($n < 0) {
                /* compute gamma(x) for -10 <= x < 1 */

                /* exact 0 or "-n" checked already above */

                /* The answer is less than half precision */
                /* because x too near a negative integer. */
                if ($x < -0.5 && abs($x - (int)($x - 0.5) / $x) < $dxrel) {
                    // ML_WARNING(ME_PRECISION, "gammafn");
                }

                /* The argument is so close to 0 that the result would overflow. */
                if ($y < $xsml) {
                    // ML_WARNING(ME_RANGE, "gammafn");
                    if ($x > 0) {
                        return \INF;
                    } else {
                        return -\INF;
                    }
                }

                $n = -$n;

                for ($i = 0; $i < $n; $i++) {
                    $value /= ($x + $i);
                }
                return $value;
            }
            /* gamma(x) for 2 <= x <= 10 */

            for ($i = 1; $i <= $n; $i++) {
                $value *= ($y + $i);
            }
            return $value;
        }
        // gamma(x) for y = |x| > 10.

        if ($x > $xmax) {
            // Overflow
            // No warning: +Inf is the best answer
            return \INF;
        }

        if ($x < $xmin) {
            // Underflow
            // No warning: 0 is the best answer
            return 0;
        }

        if ($y <= 50 && $y == (int) $y) { /* compute (n - 1)! */
            $value = 1.;
            for ($i = 2; $i < $y; $i++) {
                $value *= $i;
            }
        } else { /* normal case */
            $M_LN_SQRT_2PI = (\M_LNPI + \M_LN2)/2;
            $value = \exp(($y - 0.5) * \log($y) - $y + $M_LN_SQRT_2PI + ((2*$y == (int)2*$y)? self::stirlingError($y) : self::logGammaCorr($y)));
        }
        if ($x > 0) {
            return $value;
        }
        if (abs(($x - (int)($x - 0.5))/$x) < $dxrel) {
            /* The answer is less than half precision because */
            /* the argument is too near a negative integer. */

            // ML_WARNING(ME_PRECISION, "gammafn");
        }

        $sinpiy = \sin(\pi() * $y);
        if ($sinpiy == 0) {
            // Negative integer arg - overflow
            // ML_WARNING(ME_RANGE, "gammafn");
            return \INF;
        }
        return -\M_PI / ($y * $sinpiy * $value);
    }

    public static function logGamma(float $x)
    {
        // For IEEE double precision DBL_EPSILON = 2^-52 = 2.220446049250313e-16 :
        // xmax  = DBL_MAX / log(DBL_MAX) = 2^1024 / (1024 * log(2)) = 2^1014 / log(2)
        // dxrel = sqrt(DBL_EPSILON) = 2^-26 = 5^26 * 1e-26 (is *exact* below !)
        $xmax  = 2.5327372760800758e+305;
        $dxrel = 1.490116119384765625e-8;

        if (\is_nan($x)) {
            throw new Exception\NanException();
        }
        if ($x <= 0 && $x == (int) $x) {
            // Negative integer argument
            // No warning: this is the best answer; was ML_WARNING(ME_RANGE, "lgamma");
            return \INF;    // +Inf, since lgamma(x) = log|gamma(x)|
        }

        $y = abs($x);

        if ($y < 1e-306) {
            return -log($y); // denormalized range, R change
        }
        if ($y <= 10) {
            return log(abs(self::gamma($x)));
        }
        // ELSE  y = |x| > 10

        if ($y > $xmax) {
            // No warning: +Inf is the best answer
            return \INF;
        }

        if ($x > 0) { /* i.e. y = x > 10 */
            if ($x > 1e17) {
                return($x*(log($x) - 1));
            }
            $M_LN_SQRT_2PI = (\M_LNPI + \M_LN2)/2;
            if ($x > 4934720.) {
                return($M_LN_SQRT_2PI + ($x - 0.5) * log($x) - $x);
            }
            return $M_LN_SQRT_2PI + ($x - 0.5) * log($x) - $x + self::logGammaCorr($x);
        }
        $M_LN_SQRT_PId2 = 0.225791352644727432363097614947;        // log(sqrt(pi/2))
        $sinpiy = abs(sin(pi() * $y));
        $ans = $M_LN_SQRT_PId2 + ($x - 0.5) * log($y) - $x - log($sinpiy) - self::logGammaCorr($y);
        return $ans;
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
        $Γ⟮x⟯  = self::gamma($x);
        $Γ⟮y⟯  = self::gamma($y);
        $Γ⟮x ＋ y⟯ = self::gamma($x + $y);

        return 1 / $Γ⟮x ＋ y⟯ * $Γ⟮x⟯ * $Γ⟮y⟯;
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
     * Selects the best beta algorithm for the provided
     * values
     *
     * @param  float $a
     * @param  float $b
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     */
    public static function beta(float $a, float $b): float
    {
        $xmin = -170.5674972726612;
        $xmax = 171.61447887182298;
        $lnsml = -708.39641853226412;

        if (\is_nan($a) || \is_nan($b)) {
            throw new Exception\NanException();
        }
        if ($a < 0 || $b < 0) {
            throw new Exception\OutOfBoundsException();
        }
        if ($a == 0 || $b == 0) {
            return \INF;
        }
        if (\is_infinite($a) || \is_infinite($b)) {
            return 0;
        }
        if ($a + $b < $xmax) {/* ~= 171.61 for IEEE */
            // return gammafn(a) * gammafn(b) / gammafn(a+b);
            /* All the terms are positive, and all can be large for large
               or small arguments.  They are never much less than one.
               gammafn(x) can still overflow for x ~ 1e-308,
               but the result would too.
            */
            return self::betaBasic($a, $b);
        }
        $val = self::logBeta($a, $b);
        // underflow to 0 is not harmful per se;  exp(-999) also gives no warning

        // if ($val < $lnsml) {
            /* a and/or b so big that beta underflows */
            //ML_WARNING(ME_UNDERFLOW, "beta");
            /* return ML_UNDERFLOW; pointless giving incorrect value */
        // }
        return \exp($val);
    }

    /**
     * The log of the beta function
     *
     * @param  float $a
     * @param  float $b
     *
     * @return float
     *
     * @throws Exception\OutOfBoundsException
     * * @throws Exception\NanException
     */
    public static function logbeta($a, $b)
    {
        if (\is_nan($a) || \is_nan($b)) {
            throw new Exception\NanException();
        }
        $p = $a;
        $q = $a;
        if ($b < $p) {
            $p = $b;/* := min(a,b) */
        }
        if ($b > $q) {
            $q = $b;/* := max(a,b) */
        }

        // Both arguments must be >= 0
        if ($p < 0) {
            throw new Exception\OutOfBoundsException();
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
            $M_LN_SQRT_2PI = (\M_LNPI + \M_LN2)/2;
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
     * Compute the log gamma correction factor for x >= 10 so that
     * log(gamma(x)) = .5*log(2*pi) + (x-.5)*log(x) -x + lgammacor(x)
     */
    public static function logGammaCorr($x)
    {
        $algmcs = [
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
        $nalgm = 5;
        $xbig  = 94906265.62425156;
        $xmax  = 3.745194030963158e306;

        if ($x < 10) {
            return (float) 'NaN';
        }
        if ($x >= $xmax) {
            // ML_WARNING(ME_UNDERFLOW, "lgammacor");
            // allow to underflow below
        } elseif ($x < $xbig) {
            $tmp = 10 / $x;
            return self::chebyshev_eval($tmp * $tmp * 2 - 1, $algmcs, $nalgm) / $x;
        }
        return 1 / ($x * 12);
    }

    private static function chebyshev_eval($x, $a, int $n)
    {
        if ($n < 1 || $n > 1000) {
            return (float) 'NaN';
            // ML_WARN_return_NAN;
        }
        if ($x < -1.1 || $x > 1.1) {
            return (float) 'NaN';
            // ML_WARN_return_NAN;
        }
        $twox = $x * 2;
        $b2 = 0;
        $b1 = 0;
        $b0 = 0;
        for ($i = 1; $i <= $n; $i++) {
            $b2 = $b1;
            $b1 = $b0;
            $b0 = $twox * $b1 - $b2 + $a[$n - $i];
        }
        return ($b0 - $b2) * 0.5;
    }

    /**
     * Multivariate Beta function
     * https://en.wikipedia.org/wiki/Beta_function#Multivariate_beta_function
     *
     *                     Γ(α₁)Γ(α₂) ⋯ Γ(αn)
     * B(α₁, α₂, ... αn) = ------------------
     *                      Γ(α₁ + α₂ ⋯ αn)
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
        $xmax = 171.61447887182298;
        $∑α = \array_sum($αs);
        if ($∑α == \INF) {
            return 0;
        }
        if ($∑α < $xmax) {// ~= 171.61 for IEEE
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
     * @param  float $t
     *
     * @return float
     */
    public static function sigmoid(float $t): float
    {
        $ℯ⁻ᵗ = \exp(-$t);

        return 1 / (1 + $ℯ⁻ᵗ);
    }

    /**
     * Error function (Gauss error function)
     * https://en.wikipedia.org/wiki/Error_function
     *
     * This is an approximation of the error function (maximum error: 1.5×10−7)
     *
     * erf(x) ≈ 1 - (a₁t + a₂t² + a₃t³ + a₄t⁴ + a₅t⁵)ℯ^-x²
     *
     *       1
     * t = ------
     *     1 + px
     *
     * p = 0.3275911
     * a₁ = 0.254829592, a₂ = −0.284496736, a₃ = 1.421413741, a₄ = −1.453152027, a₅ = 1.061405429
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

        $p  = 0.3275911;
        $t  = 1 / ( 1 + $p * \abs($x) );

        $a₁ = 0.254829592;
        $a₂ = -0.284496736;
        $a₃ = 1.421413741;
        $a₄ = -1.453152027;
        $a₅ = 1.061405429;

        $error = 1 - ( $a₁ * $t + $a₂ * $t ** 2 + $a₃ * $t ** 3 + $a₄ * $t ** 4 + $a₅ * $t ** 5 ) * \exp(-\abs($x) ** 2);

        return ( $x > 0 ) ? $error : -$error;
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
     * Complementary error function (erfc)
     * erfc(x) ≡ 1 - erf(x)
     *
     * @param  number $x
     *
     * @return float
     */
    public static function complementaryErrorFunction($x): float
    {
        return 1 - self::erf($x);
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
        return 1 - self::erf($x);
    }

    /**
     * Upper Incomplete Gamma Function - Γ(s,x)
     * https://en.wikipedia.org/wiki/Incomplete_gamma_function
     *
     * @param float $s shape parameter > 0
     * @param float $x lower limit of integration
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
        return self::gamma($s) - self::lowerIncompleteGamma($s, $x);
    }

    /**
     * Lower incomplete gamma function - γ(s,t)
     * https://en.wikipedia.org/wiki/Incomplete_gamma_function#Lower_incomplete_Gamma_function
     *
     * This function is exact for all integer multiples of .5
     * using the recurrence relation: γ⟮s+1,x⟯= s*γ⟮s,x⟯-xˢ*e⁻ˣ
     *
     * The function can be evaluated at other points using the series:
     *              zˢ     /      x          x²             x³            \
     * γ(s,x) =  -------- | 1 + ----- + ---------- + --------------- + ... |
     *            s * eˣ   \     s+1    (s+1)(s+2)   (s+1)(s+2)(s+3)      /
     *
     * @param float $s > 0
     * @param float $x ≥ 0
     *
     * @return float
     */
    public static function lowerIncompleteGamma(float $s, float $x): float
    {
        if ($x == 0) {
            return 0;
        }
        if ($s == 0) {
            return \NAN;
        }


        if ($s == 1) {
            return 1 - \exp(-1 * $x);
        }
        if ($s == .5) {
            $√π = \sqrt(\M_PI);
            $√x = \sqrt($x);
            return $√π * self::erf($√x);
        }
        if (\round($s * 2, 0) == $s * 2) {
            return ($s - 1) * self::lowerIncompleteGamma($s - 1, $x) - $x ** ($s - 1) * \exp(-1 * $x);
        }

        $tol       = .000000000001;
        $xˢ∕s∕eˣ   = $x ** $s / \exp($x) / $s;
        $sum       = 1;
        $fractions = [];
        $element   = 1 + $tol;

        while ($element > $tol) {
            $fractions[] = $x / ++$s;
            $element     = \array_product($fractions);
            $sum        += $element;
        }

        return $xˢ∕s∕eˣ * $sum;
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
        $Γ⟮s⟯    = self::gamma($s);

        return $γ⟮s、x⟯ / $Γ⟮s⟯;
    }

    /**
     * Incomplete Beta Function - B(x;a,b)
     *
     * Generalized form of the beta function
     * https://en.wikipedia.org/wiki/Beta_function#Incomplete_beta_function
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
     * A continuous fraction is used to evaluate I
     *
     *                      /      α₁       \
     *              xᵃyᵇ   |  -------------- |
     * Iₓ(a, b) = -------- |    β₁ +   α₂    |
     *             B(a,b)  |         ------  |
     *                      \        β₂ + … /
     *
     *                 (a + m - 1) * (a + b + m -1) * m * (b - m) * x²
     * α₁ = 1, αm+1 = -------------------------------------------------
     *                                (a + 2m - 1)²
     *
     *             m * (b - m) * x      (a + m) * (a - (a + b) * x + 1 + m * (2 - x))
     * βm+1 = m + ------------------ + -----------------------------------------------
     *              a + 2 * m - 1                      a + 2 * m + 1
     *
     * This algorithm is valid when both a and b are greater than 1
     *
     * @param int   $m the number of α and β parameters to calculate
     * @param float $x Upper limit of the integration 0 ≦ x ≦ 1
     * @param float $a Shape parameter a > 1
     * @param float $b Shape parameter b > 1
     *
     * @return float
     *
     * @throws Exception\BadDataException
     * @throws Exception\BadParameterException
     * @throws Exception\OutOfBoundsException
     */
    private static function iBetaCF(int $m, float $x, float $a, float $b): float
    {
        $limits = [
        'x'  => '[0, 1]',
        'a'  => '(1,∞)',
        'b'  => '(1,∞)',
        ];
        Support::checkLimits($limits, ['x' => $x, 'a' => $a, 'b' => $b]);

        $beta     = self::beta($a, $b);
        $constant = $x ** $a * (1 - $x) ** $b / $beta;

        $α_array = [];
        $β_array = [];

        for ($i = 0; $i < $m; $i++) {
            if ($i == 0) {
                $α = 1;
            } else {
                $α = ($a + $i - 1) * ($a + $b + $i - 1) * $i * ($b - $i) * $x ** 2 / ($a + 2 * $i - 1) ** 2;
            }
            $β₁        = $i + $i * ($b - $i) * $x / ($a + 2 * $i - 1);
            $β₂        = ($a + $i) * ($a - ($a + $b) * $x + 1 + $i * (2 - $x)) / ($a + 2 * $i + 1);
            $β         = $β₁ + $β₂;
            $α_array[] = $α;
            $β_array[] = $β;
        }

        $fraction_array = [];
        for ($i = $m - 1; $i >= 0; $i--) {
            if ($i == $m - 1) {
                $fraction_array[$i] = $α_array[$i] / $β_array[$i];
            } else {
                $fraction_array[$i] = $α_array[$i] / ($β_array[$i] + $fraction_array[$i + 1]);
            }
        }

        return $constant * $fraction_array[0];
    }

    /**
     * Regularized incomplete beta function - Iₓ(a, b)
     *
     * https://en.wikipedia.org/wiki/Beta_function#Incomplete_beta_function
     *
     * This function looks at the values of x, a, and b, and determines which algorithm is best to calculate
     * the value of Iₓ(a, b)
     *
     * http://www.boost.org/doc/libs/1_35_0/libs/math/doc/sf_and_dist/html/math_toolkit/special/sf_beta/ibeta_function.html
     * https://github.com/boostorg/math/blob/develop/include/boost/math/special_functions/beta.hpp
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

        if ($x > .9 || $b > $a && $x > .5) {
            $y = 1 - $x;
            return 1 - self::regularizedIncompleteBeta($y, $b, $a);
        }
        if ($a > 1 && $b > 1) {
            // Tolerance on evaluating the continued fraction.
            $tol = .000000000000001;
            $dif = $tol + 1; // Initialize

            // We will calculate the continuous fraction with a minimum depth of 10.
            $m = 10;  // Counter
            $I = 0;
            do {
                $I_new = self::iBetaCF($m, $x, $a, $b);
                if ($m > 10) {
                    $dif = \abs(($I - $I_new) / $I_new);
                }
                $I = $I_new;
                $m++;
            } while ($dif > $tol);
            return $I;
        } else {
            if ($a <= 1) {
                // We shift a up by one, to the region that the continuous fraction works best.
                $offset = $x ** $a * (1 - $x) ** $b / $a / self::beta($a, $b);
                return self::regularizedIncompleteBeta($x, $a + 1, $b) + $offset;
            } else { // $b <= 1
                // We shift b up by one, to the region that the continuous fraction works best.
                $offset = $x ** $a * (1 - $x) ** $b / $b / self::beta($a, $b);
                return self::regularizedIncompleteBeta($x, $a, $b + 1) - $offset;
            }
        }
    }

    /**
     * Generalized Hypergeometric Function
     *
     * https://en.wikipedia.org/wiki/Generalized_hypergeometric_function
     *
     *                                       ∞
     *                                      ____
     *                                      \     ∏ap⁽ⁿ⁾ * zⁿ
     * pFq(a₁,a₂,...ap;b₁,b₂,...,bq;z)=      >    ------------
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

        $a       = \array_slice($params, 0, $p);
        $b       = \array_slice($params, $p, $q);
        $z       = $params[$n - 1];
        $tol     = .00000001;
        $n       = 1;
        $sum     = 0;
        $product = 1;

        do {
            $sum     += $product;
            $a_sum    = \array_product(Single::add($a, $n - 1));
            $b_sum    = \array_product(Single::add($b, $n - 1));
            $product *= $a_sum * $z / $b_sum / $n;
            $n++;
        } while ($product / $sum > $tol);

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
     * @param  float[] $𝐳
     *
     * @return array
     */
    public static function softmax(array $𝐳): array
    {
        $ℯ = \M_E;

        $∑ᴷℯᶻᵢ = \array_sum(\array_map(
            function ($z) use ($ℯ) {
                return $ℯ ** $z;
            },
            $𝐳
        ));

        $σ⟮𝐳⟯ⱼ = \array_map(
            function ($z) use ($ℯ, $∑ᴷℯᶻᵢ) {
                return ($ℯ ** $z) / $∑ᴷℯᶻᵢ;
            },
            $𝐳
        );

        return $σ⟮𝐳⟯ⱼ;
    }
}
