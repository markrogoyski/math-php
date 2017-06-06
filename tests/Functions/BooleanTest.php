<?php

namespace MathPHP\Tests\Functions;

use MathPHP\Functions\Boolean;

class BooleanTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForBooleanAdd
     */
    public function testBooleanAdd(int $a, int $b, array $expected)
    {
        $results = Boolean::booleanAdd($a, $b);
    }

    public function dataProviderForBooleanAdd()
    {
        return [
            [
                1, 1, [false, 2]
            ],
            [
                1, -1, [true, 0]
            ],
            [
                \PHP_INT_MAX, 1, [false, \PHP_INT_MIN]
            ],
        ];
    }
}
