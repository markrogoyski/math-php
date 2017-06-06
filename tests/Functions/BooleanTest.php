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
        $ezpected = ['overflow'=> $e[0], 'value'=>$e[1]];
        $this->assertEquals($expected, $results);
    }

    public function dataProviderForBooleanAdd()
    {
        return [
            [
                0b01, 0b01, [false, 0b10]
            ],
            [
                0b1, -1, [true, 0]
            ],
            [
                \PHP_INT_MAX, 0b1, [false, \PHP_INT_MIN]
            ],
            [
                -1, -1, [true, -2]
            ],
        ];
    }
}
