<?php

namespace Math\Functions;

/**
 * Arithmetic of functions. These functions return functions themselves.
 */
class Arithmetic
{
    /**
     * Adds any number of single variable (callback) functions {f(x)}. Returns
     * the sum as a callback function.
     *
     * @param callable ... $args Two or more single-variable callback functions
     *
     * @return callable          Sum of the input functions
     */
    public static function add(... $args)
    {
        // Validate input arguments
        self::validate($args);

        $sum = function ($x, ... $args)
        {
            $function = 0;
            foreach ($args as $arg) {
                $function += $arg($x);
            }
            return $function;
        };

        $functionKeys = [];
        for ($i = 0; $i < count($args); $i++) {
            ${'function' . $i} = $args[$i];
            $functionKeys['$function' . $i] = null;
        }
        $keys = array_keys($functionKeys);
        $expression = implode($keys, ', ');

        eval('$result = function($x) use (' . $expression . ', $sum) {
            return $sum($x, ' . $expression . ');
        };');

        return $result;
    }

    /**
     * Multiplies any number of single variable (callback) functions {f(x)}.
     * Returns the product as a callback function.
     *
     * @param callable ... $args Two or more single-variable callback functions
     *
     * @return callable          Product of the input functions
     */
    public static function multiply(... $args)
    {
        // Validate input arguments
        self::validate($args);

        $product = function ($x, ... $args)
        {
            $function = 1;
            foreach ($args as $arg) {
                $function *= $arg($x);
            }
            return $function;
        };

        $functionKeys = [];
        for ($i = 0; $i < count($args); $i++) {
            ${'function' . $i} = $args[$i];
            $functionKeys['$function' . $i] = null;
        }
        $keys = array_keys($functionKeys);
        $expression = implode($keys, ', ');

        eval('$result = function($x) use (' . $expression . ', $product) {
            return $product($x, ' . $expression . ');
        };');

        return $result;
    }

    /**
     * Verifies that each input is a callback functions.
     *
     * @param callable ... $args Two or more single-variable callback functions
     *
     * @throws Exception if any of our inputs are not callback functions
     */
    public static function validate($args)
    {
        foreach ($args as $arg) {
            if (!is_callable($arg)) {
                throw new \Exception("Every argument in your input needs to be
                                      a (callback) function.");
              }
        }
    }
}
