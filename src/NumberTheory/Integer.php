<?php
namespace MathPHP\NumberTheory;

use MathPHP\Algebra;

class Integer
{
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
                    return true;
                }
            }
        }

        return false;
    }
}
