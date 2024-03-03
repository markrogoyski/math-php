<?php

namespace MathPHP\Util;

/**
 * Based on IterTools PHP's IteratorFactory.
 * @see https://github.com/markrogoyski/itertools-php
 * @see https://github.com/markrogoyski/itertools-php/blob/main/src/Util/NoValueMonad.php
 */
class NoValueMonad
{
    /**
     * @var self|null
     */
    private static $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }
}
