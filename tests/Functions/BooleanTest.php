<?php

namespace MathPHP\Tests\Functions;

use MathPHP\Functions\Support;
use MathPHP\Exception;

class BooleanTest extends \PHPUnit_Framework_TestCase
{
    public function testBooleanAdd()
    {
    }

    public function dataProviderForBooleanADD()
    {
        return [
            [
                1, 1, false, 2
            ],
            [
                1, -1, true, 0
            ],
            [
                \PHP_INT_MAX, 1, false, \PHP_INT_MIN
            ],
        ];
    }
}
