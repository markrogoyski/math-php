<?php
namespace MathPHP\NumberTheory;

use MathPHP\Algebra;
use MathPHP\Exception;

class Integer
{
    /**
     * Detect if an integer is a perfect number.
     * A perfect number is a positive integer that is equal to the sum of its proper positive divisors,
     * that is, the sum of its positive divisors excluding the number itself
     * @see https://en.wikipedia.org/wiki/Perfect_number
     *
     * @param  int $n
     *
     * @return bool
     */
    public static function isPerfectNumber(int $n): bool
    {
        if ($n <= 1) {
            return false;
        }

        $∑  = 1;
        $√n = sqrt($n);

        for ($i = 2; $i <= $√n; $i++) {
            if ($n % $i === 0) {
                $∑ += $i + ($n / $i);
            }
        }

        return $∑ === $n;
    }

    /**
     * Detect if an integer is a perfect power.
     * A perfect power is a positive integer that can be expressed as an integer power of another positive integer.
     * If n is a perfect power, then exists m > 1 and k > 1 such that mᵏ = n.
     * https://en.wikipedia.org/wiki/Perfect_power
     *
     * Algorithm:
     *  For each divisor of n (as m), consider all possible values of k from 2 to log₂n.
     *   - If mᵏ = n, return true
     *   - If exhaust all possible mᵏ combinations, return false.
     *
     * @param  int $n
     *
     * @return bool True if n is a perfect power; false otherwise.
     */
    public static function isPerfectPower(int $n): bool
    {
        if (empty(self::perfectPower($n))) {
            return false;
        }
        return true;
    }

    /**
     * If n is a perfect power, compute an m and k such that mᵏ = n.
     * A perfect power is a positive integer that can be expressed as an integer power of another positive integer.
     * If n is a perfect power, then exists m > 1 and k > 1 such that mᵏ = n.
     * https://en.wikipedia.org/wiki/Perfect_power
     *
     * Algorithm:
     *  For each divisor of n (as m), consider all possible values of k from 2 to log₂n.
     *   - If mᵏ = n, return m and k
     *   - If exhaust all possible mᵏ combinations, return empty array.
     *
     * An integer n could have multiple perfect power scenarios.
     * Only one is returned.
     *
     * @param  int $n
     *
     * @return array [m, k]
     */
    public static function perfectPower(int $n): array
    {
        $√n = sqrt($n);
        $ms = array_filter(
            Algebra::factors($n),
            function ($m) use ($√n) {
                return ($m > 1 && $m <= $√n);
            }
        );
        $max_k = ceil(log($n, 2));

        foreach ($ms as $m) {
            foreach (range(2, $max_k) as $k) {
                $mᵏ = $m**$k;
                if ($mᵏ == $n) {
                    return [$m, $k];
                }
            }
        }

        return [];
    }

    /**
     * Prime factorization
     * The prime factors of an integer.
     * https://en.wikipedia.org/wiki/Prime_factor
     *
     * @todo   Use a better algorithm.
     *
     * @param  int $n
     *
     * @return array of prime factors
     *
     * @throws Exception\OutOfBoundsException if n is < 2.
     */
    public static function primeFactorization(int $n): array
    {
        if ($n < 2) {
            throw new Exception\OutOfBoundsException("n must be ≥ 2. ($n provided)");
        }

        $remainder = $n;
        $factors   = [];
        $divisor   = 2;

        while ($remainder > 1) {
            while ($remainder % $divisor === 0) {
                $factors[] = $divisor;
                $remainder = intdiv($remainder, $divisor);
            }
            $divisor++;
        }

        return $factors;
    }

    /**
     * Coprime (relatively prime, mututally prime)
     * Two integers a and b are said to be coprime if the only positive integer that divides both of them is 1.
     * That is, the only common positive factor of the two numbers is 1.
     * This is equivalent to their greatest common divisor being 1.
     * https://en.wikipedia.org/wiki/Coprime_integers
     *
     * @param  int $a
     * @param  int $b
     *
     * @return bool true if a and b are coprime; false otherwise
     */
    public static function coprime(int $a, int $b): bool
    {
        return (Algebra::gcd($a, $b) === 1);
    }

    /**
     * Odd number
     *
     * @param  int $x
     *
     * @return bool true if x is odd; false otherwise
     */
    public static function isOdd(int $x): bool
    {
        return (abs($x) % 2) === 1;
    }

    /**
     * Even number
     *
     * @param  int $x
     *
     * @return bool true if x is even; false otherwise
     */
    public static function isEven(int $x): bool
    {
        return (abs($x) % 2) === 0;
    }
}
