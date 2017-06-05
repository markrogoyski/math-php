<?php

namespace MathPHP\Tests\Number;

class BigIntTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @testCase     addition of two BigInts returns the expeced result
     * @dataProvider dataProviderForAdd
     * @param        array  $complex1
     * @param        array  $complex2
     * @param        array  $expected
     */
    public function testAdd(array $a, array $b, array $e)
    {
        $A = new BigInt($a[0], $a[1]);
        $B = new BigInt($b[0], $b[1]);
        $C = $A->add($B);
        $expected = new BigInt($e[0], $e[1]);
        $this->assertTrue($C->equals($expected));
    }

    public function dataProviderForAdd()
    {
        return [
            [ // 1 + 1 = 2
                [1, 0],
                [1, 0],
                [2, 0],
            ],
            [ // 100 + 200 = 300
                [100, 0],
                [200, 0],
                [300, 0],
            ],
            [ // Max int plus one
                [\PHP_INT_MAX, 0],
                [1, 0],
                [0, 1],
            ],
            [ // Max int times two
                [\PHP_INT_MAX, 0],
                [\PHP_INT_MAX, 0],
                [\PHP_INT_MAX - 1, 1],
            ],
        ];
    }
