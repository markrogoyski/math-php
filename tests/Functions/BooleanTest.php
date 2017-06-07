<?php

namespace MathPHP\Tests\Functions;

use MathPHP\Functions\Boolean;

class BooleanTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForBooleanAdd
     */
    public function testBooleanAdd(int $a, int $b, array $e)
    {
        $results = Boolean::bitwiseAdd($a, $b);
        $expected = ['overflow'=> $e[0], 'value'=>$e[1]];
        $this->assertEquals($expected, $results);
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
            [
                -1, -1, [true, -2]
            ],
            [
                \PHP_INT_MIN, \PHP_INT_MIN, [true, 0]
            ],
            [
                \PHP_INT_MIN, \PHP_INT_MAX, [false, -1]
            ],
            [
                0, 0, [false, 0]
            ],
        ];
    }
}
