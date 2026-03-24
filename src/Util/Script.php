<?php

namespace MathPHP\Util;

class Script
{
    /**
     * @var list<string>
     */
    private static $subscripts = ['₀', '₁', '₂', '₃', '₄', '₅', '₆', '₇', '₈', '₉'];

    /**
     * @var list<string>
     */
    private static $superscripts = ['⁰', '¹', '²', '³', '⁴', '⁵', '⁶', '⁷', '⁸', '⁹'];

    /**
     * @param int $n
     * @return string
     */
    public static function getSubscript(int $n): string
    {
        return self::get($n, self::$subscripts);
    }

    /**
     * @param int $n
     * @return string
     */
    public static function getSuperscript(int $n): string
    {
        return self::get($n, self::$superscripts);
    }

    /**
     * @param int $n
     * @param list<string> $digits
     * @return string
     */
    private static function get(int $n, array $digits): string
    {
        return \implode(
            '',
            \array_map(
                static function ($c) use ($digits) {
                    return $digits[$c];
                },
                \str_split((string)$n)
            )
        );
    }
}
