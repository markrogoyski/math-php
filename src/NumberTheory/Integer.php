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
     *
     * @see    https://en.wikipedia.org/wiki/Perfect_number
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

        return $n === self::aliquotSum($n);
    }

    /**
     * Detect if an integer is a deficient (defective) number.
     * A deficient number is a positive integer that is greater than the sum of its proper divisors,
     * that is, the sum of its positive divisors excluding the number itself
     *
     * @see    https://en.wikipedia.org/wiki/Deficient_number
     *
     * @param  int  $n
     *
     * @return bool true if n is deficient; false otherwise
     */
    public static function isDeficientNumber(int $n): bool
    {
        if ($n < 1) {
            return false;
        }
        return $n > self::aliquotSum($n);
    }

    /**
     * Detect if an integer is an abundant (excessive) number.
     * An abundant number is a positive integer that is less than the sum of its proper divisors,
     * that is, the sum of its positive divisors excluding the number itself
     *
     * @see    https://en.wikipedia.org/wiki/Abundant_number
     *
     * @param  int  $n
     *
     * @return bool true if n is abundant; false otherwise
     */
    public static function isAbundantNumber(int $n): bool
    {
        if ($n < 1) {
            return false;
        }
        return $n < self::aliquotSum($n);
    }

    /**
     * Aliquot sum
     * The aliquot sum of a positive integer is the sum of all proper divisors of n,
     * that is, the sum of its positive divisors excluding the number itself
     *
     * Notation:
     * s(n)
     *
     * Formula:
     * σ(n) − n
     *
     * @see    https://en.wikipedia.org/wiki/Aliquot_sum
     *
     * @param  int $n
     *
     * @return int aliquot sum of n
     *
     * @throws Exception\OutOfBoundsException if n is < 1.
     */
    public static function aliquotSum(int $n): int
    {
        return self::sumOfDivisors($n) - $n;
    }

    /**
     * Radical (or squarefree kernel)
     * The radical of a positive integer is the product of its distinct prime factors.
     *
     * @see    https://en.wikipedia.org/wiki/Radical_of_an_integer
     * @see    https://oeis.org/A007947
     *
     * @param  int $n
     *
     * @return int the radical of n
     *
     * @throws Exception\OutOfBoundsException if n is < 1.
     */
    public static function radical(int $n): int
    {
        return array_product(array_unique(self::primeFactorization($n)));
    }

    /**
     * Totient function (Euler's totient and Jordan's totient)
     * The number of k-tuples of positive integers that are all ≤ n that form a coprime (k+1)-tuple together with n.
     *
     * Notation:
     *    Jₖ(n)
     *
     *    (when k=1 - Euler's totient)
     *    ϕ(n)  φ(n)  phi(n)
     *
     * @see    https://en.wikipedia.org/wiki/Euler%27s_totient_function
     * @see    https://en.wikipedia.org/wiki/Jordan%27s_totient_function
     *
     * @param  int $n
     *
     * @return int number of k-tuples of positive integers ≤ n that form a coprime (k+1)-tuple with n
     */
    public static function totient(int $n, int $k = 1): int
    {
        $J = $n ** $k;
        $primes = array_unique(self::primeFactorization($n));
        foreach ($primes as $prime) {
            $J *= 1 - 1 / $prime ** $k;
        }
        return $J;
    }

    /**
     * Cototient
     * The number of positive integers ≤ n that have at least one prime factor in common with n.
     *
     * Algorithm:
     *    n - φ(n)
     *
     * @see    https://en.wikipedia.org/wiki/Euler's_totient_function
     *
     * @param  int $n
     *
     * @return int number of positive integers ≤ that have at least one prime factor in common with n
     */
    public static function cototient(int $n): int
    {
        return $n - self::totient($n);
    }

    /**
     * Reduced totient function (Carmichael function, least universal exponent function)
     * Return the exponent of the multiplicative group of integers modulo n.
     *
     * Notation:
     *    λ(n)
     *
     * @param  int $n
     * @return int the exponent of the multiplicative group of integers modulo n
     */
    public static function reducedTotient(int $n): int
    {
        $primes = array_count_values(self::primeFactorization($n));
        $λ = 1;
        if (isset($primes[2]) && $primes[2] > 2) {
            --$primes[2];
        }
        foreach ($primes as $prime => $exponent) {
            $λ = Algebra::lcm($λ, $prime ** ($exponent - 1) * ($prime - 1));
        }
        return $λ;
    }

    /**
     * Möbius function
     * The sum of the primitive nᵗʰ roots of unity.
     *
     * Notation
     *    μ(n)  mu(n)
     *
     * Algorithm:
     *    - if n is not squarefree, return 0
     *    - return (-1)ᵏ, where k is the number of primes in n
     *
     * @see    https://en.wikipedia.org/wiki/M%C3%B6bius_function
     * @see    https://oeis.org/A008683
     *
     * @param  int $n
     *
     * @return int 0 if n is not squarefree; 1 if n has an even number of prime factors; -1 if n has an odd number of prime factors
     *
     * @throws Exception\OutOfBoundsException if n is < 1.
     */
    public static function mobius(int $n): int
    {
        $factors = self::primeFactorization($n);
        if ($factors !== array_unique($factors)) {
            return 0;
        }
        return (-1) ** count($factors);
    }

    /**
     * Squarefree integer
     * A squarefree integer is an integer which is divisble by no square number other than 1.
     * It is equal to its radical (squarefree kernel).
     *
     * @see    https://en.wikipedia.org/wiki/Square-free_integer
     * @see    https://oeis.org/A005117
     *
     * @param  int $n
     * @return bool true if n is a squarefree integer; false otherwise
     */
    public static function isSquarefreeInteger(int $n): bool
    {
        if ($n < 1) {
            return false;
        }
        return $n === self::radical($n);
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
                $mᵏ = $m ** $k;
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
     * @throws Exception\OutOfBoundsException if n is < 1.
     */
    public static function primeFactorization(int $n): array
    {
        if ($n < 1) {
            throw new Exception\OutOfBoundsException("n must be ≥ 1. ($n provided)");
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
     * Number-of-divisors function
     *
     * Notations:
     * d(n)  v(n)  τ(n)  tau(n)  sigma_0(n)  σ₀(n)
     *
     * @see    https://en.wikipedia.org/wiki/Divisor_function
     * @see    https://oeis.org/A000005
     *
     * @param  int $n
     *
     * @return int number of divisors
     *
     * @throws Exception\OutOfBoundsException if n is < 1.
     */
    public static function numberOfDivisors(int $n): int
    {
        $factorization = self::primeFactorization($n);
        $product = 1;
        foreach (array_count_values($factorization) as $factor => $exponent) {
            $product *= $exponent + 1;
        }
        return $product;
    }

    /**
     * Sum-of-divisors function
     *
     * Notations:
     * σ(n)  σ₁(n)  sigma(n)  sigma_1(n)
     *
     * @see    https://en.wikipedia.org/wiki/Divisor_function
     * @see    https://oeis.org/A000203
     *
     * @param  int $n
     *
     * @return int sum of divisors
     *
     * @throws Exception\OutOfBoundsException if n is < 1.
     */
    public static function sumOfDivisors(int $n): int
    {
        $factorization = self::primeFactorization($n);
        $product = 1;
        foreach (array_count_values($factorization) as $factor => $exponent) {
            $sum = 1 + $factor;
            for ($i = 2; $i <= $exponent; $i++) {
                $sum += pow($factor, $i);
            }
            $product *= $sum;
        }
        return $product;
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
