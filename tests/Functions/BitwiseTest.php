<?php

namespace MathPHP\Tests\Functions;

use MathPHP\Functions\Bitwise;

class BitwiseTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test         add
     * @dataProvider dataProviderForBitwiseAdd
     * @param        int   $a
     * @param        int   $b
     * @param        array $expected
     */
    public function testBitwiseAdd(int $a, int $b, array $expected)
    {
        // When
        $sum = Bitwise::add($a, $b);

        // Then
        $this->assertEquals($expected, $sum);
    }

    public function dataProviderForBitwiseAdd(): array
    {
        return [
            // Basic positive additions (no overflow)
            [
                1, 1, [
                    'overflow' => false,
                    'value'    => 2,
                ],
            ],
            [
                0, 0, [
                    'overflow' => false,
                    'value'    => 0,
                ],
            ],
            [
                1, 0, [
                    'overflow' => false,
                    'value'    => 1,
                ],
            ],
            [
                100, 200, [
                    'overflow' => false,
                    'value'    => 300,
                ],
            ],
            [
                1000, 2000, [
                    'overflow' => false,
                    'value'    => 3000,
                ],
            ],

            // Positive + Negative (mixed signs)
            [
                1, -1, [
                    'overflow' => true,
                    'value'    => 0,
                ],
            ],
            [
                5, -3, [
                    'overflow' => true,
                    'value'    => 2,
                ],
            ],
            [
                -5, 3, [
                    'overflow' => false,
                    'value'    => -2,
                ],
            ],
            [
                100, -50, [
                    'overflow' => true,
                    'value'    => 50,
                ],
            ],
            [
                -100, 50, [
                    'overflow' => false,
                    'value'    => -50,
                ],
            ],
            [
                200, -100, [
                    'overflow' => true,
                    'value'    => 100,
                ],
            ],

            // Negative additions
            [
                -1, -1, [
                    'overflow' => true,
                    'value'    => -2,
                ],
            ],
            [
                -10, -20, [
                    'overflow' => true,
                    'value'    => -30,
                ],
            ],
            [
                -100, -100, [
                    'overflow' => true,
                    'value'    => -200,
                ],
            ],

            // PHP_INT_MAX boundary cases
            [
                \PHP_INT_MAX, 1, [
                    'overflow' => false,
                    'value'    => \PHP_INT_MIN,
                ],
            ],
            [
                \PHP_INT_MAX, \PHP_INT_MAX, [
                    'overflow' => false,
                    'value'    => -2,
                ],
            ],
            [
                \PHP_INT_MAX, 0, [
                    'overflow' => false,
                    'value'    => \PHP_INT_MAX,
                ],
            ],
            [
                \PHP_INT_MAX, 2, [
                    'overflow' => false,
                    'value'    => \PHP_INT_MIN + 1,
                ],
            ],
            [
                \PHP_INT_MAX, -1, [
                    'overflow' => true,
                    'value'    => \PHP_INT_MAX - 1,
                ],
            ],

            // PHP_INT_MIN boundary cases
            [
                \PHP_INT_MIN, \PHP_INT_MIN, [
                    'overflow' => true,
                    'value'    => 0,
                ],
            ],
            [
                \PHP_INT_MIN, \PHP_INT_MAX, [
                    'overflow' => false,
                    'value'    => -1,
                ],
            ],
            [
                \PHP_INT_MIN, 0, [
                    'overflow' => false,
                    'value'    => \PHP_INT_MIN,
                ],
            ],
            [
                \PHP_INT_MIN, 1, [
                    'overflow' => false,
                    'value'    => \PHP_INT_MIN + 1,
                ],
            ],
            [
                \PHP_INT_MIN, -1, [
                    'overflow' => true,
                    'value'    => \PHP_INT_MAX,
                ],
            ],
            [
                \PHP_INT_MIN, -2, [
                    'overflow' => true,
                    'value'    => \PHP_INT_MAX - 1,
                ],
            ],

            // Symmetric test cases
            [
                \PHP_INT_MAX, \PHP_INT_MIN, [
                    'overflow' => false,
                    'value'    => -1,
                ],
            ],
            [
                \PHP_INT_MIN, \PHP_INT_MAX, [
                    'overflow' => false,
                    'value'    => -1,
                ],
            ],

            // More small value combinations
            [
                7, 11, [
                    'overflow' => false,
                    'value'    => 18,
                ],
            ],
            [
                11, 7, [
                    'overflow' => false,
                    'value'    => 18,
                ],
            ],
            [
                -7, -11, [
                    'overflow' => true,
                    'value'    => -18,
                ],
            ],
            [
                -11, -7, [
                    'overflow' => true,
                    'value'    => -18,
                ],
            ],
            [
                -15, 15, [
                    'overflow' => true,
                    'value'    => 0,
                ],
            ],
            [
                15, -15, [
                    'overflow' => true,
                    'value'    => 0,
                ],
            ],

            // Additional comprehensive boundary tests
            [
                100, 100, [
                    'overflow' => false,
                    'value'    => 200,
                ],
            ],
            [
                -100, -100, [
                    'overflow' => true,
                    'value'    => -200,
                ],
            ],
            [
                50, -50, [
                    'overflow' => true,
                    'value'    => 0,
                ],
            ],

        ];
    }
}
