<?php
namespace MathPHP;

class Arithmetic
{
    /**
     * Cube root ³√x
     * This function is necessary because pow($x, 1/3) returns NAN for negative values.
     * PHP does not have the cbrt built-in function.
     *
     * @param  float $x
     *
     * @return float
     */
    public static function cubeRoot(float $x): float
    {
        if ($x >= 0) {
            return pow($x, 1/3);
        }

        return -pow(abs($x), 1/3);
    }

    /**
     * Digit sum
     * Sum of all an integer's digits.
     * https://en.wikipedia.org/wiki/Digit_sum
     *
     * log x  1
     *   ∑    -- (x mod bⁿ⁺¹ - x mod bⁿ)
     *  ⁿ⁼⁰   bⁿ
     *
     * Example (base 10): 5031   = 5 + 0 + 3 + 1 = 9
     * Example (base 2):  0b1010 = 1 + 0 + 1 + 0 = 2
     *
     * @param  int $x
     * @param  int $b Base (Default is base 10)
     *
     * @return int
     */
    public static function digitSum(int $x, int $b = 10): int
    {
        $logx                        = log($x, $b);
        $∑1／bⁿ⟮x mod bⁿ⁺¹ − x mod bⁿ⟯ = 0;

        for ($n = 0; $n <= $logx; $n++) {
            $∑1／bⁿ⟮x mod bⁿ⁺¹ − x mod bⁿ⟯ += (($x % pow($b, $n+1)) - ($x % $b**$n)) / ($b**$n);
        }

        return $∑1／bⁿ⟮x mod bⁿ⁺¹ − x mod bⁿ⟯;
    }

    /**
     * Digital root (iterated digit sum, repeated digital sum)
     * The single digit value obtained by an iterative process of summing digits,
     * on each iteration using the result from the previous iteration to compute a digit sum.
     * The process continues until a single-digit number is reached.
     * https://en.wikipedia.org/wiki/Digital_root
     *
     * Example: 65,536 is 7, because 6 + 5 + 5 + 3 + 6 = 25 and 2 + 5 = 7
     *
     * @param  int $x
     *
     * @return int
     */
    public static function digitalRoot(int $x): int
    {
        $root = $x;

        while ($root >= 10) {
            $root = self::digitSum($root);
        }

        return $root;
    }

    /**
     * Test if two numbers are almost equal, within a tolerance ε
     *
     * @param float $x
     * @param float $y
     * @param float $ε tolerance
     *
     * @return bool true if the numbers are equal within a tolerance; false if they are not
     */
    public static function almostEqual(float $x, float $y, $ε = 0.000000000001): bool
    {
        return abs($x - $y) <= $ε;
    }

    /**
     * Returns the magnitude value with the sign of the sign number
     *
     * @param float $magnitude
     * @param float $sign
     *
     * @return float $magnitude with the sign of $sign
     */
    public static function copySign(float $magnitude, float $sign): float
    {
        return $sign >= 0
            ? abs($magnitude)
            : -abs($magnitude);
    }
}
